<?php

namespace Modules\Document\Events;


use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Modules\Document\Models\Document;

class CommentReplied
{
    use Dispatchable, SerializesModels;

    public User $replier;
    public Document $document;
    public int $commentId;
    public User $targetUser;

    /**
     * Create a new event instance.
     */
    public function __construct(User $replier, Document $document, int $commentId, User $targetUser)
    {
        $this->replier = $replier;
        $this->document = $document;
        $this->commentId = $commentId;
        $this->targetUser = $targetUser;
    }
}
