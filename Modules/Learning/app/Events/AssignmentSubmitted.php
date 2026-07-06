<?php

namespace Modules\Learning\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Learning\Models\AssignmentSubmission;

class AssignmentSubmitted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public AssignmentSubmission $submission
    ) {}
}
