<?php

namespace Modules\Document\Events;


use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Document\Models\Document;

class DocumentRejected
{
    use Dispatchable, SerializesModels;

    public $document;
    public $reason;

    public function __construct(Document $document, string $reason)
    {
        $this->document = $document;
        $this->reason = $reason;
    }
}
