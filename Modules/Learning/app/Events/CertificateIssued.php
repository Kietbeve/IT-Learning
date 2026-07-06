<?php

namespace Modules\Learning\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Learning\Models\RoadmapCertificate;

class CertificateIssued
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public RoadmapCertificate $certificate
    ) {}
}
