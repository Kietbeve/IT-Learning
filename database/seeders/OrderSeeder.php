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
        $this->command->info('🌱 Seeding mock orders...');
        $this->command->newLine();

        DB::beginTransaction();

        try {
            $buyer = User::where('email', 'layacctaodi889@gmail.com')->first();
            if (!$buyer) {
                $buyer = User::create([
                    'name' => 'Hải Đăng B9 Phạm Trần',
                    'email' => 'layacctaodi889@gmail.com',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                ]);
            }

            $contributor1 = User::where('email', 'contributor1@example.com')->first();
            if (!$contributor1) {
                $contributor1 = User::create([
                    'name' => 'Nguyễn Văn A',
                    'email' => 'contributor1@example.com',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                    'contributor_balance' => 500000,
                ]);
            }

            $contributor2 = User::where('email', 'contributor2@example.com')->first();
            if (!$contributor2) {
                $contributor2 = User::create([
                    'name' => 'Trần Thị B',
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

            $category = DB::table('categories')->where('name', 'Lập trình')->where('type', 'document')->first();
            if (!$category) {
                $categoryId = DB::table('categories')->insertGetId([
                    'name' => 'Lập trình',
                    'slug' => 'lap-trinh',
                    'type' => 'document',
                    'description' => 'Tài liệu lập trình',
                    'is_active' => true,
                    'sort_order' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $categoryId = $category->id;
            }

            $doc1 = Document::where('title', 'Source code Website bán hàng PHP thuần cực đẹp')->first();
            if (!$doc1) {
                $doc1 = Document::create([
                    'public_id' => 'doc_' . \Illuminate\Support\Str::random(12),
                    'title' => 'Source code Website bán hàng PHP thuần cực đẹp',
                    'slug' => 'source-code-website-ban-hang-php',
                    'description' => 'Mã nguồn website bán hàng hoàn chỉnh với PHP thuần, không dùng framework',
                    'author_id' => $contributor1->id,
                    'category_id' => $categoryId,
                    'file_type' => 'zip',
                    'file_original_path' => 'documents/php-shop.zip',
                    'file_size' => 5242880,
                    'status' => 'approved',
                    'visibility' => 'public',
                    'is_downloadable' => true,
                    'view_count' => 150,
                    'download_count' => 45,
                ]);
            }

            $product1 = Product::where('document_id', $doc1->id)->first();
            if (!$product1) {
                $product1 = Product::create([
                    'document_id' => $doc1->id,
                    'name' => 'Source code Website bán hàng PHP',
                    'price' => 150000,
                    'is_active' => true,
                ]);
            }

            $doc2 = Document::where('title', 'Đồ án quản lý thư viện Laravel Vue.js')->first();
            if (!$doc2) {
                $doc2 = Document::create([
                    'public_id' => 'doc_' . \Illuminate\Support\Str::random(12),
                    'title' => 'Đồ án quản lý thư viện Laravel Vue.js',
                    'slug' => 'do-an-quan-ly-thu-vien-laravel-vuejs',
                    'description' => 'Hệ thống quản lý thư viện đầy đủ với Laravel backend và Vue.js frontend',
                    'author_id' => $contributor2->id,
                    'category_id' => $categoryId,
                    'file_type' => 'zip',
                    'file_original_path' => 'documents/library-laravel-vue.zip',
                    'file_size' => 8388608,
                    'status' => 'approved',
                    'visibility' => 'public',
                    'is_downloadable' => true,
                    'view_count' => 230,
                    'download_count' => 67,
                ]);
            }

            $product2 = Product::where('document_id', $doc2->id)->first();
            if (!$product2) {
                $product2 = Product::create([
                    'document_id' => $doc2->id,
                    'name' => 'Đồ án quản lý thư viện',
                    'price' => 200000,
                    'is_active' => true,
                ]);
            }

            $doc3 = Document::where('title', 'App mobile Flutter bán hàng online')->first();
            if (!$doc3) {
                $doc3 = Document::create([
                    'public_id' => 'doc_' . \Illuminate\Support\Str::random(12),
                    'title' => 'App mobile Flutter bán hàng online',
                    'slug' => 'app-mobile-flutter-ban-hang',
                    'description' => 'Ứng dụng mobile bán hàng với Flutter, tích hợp payment gateway',
                    'author_id' => $contributor1->id,
                    'category_id' => $categoryId,
                    'file_type' => 'zip',
                    'file_original_path' => 'documents/flutter-shop.zip',
                    'file_size' => 12582912,
                    'status' => 'approved',
                    'visibility' => 'public',
                    'is_downloadable' => true,
                    'view_count' => 180,
                    'download_count' => 52,
                ]);
            }

            $product3 = Product::where('document_id', $doc3->id)->first();
            if (!$product3) {
                $product3 = Product::create([
                    'document_id' => $doc3->id,
                    'name' => 'App mobile Flutter',
                    'price' => 250000,
                    'is_active' => true,
                ]);
            }

            $this->command->info('✅ Users & Documents created');
            $this->command->newLine();

            $this->command->info('📦 Creating Order #1: Mua trực tiếp (Direct Purchase - Paid)');
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

            $this->command->info("   Mã ĐH: #{$order1->order_code}");
            $this->command->info("   Loại: Mua trực tiếp (Blue badge)");
            $this->command->info("   Người mua: {$buyer->name}");
            $this->command->info("   Tài liệu: {$doc1->title}");
            $this->command->info("   Người bán: {$contributor1->name}");
            $this->command->info("   Tổng tiền: 150,000đ");
            $this->command->info("   Trạng thái: Đã thanh toán");
            $this->command->newLine();

            $this->command->info('📦 Creating Order #2: Tải bằng VIP (VIP Download - Free)');
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

            $this->command->info("   M� �H: #{$order2->order_code}");
            $this->command->info("   Lo?i: T?i b?ng VIP (Teal badge)");
            $this->command->info("   Ngu?i mua: {$vipUser->name}");
            $this->command->info("   T�i li?u: {$doc2->title}");
            $this->command->info("   Ngu?i b�n: {$contributor2->name}");
            $this->command->info("   T?ng ti?n: 0d (VIP download)");
            $this->command->info("   Tr?ng th�i: �� thanh to�n");
            $this->command->newLine();

            $this->command->info('?? Creating Order #3: �ang k� VIP (VIP Subscription)');
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

            $this->command->info("   M� �H: #{$order3->order_code}");
            $this->command->info("   Lo?i: �ang k� VIP (Purple badge)");
            $this->command->info("   Ngu?i mua: {$vipUser->name}");
            $this->command->info("   G�i: VIP 1 Th�ng");
            $this->command->info("   T?ng ti?n: 99,000d");
            $this->command->info("   Tr?ng th�i: �� thanh to�n");
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

            $this->command->info("   M� �H: #{$order4->order_code}");
            $this->command->info("   Lo?i: Mua tr?c ti?p (Blue badge)");
            $this->command->info("   Ngu?i mua: {$buyer->name}");
            $this->command->info("   T�i li?u: {$doc3->title}");
            $this->command->info("   Ngu?i b�n: {$contributor1->name}");
            $this->command->info("   T?ng ti?n: 250,000d");
            $this->command->info("   Tr?ng th�i: Ch? thanh to�n");
            $this->command->newLine();

            DB::commit();

            $this->command->info('? Mock orders seeded successfully!');
            $this->command->info('????????????????????????????????????????');
            $this->command->info('?? Summary:');
            $this->command->info('   � 1x Mua tr?c ti?p (Paid) - Blue badge');
            $this->command->info('   � 1x T?i b?ng VIP (Free) - Teal badge');
            $this->command->info('   � 1x �ang k� VIP - Purple badge');
            $this->command->info('   � 1x Mua tr?c ti?p (Pending) - Blue badge');
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
