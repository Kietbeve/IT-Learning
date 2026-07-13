<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model ProjectFinalGrade
 * 
 * Điểm số và nhận xét cuối cùng sau khi học viên hoàn thành tất cả các bước.
 * Chỉ được tạo khi tất cả steps đã approved.
 * 
 * @property int $id
 * @property int $project_submission_id
 * @property int $user_id
 * @property float $score
 * @property string|null $grade_level (excellent|good|average|poor)
 * @property string|null $feedback
 * @property array|null $step_scores
 * @property int $graded_by
 * @property string $graded_at
 * @property bool $is_passed
 * @property string|null $completed_at
 */
class ProjectFinalGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_submission_id',
        'user_id',
        'score',
        'grade_level',
        'feedback',
        'step_scores',
        'graded_by',
        'graded_at',
        'is_passed',
        'completed_at',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'step_scores' => 'array',
        'graded_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_passed' => 'boolean',
    ];

    /**
     * RELATIONSHIPS
     */

    /**
     * Final grade cho submission nào
     */
    public function projectSubmission(): BelongsTo
    {
        return $this->belongsTo(ProjectSubmission::class);
    }

    /**
     * Học viên được chấm điểm
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\Modules\Auth\Models\User::class);
    }

    /**
     * Người chấm điểm
     */
    public function gradedBy(): BelongsTo
    {
        return $this->belongsTo(\Modules\Auth\Models\User::class, 'graded_by');
    }

    /**
     * SCOPES
     */

    /**
     * Lấy grades đã pass
     */
    public function scopePassed($query)
    {
        return $query->where('is_passed', true);
    }

    /**
     * Lấy grades chưa pass
     */
    public function scopeFailed($query)
    {
        return $query->where('is_passed', false);
    }

    /**
     * Lấy grades theo level
     */
    public function scopeByLevel($query, string $level)
    {
        return $query->where('grade_level', $level);
    }

    /**
     * Lấy grades của một user cụ thể
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * ACCESSORS & HELPERS
     */

    /**
     * Lấy grade level label tiếng Việt
     */
    public function getGradeLevelLabel(): string
    {
        return match($this->grade_level) {
            'excellent' => 'Xuất sắc',
            'good' => 'Tốt',
            'average' => 'Trung bình',
            'poor' => 'Yếu',
            default => 'Chưa xếp loại',
        };
    }

    /**
     * Lấy grade level color
     */
    public function getGradeLevelColor(): string
    {
        return match($this->grade_level) {
            'excellent' => 'purple',
            'good' => 'green',
            'average' => 'yellow',
            'poor' => 'red',
            default => 'gray',
        };
    }

    /**
     * Lấy pass/fail badge color
     */
    public function getPassedColor(): string
    {
        return $this->is_passed ? 'green' : 'red';
    }

    /**
     * Lấy pass/fail label
     */
    public function getPassedLabel(): string
    {
        return $this->is_passed ? 'Đạt' : 'Chưa đạt';
    }

    /**
     * Lấy score formatted
     */
    public function getScoreFormatted(): string
    {
        return number_format($this->score, 2) . '/100';
    }

    /**
     * Lấy graded_at dưới dạng human readable
     */
    public function getGradedAtForHumans(): string
    {
        return $this->graded_at->diffForHumans();
    }

    /**
     * BUSINESS LOGIC
     */

    /**
     * Tạo final grade mới
     */
    public static function createGrade(
        int $projectSubmissionId,
        int $userId,
        float $score,
        int $gradedBy,
        ?string $feedback = null,
        ?array $stepScores = null
    ): self {
        // Tự động xác định grade level dựa vào score
        $gradeLevel = self::determineGradeLevel($score);
        
        // Tự động xác định is_passed (>= 50 điểm là pass)
        $isPassed = $score >= 50;

        return self::create([
            'project_submission_id' => $projectSubmissionId,
            'user_id' => $userId,
            'score' => $score,
            'grade_level' => $gradeLevel,
            'feedback' => $feedback,
            'step_scores' => $stepScores,
            'graded_by' => $gradedBy,
            'graded_at' => now(),
            'is_passed' => $isPassed,
            'completed_at' => now(),
        ]);
    }

    /**
     * Xác định grade level dựa vào điểm số
     */
    public static function determineGradeLevel(float $score): string
    {
        return match(true) {
            $score >= 90 => 'excellent',
            $score >= 70 => 'good',
            $score >= 50 => 'average',
            default => 'poor',
        };
    }

    /**
     * Update score và recalculate grade level
     */
    public function updateScore(float $newScore): bool
    {
        $this->score = $newScore;
        $this->grade_level = self::determineGradeLevel($newScore);
        $this->is_passed = $newScore >= 50;
        
        return $this->save();
    }
}
