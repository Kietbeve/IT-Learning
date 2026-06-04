<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoadmapSection extends Model
{
    protected $fillable = [
        'roadmap_id',
        'title',
        'sort_order',
    ];

    public function roadmap(): BelongsTo
    {
        return $this->belongsTo(Roadmap::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(
            RoadmapLesson::class,
            'section_id'
        );
    }

    public function projects(): HasMany
    {
        return $this->hasMany(
            Project::class,
            'section_id'
        );
    }
}
