<?php

namespace Modules\Learning\Policies;

use Modules\Learning\Models\RoadmapSection;
use Modules\Learning\Models\SectionProgress;
use Modules\Auth\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SectionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if user có thể access section
     */
    public function access(User $user, RoadmapSection $section): bool
    {
        // Check if user enrolled in roadmap
        $enrollment = $section->roadmap->getEnrollmentFor($user->id);
        if (!$enrollment) {
            return false;
        }

        // Check if section is locked
        if ($section->is_locked && $section->prerequisite_section_id) {
            $prerequisiteProgress = SectionProgress::where('user_id', $user->id)
                ->where('section_id', $section->prerequisite_section_id)
                ->first();

            if (!$prerequisiteProgress || $prerequisiteProgress->status !== 'completed') {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if user có thể xem section content
     */
    public function view(User $user, RoadmapSection $section): bool
    {
        return $this->access($user, $section);
    }

    /**
     * Determine if user có thể start section
     */
    public function start(User $user, RoadmapSection $section): bool
    {
        if (!$this->access($user, $section)) {
            return false;
        }

        $progress = SectionProgress::where('user_id', $user->id)
            ->where('section_id', $section->id)
            ->first();

        return !$progress || $progress->status === 'not_started';
    }

    /**
     * Determine if admin có thể create section
     */
    public function create(User $user): bool
    {
        return $user->type === 'admin' || $user->type === 'instructor';
    }

    /**
     * Determine if user có thể update section
     */
    public function update(User $user, RoadmapSection $section): bool
    {
        if ($user->type === 'admin') {
            return true;
        }

        if ($user->type === 'instructor') {
            // Instructor chỉ có thể edit section của roadmap mình tạo
            return $section->roadmap->author_id === $user->id;
        }

        return false;
    }

    /**
     * Determine if user có thể delete section
     */
    public function delete(User $user, RoadmapSection $section): bool
    {
        return $this->update($user, $section);
    }

    /**
     * Determine if user có thể unlock section manually
     */
    public function unlock(User $user, RoadmapSection $section): bool
    {
        return $user->type === 'admin';
    }
}
