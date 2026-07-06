<?php

namespace Modules\Learning\Services;

use Modules\Learning\Models\Project;
use Modules\Learning\Models\ProjectSubmission;
use Modules\Learning\Models\RoadmapLesson;
use Modules\Learning\Models\RoadmapSection;
use Modules\Learning\Models\RoadmapLessonProgress;
use Modules\Learning\Models\RoadmapEnrollment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ProjectSubmissionService
{
    /**
     * Submit a project for a lesson (file + optional videos)
     */
    public function submitProject(
        int $userId,
        int $lessonId,
        int $roadmapId,
        string $note,
        ?UploadedFile $attachment = null,
        array $videos = []
    ): ProjectSubmission {
        // Find the lesson and verify it has a project
        $lesson = RoadmapLesson::findOrFail($lessonId);
        
        if (!$lesson->project_id) {
            throw new \Exception('Bài học này không có project để nộp.');
        }

        $project = Project::findOrFail($lesson->project_id);

        // Verify user is enrolled in the roadmap
        $enrollment = RoadmapEnrollment::where('user_id', $userId)
            ->where('roadmap_id', $roadmapId)
            ->firstOrFail();

        // Check lesson completion prerequisite
        $prerequisiteCheck = $this->checkLessonCompletionPrerequisite($userId, $project->id);
        if (!$prerequisiteCheck['passed']) {
            throw new \Exception(
                "Bạn cần hoàn thành {$prerequisiteCheck['required']}% bài học trong chương này trước khi nộp project. " .
                "Hiện tại: {$prerequisiteCheck['current']}% ({$prerequisiteCheck['completed']}/{$prerequisiteCheck['total']} bài)."
            );
        }

        // Get existing submission if any
        $existingSubmission = ProjectSubmission::where('project_id', $project->id)
            ->where('user_id', $userId)
            ->first();

        // Check resubmission limit
        if ($existingSubmission) {
            $this->validateResubmissionLimit($existingSubmission, $project);
        }

        // Handle file attachment (ZIP/RAR) - REQUIRED
        $attachmentPath = null;
        if ($attachment) {
            $attachmentPath = $attachment->store('project-files', 'public');
            
            // Delete old attachment if exists
            if ($existingSubmission && $existingSubmission->attachment_path) {
                Storage::disk('public')->delete($existingSubmission->attachment_path);
            }
        }

        // Handle multiple video uploads (OPTIONAL)
        $videoPaths = [];
        if (!empty($videos)) {
            foreach ($videos as $video) {
                $path = $video->store('project-videos', 'public');
                $videoPaths[] = $path;
            }
            
            // Delete old videos if exists
            if ($existingSubmission && $existingSubmission->video_files) {
                foreach ($existingSubmission->video_files as $oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
        }

        // Determine submission number
        $submissionNo = $existingSubmission ? $existingSubmission->submission_no + 1 : 1;

        // Determine status
        $status = $existingSubmission && $existingSubmission->status === 'failed' 
            ? 'resubmitted' 
            : 'submitted';

        // Calculate late submission status
        $isLate = false;
        $daysLate = 0;
        
        if ($project->deadline_at && now()->isAfter($project->deadline_at)) {
            $isLate = true;
            $daysLate = (int) now()->diffInDays($project->deadline_at, false);
        }

        // Create or update submission
        if ($existingSubmission) {
            $existingSubmission->update([
                'attachment_path' => $attachmentPath ?? $existingSubmission->attachment_path,
                'video_files' => !empty($videoPaths) ? $videoPaths : $existingSubmission->video_files,
                'note' => $note,
                'submission_no' => $submissionNo,
                'status' => $status,
                'submitted_at' => now(),
                'is_late' => $isLate,
                'days_late' => $daysLate,
                'reviewed_by' => null,
                'reviewed_at' => null,
                'feedback' => null,
                'score' => null,
                'grading_notes' => null,
            ]);
            
            return $existingSubmission->fresh();
        }

        return ProjectSubmission::create([
            'project_id' => $project->id,
            'user_id' => $userId,
            'enrollment_id' => $enrollment->id,
            'attachment_path' => $attachmentPath,
            'video_files' => $videoPaths,
            'note' => $note,
            'submission_no' => $submissionNo,
            'status' => $status,
            'submitted_at' => now(),
            'is_late' => $isLate,
            'days_late' => $daysLate,
        ]);
    }

    /**
     * Review a project submission
     */
    public function reviewSubmission(
        int $submissionId,
        int $reviewerId,
        string $status,
        ?string $feedback = null,
        ?float $score = null,
        ?array $gradingNotes = null
    ): ProjectSubmission {
        if (!in_array($status, ['passed', 'failed', 'in_review'])) {
            throw new \Exception('Trạng thái không hợp lệ.');
        }

        $submission = ProjectSubmission::findOrFail($submissionId);

        // Validate score if provided
        if ($score !== null) {
            $project = $submission->project;
            if ($score < 0 || $score > $project->max_score) {
                throw new \Exception("Điểm số phải nằm trong khoảng 0 - {$project->max_score}.");
            }

            // Auto-determine status based on score if not explicitly set to in_review
            if ($status !== 'in_review') {
                $status = $score >= $project->passing_score ? 'passed' : 'failed';
            }
        }

        $submission->update([
            'status' => $status,
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'feedback' => $feedback,
            'score' => $score,
            'grading_notes' => $gradingNotes,
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
            // Check prerequisites for first-time submission
            $prerequisiteCheck = $this->checkLessonCompletionPrerequisite($userId, $projectId);
            
            if (!$prerequisiteCheck['passed']) {
                return [
                    'can_submit' => false,
                    'reason' => "Cần hoàn thành {$prerequisiteCheck['required']}% bài học (hiện tại: {$prerequisiteCheck['current']}%)",
                    'submission_no' => 0,
                    'prerequisite' => $prerequisiteCheck,
                ];
            }
            
            return [
                'can_submit' => true,
                'reason' => null,
                'submission_no' => 0,
                'prerequisite' => $prerequisiteCheck,
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

    /**
     * Check if user has completed required % of lessons before submitting project
     */
    public function checkLessonCompletionPrerequisite(int $userId, int $projectId): array
    {
        $project = Project::with('section.lessons')->findOrFail($projectId);
        
        // If project has no section, no prerequisite check needed
        if (!$project->section) {
            return [
                'passed' => true,
                'current' => 100,
                'required' => 0,
                'completed' => 0,
                'total' => 0,
            ];
        }
        
        // Count total published lessons in the section (excluding project lessons)
        $totalLessons = $project->section->lessons()
            ->where('is_published', true)
            ->where('lesson_type', '!=', 'project') // Don't count project lessons
            ->count();
        
        if ($totalLessons === 0) {
            return [
                'passed' => true,
                'current' => 100,
                'required' => $project->required_completion_percentage,
                'completed' => 0,
                'total' => 0,
            ];
        }
        
        // Count completed lessons by user
        $lessonIds = $project->section->lessons()
            ->where('is_published', true)
            ->where('lesson_type', '!=', 'project')
            ->pluck('id');
        
        $completedLessons = RoadmapLessonProgress::where('user_id', $userId)
            ->whereIn('roadmap_lesson_id', $lessonIds)
            ->where('status', 'completed')
            ->count();
        
        $completionRate = ($completedLessons / $totalLessons) * 100;
        $required = $project->required_completion_percentage;
        
        return [
            'passed' => $completionRate >= $required,
            'current' => round($completionRate, 2),
            'required' => $required,
            'completed' => $completedLessons,
            'total' => $totalLessons,
        ];
    }
}
