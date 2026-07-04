<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Auth\Models\User;

class ForumPost extends Model
{
    use SoftDeletes;

    protected $table = 'forum_posts';

    protected $fillable = [
        'thread_id',
        'user_id',
        'content',
        'parent_id',
        'is_best_answer',
        'is_pinned',
        'edited_at',
        'mentions',
        'share_token',
    ];

    protected function casts(): array
    {
        return [
            'is_best_answer' => 'boolean',
            'is_pinned' => 'boolean',
            'mentions' => 'array',
            'edited_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function thread(): BelongsTo
    {
        return $this->belongsTo(ForumThread::class, 'thread_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(ForumLike::class, 'likeable_id')->where('likeable_type', static::class);
    }

    public function isLikedBy($userId): bool
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(ForumReaction::class, 'reactable_id')
            ->where('reactable_type', static::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(ForumReport::class, 'reportable_id')
            ->where('reportable_type', static::class);
    }

    public function hasReaction(int $userId, string $type = null): bool
    {
        $query = $this->reactions()->where('user_id', $userId);
        if ($type) {
            $query->where('reaction_type', $type);
        }
        return $query->exists();
    }

    public function getUserReaction(int $userId): ?ForumReaction
    {
        return $this->reactions()->where('user_id', $userId)->first();
    }

    public function getReactionCount(string $type = null): int
    {
        $query = $this->reactions();
        if ($type) {
            $query->where('reaction_type', $type);
        }
        return $query->count();
    }

    public function getReactionsSummary(): array
    {
        $reactions = $this->reactions()
            ->selectRaw('reaction_type, COUNT(*) as count')
            ->groupBy('reaction_type')
            ->pluck('count', 'reaction_type')
            ->toArray();
        
        return $reactions;
    }

    public function isEdited(): bool
    {
        return $this->edited_at !== null;
    }

    public function getMentionedUsers(): array
    {
        return $this->mentions ?? [];
    }
}
