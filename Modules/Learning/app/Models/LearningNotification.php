<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Auth\Models\User;

class LearningNotification extends Model
{
    protected $table = 'learning_notifications';

    protected $fillable = [
        'user_id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'roadmap_id',
        'title',
        'message',
        'data',
        'action_url',
        'action_text',
        'priority',
        'is_read',
        'read_at',
        'sent_at',
        'scheduled_at',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'is_read' => 'boolean',
            'read_at' => 'datetime',
            'sent_at' => 'datetime',
            'scheduled_at' => 'datetime',
        ];
    }

    /**
     * User nhận notification
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Roadmap liên quan
     */
    public function roadmap(): BelongsTo
    {
        return $this->belongsTo(Roadmap::class);
    }

    /**
     * Entity được notify (Assignment, Quiz, etc.)
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Đánh dấu đã đọc
     */
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->is_read = true;
            $this->read_at = now();
            $this->save();
        }
    }

    /**
     * Đánh dấu chưa đọc
     */
    public function markAsUnread(): void
    {
        $this->is_read = false;
        $this->read_at = null;
        $this->save();
    }

    /**
     * Scope - chưa đọc
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope - đã đọc
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope - theo priority
     */
    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope - notifications cần gửi (scheduled)
     */
    public function scopePendingScheduled($query)
    {
        return $query->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->whereNull('sent_at');
    }

    /**
     * Đánh dấu đã gửi
     */
    public function markAsSent(): void
    {
        $this->sent_at = now();
        $this->save();
    }
}
