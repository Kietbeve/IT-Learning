<div class="min-h-screen bg-slate-50 py-8 font-sans">
    <div class="container mx-auto px-4 max-w-7xl">
        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Main --}}
            <div class="flex-1 min-w-0">
                {{-- Header --}}
                <div class="mb-8 relative overflow-hidden rounded-3xl bg-gradient-to-br from-cyan-600 via-blue-600 to-indigo-600 p-8 text-white shadow-xl">
                    <div class="absolute inset-0 bg-white opacity-[0.05]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 24px 24px;"></div>
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <a href="{{ route('learning.roadmaps.index') }}" class="mb-5 inline-flex items-center gap-2 rounded-full bg-white/20 px-4 py-1.5 text-sm font-bold text-white hover:bg-white/30 backdrop-blur-md border border-white/10 transition-all shadow-sm w-max">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Quay lại Lộ trình
                            </a>
                            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight drop-shadow-md">Diễn Đàn Thảo Luận</h1>
                            <p class="mt-3 text-cyan-100 max-w-2xl leading-relaxed text-base">Đặt câu hỏi, chia sẻ kiến thức và thảo luận cùng cộng đồng</p>
                        </div>
                        @auth
                            @if($roadmapFilter)
                                <a href="{{ route('learning.forum.threads.create', ['type' => 'roadmap', 'id' => $roadmapFilter]) }}"
                                   class="inline-flex items-center gap-2 px-6 py-3 bg-white text-blue-600 hover:bg-slate-50 text-sm font-bold rounded-2xl transition-colors shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Tạo câu hỏi
                                </a>
                            @else
                                <span class="text-sm text-cyan-200 font-medium italic bg-black/10 px-4 py-2 rounded-xl backdrop-blur-sm border border-white/10">Chọn lộ trình để đặt câu hỏi</span>
                            @endif
                        @endauth
                    </div>
                </div>

                {{-- Filters --}}
                <div class="mb-6 rounded-3xl bg-white p-6 shadow-sm border border-slate-100">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="sm:col-span-2 relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Tìm kiếm thảo luận..."
                                   class="w-full border-slate-200 rounded-2xl pl-11 pr-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 bg-slate-50/50 hover:bg-slate-50 transition-colors font-medium">
                        </div>
                        <select wire:model.live="roadmapFilter"
                                class="border-slate-200 rounded-2xl px-4 py-3 text-sm bg-slate-50/50 hover:bg-slate-50 focus:border-blue-500 focus:ring-blue-500 transition-colors cursor-pointer font-medium text-slate-700">
                            <option value="">Tất cả lộ trình</option>
                            @foreach($this->roadmaps as $roadmap)
                                <option value="{{ $roadmap->id }}">{{ $roadmap->title }}</option>
                            @endforeach
                        </select>
                        <div class="flex gap-3">
                            <select wire:model.live="sort"
                                    class="flex-1 border-slate-200 rounded-2xl px-4 py-3 text-sm bg-slate-50/50 hover:bg-slate-50 focus:border-blue-500 focus:ring-blue-500 transition-colors cursor-pointer font-medium text-slate-700">
                                <option value="latest">Mới nhất</option>
                                <option value="oldest">Cũ nhất</option>
                                <option value="popular">Xem nhiều</option>
                            </select>
                            <select wire:model.live="statusFilter"
                                    class="flex-1 border-slate-200 rounded-2xl px-4 py-3 text-sm bg-slate-50/50 hover:bg-slate-50 focus:border-blue-500 focus:ring-blue-500 transition-colors cursor-pointer font-medium text-slate-700">
                                <option value="">Tất cả</option>
                                <option value="unanswered">Chưa trả lời</option>
                                <option value="solved">Đã giải đáp</option>
                                <option value="pinned">Đã ghim</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Threads --}}
                <div class="space-y-4">
                @forelse($this->threads as $thread)
                    <div class="group bg-white border border-slate-100 rounded-3xl p-5 hover:shadow-lg hover:-translate-y-1 hover:border-blue-200 transition-all duration-300 {{ $thread->is_pinned ? 'bg-amber-50/30 border-amber-200' : '' }}">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl flex items-center justify-center text-white text-lg font-bold flex-shrink-0 shadow-sm group-hover:scale-110 transition-transform duration-300">
                                {{ strtoupper(substr($thread->user->name ?? '?', 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0 py-1">
                                <div class="flex items-center gap-3 flex-wrap mb-2">
                                    <a href="{{ route('learning.forum.threads.show', $thread->id) }}"
                                       class="text-lg font-extrabold text-slate-800 hover:text-blue-600 truncate transition-colors">
                                        {{ $thread->title }}
                                    </a>
                                    @if($thread->is_pinned)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-amber-700 bg-amber-100 px-2 py-0.5 rounded-lg border border-amber-200">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                                            Ghim
                                        </span>
                                    @endif
                                    @if($thread->is_locked)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-rose-700 bg-rose-100 px-2 py-0.5 rounded-lg border border-rose-200">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                                            Khóa
                                        </span>
                                    @endif
                                    @if($thread->is_answered)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-lg border border-emerald-200">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            Đã giải đáp
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-4 mt-2 text-xs font-semibold text-slate-500 flex-wrap">
                                    <span class="flex items-center gap-1.5 bg-slate-50 px-2 py-1 rounded-lg border border-slate-100">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        {{ $thread->user->name ?? 'Ẩn danh' }}
                                    </span>
                                    <span class="flex items-center gap-1.5 bg-slate-50 px-2 py-1 rounded-lg border border-slate-100">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $thread->created_at->diffForHumans() }}
                                    </span>
                                    <span class="flex items-center gap-1.5 bg-blue-50 px-2 py-1 rounded-lg border border-blue-100 text-blue-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                        {{ $thread->posts_count ?? $thread->posts()->count() }}
                                    </span>
                                    <span class="flex items-center gap-1.5 bg-purple-50 px-2 py-1 rounded-lg border border-purple-100 text-purple-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        {{ $thread->view_count }}
                                    </span>
                                    @if($thread->threadable)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 rounded-full text-slate-600 font-bold border border-slate-200 hover:bg-slate-200 transition-colors cursor-default">
                                            {{ class_basename($thread->threadable_type) === 'Roadmap' ? '📚' : '📖' }}
                                            {{ $thread->threadable->title ?? '' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-1 flex-shrink-0">
                                @auth
                                    <button wire:click="toggleLike({{ $thread->id }})"
                                            class="p-2 rounded-xl hover:bg-rose-50 transition-colors {{ $thread->isLikedBy(auth()->id()) ? 'text-rose-500 bg-rose-50' : 'text-slate-400 bg-slate-50' }} border border-transparent {{ $thread->isLikedBy(auth()->id()) ? 'border-rose-100' : 'border-slate-100' }}"
                                            title="Thích">
                                        <svg class="w-5 h-5 transition-transform hover:scale-110" fill="{{ $thread->isLikedBy(auth()->id()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    </button>
                                @endauth
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 bg-white rounded-3xl border border-slate-100 shadow-sm">
                        <p class="text-5xl mb-4">📭</p>
                        <p class="text-xl text-slate-800 font-extrabold">Chưa có thảo luận nào</p>
                        <p class="text-sm text-slate-500 mt-2 font-medium">Hãy là người đầu tiên tạo thảo luận để chia sẻ kiến thức nhé!</p>
                    </div>
                @endforelse

                {{-- Pagination --}}
                @if($this->threads instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="mt-8">
                        {{ $this->threads->links() }}
                    </div>
                @endif
        </div>

        {{-- Sidebar --}}
        <div class="w-full lg:w-80 flex-shrink-0 space-y-6">
            {{-- Stats --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition-shadow">
                <h3 class="text-sm font-extrabold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="text-lg">📊</span> Thống kê
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gradient-to-br from-cyan-50 to-blue-50 rounded-2xl p-4 text-center border border-cyan-100/50 shadow-sm hover:scale-105 transition-transform">
                        <p class="text-2xl font-black text-cyan-700">{{ number_format($this->stats['threads']) }}</p>
                        <p class="text-[10px] uppercase tracking-wider text-cyan-600 font-bold mt-1">Thảo luận</p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-4 text-center border border-blue-100/50 shadow-sm hover:scale-105 transition-transform">
                        <p class="text-2xl font-black text-blue-700">{{ number_format($this->stats['posts']) }}</p>
                        <p class="text-[10px] uppercase tracking-wider text-blue-600 font-bold mt-1">Trả lời</p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-fuchsia-50 rounded-2xl p-4 text-center border border-purple-100/50 shadow-sm hover:scale-105 transition-transform">
                        <p class="text-2xl font-black text-purple-700">{{ number_format($this->stats['users']) }}</p>
                        <p class="text-[10px] uppercase tracking-wider text-purple-600 font-bold mt-1">Thành viên</p>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-4 text-center border border-emerald-100/50 shadow-sm hover:scale-105 transition-transform">
                        <p class="text-2xl font-black text-emerald-700">{{ number_format($this->stats['solved']) }}</p>
                        <p class="text-[10px] uppercase tracking-wider text-emerald-600 font-bold mt-1">Đã giải đáp</p>
                    </div>
                </div>
            </div>

            {{-- Quick links --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition-shadow">
                <h3 class="text-sm font-extrabold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="text-lg">🔗</span> Liên kết nhanh
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('learning.roadmaps.index') }}"
                       class="flex items-center gap-3 text-sm font-bold text-slate-600 hover:text-blue-600 bg-slate-50 hover:bg-blue-50 p-3 rounded-2xl transition-colors border border-transparent hover:border-blue-100">
                        <span class="text-xl">📚</span> Lộ trình học tập
                    </a>
                    @auth
                        @if($roadmapFilter)
                            <a href="{{ route('learning.forum.threads.create', ['type' => 'roadmap', 'id' => $roadmapFilter]) }}"
                               class="flex items-center gap-3 text-sm font-bold text-slate-600 hover:text-cyan-700 bg-slate-50 hover:bg-cyan-50 p-3 rounded-2xl transition-colors border border-transparent hover:border-cyan-100">
                                <span class="text-xl">✏️</span> Tạo thảo luận mới
                            </a>
                        @else
                            <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 text-center">
                                <span class="text-xs text-slate-400 font-medium italic">Chọn lộ trình trước để tạo bài viết</span>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- Recent roadmaps --}}
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition-shadow">
                <h3 class="text-sm font-extrabold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="text-lg">🗺️</span> Lộ trình
                </h3>
                <div class="space-y-3 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                    @foreach($this->roadmaps as $roadmap)
                        <a href="{{ route('learning.roadmaps.show', $roadmap->id) }}"
                           class="flex items-center gap-3 text-sm font-bold text-slate-600 hover:text-cyan-700 bg-slate-50 hover:bg-cyan-50 p-3 rounded-2xl transition-colors border border-transparent hover:border-cyan-100">
                            <span class="text-xl">📖</span>
                            <span class="truncate">{{ $roadmap->title }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>
