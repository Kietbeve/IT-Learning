<?php

namespace Modules\Learning\Policies;

use Modules\Learning\Models\Assignment;
use Modules\Learning\Models\AssignmentSubmission;
use Modules\Auth\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssignmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if user có thể view assignment
     */
    public function view(User $user, Assignment $assignment): bool
    {
        // Check if assignment is published
        if (!$assignment->isPublished()) {
            // Only admin/instructor có thể xem draft
            return $user->type === 'admin' || $user->type === 'instructor';
        }

        // Check if user enrolled in roadmap
        $enrollment = $assignment->lesson->roadmap->getEnrollmentFor($user->id);
        return $enrollment !== null;
    }

    /**
     * Determine if user có thể submit assignment
     */
    public function submit(User $user, Assignment $assignment): bool
    {
        if (!$assignment->isPublished()) {
            return false;
        }

        // Check enrollment
        $enrollment = $assignment->lesson->roadmap->getEnrollmentFor($user->id);
        if (!$enrollment) {
            return false;
        }

        // Check if lesson's section is unlocked
        $lesson = $assignment->lesson;
        if ($lesson->section_id) {
            $section = $lesson->section;
            if ($section->isLockedForUser($user->id)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if user có thể view submission
     */
    public function viewSubmission(User $user, AssignmentSubmission $submission): bool
    {
        // Owner có thể xem submission của mình
        if ($submission->user_id === $user->id) {
            return true;
        }

        // Admin/instructor có thể xem tất cả
        if ($user->type === 'admin') {
            return true;
        }

        // Instructor chỉ xem submission của roadmap mình tạo
        if ($user->type === 'instructor') {
            return $submission->assignment->lesson->roadmap->author_id === $user->id;
        }

        return false;
    }

    /**
     * Determine if user có thể edit submission
     */
    public function updateSubmission(User $user, AssignmentSubmission $submission): bool
    {
        // Chỉ owner mới edit được và chỉ khi chưa submit hoặc chưa chấm
        if ($submission->user_id !== $user->id) {
            return false;
        }

        return in_array($submission->status, ['draft', 'needs_revision']);
    }

    /**
     * Determine if user có thể delete submission
     */
    public function deleteSubmission(User $user, AssignmentSubmission $submission): bool
    {
        // Owner có thể xóa submission draft
        if ($submission->user_id === $user->id && $submission->status === 'draft') {
            return true;
        }

        // Admin có thể xóa bất kỳ submission nào
        return $user->type === 'admin';
    }

    /**
     * Determine if user có thể grade submission
     */
    public function grade(User $user, AssignmentSubmission $submission): bool
    {
        // Admin luôn được phép chấm
        if ($user->type === 'admin') {
            return true;
        }

        // Instructor chỉ chấm submission của roadmap mình tạo
        if ($user->type === 'instructor') {
            return $submission->assignment->lesson->roadmap->author_id === $user->id;
        }

        // Check if user is assigned grader
        $isGrader = $submission->assignment->graders()
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->exists();

        return $isGrader;
    }

    /**
     * Determine if user có thể create assignment
     */
    public function create(User $user): bool
    {
        return $user->type === 'admin' || $user->type === 'instructor';
    }

    /**
     * Determine if user có thể update assignment
     */
    public function update(User $user, Assignment $assignment): bool
    {
        if ($user->type === 'admin') {
            return true;
        }

        if ($user->type === 'instructor') {
            return $assignment->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine if user có thể delete assignment
     */
    public function delete(User $user, Assignment $assignment): bool
    {
        return $this->update($user, $assignment);
    }

    /**
     * Determine if user có thể download attachment
     */
    public function downloadAttachment(User $user, Assignment $assignment): bool
    {
        return $this->view($user, $assignment);
    }

    /**
     * Determine if user có thể upload attachment
     */
    public function uploadAttachment(User $user, Assignment $assignment): bool
    {
        // Instructor/admin có thể upload attachment cho assignment
        if ($user->type === 'admin') {
            return true;
        }

        if ($user->type === 'instructor') {
            return $assignment->created_by === $user->id;
        }

        return false;
    }
}
