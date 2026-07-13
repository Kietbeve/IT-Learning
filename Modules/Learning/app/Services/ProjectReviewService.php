<?php

namespace Modules\Learning\Services;

use Modules\Learning\Models\ProjectStepSubmission;
use Modules\Learning\Models\ProjectStepReview;
use Modules\Learning\Models\ProjectSubmission;
use Modules\Learning\Models\ProjectFinalGrade;
use Modules\Learning\Models\ProjectReviewer;
use Modules\Learning\Models\ProjectSubmissionStep;
use Illuminate\Support\Facades\DB;

/**
 * Service ProjectReviewService
 * 
 * Xử lý logic review và chấm điểm từ phía Admin/CTV:
 * - Approve/Reject submissions
 * - Assign reviewers
 * - Calculate và submit final grade
 * - Tracking review statistics
 */
class ProjectReviewService
{
    /**
     * Approve một step submission
     * 
     * @param int $submissionId ID của step submission
     * @param int $reviewerId ID của reviewer (Admin/CTV)
     * @param string|null $feedback Nhận xét (optional)
     * @return ProjectStepSubmission
     * @throws \Exception
     */
    public function approveSubmission(
        int $submissionId,
        int $reviewerId,
        ?string $feedback = null
    ): ProjectStepSubmission {
        DB::beginTransaction();
        
        try {
            $submission = ProjectStepSubmission::findOrFail($submissionId);
            
            // Kiểm tra reviewer có quyền chấm project này không
            $this->ensureReviewerHasAccess($submission->projectSubmission->project_id, $reviewerId);
            
            // Approve submission
            $submission->approve($reviewerId, $feedback);
            
            // Update reviewer statistics
            $this->updateReviewerStats($submission->projectSubmission->project_id, $reviewerId);
            
            // Update project progress
            $submissionService = new ProjectSubmissionService();
            $submissionService->updateProjectProgress($submission->project_submission_id);
            
            // Kiểm tra nếu hoàn thành tất cả steps -> tự động notify reviewer chấm final
            if ($submissionService->isProjectCompleted($submission->project_submission_id)) {
                // TODO: Send notification to reviewer có quyền final_grade
                // $this->notifyForFinalGrading($submission->project_submission_id);
            }
            
            DB::commit();
            
            return $submission->fresh();
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reject một step submission với feedback
     * 
     * @param int $submissionId ID của step submission
     * @param int $reviewerId ID của reviewer
     * @param string $feedback Feedback bắt buộc khi reject
     * @return ProjectStepSubmission
     * @throws \Exception
     */
    public function rejectSubmission(
        int $submissionId,
        int $reviewerId,
        string $feedback
    ): ProjectStepSubmission {
        DB::beginTransaction();
        
        try {
            if (empty(trim($feedback))) {
                throw new \Exception("Vui lòng cung cấp feedback khi từ chối bài nộp");
            }
            
            $submission = ProjectStepSubmission::findOrFail($submissionId);
            
            // Kiểm tra reviewer có quyền chấm project này không
            $this->ensureReviewerHasAccess($submission->projectSubmission->project_id, $reviewerId);
            
            // Reject submission
            $submission->reject($reviewerId, $feedback);
            
            // Update reviewer statistics
            $this->updateReviewerStats($submission->projectSubmission->project_id, $reviewerId);
            
            // TODO: Send notification to student
            // $this->notifyStudentRejection($submission);
            
            DB::commit();
            
            return $submission->fresh();
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Kiểm tra reviewer có quyền chấm project này không
     */
    protected function ensureReviewerHasAccess(int $projectId, int $reviewerId): void
    {
        $user = \Modules\Auth\Models\User::find($reviewerId);
        if ($user && $user->hasRole('admin')) {
            return;
        }

        $isReviewer = ProjectReviewer::where('project_id', $projectId)
            ->where('user_id', $reviewerId)
            ->where('is_active', true)
            ->exists();
        
        if (!$isReviewer) {
            throw new \Exception("Bạn không có quyền chấm project này");
        }
    }

    /**
     * Update statistics của reviewer
     */
    protected function updateReviewerStats(int $projectId, int $reviewerId): void
    {
        $reviewer = ProjectReviewer::where('project_id', $projectId)
            ->where('user_id', $reviewerId)
            ->first();
        
        if ($reviewer) {
            $reviewer->incrementReviewsCount();
        }
    }

    /**
     * Assign reviewer cho project
     * 
     * @param int $projectId ID của project
     * @param int $userId ID của user (Admin/CTV)
     * @param int $assignedBy ID người phân công
     * @param bool $canFinalGrade Có quyền chấm điểm cuối cùng không
     * @return ProjectReviewer
     */
    public function assignReviewer(
        int $projectId,
        int $userId,
        int $assignedBy,
        bool $canFinalGrade = false
    ): ProjectReviewer {
        // Kiểm tra đã assign chưa
        $existing = ProjectReviewer::where('project_id', $projectId)
            ->where('user_id', $userId)
            ->first();
        
        if ($existing) {
            // Nếu đã có thì activate lại
            $existing->activate();
            if ($canFinalGrade) {
                $existing->grantFinalGradePermission();
            }
            return $existing;
        }
        
        return ProjectReviewer::assignReviewer($projectId, $userId, $assignedBy, $canFinalGrade);
    }

    /**
     * Remove reviewer khỏi project
     */
    public function removeReviewer(int $projectId, int $userId): bool
    {
        $reviewer = ProjectReviewer::where('project_id', $projectId)
            ->where('user_id', $userId)
            ->first();
        
        if (!$reviewer) {
            throw new \Exception("Reviewer không tồn tại");
        }
        
        return $reviewer->deactivate();
    }

    /**
     * Submit final grade cho project (sau khi tất cả steps approved)
     * 
     * @param int $projectSubmissionId ID của project submission
     * @param int $reviewerId ID của reviewer
     * @param float $score Điểm số (0-100)
     * @param string|null $feedback Nhận xét tổng quan
     * @param array|null $stepScores Điểm chi tiết từng bước
     * @return ProjectFinalGrade
     * @throws \Exception
     */
    public function submitFinalGrade(
        int $projectSubmissionId,
        int $reviewerId,
        float $score,
        ?string $feedback = null,
        ?array $stepScores = null
    ): ProjectFinalGrade {
        DB::beginTransaction();
        
        try {
            $projectSubmission = ProjectSubmission::findOrFail($projectSubmissionId);
            
            // Kiểm tra reviewer có quyền final grade không
            $reviewer = ProjectReviewer::where('project_id', $projectSubmission->project_id)
                ->where('user_id', $reviewerId)
                ->where('can_final_grade', true)
                ->where('is_active', true)
                ->first();
            
            if (!$reviewer) {
                throw new \Exception("Bạn không có quyền chấm điểm cuối cùng cho project này");
            }
            
            // Kiểm tra tất cả steps đã approved chưa
            $submissionService = new ProjectSubmissionService();
            if (!$submissionService->isProjectCompleted($projectSubmissionId)) {
                throw new \Exception("Học viên chưa hoàn thành tất cả các bước");
            }
            
            // Kiểm tra đã có final grade chưa
            $existing = ProjectFinalGrade::where('project_submission_id', $projectSubmissionId)->first();
            if ($existing) {
                throw new \Exception("Project này đã được chấm điểm cuối cùng rồi");
            }
            
            // Validate score
            if ($score < 0 || $score > 100) {
                throw new \Exception("Điểm số phải từ 0 đến 100");
            }
            
            // Create final grade
            $finalGrade = ProjectFinalGrade::createGrade(
                $projectSubmissionId,
                $projectSubmission->user_id,
                $score,
                $reviewerId,
                $feedback,
                $stepScores
            );
            
            // Update project submission status
            $projectSubmission->update([
                'status' => $finalGrade->is_passed ? 'passed' : 'failed',
                'score' => $score,
                'feedback' => $feedback,
                'reviewed_by' => $reviewerId,
                'reviewed_at' => now(),
            ]);
            
            // TODO: Send notification to student
            // $this->notifyStudentFinalGrade($projectSubmission, $finalGrade);
            
            DB::commit();
            
            return $finalGrade;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Lấy danh sách submissions cần review cho reviewer
     * 
     * @param int $reviewerId ID của reviewer
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPendingSubmissionsForReviewer(int $reviewerId)
    {
        // Lấy tất cả projects mà reviewer được assign
        $projectIds = ProjectReviewer::where('user_id', $reviewerId)
            ->where('is_active', true)
            ->pluck('project_id');
        
        if ($projectIds->isEmpty()) {
            return collect();
        }
        
        // Lấy tất cả submissions đang pending review cho các projects đó
        return ProjectStepSubmission::whereHas('projectSubmission', function ($query) use ($projectIds) {
                $query->whereIn('project_id', $projectIds);
            })
            ->whereIn('status', ['submitted', 'under_review'])
            ->where('is_current', true)
            ->with(['step', 'user', 'projectSubmission.project'])
            ->orderBy('submitted_at', 'asc')
            ->get();
    }

    /**
     * Lấy statistics cho reviewer
     */
    public function getReviewerStatistics(int $reviewerId): array
    {
        $reviewer = ProjectReviewer::where('user_id', $reviewerId)
            ->where('is_active', true)
            ->first();
        
        if (!$reviewer) {
            return [
                'total_reviews' => 0,
                'pending_reviews' => 0,
                'approved_count' => 0,
                'rejected_count' => 0,
            ];
        }
        
        $totalReviews = ProjectStepReview::where('reviewer_id', $reviewerId)->count();
        $approvedCount = ProjectStepReview::where('reviewer_id', $reviewerId)
            ->where('decision', 'approved')
            ->count();
        $rejectedCount = ProjectStepReview::where('reviewer_id', $reviewerId)
            ->where('decision', 'rejected')
            ->count();
        $pendingReviews = $this->getPendingSubmissionsForReviewer($reviewerId)->count();
        
        return [
            'total_reviews' => $totalReviews,
            'pending_reviews' => $pendingReviews,
            'approved_count' => $approvedCount,
            'rejected_count' => $rejectedCount,
            'approval_rate' => $totalReviews > 0 ? round(($approvedCount / $totalReviews) * 100, 2) : 0,
        ];
    }

    /**
     * Auto-assign submission to reviewer với least workload (load balancing)
     */
    public function autoAssignSubmissionToReviewer(int $projectId): ?ProjectReviewer
    {
        return ProjectReviewer::getLeastBusyReviewerForProject($projectId);
    }
}
