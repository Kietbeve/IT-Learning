<?php

namespace Modules\Learning\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Learning\Models\SectionProgress;

class SectionCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public SectionProgress $sectionProgress
    ) {}
}
