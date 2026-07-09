<?php

namespace Modules\Document\Events;


use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class DocumentDeletedByAdmin
{
    use Dispatchable, SerializesModels;

    public $author;
    public $documentTitle;

    public function __construct(User $author, string $documentTitle)
    {
        $this->author = $author;
        $this->documentTitle = $documentTitle;
    }
}
