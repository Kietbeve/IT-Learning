<?php

namespace Modules\Payment\database\seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class NewPaymentSeeder extends Seeder
{
    public function run(): void
    {
        $contributor = User::role('contributor')->first();
        $user = User::role('user')->first();

        if (! $contributor || ! $user) {
            return;
        }

        $documents = DB::table('documents')->get();
        if ($documents->isEmpty()) {
            return;
        }

        foreach ($documents as $doc) {
            $version = DB::table('document_versions')->where('id', $doc->current_version_id)->first();
            if ($version && $version->price > 0) {
                $productId = DB::table('products')->insertGetId([
                    'document_id' => $doc->id,
                    'name' => 'Sản phẩm '.$version->title,
                    'price' => $version->price,
                    'sale_price' => null,
                    'is_active' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                // Order 1
                $orderId = DB::table('orders')->insertGetId([
                    'order_code' => 'ORD'.rand(100000, 999999),
                    'order_type' => 'document',
                    'user_id' => $user->id,
                    'total_amount' => $version->price,
                    'payment_status' => 'paid',
                    'order_status' => 'completed',
                    'paid_at' => Carbon::now()->subDays(rand(1, 5)),
                    'completed_at' => Carbon::now()->subDays(rand(1, 5)),
                    'created_at' => Carbon::now()->subDays(rand(1, 5)),
                    'updated_at' => Carbon::now()->subDays(rand(1, 5)),
                ]);

                DB::table('order_items')->insert([
                    'order_id' => $orderId,
                    'product_id' => $productId,
                    'document_id' => $doc->id,
                    'document_title_snapshot' => $version->title,
                    'unit_price' => $version->price,
                    'quantity' => 1,
                    'subtotal' => $version->price,
                    'contributor_amount' => $version->price * 0.7,
                    'platform_amount' => $version->price * 0.3,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                DB::table('wallet_transactions')->insert([
                    'user_id' => $contributor->id,
                    'type' => 'deposit',
                    'amount' => $version->price * 0.7,
                    'balance_before' => 0,
                    'balance_after' => $version->price * 0.7,
                    'reference_type' => 'order',
                    'reference_id' => $orderId,
                    'note' => 'Nhận tiền chia sẻ tài liệu '.$version->title,
                    'created_at' => Carbon::now(),
                ]);
            }
        }

        DB::table('payout_requests')->insert([
            'user_id' => $contributor->id,
            'amount' => 50000,
            'bank_name' => 'Vietcombank',
            'bank_account_number' => '123456789',
            'bank_account_name' => 'NGUYEN VAN A',
            'status' => 'pending',
            'note' => 'Rút tiền lần 1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
