<div class="space-y-4">
    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <h3 class="text-lg font-bold text-gray-900">💬 Thảo luận</h3>
        @auth
            <a href="{{ route('learning.forum.threads.create', ['type' => $threadableType, 'id' => $threadableId]) }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tạo thảo luận mới
            </a>
        @endauth
    </div>

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Tìm kiếm thảo luận..."
                   class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
        </div>
        <select wire:model.live="sort"
                class="border border-gray-300 rounded-xl px-4 py-2 text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 bg-white">
            <option value="latest">Mới nhất</option>
            <option value="oldest">Cũ nhất</option>
            <option value="unanswered">Chưa trả lời</option>
        </select>
    </div>

    {{-- Threads --}}
    <div class="space-y-3">
        @forelse($this->threads as $thread)
            <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-sm transition-shadow">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                        {{ strtoupper(substr($thread->user->name ?? '?', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <a href="{{ route('learning.forum.threads.show', $thread->id) }}"
                               class="text-sm font-bold text-gray-900 hover:text-cyan-700 truncate">
                                {{ $thread->title }}
                            </a>
                            @if($thread->is_pinned)
                                <span class="text-[10px] font-bold text-yellow-700 bg-yellow-100 px-1.5 py-0.5 rounded">Ghim</span>
                            @endif
                            @if($thread->is_locked)
                                <span class="text-[10px] font-bold text-red-700 bg-red-100 px-1.5 py-0.5 rounded">Khóa</span>
                            @endif
                            @if($thread->is_answered)
                                <span class="text-[10px] font-bold text-green-700 bg-green-100 px-1.5 py-0.5 rounded">✓ Đã trả lời</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ strip_tags($thread->content) }}</p>
                        <div class="flex items-center gap-3 mt-2 text-[11px] text-gray-400">
                            <span>{{ $thread->user->name ?? 'Ẩn danh' }}</span>
                            <span>{{ $thread->created_at->diffForHumans() }}</span>
                            <span>{{ $thread->posts_count ?? $thread->posts()->count() }} trả lời</span>
                            <span>{{ $thread->view_count }} lượt xem</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-1 flex-shrink-0">
                        @auth
                            <button wire:click="toggleLike({{ $thread->id }})"
                                    class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors {{ $thread->isLikedBy(auth()->id()) ? 'text-red-500' : 'text-gray-400' }}"
                                    title="Thích">
                                <svg class="w-4 h-4" fill="{{ $thread->isLikedBy(auth()->id()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                            <button wire:click="toggleBookmark({{ $thread->id }})"
                                    class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors {{ $thread->isBookmarkedBy(auth()->id()) ? 'text-yellow-500' : 'text-gray-400' }}"
                                    title="Lưu">
                                <svg class="w-4 h-4" fill="{{ $thread->isBookmarkedBy(auth()->id()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                </svg>
                            </button>
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">
                <p class="text-lg mb-2">📭 Chưa có thảo luận nào</p>
                <p class="text-sm">Hãy là người đầu tiên đặt câu hỏi về bài học này!</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($this->threads instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-4">
            {{ $this->threads->links() }}
        </div>
    @endif
</div>
