<div class="space-y-6">
    {{-- Flash Messages --}}
    @if(session()->has('message'))
        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-sm text-emerald-800 flex items-center justify-between shadow-sm">
            <span class="font-medium">{{ session('message') }}</span>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif
    @if(session()->has('error'))
        <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl text-sm text-rose-800 flex items-center justify-between shadow-sm">
            <span class="font-medium">{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-cyan-50 text-cyan-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Thảo luận bài học</h3>
        </div>
        @auth
            <button wire:click="toggleCreateForm" 
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 {{ $showCreateForm ? 'bg-slate-200 hover:bg-slate-300 text-slate-700' : 'bg-cyan-600 hover:bg-cyan-700 text-white' }} text-sm font-bold rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $showCreateForm ? 'M6 18L18 6M6 6l12 12' : 'M12 4v16m8-8H4' }}"/>
                </svg>
                {{ $showCreateForm ? 'Hủy viết' : 'Tạo câu hỏi mới' }}
            </button>
        @else
            <a href="{{ route('auth.google.redirect') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition-all duration-200">
                Đăng nhập để thảo luận
            </a>
        @endauth
    </div>

    {{-- Create Thread Form --}}
    @if($showCreateForm)
        <div class="bg-white border border-cyan-100 rounded-2xl p-5 sm:p-6 shadow-md ring-4 ring-cyan-50/50 transition-all">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name ?? '?', 0, 1)) }}
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-800">Đặt câu hỏi mới</h4>
                    <p class="text-xs text-slate-500">Hãy miêu tả chi tiết vấn đề của bạn</p>
                </div>
            </div>
            
            <form wire:submit="createThread" class="space-y-4">
                <div>
                    <input type="text" wire:model="newThreadTitle" 
                           placeholder="Tiêu đề câu hỏi (VD: Làm thế nào để setup môi trường?)"
                           class="w-full bg-slate-50 border border-transparent rounded-xl px-4 py-3 text-sm font-medium focus:bg-white focus:border-cyan-300 focus:ring-4 focus:ring-cyan-500/10 transition-all placeholder:font-normal">
                    @error('newThreadTitle') <p class="text-xs text-rose-500 mt-1.5 ml-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <textarea wire:model="newThreadContent" rows="4"
                              placeholder="Mô tả chi tiết câu hỏi của bạn..."
                              class="w-full bg-slate-50 border border-transparent rounded-xl px-4 py-3 text-sm focus:bg-white focus:border-cyan-300 focus:ring-4 focus:ring-cyan-500/10 transition-all leading-relaxed"></textarea>
                    @error('newThreadContent') <p class="text-xs text-rose-500 mt-1.5 ml-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" wire:click="toggleCreateForm"
                            class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                        Hủy bỏ
                    </button>
                    <button type="submit"
                            class="px-6 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">
                        Đăng câu hỏi
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row gap-4 bg-slate-50 p-2 rounded-2xl">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" 
                   placeholder="Tìm kiếm trong thảo luận..."
                   class="w-full border-none bg-white rounded-xl pl-10 pr-4 py-2.5 text-sm shadow-sm focus:ring-2 focus:ring-cyan-500/20 transition-shadow">
        </div>
        <select wire:model.live="sort"
                class="border-none bg-white rounded-xl px-4 py-2.5 text-sm shadow-sm focus:ring-2 focus:ring-cyan-500/20 font-medium text-slate-700 cursor-pointer min-w-[140px]">
            <option value="latest">Mới nhất</option>
            <option value="oldest">Cũ nhất</option>
            <option value="unanswered">Chưa trả lời</option>
        </select>
    </div>

    {{-- Threads List --}}
    <div class="space-y-4">
        @forelse($this->threads as $thread)
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden ring-1 ring-slate-100 relative group">
                {{-- Thread Summary --}}
                <div class="p-5 sm:p-6 {{ $expandedThreadId === $thread->id ? 'bg-slate-50/50 border-b border-slate-100' : '' }} transition-colors">
                    <div class="flex items-start gap-4">
                        {{-- Avatar --}}
                        <div class="relative flex-shrink-0">
                            <div class="w-11 h-11 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-full flex items-center justify-center text-white text-base font-bold shadow-sm ring-4 ring-cyan-50">
                                {{ strtoupper(substr($thread->user->name ?? '?', 0, 1)) }}
                            </div>
                            @if($thread->user?->hasRole('admin'))
                                <div class="absolute -bottom-1 -right-1 bg-amber-500 rounded-full p-0.5 ring-2 ring-white" title="Admin">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            {{-- Metadata Row --}}
                            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium mb-1.5 flex-wrap">
                                <span class="text-slate-800 font-bold">{{ $thread->user->name }}</span>
                                <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                <span>{{ $thread->created_at->diffForHumans() }}</span>
                                <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>{{ $thread->view_count }}</span>
                            </div>

                            {{-- Title & Badges --}}
                            <div class="flex items-center gap-2 flex-wrap mb-2">
                                @if($editingThreadId === $thread->id)
                                    <input type="text" wire:model="editTitle" class="flex-1 bg-white border border-cyan-300 focus:ring-2 focus:ring-cyan-500/20 rounded-lg px-3 py-1.5 text-sm font-bold shadow-sm">
                                @else
                                    <button wire:click="toggleExpand({{ $thread->id }})" 
                                            class="text-base font-bold text-slate-900 hover:text-cyan-600 text-left transition-colors">
                                        {{ $thread->title }}
                                    </button>
                                @endif
                                
                                @if($thread->is_pinned) <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">📌 Ghim</span> @endif
                                @if($thread->is_locked) <span class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-200">🔒 Khóa</span> @endif
                                @if($thread->is_answered) <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">✓ Đã giải quyết</span> @endif
                            </div>

                            {{-- Content Preview or Edit --}}
                            @if($editingThreadId === $thread->id)
                                <textarea wire:model="editContent" rows="4" class="w-full bg-white border border-cyan-300 focus:ring-2 focus:ring-cyan-500/20 rounded-lg px-3 py-2 text-sm mb-3 shadow-sm"></textarea>
                                <div class="flex gap-2">
                                    <button wire:click="cancelEdit" class="px-4 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">Hủy</button>
                                    <button wire:click="saveEditThread" class="px-4 py-1.5 bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm">Lưu thay đổi</button>
                                </div>
                            @else
                                @if($expandedThreadId !== $thread->id)
                                    <p class="text-sm text-slate-500 line-clamp-2 mb-3 leading-relaxed">{{ strip_tags($thread->content) }}</p>
                                    
                                    {{-- Bottom Actions (Compact state) --}}
                                    <div class="flex items-center justify-between mt-4">
                                        <button type="button" wire:click="toggleExpand({{ $thread->id }})" 
                                                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-50 hover:bg-cyan-50 text-slate-600 hover:text-cyan-700 text-sm font-semibold rounded-xl transition-colors ring-1 ring-slate-200 hover:ring-cyan-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                            </svg>
                                            {{ $thread->getCommentsCount() }} Bình luận
                                        </button>
                                        
                                        {{-- Reactions Summary (Compact) --}}
                                        @php $reactionsSummary = $thread->getReactionsSummary(); @endphp
                                        @if(!empty($reactionsSummary))
                                            <div class="flex items-center gap-1.5 px-3 py-1 bg-slate-50 rounded-full border border-slate-100">
                                                @foreach(array_slice($reactionsSummary, 0, 3, true) as $type => $count)
                                                    <span class="text-sm" title="{{ \Modules\Learning\Models\ForumReaction::TYPES[$type] }}">{{ \Modules\Learning\Models\ForumReaction::TYPES[$type] }}</span>
                                                @endforeach
                                                <span class="text-xs font-semibold text-slate-500 ml-1">{{ array_sum($reactionsSummary) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    {{-- Expanded state full content --}}
                                    <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap mt-2">{!! nl2br(e($thread->content)) !!}</div>
                                @endif
                            @endif
                        </div>

                        {{-- Right Actions Menu (Desktop) --}}
                        <div class="hidden sm:flex items-center gap-1 flex-shrink-0 absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                            @auth
                                {{-- Bookmark --}}
                                <button wire:click="toggleBookmark({{ $thread->id }})"
                                        class="p-2 rounded-full hover:bg-slate-100 transition-colors {{ $thread->isBookmarkedBy(auth()->id()) ? 'text-amber-500 bg-amber-50' : 'text-slate-400 hover:text-slate-600' }}" title="Lưu">
                                    <svg class="w-4 h-4" fill="{{ $thread->isBookmarkedBy(auth()->id()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                </button>

                                {{-- Edit --}}
                                @if($thread->user_id === auth()->id() || auth()->user()?->hasRole('admin'))
                                    <button wire:click="startEditThread({{ $thread->id }})"
                                            class="p-2 rounded-full hover:bg-slate-100 transition-colors text-slate-400 hover:text-cyan-600" title="Chỉnh sửa">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                @endif

                                {{-- Report --}}
                                <button wire:click="startReport({{ $thread->id }}, 'thread')"
                                        class="p-2 rounded-full hover:bg-rose-50 transition-colors text-slate-400 hover:text-rose-500" title="Báo cáo">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                                </button>

                                {{-- Delete --}}
                                @if($thread->user_id === auth()->id() || auth()->user()?->hasRole('admin'))
                                    <button wire:click="deleteThread({{ $thread->id }})" wire:confirm="Xóa thảo luận này?"
                                            class="p-2 rounded-full hover:bg-rose-50 transition-colors text-slate-400 hover:text-rose-600" title="Xóa">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                @endif
                            @endauth
                        </div>
                    </div>

                    {{-- Action Bar under expanded thread --}}
                    @if($expandedThreadId === $thread->id)
                        <div class="flex items-center justify-between mt-5 pt-4 border-t border-slate-100">
                            <div class="flex items-center gap-2">
                                @auth
                                    {{-- Reaction Button --}}
                                    <div class="relative">
                                        <button wire:click="showReactionPicker({{ $thread->id }}, 'thread')"
                                                class="flex items-center gap-1.5 px-4 py-2 rounded-xl transition-colors font-medium text-sm {{ $thread->hasReaction(auth()->id()) ? 'bg-cyan-50 text-cyan-700 ring-1 ring-cyan-200' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 ring-1 ring-slate-200' }}">
                                            @php
                                                $userReaction = $thread->getUserReaction(auth()->id());
                                                $emoji = $userReaction ? $userReaction->emoji : '👍';
                                            @endphp
                                            <span>{{ $emoji }}</span>
                                            @if($thread->hasReaction(auth()->id())) Thích @else Thích @endif
                                        </button>
                                        
                                        {{-- Reaction Picker --}}
                                        @if($showReactionPickerFor === $thread->id && $reactionPickerType === 'thread')
                                            <div class="absolute bottom-full left-0 mb-2 bg-white border border-slate-200 rounded-2xl shadow-xl p-2 flex gap-1 z-20 origin-bottom-left animate-in fade-in zoom-in duration-200">
                                                @foreach(\Modules\Learning\Models\ForumReaction::TYPES as $type => $emoji)
                                                    <button wire:click="toggleReaction('thread', {{ $thread->id }}, '{{ $type }}')"
                                                            class="text-2xl hover:scale-125 transition-transform p-2 hover:bg-slate-50 rounded-xl">
                                                        {{ $emoji }}
                                                    </button>
                                                @endforeach
                                                <button wire:click="hideReactionPicker" class="text-slate-400 hover:text-slate-600 px-3 hover:bg-slate-50 rounded-xl">×</button>
                                            </div>
                                        @endif
                                    </div>

                                    @if(!$thread->is_locked)
                                        <button wire:click="startReply({{ $thread->id }})"
                                                class="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-600 transition-colors font-medium text-sm ring-1 ring-slate-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                            Bình luận
                                        </button>
                                    @endif
                                @endauth
                                
                                @php $reactionsSummary = $thread->getReactionsSummary(); @endphp
                                @if(!empty($reactionsSummary))
                                    <div class="flex items-center gap-1 ml-2">
                                        @foreach(array_slice($reactionsSummary, 0, 3, true) as $type => $count)
                                            <span class="text-lg -ml-1 first:ml-0 drop-shadow-sm ring-2 ring-white rounded-full bg-white" title="{{ \Modules\Learning\Models\ForumReaction::TYPES[$type] }}">{{ \Modules\Learning\Models\ForumReaction::TYPES[$type] }}</span>
                                        @endforeach
                                        <span class="text-xs font-semibold text-slate-500 ml-2 hover:underline cursor-pointer">{{ array_sum($reactionsSummary) }} cảm xúc</span>
                                    </div>
                                @endif
                            </div>
                            
                            {{-- Mobile Actions Menu --}}
                            <div class="sm:hidden flex items-center gap-1">
                                @auth
                                    <button wire:click="toggleBookmark({{ $thread->id }})" class="p-2 rounded-full {{ $thread->isBookmarkedBy(auth()->id()) ? 'text-amber-500' : 'text-slate-400' }}"><svg class="w-5 h-5" fill="{{ $thread->isBookmarkedBy(auth()->id()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg></button>
                                @endauth
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Expanded Thread Comments --}}
                @if($expandedThreadId === $thread->id)
                    <div class="bg-slate-50 border-t border-slate-100">
                        {{-- Comments Header --}}
                        <div class="px-5 sm:px-6 py-4 flex items-center justify-between">
                            <span class="text-sm font-bold text-slate-800">{{ $thread->posts()->whereNull('parent_id')->count() }} Bình luận</span>
                            <div class="flex items-center gap-2">
                                <label class="text-xs font-medium text-slate-500 hidden sm:block">Sắp xếp theo:</label>
                                <select wire:model.live="commentSort" class="text-sm border-none bg-white rounded-xl px-3 py-1.5 shadow-sm focus:ring-2 focus:ring-cyan-500/20 text-slate-700 font-medium cursor-pointer">
                                    <option value="latest">Mới nhất</option>
                                    <option value="oldest">Cũ nhất</option>
                                    <option value="popular">Phổ biến nhất</option>
                                </select>
                            </div>
                        </div>

                        {{-- Posts/Comments --}}
                        <div class="px-5 sm:px-6 pb-6 space-y-4">
                            @php $sortedPosts = $this->getSortedPosts($thread->id); @endphp
                            @forelse($sortedPosts as $post)
                                <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm ring-1 ring-slate-100 {{ $post->is_best_answer ? 'ring-emerald-400/50 bg-emerald-50/20' : '' }} {{ $post->is_pinned ? 'ring-amber-300/50 bg-amber-50/20' : '' }} group/post transition-all">
                                    <div class="flex items-start gap-3 sm:gap-4">
                                        <div class="w-9 h-9 bg-gradient-to-br from-slate-600 to-slate-800 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 shadow-sm ring-2 ring-slate-100 mt-1">
                                            {{ strtoupper(substr($post->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start sm:items-center justify-between gap-2 mb-2 flex-col sm:flex-row">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <span class="text-sm font-bold text-slate-900">{{ $post->user->name }}</span>
                                                    <span class="text-[11px] font-medium text-slate-400">{{ $post->created_at->diffForHumans() }}</span>
                                                    @if($post->is_best_answer)
                                                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full border border-emerald-200 shadow-sm">★ Hay nhất</span>
                                                    @endif
                                                    @if($post->is_pinned)
                                                        <span class="text-[10px] font-bold text-amber-700 bg-amber-100 px-2 py-0.5 rounded-full border border-amber-200 shadow-sm">📌 Ghim</span>
                                                    @endif
                                                    @if($post->isEdited())
                                                        <span class="text-[10px] italic text-slate-400">(đã chỉnh sửa)</span>
                                                    @endif
                                                </div>

                                                {{-- Post Actions Mini Menu --}}
                                                @auth
                                                    <div class="flex items-center gap-0.5 opacity-0 group-hover/post:opacity-100 transition-opacity bg-slate-50 rounded-xl px-1 border border-slate-100 shadow-sm">
                                                        {{-- Mark Best Answer --}}
                                                        @if(!$post->is_best_answer && ($thread->user_id === auth()->id() || auth()->user()?->hasRole('admin')))
                                                            <button wire:click="markBestAnswer({{ $post->id }})" class="p-1.5 rounded-lg hover:bg-white hover:shadow-sm text-slate-400 hover:text-emerald-500 transition-all" title="Đánh dấu hay nhất"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button>
                                                        @endif
                                                        {{-- Pin Post --}}
                                                        @if($thread->user_id === auth()->id() || auth()->user()?->hasRole('admin'))
                                                            <button wire:click="togglePinPost({{ $post->id }})" class="p-1.5 rounded-lg hover:bg-white hover:shadow-sm text-slate-400 hover:text-amber-500 transition-all" title="{{ $post->is_pinned ? 'Bỏ ghim' : 'Ghim' }}"><svg class="w-4 h-4" fill="{{ $post->is_pinned ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg></button>
                                                        @endif
                                                        {{-- Edit Post --}}
                                                        @if($post->user_id === auth()->id() || auth()->user()?->hasRole('admin'))
                                                            <button wire:click="startEditPost({{ $post->id }})" class="p-1.5 rounded-lg hover:bg-white hover:shadow-sm text-slate-400 hover:text-cyan-600 transition-all" title="Sửa"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                                        @endif
                                                        {{-- Report Post --}}
                                                        <button wire:click="startReport({{ $post->id }}, 'post')" class="p-1.5 rounded-lg hover:bg-white hover:shadow-sm text-slate-400 hover:text-rose-500 transition-all" title="Báo cáo"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg></button>
                                                        {{-- Delete Post --}}
                                                        @if($post->user_id === auth()->id() || auth()->user()?->hasRole('admin'))
                                                            <button wire:click="deletePost({{ $post->id }})" wire:confirm="Xóa bình luận?" class="p-1.5 rounded-lg hover:bg-white hover:shadow-sm text-slate-400 hover:text-rose-600 transition-all" title="Xóa"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                                        @endif
                                                    </div>
                                                @endauth
                                            </div>

                                            {{-- Post Content or Edit Form --}}
                                            @if($editingPostId === $post->id)
                                                <textarea wire:model="editContent" rows="3"
                                                          class="w-full bg-slate-50 border border-transparent rounded-xl px-3 py-2 text-sm mb-2 focus:bg-white focus:border-cyan-300 focus:ring-4 focus:ring-cyan-500/10 transition-all"></textarea>
                                                <div class="flex gap-2">
                                                    <button wire:click="cancelEdit" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">Hủy</button>
                                                    <button wire:click="saveEditPost" class="px-4 py-1.5 bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">Lưu</button>
                                                </div>
                                            @else
                                                <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap">{!! nl2br(e($post->content)) !!}</div>
                                            @endif

                                            {{-- Post Interactions (Like, Reply) --}}
                                            <div class="flex items-center gap-4 mt-3">
                                                @auth
                                                    <div class="relative flex items-center">
                                                        <button wire:click="showReactionPicker({{ $post->id }}, 'post')"
                                                                class="flex items-center gap-1.5 text-xs font-semibold hover:text-slate-800 transition-colors {{ $post->hasReaction(auth()->id()) ? 'text-cyan-600' : 'text-slate-500' }}">
                                                            @php
                                                                $userReaction = $post->getUserReaction(auth()->id());
                                                                $emoji = $userReaction ? $userReaction->emoji : '👍';
                                                            @endphp
                                                            <span class="text-sm bg-slate-100 rounded-full w-6 h-6 flex items-center justify-center">{{ $emoji }}</span>
                                                            @if($post->hasReaction(auth()->id())) Đã thích @else Thích @endif
                                                        </button>
                                                        @php $postReactions = $post->getReactionsSummary(); @endphp
                                                        @if(!empty($postReactions))
                                                            <span class="text-[11px] font-bold text-slate-500 ml-1.5 bg-slate-100 px-1.5 py-0.5 rounded-full">{{ array_sum($postReactions) }}</span>
                                                        @endif

                                                        @if($showReactionPickerFor === $post->id && $reactionPickerType === 'post')
                                                            <div class="absolute bottom-full left-0 mb-1.5 bg-white border border-slate-200 rounded-2xl shadow-xl p-1.5 flex gap-1 z-20 origin-bottom-left animate-in fade-in zoom-in duration-200">
                                                                @foreach(\Modules\Learning\Models\ForumReaction::TYPES as $type => $emoji)
                                                                    <button wire:click="toggleReaction('post', {{ $post->id }}, '{{ $type }}')"
                                                                            class="text-xl hover:scale-125 transition-transform p-1.5 hover:bg-slate-50 rounded-xl">
                                                                        {{ $emoji }}
                                                                    </button>
                                                                @endforeach
                                                                <button wire:click="hideReactionPicker" class="text-slate-400 hover:text-slate-600 px-2 hover:bg-slate-50 rounded-xl">×</button>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    @if(!$thread->is_locked)
                                                        <button wire:click="startReply({{ $thread->id }}, {{ $post->id }})"
                                                                class="flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-cyan-600 transition-colors">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                                            Trả lời
                                                        </button>
                                                    @endif
                                                @endauth
                                            </div>

                                            {{-- Nested Replies --}}
                                            @if($post->replies->count() > 0)
                                                <div class="mt-4 space-y-3">
                                                    @foreach($post->replies as $reply)
                                                        <div class="flex gap-3 relative">
                                                            {{-- Connector line --}}
                                                            <div class="absolute top-0 bottom-0 left-3 w-px bg-slate-200 -z-10"></div>
                                                            <div class="absolute top-4 left-3 w-4 h-px bg-slate-200 -z-10"></div>
                                                            
                                                            <div class="w-6 h-6 bg-slate-200 rounded-full flex items-center justify-center text-slate-600 text-[10px] font-bold flex-shrink-0 border-2 border-white relative z-0 mt-1.5">
                                                                {{ strtoupper(substr($reply->user->name ?? '?', 0, 1)) }}
                                                            </div>
                                                            <div class="flex-1 bg-slate-50/80 rounded-2xl p-3 border border-slate-100">
                                                                <div class="flex items-center gap-2 mb-1">
                                                                    <span class="text-xs font-bold text-slate-800">{{ $reply->user->name }}</span>
                                                                    <span class="text-[10px] font-medium text-slate-400">{{ $reply->created_at->diffForHumans() }}</span>
                                                                </div>
                                                                <div class="text-xs text-slate-700 leading-relaxed">{!! nl2br(e($reply->content)) !!}</div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            {{-- Inline Reply Form --}}
                                            @if($replyToPostId === $post->id && $replyToThreadId === $thread->id && !$thread->is_locked)
                                                <div class="mt-4 flex gap-3">
                                                    <div class="w-6 h-6 bg-cyan-100 rounded-full flex items-center justify-center text-cyan-700 text-[10px] font-bold flex-shrink-0 mt-1 border-2 border-white">
                                                        {{ strtoupper(substr(auth()->user()->name ?? '?', 0, 1)) }}
                                                    </div>
                                                    <div class="flex-1">
                                                        <form wire:submit="submitReply" class="flex flex-col sm:flex-row gap-2">
                                                            <input type="text" wire:model="replyContent"
                                                                   class="flex-1 bg-slate-50 border border-transparent rounded-xl px-3 py-2 text-sm focus:bg-white focus:border-cyan-300 focus:ring-4 focus:ring-cyan-500/10 transition-all placeholder:text-slate-400"
                                                                   placeholder="Viết trả lời...">
                                                            <div class="flex gap-2 justify-end">
                                                                <button type="button" wire:click="cancelReply" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition-colors">Hủy</button>
                                                                <button type="submit" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-xl text-xs font-bold shadow-sm transition-colors">Gửi</button>
                                                            </div>
                                                        </form>
                                                        @error('replyContent') <p class="text-[10px] text-rose-500 mt-1.5 ml-1">{{ $message }}</p> @enderror
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="py-8 text-center bg-white rounded-2xl border border-dashed border-slate-200">
                                    <div class="inline-flex items-center justify-center w-12 h-12 bg-slate-50 rounded-full mb-3">
                                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    </div>
                                    <p class="text-sm font-medium text-slate-800 mb-1">Chưa có bình luận nào</p>
                                    <p class="text-xs text-slate-500">Hãy là người đầu tiên đóng góp ý kiến!</p>
                                </div>
                            @endforelse

                            {{-- Main Reply Form --}}
                            @auth
                                @if($replyToThreadId === $thread->id && !$replyToPostId && !$thread->is_locked)
                                    <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm ring-1 ring-cyan-100/50 relative overflow-hidden mt-6">
                                        <div class="absolute top-0 left-0 w-1 h-full bg-cyan-500"></div>
                                        <div class="flex items-center gap-3 mb-3">
                                            <div class="w-8 h-8 bg-cyan-100 rounded-full flex items-center justify-center text-cyan-700 text-xs font-bold">
                                                {{ strtoupper(substr(auth()->user()->name ?? '?', 0, 1)) }}
                                            </div>
                                            <span class="text-sm font-bold text-slate-800">Trả lời câu hỏi</span>
                                        </div>
                                        <form wire:submit="submitReply" class="space-y-3">
                                            <textarea wire:model="replyContent" rows="3"
                                                      class="w-full bg-slate-50 border border-transparent rounded-xl px-4 py-3 text-sm focus:bg-white focus:border-cyan-300 focus:ring-4 focus:ring-cyan-500/10 transition-all leading-relaxed placeholder:text-slate-400"
                                                      placeholder="Viết câu trả lời..."></textarea>
                                            @error('replyContent') <p class="text-xs text-rose-500 ml-1">{{ $message }}</p> @enderror
                                            <div class="flex justify-end gap-2 pt-1">
                                                <button type="button" wire:click="cancelReply" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition-colors">Hủy</button>
                                                <button type="submit" class="px-5 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-xl text-sm font-bold shadow-sm transition-all hover:shadow-md hover:-translate-y-0.5">Gửi trả lời</button>
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
            <div class="flex flex-col items-center justify-center py-16 px-4 bg-white rounded-3xl border border-slate-100 shadow-sm text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                </div>
                <h4 class="text-base font-bold text-slate-800 mb-1">Chưa có chủ đề nào</h4>
                <p class="text-sm text-slate-500 mb-6">Hãy là người đầu tiên đặt câu hỏi về bài học này!</p>
                @auth
                    <button wire:click="toggleCreateForm" class="px-6 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all hover:shadow-md hover:-translate-y-0.5">
                        Tạo câu hỏi đầu tiên
                    </button>
                @endauth
            </div>
        @endforelse
    </div>

    {{-- Report Modal --}}
    @if($reportingId && $reportingType)
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center z-50 p-4 transition-opacity" wire:click.self="cancelReport">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 sm:p-8 animate-in zoom-in-95 duration-200">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-900">Báo cáo nội dung</h3>
                    <button wire:click="cancelReport" class="text-slate-400 hover:text-slate-600 p-2 rounded-full hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form wire:submit="submitReport" class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Lý do báo cáo</label>
                        <select wire:model="reportReason" class="w-full bg-slate-50 border border-transparent focus:bg-white focus:border-cyan-300 focus:ring-4 focus:ring-cyan-500/10 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 transition-all cursor-pointer">
                            <option value="">Chọn lý do phù hợp...</option>
                            @foreach(\Modules\Learning\Models\ForumReport::REASONS as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('reportReason') <p class="text-xs text-rose-500 mt-1.5 ml-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Mô tả thêm <span class="text-slate-400 font-normal">(không bắt buộc)</span></label>
                        <textarea wire:model="reportDescription" rows="3"
                                  class="w-full bg-slate-50 border border-transparent focus:bg-white focus:border-cyan-300 focus:ring-4 focus:ring-cyan-500/10 rounded-xl px-4 py-3 text-sm transition-all"
                                  placeholder="Chi tiết vấn đề..."></textarea>
                        @error('reportDescription') <p class="text-xs text-rose-500 mt-1.5 ml-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="cancelReport"
                                class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-bold transition-colors">
                            Hủy bỏ
                        </button>
                        <button type="submit"
                                class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold rounded-xl shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
                            Gửi báo cáo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Pagination --}}
    @if($this->threads instanceof \Illuminate\Pagination\LengthAwarePaginator && $this->threads->hasPages())
        <div class="mt-6 pt-4 flex justify-center">
            {{ $this->threads->links() }}
        </div>
    @endif
</div>
