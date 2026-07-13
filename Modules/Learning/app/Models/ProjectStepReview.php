<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model ProjectStepReview
 * 
 * Lưu lịch sử review chi tiết cho từng lần nộp.
 * Giúp học viên xem lại các feedback từ các lần review trước.
 * 
 * @property int $id
 * @property int $step_submission_id
 * @property int $reviewer_id
 * @property string $decision (approved|rejected)
 * @property string|null $feedback
 * @property array|null $review_notes
 * @property string $reviewed_at
 * @property int|null $review_duration_seconds
 */
class ProjectStepReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'step_submission_id',
        'reviewer_id',
        'decision',
        'feedback',
        'review_notes',
        'reviewed_at',
        'review_duration_seconds',
    ];

    protected $casts = [
        'review_notes' => 'array',
        'reviewed_at' => 'datetime',
        'review_duration_seconds' => 'integer',
    ];

    /**
     * RELATIONSHIPS
     */

    /**
     * Review này cho submission nào
     */
    public function stepSubmission(): BelongsTo
    {
        return $this->belongsTo(ProjectStepSubmission::class, 'step_submission_id');
    }

    /**
     * Người review
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(\Modules\Auth\Models\User::class, 'reviewer_id');
    }

    /**
     * SCOPES
     */

    /**
     * Lấy reviews đã approve
     */
    public function scopeApproved($query)
    {
        return $query->where('decision', 'approved');
    }

    /**
     * Lấy reviews đã reject
     */
    public function scopeRejected($query)
    {
        return $query->where('decision', 'rejected');
    }

    /**
     * Lấy reviews của một reviewer cụ thể
     */
    public function scopeByReviewer($query, int $reviewerId)
    {
        return $query->where('reviewer_id', $reviewerId);
    }

    /**
     * Sắp xếp theo thời gian review (mới nhất trước)
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('reviewed_at', 'desc');
    }

    /**
     * ACCESSORS & HELPERS
     */

    /**
     * Kiểm tra review này có phải approved không
     */
    public function isApproved(): bool
    {
        return $this->decision === 'approved';
    }

    /**
     * Kiểm tra review này có phải rejected không
     */
    public function isRejected(): bool
    {
        return $this->decision === 'rejected';
    }

    /**
     * Lấy decision badge color
     */
    public function getDecisionColor(): string
    {
        return match($this->decision) {
            'approved' => 'green',
            'rejected' => 'red',
            default => 'gray',
        };
    }

    /**
     * Lấy decision label tiếng Việt
     */
    public function getDecisionLabel(): string
    {
        return match($this->decision) {
            'approved' => 'Đã duyệt',
            'rejected' => 'Từ chối',
            default => 'Không xác định',
        };
    }

    /**
     * Lấy review duration formatted
     */
    public function getReviewDurationFormatted(): string
    {
        if (empty($this->review_duration_seconds)) {
            return 'N/A';
        }

        $minutes = floor($this->review_duration_seconds / 60);
        $seconds = $this->review_duration_seconds % 60;

        if ($minutes > 0) {
            return "{$minutes} phút {$seconds} giây";
        }

        return "{$seconds} giây";
    }

    /**
     * Lấy reviewed_at dưới dạng human readable
     */
    public function getReviewedAtForHumans(): string
    {
        return $this->reviewed_at->diffForHumans();
    }

    /**
     * BUSINESS LOGIC
     */

    /**
     * Tạo review mới cho submission
     */
    public static function createReview(
        int $stepSubmissionId,
        int $reviewerId,
        string $decision,
        ?string $feedback = null,
        ?array $reviewNotes = null,
        ?int $durationSeconds = null
    ): self {
        return self::create([
            'step_submission_id' => $stepSubmissionId,
            'reviewer_id' => $reviewerId,
            'decision' => $decision,
            'feedback' => $feedback,
            'review_notes' => $reviewNotes,
            'reviewed_at' => now(),
            'review_duration_seconds' => $durationSeconds,
        ]);
    }
}
