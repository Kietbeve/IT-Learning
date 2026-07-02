<?php

namespace Modules\Learning\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Auth\Models\User;
use Modules\Learning\Models\ForumPost;

class ForumPostPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, ForumPost $post): bool
    {
        return $user->id === $post->user_id || $user->hasRole('admin');
    }

    public function delete(User $user, ForumPost $post): bool
    {
        return $user->id === $post->user_id || $user->hasRole('admin');
    }

    public function markBestAnswer(User $user, ForumPost $post): bool
    {
        return $user->id === $post->thread->user_id || $user->hasRole('admin');
    }
}
