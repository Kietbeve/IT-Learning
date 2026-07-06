<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoadmapSection extends Model
{
    protected $table = 'roadmap_sections';

    protected $fillable = [
        'roadmap_id',
        'title',
        'description',
        'objectives',
        'prerequisite_section_id',
        'is_locked',
        'estimated_hours',
        'min_completion_percent',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'objectives' => 'array',
            'is_locked' => 'boolean',
        ];
    }

    /**
     * Roadmap mà section này thuộc về
     */
    public function roadmap(): BelongsTo
    {
        return $this->belongsTo(Roadmap::class);
    }

    /**
     * Các bài học trong section này
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(RoadmapLesson::class, 'section_id');
    }

    /**
     * Section tiên quyết (phải hoàn thành trước)
     */
    public function prerequisiteSection(): BelongsTo
    {
        return $this->belongsTo(RoadmapSection::class, 'prerequisite_section_id');
    }

    /**
     * Các section phụ thuộc vào section này
     */
    public function dependentSections(): HasMany
    {
        return $this->hasMany(RoadmapSection::class, 'prerequisite_section_id');
    }

    /**
     * Tiến độ học tập của users trong section này
     */
    public function progress(): HasMany
    {
        return $this->hasMany(SectionProgress::class, 'section_id');
    }

    /**
     * Kiểm tra xem section có bị khóa cho user không
     */
    public function isLockedForUser($userId): bool
    {
        if (!$this->is_locked || !$this->prerequisite_section_id) {
            return false;
        }

        $prerequisiteProgress = SectionProgress::where('user_id', $userId)
            ->where('section_id', $this->prerequisite_section_id)
            ->first();

        return !$prerequisiteProgress || $prerequisiteProgress->status !== 'completed';
    }

    /**
     * Lấy tiến độ của user trong section này
     */
    public function getProgressForUser($userId)
    {
        return $this->progress()->where('user_id', $userId)->first();
    }
}