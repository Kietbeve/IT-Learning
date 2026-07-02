<div class="flex gap-3" x-data="{ showReplyForm: false }">
    <div class="h-8 w-8 shrink-0 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs uppercase mt-0.5">
        {{ substr($comment->user?->name ?? 'U', 0, 1) }}
    </div>
    <div class="flex-1 space-y-1 min-w-0">
        @if($editingCommentId === $comment->id)
            <form wire:submit.prevent="updateComment" class="space-y-2">
                <textarea wire:model="editingContent" 
                          rows="2" 
                          class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-300"></textarea>
                @error('editingContent')
                    <span class="text-xs text-red-500 font-medium">{{ $message }}</span>
                @enderror
                <div class="flex gap-2">
                    <button type="button" wire:click="cancelEditComment" class="text-xs text-slate-500 hover:text-slate-700 font-semibold">Hủy</button>
                    <button type="submit" class="text-xs text-blue-600 hover:text-blue-700 font-semibold">Lưu</button>
                </div>
            </form>
        @else
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-2 min-w-0">
                    <span class="text-sm font-bold text-slate-900 truncate">{{ $comment->user?->name ?? 'Người dùng' }}</span>
                    <span class="text-xs text-slate-400 shrink-0">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                    @auth
                        @if(Auth::id() === $comment->user_id)
                            @if($comment->created_at->diffInHours(now()) < 24)
                                <button wire:click="startEditComment({{ $comment->id }})" class="text-xs text-slate-400 hover:text-blue-600 transition-colors" title="Sửa">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                            @endif
                            <button wire:click="deleteComment({{ $comment->id }})" wire:confirm="Xoá bình luận này?" class="text-xs text-slate-400 hover:text-red-500 transition-colors" title="Xoá">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        @endif
                        @if($isAdmin ?? false)
                            <button wire:click="hideComment({{ $comment->id }})" class="text-xs text-amber-600 hover:text-amber-800 font-semibold px-2 py-1 rounded" title="Ẩn bình luận">
                                Ẩn
                            </button>
                        @endif
                        @if($level < 2)
                            <button @click="showReplyForm = !showReplyForm" class="text-xs text-slate-400 hover:text-blue-600 transition-colors" title="Trả lời">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                            </button>
                        @endif
                    @endauth
                </div>
            </div>
            <p class="text-sm text-slate-600 leading-relaxed break-words">{{ $comment->content }}</p>

            <!-- Reply form inline -->
            @auth
                <div x-show="showReplyForm" x-cloak class="mt-2">
                    @if($replyTo === $comment->id)
                        <form wire:submit.prevent="addReply" class="flex gap-2">
                            <div class="flex-1">
                                <textarea wire:model="replyContent" 
                                          rows="1" 
                                          placeholder="Viết trả lời..."
                                          class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-300"></textarea>
                                @error('replyContent')
                                    <span class="text-xs text-red-500 font-medium">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex items-start gap-1 pt-1">
                                <button type="button" wire:click="cancelReply" class="text-xs text-slate-500 hover:text-slate-700 font-semibold px-2">Hủy</button>
                                <button type="submit" class="text-xs text-white bg-slate-900 hover:bg-slate-800 rounded-lg px-3 py-1.5 font-semibold">Trả lời</button>
                            </div>
                        </form>
                    @else
                        <button wire:click="startReply({{ $comment->id }})" class="text-xs text-blue-600 hover:text-blue-700 font-semibold">Trả lời</button>
                    @endif
                </div>
            @endauth

            <!-- Nested replies -->
            @if($comment->relationLoaded('replies') && $comment->replies->isNotEmpty())
                <div class="mt-3 space-y-3 pl-2 border-l-2 border-slate-100">
                    @foreach($comment->replies as $reply)
                        @include('document::livewire.user.comment-item', ['comment' => $reply, 'level' => $level + 1, 'isAdmin' => $isAdmin ?? false])
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</div>
