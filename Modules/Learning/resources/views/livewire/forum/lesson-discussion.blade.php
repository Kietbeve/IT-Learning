<div class="space-y-4">
    {{-- Flash Messages --}}
    @if(session()->has('message'))
        <div class="p-3 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700 flex items-center justify-between">
            <span>{{ session('message') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">&times;</button>
        </div>
    @endif
    @if(session()->has('error'))
        <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 flex items-center justify-between">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">&times;</button>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <h3 class="text-base font-bold text-gray-800">💬 Thảo luận về bài học</h3>
        @auth
            <button wire:click="toggleCreateForm" 
                    class="inline-flex items-center gap-1.5 px-4 py-2 {{ $showCreateForm ? 'bg-gray-400 hover:bg-gray-500' : 'bg-cyan-600 hover:bg-cyan-700' }} text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $showCreateForm ? 'M6 18L18 6M6 6l12 12' : 'M12 4v16m8-8H4' }}"/>
                </svg>
                {{ $showCreateForm ? 'Hủy' : 'Tạo thảo luận mới' }}
            </button>
        @else
            <a href="{{ route('auth.google.redirect') }}" 
               class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors border border-gray-300">
                Đăng nhập để thảo luận
            </a>
        @endauth
    </div>

    {{-- Create Thread Form --}}
    @if($showCreateForm)
        <div class="bg-gradient-to-br from-cyan-50 to-blue-50 border border-cyan-200 rounded-xl p-4 shadow-sm">
            <h4 class="text-sm font-bold text-gray-800 mb-3">Tạo câu hỏi / thảo luận mới</h4>
            <form wire:submit="createThread" class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tiêu đề</label>
                    <input type="text" wire:model="newThreadTitle" 
                           placeholder="VD: Làm thế nào để..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                    @error('newThreadTitle') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nội dung (hỗ trợ @mention)</label>
                    <textarea wire:model="newThreadContent" rows="4"
                              placeholder="Mô tả chi tiết câu hỏi của bạn... (dùng @username để mention)"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500"></textarea>
                    @error('newThreadContent') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" wire:click="toggleCreateForm"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        Hủy
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
                        Đăng thảo luận
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1">
            <input type="text" wire:model.live.debounce.300ms="search" 
                   placeholder="Tìm kiếm thảo luận..."
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
        </div>
        <select wire:model.live="sort"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 bg-white">
            <option value="latest">Mới nhất</option>
            <option value="oldest">Cũ nhất</option>
            <option value="unanswered">Chưa trả lời</option>
        </select>
    </div>

    {{-- Threads List --}}
    <div class="space-y-3">
        @forelse($this->threads as $thread)
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                {{-- Thread Summary --}}
                <div class="p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                            {{ strtoupper(substr($thread->user->name ?? '?', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            {{-- Title & Badges --}}
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                @if($editingThreadId === $thread->id)
                                    <input type="text" wire:model="editTitle" class="flex-1 border border-cyan-500 rounded px-2 py-1 text-sm font-bold">
                                @else
                                    <button wire:click="toggleExpand({{ $thread->id }})" 
                                            class="text-sm font-bold text-gray-900 hover:text-cyan-700 text-left">
                                        {{ $thread->title }}
                                    </button>
                                @endif
                                @if($thread->is_pinned) <span class="text-[10px] font-bold text-yellow-700 bg-yellow-100 px-1.5 py-0.5 rounded">📌 Ghim</span> @endif
                                @if($thread->is_locked) <span class="text-[10px] font-bold text-red-700 bg-red-100 px-1.5 py-0.5 rounded">🔒 Khóa</span> @endif
                                @if($thread->is_answered) <span class="text-[10px] font-bold text-green-700 bg-green-100 px-1.5 py-0.5 rounded">✓ Đã trả lời</span> @endif
                            </div>

                            {{-- Content Preview or Edit --}}
                            @if($editingThreadId === $thread->id)
                                <textarea wire:model="editContent" rows="3" class="w-full border border-cyan-500 rounded px-2 py-1 text-sm mb-2"></textarea>
                                <div class="flex gap-2">
                                    <button wire:click="saveEditThread" class="px-3 py-1 bg-cyan-600 text-white text-xs rounded">Lưu</button>
                                    <button wire:click="cancelEdit" class="px-3 py-1 border border-gray-300 text-xs rounded">Hủy</button>
                                </div>
                            @else
                                @if($expandedThreadId !== $thread->id)
                                    <p class="text-xs text-gray-500 line-clamp-2 mb-2">{{ strip_tags($thread->content) }}</p>
                                @endif
                            @endif

                            {{-- Metadata --}}
                            <div class="flex items-center flex-wrap gap-3 text-[11px] text-gray-400 mb-2">
                                <span class="font-medium text-gray-600">{{ $thread->user->name }}</span>
                                <span>{{ $thread->created_at->diffForHumans() }}</span>
                                <span>{{ $thread->view_count }} lượt xem</span>
                            </div>
                            
                            {{-- Comment Button - Standalone for better visibility --}}
                            <div class="flex items-center gap-2 mb-2">
                                <button type="button" wire:click="toggleExpand({{ $thread->id }})" 
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-cyan-700 hover:text-cyan-800 hover:bg-cyan-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    {{ $thread->getCommentsCount() }} bình luận
                                </button>
                            </div>
                            
                            {{-- Quick Actions Bar --}}
                            @auth
                                @if($expandedThreadId !== $thread->id)
                                    <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                                        <button type="button" wire:click="toggleExpandAndReply({{ $thread->id }})"
                                                class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-cyan-600 hover:text-cyan-700 hover:bg-cyan-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                            </svg>
                                            Viết bình luận
                                        </button>
                                    </div>
                                @endif
                            @endauth
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-1 flex-shrink-0 relative">
                            @auth
                                {{-- Reaction Button --}}
                                <div class="relative">
                                    <button wire:click="showReactionPicker({{ $thread->id }}, 'thread')"
                                            class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors {{ $thread->hasReaction(auth()->id()) ? 'text-blue-500' : 'text-gray-400' }}">
                                        @php
                                            $userReaction = $thread->getUserReaction(auth()->id());
                                            $emoji = $userReaction ? $userReaction->emoji : '👍';
                                        @endphp
                                        <span class="text-base">{{ $emoji }}</span>
                                    </button>
                                    @php $reactionsSummary = $thread->getReactionsSummary(); @endphp
                                    @if(!empty($reactionsSummary))
                                        <span class="text-xs text-gray-500 ml-1">{{ array_sum($reactionsSummary) }}</span>
                                    @endif

                                    {{-- Reaction Picker --}}
                                    @if($showReactionPickerFor === $thread->id && $reactionPickerType === 'thread')
                                        <div class="absolute top-full left-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg p-2 flex gap-1 z-10">
                                            @foreach(\Modules\Learning\Models\ForumReaction::TYPES as $type => $emoji)
                                                <button wire:click="toggleReaction('thread', {{ $thread->id }}, '{{ $type }}')"
                                                        class="text-xl hover:scale-125 transition-transform p-1">
                                                    {{ $emoji }}
                                                </button>
                                            @endforeach
                                            <button wire:click="hideReactionPicker" class="text-gray-400 hover:text-gray-600 px-2">×</button>
                                        </div>
                                    @endif
                                </div>

                                {{-- Bookmark --}}
                                <button wire:click="toggleBookmark({{ $thread->id }})"
                                        class="p-1.5 rounded-lg hover:bg-gray-100 {{ $thread->isBookmarkedBy(auth()->id()) ? 'text-yellow-500' : 'text-gray-400' }}">
                                    <svg class="w-4 h-4" fill="{{ $thread->isBookmarkedBy(auth()->id()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                    </svg>
                                </button>

                                {{-- Edit --}}
                                @if($thread->user_id === auth()->id() || auth()->user()?->hasRole('admin'))
                                    <button wire:click="startEditThread({{ $thread->id }})"
                                            class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-blue-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                @endif

                                {{-- Report --}}
                                <button wire:click="startReport({{ $thread->id }}, 'thread')"
                                        class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-red-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                                    </svg>
                                </button>

                                {{-- Delete --}}
                                @if($thread->user_id === auth()->id() || auth()->user()?->hasRole('admin'))
                                    <button wire:click="deleteThread({{ $thread->id }})" wire:confirm="Xóa thảo luận này?"
                                            class="p-1.5 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>

                {{-- Expanded Thread Detail --}}
                @if($expandedThreadId === $thread->id)
                    <div class="border-t border-gray-200 bg-gray-50/50">
                        {{-- Thread Full Content --}}
                        <div class="p-4 bg-white border-b border-gray-200">
                            <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{!! nl2br(e($thread->content)) !!}</p>
                            <div class="flex items-center gap-2 mt-3">
                                @auth
                                    @if(!$thread->is_locked)
                                        <button wire:click="startReply({{ $thread->id }})"
                                                class="text-xs font-medium text-cyan-600 hover:text-cyan-700 hover:underline">
                                            💬 Trả lời
                                        </button>
                                    @endif
                                @endauth
                            </div>
                        </div>

                        {{-- Comment Sort --}}
                        <div class="px-4 py-2 bg-gray-100 border-b border-gray-200 flex items-center justify-between">
                            <span class="text-xs font-medium text-gray-600">{{ $thread->posts()->whereNull('parent_id')->count() }} bình luận</span>
                            <select wire:model.live="commentSort" class="text-xs border-gray-300 rounded px-2 py-1">
                                <option value="latest">Mới nhất</option>
                                <option value="oldest">Cũ nhất</option>
                                <option value="popular">Phổ biến nhất</option>
                            </select>
                        </div>

                        {{-- Posts/Comments --}}
                        <div class="p-4 space-y-3">
                            @php $sortedPosts = $this->getSortedPosts($thread->id); @endphp
                            @forelse($sortedPosts as $post)
                                <div class="bg-white border border-gray-100 rounded-lg p-3 {{ $post->is_best_answer ? 'border-green-300 bg-green-50/30' : '' }} {{ $post->is_pinned ? 'border-yellow-300 bg-yellow-50/20' : '' }}">
                                    <div class="flex items-start gap-2">
                                        <div class="w-8 h-8 bg-gradient-to-br from-gray-600 to-gray-800 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                            {{ strtoupper(substr($post->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-2 mb-1">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <span class="text-xs font-bold text-gray-900">{{ $post->user->name }}</span>
                                                    <span class="text-[10px] text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                                                    @if($post->is_best_answer)
                                                        <span class="text-[10px] font-bold text-green-700 bg-green-100 px-1.5 py-0.5 rounded">★ Hay nhất</span>
                                                    @endif
                                                    @if($post->is_pinned)
                                                        <span class="text-[10px] font-bold text-yellow-700 bg-yellow-100 px-1.5 py-0.5 rounded">📌 Ghim</span>
                                                    @endif
                                                    @if($post->isEdited())
                                                        <span class="text-[10px] text-gray-400">(đã chỉnh sửa)</span>
                                                    @endif
                                                </div>

                                                {{-- Post Actions --}}
                                                @auth
                                                    <div class="flex items-center gap-1">
                                                        {{-- Reaction Button --}}
                                                        <div class="relative">
                                                            <button wire:click="showReactionPicker({{ $post->id }}, 'post')"
                                                                    class="p-1 rounded hover:bg-gray-100 {{ $post->hasReaction(auth()->id()) ? 'text-blue-500' : 'text-gray-400' }}">
                                                                @php
                                                                    $userReaction = $post->getUserReaction(auth()->id());
                                                                    $emoji = $userReaction ? $userReaction->emoji : '👍';
                                                                @endphp
                                                                <span class="text-sm">{{ $emoji }}</span>
                                                            </button>
                                                            @php $postReactions = $post->getReactionsSummary(); @endphp
                                                            @if(!empty($postReactions))
                                                                <span class="text-[10px] text-gray-500">{{ array_sum($postReactions) }}</span>
                                                            @endif

                                                            {{-- Reaction Picker for Post --}}
                                                            @if($showReactionPickerFor === $post->id && $reactionPickerType === 'post')
                                                                <div class="absolute top-full left-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg p-1 flex gap-1 z-10">
                                                                    @foreach(\Modules\Learning\Models\ForumReaction::TYPES as $type => $emoji)
                                                                        <button wire:click="toggleReaction('post', {{ $post->id }}, '{{ $type }}')"
                                                                                class="text-base hover:scale-125 transition-transform p-1">
                                                                            {{ $emoji }}
                                                                        </button>
                                                                    @endforeach
                                                                    <button wire:click="hideReactionPicker" class="text-gray-400 hover:text-gray-600 px-1">×</button>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        {{-- Mark Best Answer --}}
                                                        @if(!$post->is_best_answer && ($thread->user_id === auth()->id() || auth()->user()?->hasRole('admin')))
                                                            <button wire:click="markBestAnswer({{ $post->id }})"
                                                                    class="p-1 rounded hover:bg-green-50 text-gray-400 hover:text-green-500" title="Đánh dấu hay nhất">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                            </button>
                                                        @endif

                                                        {{-- Pin Post --}}
                                                        @if($thread->user_id === auth()->id() || auth()->user()?->hasRole('admin'))
                                                            <button wire:click="togglePinPost({{ $post->id }})"
                                                                    class="p-1 rounded hover:bg-yellow-50 text-gray-400 hover:text-yellow-500" title="{{ $post->is_pinned ? 'Bỏ ghim' : 'Ghim' }}">
                                                                <svg class="w-3.5 h-3.5" fill="{{ $post->is_pinned ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                                                </svg>
                                                            </button>
                                                        @endif

                                                        {{-- Edit Post --}}
                                                        @if($post->user_id === auth()->id() || auth()->user()?->hasRole('admin'))
                                                            <button wire:click="startEditPost({{ $post->id }})"
                                                                    class="p-1 rounded hover:bg-gray-100 text-gray-400 hover:text-blue-500">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                                </svg>
                                                            </button>
                                                        @endif

                                                        {{-- Report Post --}}
                                                        <button wire:click="startReport({{ $post->id }}, 'post')"
                                                                class="p-1 rounded hover:bg-gray-100 text-gray-400 hover:text-red-500">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                                                            </svg>
                                                        </button>

                                                        {{-- Delete Post --}}
                                                        @if($post->user_id === auth()->id() || auth()->user()?->hasRole('admin'))
                                                            <button wire:click="deletePost({{ $post->id }})" wire:confirm="Xóa bình luận?"
                                                                    class="p-1 rounded hover:bg-red-50 text-gray-400 hover:text-red-500">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                </svg>
                                                            </button>
                                                        @endif

                                                        {{-- Reply Button --}}
                                                        @if(!$thread->is_locked)
                                                            <button wire:click="startReply({{ $thread->id }}, {{ $post->id }})"
                                                                    class="p-1 rounded hover:bg-gray-100 text-gray-400 hover:text-cyan-600">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                                                </svg>
                                                            </button>
                                                        @endif
                                                    </div>
                                                @endauth
                                            </div>

                                            {{-- Post Content or Edit Form --}}
                                            @if($editingPostId === $post->id)
                                                <textarea wire:model="editContent" rows="3"
                                                          class="w-full border border-cyan-500 rounded px-2 py-1 text-sm mb-2"></textarea>
                                                <div class="flex gap-2">
                                                    <button wire:click="saveEditPost" class="px-3 py-1 bg-cyan-600 text-white text-xs rounded">Lưu</button>
                                                    <button wire:click="cancelEdit" class="px-3 py-1 border border-gray-300 text-xs rounded">Hủy</button>
                                                </div>
                                            @else
                                                <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{!! nl2br(e($post->content)) !!}</p>
                                            @endif

                                            {{-- Nested Replies --}}
                                            @if($post->replies->count() > 0)
                                                <div class="mt-2 ml-4 pl-3 border-l-2 border-gray-200 space-y-2">
                                                    @foreach($post->replies as $reply)
                                                        <div class="bg-gray-50 rounded p-2">
                                                            <div class="flex items-center gap-2 mb-1">
                                                                <span class="text-xs font-bold text-gray-900">{{ $reply->user->name }}</span>
                                                                <span class="text-[10px] text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>
                                                            </div>
                                                            <p class="text-xs text-gray-700">{!! nl2br(e($reply->content)) !!}</p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            {{-- Inline Reply Form --}}
                                            @if($replyToPostId === $post->id && $replyToThreadId === $thread->id && !$thread->is_locked)
                                                <div class="mt-2">
                                                    <form wire:submit="submitReply" class="flex gap-2">
                                                        <input type="text" wire:model="replyContent"
                                                               class="flex-1 border border-gray-300 rounded px-2 py-1.5 text-xs focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500"
                                                               placeholder="Trả lời...">
                                                        <button type="submit" class="px-3 py-1.5 bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-bold rounded">Gửi</button>
                                                        <button type="button" wire:click="cancelReply" class="px-3 py-1.5 border border-gray-300 rounded text-xs text-gray-600 hover:bg-gray-50">Hủy</button>
                                                    </form>
                                                    @error('replyContent') <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p> @enderror
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 text-center py-4">Chưa có trả lời nào. Hãy là người đầu tiên!</p>
                            @endforelse

                            {{-- Main Reply Form --}}
                            @auth
                                @if($replyToThreadId === $thread->id && !$replyToPostId && !$thread->is_locked)
                                    <div class="bg-white border border-gray-200 rounded-lg p-3">
                                        <form wire:submit="submitReply" class="space-y-2">
                                            <textarea wire:model="replyContent" rows="3"
                                                      class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500"
                                                      placeholder="Viết câu trả lời... (dùng @username để mention)"></textarea>
                                            @error('replyContent') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                                            <div class="flex justify-end gap-2">
                                                <button type="button" wire:click="cancelReply" class="px-3 py-1.5 border border-gray-300 rounded text-sm text-gray-600 hover:bg-gray-50">Hủy</button>
                                                <button type="submit" class="px-4 py-1.5 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-bold rounded">Gửi trả lời</button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-8 text-gray-500 bg-white rounded-xl border border-gray-200">
                <p class="text-base mb-1">📭 Chưa có thảo luận nào</p>
                <p class="text-sm">Hãy là người đầu tiên đặt câu hỏi về bài học này!</p>
            </div>
        @endforelse
    </div>

    {{-- Report Modal --}}
    @if($reportingId && $reportingType)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" wire:click.self="cancelReport">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Báo cáo nội dung</h3>
                <form wire:submit="submitReport" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lý do báo cáo</label>
                        <select wire:model="reportReason" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">Chọn lý do...</option>
                            @foreach(\Modules\Learning\Models\ForumReport::REASONS as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('reportReason') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả chi tiết (không bắt buộc)</label>
                        <textarea wire:model="reportDescription" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                  placeholder="Mô tả thêm về vấn đề..."></textarea>
                        @error('reportDescription') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" wire:click="cancelReport"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
                            Hủy
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-lg">
                            Gửi báo cáo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Pagination --}}
    @if($this->threads instanceof \Illuminate\Pagination\LengthAwarePaginator && $this->threads->hasPages())
        <div class="mt-4">
            {{ $this->threads->links() }}
        </div>
    @endif
</div>
