<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Modules\Payment\Models\Order;
use Modules\Payment\Models\OrderItem;
use Modules\Payment\Models\DocumentAccess;
use Modules\Document\Models\Document;
use Modules\Payment\Models\Product;
use Modules\Auth\Models\User;
use Illuminate\Support\Facades\DB;

echo "🌱 Seeding mock orders...\n\n";

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

    echo "✅ Users & Documents created\n\n";

    echo "📦 Creating Order #1: Mua trực tiếp (Direct Purchase - Paid)\n";
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

    echo "   Mã ĐH: #{$order1->order_code}\n";
    echo "   Loại: Mua trực tiếp (Blue badge)\n";
    echo "   Người mua: {$buyer->name}\n";
    echo "   Tài liệu: {$doc1->title}\n";
    echo "   Người bán: {$contributor1->name}\n";
    echo "   Tổng tiền: 150,000đ\n";
    echo "   Trạng thái: Đã thanh toán\n\n";

    echo "📦 Creating Order #2: Tải bằng VIP (VIP Download - Free)\n";
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

    echo "   Mã ĐH: #{$order2->order_code}\n";
    echo "   Loại: Tải bằng VIP (Teal badge)\n";
    echo "   Người mua: {$vipUser->name}\n";
    echo "   Tài liệu: {$doc2->title}\n";
    echo "   Người bán: {$contributor2->name}\n";
    echo "   Tổng tiền: 0đ (VIP download)\n";
    echo "   Trạng thái: Đã thanh toán\n\n";

    echo "📦 Creating Order #3: Đăng ký VIP (VIP Subscription)\n";
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

    echo "   Mã ĐH: #{$order3->order_code}\n";
    echo "   Loại: Đăng ký VIP (Purple badge)\n";
    echo "   Người mua: {$vipUser->name}\n";
    echo "   Gói: VIP 1 Tháng\n";
    echo "   Tổng tiền: 99,000đ\n";
    echo "   Trạng thái: Đã thanh toán\n\n";

    echo "📦 Creating Order #4: Mua trực tiếp (Pending Payment)\n";
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

    echo "   Mã ĐH: #{$order4->order_code}\n";
    echo "   Loại: Mua trực tiếp (Blue badge)\n";
    echo "   Người mua: {$buyer->name}\n";
    echo "   Tài liệu: {$doc3->title}\n";
    echo "   Người bán: {$contributor1->name}\n";
    echo "   Tổng tiền: 250,000đ\n";
    echo "   Trạng thái: Chờ thanh toán\n\n";

    DB::commit();

    echo "✅ Mock orders seeded successfully!\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📊 Summary:\n";
    echo "   • 1x Mua trực tiếp (Paid) - Blue badge\n";
    echo "   • 1x Tải bằng VIP (Free) - Teal badge\n";
    echo "   • 1x Đăng ký VIP - Purple badge\n";
    echo "   • 1x Mua trực tiếp (Pending) - Blue badge\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "🚀 Go to /admin/orders to see the results!\n\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
