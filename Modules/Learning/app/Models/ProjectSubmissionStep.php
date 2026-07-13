<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model ProjectSubmissionStep
 * 
 * Đại diện cho một bước trong quy trình nộp project.
 * Mỗi project có nhiều steps (Phân tích, Database, UI, Code, Video).
 * 
 * @property int $id
 * @property int $project_id
 * @property string $step_name
 * @property int $step_order
 * @property string $submission_type (file|link|both)
 * @property string|null $instructions
 * @property string|null $requirements
 * @property array|null $allowed_file_types
 * @property int $max_file_size_mb
 * @property string|null $link_placeholder
 * @property bool $is_required
 * @property bool $is_active
 * @property int $max_resubmissions
 */
class ProjectSubmissionStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'step_name',
        'step_order',
        'submission_type',
        'instructions',
        'resource_file_path',
        'resource_file_name',
        'requirements',
        'allowed_file_types',
        'max_file_size_mb',
        'link_placeholder',
        'is_required',
        'is_active',
        'max_resubmissions',
    ];

    protected $casts = [
        'allowed_file_types' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'max_file_size_mb' => 'integer',
        'max_resubmissions' => 'integer',
        'step_order' => 'integer',
    ];

    /**
     * RELATIONSHIPS
     */

    /**
     * Bước này thuộc về project nào
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Các lần nộp bài cho bước này
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(ProjectStepSubmission::class, 'step_id');
    }

    /**
     * SCOPES
     */

    /**
     * Chỉ lấy các bước active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Chỉ lấy các bước required
     */
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    /**
     * Sắp xếp theo thứ tự bước
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('step_order');
    }

    /**
     * ACCESSORS & HELPERS
     */

    /**
     * Kiểm tra xem bước này có yêu cầu upload file không
     */
    public function requiresFile(): bool
    {
        return in_array($this->submission_type, ['file', 'both']);
    }

    /**
     * Kiểm tra xem bước này có yêu cầu nhập link không
     */
    public function requiresLink(): bool
    {
        return in_array($this->submission_type, ['link', 'both']);
    }

    /**
     * Lấy danh sách file types được phép dưới dạng string
     * Ví dụ: "doc, docx, pdf, txt"
     */
    public function getAllowedFileTypesString(): string
    {
        if (empty($this->allowed_file_types)) {
            return 'Tất cả loại file';
        }
        
        return implode(', ', $this->allowed_file_types);
    }

    /**
     * Lấy kích thước file tối đa dưới dạng readable
     * Ví dụ: "50 MB"
     */
    public function getMaxFileSizeFormatted(): string
    {
        return $this->max_file_size_mb . ' MB';
    }

    /**
     * Get the full URL for the resource file
     */
    public function getResourceFileUrlAttribute(): ?string
    {
        if ($this->resource_file_path) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->resource_file_path);
        }
        
        return null;
    }

    /**
     * Validate file extension có hợp lệ không
     */
    public function isFileTypeAllowed(string $extension): bool
    {
        if (empty($this->allowed_file_types)) {
            return true; // Cho phép tất cả nếu không có restriction
        }
        
        return in_array(strtolower($extension), array_map('strtolower', $this->allowed_file_types));
    }

    /**
     * BUSINESS LOGIC
     */

    /**
     * Kiểm tra user đã nộp bước này chưa
     */
    public function hasUserSubmitted(int $userId, int $projectSubmissionId): bool
    {
        return $this->submissions()
            ->where('user_id', $userId)
            ->where('project_submission_id', $projectSubmissionId)
            ->where('is_current', true)
            ->exists();
    }

    /**
     * Lấy submission hiện tại của user cho bước này
     */
    public function getCurrentSubmission(int $userId, int $projectSubmissionId): ?ProjectStepSubmission
    {
        return $this->submissions()
            ->where('user_id', $userId)
            ->where('project_submission_id', $projectSubmissionId)
            ->where('is_current', true)
            ->first();
    }

    /**
     * Kiểm tra bước này đã được approved chưa (cho user cụ thể)
     */
    public function isApprovedForUser(int $userId, int $projectSubmissionId): bool
    {
        $submission = $this->getCurrentSubmission($userId, $projectSubmissionId);
        
        return $submission && $submission->status === 'approved';
    }

    /**
     * Đếm số lần user đã nộp bước này
     */
    public function getSubmissionCount(int $userId, int $projectSubmissionId): int
    {
        return $this->submissions()
            ->where('user_id', $userId)
            ->where('project_submission_id', $projectSubmissionId)
            ->count();
    }

    /**
     * Kiểm tra user còn được phép nộp lại không
     */
    public function canUserResubmit(int $userId, int $projectSubmissionId): bool
    {
        $submissionCount = $this->getSubmissionCount($userId, $projectSubmissionId);
        
        return $submissionCount < $this->max_resubmissions;
    }
}
