<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    {{-- Breadcrumb --}}
    <div class="mb-4">
        <a href="{{ route('learning.roadmaps.index') }}"
           class="flex items-center gap-2 text-sm text-gray-600 hover:text-cyan-700 transition-colors">
            ← Quay lại Lộ trình học tập
        </a>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">

        {{-- Main --}}
        <div class="flex-1 min-w-0">
            {{-- Header --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm mb-4">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">💬 Diễn đàn thảo luận</h1>
                        <p class="text-sm text-gray-500 mt-1">Đặt câu hỏi, chia sẻ kiến thức và thảo luận cùng cộng đồng</p>
                    </div>
                    @auth
                        @if($roadmapFilter)
                            <a href="{{ route('learning.forum.threads.create', ['type' => 'roadmap', 'id' => $roadmapFilter]) }}"
                               class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Tạo câu hỏi
                            </a>
                        @else
                            <span class="text-xs text-gray-400 italic">Chọn lộ trình để đặt câu hỏi</span>
                        @endif
                    @endauth
                </div>

                {{-- Filters --}}
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div class="sm:col-span-2">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="🔍 Tìm kiếm thảo luận..."
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                    </div>
                    <select wire:model.live="roadmapFilter"
                            class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm bg-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                        <option value="">Tất cả lộ trình</option>
                        @foreach($this->roadmaps as $roadmap)
                            <option value="{{ $roadmap->id }}">{{ $roadmap->title }}</option>
                        @endforeach
                    </select>
                    <div class="flex gap-2">
                        <select wire:model.live="sort"
                                class="flex-1 border border-gray-300 rounded-xl px-4 py-2.5 text-sm bg-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                            <option value="latest">Mới nhất</option>
                            <option value="oldest">Cũ nhất</option>
                            <option value="popular">Xem nhiều</option>
                        </select>
                        <select wire:model.live="statusFilter"
                                class="flex-1 border border-gray-300 rounded-xl px-4 py-2.5 text-sm bg-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                            <option value="">Tất cả</option>
                            <option value="unanswered">Chưa trả lời</option>
                            <option value="solved">Đã giải đáp</option>
                            <option value="pinned">Đã ghim</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Threads --}}
            <div class="space-y-2">
                @forelse($this->threads as $thread)
                    <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-sm transition-shadow {{ $thread->is_pinned ? 'border-yellow-300 bg-yellow-50/30' : '' }}">
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
                                        <span class="text-[10px] font-bold text-green-700 bg-green-100 px-1.5 py-0.5 rounded">✓ Đã giải đáp</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3 mt-1.5 text-[11px] text-gray-400 flex-wrap">
                                    <span class="font-medium text-gray-500">{{ $thread->user->name ?? 'Ẩn danh' }}</span>
                                    <span>{{ $thread->created_at->diffForHumans() }}</span>
                                    <span class="flex items-center gap-1">💬 {{ $thread->posts_count ?? $thread->posts()->count() }}</span>
                                    <span class="flex items-center gap-1">👁 {{ $thread->view_count }}</span>
                                    @if($thread->threadable)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-100 rounded-full text-gray-500">
                                            {{ class_basename($thread->threadable_type) === 'Roadmap' ? '📚' : '📖' }}
                                            {{ $thread->threadable->title ?? '' }}
                                        </span>
                                    @endif
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
                                @endauth
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white rounded-xl border border-gray-200">
                        <p class="text-3xl mb-3">📭</p>
                        <p class="text-gray-500 font-medium">Chưa có thảo luận nào</p>
                        <p class="text-sm text-gray-400 mt-1">Hãy là người đầu tiên tạo thảo luận!</p>
                    </div>
                @endforelse

                {{-- Pagination --}}
                @if($this->threads instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="mt-4">
                        {{ $this->threads->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="w-full lg:w-72 flex-shrink-0 space-y-4">
            {{-- Stats --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <h3 class="text-sm font-bold text-gray-800 mb-3">📊 Thống kê</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-cyan-50 rounded-xl p-3 text-center">
                        <p class="text-xl font-bold text-cyan-700">{{ number_format($this->stats['threads']) }}</p>
                        <p class="text-[10px] text-cyan-600 font-medium">Thảo luận</p>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-3 text-center">
                        <p class="text-xl font-bold text-blue-700">{{ number_format($this->stats['posts']) }}</p>
                        <p class="text-[10px] text-blue-600 font-medium">Trả lời</p>
                    </div>
                    <div class="bg-purple-50 rounded-xl p-3 text-center">
                        <p class="text-xl font-bold text-purple-700">{{ number_format($this->stats['users']) }}</p>
                        <p class="text-[10px] text-purple-600 font-medium">Thành viên</p>
                    </div>
                    <div class="bg-green-50 rounded-xl p-3 text-center">
                        <p class="text-xl font-bold text-green-700">{{ number_format($this->stats['solved']) }}</p>
                        <p class="text-[10px] text-green-600 font-medium">Đã giải đáp</p>
                    </div>
                </div>
            </div>

            {{-- Quick links --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <h3 class="text-sm font-bold text-gray-800 mb-3">🔗 Liên kết nhanh</h3>
                <div class="space-y-2">
                    <a href="{{ route('learning.roadmaps.index') }}"
                       class="flex items-center gap-2 text-sm text-gray-600 hover:text-cyan-700 transition-colors">
                        📚 Lộ trình học tập
                    </a>
                    @auth
                        @if($roadmapFilter)
                            <a href="{{ route('learning.forum.threads.create', ['type' => 'roadmap', 'id' => $roadmapFilter]) }}"
                               class="flex items-center gap-2 text-sm text-gray-600 hover:text-cyan-700 transition-colors">
                                ✏️ Tạo thảo luận mới
                            </a>
                        @else
                            <span class="text-xs text-gray-400 italic">Chọn lộ trình trước</span>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- Recent roadmaps --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <h3 class="text-sm font-bold text-gray-800 mb-3">📚 Lộ trình</h3>
                <div class="space-y-2 max-h-60 overflow-y-auto">
                    @foreach($this->roadmaps as $roadmap)
                        <a href="{{ route('learning.roadmaps.show', $roadmap->id) }}"
                           class="block text-sm text-gray-600 hover:text-cyan-700 transition-colors truncate">
                            📖 {{ $roadmap->title }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>
