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
            'category_id'     => 1,
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
            'category_id'     => 1,
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
            'category_id'     => 1,
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
        'category_id'       => 1,
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
                'category_id' => 1,
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
            'category_id'       => 1,
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
    }
}
