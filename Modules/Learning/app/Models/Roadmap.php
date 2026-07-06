<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Category;
use Modules\Auth\Models\User;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Roadmap extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'public_id',
        'author_id',
        'category_id',
        'title',
        'slug',
        'short_description',
        'description',
        'objective',
        'thumbnail',
        'visibility',
        'status',
        'rejected_reason',
        'reviewed_by',
        'reviewed_at',
        'published_at',
        'category',
        'level',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Relationships với các models khác trong module
    public function sections(): HasMany
    {
        return $this->hasMany(RoadmapSection::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(RoadmapLesson::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(RoadmapEnrollment::class);
    }

    public function lessonProgress(): HasMany
    {
        return $this->hasMany(RoadmapLessonProgress::class);
    }

    public function sectionProgress(): HasMany
    {
        return $this->hasMany(SectionProgress::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(RoadmapCertificate::class);
    }

    /**
     * Check if a user is enrolled in this roadmap
     */
    public function isEnrolledBy($userId): bool
    {
        return $this->enrollments()->where('user_id', $userId)->exists();
    }

    /**
     * Get enrollment for a specific user
     */
    public function getEnrollmentFor($userId)
    {
        return $this->enrollments()->where('user_id', $userId)->first();
    }
    public function users(): BelongsToMany
{
    return $this->belongsToMany(
        User::class,
        'roadmap_enrollments',
        'roadmap_id',
        'user_id'
    )
    ->withPivot([
        'status',
        'progress_percent',
        'started_at'
    ]);
}
}