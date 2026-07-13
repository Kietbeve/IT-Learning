<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectRubricCriteria extends Model
{
    protected $table = 'project_rubric_criteria';

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'max_points',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'max_points' => 'decimal:2',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Get the project that owns this rubric criteria
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get all scores for this criteria across submissions
     */
    public function scores(): HasMany
    {
        return $this->hasMany(SubmissionRubricScore::class, 'rubric_criteria_id');
    }

    /**
     * Scope to order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
}
