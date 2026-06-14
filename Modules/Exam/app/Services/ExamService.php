<?php

namespace Modules\Exam\Services;

use Illuminate\Support\Facades\DB;
use Modules\Exam\Models\Exam;
use Modules\Exam\Models\Question;
use Modules\Exam\Models\QuestionOption;


class ExamService
{
    public function __construct(
        //Inject Model vào constructor
        protected Question $questionModel,
        protected QuestionOption $question_optionModel
    ){}
    public function createQuestion(array $data, int $authorId): Question
    {
        return DB::transaction(function () use ($data, $authorId) {

            $question = $this->questionModel->create([
                'author_id' => $authorId,
                'category_id' => $data['category_id'],
                'content' => $data['content'],
                'explanation' => $data['explanation'] ?? null,
                'difficulty' => $data['difficulty'],
                'status' => 'pending',
                'type' => $data['type'],
            ]);

            if (
                in_array(
                    $data['type'],
                    ['single_choice', 'multiple_choice'],
                    true
                )
            ) {
                collect($data['options'] ?? [])
                    ->filter(fn ($option) => filled($option['content'] ?? null))
                    ->values()
                    ->each(function (array $option, int $index) use ($question) {

                        $this->question_optionModel->create([
                            'question_id' => $question->id,
                            'option_key' => chr(65 + $index),
                            'content' => $option['content'],
                            'is_correct' => (bool) ($option['is_correct'] ?? false),
                            'sort_order' => $index + 1,
                        ]);
                    });
            }

            return $question;
        });
    }

    public function updateQuestion(Question $question, array $data): Question
    {
        return DB::transaction(function () use ($question, $data) {

            $question->update([
                'category_id' => $data['category_id'],
                'content' => $data['content'],
                'explanation' => $data['explanation'] ?? null,
                'difficulty' => $data['difficulty'],
                'type' => $data['type'],
            ]);

            if (
                in_array(
                    $data['type'],
                    ['single_choice', 'multiple_choice'],
                    true
                )
            ) {
                // Delete existing options
                $question->options()->delete();

                // Create new options
                collect($data['options'] ?? [])
                    ->filter(fn ($option) => filled($option['content'] ?? null))
                    ->values()
                    ->each(function (array $option, int $index) use ($question) {

                        $this->question_optionModel->create([
                            'question_id' => $question->id,
                            'option_key' => chr(65 + $index),
                            'content' => $option['content'],
                            'is_correct' => (bool) ($option['is_correct'] ?? false),
                            'sort_order' => $index + 1,
                        ]);
                    });
            } else {
                // If changed to essay type, delete all options
                $question->options()->delete();
            }

            return $question->fresh(['options']);
        });
    }

    /*
    Hàm lấy danh sách bài kiểm tra
    */
    public function getExamList()
    {
    return Exam::query()
        ->select([
            'id',
            'title',
            'short_description',
            'duration_minutes',
            'author_id',
            'category_id',
        ])
        ->with([
            'author:id,name',
            'category:id,name'
        ])
        ->withCount('questions')
        ->get();
    }
    /*
    Ket qua tra ve co dang: 
    [
      Modules\Exam\Models\Exam {#8983
        id: 1,
        title: "Đề thi Laravel cơ bản",
        short_description: "Đề thi demo",
        duration_minutes: 30,
        author_id: 1,
        category_id: 1,
        questions_count: 3,
        author: Modules\Auth\Models\User {#9016
          id: 1,
          name: "admin",
        },
        category: App\Models\Category {#9019
          id: 1,
          name: "PHP",
        },
      },
    ],...
    */
}
