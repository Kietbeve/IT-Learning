<?php

namespace Modules\Payment\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Auth\Models\User;
use Modules\Payment\Models\WalletTransaction;
use Modules\Payment\Models\PayoutRequest;

class AdminDashboardSeeder extends Seeder
{
    public function run(): void
    {
        $this->createUsersWithBalance();
        $this->createWalletTransactions();
        $this->createPayoutRequests();
    }

    private function createUsersWithBalance(): void
    {
        $users = User::take(15)->get();

        foreach ($users as $index => $user) {
            $balance = rand(50000, 5000000);
            $user->update(['contributor_balance' => $balance]);
        }

        echo "✅ Updated " . $users->count() . " users with contributor balance\n";
    }

    private function createWalletTransactions(): void
    {
        $users = User::whereNotNull('contributor_balance')->take(10)->get();

        if ($users->isEmpty()) {
            echo "⚠️ No users with balance found\n";
            return;
        }

        $transactions = [];
        $startDate = now()->subDays(30);

        for ($day = 0; $day <= 30; $day++) {
            $date = $startDate->copy()->addDays($day);
            $transactionsPerDay = rand(1, 8);

            for ($i = 0; $i < $transactionsPerDay; $i++) {
                $user = $users->random();
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
                    'note' => 'Demo transaction - ' . $type,
                    'created_by' => $user->id,
                    'created_at' => $date->copy()->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                    'updated_at' => now(),
                ];
            }
        }

        foreach (array_chunk($transactions, 50) as $chunk) {
            DB::table('wallet_transactions')->insert($chunk);
        }

        echo "✅ Created " . count($transactions) . " wallet transactions over 30 days\n";
    }

    private function createPayoutRequests(): void
    {
        $users = User::whereNotNull('contributor_balance')
            ->where('contributor_balance', '>', 100000)
            ->take(8)
            ->get();

        if ($users->isEmpty()) {
            echo "⚠️ No users with sufficient balance found\n";
            return;
        }

        $payouts = [];

        foreach ($users as $index => $user) {
            $status = match($index % 4) {
                0, 1 => 'pending',
                2 => 'completed',
                3 => 'rejected',
            };

            $amount = rand(50000, min($user->contributor_balance, 2000000));

            $payout = [
                'user_id' => $user->id,
                'amount' => $amount,
                'bank_name' => ['Vietcombank', 'Techcombank', 'MB Bank', 'ACB', 'VPBank'][rand(0, 4)],
                'bank_account_number' => rand(1000000000, 9999999999),
                'bank_account_name' => $user->name,
                'status' => $status,
                'note' => $status === 'rejected' ? 'Demo rejection - invalid bank info' : null,
                'processed_by' => $status !== 'pending' ? 1 : null,
                'processed_at' => $status !== 'pending' ? now()->subDays(rand(1, 10)) : null,
                'receipt_image' => $status === 'completed' ? 'demo/receipt_' . rand(1, 100) . '.jpg' : null,
                'created_at' => now()->subDays(rand(1, 15)),
                'updated_at' => now(),
            ];

            $payouts[] = $payout;
        }

        foreach ($payouts as $payout) {
            DB::table('payout_requests')->insert($payout);
        }

        echo "✅ Created " . count($payouts) . " payout requests\n";
    }
}
