<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Auth\Models\User;

class ForumReaction extends Model
{
    protected $table = 'forum_reactions';

    protected $fillable = [
        'user_id',
        'reactable_id',
        'reactable_type',
        'reaction_type',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user who made the reaction
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent reactable model (thread or post)
     */
    public function reactable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Available reaction types
     */
    public const TYPES = [
        'like' => '👍',
        'love' => '❤️',
        'haha' => '😂',
        'wow' => '😮',
        'sad' => '😢',
        'angry' => '😡',
    ];

    /**
     * Get emoji for reaction type
     */
    public function getEmojiAttribute(): string
    {
        return self::TYPES[$this->reaction_type] ?? '👍';
    }
}
