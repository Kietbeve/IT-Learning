<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Modules\Auth\Models\User;
class ProjectSubmission extends Model
{
 protected $fillable = [
        'project_id',
        'user_id',
        'enrollment_id',
        'github_url',
        'live_demo_url',
        'attachment_path',
        'video_files',
        'note',
        'submission_no',
        'submission_type',
        'status',
        'reviewed_by',
        'reviewed_at',
        'feedback',
        'score',
        'grading_notes',
        'submitted_at',
        'is_late',
        'days_late',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
            'submitted_at' => 'datetime',
            'grading_notes' => 'array',
            'video_files' => 'array',
            'score' => 'decimal:2',
        ];
    }

    /**
     * Check if submission passed based on score
     */
    public function isPassed(): bool
    {
        if ($this->status === 'passed') {
            return true;
        }

        if ($this->score && $this->project) {
            return $this->score >= $this->project->passing_score;
        }

        return false;
    }

    /**
     * Get score percentage
     */
    public function getScorePercentage(): ?float
    {
        if (!$this->score || !$this->project || !$this->project->max_score) {
            return null;
        }

        return ($this->score / $this->project->max_score) * 100;
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(
            RoadmapEnrollment::class,
            'enrollment_id'
        );
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'reviewed_by'
        );
    }

    public function rubricScores(): HasMany
    {
        return $this->hasMany(
            SubmissionRubricScore::class,
            'submission_id'
        );
    }
}
