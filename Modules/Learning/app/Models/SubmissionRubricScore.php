<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmissionRubricScore extends Model
{
    protected $table = 'submission_rubric_scores';

    protected $fillable = [
        'submission_id',
        'rubric_criteria_id',
        'score',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
        ];
    }

    /**
     * Get the submission that owns this score
     */
    public function submission(): BelongsTo
    {
        return $this->belongsTo(ProjectSubmission::class, 'submission_id');
    }

    /**
     * Get the rubric criteria for this score
     */
    public function criteria(): BelongsTo
    {
        return $this->belongsTo(ProjectRubricCriteria::class, 'rubric_criteria_id');
    }
}
