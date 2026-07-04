<?php

namespace Modules\Exam\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

use Modules\Exam\Models\ExamAttempt;
use Modules\Exam\Mail\AttemptResultMail;

class SendAttemptResultEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected int $attemptId,
        protected array $stats
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $attempt = ExamAttempt::with([
            'user',
            'exam'
        ])->findOrFail($this->attemptId);

        Mail::to($attempt->user->email)
            ->send(new AttemptResultMail(
                $attempt,
                $this->stats
            ));
    }
}
