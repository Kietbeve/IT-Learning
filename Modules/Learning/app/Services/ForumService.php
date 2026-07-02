<?php

namespace Modules\Learning\Services;

use Illuminate\Database\Eloquent\Model;
use Modules\Learning\Models\ForumThread;
use Modules\Learning\Models\ForumPost;
use Modules\Learning\Models\ForumLike;
use Modules\Learning\Models\ForumBookmark;
use Modules\Learning\Models\ForumReaction;
use Modules\Learning\Models\ForumReport;

class ForumService
{
    protected ProfanityFilter $profanity;

    public function __construct()
    {
        $this->profanity = app(ProfanityFilter::class);
    }
    public function getThreadsFor(Model $threadable, array $filters = [], int $perPage = 15)
    {
        $query = ForumThread::with('user')
            ->where('threadable_type', get_class($threadable))
            ->where('threadable_id', $threadable->id);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $sort = $filters['sort'] ?? 'latest';
        match ($sort) {
            'oldest' => $query->oldest(),
            'unanswered' => $query->where('is_answered', false)->latest(),
            default => $query->latest(),
        };

        return $query->paginate($perPage);
    }

    public function findThread(int $id): ForumThread
    {
        return ForumThread::with(['user', 'posts.user', 'posts.replies.user'])->findOrFail($id);
    }

    public function createThread(Model $threadable, array $data): ForumThread
    {
        $thread = ForumThread::create([
            'user_id' => auth()->id(),
            'title' => $this->profanity->filter($data['title']),
            'content' => $this->profanity->filter($data['content']),
            'threadable_id' => $threadable->id,
            'threadable_type' => get_class($threadable),
            'last_post_at' => now(),
        ]);

        return $thread;
    }

    public function createReply(ForumThread $thread, array $data, ?int $parentId = null): ForumPost
    {
        $post = ForumPost::create([
            'thread_id' => $thread->id,
            'user_id' => auth()->id(),
            'content' => $this->profanity->filter($data['content']),
            'parent_id' => $parentId,
        ]);

        $thread->update(['last_post_at' => now()]);

        return $post;
    }

    public function toggleLike($likeable, int $userId): bool
    {
        $like = ForumLike::where('user_id', $userId)
            ->where('likeable_id', $likeable->id)
            ->where('likeable_type', get_class($likeable))
            ->first();

        if ($like) {
            $like->delete();
            return false;
        }

        ForumLike::create([
            'user_id' => $userId,
            'likeable_id' => $likeable->id,
            'likeable_type' => get_class($likeable),
        ]);

        return true;
    }

    public function toggleBookmark(ForumThread $thread, int $userId): bool
    {
        $bookmark = ForumBookmark::where('user_id', $userId)
            ->where('thread_id', $thread->id)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            return false;
        }

        ForumBookmark::create([
            'user_id' => $userId,
            'thread_id' => $thread->id,
        ]);

        return true;
    }

    public function markBestAnswer(ForumPost $post): void
    {
        ForumPost::where('thread_id', $post->thread_id)
            ->where('is_best_answer', true)
            ->update(['is_best_answer' => false]);

        $post->update(['is_best_answer' => true]);
        $post->thread->update(['is_answered' => true]);
    }

    public function deleteThread(ForumThread $thread): void
    {
        $thread->posts()->delete();
        $thread->delete();
    }

    public function deletePost(ForumPost $post): void
    {
        $post->replies()->delete();
        $post->delete();
    }

    public function incrementView(ForumThread $thread): void
    {
        $thread->increment('view_count');
    }

    /**
     * Toggle reaction on thread or post
     */
    public function toggleReaction($reactable, int $userId, string $type = 'like'): bool
    {
        $existing = ForumReaction::where('user_id', $userId)
            ->where('reactable_id', $reactable->id)
            ->where('reactable_type', get_class($reactable))
            ->first();

        if ($existing) {
            // If same reaction type, remove it
            if ($existing->reaction_type === $type) {
                $existing->delete();
                return false;
            }
            // If different reaction type, update it
            $existing->update(['reaction_type' => $type]);
            return true;
        }

        // Create new reaction
        ForumReaction::create([
            'user_id' => $userId,
            'reactable_id' => $reactable->id,
            'reactable_type' => get_class($reactable),
            'reaction_type' => $type,
        ]);

        return true;
    }

    /**
     * Get reaction summary for a reactable
     */
    public function getReactionsSummary($reactable): array
    {
        return ForumReaction::where('reactable_id', $reactable->id)
            ->where('reactable_type', get_class($reactable))
            ->selectRaw('reaction_type, COUNT(*) as count')
            ->groupBy('reaction_type')
            ->pluck('count', 'reaction_type')
            ->toArray();
    }

    /**
     * Edit a post
     */
    public function editPost(ForumPost $post, string $content): ForumPost
    {
        $post->update([
            'content' => $this->profanity->filter($content),
            'edited_at' => now(),
        ]);

        return $post->fresh();
    }

    /**
     * Edit a thread
     */
    public function editThread(ForumThread $thread, array $data): ForumThread
    {
        $updateData = [];
        
        if (isset($data['title'])) {
            $updateData['title'] = $this->profanity->filter($data['title']);
        }
        
        if (isset($data['content'])) {
            $updateData['content'] = $this->profanity->filter($data['content']);
        }

        $thread->update($updateData);

        return $thread->fresh();
    }

    /**
     * Pin a post in a thread
     */
    public function pinPost(ForumPost $post): void
    {
        // Unpin all other posts in the same thread
        ForumPost::where('thread_id', $post->thread_id)
            ->where('is_pinned', true)
            ->update(['is_pinned' => false]);

        $post->update(['is_pinned' => true]);
    }

    /**
     * Unpin a post
     */
    public function unpinPost(ForumPost $post): void
    {
        $post->update(['is_pinned' => false]);
    }

    /**
     * Report a thread or post
     */
    public function reportContent($reportable, int $userId, string $reason, ?string $description = null): ForumReport
    {
        return ForumReport::create([
            'user_id' => $userId,
            'reportable_id' => $reportable->id,
            'reportable_type' => get_class($reportable),
            'reason' => $reason,
            'description' => $description,
            'status' => 'pending',
        ]);
    }

    /**
     * Generate unique share token for thread or post
     */
    public function generateShareToken($shareable): string
    {
        $token = \Str::random(32);
        $shareable->update(['share_token' => $token]);
        return $token;
    }

    /**
     * Extract mentioned user IDs from content
     */
    public function extractMentions(string $content): array
    {
        preg_match_all('/@(\w+)/', $content, $matches);
        
        if (empty($matches[1])) {
            return [];
        }

        // Find user IDs by username
        $usernames = $matches[1];
        $users = \Modules\Auth\Models\User::whereIn('name', $usernames)->get(['id', 'name']);
        
        return $users->pluck('id')->toArray();
    }

    /**
     * Get posts sorted by preference
     */
    public function getSortedPosts(ForumThread $thread, string $sort = 'latest')
    {
        $query = $thread->posts()->whereNull('parent_id')->with(['user', 'replies.user']);

        return match ($sort) {
            'oldest' => $query->orderBy('created_at', 'asc')->get(),
            'popular' => $query->withCount('reactions')->orderBy('reactions_count', 'desc')->get(),
            default => $query->orderBy('is_pinned', 'desc')->orderBy('created_at', 'desc')->get(),
        };
    }
}
