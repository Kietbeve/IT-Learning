<?php

namespace Modules\Exam\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

use Modules\Exam\Models\Exam;
use Modules\Exam\Mail\ExamReviewResultMail;
class SendExamReviewEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected int $examId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $exam = Exam::with([
            'author',
            'reviewer',
        ])->findOrFail($this->examId);

        Mail::to($exam->author->email)
            ->send(new ExamReviewResultMail($exam));
    }
}
