<?php

namespace Modules\Exam\database\seeders;

use Illuminate\Database\Seeder;
use Str;
use Modules\Exam\Models\Question;
use Modules\Exam\Models\QuestionOption;
use Modules\Exam\Models\Exam;
use Modules\Exam\Models\ExamAttempt;
use Modules\Exam\Models\AttemptAnswer;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         /*
         |--------------------------------------------------------------------------
         | Single Choice
         |--------------------------------------------------------------------------
         */
        $singleChoice = Question::create([
            'author_id'       => 1,
            'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
            'content'         => 'PHP là gì?',
            'explanation'     => 'PHP là một ngôn ngữ lập trình phía máy chủ (server-side scripting language).',
            'difficulty'      => 'easy',
            'status'          => 'approved',
            'type'            => 'single_choice',
            'reviewed_by'     => 1,
            'reviewed_at'     => now(),
        ]);

        QuestionOption::insert([
            [
                'question_id' => $singleChoice->id,
                'option_key'  => 'A',
                'content'     => 'Framework PHP',
                'is_correct'  => false,
                'sort_order'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'question_id' => $singleChoice->id,
                'option_key'  => 'B',
                'content'     => 'Ngôn ngữ lập trình',
                'is_correct'  => true,
                'sort_order'  => 2,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'question_id' => $singleChoice->id,
                'option_key'  => 'C',
                'content'     => 'Hệ quản trị cơ sở dữ liệu',
                'is_correct'  => false,
                'sort_order'  => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'question_id' => $singleChoice->id,
                'option_key'  => 'D',
                'content'     => 'Web Server',
                'is_correct'  => false,
                'sort_order'  => 4,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);

        /*
         |--------------------------------------------------------------------------
         | Multiple Choice
         |--------------------------------------------------------------------------
         */
        $multipleChoice = Question::create([
            'author_id'       => 1,
            'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
            'content'         => 'Những thành phần nào thuộc Laravel?',
            'explanation'     => 'Eloquent, Blade và Queue đều là thành phần của Laravel.',
            'difficulty'      => 'medium',
            'status'          => 'approved',
            'type'            => 'multiple_choice',
            'reviewed_by'     => 1,
            'reviewed_at'     => now(),
        ]);

        QuestionOption::insert([
            [
                'question_id' => $multipleChoice->id,
                'option_key'  => 'A',
                'content'     => 'Eloquent ORM',
                'is_correct'  => true,
                'sort_order'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'question_id' => $multipleChoice->id,
                'option_key'  => 'B',
                'content'     => 'Blade Template Engine',
                'is_correct'  => true,
                'sort_order'  => 2,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'question_id' => $multipleChoice->id,
                'option_key'  => 'C',
                'content'     => 'Docker Engine',
                'is_correct'  => false,
                'sort_order'  => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'question_id' => $multipleChoice->id,
                'option_key'  => 'D',
                'content'     => 'Queue System',
                'is_correct'  => true,
                'sort_order'  => 4,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);

        /*
         |--------------------------------------------------------------------------
         | Essay
         |--------------------------------------------------------------------------
         */
        Question::create([
            'author_id'       => 1,
            'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
            'content'         => 'Giải thích Service Container trong Laravel và lợi ích của nó.',
            'explanation'     => 'Service Container là nơi quản lý dependency và hỗ trợ Dependency Injection trong Laravel.',
            'difficulty'      => 'hard',
            'status'          => 'pending',
            'type'            => 'essay',
            'reviewed_by'     => null,
            'reviewed_at'     => null,
        ]);
        //Exam
        $exam = Exam::create([
        'public_id'         => Str::uuid(),
        'author_id'         => 1,
        'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
        'title'             => 'Đề thi Laravel cơ bản',
        'slug'              => 'de-thi-laravel-co-ban',
        'short_description' => 'Đề thi demo',
        'description'       => 'Đề thi dùng để test giao diện làm bài.',
        'type'              => 'hybrid',
        'mode'              => 'practice',
        'duration_minutes'  => 30,
        'pass_percent'      => 50,
        'visibility'        => 'public',
        'status'            => 'approved',
        'reviewed_by'       => 1,
        'reviewed_at'       => now(),
        'publish_at'        => now(),
        ]);
        //ExamQuestion
        $exam->questions()->sync([
            1 => [
                'sort_order' => 1,
                'score'      => 1,
            ],
            2 => [
                'sort_order' => 2,
                'score'      => 1,
            ],
            3 => [
                'sort_order' => 3,
                'score'      => 2,
            ],
        ]);
        /*
        |--------------------------------------------------------------------------
        | Exam Attempt
        |--------------------------------------------------------------------------
        */

        $attempt = ExamAttempt::create([
            'exam_id'           => $exam->id,
            'user_id'           => 1,
            'session_id'        => "test",

            'started_at'        => now()->subMinutes(15),
            // 'submitted_at'      => null,

            // 'expires_at'        => now()->addMinutes(15),

            'total_questions'   => 0,

            'correct_answers'   => 0,
            'wrong_answers'     => 0,
            'skipped_answers'   => 0,

            'score'             => 0,
            'percent_score'     => 0,

            'is_passed'         => false,

            'status'            => 'in_progress',

            'violation_count'   => 0,
        ]);
        // cau dung
        AttemptAnswer::create([
            'attempt_id'         => $attempt->id,
            'question_id'        => $singleChoice->id,

            'selected_option_ids'=> [2],

            'answer_text'        => null,

            'is_correct'         => true,

            'answered_at'        => now()->subMinutes(10),
        ]);
        //cau sai
        AttemptAnswer::create([
            'attempt_id'         => $attempt->id,
            'question_id'        => $multipleChoice->id,

            'selected_option_ids'=> [5, 6],

            'answer_text'        => null,

            'is_correct'         => false,

            'answered_at'        => now()->subMinutes(5),
        ]);
        //cau tu luan
        AttemptAnswer::create([
            'attempt_id'         => $attempt->id,
            'question_id'        => 3,

            'selected_option_ids'=> null,

            'answer_text'        => 'Service Container giúp quản lý dependency và hỗ trợ Dependency Injection.',

            'is_correct'         => true,

            'answered_at'        => now()->subMinute(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | 100 IT Questions (5 Categories × 20 Questions Each)
        | Categories: Lập trình Web, Lập trình Mobile, Trí tuệ nhân tạo (AI), 
        |             Cơ sở dữ liệu, Mạng & Bảo mật
        | Distribution per category: 8 easy, 6 medium, 6 hard
        |                           8 single_choice, 8 multiple_choice, 4 essay
        |--------------------------------------------------------------------------
        */
        
        // Get category IDs
        $categories = [
            'web' => \DB::table('categories')->where('name', 'LIKE', '%Lập trình Web%')->value('id') ?? 1,
            'mobile' => \DB::table('categories')->where('name', 'LIKE', '%Mobile%')->value('id') ?? 1,
            'ai' => \DB::table('categories')->where('name', 'LIKE', '%trí tuệ%')->orWhere('name', 'LIKE', '%AI%')->value('id') ?? 1,
            'database' => \DB::table('categories')->where('name', 'LIKE', '%Cơ sở dữ liệu%')->value('id') ?? 1,
            'network' => \DB::table('categories')->where('name', 'LIKE', '%Mạng%')->orWhere('name', 'LIKE', '%Bảo mật%')->value('id') ?? 1,
        ];

        $allQuestionsData = [
            // ===== Lập trình Web (20 câu) =====
            // Single choice - Easy (4 câu)
            [
                'category_id' => $categories['web'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'HTML là viết tắt của từ gì?',
                'explanation' => 'HTML là viết tắt của HyperText Markup Language, ngôn ngữ đánh dấu siêu văn bản.',
                'options' => [
                    ['key' => 'A', 'content' => 'HyperText Markup Language', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'HighText Machine Language', 'is_correct' => false],
                    ['key' => 'C', 'content' => 'Hyperlink and Text Markup Language', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Home Tool Markup Language', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['web'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'Thẻ HTML nào dùng để tạo liên kết?',
                'explanation' => 'Thẻ <a> (anchor) dùng để tạo hyperlink trong HTML.',
                'options' => [
                    ['key' => 'A', 'content' => '<link>', 'is_correct' => false],
                    ['key' => 'B', 'content' => '<a>', 'is_correct' => true],
                    ['key' => 'C', 'content' => '<href>', 'is_correct' => false],
                    ['key' => 'D', 'content' => '<url>', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['web'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'CSS là viết tắt của gì?',
                'explanation' => 'CSS là Cascading Style Sheets, dùng để tạo kiểu cho HTML.',
                'options' => [
                    ['key' => 'A', 'content' => 'Computer Style Sheets', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Cascading Style Sheets', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Creative Style System', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Colorful Style Sheets', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['web'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'Phương thức HTTP nào dùng để lấy dữ liệu từ server?',
                'explanation' => 'GET là phương thức HTTP dùng để yêu cầu dữ liệu từ server.',
                'options' => [
                    ['key' => 'A', 'content' => 'POST', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'GET', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'PUT', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'DELETE', 'is_correct' => false],
                ]
            ],
            // Single choice - Medium (2 câu)
            [
                'category_id' => $categories['web'],
                'type' => 'single_choice',
                'difficulty' => 'medium',
                'content' => 'Trong JavaScript, phương thức nào dùng để thêm phần tử vào cuối mảng?',
                'explanation' => 'push() thêm phần tử vào cuối mảng và trả về độ dài mới.',
                'options' => [
                    ['key' => 'A', 'content' => 'append()', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'push()', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'add()', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'insert()', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['web'],
                'type' => 'single_choice',
                'difficulty' => 'medium',
                'content' => 'Framework nào sau đây KHÔNG phải là JavaScript framework?',
                'explanation' => 'Laravel là PHP framework, còn React, Vue và Angular là JavaScript frameworks.',
                'options' => [
                    ['key' => 'A', 'content' => 'React', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Vue', 'is_correct' => false],
                    ['key' => 'C', 'content' => 'Laravel', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Angular', 'is_correct' => false],
                ]
            ],
            // Single choice - Hard (2 câu)
            [
                'category_id' => $categories['web'],
                'type' => 'single_choice',
                'difficulty' => 'hard',
                'content' => 'CORS (Cross-Origin Resource Sharing) được triển khai để giải quyết vấn đề gì?',
                'explanation' => 'CORS cho phép server kiểm soát việc chia sẻ tài nguyên với các domain khác, giải quyết Same-Origin Policy.',
                'options' => [
                    ['key' => 'A', 'content' => 'Tăng tốc độ tải trang', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Bảo mật cross-origin requests', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Cache dữ liệu', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Nén file CSS', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['web'],
                'type' => 'single_choice',
                'difficulty' => 'hard',
                'content' => 'Trong HTTP/2, tính năng nào giúp gửi nhiều request đồng thời trên một connection?',
                'explanation' => 'Multiplexing cho phép gửi và nhận nhiều request/response đồng thời trên cùng một TCP connection.',
                'options' => [
                    ['key' => 'A', 'content' => 'Server Push', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Multiplexing', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Header Compression', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Binary Framing', 'is_correct' => false],
                ]
            ],
            // Multiple choice - Easy (3 câu)
            [
                'category_id' => $categories['web'],
                'type' => 'multiple_choice',
                'difficulty' => 'easy',
                'content' => 'Những ngôn ngữ nào được sử dụng trong phát triển Front-end?',
                'explanation' => 'HTML, CSS và JavaScript là ba ngôn ngữ cốt lõi của Front-end development.',
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
                'content' => 'Thẻ HTML nào được dùng để tạo danh sách?',
                'explanation' => '<ul> tạo danh sách không thứ tự, <ol> tạo danh sách có thứ tự, <li> là phần tử danh sách.',
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
                'difficulty' => 'easy',
                'content' => 'Thuộc tính CSS nào dùng để điều chỉnh khoảng cách?',
                'explanation' => 'margin điều chỉnh khoảng cách bên ngoài, padding điều chỉnh khoảng cách bên trong element.',
                'options' => [
                    ['key' => 'A', 'content' => 'margin', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'padding', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'color', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'font-size', 'is_correct' => false],
                ]
            ],
            // Multiple choice - Medium (3 câu)
            [
                'category_id' => $categories['web'],
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'Những HTTP status code nào cho biết lỗi phía client?',
                'explanation' => '4xx status codes biểu thị lỗi phía client. 400: Bad Request, 401: Unauthorized, 404: Not Found.',
                'options' => [
                    ['key' => 'A', 'content' => '400 Bad Request', 'is_correct' => true],
                    ['key' => 'B', 'content' => '401 Unauthorized', 'is_correct' => true],
                    ['key' => 'C', 'content' => '404 Not Found', 'is_correct' => true],
                    ['key' => 'D', 'content' => '500 Internal Server Error', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['web'],
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'CSS Flexbox có những thuộc tính nào?',
                'explanation' => 'justify-content, align-items và flex-direction đều là thuộc tính của Flexbox.',
                'options' => [
                    ['key' => 'A', 'content' => 'justify-content', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'align-items', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'flex-direction', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'table-layout', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['web'],
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'Những công cụ nào dùng để quản lý package trong JavaScript?',
                'explanation' => 'npm và yarn là các package manager phổ biến, pnpm là alternative hiệu suất cao.',
                'options' => [
                    ['key' => 'A', 'content' => 'npm', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'yarn', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'pnpm', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'composer', 'is_correct' => false],
                ]
            ],
            // Multiple choice - Hard (2 câu)
            [
                'category_id' => $categories['web'],
                'type' => 'multiple_choice',
                'difficulty' => 'hard',
                'content' => 'Những kỹ thuật nào giúp tối ưu hiệu suất web?',
                'explanation' => 'Code splitting, lazy loading và tree shaking đều là kỹ thuật tối ưu hiệu suất.',
                'options' => [
                    ['key' => 'A', 'content' => 'Code splitting', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Lazy loading', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Tree shaking', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Inline all CSS', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['web'],
                'type' => 'multiple_choice',
                'difficulty' => 'hard',
                'content' => 'Web API nào cho phép lưu trữ dữ liệu ở phía client?',
                'explanation' => 'localStorage, sessionStorage và IndexedDB đều là storage APIs ở phía client.',
                'options' => [
                    ['key' => 'A', 'content' => 'localStorage', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'sessionStorage', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'IndexedDB', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'MySQL', 'is_correct' => false],
                ]
            ],
            // Essay - Easy (2 câu)
            [
                'category_id' => $categories['web'],
                'type' => 'essay',
                'difficulty' => 'easy',
                'content' => 'Giải thích vai trò của JavaScript trong phát triển web.',
                'explanation' => 'JavaScript tạo tính tương tác, xử lý sự kiện, thao tác DOM, và giao tiếp với server qua AJAX/Fetch.',
                'answer_text' => 'Tương tác động',
            ],
            [
                'category_id' => $categories['web'],
                'type' => 'essay',
                'difficulty' => 'easy',
                'content' => 'Sự khác biệt giữa class và id trong HTML/CSS là gì?',
                'explanation' => 'ID là duy nhất cho một element, class có thể dùng cho nhiều elements. ID có độ ưu tiên CSS cao hơn.',
                'answer_text' => 'ID duy nhất',
            ],
            // Essay - Medium (1 câu)
            [
                'category_id' => $categories['web'],
                'type' => 'essay',
                'difficulty' => 'medium',
                'content' => 'So sánh REST API và GraphQL.',
                'explanation' => 'REST dùng nhiều endpoints, GraphQL dùng một endpoint với query linh hoạt. GraphQL tránh over-fetching nhưng phức tạp hơn.',
                'answer_text' => 'Endpoints vs query',
            ],
            // Essay - Hard (1 câu)
            [
                'category_id' => $categories['web'],
                'type' => 'essay',
                'difficulty' => 'hard',
                'content' => 'Giải thích cơ chế hoạt động của Virtual DOM trong React.',
                'explanation' => 'Virtual DOM là bản sao của DOM, React so sánh (diffing) và chỉ cập nhật những thay đổi cần thiết lên Real DOM.',
                'answer_text' => 'Diffing và reconciliation',
            ],

            // ===== Lập trình Mobile (20 câu) =====
            // Single choice - Easy (4 câu)
            [
                'category_id' => $categories['mobile'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'Hệ điều hành nào được phát triển bởi Google?',
                'explanation' => 'Android là hệ điều hành mobile được Google phát triển và duy trì.',
                'options' => [
                    ['key' => 'A', 'content' => 'iOS', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Android', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Windows Phone', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'BlackBerry OS', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['mobile'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'Ngôn ngữ nào được Apple khuyến nghị để phát triển ứng dụng iOS?',
                'explanation' => 'Swift là ngôn ngữ chính thức được Apple phát triển cho iOS/macOS development.',
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
                'difficulty' => 'easy',
                'content' => 'Framework nào cho phép viết code một lần chạy trên cả iOS và Android?',
                'explanation' => 'Flutter là cross-platform framework của Google cho phép phát triển ứng dụng iOS và Android.',
                'options' => [
                    ['key' => 'A', 'content' => 'Android Studio', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Flutter', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Xcode', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Visual Studio', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['mobile'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'IDE chính thức để phát triển ứng dụng Android là gì?',
                'explanation' => 'Android Studio là IDE chính thức từ Google cho Android development.',
                'options' => [
                    ['key' => 'A', 'content' => 'Eclipse', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Visual Studio Code', 'is_correct' => false],
                    ['key' => 'C', 'content' => 'Android Studio', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'NetBeans', 'is_correct' => false],
                ]
            ],
            // ===== JavaScript (category_id = 4) =====
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'Từ khóa nào dùng để khai báo biến có phạm vi block trong JavaScript?',
                'explanation' => 'let và const có phạm vi block (block scope), còn var có phạm vi function.',
                'options' => [
                    ['key' => 'A', 'content' => 'var',   'is_correct' => false],
                    ['key' => 'B', 'content' => 'let',   'is_correct' => true],
                    ['key' => 'C', 'content' => 'dim',   'is_correct' => false],
                    ['key' => 'D', 'content' => 'set',   'is_correct' => false],
                ],
            ],
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'type' => 'single_choice',
                'difficulty' => 'medium',
                'content' => 'Phương thức nào dùng để chuyển chuỗi JSON thành đối tượng JavaScript?',
                'explanation' => 'JSON.parse() dùng để chuyển chuỗi JSON thành đối tượng JavaScript.',
                'options' => [
                    ['key' => 'A', 'content' => 'JSON.stringify()', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'JSON.parse()',     'is_correct' => true],
                    ['key' => 'C', 'content' => 'JSON.toObject()',  'is_correct' => false],
                    ['key' => 'D', 'content' => 'JSON.convert()',   'is_correct' => false],
                ],
            ],
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'Những phương thức nào thuộc Array trong JavaScript?',
                'explanation' => 'map(), filter() và reduce() đều là phương thức của Array prototype.',
                'options' => [
                    ['key' => 'A', 'content' => 'map()',    'is_correct' => true],
                    ['key' => 'B', 'content' => 'filter()', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'reduce()', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'query()',  'is_correct' => false],
                ],
            ],
            // Single choice - Medium (2 câu)
            [
                'category_id' => $categories['mobile'],
                'type' => 'single_choice',
                'difficulty' => 'medium',
                'content' => 'Trong Android, component nào dùng để hiển thị giao diện người dùng?',
                'explanation' => 'Activity là component chính dùng để hiển thị UI trong Android.',
                'options' => [
                    ['key' => 'A', 'content' => 'Service', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Activity', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Broadcast Receiver', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Content Provider', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['mobile'],
                'type' => 'single_choice',
                'difficulty' => 'medium',
                'content' => 'Ngôn ngữ nào Flutter sử dụng để phát triển?',
                'explanation' => 'Flutter sử dụng Dart programming language.',
                'options' => [
                    ['key' => 'A', 'content' => 'JavaScript', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Dart', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Kotlin', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Swift', 'is_correct' => false],
                ]
            ],
            // Single choice - Hard (2 câu)
            [
                'category_id' => $categories['mobile'],
                'type' => 'single_choice',
                'difficulty' => 'hard',
                'content' => 'Trong iOS, pattern nào được khuyến nghị cho việc quản lý state?',
                'explanation' => 'MVVM (Model-View-ViewModel) là pattern phổ biến trong iOS development với SwiftUI.',
                'options' => [
                    ['key' => 'A', 'content' => 'Singleton', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'MVVM', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Factory', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Observer', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['mobile'],
                'type' => 'single_choice',
                'difficulty' => 'hard',
                'content' => 'Jetpack Compose trong Android sử dụng paradigm nào?',
                'explanation' => 'Jetpack Compose sử dụng declarative UI paradigm, tương tự SwiftUI và React.',
                'options' => [
                    ['key' => 'A', 'content' => 'Imperative UI', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Declarative UI', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Procedural UI', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Object-oriented UI', 'is_correct' => false],
                ]
            ],
            // Multiple choice - Easy (3 câu)
            [
                'category_id' => $categories['mobile'],
                'type' => 'multiple_choice',
                'difficulty' => 'easy',
                'content' => 'Những nền tảng nào có thể phát triển ứng dụng mobile?',
                'explanation' => 'iOS, Android và Windows Phone đều là nền tảng mobile (dù Windows Phone đã ngừng).',
                'options' => [
                    ['key' => 'A', 'content' => 'iOS', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Android', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Windows Phone', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Linux Desktop', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['mobile'],
                'type' => 'multiple_choice',
                'difficulty' => 'easy',
                'content' => 'Framework nào cho phép phát triển cross-platform mobile?',
                'explanation' => 'Flutter, React Native và Xamarin đều là cross-platform frameworks.',
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
                'content' => 'Ngôn ngữ nào được dùng để phát triển Android native?',
                'explanation' => 'Java và Kotlin là ngôn ngữ chính thức cho Android development.',
                'options' => [
                    ['key' => 'A', 'content' => 'Java', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Kotlin', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Swift', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Ruby', 'is_correct' => false],
                ]
            ],
            // Multiple choice - Medium (3 câu)
            [
                'category_id' => $categories['mobile'],
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'Android cung cấp những loại storage nào?',
                'explanation' => 'Android có SharedPreferences (key-value), SQLite (database), và Internal/External Storage (files).',
                'options' => [
                    ['key' => 'A', 'content' => 'SharedPreferences', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'SQLite Database', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Internal Storage', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Redis', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['mobile'],
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'iOS lifecycle methods nào được gọi khi app vào foreground?',
                'explanation' => 'applicationWillEnterForeground và applicationDidBecomeActive được gọi khi app active.',
                'options' => [
                    ['key' => 'A', 'content' => 'applicationWillEnterForeground', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'applicationDidBecomeActive', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'applicationDidEnterBackground', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'applicationWillTerminate', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['mobile'],
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'Công cụ nào dùng để test mobile apps?',
                'explanation' => 'Espresso (Android), XCTest (iOS) và Appium (cross-platform) là các testing frameworks.',
                'options' => [
                    ['key' => 'A', 'content' => 'Espresso', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'XCTest', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Appium', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'JUnit', 'is_correct' => false],
                ]
            ],
            // Multiple choice - Hard (2 câu)
            [
                'category_id' => $categories['mobile'],
                'type' => 'multiple_choice',
                'difficulty' => 'hard',
                'content' => 'Kỹ thuật nào giúp tối ưu hiệu suất mobile app?',
                'explanation' => 'Lazy loading, image caching và code splitting đều giúp tối ưu hiệu suất mobile.',
                'options' => [
                    ['key' => 'A', 'content' => 'Lazy loading', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Image caching', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Code splitting', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Inline all resources', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['mobile'],
                'type' => 'multiple_choice',
                'difficulty' => 'hard',
                'content' => 'Architecture patterns nào phù hợp cho mobile apps?',
                'explanation' => 'MVC, MVP và MVVM đều là các architecture patterns phổ biến trong mobile development.',
                'options' => [
                    ['key' => 'A', 'content' => 'MVC', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'MVP', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'MVVM', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Microservices', 'is_correct' => false],
                ]
            ],
            // Essay - Easy (2 câu)
            [
                'category_id' => $categories['mobile'],
                'type' => 'essay',
                'difficulty' => 'easy',
                'content' => 'So sánh native app và hybrid app.',
                'explanation' => 'Native app được viết bằng ngôn ngữ gốc của platform (Swift/Kotlin), hiệu suất cao. Hybrid app dùng web technologies, phát triển nhanh hơn.',
                'answer_text' => 'Native nhanh hơn',
            ],
            [
                'category_id' => $categories['mobile'],
                'type' => 'essay',
                'difficulty' => 'easy',
                'content' => 'Giải thích khái niệm responsive design trong mobile.',
                'explanation' => 'Responsive design làm cho giao diện tự động điều chỉnh phù hợp với nhiều kích thước màn hình khác nhau.',
                'answer_text' => 'Tự động điều chỉnh',
            ],
            // Essay - Medium (1 câu)
            [
                'category_id' => $categories['mobile'],
                'type' => 'essay',
                'difficulty' => 'medium',
                'content' => 'Giải thích vòng đời (lifecycle) của một Activity trong Android.',
                'explanation' => 'Activity lifecycle bao gồm: onCreate() → onStart() → onResume() → onPause() → onStop() → onDestroy().',
                'answer_text' => 'Create Start Resume',
            ],
            // Essay - Hard (1 câu)
            [
                'category_id' => $categories['mobile'],
                'type' => 'essay',
                'difficulty' => 'hard',
                'content' => 'Phân tích ưu nhược điểm của Flutter so với React Native.',
                'explanation' => 'Flutter: hiệu suất tốt hơn, UI nhất quán, nhưng ecosystem nhỏ hơn. React Native: ecosystem lớn, dễ tìm developer, nhưng performance bridge có overhead.',
                'answer_text' => 'Flutter nhanh hơn',
            ],

            // ===== Trí tuệ nhân tạo - AI (20 câu) =====
            // Single choice - Easy (4 câu)
            [
                'category_id' => $categories['ai'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'AI là viết tắt của từ gì?',
                'explanation' => 'AI là Artificial Intelligence - Trí tuệ nhân tạo.',
                'options' => [
                    ['key' => 'A', 'content' => 'Automated Intelligence', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Artificial Intelligence', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Advanced Integration', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Algorithmic Information', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['ai'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'Machine Learning thuộc lĩnh vực nào?',
                'explanation' => 'Machine Learning là một nhánh của Artificial Intelligence.',
                'options' => [
                    ['key' => 'A', 'content' => 'Web Development', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Artificial Intelligence', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Network Security', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Database Management', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['ai'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'Thuật toán nào được dùng cho classification problems?',
                'explanation' => 'Decision Tree là thuật toán classification phổ biến trong ML.',
                'options' => [
                    ['key' => 'A', 'content' => 'Bubble Sort', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Decision Tree', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Binary Search', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Quick Sort', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['ai'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'Neural Network được lấy cảm hứng từ đâu?',
                'explanation' => 'Neural Network mô phỏng cách hoạt động của não bộ con người.',
                'options' => [
                    ['key' => 'A', 'content' => 'Hệ thống máy tính', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Não bộ con người', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Mạng internet', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Cơ sở dữ liệu', 'is_correct' => false],
                ]
            ],
            // Single choice - Medium (2 câu)
            [
                'category_id' => $categories['ai'],
                'type' => 'single_choice',
                'difficulty' => 'medium',
                'content' => 'Trong supervised learning, model học từ dữ liệu nào?',
                'explanation' => 'Supervised learning sử dụng labeled data (dữ liệu có nhãn) để training.',
                'options' => [
                    ['key' => 'A', 'content' => 'Unlabeled data', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Labeled data', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Random data', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Synthetic data', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['ai'],
                'type' => 'single_choice',
                'difficulty' => 'medium',
                'content' => 'Overfitting xảy ra khi nào?',
                'explanation' => 'Overfitting xảy ra khi model học quá tốt trên training data nhưng kém trên test data.',
                'options' => [
                    ['key' => 'A', 'content' => 'Model quá đơn giản', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Model học thuộc training data', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Thiếu dữ liệu', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Learning rate quá thấp', 'is_correct' => false],
                ]
            ],
            // Single choice - Hard (2 câu)
            [
                'category_id' => $categories['ai'],
                'type' => 'single_choice',
                'difficulty' => 'hard',
                'content' => 'Gradient Descent được dùng để làm gì?',
                'explanation' => 'Gradient Descent là thuật toán tối ưu hóa để minimize loss function.',
                'options' => [
                    ['key' => 'A', 'content' => 'Tăng accuracy', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Minimize loss function', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Tạo dữ liệu mới', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Split dataset', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['ai'],
                'type' => 'single_choice',
                'difficulty' => 'hard',
                'content' => 'Backpropagation được dùng trong training phase nào của neural network?',
                'explanation' => 'Backpropagation được dùng để tính gradients và update weights trong training.',
                'options' => [
                    ['key' => 'A', 'content' => 'Data preprocessing', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Weight initialization', 'is_correct' => false],
                    ['key' => 'C', 'content' => 'Gradient computation', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Model evaluation', 'is_correct' => false],
                ]
            ],
            // Multiple choice - Easy (3 câu)
            [
                'category_id' => $categories['ai'],
                'type' => 'multiple_choice',
                'difficulty' => 'easy',
                'content' => 'Những loại Machine Learning nào sau đây?',
                'explanation' => 'Supervised, Unsupervised và Reinforcement Learning là 3 loại ML chính.',
                'options' => [
                    ['key' => 'A', 'content' => 'Supervised Learning', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Unsupervised Learning', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Reinforcement Learning', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Manual Learning', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['ai'],
                'type' => 'multiple_choice',
                'difficulty' => 'easy',
                'content' => 'Thư viện Python nào phổ biến cho Deep Learning?',
                'explanation' => 'TensorFlow, PyTorch và Keras là các thư viện Deep Learning phổ biến.',
                'options' => [
                    ['key' => 'A', 'content' => 'TensorFlow', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'PyTorch', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Keras', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Django', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['ai'],
                'type' => 'multiple_choice',
                'difficulty' => 'easy',
                'content' => 'Ứng dụng nào của AI trong đời sống?',
                'explanation' => 'Face recognition, voice assistant và recommendation systems là ứng dụng AI phổ biến.',
                'options' => [
                    ['key' => 'A', 'content' => 'Face Recognition', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Voice Assistants', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Recommendation Systems', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'File Compression', 'is_correct' => false],
                ]
            ],
            // Multiple choice - Medium (3 câu)
            [
                'category_id' => $categories['ai'],
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'Metrics nào dùng để đánh giá classification model?',
                'explanation' => 'Accuracy, Precision, Recall và F1-score là các metrics đánh giá classification.',
                'options' => [
                    ['key' => 'A', 'content' => 'Accuracy', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Precision', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Recall', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Bandwidth', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['ai'],
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'Kỹ thuật nào giúp tránh overfitting?',
                'explanation' => 'Regularization, Dropout và Cross-validation đều giúp giảm overfitting.',
                'options' => [
                    ['key' => 'A', 'content' => 'Regularization', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Dropout', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Cross-validation', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Increase model complexity', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['ai'],
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'Loại Neural Network nào được dùng cho image processing?',
                'explanation' => 'CNN (Convolutional Neural Network) được thiết kế đặc biệt cho xử lý ảnh.',
                'options' => [
                    ['key' => 'A', 'content' => 'CNN', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'ResNet', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'VGG', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'LSTM', 'is_correct' => false],
                ]
            ],
            // Multiple choice - Hard (2 câu)
            [
                'category_id' => $categories['ai'],
                'type' => 'multiple_choice',
                'difficulty' => 'hard',
                'content' => 'Activation functions nào được dùng trong neural networks?',
                'explanation' => 'ReLU, Sigmoid và Tanh là các activation functions phổ biến.',
                'options' => [
                    ['key' => 'A', 'content' => 'ReLU', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Sigmoid', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Tanh', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Binary Search', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['ai'],
                'type' => 'multiple_choice',
                'difficulty' => 'hard',
                'content' => 'Optimizer nào được sử dụng để training deep learning models?',
                'explanation' => 'Adam, SGD và RMSprop là các optimization algorithms phổ biến.',
                'options' => [
                    ['key' => 'A', 'content' => 'Adam', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'SGD', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'RMSprop', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Bubble Sort', 'is_correct' => false],
                ]
            ],
            // Essay - Easy (2 câu)
            [
                'category_id' => $categories['ai'],
                'type' => 'essay',
                'difficulty' => 'easy',
                'content' => 'Phân biệt giữa AI, Machine Learning và Deep Learning.',
                'explanation' => 'AI là khái niệm rộng nhất. ML là subset của AI. Deep Learning là subset của ML sử dụng neural networks.',
                'answer_text' => 'AI chứa ML',
            ],
            [
                'category_id' => $categories['ai'],
                'type' => 'essay',
                'difficulty' => 'easy',
                'content' => 'Giải thích khái niệm Training và Testing trong Machine Learning.',
                'explanation' => 'Training là quá trình model học từ dữ liệu. Testing là đánh giá model trên dữ liệu chưa thấy.',
                'answer_text' => 'Học và kiểm tra',
            ],
            // Essay - Medium (1 câu)
            [
                'category_id' => $categories['ai'],
                'type' => 'essay',
                'difficulty' => 'medium',
                'content' => 'So sánh Supervised Learning và Unsupervised Learning.',
                'explanation' => 'Supervised learning cần labeled data, dùng cho classification/regression. Unsupervised learning dùng unlabeled data, dùng cho clustering/dimensionality reduction.',
                'answer_text' => 'Label vs unlabel',
            ],
            // Essay - Hard (1 câu)
            [
                'category_id' => $categories['ai'],
                'type' => 'essay',
                'difficulty' => 'hard',
                'content' => 'Giải thích cơ chế hoạt động của Convolutional Neural Network (CNN).',
                'explanation' => 'CNN sử dụng convolutional layers để extract features từ ảnh, pooling layers giảm dimensions, fully connected layers để classification.',
                'answer_text' => 'Convolution và pooling',
            ],

            // ===== Cơ sở dữ liệu (20 câu) =====
            // Single choice - Easy (4 câu)
            [
                'category_id' => $categories['database'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'SQL là viết tắt của gì?',
                'explanation' => 'SQL là Structured Query Language - ngôn ngữ truy vấn có cấu trúc.',
                'options' => [
                    ['key' => 'A', 'content' => 'Simple Query Language', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Structured Query Language', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Standard Query Language', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Sequential Query Language', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['database'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'Lệnh SQL nào dùng để lấy dữ liệu từ bảng?',
                'explanation' => 'SELECT là lệnh dùng để truy vấn và lấy dữ liệu từ bảng.',
                'options' => [
                    ['key' => 'A', 'content' => 'GET', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'SELECT', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'FETCH', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'RETRIEVE', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['database'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'Primary Key được dùng để làm gì?',
                'explanation' => 'Primary Key xác định duy nhất mỗi bản ghi trong bảng.',
                'options' => [
                    ['key' => 'A', 'content' => 'Xác định duy nhất bản ghi', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Sắp xếp dữ liệu', 'is_correct' => false],
                    ['key' => 'C', 'content' => 'Mã hóa dữ liệu', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Backup dữ liệu', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['database'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'NoSQL database nào sau đây?',
                'explanation' => 'MongoDB là NoSQL database dạng document store.',
                'options' => [
                    ['key' => 'A', 'content' => 'MySQL', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'MongoDB', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'PostgreSQL', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Oracle', 'is_correct' => false],
                ]
            ],
            // Single choice - Medium (2 câu)
            [
                'category_id' => $categories['database'],
                'type' => 'single_choice',
                'difficulty' => 'medium',
                'content' => 'Normalization trong database design để làm gì?',
                'explanation' => 'Normalization giúp giảm redundancy và đảm bảo data integrity.',
                'options' => [
                    ['key' => 'A', 'content' => 'Tăng tốc độ query', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Giảm redundancy', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Tăng storage', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Encrypt data', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['database'],
                'type' => 'single_choice',
                'difficulty' => 'medium',
                'content' => 'ACID properties trong database transaction là gì?',
                'explanation' => 'ACID là Atomicity, Consistency, Isolation, Durability - đảm bảo tính toàn vẹn transaction.',
                'options' => [
                    ['key' => 'A', 'content' => 'Advanced Computing Integration Design', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Atomicity Consistency Isolation Durability', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Automated Cloud Integration Database', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Asynchronous Communication Interface Driver', 'is_correct' => false],
                ]
            ],
            // Single choice - Hard (2 câu)
            [
                'category_id' => $categories['database'],
                'type' => 'single_choice',
                'difficulty' => 'hard',
                'content' => 'Index trong database ảnh hưởng như thế nào đến performance?',
                'explanation' => 'Index tăng tốc SELECT nhưng làm chậm INSERT/UPDATE/DELETE do phải maintain index.',
                'options' => [
                    ['key' => 'A', 'content' => 'Tăng tốc tất cả operations', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Tăng tốc SELECT, chậm INSERT/UPDATE', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Không ảnh hưởng', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Giảm storage', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['database'],
                'type' => 'single_choice',
                'difficulty' => 'hard',
                'content' => 'CAP theorem trong distributed database là gì?',
                'explanation' => 'CAP theorem: không thể đồng thời đạt được Consistency, Availability và Partition tolerance.',
                'options' => [
                    ['key' => 'A', 'content' => 'Consistency, Availability, Performance', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Consistency, Availability, Partition tolerance', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Caching, Authorization, Performance', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Clustering, Authentication, Privacy', 'is_correct' => false],
                ]
            ],
            // Multiple choice - Easy (3 câu)
            [
                'category_id' => $categories['database'],
                'type' => 'multiple_choice',
                'difficulty' => 'easy',
                'content' => 'SQL commands nào thuộc loại DML (Data Manipulation Language)?',
                'explanation' => 'SELECT, INSERT, UPDATE, DELETE là DML commands dùng để thao tác dữ liệu.',
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
                'content' => 'Relational database nào phổ biến?',
                'explanation' => 'MySQL, PostgreSQL và Oracle là các relational database phổ biến.',
                'options' => [
                    ['key' => 'A', 'content' => 'MySQL', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'PostgreSQL', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Oracle', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Redis', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['database'],
                'type' => 'multiple_choice',
                'difficulty' => 'easy',
                'content' => 'Loại relationship nào có trong relational database?',
                'explanation' => 'One-to-One, One-to-Many và Many-to-Many là các loại relationship cơ bản.',
                'options' => [
                    ['key' => 'A', 'content' => 'One-to-One', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'One-to-Many', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Many-to-Many', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'None-to-None', 'is_correct' => false],
                ]
            ],
            // Multiple choice - Medium (3 câu)
            [
                'category_id' => $categories['database'],
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'Loại JOIN nào có trong SQL?',
                'explanation' => 'INNER JOIN, LEFT JOIN, RIGHT JOIN và FULL JOIN là các loại JOIN.',
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
                'content' => 'NoSQL database types nào?',
                'explanation' => 'Document, Key-Value và Graph là các loại NoSQL database.',
                'options' => [
                    ['key' => 'A', 'content' => 'Document Store', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Key-Value Store', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Graph Database', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Relational Database', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['database'],
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'Database isolation levels nào sau đây?',
                'explanation' => 'Read Uncommitted, Read Committed và Serializable là isolation levels.',
                'options' => [
                    ['key' => 'A', 'content' => 'Read Uncommitted', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Read Committed', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Serializable', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Read Forbidden', 'is_correct' => false],
                ]
            ],
            // Multiple choice - Hard (2 câu)
            [
                'category_id' => $categories['database'],
                'type' => 'multiple_choice',
                'difficulty' => 'hard',
                'content' => 'Database optimization techniques nào?',
                'explanation' => 'Indexing, Query optimization và Partitioning đều là kỹ thuật tối ưu database.',
                'options' => [
                    ['key' => 'A', 'content' => 'Indexing', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Query optimization', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Partitioning', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Deleting all data', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['database'],
                'type' => 'multiple_choice',
                'difficulty' => 'hard',
                'content' => 'Database replication strategies nào?',
                'explanation' => 'Master-Slave, Master-Master và Multi-Master là các replication strategies.',
                'options' => [
                    ['key' => 'A', 'content' => 'Master-Slave', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Master-Master', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Multi-Master', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'No-Master', 'is_correct' => false],
                ]
            ],
            // Essay - Easy (2 câu)
            [
                'category_id' => $categories['database'],
                'type' => 'essay',
                'difficulty' => 'easy',
                'content' => 'Sự khác biệt giữa SQL và NoSQL database.',
                'explanation' => 'SQL có schema cố định, dùng cho structured data. NoSQL flexible schema, dùng cho unstructured/semi-structured data.',
                'answer_text' => 'Schema fixed vs flexible',
            ],
            [
                'category_id' => $categories['database'],
                'type' => 'essay',
                'difficulty' => 'easy',
                'content' => 'Giải thích khái niệm Foreign Key trong database.',
                'explanation' => 'Foreign Key là trường tham chiếu đến Primary Key của bảng khác, tạo relationship giữa các bảng.',
                'answer_text' => 'Tham chiếu bảng khác',
            ],
            // Essay - Medium (1 câu)
            [
                'category_id' => $categories['database'],
                'type' => 'essay',
                'difficulty' => 'medium',
                'content' => 'Phân tích ưu nhược điểm của Database Normalization.',
                'explanation' => 'Ưu: giảm redundancy, data integrity. Nhược: phức tạp, nhiều JOIN làm chậm queries.',
                'answer_text' => 'Giảm redundancy nhiều JOIN',
            ],
            // Essay - Hard (1 câu)
            [
                'category_id' => $categories['database'],
                'type' => 'essay',
                'difficulty' => 'hard',
                'content' => 'Giải thích cơ chế hoạt động của Database Transaction và ACID properties.',
                'explanation' => 'Transaction là nhóm operations thực thi như một đơn vị. ACID đảm bảo: Atomicity (all or nothing), Consistency (valid state), Isolation (concurrent), Durability (persistent).',
                'answer_text' => 'All or nothing',
            ],

            // ===== Mạng & Bảo mật (20 câu) =====
            // Single choice - Easy (4 câu)
            [
                'category_id' => $categories['network'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'Mô hình OSI có bao nhiêu tầng?',
                'explanation' => 'Mô hình OSI (Open Systems Interconnection) có 7 tầng.',
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
                'content' => 'Giao thức nào hoạt động ở tầng Application?',
                'explanation' => 'HTTP là giao thức tầng Application trong mô hình OSI/TCP-IP.',
                'options' => [
                    ['key' => 'A', 'content' => 'HTTP', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'TCP', 'is_correct' => false],
                    ['key' => 'C', 'content' => 'IP', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Ethernet', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['network'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'IP address 192.168.1.1 thuộc class nào?',
                'explanation' => 'IP address bắt đầu với 192 thuộc Class C (192.0.0.0 - 223.255.255.255).',
                'options' => [
                    ['key' => 'A', 'content' => 'Class A', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Class B', 'is_correct' => false],
                    ['key' => 'C', 'content' => 'Class C', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Class D', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['network'],
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'Port mặc định của HTTPS là gì?',
                'explanation' => 'HTTPS (HTTP Secure) sử dụng port 443 mặc định.',
                'options' => [
                    ['key' => 'A', 'content' => '80', 'is_correct' => false],
                    ['key' => 'B', 'content' => '443', 'is_correct' => true],
                    ['key' => 'C', 'content' => '8080', 'is_correct' => false],
                    ['key' => 'D', 'content' => '22', 'is_correct' => false],
                ]
            ],
            // Single choice - Medium (2 câu)
            [
                'category_id' => $categories['network'],
                'type' => 'single_choice',
                'difficulty' => 'medium',
                'content' => 'Subnet mask 255.255.255.0 có bao nhiêu host addresses?',
                'explanation' => 'Subnet mask /24 (255.255.255.0) có 254 host addresses khả dụng (256 - 2 cho network và broadcast).',
                'options' => [
                    ['key' => 'A', 'content' => '256', 'is_correct' => false],
                    ['key' => 'B', 'content' => '254', 'is_correct' => true],
                    ['key' => 'C', 'content' => '255', 'is_correct' => false],
                    ['key' => 'D', 'content' => '128', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['network'],
                'type' => 'single_choice',
                'difficulty' => 'medium',
                'content' => 'VPN được dùng để làm gì?',
                'explanation' => 'VPN (Virtual Private Network) tạo kết nối mã hóa an toàn qua internet công cộng.',
                'options' => [
                    ['key' => 'A', 'content' => 'Tăng tốc độ internet', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Tạo kết nối mã hóa an toàn', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Lưu trữ dữ liệu', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Quét virus', 'is_correct' => false],
                ]
            ],
            // Single choice - Hard (2 câu)
            [
                'category_id' => $categories['network'],
                'type' => 'single_choice',
                'difficulty' => 'hard',
                'content' => 'Trong three-way handshake của TCP, bước nào xảy ra đầu tiên?',
                'explanation' => 'Three-way handshake: SYN từ client, SYN-ACK từ server, ACK từ client.',
                'options' => [
                    ['key' => 'A', 'content' => 'ACK', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'SYN', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'FIN', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'RST', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['network'],
                'type' => 'single_choice',
                'difficulty' => 'hard',
                'content' => 'DDoS attack hoạt động như thế nào?',
                'explanation' => 'DDoS (Distributed Denial of Service) dùng nhiều máy để gửi traffic tấn công làm quá tải server.',
                'options' => [
                    ['key' => 'A', 'content' => 'Đánh cắp password', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'Làm quá tải server với traffic', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Mã hóa dữ liệu', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Xóa database', 'is_correct' => false],
                ]
            ],
            // Multiple choice - Easy (3 câu)
            [
                'category_id' => $categories['network'],
                'type' => 'multiple_choice',
                'difficulty' => 'easy',
                'content' => 'Thiết bị mạng nào sau đây?',
                'explanation' => 'Router, Switch và Hub đều là các thiết bị mạng cơ bản.',
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
                'content' => 'Protocol nào thuộc Transport Layer?',
                'explanation' => 'TCP và UDP là hai giao thức chính ở Transport Layer.',
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
                'difficulty' => 'easy',
                'content' => 'Loại tấn công mạng nào phổ biến?',
                'explanation' => 'Phishing, Malware và DDoS là các dạng tấn công mạng phổ biến.',
                'options' => [
                    ['key' => 'A', 'content' => 'Phishing', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Malware', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'DDoS', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Antivirus', 'is_correct' => false],
                ]
            ],
            // Multiple choice - Medium (3 câu)
            [
                'category_id' => $categories['network'],
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'Firewall có những loại nào?',
                'explanation' => 'Packet filtering, Stateful inspection và Application firewall là các loại firewall.',
                'options' => [
                    ['key' => 'A', 'content' => 'Packet filtering', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Stateful inspection', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Application firewall', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Database firewall', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['network'],
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'Encryption algorithms nào được dùng phổ biến?',
                'explanation' => 'AES, RSA và SHA là các thuật toán mã hóa phổ biến.',
                'options' => [
                    ['key' => 'A', 'content' => 'AES', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'RSA', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'SHA', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'HTML', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['network'],
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'DNS records nào sau đây?',
                'explanation' => 'A, CNAME và MX là các loại DNS records thông dụng.',
                'options' => [
                    ['key' => 'A', 'content' => 'A Record', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'CNAME', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'MX Record', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'HTTP Record', 'is_correct' => false],
                ]
            ],
            // Multiple choice - Hard (2 câu)
            [
                'category_id' => $categories['network'],
                'type' => 'multiple_choice',
                'difficulty' => 'hard',
                'content' => 'Security best practices nào cho web applications?',
                'explanation' => 'HTTPS, Input validation và SQL injection prevention là security best practices quan trọng.',
                'options' => [
                    ['key' => 'A', 'content' => 'Use HTTPS', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Input validation', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Prevent SQL injection', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Disable all authentication', 'is_correct' => false],
                ]
            ],
            [
                'category_id' => $categories['network'],
                'type' => 'multiple_choice',
                'difficulty' => 'hard',
                'content' => 'Load balancing algorithms nào?',
                'explanation' => 'Round Robin, Least Connections và IP Hash là các load balancing algorithms.',
                'options' => [
                    ['key' => 'A', 'content' => 'Round Robin', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Least Connections', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'IP Hash', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Random Delete', 'is_correct' => false],
                ]
            ],
            // Essay - Easy (2 câu)
            [
                'category_id' => $categories['network'],
                'type' => 'essay',
                'difficulty' => 'easy',
                'content' => 'Phân biệt giữa TCP và UDP.',
                'explanation' => 'TCP là connection-oriented, đảm bảo delivery. UDP là connectionless, nhanh hơn nhưng không đảm bảo.',
                'answer_text' => 'Tin cậy vs nhanh',
            ],
            [
                'category_id' => $categories['network'],
                'type' => 'essay',
                'difficulty' => 'easy',
                'content' => 'Giải thích vai trò của Firewall trong bảo mật mạng.',
                'explanation' => 'Firewall giám sát và kiểm soát traffic ra vào mạng dựa trên security rules, ngăn chặn truy cập trái phép.',
                'answer_text' => 'Kiểm soát traffic',
            ],
            // Essay - Medium (1 câu)
            [
                'category_id' => $categories['network'],
                'type' => 'essay',
                'difficulty' => 'medium',
                'content' => 'Giải thích cơ chế hoạt động của SSL/TLS.',
                'explanation' => 'SSL/TLS mã hóa dữ liệu giữa client và server thông qua handshake protocol, sử dụng public/private key encryption.',
                'answer_text' => 'Mã hóa kết nối',
            ],
            // Essay - Hard (1 câu)
            [
                'category_id' => $categories['network'],
                'type' => 'essay',
                'difficulty' => 'hard',
                'content' => 'Phân tích các lớp bảo vệ trong Defense in Depth security strategy.',
                'explanation' => 'Defense in Depth dùng nhiều lớp bảo vệ: perimeter (firewall), network (IDS/IPS), host (antivirus), application (input validation), data (encryption).',
                'answer_text' => 'Nhiều lớp bảo vệ',
            ],
        ];

        // Process all 100 questions
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


        /*
        |--------------------------------------------------------------------------
        | 11 bài kiểm tra bổ sung (đa dạng type, mode, danh mục)
        |--------------------------------------------------------------------------
        */
        $bulkExamsData = [
            // Exam 1: JavaScript - multiple_choice - practice
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'title'             => 'Kiểm tra JavaScript cơ bản',
                'slug'              => 'kiem-tra-javascript-co-ban',
                'short_description' => 'Bài kiểm tra kiến thức JavaScript nền tảng cho người mới.',
                'description'       => 'Bao gồm câu hỏi về biến, kiểu dữ liệu, hàm và DOM cơ bản.',
                'type'              => 'multiple_choice',
                'mode'              => 'practice',
                'duration_minutes'  => 15,
            ],
            // Exam 2: JavaScript - hybrid - official
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'title'             => 'Đề thi JavaScript nâng cao',
                'slug'              => 'de-thi-javascript-nang-cao',
                'short_description' => 'Đề thi chuyên sâu về ES6+, Promise và pattern nâng cao.',
                'description'       => 'Đánh giá kiến thức nâng cao: async/await, closure, prototype chain.',
                'type'              => 'hybrid',
                'mode'              => 'official',
                'duration_minutes'  => 45,
            ],
            // Exam 3: Python - multiple_choice - practice
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'title'             => 'Kiểm tra Python cho người mới',
                'slug'              => 'kiem-tra-python-cho-nguoi-moi',
                'short_description' => 'Bài kiểm tra cú pháp Python cơ bản và cấu trúc dữ liệu.',
                'description'       => 'Bao gồm câu hỏi về biến, list, tuple, dictionary và vòng lặp.',
                'type'              => 'multiple_choice',
                'mode'              => 'practice',
                'duration_minutes'  => 20,
            ],
            // Exam 4: Python - hybrid - official
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'title'             => 'Đề thi Python - Data Science',
                'slug'              => 'de-thi-python-data-science',
                'short_description' => 'Đề thi về ứng dụng Python trong khoa học dữ liệu.',
                'description'       => 'Kiểm tra NumPy, Pandas, Matplotlib và Machine Learning cơ bản.',
                'type'              => 'hybrid',
                'mode'              => 'official',
                'duration_minutes'  => 60,
            ],
            // Exam 5: Java - multiple_choice - practice
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'title'             => 'Kiểm tra Java OOP',
                'slug'              => 'kiem-tra-java-oop',
                'short_description' => 'Bài kiểm tra về lập trình hướng đối tượng trong Java.',
                'description'       => 'Câu hỏi về class, object, inheritance, polymorphism, encapsulation.',
                'type'              => 'multiple_choice',
                'mode'              => 'practice',
                'duration_minutes'  => 25,
            ],
            // Exam 6: Java - essay - official
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'title'             => 'Đề thi Java - Design Patterns',
                'slug'              => 'de-thi-java-design-patterns',
                'short_description' => 'Đề thi về các mẫu thiết kế phổ biến trong Java.',
                'description'       => 'Factory, Singleton, Observer, Strategy và các design pattern.',
                'type'              => 'essay',
                'mode'              => 'official',
                'duration_minutes'  => 40,
            ],
            // Exam 7: Mạng máy tính - hybrid - practice
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'title'             => 'Kiểm tra Mạng máy tính cơ bản',
                'slug'              => 'kiem-tra-mang-may-tinh-co-ban',
                'short_description' => 'Bài kiểm tra kiến thức nền tảng về mạng máy tính.',
                'description'       => 'Câu hỏi về mô hình OSI, TCP/IP, địa chỉ IP và thiết bị mạng.',
                'type'              => 'hybrid',
                'mode'              => 'practice',
                'duration_minutes'  => 30,
            ],
            // Exam 8: Mạng máy tính - essay - official
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'title'             => 'Đề thi An ninh mạng',
                'slug'              => 'de-thi-an-ninh-mang',
                'short_description' => 'Đề thi về bảo mật mạng và kỹ thuật phòng chống tấn công.',
                'description'       => 'Firewall, VPN, mã hóa, SSL/TLS và các loại tấn công mạng.',
                'type'              => 'essay',
                'mode'              => 'official',
                'duration_minutes'  => 50,
            ],
            // Exam 9: Database - hybrid - official
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'title'             => 'Đề thi SQL nâng cao',
                'slug'              => 'de-thi-sql-nang-cao',
                'short_description' => 'Đề thi chuyên sâu về truy vấn SQL phức tạp.',
                'description'       => 'Subquery, JOIN phức tạp, window functions, index và optimization.',
                'type'              => 'hybrid',
                'mode'              => 'official',
                'duration_minutes'  => 35,
            ],
            // Exam 10: Laravel - hybrid - practice
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'title'             => 'Kiểm tra Laravel Eloquent',
                'slug'              => 'kiem-tra-laravel-eloquent',
                'short_description' => 'Bài kiểm tra về ORM Eloquent trong Laravel.',
                'description'       => 'Relationship, query builder, migration, seeder và model.',
                'type'              => 'hybrid',
                'mode'              => 'practice',
                'duration_minutes'  => 20,
            ],
            // Exam 11: PHP - essay - official
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'title'             => 'Đề thi PHP nâng cao',
                'slug'              => 'de-thi-php-nang-cao',
                'short_description' => 'Đề thi chuyên sâu về PHP: OOP, namespace, trait.',
                'description'       => 'Interface, abstract class, trait, autoloading, Composer.',
                'type'              => 'essay',
                'mode'              => 'official',
                'duration_minutes'  => 40,
            ],
        ];

        foreach ($bulkExamsData as $examData) {
            $bulkExam = Exam::create([
                'public_id'         => Str::uuid(),
                'author_id'         => 1,
                'category_id'       => $examData['category_id'],
                'title'             => $examData['title'],
                'slug'              => $examData['slug'],
                'short_description' => $examData['short_description'],
                'description'       => $examData['description'],
                'type'              => $examData['type'],
                'mode'              => $examData['mode'],
                'duration_minutes'  => $examData['duration_minutes'],
                'pass_percent'      => 50,
                'visibility'        => 'public',
                'status'            => 'approved',
                'reviewed_by'       => 1,
                'reviewed_at'       => now(),
                'publish_at'        => now(),
            ]);

            // Note: Questions can be manually attached to exams as needed
        }
    }
}
