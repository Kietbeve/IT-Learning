<?php

namespace Modules\Learning\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Learning\Services\SectionProgressService;
use Illuminate\Support\Facades\Log;

class UpdateSectionProgressJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $userId,
        public int $lessonId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $sectionProgressService = app(SectionProgressService::class);
            
            // Update progress based on lesson completion
            $sectionProgressService->updateProgressOnLessonComplete($this->userId, $this->lessonId);
            
            Log::info("Section progress updated", [
                'user_id' => $this->userId,
                'lesson_id' => $this->lessonId,
            ]);
        } catch (\Exception $e) {
            Log::error("UpdateSectionProgressJob failed", [
                'user_id' => $this->userId,
                'lesson_id' => $this->lessonId,
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return ['learning', 'progress', "user:{$this->userId}"];
    }
}
