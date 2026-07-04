<?php

namespace Modules\Learning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Auth\Models\User;

class ForumThread extends Model
{
    use SoftDeletes;

    protected $table = 'forum_threads';

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'threadable_id',
        'threadable_type',
        'is_pinned',
        'is_locked',
        'is_answered',
        'view_count',
        'last_post_at',
        'share_token',
        'comment_sort',
    ];

    protected function casts(): array
    {
        return [
            'is_pinned' => 'boolean',
            'is_locked' => 'boolean',
            'is_answered' => 'boolean',
            'view_count' => 'integer',
            'last_post_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function threadable(): MorphTo
    {
        return $this->morphTo();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(ForumPost::class, 'thread_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(ForumLike::class, 'likeable_id')->where('likeable_type', static::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(ForumBookmark::class, 'thread_id');
    }

    public function isLikedBy($userId): bool
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function isBookmarkedBy($userId): bool
    {
        return $this->bookmarks()->where('user_id', $userId)->exists();
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

    public function getCommentsCount(): int
    {
        return $this->posts()->count();
    }
}
