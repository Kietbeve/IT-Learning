<?php

namespace Modules\Payment\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Modules\Payment\Events\VipExpiringSoon;

class SendVipExpiryReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vip:send-expiry-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders to users whose VIP subscription expires in 3 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Searching for VIP users expiring in 3 days...');

        // Find users whose VIP expires exactly 3 days from now
        $users = User::whereDate('vip_expires_at', now()->addDays(3))
            ->where('vip_expires_at', '>', now())
            ->get();

        if ($users->isEmpty()) {
            $this->info('✅ No users found with VIP expiring in 3 days.');

            return 0;
        }

        $count = 0;
        foreach ($users as $user) {
            // Fire event to send notification
            event(new VipExpiringSoon($user, 3));
            $count++;

            $this->line("📧 Reminder sent to: {$user->email} (expires: {$user->vip_expires_at->format('d/m/Y')})");
        }

        $this->info("✅ Successfully sent {$count} VIP expiry reminder(s)!");

        return 0;
    }
}
