<?php

namespace Modules\Learning\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Learning\Services\CertificateService;
use Modules\Learning\Models\RoadmapCertificate;
use Illuminate\Support\Facades\Log;

class GenerateCertificateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $userId,
        public int $roadmapId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $certificateService = app(CertificateService::class);
            
            // Issue certificate
            $certificate = $certificateService->issueCertificate($this->userId, $this->roadmapId);
            
            if ($certificate) {
                // Generate PDF
                $certificateService->generateCertificatePdf($certificate->id);
                
                Log::info("Certificate generated successfully", [
                    'certificate_id' => $certificate->id,
                    'user_id' => $this->userId,
                    'roadmap_id' => $this->roadmapId,
                ]);
            } else {
                Log::warning("Certificate generation skipped - requirements not met", [
                    'user_id' => $this->userId,
                    'roadmap_id' => $this->roadmapId,
                ]);
            }
        } catch (\Exception $e) {
            Log::error("GenerateCertificateJob failed", [
                'user_id' => $this->userId,
                'roadmap_id' => $this->roadmapId,
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
        return ['learning', 'certificates', "user:{$this->userId}"];
    }
}
