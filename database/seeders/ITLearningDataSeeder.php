<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ITLearningDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SETTINGS
        $this->seedSettings();

        // 2. USERS
        $this->seedUsers();

        // 3. CATEGORIES & SUBJECTS
        $this->seedCategoriesAndSubjects();

        // 4. TAGS
        $this->seedTags();

        // 5. DOCUMENTS
        $this->seedDocuments();
    }

    private function seedSettings()
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'IT Learning', 'group' => 'general'],
            ['key' => 'commission_rate', 'value' => '70', 'group' => 'payment'],
            ['key' => 'min_payout_amount', 'value' => '200000', 'group' => 'payment'],
            ['key' => 'theme_primary_color', 'value' => '#6366f1', 'group' => 'theme'],
            ['key' => 'smtp_from_email', 'value' => 'no-reply@itlearning.com', 'group' => 'email'],
            ['key' => 'vip_sale_price', 'value' => '99000', 'group' => 'payment'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'group' => $setting['group'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    private function seedUsers()
    {
        $roleUser = Role::findOrCreate('user', 'web');
        $roleContributor = Role::findOrCreate('contributor', 'web');

        // Tạo 5 User
        for ($i = 1; $i <= 5; $i++) {
            $user = User::firstOrCreate(
                ['email' => "student{$i}@example.com"],
                [
                    'name' => "Student {$i}",
                    'password' => bcrypt('password'),
                    'status' => 'active'
                ]
            );
            $user->assignRole('user');
        }

        // Tạo 2 Contributor
        for ($i = 1; $i <= 2; $i++) {
            $user = User::firstOrCreate(
                ['email' => "expert{$i}@example.com"],
                [
                    'name' => "IT Expert {$i}",
                    'password' => bcrypt('password'),
                    'status' => 'active',
                    'contributor_balance' => 0
                ]
            );
            $user->assignRole('contributor');
        }
    }

    private function seedCategoriesAndSubjects()
    {
        $categories = [
            'Lập trình Web' => ['PHP & Laravel', 'Node.js', 'ReactJS', 'VueJS', 'HTML/CSS/JS'],
            'Lập trình Mobile' => ['Flutter', 'React Native', 'Android (Kotlin)', 'iOS (Swift)'],
            'Trí tuệ nhân tạo (AI)' => ['Machine Learning', 'Deep Learning', 'Data Science', 'Computer Vision'],
            'Cơ sở dữ liệu' => ['MySQL', 'PostgreSQL', 'MongoDB', 'SQL Server'],
            'Mạng & Bảo mật' => ['Mạng máy tính cơ bản', 'CCNA', 'Quản trị Linux', 'Bảo mật thông tin'],
        ];

        foreach ($categories as $catName => $subjects) {
            $catSlug = Str::slug($catName);
            $category = DB::table('categories')->where('slug', $catSlug)->first();
            
            if ($category) {
                $categoryId = $category->id;
            } else {
                $categoryId = DB::table('categories')->insertGetId([
                    'name' => $catName,
                    'slug' => $catSlug,
                    'type' => 'document',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            foreach ($subjects as $subName) {
                $subSlug = Str::slug($subName);
                $subjectExists = DB::table('subjects')->where('slug', $subSlug)->exists();
                
                if (!$subjectExists) {
                    DB::table('subjects')->insert([
                        'category_id' => $categoryId,
                        'name' => $subName,
                        'slug' => $subSlug,
                        'is_active' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    private function seedTags()
    {
        $tags = [
            'Backend', 'Frontend', 'DevOps', 'Fullstack', 'Beginner', 
            'Advanced', 'API', 'UI/UX', 'Cloud', 'Microservices', 
            'Clean Code', 'System Design', 'Performance', 'Testing', 'Security'
        ];

        foreach ($tags as $tagName) {
            DB::table('tags')->updateOrInsert(
                ['slug' => Str::slug($tagName)],
                [
                    'name' => $tagName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    private function seedDocuments()
    {
        // Get or Create Author
        $author = User::firstOrCreate(
            ['email' => 'contributor@example.com'],
            [
                'name' => 'IT Contributor',
                'password' => bcrypt('password'),
                'status' => 'active'
            ]
        );
        $author->assignRole('contributor');

        // Lấy Admin thực sự để làm người duyệt (thay vì gán cứng số 1)
        $admin = User::role('admin')->first() ?? User::first();
        $adminId = $admin ? $admin->id : 1;

        $documentsData = [
            // Lập trình Web
            ['Cẩm nang học Laravel 11 từ Zero đến Hero', 'Lập trình Web', 'PHP & Laravel', 0, 0, 'Sách Laravel 11 chi tiết nhất cho người mới bắt đầu.'],
            ['Xây dựng API RESTful với Laravel', 'Lập trình Web', 'PHP & Laravel', 50000, 39000, 'Khóa học thực hành xây dựng API chuẩn RESTful.'],
            ['Tài liệu ReactJS cho người mới', 'Lập trình Web', 'ReactJS', 0, 0, 'Hiểu rõ Hook, Component, State trong React.'],
            ['Next.js và SSR toàn tập', 'Lập trình Web', 'ReactJS', 100000, 89000, 'Tối ưu hóa SEO với Next.js.'],
            ['Lập trình Backend với Node.js & Express', 'Lập trình Web', 'Node.js', 0, 0, 'Cơ bản về Node.js và xây dựng server.'],
            ['Cẩm nang VueJS 3 Composition API', 'Lập trình Web', 'VueJS', 0, 0, 'Cách dùng Composition API hiệu quả.'],
            ['Bí kíp CSS Flexbox và Grid', 'Lập trình Web', 'HTML/CSS/JS', 20000, 15000, 'Thành thạo dàn trang chỉ trong 1 tuần.'],

            // Lập trình Mobile
            ['Tự học Flutter trong 30 ngày', 'Lập trình Mobile', 'Flutter', 0, 0, 'Tạo ứng dụng đa nền tảng đầu tiên.'],
            ['Xây dựng ứng dụng E-commerce với Flutter', 'Lập trình Mobile', 'Flutter', 150000, 120000, 'Dự án thực tế app bán hàng mượt mà.'],
            ['React Native cho Web Developer', 'Lập trình Mobile', 'React Native', 0, 0, 'Chuyển hướng từ Web sang Mobile nhanh chóng.'],
            ['Lập trình Android với Kotlin hiện đại', 'Lập trình Mobile', 'Android (Kotlin)', 80000, 69000, 'Sử dụng Coroutine và Jetpack Compose.'],
            ['Thiết kế UI/UX cho ứng dụng iOS', 'Lập trình Mobile', 'iOS (Swift)', 0, 0, 'Giao diện chuẩn Apple guidelines.'],

            // Trí tuệ nhân tạo
            ['Nhập môn Machine Learning với Python', 'Trí tuệ nhân tạo (AI)', 'Machine Learning', 0, 0, 'Scikit-learn cơ bản và thuật toán.'],
            ['Thực hành Deep Learning với TensorFlow', 'Trí tuệ nhân tạo (AI)', 'Deep Learning', 200000, 150000, 'Xây dựng mạng nơ-ron từ con số 0.'],
            ['Nhận diện khuôn mặt với OpenCV', 'Trí tuệ nhân tạo (AI)', 'Computer Vision', 50000, 45000, 'Phân tích hình ảnh và xử lý video.'],
            ['Phân tích dữ liệu với Pandas và NumPy', 'Trí tuệ nhân tạo (AI)', 'Data Science', 0, 0, 'Tiền xử lý và trực quan hóa dữ liệu.'],

            // Cơ sở dữ liệu
            ['Tối ưu hóa câu truy vấn MySQL', 'Cơ sở dữ liệu', 'MySQL', 100000, 79000, 'Cách thiết kế index và tránh slow queries.'],
            ['Thiết kế CSDL PostgreSQL nâng cao', 'Cơ sở dữ liệu', 'PostgreSQL', 0, 0, 'Tính năng JSONB, CTEs và Window Functions.'],
            ['MongoDB cho ứng dụng thời gian thực', 'Cơ sở dữ liệu', 'MongoDB', 50000, 39000, 'Lưu trữ dữ liệu phi cấu trúc.'],
            ['Quản trị SQL Server cho doanh nghiệp', 'Cơ sở dữ liệu', 'SQL Server', 120000, 99000, 'Backup, Restore và High Availability.'],

            // Mạng & Bảo mật
            ['Mạng máy tính từ cơ bản đến nâng cao', 'Mạng & Bảo mật', 'Mạng máy tính cơ bản', 0, 0, 'Mô hình OSI, TCP/IP, Routing.'],
            ['Tài liệu ôn thi CCNA 200-301', 'Mạng & Bảo mật', 'CCNA', 80000, 59000, 'Full bộ đề cương và bài lab thực hành.'],
            ['Quản trị hệ thống Ubuntu Linux', 'Mạng & Bảo mật', 'Quản trị Linux', 0, 0, 'Command line, File permission, SSH, Nginx.'],
            ['Hacker mũ trắng: Bảo mật ứng dụng Web', 'Mạng & Bảo mật', 'Bảo mật thông tin', 250000, 199000, 'SQL Injection, XSS, CSRF và cách phòng chống.']
        ];

        // Prepare Tags
        $tagIds = DB::table('tags')->pluck('id')->toArray();

        foreach ($documentsData as $index => $data) {
            [$title, $catName, $subName, $price, $salePrice, $desc] = $data;

            $category = DB::table('categories')->where('name', $catName)->first();
            $subject = DB::table('subjects')->where('name', $subName)->first();
            
            if (!$category || !$subject) continue;

            $docId = DB::table('documents')->insertGetId([
                'public_id' => 'doc_' . Str::random(12),
                'author_id' => $author->id,
                'slug' => Str::slug($title) . '-' . Str::random(5),
                'status' => 'approved',
                'download_count' => rand(10, 500),
                'view_count' => rand(100, 2000),
                'favorite_count' => rand(5, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $submittedDaysAgo = rand(5, 30);
            $reviewedDaysAgo = rand(1, $submittedDaysAgo - 1);

            $versionId = DB::table('document_versions')->insertGetId([
                'document_id' => $docId,
                'version_number' => 1,
                'title' => $title,
                'short_description' => Str::limit($desc, 100),
                'description' => '<p>' . $desc . '</p><p>Nội dung chi tiết sẽ liên tục được cập nhật. Đây là tài liệu quý giá dành cho cộng đồng IT Learning.</p>',
                'category_id' => $category->id,
                'subject_id' => $subject->id,
                'thumbnail' => 'documents/thumbnails/default.jpg',
                'file_original_path' => 'documents/files/sample.pdf',
                'file_type' => 'pdf',
                'file_size' => 1024 * 1024 * rand(1, 50), // 1MB - 50MB
                'visibility' => 'public',
                'price' => $price,
                'sale_price' => $salePrice > 0 ? $salePrice : null,
                'status' => 'approved',
                'submitted_by' => $author->id,
                'reviewed_by' => $adminId,
                'submitted_at' => now()->subDays($submittedDaysAgo),
                'reviewed_at' => now()->subDays($reviewedDaysAgo),
                'created_at' => now()->subDays($submittedDaysAgo),
                'updated_at' => now()->subDays($reviewedDaysAgo),
            ]);

            DB::table('documents')->where('id', $docId)->update(['current_version_id' => $versionId]);
            
            DB::table('products')->insert([
                'document_id' => $docId,
                'name' => $title,
                'price' => $price,
                'sale_price' => $salePrice > 0 ? $salePrice : null,
                'is_active' => 1,
                'created_at' => now()->subDays($submittedDaysAgo),
                'updated_at' => now()->subDays($reviewedDaysAgo),
            ]);

            // Assign random tags
            if (count($tagIds) > 0) {
                $randomKeys = array_rand($tagIds, rand(2, 4));
                $randomKeys = is_array($randomKeys) ? $randomKeys : [$randomKeys];
                
                foreach ($randomKeys as $key) {
                    DB::table('document_tag_maps')->insert([
                        'document_id' => $docId,
                        'tag_id' => $tagIds[$key],
                    ]);
                }
            }
        }
    }
}
