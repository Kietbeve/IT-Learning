<?php

namespace Modules\Exam\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Modules\Exam\Services\ExamService;

class QuestionsImport implements WithMultipleSheets
{
    protected ?QuestionSheetImport $questionSheetImport = null;

    public function __construct(
        protected ExamService $examService,
        protected int $authorId,
    ) {}

    public function sheets(): array
    {
        $this->questionSheetImport = new QuestionSheetImport(
            $this->examService,
            $this->authorId
        );

        return [
            0 => $this->questionSheetImport, // Only import the first sheet (Questions)
        ];
    }

    public function getImportedCount(): int
    {
        return $this->questionSheetImport?->getImportedCount() ?? 0;
    }
}
