<?php

namespace Modules\Learning\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Auth\Models\User;
use Modules\Learning\Models\ForumThread;

class ForumThreadPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, ForumThread $thread): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, ForumThread $thread): bool
    {
        return $user->id === $thread->user_id || $user->hasRole('admin');
    }

    public function delete(User $user, ForumThread $thread): bool
    {
        return $user->id === $thread->user_id || $user->hasRole('admin');
    }

    public function pin(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function lock(User $user): bool
    {
        return $user->hasRole('admin');
    }
}
