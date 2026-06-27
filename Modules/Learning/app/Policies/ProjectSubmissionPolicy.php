<?php

namespace Modules\Learning\Policies;

use App\Models\User;
use Modules\Learning\Models\ProjectSubmission;
use Modules\Learning\Models\Enrollment;

class ProjectSubmissionPolicy
{
    /**
     * Determine if the user can view any submissions.
     */
    public function viewAny(User $user): bool
    {
        // Admins and instructors can view all submissions
        return $user->hasAnyRole(['admin', 'instructor']);
    }

    /**
     * Determine if the user can view the submission.
     */
    public function view(User $user, ProjectSubmission $submission): bool
    {
        // User can view their own submission
        if ($user->id === $submission->user_id) {
            return true;
        }

        // Admins and instructors can view any submission
        return $user->hasAnyRole(['admin', 'instructor']);
    }

    /**
     * Determine if the user can create a submission.
     */
    public function create(User $user, int $roadmapId): bool
    {
        // Check if user is enrolled in the roadmap
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('roadmap_id', $roadmapId)
            ->where('status', 'active')
            ->exists();

        return $enrollment;
    }

    /**
     * Determine if the user can update the submission.
     */
    public function update(User $user, ProjectSubmission $submission): bool
    {
        // Only the owner can update their submission
        // And only if it's in a state that allows updates (failed status)
        return $user->id === $submission->user_id 
            && in_array($submission->status, ['failed']);
    }

    /**
     * Determine if the user can delete the submission.
     */
    public function delete(User $user, ProjectSubmission $submission): bool
    {
        // Only admins can delete submissions
        return $user->hasRole('admin');
    }

    /**
     * Determine if the user can review the submission.
     */
    public function review(User $user, ProjectSubmission $submission): bool
    {
        // Admins and instructors can review submissions
        return $user->hasAnyRole(['admin', 'instructor']);
    }

    /**
     * Determine if the user can resubmit.
     */
    public function resubmit(User $user, ProjectSubmission $submission): bool
    {
        // User can resubmit only if:
        // 1. They own the submission
        // 2. Status is 'failed'
        // 3. Haven't exceeded max resubmissions
        if ($user->id !== $submission->user_id) {
            return false;
        }

        if ($submission->status !== 'failed') {
            return false;
        }

        $project = $submission->project;
        if ($submission->submission_no >= $project->max_resubmissions) {
            return false;
        }

        return true;
    }
}
