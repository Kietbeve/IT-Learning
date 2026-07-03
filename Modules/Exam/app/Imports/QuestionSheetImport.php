<?php

namespace Modules\Exam\Imports;

use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Modules\Exam\Services\ExamService;

class QuestionSheetImport implements ToCollection
{
    protected int $importedCount = 0;

    public function __construct(
        protected ExamService $examService,
        protected int $authorId,
    ) {}

    public function collection(Collection $rows): void
    {
        $headers = collect($rows->shift())
        ->map(fn ($header) => trim(strtolower((string) $header)))
        ->toArray();
        
        DB::transaction(function () use ($rows, $headers) {

            foreach ($rows as $index => $row) {

                $line = $index + 2;

                $data = array_combine(
                    $headers,
                    $row->toArray()
                );

                $category = Category::query()
                    ->where('name', trim($data['category']))
                    ->first();

                if (! $category) {
                    throw ValidationException::withMessages([
                        'import' => "Dòng {$line}: Danh mục không tồn tại.",
                    ]);
                }

                $options = [];

                if (
                    in_array(
                        $data['type'],
                        ['single_choice', 'multiple_choice']
                    )
                ) {

                    // Parse semicolon-separated options format
                    // Example: "+Bàn phím;+Chuột;Màn hình"
                    $optionsString = trim($data['options'] ?? '');
                    
                    if (empty($optionsString)) {
                        throw ValidationException::withMessages([
                            'import' => "Dòng {$line}: Options không hợp lệ.",
                        ]);
                    }

                    $optionParts = explode(';', $optionsString);
                    foreach ($optionParts as $optionPart) {
                        $optionPart = trim($optionPart);
                        if (!empty($optionPart)) {
                            $isCorrect = str_starts_with($optionPart, '+');
                            $content = $isCorrect ? substr($optionPart, 1) : $optionPart;
                            $options[] = [
                                'content' => trim($content),
                                'is_correct' => $isCorrect,
                            ];
                        }
                    }

                    if (empty($options)) {
                        throw ValidationException::withMessages([
                            'import' => "Dòng {$line}: Options không hợp lệ.",
                        ]);
                    }

                    $correctCount = collect($options)
                        ->where('is_correct', true)
                        ->count();

                    if (
                        $data['type'] === 'single_choice'
                        && $correctCount !== 1
                    ) {
                        throw ValidationException::withMessages([
                            'import' => "Dòng {$line}: Single choice phải có đúng 1 đáp án đúng.",
                        ]);
                    }

                    if (
                        $data['type'] === 'multiple_choice'
                        && $correctCount < 1
                    ) {
                        throw ValidationException::withMessages([
                            'import' => "Dòng {$line}: Multiple choice phải có ít nhất 1 đáp án đúng.",
                        ]);
                    }
                }

                if (
                    $data['type'] === 'essay'
                    && blank($data['answer_text'])
                ) {
                    throw ValidationException::withMessages([
                        'import' => "Dòng {$line}: Thiếu đáp án tự luận.",
                    ]);
                }

                $this->examService->createQuestion([
                    'category_id' => $category->id,
                    'content' => $data['content'],
                    'explanation' => $data['explanation'] ?? null,
                    'difficulty' => $data['difficulty'],
                    'type' => $data['type'],
                    'answer_text' => $data['answer_text'] ?? null,
                    'options' => $options,
                ], $this->authorId);

                $this->importedCount++;
            }
        });
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }
}
