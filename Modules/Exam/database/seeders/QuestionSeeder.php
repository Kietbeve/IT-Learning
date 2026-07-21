<?php

namespace Modules\Exam\database\seeders;

use Illuminate\Database\Seeder;
use Str;
use Modules\Exam\Models\Question;
use Modules\Exam\Models\QuestionOption;
use Modules\Exam\Models\Exam;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get category IDs
        $categories = [
            'web' => \DB::table('categories')->where('name', 'LIKE', '%Lập trình Web%')->value('id') ?? 1,
            'mobile' => \DB::table('categories')->where('name', 'LIKE', '%Mobile%')->value('id') ?? 1,
            'database' => \DB::table('categories')->where('name', 'LIKE', '%Cơ sở dữ liệu%')->value('id') ?? 1,
            'network' => \DB::table('categories')->where('name', 'LIKE', '%Mạng%')->orWhere('name', 'LIKE', '%Bảo mật%')->value('id') ?? 1,
        ];

        $allQuestionsData = [
            // ===== Cơ sở dữ liệu (10 câu) =====
            // Single choice - 4 câu
            [
                'category_id' => $categories['database'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'SQL là gì?',
                'explanation' => 'SQL là ngôn ngữ truy vấn có cấu trúc.',
                'options' => [
                    ['key' => 'A', 'content' => 'Ngôn ngữ lập trình', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Ngôn ngữ truy vấn', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Hệ điều hành', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Web framework', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['database'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'Lệnh nào dùng để lấy dữ liệu?',
                'explanation' => 'SELECT dùng để lấy dữ liệu từ bảng.',
                'options' => [
                    ['key' => 'A', 'content' => 'GET', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'SELECT', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'FETCH', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'READ', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['database'],
                'type' => 'single_choice',
                'difficulty' => 'medium',
                'content' => 'Primary Key dùng để làm gì?',
                'explanation' => 'Primary Key xác định duy nhất mỗi bản ghi.',
                'options' => [
                    ['key' => 'A', 'content' => 'Xác định duy nhất', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Sắp xếp dữ liệu', 'is_correct' => false],
                    ['key' => 'C', 'content' => 'Mã hóa dữ liệu', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Backup dữ liệu', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['database'],
                'type' => 'single_choice',
                'difficulty' => 'medium',
                'content' => 'NoSQL database nào?',
                'explanation' => 'MongoDB là NoSQL database.',
                'options' => [
                    ['key' => 'A', 'content' => 'MySQL', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'MongoDB', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'PostgreSQL', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Oracle', 'is_correct' => false],
                ]
            ],

            // Multiple choice - 4 câu
            [
                'category_id' => $categories['database'],
                'type' => 'multiple_choice',
                'difficulty' => 'easy',
                'content' => 'Lệnh DML nào sau đây?',
                'explanation' => 'SELECT, INSERT, UPDATE là lệnh DML.',
                'options' => [
                    ['key' => 'A', 'content' => 'SELECT', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'INSERT', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'UPDATE', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'CREATE', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['database'],
                'type' => 'multiple_choice',
                'difficulty' => 'easy',
                'content' => 'Database nào là SQL?',
                'explanation' => 'MySQL, PostgreSQL là SQL database.',
                'options' => [
                    ['key' => 'A', 'content' => 'MySQL', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'PostgreSQL', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'MongoDB', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Redis', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['database'],
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'Loại JOIN nào có trong SQL?',
                'explanation' => 'INNER JOIN, LEFT JOIN là các loại JOIN.',
                'options' => [
                    ['key' => 'A', 'content' => 'INNER JOIN', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'LEFT JOIN', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'RIGHT JOIN', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'MIDDLE JOIN', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['database'],
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'Kỹ thuật tối ưu database nào?',
                'explanation' => 'Indexing và Query optimization là kỹ thuật tối ưu.',
                'options' => [
                    ['key' => 'A', 'content' => 'Indexing', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Query optimization', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Partitioning', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Delete all data', 'is_correct' => false],
                ]
            ],

            // Essay - 2 câu
            [
                'category_id' => $categories['database'],
                'type' => 'essay',
                'difficulty' => 'easy',
                'content' => 'Khác biệt SQL và NoSQL?',
                'explanation' => 'SQL có schema cố định, NoSQL linh hoạt.',
                'answer_text' => 'Schema',
            ],
            [
                'category_id' => $categories['database'],
                'type' => 'essay',
                'difficulty' => 'medium',
                'content' => 'Foreign Key là gì?',
                'explanation' => 'Foreign Key tham chiếu đến bảng khác.',
                'answer_text' => 'Tham chiếu',
            ],

            // ===== Lập trình Mobile (10 câu) =====
            // Single choice - 4 câu
            [
                'category_id' => $categories['mobile'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'Android do công ty nào phát triển?',
                'explanation' => 'Android được Google phát triển.',
                'options' => [
                    ['key' => 'A', 'content' => 'Apple', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Google', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Microsoft', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Samsung', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['mobile'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'Ngôn ngữ nào cho iOS?',
                'explanation' => 'Swift là ngôn ngữ chính cho iOS.',
                'options' => [
                    ['key' => 'A', 'content' => 'Java', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Swift', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Python', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'PHP', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['mobile'],
                'type' => 'single_choice',
                'difficulty' => 'medium',
                'content' => 'Flutter dùng ngôn ngữ gì?',
                'explanation' => 'Flutter sử dụng Dart.',
                'options' => [
                    ['key' => 'A', 'content' => 'JavaScript', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Dart', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Kotlin', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Swift', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['mobile'],
                'type' => 'single_choice',
                'difficulty' => 'medium',
                'content' => 'IDE cho Android là gì?',
                'explanation' => 'Android Studio là IDE chính thức.',
                'options' => [
                    ['key' => 'A', 'content' => 'Eclipse', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Android Studio', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Xcode', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'NetBeans', 'is_correct' => false],
                ]
            ],

            // Multiple choice - 4 câu
            [
                'category_id' => $categories['mobile'],
                'type' => 'multiple_choice',
                'difficulty' => 'easy',
                'content' => 'Framework nào cho cross-platform?',
                'explanation' => 'Flutter và React Native là cross-platform.',
                'options' => [
                    ['key' => 'A', 'content' => 'Flutter', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'React Native', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Xamarin', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Django', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['mobile'],
                'type' => 'multiple_choice',
                'difficulty' => 'easy',
                'content' => 'Ngôn ngữ nào cho Android?',
                'explanation' => 'Java và Kotlin cho Android native.',
                'options' => [
                    ['key' => 'A', 'content' => 'Java', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Kotlin', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Swift', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Ruby', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['mobile'],
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'Android storage nào?',
                'explanation' => 'SharedPreferences và SQLite là storage options.',
                'options' => [
                    ['key' => 'A', 'content' => 'SharedPreferences', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'SQLite', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Internal Storage', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Redis', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['mobile'],
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'Architecture patterns cho mobile?',
                'explanation' => 'MVC, MVP và MVVM là patterns phổ biến.',
                'options' => [
                    ['key' => 'A', 'content' => 'MVC', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'MVP', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'MVVM', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Microservices', 'is_correct' => false],
                ]
            ],

            // Essay - 2 câu
            [
                'category_id' => $categories['mobile'],
                'type' => 'essay',
                'difficulty' => 'easy',
                'content' => 'Native app là gì?',
                'explanation' => 'Native app được viết bằng ngôn ngữ gốc của platform.',
                'answer_text' => 'Ngôn ngữ gốc',
            ],
            [
                'category_id' => $categories['mobile'],
                'type' => 'essay',
                'difficulty' => 'medium',
                'content' => 'Activity lifecycle trong Android?',
                'explanation' => 'Activity có các state: onCreate, onStart, onResume, onPause.',
                'answer_text' => 'Vòng đời',
            ],

            // ===== Lập trình Web (10 câu) =====
            // Single choice - 4 câu
            [
                'category_id' => $categories['web'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'HTML là gì?',
                'explanation' => 'HTML là ngôn ngữ đánh dấu siêu văn bản.',
                'options' => [
                    ['key' => 'A', 'content' => 'Ngôn ngữ đánh dấu', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Ngôn ngữ lập trình', 'is_correct' => false],
                    ['key' => 'C', 'content' => 'Framework', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Database', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['web'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'CSS dùng để làm gì?',
                'explanation' => 'CSS dùng để tạo kiểu cho HTML.',
                'options' => [
                    ['key' => 'A', 'content' => 'Tạo kiểu', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Lưu trữ dữ liệu', 'is_correct' => false],
                    ['key' => 'C', 'content' => 'Xử lý logic', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Bảo mật', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['web'],
                'type' => 'single_choice',
                'difficulty' => 'medium',
                'content' => 'HTTP method nào lấy dữ liệu?',
                'explanation' => 'GET dùng để lấy dữ liệu từ server.',
                'options' => [
                    ['key' => 'A', 'content' => 'POST', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'GET', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'PUT', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'DELETE', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['web'],
                'type' => 'single_choice',
                'difficulty' => 'medium',
                'content' => 'Framework nào KHÔNG phải JavaScript?',
                'explanation' => 'Laravel là PHP framework.',
                'options' => [
                    ['key' => 'A', 'content' => 'React', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Laravel', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Vue', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Angular', 'is_correct' => false],
                ]
            ],

            // Multiple choice - 4 câu
            [
                'category_id' => $categories['web'],
                'type' => 'multiple_choice',
                'difficulty' => 'easy',
                'content' => 'Ngôn ngữ nào cho Front-end?',
                'explanation' => 'HTML, CSS, JavaScript là ba ngôn ngữ cốt lõi.',
                'options' => [
                    ['key' => 'A', 'content' => 'HTML', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'CSS', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'JavaScript', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Python', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['web'],
                'type' => 'multiple_choice',
                'difficulty' => 'easy',
                'content' => 'Thẻ HTML nào tạo danh sách?',
                'explanation' => 'ul, ol, li dùng để tạo danh sách.',
                'options' => [
                    ['key' => 'A', 'content' => '<ul>', 'is_correct' => true],
                    ['key' => 'B', 'content' => '<ol>', 'is_correct' => true],
                    ['key' => 'C', 'content' => '<li>', 'is_correct' => true],
                    ['key' => 'D', 'content' => '<list>', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['web'],
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'HTTP status code lỗi client?',
                'explanation' => '400, 401, 404 là lỗi phía client.',
                'options' => [
                    ['key' => 'A', 'content' => '400 Bad Request', 'is_correct' => true],
                    ['key' => 'B', 'content' => '401 Unauthorized', 'is_correct' => true],
                    ['key' => 'C', 'content' => '404 Not Found', 'is_correct' => true],
                    ['key' => 'D', 'content' => '500 Server Error', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['web'],
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'Package manager cho JavaScript?',
                'explanation' => 'npm, yarn, pnpm là package managers.',
                'options' => [
                    ['key' => 'A', 'content' => 'npm', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'yarn', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'pnpm', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'composer', 'is_correct' => false],
                ]
            ],

            // Essay - 2 câu
            [
                'category_id' => $categories['web'],
                'type' => 'essay',
                'difficulty' => 'easy',
                'content' => 'JavaScript dùng để làm gì?',
                'explanation' => 'JavaScript tạo tính tương tác cho web.',
                'answer_text' => 'Tương tác',
            ],
            [
                'category_id' => $categories['web'],
                'type' => 'essay',
                'difficulty' => 'medium',
                'content' => 'Khác biệt class và id?',
                'explanation' => 'ID là duy nhất, class có thể dùng nhiều lần.',
                'answer_text' => 'ID duy nhất',
            ],

            // ===== Mạng & Bảo mật (10 câu) =====
            // Single choice - 4 câu
            [
                'category_id' => $categories['network'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'Mô hình OSI có mấy tầng?',
                'explanation' => 'Mô hình OSI có 7 tầng.',
                'options' => [
                    ['key' => 'A', 'content' => '5 tầng', 'is_correct' => false],
                    ['key' => 'B', 'content' => '7 tầng', 'is_correct' => true],
                    ['key' => 'C', 'content' => '4 tầng', 'is_correct' => false],
                    ['key' => 'D', 'content' => '6 tầng', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['network'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'Port của HTTPS là gì?',
                'explanation' => 'HTTPS sử dụng port 443.',
                'options' => [
                    ['key' => 'A', 'content' => '80', 'is_correct' => false],
                    ['key' => 'B', 'content' => '443', 'is_correct' => true],
                    ['key' => 'C', 'content' => '8080', 'is_correct' => false],
                    ['key' => 'D', 'content' => '22', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['network'],
                'type' => 'single_choice',
                'difficulty' => 'medium',
                'content' => 'VPN dùng để làm gì?',
                'explanation' => 'VPN tạo kết nối mã hóa an toàn.',
                'options' => [
                    ['key' => 'A', 'content' => 'Tăng tốc mạng', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Kết nối an toàn', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Lưu trữ dữ liệu', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Quét virus', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['network'],
                'type' => 'single_choice',
                'difficulty' => 'medium',
                'content' => 'DDoS attack là gì?',
                'explanation' => 'DDoS làm quá tải server với traffic.',
                'options' => [
                    ['key' => 'A', 'content' => 'Đánh cắp password', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Quá tải server', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Mã hóa dữ liệu', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Xóa database', 'is_correct' => false],
                ]
            ],

            // Multiple choice - 4 câu
            [
                'category_id' => $categories['network'],
                'type' => 'multiple_choice',
                'difficulty' => 'easy',
                'content' => 'Thiết bị mạng nào?',
                'explanation' => 'Router, Switch, Hub là thiết bị mạng.',
                'options' => [
                    ['key' => 'A', 'content' => 'Router', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Switch', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Hub', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Monitor', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['network'],
                'type' => 'multiple_choice',
                'difficulty' => 'easy',
                'content' => 'Protocol ở Transport Layer?',
                'explanation' => 'TCP và UDP ở Transport Layer.',
                'options' => [
                    ['key' => 'A', 'content' => 'TCP', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'UDP', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'HTTP', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'FTP', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['network'],
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'Loại tấn công mạng nào?',
                'explanation' => 'Phishing, Malware, DDoS là loại tấn công.',
                'options' => [
                    ['key' => 'A', 'content' => 'Phishing', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Malware', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'DDoS', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Antivirus', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['network'],
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'Encryption algorithms nào?',
                'explanation' => 'AES, RSA, SHA là thuật toán mã hóa.',
                'options' => [
                    ['key' => 'A', 'content' => 'AES', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'RSA', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'SHA', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'HTML', 'is_correct' => false],
                ]
            ],

            // Essay - 2 câu
            [
                'category_id' => $categories['network'],
                'type' => 'essay',
                'difficulty' => 'easy',
                'content' => 'Khác biệt TCP và UDP?',
                'explanation' => 'TCP đảm bảo delivery, UDP nhanh hơn.',
                'answer_text' => 'Tin cậy',
            ],
            [
                'category_id' => $categories['network'],
                'type' => 'essay',
                'difficulty' => 'medium',
                'content' => 'Firewall là gì?',
                'explanation' => 'Firewall kiểm soát traffic mạng.',
                'answer_text' => 'Kiểm soát',
            ],
        ];

        // Process all questions and store IDs by category
        $questionIdsByCategory = [
            'database' => [],
            'mobile' => [],
            'web' => [],
            'network' => [],
        ];

        foreach ($allQuestionsData as $qData) {
            $question = Question::create([
                'author_id'   => 1,
                'category_id' => $qData['category_id'],
                'content'     => $qData['content'],
                'explanation' => $qData['explanation'],
                'answer_text' => $qData['answer_text'] ?? null,
                'difficulty'  => $qData['difficulty'],
                'status'      => 'approved',
                'type'        => $qData['type'],
                'is_shared'   => true,
                'reviewed_by' => 1,
                'reviewed_at' => now(),
            ]);

            // Store question ID by category
            if ($qData['category_id'] == $categories['database']) {
                $questionIdsByCategory['database'][] = $question->id;
            } elseif ($qData['category_id'] == $categories['mobile']) {
                $questionIdsByCategory['mobile'][] = $question->id;
            } elseif ($qData['category_id'] == $categories['web']) {
                $questionIdsByCategory['web'][] = $question->id;
            } elseif ($qData['category_id'] == $categories['network']) {
                $questionIdsByCategory['network'][] = $question->id;
            }

            // Create options for single_choice and multiple_choice
            if (isset($qData['options']) && !empty($qData['options'])) {
                $options = [];
                foreach ($qData['options'] as $index => $opt) {
                    $options[] = [
                        'question_id' => $question->id,
                        'option_key'  => $opt['key'],
                        'content'     => $opt['content'],
                        'is_correct'  => $opt['is_correct'],
                        'sort_order'  => $index + 1,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ];
                }
                QuestionOption::insert($options);
            }
        }

        // Create 4 exams
        $examDatabase = Exam::create([
            'public_id'         => Str::uuid(),
            'author_id'         => 1,
            'category_id'       => $categories['database'],
            'title'             => 'Đề thi Cơ sở dữ liệu',
            'slug'              => 'de-thi-co-so-du-lieu',
            'short_description' => 'Đề thi về Cơ sở dữ liệu',
            'description'       => 'Bài thi bao gồm 10 câu hỏi về SQL, NoSQL, và các khái niệm database cơ bản.',
            'type'              => 'hybrid',
            'mode'              => 'practice',
            'duration_minutes'  => 25,
            'pass_percent'      => 50,
            'visibility'        => 'public',
            'status'            => 'approved',
            'reviewed_by'       => 1,
            'reviewed_at'       => now(),
            'publish_at'        => now(),
        ]);

        $examMobile = Exam::create([
            'public_id'         => Str::uuid(),
            'author_id'         => 1,
            'category_id'       => $categories['mobile'],
            'title'             => 'Đề thi Lập trình Mobile',
            'slug'              => 'de-thi-lap-trinh-mobile',
            'short_description' => 'Đề thi về Lập trình Mobile',
            'description'       => 'Bài thi bao gồm 10 câu hỏi về Android, iOS, Flutter và cross-platform development.',
            'type'              => 'hybrid',
            'mode'              => 'practice',
            'duration_minutes'  => 25,
            'pass_percent'      => 50,
            'visibility'        => 'public',
            'status'            => 'approved',
            'reviewed_by'       => 1,
            'reviewed_at'       => now(),
            'publish_at'        => now(),
        ]);

        $examWeb = Exam::create([
            'public_id'         => Str::uuid(),
            'author_id'         => 1,
            'category_id'       => $categories['web'],
            'title'             => 'Đề thi Lập trình Web',
            'slug'              => 'de-thi-lap-trinh-web',
            'short_description' => 'Đề thi về Lập trình Web',
            'description'       => 'Bài thi bao gồm 10 câu hỏi về HTML, CSS, JavaScript và web frameworks.',
            'type'              => 'hybrid',
            'mode'              => 'practice',
            'duration_minutes'  => 25,
            'pass_percent'      => 50,
            'visibility'        => 'public',
            'status'            => 'approved',
            'reviewed_by'       => 1,
            'reviewed_at'       => now(),
            'publish_at'        => now(),
        ]);

        $examNetwork = Exam::create([
            'public_id'         => Str::uuid(),
            'author_id'         => 1,
            'category_id'       => $categories['network'],
            'title'             => 'Đề thi Mạng & Bảo mật',
            'slug'              => 'de-thi-mang-va-bao-mat',
            'short_description' => 'Đề thi về Mạng & Bảo mật',
            'description'       => 'Bài thi bao gồm 10 câu hỏi về mạng máy tính, protocols và security.',
            'type'              => 'hybrid',
            'mode'              => 'practice',
            'duration_minutes'  => 25,
            'pass_percent'      => 50,
            'visibility'        => 'public',
            'status'            => 'approved',
            'reviewed_by'       => 1,
            'reviewed_at'       => now(),
            'publish_at'        => now(),
        ]);

        // Sync questions to exams
        $syncDataDatabase = [];
        foreach ($questionIdsByCategory['database'] as $index => $qId) {
            $syncDataDatabase[$qId] = [
                'sort_order' => $index + 1,
                'score'      => 1,
            ];
        }
        $examDatabase->questions()->sync($syncDataDatabase);

        $syncDataMobile = [];
        foreach ($questionIdsByCategory['mobile'] as $index => $qId) {
            $syncDataMobile[$qId] = [
                'sort_order' => $index + 1,
                'score'      => 1,
            ];
        }
        $examMobile->questions()->sync($syncDataMobile);

        $syncDataWeb = [];
        foreach ($questionIdsByCategory['web'] as $index => $qId) {
            $syncDataWeb[$qId] = [
                'sort_order' => $index + 1,
                'score'      => 1,
            ];
        }
        $examWeb->questions()->sync($syncDataWeb);

        $syncDataNetwork = [];
        foreach ($questionIdsByCategory['network'] as $index => $qId) {
            $syncDataNetwork[$qId] = [
                'sort_order' => $index + 1,
                'score'      => 1,
            ];
        }
        $examNetwork->questions()->sync($syncDataNetwork);
    }
}
