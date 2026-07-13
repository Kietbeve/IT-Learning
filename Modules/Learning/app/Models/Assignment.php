<?php

namespace Modules\Learning\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\User;

class Assignment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'public_id',
        'lesson_id',
        'created_by',
        'title',
        'description',
        'instructions',
        'requirements',
        'submission_type',
        'allowed_file_types',
        'max_file_size_mb',
        'deadline_days',
        'send_reminder',
        'reminder_hours',
        'max_score',
        'has_rubric',
        'grading_type',
        'status',
    ];

    protected $casts = [
        'requirements' => 'array',
        'send_reminder' => 'boolean',
        'has_rubric' => 'boolean',
        'deadline_days' => 'integer',
        'reminder_hours' => 'integer',
        'max_score' => 'integer',
        'max_file_size_mb' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($assignment) {
            if (empty($assignment->public_id)) {
                $assignment->public_id = Str::uuid()->toString();
            }
        });
    }

    /**
     * Belongs to lesson
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(RoadmapLesson::class, 'lesson_id');
    }

    /**
     * Created by user (admin/instructor)
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Has many submissions
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    /**
     * Has many rubrics
     */
    public function rubrics(): HasMany
    {
        return $this->hasMany(AssignmentRubric::class)->orderBy('order');
    }

    /**
     * Has assigned graders
     */
    public function graders(): HasMany
    {
        return $this->hasMany(AssignmentGrader::class);
    }

    /**
     * Has many attachments (từ giảng viên)
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(AssignmentAttachment::class);
    }

    /**
     * Get active graders
     */
    public function activeGraders(): HasMany
    {
        return $this->graders()->where('is_active', true);
    }

    /**
     * Get pending submissions (waiting for grading)
     */
    public function pendingSubmissions(): HasMany
    {
        return $this->submissions()->whereIn('status', ['submitted', 'in_review']);
    }

    /**
     * Get graded submissions
     */
    public function gradedSubmissions(): HasMany
    {
        return $this->submissions()->where('status', 'graded');
    }

    /**
     * Get late submissions
     */
    public function lateSubmissions(): HasMany
    {
        return $this->submissions()->where('is_late', true);
    }

    /**
     * Check if assignment is published
     */
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Get total score from rubrics
     */
    public function getTotalScoreFromRubrics(): int
    {
        return $this->rubrics()->sum('max_points');
    }

    /**
     * Scope: Published assignments
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope: For specific lesson
     */
    public function scopeForLesson($query, $lessonId)
    {
        return $query->where('lesson_id', $lessonId);
    }

    /**
     * Scope: For specific roadmap (via lesson)
     */
    public function scopeForRoadmap($query, $roadmapId)
    {
        return $query->whereHas('lesson', function ($q) use ($roadmapId) {
            $q->where('roadmap_id', $roadmapId);
        });
    }

    /**
     * Scope: For specific section (via lesson)
     */
    public function scopeForSection($query, $sectionId)
    {
        return $query->whereHas('lesson', function ($q) use ($sectionId) {
            $q->where('section_id', $sectionId);
        });
    }

    /**
     * Validate that lesson belongs to the same roadmap/section context
     */
    public function validateLessonContext(int $lessonId): bool
    {
        $lesson = RoadmapLesson::find($lessonId);
        
        if (!$lesson) {
            return false;
        }

        // If this assignment already has a lesson, check they're in same context
        if ($this->lesson_id && $this->lesson) {
            // Must be same roadmap
            if ($this->lesson->roadmap_id !== $lesson->roadmap_id) {
                return false;
            }
            
            // Optionally check same section (can be relaxed if needed)
            // if ($this->lesson->section_id !== $lesson->section_id) {
            //     return false;
            // }
        }

        return true;
    }

    /**
     * Check if assignment is valid for a specific lesson
     */
    public static function isValidForLesson(int $lessonId): bool
    {
        $lesson = RoadmapLesson::find($lessonId);
        return $lesson && $lesson->roadmap_id && $lesson->is_published;
    }
}
