<?php

namespace Modules\Payment\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class VipExpiringSoon
{
    use Dispatchable, SerializesModels;

    public User $user;

    public int $daysRemaining;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, int $daysRemaining)
    {
        $this->user = $user;
        $this->daysRemaining = $daysRemaining;
    }
}
