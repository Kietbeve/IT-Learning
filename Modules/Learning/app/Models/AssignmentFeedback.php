<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Auth\Models\User;

class AssignmentFeedback extends Model
{
    protected $table = 'assignment_feedback';

    protected $fillable = [
        'submission_id',
        'grader_id',
        'comment',
        'score',
        'feedback_type',
        'reference_file',
        'reference_line',
        'is_resolved',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'is_resolved' => 'boolean',
            'resolved_at' => 'datetime',
        ];
    }

    /**
     * Submission nhận feedback này
     */
    public function submission(): BelongsTo
    {
        return $this->belongsTo(AssignmentSubmission::class, 'submission_id');
    }

    /**
     * Giảng viên chấm bài
     */
    public function grader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'grader_id');
    }

    /**
     * Đánh dấu feedback đã được xử lý
     */
    public function markAsResolved(): void
    {
        $this->is_resolved = true;
        $this->resolved_at = now();
        $this->save();
    }

    /**
     * Scope để lấy feedback chung (không thuộc rubric cụ thể)
     */
    public function scopeGeneral($query)
    {
        return $query->where('feedback_type', 'general');
    }

    /**
     * Scope để lấy feedback theo tiêu chí
     */
    public function scopeCriterion($query)
    {
        return $query->where('feedback_type', 'criterion');
    }

    /**
     * Scope để lấy inline feedback
     */
    public function scopeInline($query)
    {
        return $query->where('feedback_type', 'inline');
    }
}
