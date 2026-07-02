<?php

namespace Modules\Document\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Document\Models\Subject;
use App\Models\Category;
use Illuminate\Support\Str;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cấu trúc: 'Tên Category' => ['Môn học 1', 'Môn học 2', ...]
        $subjectsData = [
            'Công nghệ thông tin' => [
                'Lập trình Web',
                'Lập trình Mobile',
                'Cơ sở dữ liệu',
                'Mạng máy tính',
                'An toàn thông tin',
                'Trí tuệ nhân tạo',
                'Machine Learning',
                'Cấu trúc dữ liệu & Giải thuật',
                'Lập trình hướng đối tượng',
                'Kỹ thuật phần mềm',
                'Hệ điều hành',
                'Kiến trúc máy tính',
                'Lập trình Python',
                'Lập trình Java',
                'Lập trình C/C++',
                'DevOps',
                'Cloud Computing',
            ],
            'Toán học' => [
                'Toán cao cấp',
                'Giải tích',
                'Đại số tuyến tính',
                'Xác suất thống kê',
                'Toán rời rạc',
                'Phương trình vi phân',
                'Hình học giải tích',
                'Toán ứng dụng',
            ],
            'Vật lý' => [
                'Vật lý đại cương',
                'Cơ học',
                'Điện từ học',
                'Quang học',
                'Vật lý hiện đại',
                'Nhiệt động lực học',
                'Vật lý lượng tử',
            ],
            'Hóa học' => [
                'Hóa đại cương',
                'Hóa hữu cơ',
                'Hóa vô cơ',
                'Hóa phân tích',
                'Hóa lý',
                'Hóa sinh',
            ],
            'Ngoại ngữ' => [
                'Tiếng Anh',
                'Tiếng Nhật',
                'Tiếng Trung',
                'Tiếng Hàn',
                'Tiếng Pháp',
                'Tiếng Đức',
            ],
            'Kinh tế' => [
                'Kinh tế vi mô',
                'Kinh tế vĩ mô',
                'Quản trị kinh doanh',
                'Marketing',
                'Kế toán',
                'Tài chính',
                'Thống kê kinh tế',
            ],
            'Kỹ thuật' => [
                'Kỹ thuật điện',
                'Kỹ thuật điện tử',
                'Kỹ thuật cơ khí',
                'Kỹ thuật xây dựng',
                'Kỹ thuật hóa học',
                'Tự động hóa',
            ],
            'Lập trình' => [
                'HTML/CSS',
                'JavaScript',
                'PHP',
                'Laravel',
                'ReactJS',
                'VueJS',
                'NodeJS',
                'Android',
                'iOS/Swift',
                'Flutter',
            ],
        ];

        foreach ($subjectsData as $categoryName => $subjects) {
            // Tìm category (dùng LIKE để flexible matching)
            $category = Category::where('name', 'like', '%' . $categoryName . '%')->first();
            
            if (!$category) {
                // Nếu không tìm thấy, tạo category mới
                $category = Category::create([
                    'name' => $categoryName,
                    'slug' => Str::slug($categoryName),
                    'is_active' => true,
                ]);
            }

            // Tạo subjects cho category này
            foreach ($subjects as $subjectName) {
                Subject::updateOrCreate(
                    [
                        'slug' => Str::slug($subjectName),
                    ],
                    [
                        'category_id' => $category->id,
                        'name' => $subjectName,
                        'is_active' => true,
                    ]
                );
            }
        }

        $totalSubjects = Subject::count();
        $totalCategories = count($subjectsData);
        
        $this->command->info("✅ Đã seed {$totalSubjects} môn học cho {$totalCategories} danh mục");
    }
}
