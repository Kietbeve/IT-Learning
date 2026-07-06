<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class AssignmentSubmission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'public_id',
        'assignment_id',
        'user_id',
        'github_url',
        'live_demo_url',
        'file_path',
        'file_name',
        'notes',
        'attempt_number',
        'submitted_at',
        'deadline',
        'is_late',
        'days_late',
        'status',
        'graded_by',
        'score',
        'feedback',
        'graded_at',
        'reminder_sent',
        'reminder_sent_at',
        'deadline_warning_sent',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'deadline' => 'datetime',
        'graded_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
        'is_late' => 'boolean',
        'reminder_sent' => 'boolean',
        'deadline_warning_sent' => 'boolean',
        'days_late' => 'integer',
        'attempt_number' => 'integer',
        'score' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($submission) {
            if (empty($submission->public_id)) {
                $submission->public_id = Str::uuid()->toString();
            }
            
            // Calculate deadline from assignment
            if (empty($submission->deadline) && $submission->assignment) {
                $submission->deadline = now()->addDays($submission->assignment->deadline_days);
            }
        });
    }

    /**
     * Belongs to assignment
     */
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * Belongs to student (user)
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Graded by user (CTV/Admin)
     */
    public function grader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    /**
     * Has rubric item scores
     */
    public function rubricItems(): HasMany
    {
        return $this->hasMany(AssignmentRubricItem::class, 'submission_id');
    }

    /**
     * Has many attachments (files từ học viên)
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(AssignmentAttachment::class, 'submission_id');
    }

    /**
     * Has many feedback comments
     */
    public function feedback(): HasMany
    {
        return $this->hasMany(AssignmentFeedback::class, 'submission_id');
    }

    /**
     * Mark as submitted
     */
    public function markAsSubmitted(): void
    {
        $this->submitted_at = now();
        $this->status = 'submitted';
        
        // Check if late
        if ($this->deadline && now()->gt($this->deadline)) {
            $this->is_late = true;
            $this->days_late = now()->diffInDays($this->deadline);
        }
        
        $this->save();
    }

    /**
     * Mark as graded
     */
    public function markAsGraded(int $score, string $feedback, int $graderId): void
    {
        $this->status = 'graded';
        $this->score = $score;
        $this->feedback = $feedback;
        $this->graded_by = $graderId;
        $this->graded_at = now();
        $this->save();
    }

    /**
     * Check if needs reminder
     */
    public function needsReminder(): bool
    {
        if ($this->reminder_sent || !$this->deadline) {
            return false;
        }

        $assignment = $this->assignment;
        if (!$assignment || !$assignment->send_reminder) {
            return false;
        }

        $reminderTime = $this->deadline->subHours($assignment->reminder_hours);
        return now()->gte($reminderTime) && $this->status !== 'submitted';
    }

    /**
     * Check if past deadline
     */
    public function isPastDeadline(): bool
    {
        return $this->deadline && now()->gt($this->deadline);
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColor(): string
    {
        return match($this->status) {
            'draft' => 'gray',
            'submitted' => 'blue',
            'in_review' => 'yellow',
            'graded' => 'green',
            'needs_revision' => 'red',
            default => 'gray'
        };
    }

    /**
     * Scope: Pending grading
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', ['submitted', 'in_review']);
    }

    /**
     * Scope: Late submissions
     */
    public function scopeLate($query)
    {
        return $query->where('is_late', true);
    }

    /**
     * Scope: For specific student
     */
    public function scopeForStudent($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: For specific assignment
     */
    public function scopeForAssignment($query, $assignmentId)
    {
        return $query->where('assignment_id', $assignmentId);
    }
}
