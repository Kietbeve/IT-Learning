<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model ProjectReviewer
 * 
 * Phân công Admin/CTV chấm bài cho project.
 * Một project có thể có nhiều reviewers để phân tải công việc.
 * 
 * @property int $id
 * @property int $project_id
 * @property int $user_id
 * @property int|null $assigned_by
 * @property string $assigned_at
 * @property bool $is_active
 * @property bool $can_final_grade
 * @property int $reviews_count
 * @property string|null $last_review_at
 */
class ProjectReviewer extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'assigned_by',
        'assigned_at',
        'is_active',
        'can_final_grade',
        'reviews_count',
        'last_review_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'can_final_grade' => 'boolean',
        'reviews_count' => 'integer',
        'assigned_at' => 'datetime',
        'last_review_at' => 'datetime',
    ];

    /**
     * RELATIONSHIPS
     */

    /**
     * Project được phân công
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Reviewer (User là Admin hoặc CTV)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\Modules\Auth\Models\User::class);
    }

    /**
     * Người phân công (Admin)
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(\Modules\Auth\Models\User::class, 'assigned_by');
    }

    /**
     * SCOPES
     */

    /**
     * Chỉ lấy reviewers active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Lấy reviewers có quyền chấm điểm cuối cùng
     */
    public function scopeCanFinalGrade($query)
    {
        return $query->where('can_final_grade', true);
    }

    /**
     * Lấy reviewers của một project cụ thể
     */
    public function scopeForProject($query, int $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Lấy assignments của một reviewer cụ thể
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Sắp xếp theo số lượng reviews (ít nhất trước - load balancing)
     */
    public function scopeOrderByWorkload($query)
    {
        return $query->orderBy('reviews_count', 'asc');
    }

    /**
     * ACCESSORS & HELPERS
     */

    /**
     * Kiểm tra reviewer này có active không
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Kiểm tra có quyền chấm điểm cuối cùng không
     */
    public function canGiveFinalGrade(): bool
    {
        return $this->can_final_grade;
    }

    /**
     * Lấy số lượng reviews đã làm
     */
    public function getReviewsCount(): int
    {
        return $this->reviews_count;
    }

    /**
     * Lấy thời gian phân công dưới dạng human readable
     */
    public function getAssignedAtForHumans(): string
    {
        return $this->assigned_at->diffForHumans();
    }

    /**
     * Lấy thời gian review gần nhất dưới dạng human readable
     */
    public function getLastReviewAtForHumans(): ?string
    {
        if (empty($this->last_review_at)) {
            return null;
        }

        return $this->last_review_at->diffForHumans();
    }

    /**
     * BUSINESS LOGIC
     */

    /**
     * Phân công reviewer cho project
     */
    public static function assignReviewer(
        int $projectId,
        int $userId,
        int $assignedBy,
        bool $canFinalGrade = false
    ): self {
        return self::create([
            'project_id' => $projectId,
            'user_id' => $userId,
            'assigned_by' => $assignedBy,
            'assigned_at' => now(),
            'is_active' => true,
            'can_final_grade' => $canFinalGrade,
            'reviews_count' => 0,
        ]);
    }

    /**
     * Tăng số lượng reviews đã làm
     */
    public function incrementReviewsCount(): bool
    {
        $this->reviews_count++;
        $this->last_review_at = now();
        
        return $this->save();
    }

    /**
     * Deactivate reviewer
     */
    public function deactivate(): bool
    {
        $this->is_active = false;
        return $this->save();
    }

    /**
     * Activate reviewer
     */
    public function activate(): bool
    {
        $this->is_active = true;
        return $this->save();
    }

    /**
     * Cấp quyền chấm điểm cuối cùng
     */
    public function grantFinalGradePermission(): bool
    {
        $this->can_final_grade = true;
        return $this->save();
    }

    /**
     * Thu hồi quyền chấm điểm cuối cùng
     */
    public function revokeFinalGradePermission(): bool
    {
        $this->can_final_grade = false;
        return $this->save();
    }

    /**
     * Kiểm tra reviewer có được phân công cho project này không
     */
    public static function isReviewerForProject(int $projectId, int $userId): bool
    {
        return self::where('project_id', $projectId)
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Lấy reviewer có ít công việc nhất cho project (load balancing)
     */
    public static function getLeastBusyReviewerForProject(int $projectId): ?self
    {
        return self::forProject($projectId)
            ->active()
            ->orderByWorkload()
            ->first();
    }
}
