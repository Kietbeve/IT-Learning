<?php

namespace Modules\Document\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Modules\Document\Models\Document;

class CommentRepliedNotification extends Notification
{
    use Queueable;

    public $replier;

    public $document;

    public $commentId;

    /**
     * Create a new notification instance.
     */
    public function __construct($replier, Document $document, $commentId)
    {
        $this->replier = $replier;
        $this->document = $document;
        $this->commentId = $commentId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'icon' => '💬',
            'title' => 'Có phản hồi mới',
            'message' => "{$this->replier->name} đã trả lời bình luận của bạn trong tài liệu \"{$this->document->title}\"",
            'url' => route('documents.show', ['id' => $this->document->id, 'slug' => $this->document->slug]).'#comment-'.$this->commentId,
            'replier_name' => $this->replier->name,
            'document_id' => $this->document->id,
            'comment_id' => $this->commentId,
        ];
    }
}
