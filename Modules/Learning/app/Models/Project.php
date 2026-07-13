<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
 protected $fillable = [
        'roadmap_id',
        'section_id',
        'title',
        'description',
        'starter_code_url',
        'deadline_at',
        'max_resubmissions',
        'max_score',
        'grading_criteria',
        'passing_score',
        'required_completion_percentage',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'deadline_at' => 'datetime',
            'grading_criteria' => 'array',
            'max_score' => 'decimal:2',
            'passing_score' => 'decimal:2',
            'required_completion_percentage' => 'decimal:2',
        ];
    }

    public function roadmap(): BelongsTo
    {
        return $this->belongsTo(Roadmap::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(
            RoadmapSection::class,
            'section_id'
        );
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(
            ProjectSubmission::class
        );
    }

    public function lesson(): HasOne
    {
        return $this->hasOne(
            RoadmapLesson::class,
            'project_id'
        );
    }

    public function rubricCriteria(): HasMany
    {
        return $this->hasMany(
            ProjectRubricCriteria::class
        )->orderBy('sort_order');
    }

    public function reviewers(): HasMany
    {
        return $this->hasMany(ProjectReviewer::class);
    }
}
