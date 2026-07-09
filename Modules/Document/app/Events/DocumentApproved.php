<?php

namespace Modules\Document\Events;


use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Document\Models\Document;

class DocumentApproved
{
    use Dispatchable, SerializesModels;

    public $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }
}
