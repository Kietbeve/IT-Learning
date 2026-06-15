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
    }
}
