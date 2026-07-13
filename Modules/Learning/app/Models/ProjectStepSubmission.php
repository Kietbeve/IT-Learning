<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Model ProjectStepSubmission
 * 
 * Đại diện cho một lần nộp bài của học viên cho một bước cụ thể.
 * Học viên có thể nộp lại nhiều lần nếu bị reject.
 * 
 * @property int $id
 * @property string $public_id
 * @property int $project_submission_id
 * @property int $step_id
 * @property int $user_id
 * @property int $submission_number
 * @property string|null $file_path
 * @property string|null $file_name
 * @property int|null $file_size_kb
 * @property string|null $link_url
 * @property string|null $notes
 * @property string $status (draft|submitted|under_review|approved|rejected)
 * @property string|null $submitted_at
 * @property string|null $reviewed_at
 * @property int|null $reviewed_by
 * @property string|null $feedback
 * @property bool $is_current
 */
class ProjectStepSubmission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'project_submission_id',
        'step_id',
        'user_id',
        'submission_number',
        'file_path',
        'file_name',
        'file_size_kb',
        'link_url',
        'notes',
        'status',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
        'feedback',
        'is_current',
    ];

    protected $casts = [
        'submission_number' => 'integer',
        'file_size_kb' => 'integer',
        'is_current' => 'boolean',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Boot method - tự động generate public_id
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->public_id)) {
                $model->public_id = 'PSS' . strtoupper(Str::random(12));
            }
        });
    }

    /**
     * RELATIONSHIPS
     */

    /**
     * Submission này thuộc về project submission tổng nào
     */
    public function projectSubmission(): BelongsTo
    {
        return $this->belongsTo(ProjectSubmission::class);
    }

    /**
     * Bước nào đang được nộp
     */
    public function step(): BelongsTo
    {
        return $this->belongsTo(ProjectSubmissionStep::class, 'step_id');
    }

    /**
     * Học viên nộp bài
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\Modules\Auth\Models\User::class);
    }

    /**
     * Người review (Admin/CTV)
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(\Modules\Auth\Models\User::class, 'reviewed_by');
    }

    /**
     * Các lần review cho submission này
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(ProjectStepReview::class, 'step_submission_id');
    }

    /**
     * SCOPES
     */

    /**
     * Chỉ lấy submissions hiện tại (latest)
     */
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    /**
     * Lấy theo status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Chỉ lấy submissions đang chờ review
     */
    public function scopePendingReview($query)
    {
        return $query->whereIn('status', ['submitted', 'under_review']);
    }

    /**
     * Đã approved
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Đã rejected
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Lấy submissions của một user cụ thể
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * ACCESSORS & HELPERS
     */

    /**
     * Kiểm tra submission đã được submit chưa
     */
    public function isSubmitted(): bool
    {
        return !in_array($this->status, ['draft']);
    }

    /**
     * Kiểm tra đang chờ review
     */
    public function isPendingReview(): bool
    {
        return in_array($this->status, ['submitted', 'under_review']);
    }

    /**
     * Kiểm tra đã được approve
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Kiểm tra đã bị reject
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Kiểm tra có thể edit được không (chỉ draft và rejected)
     */
    public function canEdit(): bool
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    /**
     * Kiểm tra có thể submit được không
     */
    public function canSubmit(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Kiểm tra có file không
     */
    public function hasFile(): bool
    {
        return !empty($this->file_path);
    }

    /**
     * Kiểm tra có link không
     */
    public function hasLink(): bool
    {
        return !empty($this->link_url);
    }

    /**
     * Lấy file size dưới dạng readable
     */
    public function getFileSizeFormatted(): string
    {
        if (empty($this->file_size_kb)) {
            return 'N/A';
        }

        if ($this->file_size_kb < 1024) {
            return $this->file_size_kb . ' KB';
        }

        return round($this->file_size_kb / 1024, 2) . ' MB';
    }

    /**
     * Lấy đường dẫn file đầy đủ
     */
    public function getFileUrl(): ?string
    {
        if (empty($this->file_path)) {
            return null;
        }

        return asset('storage/' . $this->file_path);
    }

    /**
     * Lấy status badge color
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            'draft' => 'gray',
            'submitted' => 'blue',
            'under_review' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            default => 'gray',
        };
    }

    /**
     * Lấy status label tiếng Việt
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'draft' => 'Nháp',
            'submitted' => 'Đã nộp',
            'under_review' => 'Đang chấm',
            'approved' => 'Đã duyệt',
            'rejected' => 'Bị từ chối',
            default => 'Không xác định',
        };
    }

    /**
     * BUSINESS LOGIC METHODS
     */

    /**
     * Submit bài (chuyển từ draft -> submitted)
     */
    public function submit(): bool
    {
        if (!$this->canSubmit()) {
            return false;
        }

        $this->status = 'submitted';
        $this->submitted_at = now();
        
        return $this->save();
    }

    /**
     * Approve submission
     */
    public function approve(int $reviewerId, ?string $feedback = null): bool
    {
        if (!$this->isPendingReview()) {
            return false;
        }

        $this->status = 'approved';
        $this->reviewed_by = $reviewerId;
        $this->reviewed_at = now();
        $this->feedback = $feedback;

        // Tạo review record
        $this->reviews()->create([
            'reviewer_id' => $reviewerId,
            'decision' => 'approved',
            'feedback' => $feedback,
            'reviewed_at' => now(),
        ]);

        return $this->save();
    }

    /**
     * Reject submission với feedback
     */
    public function reject(int $reviewerId, string $feedback): bool
    {
        if (!$this->isPendingReview()) {
            return false;
        }

        $this->status = 'rejected';
        $this->reviewed_by = $reviewerId;
        $this->reviewed_at = now();
        $this->feedback = $feedback;

        // Tạo review record
        $this->reviews()->create([
            'reviewer_id' => $reviewerId,
            'decision' => 'rejected',
            'feedback' => $feedback,
            'reviewed_at' => now(),
        ]);

        return $this->save();
    }

    /**
     * Đánh dấu submission này không còn current (khi user resubmit)
     */
    public function markAsNotCurrent(): bool
    {
        $this->is_current = false;
        return $this->save();
    }
}
