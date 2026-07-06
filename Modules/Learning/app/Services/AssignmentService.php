<?php

namespace Modules\Learning\Services;

use Modules\Learning\Models\Assignment;
use Modules\Learning\Models\AssignmentSubmission;
use Modules\Learning\Models\AssignmentAttachment;
use Modules\Learning\Models\AssignmentFeedback;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class AssignmentService
{
    /**
     * Tạo submission mới cho assignment
     */
    public function createSubmission(int $userId, int $assignmentId, array $data): AssignmentSubmission
    {
        $assignment = Assignment::with('lesson')->findOrFail($assignmentId);
        
        // Validate assignment is published
        if (!$assignment->isPublished()) {
            throw new \Exception('Assignment chưa được publish.');
        }

        // Validate lesson context
        if (!$assignment->lesson || !$assignment->lesson->is_published) {
            throw new \Exception('Lesson không hợp lệ hoặc chưa được publish.');
        }
        
        // Tính attempt number
        $attemptNumber = AssignmentSubmission::where('user_id', $userId)
            ->where('assignment_id', $assignmentId)
            ->max('attempt_number') + 1;

        $submission = AssignmentSubmission::create([
            'assignment_id' => $assignmentId,
            'user_id' => $userId,
            'github_url' => $data['github_url'] ?? null,
            'live_demo_url' => $data['live_demo_url'] ?? null,
            'notes' => $data['notes'] ?? null,
            'attempt_number' => $attemptNumber,
            'deadline' => now()->addDays($assignment->deadline_days),
            'status' => 'draft',
        ]);

        // Schedule deadline reminder
        app(NotificationService::class)->scheduleDeadlineReminder($submission);

        return $submission;
    }

    /**
     * Validate assignment can be created for a lesson
     */
    public function validateAssignmentForLesson(int $lessonId, ?int $existingAssignmentId = null): bool
    {
        $lesson = \Modules\Learning\Models\RoadmapLesson::with(['roadmap', 'section'])->find($lessonId);
        
        if (!$lesson) {
            throw new \Exception('Lesson không tồn tại.');
        }

        if (!$lesson->roadmap) {
            throw new \Exception('Lesson phải thuộc một roadmap.');
        }

        if (!$lesson->roadmap->status === 'approved') {
            throw new \Exception('Roadmap chưa được duyệt.');
        }

        // If editing existing assignment, verify it belongs to same roadmap
        if ($existingAssignmentId) {
            $existingAssignment = Assignment::with('lesson')->find($existingAssignmentId);
            if ($existingAssignment && $existingAssignment->lesson) {
                if ($existingAssignment->lesson->roadmap_id !== $lesson->roadmap_id) {
                    throw new \Exception('Assignment và Lesson phải cùng thuộc một roadmap.');
                }
            }
        }

        return true;
    }

    /**
     * Submit assignment (nộp bài)
     */
    public function submitAssignment(int $submissionId): AssignmentSubmission
    {
        $submission = AssignmentSubmission::findOrFail($submissionId);
        
        if ($submission->status === 'submitted' || $submission->status === 'graded') {
            throw new \Exception('Submission đã được nộp hoặc đã chấm.');
        }

        $submission->markAsSubmitted();

        // Notify instructor
        $this->notifyInstructorNewSubmission($submission);

        // Fire event
        event(new \Modules\Learning\Events\AssignmentSubmitted($submission));

        return $submission;
    }

    /**
     * Upload file attachment
     */
    public function uploadAttachment(
        int $uploadedBy,
        UploadedFile $file,
        ?int $assignmentId = null,
        ?int $submissionId = null,
        ?string $description = null
    ): AssignmentAttachment {
        $assignment = $assignmentId ? Assignment::find($assignmentId) : null;
        
        // Validate file type
        if ($assignment && $assignment->allowed_file_types) {
            $allowedTypes = explode(',', $assignment->allowed_file_types);
            $fileExtension = $file->getClientOriginalExtension();
            
            if (!in_array($fileExtension, $allowedTypes)) {
                throw new \Exception("File type .{$fileExtension} không được phép. Chỉ chấp nhận: " . $assignment->allowed_file_types);
            }
        }

        // Validate file size
        if ($assignment && $file->getSize() > ($assignment->max_file_size_mb * 1024 * 1024)) {
            throw new \Exception("File quá lớn. Tối đa {$assignment->max_file_size_mb}MB");
        }

        // Store file
        $path = $file->store('assignments/attachments', 'public');

        return AssignmentAttachment::create([
            'assignment_id' => $assignmentId,
            'submission_id' => $submissionId,
            'uploaded_by' => $uploadedBy,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'description' => $description,
        ]);
    }

    /**
     * Chấm bài assignment
     */
    public function gradeSubmission(
        int $submissionId,
        int $graderId,
        float $score,
        string $feedback
    ): AssignmentSubmission {
        $submission = AssignmentSubmission::findOrFail($submissionId);
        $assignment = $submission->assignment;

        if ($score > $assignment->max_score) {
            throw new \Exception("Điểm không được vượt quá {$assignment->max_score}");
        }

        $submission->markAsGraded($score, $feedback, $graderId);

        // Send notification
        app(NotificationService::class)->sendAssignmentGradedNotification($submission);

        // Fire event
        event(new \Modules\Learning\Events\AssignmentGraded($submission));

        return $submission;
    }

    /**
     * Thêm feedback chi tiết
     */
    public function addFeedback(
        int $submissionId,
        int $graderId,
        string $comment,
        ?float $score = null,
        ?int $rubricItemId = null,
        string $feedbackType = 'general',
        ?string $referenceFile = null,
        ?int $referenceLine = null
    ): AssignmentFeedback {
        return AssignmentFeedback::create([
            'submission_id' => $submissionId,
            'rubric_item_id' => $rubricItemId,
            'grader_id' => $graderId,
            'comment' => $comment,
            'score' => $score,
            'feedback_type' => $feedbackType,
            'reference_file' => $referenceFile,
            'reference_line' => $referenceLine,
        ]);
    }

    /**
     * Thêm inline feedback cho code
     */
    public function addInlineFeedback(
        int $submissionId,
        int $graderId,
        string $fileName,
        int $lineNumber,
        string $comment
    ): AssignmentFeedback {
        return $this->addFeedback(
            submissionId: $submissionId,
            graderId: $graderId,
            comment: $comment,
            feedbackType: 'inline',
            referenceFile: $fileName,
            referenceLine: $lineNumber
        );
    }

    /**
     * Lấy tất cả submissions của một assignment
     */
    public function getAssignmentSubmissions(int $assignmentId, ?string $status = null)
    {
        $query = AssignmentSubmission::where('assignment_id', $assignmentId)
            ->with(['student', 'attachments']);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('submitted_at', 'desc')->get();
    }

    /**
     * Lấy submissions của user trong roadmap
     */
    public function getUserSubmissions(int $userId, int $roadmapId)
    {
        return AssignmentSubmission::where('user_id', $userId)
            ->whereHas('assignment.lesson', function ($query) use ($roadmapId) {
                $query->where('roadmap_id', $roadmapId);
            })
            ->with(['assignment', 'attachments', 'feedback'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Check if submission is late
     */
    public function checkLateSubmissions(): int
    {
        $submissions = AssignmentSubmission::where('status', 'draft')
            ->where('deadline', '<', now())
            ->where('is_late', false)
            ->get();

        $count = 0;
        foreach ($submissions as $submission) {
            $submission->is_late = true;
            $submission->days_late = now()->diffInDays($submission->deadline);
            $submission->save();
            $count++;
        }

        return $count;
    }

    /**
     * Gửi reminder cho submissions sắp đến deadline
     */
    public function sendDeadlineReminders(): int
    {
        $submissions = AssignmentSubmission::where('status', 'draft')
            ->where('deadline', '>', now())
            ->where('reminder_sent', false)
            ->get();

        $count = 0;
        foreach ($submissions as $submission) {
            if ($submission->needsReminder()) {
                app(NotificationService::class)->sendAssignmentDueNotification(
                    $submission->user_id,
                    $submission->assignment,
                    $submission
                );
                
                $submission->reminder_sent = true;
                $submission->reminder_sent_at = now();
                $submission->save();
                
                $count++;
            }
        }

        return $count;
    }

    /**
     * Notify instructor khi có submission mới
     */
    protected function notifyInstructorNewSubmission(AssignmentSubmission $submission): void
    {
        $assignment = $submission->assignment;
        $instructorId = $assignment->created_by;

        // Create notification for instructor
        \Modules\Learning\Models\LearningNotification::create([
            'user_id' => $instructorId,
            'type' => 'assignment_due',
            'notifiable_type' => AssignmentSubmission::class,
            'notifiable_id' => $submission->id,
            'roadmap_id' => $assignment->lesson->roadmap_id,
            'title' => 'Có bài nộp mới',
            'message' => "Học viên {$submission->student->name} đã nộp bài '{$assignment->title}'",
            'action_url' => "/admin/submissions/{$submission->id}/review",
            'action_text' => 'Chấm bài',
            'priority' => 'normal',
            'sent_at' => now(),
        ]);
    }

    /**
     * Get assignment statistics
     */
    public function getAssignmentStats(int $assignmentId): array
    {
        $assignment = Assignment::findOrFail($assignmentId);
        $submissions = $assignment->submissions;

        return [
            'total_submissions' => $submissions->count(),
            'pending' => $submissions->where('status', 'submitted')->count(),
            'graded' => $submissions->where('status', 'graded')->count(),
            'late' => $submissions->where('is_late', true)->count(),
            'average_score' => $submissions->where('status', 'graded')->avg('score'),
            'pass_rate' => $this->calculatePassRate($submissions, $assignment->max_score),
        ];
    }

    /**
     * Calculate pass rate
     */
    protected function calculatePassRate($submissions, float $maxScore): float
    {
        $graded = $submissions->where('status', 'graded');
        if ($graded->isEmpty()) {
            return 0;
        }

        $passThreshold = $maxScore * 0.6; // 60% để pass
        $passed = $graded->where('score', '>=', $passThreshold)->count();

        return round(($passed / $graded->count()) * 100, 2);
    }

    /**
     * Delete attachment
     */
    public function deleteAttachment(int $attachmentId): bool
    {
        $attachment = AssignmentAttachment::find($attachmentId);
        if (!$attachment) {
            return false;
        }

        // Delete file from storage
        Storage::disk('public')->delete($attachment->file_path);

        // Delete record
        $attachment->delete();

        return true;
    }
}
