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
        | 10 Easy Questions (Various Types)
        |--------------------------------------------------------------------------
        */
        $easyQuestionsData = [
            // Single Choice (4 câu)
            [
                'type' => 'single_choice',
                'content' => 'HTML là viết tắt của từ gì?',
                'explanation' => 'HTML là viết tắt của HyperText Markup Language.',
                'options' => [
                    ['key' => 'A', 'content' => 'HyperText Markup Language', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'HighText Machine Language', 'is_correct' => false],
                    ['key' => 'C', 'content' => 'Hyperlink and Text Markup Language', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Home Tool Markup Language', 'is_correct' => false],
                ]
            ],
            [
                'type' => 'single_choice',
                'content' => 'Ký hiệu nào dùng để khai báo biến trong PHP?',
                'explanation' => 'Trong PHP, tất cả các biến đều bắt đầu bằng ký hiệu đô la ($).',
                'options' => [
                    ['key' => 'A', 'content' => '$', 'is_correct' => true],
                    ['key' => 'B', 'content' => '#', 'is_correct' => false],
                    ['key' => 'C', 'content' => '@', 'is_correct' => false],
                    ['key' => 'D', 'content' => '%', 'is_correct' => false],
                ]
            ],
            [
                'type' => 'single_choice',
                'content' => 'Thẻ HTML nào dùng để tạo liên kết (hyperlink)?',
                'explanation' => 'Thẻ <a> dùng để tạo liên kết trong HTML.',
                'options' => [
                    ['key' => 'A', 'content' => '<a>', 'is_correct' => true],
                    ['key' => 'B', 'content' => '<link>', 'is_correct' => false],
                    ['key' => 'C', 'content' => '<href>', 'is_correct' => false],
                    ['key' => 'D', 'content' => '<a> liên kết', 'is_correct' => false],
                ]
            ],
            [
                'type' => 'single_choice',
                'content' => 'Đâu là một trình duyệt web phổ biến?',
                'explanation' => 'Google Chrome là một trình duyệt web phổ biến.',
                'options' => [
                    ['key' => 'A', 'content' => 'Google Chrome', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'Microsoft Word', 'is_correct' => false],
                    ['key' => 'C', 'content' => 'MySQL', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Photoshop', 'is_correct' => false],
                ]
            ],
            // Multiple Choice (3 câu)
            [
                'type' => 'multiple_choice',
                'content' => 'Những ngôn ngữ nào thường được dùng để phát triển Front-end của trang web?',
                'explanation' => 'HTML, CSS và JavaScript là 3 ngôn ngữ cốt lõi tạo nên giao diện và tương tác Front-end.',
                'options' => [
                    ['key' => 'A', 'content' => 'HTML', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'CSS', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'JavaScript', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'SQL', 'is_correct' => false],
                ]
            ],
            [
                'type' => 'multiple_choice',
                'content' => 'Những hệ điều hành nào sau đây dành cho thiết bị di động?',
                'explanation' => 'Android và iOS là hai hệ điều hành di động phổ biến nhất hiện nay.',
                'options' => [
                    ['key' => 'A', 'content' => 'Android', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'iOS', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'Windows Server', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'Ubuntu Desktop', 'is_correct' => false],
                ]
            ],
            [
                'type' => 'multiple_choice',
                'content' => 'Những giao thức nào thuộc tầng ứng dụng trong mô hình TCP/IP?',
                'explanation' => 'HTTP và FTP là các giao thức thuộc tầng ứng dụng. TCP thuộc tầng giao vận (Transport), IP thuộc tầng mạng (Internet).',
                'options' => [
                    ['key' => 'A', 'content' => 'HTTP', 'is_correct' => true],
                    ['key' => 'B', 'content' => 'FTP', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'TCP', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'IP', 'is_correct' => false],
                ]
            ],
            // Essay (3 câu)
            [
                'type' => 'essay',
                'content' => 'Hãy mô tả ngắn gọn vai trò của mạng Internet trong cuộc sống hàng ngày.',
                'explanation' => 'Internet giúp kết nối mọi người, tìm kiếm thông tin, học tập, làm việc giải trí trực tuyến nhanh chóng.',
            ],
            [
                'type' => 'essay',
                'content' => 'Tại sao lập trình viên cần học cách sử dụng hệ thống quản lý phiên bản Git?',
                'explanation' => 'Git giúp theo dõi lịch sử thay đổi mã nguồn, làm việc nhóm hiệu quả, quản lý các nhánh phát triển và khôi phục mã nguồn dễ dàng.',
            ],
            [
                'type' => 'essay',
                'content' => 'Hãy nêu sự khác nhau cơ bản giữa Client-side và Server-side trong phát triển web.',
                'explanation' => 'Client-side chạy trên trình duyệt người dùng (HTML, CSS, JS), còn Server-side chạy trên máy chủ (PHP, Node.js, Python) xử lý logic và cơ sở dữ liệu.',
            ],
        ];

        $easyQuestionIds = [];

        foreach ($easyQuestionsData as $qData) {
            $question = Question::create([
                'author_id'   => 1,
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'content'     => $qData['content'],
                'explanation' => $qData['explanation'],
                'difficulty'  => 'easy',
                'status'      => 'approved',
                'type'        => $qData['type'],
                'reviewed_by' => 1,
                'reviewed_at' => now(),
            ]);

            $easyQuestionIds[] = $question->id;

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

        // Tạo 1 đề thi chính thức (mode = official, type = hybrid) chứa 10 câu hỏi đó
        $officialExam = Exam::create([
            'public_id'         => 'exam-002',
            'author_id'         => 1,
            'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
            'title'             => 'Đề thi Tin học đại cương (Chính thức)',
            'slug'              => 'de-thi-tin-hoc-dai-cuong-chinh-thuc',
            'short_description' => 'Đề thi Tin học đại cương siêu dễ dành cho mọi người.',
            'description'       => 'Đề thi chính thức với thời gian làm bài ngắn và độ khó cực kỳ thấp.',
            'type'              => 'hybrid',
            'mode'              => 'official',
            'duration_minutes'  => 10,
            'pass_percent'      => 50,
            'visibility'        => 'public',
            'status'            => 'approved',
            'reviewed_by'       => 1,
            'reviewed_at'       => now(),
            'publish_at'        => now(),
        ]);

        $syncData = [];
        foreach ($easyQuestionIds as $index => $qId) {
            $syncData[$qId] = [
                'sort_order' => $index + 1,
                'score'      => 1,
            ];
        }
        $officialExam->questions()->sync($syncData);

        /*
        |--------------------------------------------------------------------------
        | 20 câu hỏi bổ sung (đa dạng danh mục và loại)
        | category_id: 1=PHP, 2=Laravel, 3=Database, 4=JavaScript, 5=Python, 6=Java, 7=Mạng máy tính
        |--------------------------------------------------------------------------
        */
        $bulkQuestionsData = [
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
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'type' => 'essay',
                'difficulty' => 'hard',
                'content' => 'Giải thích sự khác nhau giữa Promise và async/await trong JavaScript.',
                'explanation' => 'Promise là đối tượng đại diện cho giá trị bất đồng bộ. async/await là cú pháp giúp viết code bất đồng bộ dễ đọc hơn, thực chất vẫn dùng Promise bên dưới.',
            ],

            // ===== Python (category_id = 5) =====
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'Hàm nào dùng để in ra màn hình trong Python?',
                'explanation' => 'print() là hàm dùng để xuất dữ liệu ra màn hình trong Python.',
                'options' => [
                    ['key' => 'A', 'content' => 'echo()',    'is_correct' => false],
                    ['key' => 'B', 'content' => 'print()',   'is_correct' => true],
                    ['key' => 'C', 'content' => 'console()', 'is_correct' => false],
                    ['key' => 'D', 'content' => 'write()',   'is_correct' => false],
                ],
            ],
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'type' => 'single_choice',
                'difficulty' => 'medium',
                'content' => 'Kiểu dữ liệu nào trong Python là immutable (không thay đổi được)?',
                'explanation' => 'Tuple là kiểu dữ liệu immutable, không thể thay đổi sau khi tạo.',
                'options' => [
                    ['key' => 'A', 'content' => 'list',  'is_correct' => false],
                    ['key' => 'B', 'content' => 'dict',  'is_correct' => false],
                    ['key' => 'C', 'content' => 'tuple', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'set',   'is_correct' => false],
                ],
            ],
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'Những thư viện nào phổ biến trong lĩnh vực Machine Learning với Python?',
                'explanation' => 'TensorFlow, PyTorch và Scikit-learn là các thư viện ML phổ biến. Laravel là framework PHP.',
                'options' => [
                    ['key' => 'A', 'content' => 'TensorFlow',   'is_correct' => true],
                    ['key' => 'B', 'content' => 'PyTorch',      'is_correct' => true],
                    ['key' => 'C', 'content' => 'Scikit-learn', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'Laravel',      'is_correct' => false],
                ],
            ],
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'type' => 'essay',
                'difficulty' => 'hard',
                'content' => 'Giải thích cơ chế Garbage Collection trong Python và ảnh hưởng đến hiệu suất.',
                'explanation' => 'Python sử dụng reference counting kết hợp cyclic garbage collector để tự động thu hồi bộ nhớ.',
            ],

            // ===== Java (category_id = 6) =====
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'Phương thức nào là điểm bắt đầu thực thi của chương trình Java?',
                'explanation' => 'Phương thức main() với signature public static void main(String[] args) là điểm bắt đầu.',
                'options' => [
                    ['key' => 'A', 'content' => 'start()',  'is_correct' => false],
                    ['key' => 'B', 'content' => 'main()',   'is_correct' => true],
                    ['key' => 'C', 'content' => 'run()',    'is_correct' => false],
                    ['key' => 'D', 'content' => 'init()',   'is_correct' => false],
                ],
            ],
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'type' => 'single_choice',
                'difficulty' => 'medium',
                'content' => 'Từ khóa nào trong Java ngăn class được kế thừa?',
                'explanation' => 'Từ khóa final khi đặt trước class sẽ ngăn class đó bị kế thừa.',
                'options' => [
                    ['key' => 'A', 'content' => 'static',   'is_correct' => false],
                    ['key' => 'B', 'content' => 'abstract', 'is_correct' => false],
                    ['key' => 'C', 'content' => 'final',    'is_correct' => true],
                    ['key' => 'D', 'content' => 'private',  'is_correct' => false],
                ],
            ],
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'type' => 'multiple_choice',
                'difficulty' => 'hard',
                'content' => 'Những nguyên tắc nào thuộc SOLID trong lập trình hướng đối tượng?',
                'explanation' => 'SOLID gồm: Single Responsibility, Open/Closed, Liskov Substitution, Interface Segregation, Dependency Inversion.',
                'options' => [
                    ['key' => 'A', 'content' => 'Single Responsibility',    'is_correct' => true],
                    ['key' => 'B', 'content' => 'Open/Closed',             'is_correct' => true],
                    ['key' => 'C', 'content' => 'Don\'t Repeat Yourself',  'is_correct' => false],
                    ['key' => 'D', 'content' => 'Dependency Inversion',    'is_correct' => true],
                ],
            ],
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'type' => 'essay',
                'difficulty' => 'hard',
                'content' => 'So sánh ArrayList và LinkedList trong Java. Khi nào nên dùng loại nào?',
                'explanation' => 'ArrayList truy cập nhanh O(1), thêm/xóa chậm O(n). LinkedList ngược lại.',
            ],

            // ===== Mạng máy tính (category_id = 7) =====
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'Mô hình OSI có bao nhiêu tầng?',
                'explanation' => 'Mô hình OSI (Open Systems Interconnection) có 7 tầng.',
                'options' => [
                    ['key' => 'A', 'content' => '4 tầng', 'is_correct' => false],
                    ['key' => 'B', 'content' => '5 tầng', 'is_correct' => false],
                    ['key' => 'C', 'content' => '7 tầng', 'is_correct' => true],
                    ['key' => 'D', 'content' => '6 tầng', 'is_correct' => false],
                ],
            ],
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'type' => 'single_choice',
                'difficulty' => 'medium',
                'content' => 'Giao thức nào hoạt động ở tầng Transport trong mô hình TCP/IP?',
                'explanation' => 'TCP (Transmission Control Protocol) hoạt động ở tầng Transport.',
                'options' => [
                    ['key' => 'A', 'content' => 'HTTP', 'is_correct' => false],
                    ['key' => 'B', 'content' => 'TCP',  'is_correct' => true],
                    ['key' => 'C', 'content' => 'IP',   'is_correct' => false],
                    ['key' => 'D', 'content' => 'ARP',  'is_correct' => false],
                ],
            ],
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'Những thiết bị nào hoạt động ở tầng Network (tầng 3) trong mô hình OSI?',
                'explanation' => 'Router và Switch Layer 3 hoạt động ở tầng Network.',
                'options' => [
                    ['key' => 'A', 'content' => 'Router',          'is_correct' => true],
                    ['key' => 'B', 'content' => 'Switch Layer 3',  'is_correct' => true],
                    ['key' => 'C', 'content' => 'Hub',             'is_correct' => false],
                    ['key' => 'D', 'content' => 'Repeater',        'is_correct' => false],
                ],
            ],
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'type' => 'essay',
                'difficulty' => 'hard',
                'content' => 'Phân tích sự khác nhau giữa giao thức TCP và UDP. Cho ví dụ ứng dụng thực tế.',
                'explanation' => 'TCP đảm bảo truyền tin cậy (web, email). UDP nhanh hơn nhưng không đảm bảo (video call, game online).',
            ],

            // ===== Database bổ sung (category_id = 3) =====
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'type' => 'single_choice',
                'difficulty' => 'easy',
                'content' => 'Câu lệnh SQL nào dùng để lấy dữ liệu từ bảng?',
                'explanation' => 'SELECT là câu lệnh dùng để truy vấn và lấy dữ liệu từ bảng.',
                'options' => [
                    ['key' => 'A', 'content' => 'GET',    'is_correct' => false],
                    ['key' => 'B', 'content' => 'SELECT', 'is_correct' => true],
                    ['key' => 'C', 'content' => 'FETCH',  'is_correct' => false],
                    ['key' => 'D', 'content' => 'FIND',   'is_correct' => false],
                ],
            ],

            // ===== Laravel bổ sung (category_id = 2) =====
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'type' => 'single_choice',
                'difficulty' => 'medium',
                'content' => 'Lệnh artisan nào dùng để tạo Controller mới trong Laravel?',
                'explanation' => 'php artisan make:controller là lệnh tạo controller mới.',
                'options' => [
                    ['key' => 'A', 'content' => 'php artisan make:controller',   'is_correct' => true],
                    ['key' => 'B', 'content' => 'php artisan create:controller', 'is_correct' => false],
                    ['key' => 'C', 'content' => 'php artisan new:controller',    'is_correct' => false],
                    ['key' => 'D', 'content' => 'php artisan add:controller',    'is_correct' => false],
                ],
            ],

            // ===== PHP bổ sung (category_id = 1) =====
            [
                'category_id' => \DB::table('categories')->inRandomOrder()->value('id') ?? 1,
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'content' => 'Những kiểu dữ liệu nào là kiểu scalar trong PHP?',
                'explanation' => 'PHP có 4 kiểu scalar: int, float, string, bool.',
                'options' => [
                    ['key' => 'A', 'content' => 'int',    'is_correct' => true],
                    ['key' => 'B', 'content' => 'float',  'is_correct' => true],
                    ['key' => 'C', 'content' => 'string', 'is_correct' => true],
                    ['key' => 'D', 'content' => 'array',  'is_correct' => false],
                ],
            ],
        ];

        $bulkQuestionIds = [];

        foreach ($bulkQuestionsData as $qData) {
            $question = Question::create([
                'author_id'   => 1,
                'category_id' => $qData['category_id'],
                'content'     => $qData['content'],
                'explanation' => $qData['explanation'],
                'difficulty'  => $qData['difficulty'],
                'status'      => 'approved',
                'type'        => $qData['type'],
                'reviewed_by' => 1,
                'reviewed_at' => now(),
            ]);

            $bulkQuestionIds[] = [
                'id'          => $question->id,
                'category_id' => $qData['category_id'],
            ];

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

            // Gắn câu hỏi cùng danh mục vào bài kiểm tra
            $matchingQuestions = collect($bulkQuestionIds)
                ->where('category_id', $examData['category_id'])
                ->values();

            $examSyncData = [];
            foreach ($matchingQuestions as $sortIndex => $q) {
                $examSyncData[$q['id']] = [
                    'sort_order' => $sortIndex + 1,
                    'score'      => 1,
                ];
            }

            $bulkExam->questions()->sync($examSyncData);
        }
    }
}
