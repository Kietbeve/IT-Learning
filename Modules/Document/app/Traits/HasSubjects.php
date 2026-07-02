<?php

namespace Modules\Document\Traits;

use Modules\Document\Models\Subject;

trait HasSubjects
{
    public function getSubjectsByCategory($categoryId)
    {
        if (empty($categoryId)) {
            return [];
        }

        return Subject::where('category_id', $categoryId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
}
