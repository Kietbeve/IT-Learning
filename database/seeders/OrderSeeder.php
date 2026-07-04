<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Payment\Models\Order;
use Modules\Payment\Models\OrderItem;
use Modules\Payment\Models\DocumentAccess;
use Modules\Document\Models\Document;
use Modules\Payment\Models\Product;
use Modules\Auth\Models\User;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('ğŸŒ± Seeding mock orders...');
        $this->command->newLine();

        DB::beginTransaction();

        try {
            $buyer = User::where('email', 'layacctaodi889@gmail.com')->first();
            if (!$buyer) {
                $buyer = User::create([
                    'name' => 'Háº£i ÄÄƒng B9 Pháº¡m Tráº§n',
                    'email' => 'layacctaodi889@gmail.com',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                ]);
            }

            $contributor1 = User::where('email', 'contributor1@example.com')->first();
            if (!$contributor1) {
                $contributor1 = User::create([
                    'name' => 'Nguyá»…n VÄƒn A',
                    'email' => 'contributor1@example.com',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                    'contributor_balance' => 500000,
                ]);
            }

            $contributor2 = User::where('email', 'contributor2@example.com')->first();
            if (!$contributor2) {
                $contributor2 = User::create([
                    'name' => 'Tráº§n Thá»‹ B',
                    'email' => 'contributor2@example.com',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                    'contributor_balance' => 300000,
                ]);
            }

            $vipUser = User::where('email', 'vipuser@example.com')->first();
            if (!$vipUser) {
                $vipUser = User::create([
                    'name' => 'VIP User Premium',
                    'email' => 'vipuser@example.com',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                    'vip_expires_at' => now()->addMonths(1),
                    'vip_download_quota' => 3,
                ]);
            }

                                    $products = \Modules\Payment\Models\Product::with('document.latestVersion')->where('is_active', true)->whereHas('document')->take(2)->get();
            if ($products->count() < 2) {
                $this->command->error('Not enough products seeded. Please run DocumentDatabaseSeeder first.');
                return;
            }
            $product1 = $products[0];
            $doc1 = $product1->document;
            $product2 = $products[1];
            $doc2 = $product2->document;
            $product3 = $products[0];
            $doc3 = $product3->document;
            $this->command->info('âœ… Users & Documents created');
            $this->command->newLine();

            $this->command->info('ğŸ“¦ Creating Order #1: Mua trá»±c tiáº¿p (Direct Purchase - Paid)');
            $order1 = Order::create([
                'order_code' => (string)(now()->timestamp . rand(1000, 9999)),
                'order_type' => 'document',
                'user_id' => $buyer->id,
                'total_amount' => 150000,
                'payment_status' => 'paid',
                'order_status' => 'completed',
                'paid_at' => now()->subDays(2),
                'completed_at' => now()->subDays(2),
                'download_token' => \Illuminate\Support\Str::random(64),
            ]);

            OrderItem::create([
                'order_id' => $order1->id,
                'product_id' => $product1->id,
                'document_id' => $doc1->id,
                'document_title_snapshot' => $doc1->title,
                'unit_price' => 150000,
                'quantity' => 1,
                'subtotal' => 150000,
                'contributor_amount' => 135000,
                'platform_amount' => 15000,
            ]);

            DocumentAccess::updateOrCreate(
                [
                    'user_id' => $buyer->id,
                    'document_id' => $doc1->id,
                ],
                [
                    'order_item_id' => $order1->items->first()->id,
                    'access_type' => 'purchased',
                ]
            );

            $this->command->info("   MÃ£ ÄH: #{$order1->order_code}");
            $this->command->info("   Loáº¡i: Mua trá»±c tiáº¿p (Blue badge)");
            $this->command->info("   NgÆ°á»i mua: {$buyer->name}");
            $this->command->info("   TÃ i liá»‡u: {$doc1->title}");
            $this->command->info("   NgÆ°á»i bÃ¡n: {$contributor1->name}");
            $this->command->info("   Tá»•ng tiá»n: 150,000Ä‘");
            $this->command->info("   Tráº¡ng thÃ¡i: ÄÃ£ thanh toÃ¡n");
            $this->command->newLine();

            $this->command->info('ğŸ“¦ Creating Order #2: Táº£i báº±ng VIP (VIP Download - Free)');
            $order2 = Order::create([
                'order_code' => (string)(now()->timestamp . rand(1000, 9999)),
                'order_type' => 'document',
                'user_id' => $vipUser->id,
                'total_amount' => 0,
                'payment_status' => 'paid',
                'order_status' => 'completed',
                'paid_at' => now()->subHours(5),
                'completed_at' => now()->subHours(5),
                'download_token' => \Illuminate\Support\Str::random(64),
            ]);

            OrderItem::create([
                'order_id' => $order2->id,
                'product_id' => $product2->id,
                'document_id' => $doc2->id,
                'document_title_snapshot' => $doc2->title,
                'unit_price' => 0,
                'quantity' => 1,
                'subtotal' => 0,
                'contributor_amount' => 0,
                'platform_amount' => 0,
            ]);

            DocumentAccess::updateOrCreate(
                [
                    'user_id' => $vipUser->id,
                    'document_id' => $doc2->id,
                ],
                [
                    'order_item_id' => $order2->items->first()->id,
                    'access_type' => 'vip',
                ]
            );

            $this->command->info("   Mã ĞH: #{$order2->order_code}");
            $this->command->info("   Lo?i: T?i b?ng VIP (Teal badge)");
            $this->command->info("   Ngu?i mua: {$vipUser->name}");
            $this->command->info("   Tài li?u: {$doc2->title}");
            $this->command->info("   Ngu?i bán: {$contributor2->name}");
            $this->command->info("   T?ng ti?n: 0d (VIP download)");
            $this->command->info("   Tr?ng thái: Ğã thanh toán");
            $this->command->newLine();

            $this->command->info('?? Creating Order #3: Ğang kı VIP (VIP Subscription)');
            $order3 = Order::create([
                'order_code' => (string)(now()->timestamp . rand(1000, 9999)),
                'order_type' => 'subscription',
                'subscription_package_key' => 'vip',
                'user_id' => $vipUser->id,
                'total_amount' => 99000,
                'payment_status' => 'paid',
                'order_status' => 'completed',
                'paid_at' => now()->subDays(7),
                'completed_at' => now()->subDays(7),
                'download_token' => \Illuminate\Support\Str::random(64),
            ]);

            $this->command->info("   Mã ĞH: #{$order3->order_code}");
            $this->command->info("   Lo?i: Ğang kı VIP (Purple badge)");
            $this->command->info("   Ngu?i mua: {$vipUser->name}");
            $this->command->info("   Gói: VIP 1 Tháng");
            $this->command->info("   T?ng ti?n: 99,000d");
            $this->command->info("   Tr?ng thái: Ğã thanh toán");
            $this->command->newLine();

            $this->command->info('?? Creating Order #4: Mua tr?c ti?p (Pending Payment)');
            $order4 = Order::create([
                'order_code' => (string)(now()->timestamp . rand(1000, 9999)),
                'order_type' => 'document',
                'user_id' => $buyer->id,
                'total_amount' => 250000,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'download_token' => \Illuminate\Support\Str::random(64),
            ]);

            OrderItem::create([
                'order_id' => $order4->id,
                'product_id' => $product3->id,
                'document_id' => $doc3->id,
                'document_title_snapshot' => $doc3->title,
                'unit_price' => 250000,
                'quantity' => 1,
                'subtotal' => 250000,
                'contributor_amount' => 225000,
                'platform_amount' => 25000,
            ]);

            $this->command->info("   Mã ĞH: #{$order4->order_code}");
            $this->command->info("   Lo?i: Mua tr?c ti?p (Blue badge)");
            $this->command->info("   Ngu?i mua: {$buyer->name}");
            $this->command->info("   Tài li?u: {$doc3->title}");
            $this->command->info("   Ngu?i bán: {$contributor1->name}");
            $this->command->info("   T?ng ti?n: 250,000d");
            $this->command->info("   Tr?ng thái: Ch? thanh toán");
            $this->command->newLine();

            DB::commit();

            $this->command->info('? Mock orders seeded successfully!');
            $this->command->info('????????????????????????????????????????');
            $this->command->info('?? Summary:');
            $this->command->info('   • 1x Mua tr?c ti?p (Paid) - Blue badge');
            $this->command->info('   • 1x T?i b?ng VIP (Free) - Teal badge');
            $this->command->info('   • 1x Ğang kı VIP - Purple badge');
            $this->command->info('   • 1x Mua tr?c ti?p (Pending) - Blue badge');
            $this->command->info('????????????????????????????????????????');
            $this->command->info('?? Go to /admin/orders to see the results!');
            $this->command->newLine();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('? Error: ' . $e->getMessage());
            $this->command->error('File: ' . $e->getFile() . ':' . $e->getLine());
        }
    }
}
