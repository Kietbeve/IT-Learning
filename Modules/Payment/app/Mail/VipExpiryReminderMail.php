<?php

namespace Modules\Payment\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class VipExpiryReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public int $daysRemaining;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, int $daysRemaining)
    {
        $this->user = $user;
        $this->daysRemaining = $daysRemaining;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('VIP sắp hết hạn - IT-Learning')
            ->markdown('payment::emails.vip.expiry-reminder')
            ->with([
                'user' => $this->user,
                'daysRemaining' => $this->daysRemaining,
                'expiresAt' => $this->user->vip_expires_at,
                'quota' => $this->user->vip_download_quota,
            ]);
    }
}
