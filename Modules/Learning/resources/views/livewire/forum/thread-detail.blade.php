<div class="max-w-4xl mx-auto">
    @php $thread = $this->thread; @endphp

    {{-- Back --}}
    <div class="mb-4">
        <a href="{{ route('learning.forum.threads.index', ['type' => class_basename($thread->threadable_type), 'id' => $thread->threadable_id]) }}"
           class="flex items-center gap-2 text-sm text-gray-600 hover:text-cyan-700 transition-colors">
            ← Quay lại danh sách thảo luận
        </a>
    </div>

    {{-- Flash --}}
    @if(session()->has('message'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700">
            {{ session('message') }}
        </div>
    @endif
    @if(session()->has('error'))
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Thread --}}
    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm mb-4">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                {{ strtoupper(substr($thread->user->name ?? '?', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="text-lg font-bold text-gray-900">{{ $thread->title }}</h1>
                    @if($thread->is_pinned) <span class="text-xs font-bold text-yellow-700 bg-yellow-100 px-2 py-0.5 rounded">Ghim</span> @endif
                    @if($thread->is_locked) <span class="text-xs font-bold text-red-700 bg-red-100 px-2 py-0.5 rounded">Khóa</span> @endif
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-400 mt-1">
                    <span class="font-medium text-gray-600">{{ $thread->user->name }}</span>
                    <span>{{ $thread->created_at->diffForHumans() }}</span>
                    <span>{{ $thread->view_count }} lượt xem</span>
                </div>
            </div>
            @auth
                <div class="flex items-center gap-1 flex-shrink-0">
                    <button wire:click="toggleLike('thread', {{ $thread->id }})"
                            class="p-2 rounded-lg hover:bg-gray-100 transition-colors {{ $thread->isLikedBy(auth()->id()) ? 'text-red-500' : 'text-gray-400' }}">
                        <svg class="w-5 h-5" fill="{{ $thread->isLikedBy(auth()->id()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </button>
                    @if(auth()->user()?->hasRole('admin'))
                        <button wire:click="togglePin"
                                class="p-2 rounded-lg hover:bg-yellow-50 text-gray-400 hover:text-yellow-500 transition-colors"
                                title="{{ $thread->is_pinned ? 'Bỏ ghim' : 'Ghim' }}">
                            <svg class="w-5 h-5" fill="{{ $thread->is_pinned ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                        </button>
                        <button wire:click="toggleLock"
                                class="p-2 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-500 transition-colors"
                                title="{{ $thread->is_locked ? 'Mở khóa' : 'Khóa' }}">
                            <svg class="w-5 h-5" fill="{{ $thread->is_locked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </button>
                    @endif
                    @if($thread->user_id === auth()->id() || auth()->user()?->hasRole('admin'))
                        <button wire:click="deleteThread" wire:confirm="Xóa thảo luận này?"
                                class="p-2 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-500 transition-colors"
                                title="Xóa">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    @endif
                </div>
            @endauth
        </div>
        <div class="mt-4 text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $thread->content }}</div>
    </div>

    {{-- Posts --}}
    <div class="space-y-3 mb-4">
        @forelse($thread->posts->whereNull('parent_id') as $post)
            <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm {{ $post->is_best_answer ? 'border-green-300 bg-green-50/30' : '' }}"
                 id="post-{{ $post->id }}">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-gray-600 to-gray-800 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                        {{ strtoupper(substr($post->user->name ?? '?', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-gray-900">{{ $post->user->name }}</span>
                                <span class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                                @if($post->is_best_answer)
                                    <span class="text-xs font-bold text-green-700 bg-green-100 px-2 py-0.5 rounded">★ Câu trả lời hay nhất</span>
                                @endif
                            </div>
                            @auth
                                <div class="flex items-center gap-1">
                                    <button wire:click="toggleLike('post', {{ $post->id }})"
                                            class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors {{ $post->isLikedBy(auth()->id()) ? 'text-red-500' : 'text-gray-400' }}">
                                        <svg class="w-4 h-4" fill="{{ $post->isLikedBy(auth()->id()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    </button>
                                    @if(!$post->is_best_answer && ($thread->user_id === auth()->id() || auth()->user()?->hasRole('admin')))
                                        <button wire:click="markBestAnswer({{ $post->id }})"
                                                class="p-1.5 rounded-lg hover:bg-green-50 text-gray-400 hover:text-green-500 transition-colors"
                                                title="Đánh dấu câu trả lời hay nhất">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </button>
                                    @endif
                                    @if($post->user_id === auth()->id() || auth()->user()?->hasRole('admin'))
                                        <button wire:click="deletePost({{ $post->id }})" wire:confirm="Xóa bình luận này?"
                                                class="p-1.5 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-500 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @endif
                                    @if(!$thread->is_locked)
                                        <button wire:click="replyTo({{ $post->id }})"
                                                class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-cyan-600 transition-colors"
                                                title="Trả lời">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            @endauth
                        </div>
                        <p class="text-sm text-gray-700 mt-2 leading-relaxed whitespace-pre-wrap">{{ $post->content }}</p>

                        {{-- Nested replies --}}
                        @if($post->replies->count() > 0)
                            <div class="mt-3 ml-4 pl-4 border-l-2 border-gray-100 space-y-2">
                                @foreach($post->replies as $reply)
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-gray-900">{{ $reply->user->name }}</span>
                                            <span class="text-xs text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-gray-700 mt-1">{{ $reply->content }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Inline reply form --}}
                @if($replyToId === $post->id && !$thread->is_locked)
                    <div class="mt-3 ml-12">
                        <form wire:submit="submitReply" class="flex gap-2">
                            <input type="text" wire:model="replyContent"
                                   class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500"
                                   placeholder="Trả lời...">
                            <button type="submit"
                                    class="px-3 py-2 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-bold rounded-lg transition-colors">
                                Gửi
                            </button>
                            <button type="button" wire:click="cancelReply"
                                    class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition-colors">
                                Hủy
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-8 text-gray-500 bg-white rounded-xl border border-gray-200">
                <p>Chưa có câu trả lời nào. Hãy là người đầu tiên trả lời!</p>
            </div>
        @endforelse
    </div>

    {{-- Reply form --}}
    @auth
        @if(!$thread->is_locked)
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <h3 class="text-sm font-bold text-gray-800 mb-3">Viết câu trả lời của bạn</h3>
                <form wire:submit="submitReply">
                    <textarea wire:model="replyContent" rows="3"
                              class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500"
                              placeholder="Nhập nội dung trả lời..."></textarea>
                    @error('replyContent') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    <div class="flex justify-end mt-3">
                        <button type="submit"
                                class="px-5 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                            Gửi trả lời
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4 text-center text-sm text-yellow-700">
                🔒 Chủ đề này đã bị khóa, không thể thêm câu trả lời mới.
            </div>
        @endif
    @else
        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 text-center">
            <p class="text-sm text-gray-600 mb-3">Vui lòng đăng nhập để tham gia thảo luận</p>
            <a href="{{ route('auth.google.redirect') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                Đăng nhập với Google
            </a>
        </div>
    @endauth
</div>
