<?php

namespace Modules\Document\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Modules\Auth\Models\User;
use App\Models\Category;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentReview;
use Modules\Payment\Models\Product;
use Spatie\Permission\Models\Role;

class DocumentDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 0. Tạo thư mục và sinh các tệp tin thực tế trong public storage
        \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('documents');

        // Tạo file PDF thật
        $pdfContent = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n3 0 obj\n<< /Type /Page /Parent 2 0 R /Resources << >> /MediaBox [0 0 612 792] /Contents 4 0 R >>\nendobj\n4 0 obj\n<< /Length 58 >>\nstream\nBT /F1 12 Tf 50 700 Td (Hello World - IT-Learning Sample PDF Document) Tj ET\nendstream\nendobj\nxref\n0 5\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \n0000000220 00000 n \ntrailer\n<< /Size 5 /Root 1 0 R >>\nstartxref\n327\n%%EOF";
        \Illuminate\Support\Facades\Storage::disk('public')->put('documents/giao-trinh-laravel-11.pdf', $pdfContent);
        
        // Tạo file PDF preview (2 trang để giả lập giới hạn xem thử)
        $pdfPreviewContent = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n3 0 obj\n<< /Type /Page /Parent 2 0 R /Resources << >> /MediaBox [0 0 612 792] /Contents 4 0 R >>\nendobj\n4 0 obj\n<< /Length 72 >>\nstream\nBT /F1 12 Tf 50 700 Td (BAN XEM TRUOC GIOI HAN - VUI LONG TAI XUONG DE XEM TOAN BO) Tj ET\nendstream\nendobj\nxref\n0 5\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \n0000000220 00000 n \ntrailer\n<< /Size 5 /Root 1 0 R >>\nstartxref\n341\n%%EOF";
        \Illuminate\Support\Facades\Storage::disk('public')->put('documents/giao-trinh-laravel-11-preview.pdf', $pdfPreviewContent);
        
        // Tạo file PDF watermarked
        $pdfWatermarkedContent = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n3 0 obj\n<< /Type /Page /Parent 2 0 R /Resources << >> /MediaBox [0 0 612 792] /Contents 4 0 R >>\nendobj\n4 0 obj\n<< /Length 64 >>\nstream\nBT /F1 12 Tf 50 700 Td (BAN CO DONG DAU - IT-LEARNING COPYRIGHT WATERMARK) Tj ET\nendstream\nendobj\nxref\n0 5\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \n0000000220 00000 n \ntrailer\n<< /Size 5 /Root 1 0 R >>\nstartxref\n333\n%%EOF";
        \Illuminate\Support\Facades\Storage::disk('public')->put('documents/giao-trinh-laravel-11-watermarked.pdf', $pdfWatermarkedContent);

        // Tạo file PDF preview cho Tin học đại cương
        \Illuminate\Support\Facades\Storage::disk('public')->put('documents/tin-hoc-dai-cuong-preview.pdf', $pdfPreviewContent);

        // Tạo file PDF watermarked cho Tin học đại cương
        \Illuminate\Support\Facades\Storage::disk('public')->put('documents/tin-hoc-dai-cuong-watermarked.pdf', $pdfWatermarkedContent);

        // Tạo file PDF original cho Tin học đại cương
        \Illuminate\Support\Facades\Storage::disk('public')->put('documents/tin-hoc-dai-cuong.pdf', $pdfContent);


        // Tạo file ZIP thật bằng ZipArchive
        $zipPath = storage_path('app/public/documents/source-code-php-web.zip');
        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            $zip->addFromString('index.php', '<?php echo "Welcome to Phone Shop PHP Web!"; ?>');
            $zip->addFromString('config.php', '<?php $db_host = "localhost"; $db_user = "root"; $db_pass = ""; ?>');
            $zip->addFromString('README.md', "# Phone Shop PHP Web\n\nĐây là mã nguồn website bán hàng viết bằng PHP thuần kết nối MySQL PDO.\n\n## Cài đặt:\n1. Import database.sql vào phpMyAdmin.\n2. Cấu hình config.php.\n3. Chạy trên XAMPP.");
            $zip->close();
        }

        // Tạo file DOCX mock
        \Illuminate\Support\Facades\Storage::disk('public')->put('documents/bao-cao-quan-ly-ban-hang.docx', 'DOCX dummy content');

        // 1. Đảm bảo các role tồn tại
        $adminRole = Role::findOrCreate('admin', 'web');
        $contributorRole = Role::findOrCreate('contributor', 'web');
        $studentRole = Role::findOrCreate('student', 'web');

        // 2. Lấy hoặc tạo tài khoản Admin
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin IT-Learning',
                'password' => Hash::make('admin123'),
                'status' => 'active',
            ]
        );
        $adminUser->assignRole($adminRole);

        // Lấy hoặc tạo tài khoản Contributor (Người đăng tải)
        $contributorUser = User::firstOrCreate(
            ['email' => 'contributor@example.com'],
            [
                'name' => 'Nguyễn Văn Cộng Tác Viên',
                'password' => Hash::make('contributor123'),
                'status' => 'active',
            ]
        );
        $contributorUser->assignRole($contributorRole);

        // Lấy hoặc tạo tài khoản Học viên
        $studentUser = User::firstOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Trần Học Viên',
                'password' => Hash::make('student123'),
                'status' => 'active',
            ]
        );
        $studentUser->assignRole($studentRole);

        // 3. Đảm bảo danh mục tài liệu tồn tại
        $catPHP = Category::firstOrCreate(
            ['slug' => 'php'],
            [
                'name' => 'PHP',
                'type' => 'document',
                'is_active' => true,
                'sort_order' => 1,
                'description' => 'Tài liệu lập trình PHP',
            ]
        );

        $catLaravel = Category::firstOrCreate(
            ['slug' => 'laravel'],
            [
                'name' => 'Laravel',
                'type' => 'document',
                'is_active' => true,
                'sort_order' => 2,
                'description' => 'Tài liệu và Đồ án Laravel',
            ]
        );

        // 4. Sinh dữ liệu mẫu Tài liệu (Documents)
        
        // Tài liệu 1: Laravel Miễn phí (Đã duyệt)
        $doc1 = Document::updateOrCreate(
            ['slug' => 'giao-trinh-laravel-11-can-ban-den-nang-cao'],
            [
                'public_id' => 'doc_laravel_base_001',
                'author_id' => $contributorUser->id,
                'category_id' => $catLaravel->id,
                'title' => 'Giáo trình Laravel 11 từ căn bản đến nâng cao',
                'short_description' => 'Tài liệu hướng dẫn học Laravel 11 chi tiết nhất phù hợp cho người mới bắt đầu.',
                'description' => "Cuốn giáo trình này giúp bạn làm chủ Laravel 11 thông qua các bài học thực tế.\nNội dung chính:\n- Cài đặt và cấu hình môi trường\n- Routing, Controller và View\n- Database & Migrations, Eloquent ORM\n- Xây dựng ứng dụng CRUD hoàn chỉnh.",
                'preview_file_path' => 'documents/giao-trinh-laravel-11-preview.pdf',
                'file_watermarked_path' => 'documents/giao-trinh-laravel-11-watermarked.pdf',
                'watermark_status' => 'success',
                'file_original_path' => 'documents/giao-trinh-laravel-11.pdf',
                'file_type' => 'pdf',
                'file_size' => 1024 * 1024 * 5, // 5MB
                'visibility' => 'public',
                'is_downloadable' => true,
                'status' => 'approved',
                'reviewed_by' => $adminUser->id,
                'reviewed_at' => now(),
                'published_at' => now(),
                'download_count' => 120,
                'view_count' => 450,
            ]
        );

        // Tài liệu 2: Source code PHP Thuần Trả phí (Đã duyệt)
        $doc2 = Document::updateOrCreate(
            ['slug' => 'source-code-website-ban-hang-php-thuan'],
            [
                'public_id' => 'doc_php_web_sale_002',
                'author_id' => $contributorUser->id,
                'category_id' => $catPHP->id,
                'title' => 'Source code Website bán hàng PHP thuần cực đẹp',
                'short_description' => 'Mã nguồn website bán điện thoại di động viết bằng PHP thuần, giao diện Bootstrap, có trang Admin.',
                'description' => "Source code phù hợp cho sinh viên làm báo cáo niên luận hoặc đồ án môn học.\nTính năng nổi bật:\n- Trang hiển thị sản phẩm, giỏ hàng, đặt hàng.\n- Trang quản trị (Admin): Quản lý sản phẩm, đơn hàng, người dùng.\n- Kết nối cơ sở dữ liệu MySQL chuẩn PDO bảo mật chống SQL Injection.",
                'file_original_path' => 'documents/source-code-php-web.zip',
                'file_type' => 'zip',
                'file_size' => 1024 * 1024 * 15, // 15MB
                'visibility' => 'public',
                'is_downloadable' => true,
                'status' => 'approved',
                'reviewed_by' => $adminUser->id,
                'reviewed_at' => now(),
                'published_at' => now(),
                'download_count' => 15,
                'view_count' => 98,
            ]
        );

        // Tạo Product tương ứng cho tài liệu trả phí này
        Product::updateOrCreate(
            ['document_id' => $doc2->id],
            [
                'name' => $doc2->title,
                'price' => 150000,
                'is_active' => true,
            ]
        );

        // Tài liệu 3: Đồ án Laravel Trả phí (Đang chờ duyệt)
        $doc3 = Document::updateOrCreate(
            ['slug' => 'do-an-tot-nghiep-he-thong-quan-ly-thu-vien-laravel'],
            [
                'public_id' => 'doc_laravel_lib_003',
                'author_id' => $studentUser->id,
                'category_id' => $catLaravel->id,
                'title' => 'Đồ án tốt nghiệp: Hệ thống quản lý thư viện trường học',
                'short_description' => 'Mã nguồn đồ án quản lý thư viện viết bằng Laravel 10 kết hợp Tailwind CSS.',
                'description' => "Đồ án tốt nghiệp đạt điểm xuất sắc khóa 2022.\nĐầy đủ tính năng:\n- Đăng ký mượn sách online.\n- Quản lý kho sách, phân loại sách.\n- Quản lý thẻ độc giả, thống kê quá hạn mượn.\n- Xuất báo cáo Excel.",
                'file_original_path' => 'documents/do-an-thu-vien.zip',
                'file_type' => 'zip',
                'file_size' => 1024 * 1024 * 28, // 28MB
                'visibility' => 'public',
                'is_downloadable' => true,
                'status' => 'pending',
            ]
        );

        Product::updateOrCreate(
            ['document_id' => $doc3->id],
            [
                'name' => $doc3->title,
                'price' => 250000,
                'is_active' => true,
            ]
        );

        // Tài liệu 4: Ebook Miễn phí (Đang chờ duyệt)
        Document::updateOrCreate(
            ['slug' => 'ebook-toi-uu-hoa-database-mysql-cho-du-an-lon'],
            [
                'public_id' => 'doc_mysql_opt_004',
                'author_id' => $contributorUser->id,
                'category_id' => $catPHP->id,
                'title' => 'Ebook Tối ưu hóa Database MySQL cho dự án lớn',
                'short_description' => 'Sách hướng dẫn cách tạo Index, tối ưu câu lệnh Query và kiến trúc Database.',
                'description' => "Tài liệu nâng cao dành cho các bạn muốn tối ưu tốc độ tải trang.\nNội dung chính:\n- Cách phân tích EXPLAIN câu lệnh SQL.\n- Nguyên tắc đặt Index hiệu quả.\n- Kỹ thuật Database Partitioning và Sharding cơ bản.",
                'file_original_path' => 'documents/ebook-mysql-opt.pdf',
                'file_type' => 'pdf',
                'file_size' => 1024 * 1024 * 8, // 8MB
                'visibility' => 'public',
                'is_downloadable' => true,
                'status' => 'pending',
            ]
        );

        // Tài liệu 5: Tài liệu DOCX Miễn phí (Đã duyệt)
        Document::updateOrCreate(
            ['slug' => 'bao-cao-khao-sat-quy-trinh-nghiep-vu-quan-ly-ban-hang'],
            [
                'public_id' => 'doc_docx_sale_005',
                'author_id' => $contributorUser->id,
                'category_id' => $catPHP->id,
                'title' => 'Báo cáo khảo sát quy trình nghiệp vụ quản lý bán hàng',
                'short_description' => 'Tài liệu khảo sát chi tiết quy trình bán hàng phục vụ phân tích thiết kế hệ thống.',
                'description' => "Tài liệu mẫu dạng DOCX phục vụ làm đồ án phân tích thiết kế hệ thống thông tin.\nNội dung bao gồm:\n- Sơ đồ thực thể ERD.\n- Sơ đồ dòng dữ liệu DFD cấp 0, 1, 2.\n- Mô tả chi tiết các tác nhân và chức năng hệ thống.",
                'file_original_path' => 'documents/bao-cao-quan-ly-ban-hang.docx',
                'file_type' => 'docx',
                'file_size' => 1024 * 350, // 350KB
                'visibility' => 'public',
                'is_downloadable' => true,
                'status' => 'approved',
                'reviewed_by' => $adminUser->id,
                'reviewed_at' => now(),
                'published_at' => now(),
                'download_count' => 45,
                'view_count' => 150,
            ]
        );

        // 5. Thêm một số đánh giá mẫu cho Tài liệu 1
        DocumentReview::updateOrCreate(
            ['document_id' => $doc1->id, 'user_id' => $studentUser->id],
            [
                'rating' => 5,
                'review' => 'Tài liệu viết rất dễ hiểu, đầy đủ hình ảnh minh họa chi tiết. Em cảm ơn thầy nhiều ạ!',
                'status' => 'visible',
            ]
        );

        // 6. Seed 25 more documents for pagination testing (20 items/page)
        for ($i = 1; $i <= 25; $i++) {
            Document::updateOrCreate(
                ['slug' => "tai-lieu-tin-hoc-dai-cuong-part-{$i}"],
                [
                    'public_id' => "doc_pag_test_" . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'author_id' => $contributorUser->id,
                    'category_id' => ($i % 2 === 0) ? $catLaravel->id : $catPHP->id,
                    'title' => "Tài liệu Tin học đại cương - Phần {$i}",
                    'thumbnail' => ($i === 1) ? 'documents/cs_thumbnail.png' : null,
                    'short_description' => "Tài liệu tự học Tin học đại cương phần thứ {$i} dành cho sinh viên năm nhất.",
                    'description' => "Nội dung chi tiết của tài liệu ôn thi Tin học đại cương phần {$i}.\nBao gồm lý thuyết và bài tập thực hành mẫu.",
                    'preview_file_path' => 'documents/tin-hoc-dai-cuong-preview.pdf',
                    'file_watermarked_path' => 'documents/tin-hoc-dai-cuong-watermarked.pdf',
                    'watermark_status' => 'success',
                    'file_original_path' => 'documents/tin-hoc-dai-cuong.pdf',
                    'file_type' => 'pdf',
                    'file_size' => 1024 * 100 * $i,
                    'visibility' => 'public',
                    'is_downloadable' => true,
                    'status' => 'approved',
                    'reviewed_by' => $adminUser->id,
                    'reviewed_at' => now(),
                    'published_at' => now()->subYears($i % 3)->subDays($i),
                    'download_count' => 5 * $i,
                    'view_count' => 15 * $i,
                ]
            );
        }
    }
}
