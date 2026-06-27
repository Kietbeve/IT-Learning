<?php

namespace Modules\Exam\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class QuestionTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new QuestionTemplateSheet(),
            // new QuestionGuideSheet(),
            new CategorySheet(),
        ];
    }
}