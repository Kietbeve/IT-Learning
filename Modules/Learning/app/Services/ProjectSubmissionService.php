<?php

namespace Modules\Learning\Services;

use Modules\Learning\Models\Project;
use Modules\Learning\Models\ProjectSubmission;
use Modules\Learning\Models\RoadmapLesson;
use Modules\Learning\Models\Enrollment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ProjectSubmissionService
{
    /**
     * Submit a project for a lesson
     */
    public function submitProject(
        int $userId,
        int $lessonId,
        int $roadmapId,
        string $githubUrl,
        ?string $liveDemoUrl = null,
        ?string $note = null,
        ?UploadedFile $attachment = null
    ): ProjectSubmission {
        // Find the lesson and verify it has a project
        $lesson = RoadmapLesson::findOrFail($lessonId);
        
        if (!$lesson->project_id) {
            throw new \Exception('Bài học này không có project để nộp.');
        }

        $project = Project::findOrFail($lesson->project_id);

        // Verify user is enrolled in the roadmap
        $enrollment = Enrollment::where('user_id', $userId)
            ->where('roadmap_id', $roadmapId)
            ->firstOrFail();

        // Get existing submission if any
        $existingSubmission = ProjectSubmission::where('project_id', $project->id)
            ->where('user_id', $userId)
            ->first();

        // Check resubmission limit
        if ($existingSubmission) {
            $this->validateResubmissionLimit($existingSubmission, $project);
        }

        // Handle file upload
        $attachmentPath = null;
        if ($attachment) {
            $attachmentPath = $this->storeAttachment($attachment);
            
            // Delete old attachment if exists
            if ($existingSubmission && $existingSubmission->attachment_path) {
                Storage::disk('public')->delete($existingSubmission->attachment_path);
            }
        }

        // Determine submission number
        $submissionNo = $existingSubmission ? $existingSubmission->submission_no + 1 : 1;

        // Determine status
        $status = $existingSubmission && $existingSubmission->status === 'failed' 
            ? 'resubmitted' 
            : 'submitted';

        // Create or update submission
        if ($existingSubmission) {
            $existingSubmission->update([
                'github_url' => $githubUrl,
                'live_demo_url' => $liveDemoUrl,
                'attachment_path' => $attachmentPath ?? $existingSubmission->attachment_path,
                'note' => $note,
                'submission_no' => $submissionNo,
                'status' => $status,
                'submitted_at' => now(),
                'reviewed_by' => null,
                'reviewed_at' => null,
                'feedback' => null,
            ]);
            
            return $existingSubmission->fresh();
        }

        return ProjectSubmission::create([
            'project_id' => $project->id,
            'user_id' => $userId,
            'enrollment_id' => $enrollment->id,
            'github_url' => $githubUrl,
            'live_demo_url' => $liveDemoUrl,
            'attachment_path' => $attachmentPath,
            'note' => $note,
            'submission_no' => $submissionNo,
            'status' => $status,
            'submitted_at' => now(),
        ]);
    }

    /**
     * Review a project submission
     */
    public function reviewSubmission(
        int $submissionId,
        int $reviewerId,
        string $status,
        ?string $feedback = null
    ): ProjectSubmission {
        if (!in_array($status, ['passed', 'failed', 'in_review'])) {
            throw new \Exception('Trạng thái không hợp lệ.');
        }

        $submission = ProjectSubmission::findOrFail($submissionId);

        $submission->update([
            'status' => $status,
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'feedback' => $feedback,
        ]);

        return $submission->fresh();
    }

    /**
     * Get user's submission for a project
     */
    public function getUserSubmission(int $userId, int $projectId): ?ProjectSubmission
    {
        return ProjectSubmission::where('user_id', $userId)
            ->where('project_id', $projectId)
            ->first();
    }

    /**
     * Get pending submissions for review
     */
    public function getPendingSubmissions()
    {
        return ProjectSubmission::with(['project', 'user', 'submitter'])
            ->whereIn('status', ['submitted', 'resubmitted'])
            ->orderBy('submitted_at', 'asc')
            ->paginate(20);
    }

    /**
     * Get submissions by status
     */
    public function getSubmissionsByStatus(string $status)
    {
        return ProjectSubmission::with(['project', 'user', 'submitter'])
            ->where('status', $status)
            ->orderBy('submitted_at', 'desc')
            ->paginate(20);
    }

    /**
     * Validate resubmission limit
     */
    protected function validateResubmissionLimit(ProjectSubmission $submission, Project $project): void
    {
        // Only check if status is failed (user is resubmitting)
        if ($submission->status !== 'failed') {
            throw new \Exception('Bạn không thể nộp lại khi project chưa được đánh giá hoặc đã được chấp nhận.');
        }

        // Check if resubmission limit reached
        if ($submission->submission_no >= $project->max_resubmissions) {
            throw new \Exception(
                "Bạn đã hết lượt nộp lại. Giới hạn: {$project->max_resubmissions} lần."
            );
        }
    }

    /**
     * Store attachment file
     */
    protected function storeAttachment(UploadedFile $file): string
    {
        return $file->store('project-submissions', 'public');
    }

    /**
     * Get submission statistics
     */
    public function getSubmissionStats(): array
    {
        return [
            'total' => ProjectSubmission::count(),
            'pending' => ProjectSubmission::whereIn('status', ['submitted', 'resubmitted'])->count(),
            'in_review' => ProjectSubmission::where('status', 'in_review')->count(),
            'passed' => ProjectSubmission::where('status', 'passed')->count(),
            'failed' => ProjectSubmission::where('status', 'failed')->count(),
        ];
    }

    /**
     * Get user submission statistics
     */
    public function getUserSubmissionStats(int $userId): array
    {
        return [
            'total' => ProjectSubmission::where('user_id', $userId)->count(),
            'passed' => ProjectSubmission::where('user_id', $userId)->where('status', 'passed')->count(),
            'pending' => ProjectSubmission::where('user_id', $userId)
                ->whereIn('status', ['submitted', 'resubmitted', 'in_review'])
                ->count(),
            'failed' => ProjectSubmission::where('user_id', $userId)->where('status', 'failed')->count(),
        ];
    }

    /**
     * Check if user can submit for a project
     */
    public function canUserSubmit(int $userId, int $projectId): array
    {
        $submission = $this->getUserSubmission($userId, $projectId);
        
        if (!$submission) {
            return [
                'can_submit' => true,
                'reason' => null,
                'submission_no' => 0,
            ];
        }

        $project = Project::findOrFail($projectId);

        // If status is passed, can't resubmit
        if ($submission->status === 'passed') {
            return [
                'can_submit' => false,
                'reason' => 'Project đã được chấp nhận.',
                'submission_no' => $submission->submission_no,
            ];
        }

        // If status is submitted, resubmitted, or in_review, can't submit again
        if (in_array($submission->status, ['submitted', 'resubmitted', 'in_review'])) {
            return [
                'can_submit' => false,
                'reason' => 'Project đang chờ đánh giá.',
                'submission_no' => $submission->submission_no,
            ];
        }

        // If status is failed, check resubmission limit
        if ($submission->status === 'failed') {
            if ($submission->submission_no >= $project->max_resubmissions) {
                return [
                    'can_submit' => false,
                    'reason' => "Đã hết lượt nộp lại (tối đa {$project->max_resubmissions} lần).",
                    'submission_no' => $submission->submission_no,
                ];
            }

            return [
                'can_submit' => true,
                'reason' => null,
                'submission_no' => $submission->submission_no,
            ];
        }

        return [
            'can_submit' => true,
            'reason' => null,
            'submission_no' => $submission->submission_no,
        ];
    }
}
