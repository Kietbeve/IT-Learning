<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Modules\Auth\Models\User;

echo "🚀 Seeding admin dashboard with fake data...\n\n";

echo "1️⃣ Updating users with contributor balance...\n";
$users = User::take(15)->get();
foreach ($users as $user) {
    $balance = rand(50000, 5000000);
    $user->update(['contributor_balance' => $balance]);
}
echo "   ✅ Updated {$users->count()} users\n\n";

echo "2️⃣ Creating wallet transactions (30 days)...\n";
$activeUsers = User::whereNotNull('contributor_balance')->take(10)->get();
$transactions = [];
$startDate = now()->subDays(30);

for ($day = 0; $day <= 30; $day++) {
    $date = $startDate->copy()->addDays($day);
    $transactionsPerDay = rand(2, 8);
    
    for ($i = 0; $i < $transactionsPerDay; $i++) {
        $user = $activeUsers->random();
        $amount = rand(10000, 500000);
        $type = rand(1, 10) > 2 ? 'purchase' : 'subscription';
        
        $balanceBefore = rand(50000, 3000000);
        $balanceAfter = $balanceBefore + $amount;
        
        $transactions[] = [
            'user_id' => $user->id,
            'type' => $type,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'reference_type' => 'order_item',
            'reference_id' => rand(1, 100),
            'note' => 'Demo - ' . $type,
            'created_by' => $user->id,
            'created_at' => $date->copy()->addHours(rand(8, 20))->addMinutes(rand(0, 59)),
        ];
    }
}

foreach (array_chunk($transactions, 50) as $chunk) {
    DB::table('wallet_transactions')->insert($chunk);
}
echo "   ✅ Created " . count($transactions) . " transactions\n\n";

echo "3️⃣ Creating payout requests...\n";
$payoutUsers = User::whereNotNull('contributor_balance')
    ->where('contributor_balance', '>', 100000)
    ->take(8)
    ->get();

$payouts = [];
foreach ($payoutUsers as $index => $user) {
    $status = match($index % 4) {
        0, 1 => 'pending',
        2 => 'completed',
        3 => 'rejected',
    };
    
    $amount = rand(50000, min($user->contributor_balance, 2000000));
    
    $payouts[] = [
        'user_id' => $user->id,
        'amount' => $amount,
        'bank_name' => ['Vietcombank', 'Techcombank', 'MB Bank', 'ACB'][rand(0, 3)],
        'bank_account_number' => rand(1000000000, 9999999999),
        'bank_account_name' => $user->name,
        'status' => $status,
        'note' => $status === 'rejected' ? 'Demo - invalid bank info' : null,
        'processed_by' => $status !== 'pending' ? 1 : null,
        'processed_at' => $status !== 'pending' ? now()->subDays(rand(1, 10)) : null,
        'receipt_image' => $status === 'completed' ? 'demo/receipt.jpg' : null,
        'created_at' => now()->subDays(rand(1, 15)),
        'updated_at' => now(),
    ];
}

foreach ($payouts as $payout) {
    DB::table('payout_requests')->insert($payout);
}
echo "   ✅ Created " . count($payouts) . " payout requests\n\n";

echo "🎉 Done! Dashboard now has fake data for testing.\n";
