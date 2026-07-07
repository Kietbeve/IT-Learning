<?php

namespace Modules\Payment\Events;


use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Modules\Document\Models\Document;

class DocumentDownloadedByVip
{
    use Dispatchable, SerializesModels;

    public User $user;
    public Document $document;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Document $document)
    {
        $this->user = $user;
        $this->document = $document;
    }
}
