<div>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showDeleteConfirm): ?>
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="w-full max-w-md bg-white rounded-2xl p-6 shadow-xl">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Xác nhận xóa</h3>
                <p class="text-sm text-gray-600 mb-6">
                    Bạn có chắc muốn xóa <?php echo e($deleteType === 'thread' ? 'thảo luận này' : 'bình luận này'); ?>? Hành động này không thể hoàn tác.
                </p>
                <div class="flex justify-end gap-3">
                    <button wire:click="cancelDelete" class="px-4 py-2 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50">Hủy</button>
                    <button wire:click="executeDelete" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl">Xóa</button>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showDetail && $this->detailThread): ?>
        <?php $thread = $this->detailThread; ?>
        <div class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-black/50 py-8">
            <div class="w-full max-w-3xl bg-white rounded-2xl shadow-xl mx-4" x-data @keydown.escape.window="$wire.closeDetail()">
                
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center gap-2">
                        <h2 class="text-lg font-bold text-gray-900 truncate max-w-md"><?php echo e($thread->title); ?></h2>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($thread->is_pinned): ?> <span class="text-xs font-bold text-yellow-700 bg-yellow-100 px-2 py-0.5 rounded">Ghim</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($thread->is_locked): ?> <span class="text-xs font-bold text-red-700 bg-red-100 px-2 py-0.5 rounded">Khóa</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($thread->is_answered): ?> <span class="text-xs font-bold text-green-700 bg-green-100 px-2 py-0.5 rounded">✓ Đã trả lời</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <button wire:click="closeDetail" class="p-2 hover:bg-gray-100 rounded-lg text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <div class="p-6">
                    
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold"><?php echo e(strtoupper(substr($thread->user->name ?? '?', 0, 1))); ?></div>
                        <div>
                            <p class="text-sm font-bold text-gray-900"><?php echo e($thread->user->name ?? 'Ẩn danh'); ?></p>
                            <p class="text-xs text-gray-400"><?php echo e($thread->created_at->format('d/m/Y H:i')); ?> • <?php echo e($thread->view_count); ?> lượt xem</p>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($thread->threadable): ?>
                            <span class="ml-auto text-xs text-gray-500"><?php echo e(class_basename($thread->threadable_type) === 'Roadmap' ? '📚' : '📖'); ?> <?php echo e($thread->threadable->title); ?></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap bg-gray-50 rounded-xl p-4 mb-6"><?php echo e($thread->content); ?></div>

                    
                    <h3 class="text-sm font-bold text-gray-800 mb-3">Trả lời (<?php echo e($thread->posts->count()); ?>)</h3>
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $thread->posts->whereNull('parent_id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="bg-white border border-gray-100 rounded-xl p-4 <?php echo e($post->is_best_answer ? 'border-green-300 bg-green-50/30' : ''); ?>">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-gray-600 to-gray-800 rounded-full flex items-center justify-center text-white text-xs font-bold"><?php echo e(strtoupper(substr($post->user->name ?? '?', 0, 1))); ?></div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-2">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-bold text-gray-900"><?php echo e($post->user->name); ?></span>
                                                <span class="text-xs text-gray-400"><?php echo e($post->created_at->diffForHumans()); ?></span>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post->is_best_answer): ?> <span class="text-xs font-bold text-green-700 bg-green-100 px-2 py-0.5 rounded">★ Best</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <button wire:click="replyTo(<?php echo e($post->id); ?>)"
                                                        class="p-1.5 rounded-lg hover:bg-cyan-50 text-gray-400 hover:text-cyan-600 transition-colors text-xs" title="Trả lời">
                                                    💬
                                                </button>
                                                <button wire:click="confirmDeletePost(<?php echo e($post->id); ?>)"
                                                        class="p-1.5 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-500 transition-colors text-xs">
                                                    🗑
                                                </button>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-700 mt-1 whitespace-pre-wrap"><?php echo e($post->content); ?></p>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post->replies->count() > 0): ?>
                                            <div class="mt-2 ml-4 pl-3 border-l-2 border-gray-100 space-y-2">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $post->replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                    <div class="bg-gray-50 rounded-lg p-3">
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex items-center gap-2">
                                                                <span class="text-xs font-bold text-gray-900"><?php echo e($reply->user->name); ?></span>
                                                                <span class="text-xs text-gray-400"><?php echo e($reply->created_at->diffForHumans()); ?></span>
                                                            </div>
                                                            <button wire:click="confirmDeletePost(<?php echo e($reply->id); ?>)"
                                                                    class="text-xs text-gray-400 hover:text-red-500 transition-colors">🗑</button>
                                                        </div>
                                                        <p class="text-sm text-gray-700 mt-1 whitespace-pre-wrap"><?php echo e($reply->content); ?></p>
                                                    </div>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($replyToPostId === $post->id): ?>
                                            <div class="mt-3 ml-4">
                                                <form wire:submit="submitReply" class="flex gap-2">
                                                    <input type="text" wire:model="replyContent"
                                                           class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                                           placeholder="Trả lời...">
                                                    <button type="submit"
                                                            class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg">Gửi</button>
                                                    <button type="button" wire:click="cancelReply"
                                                            class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-50">Hủy</button>
                                                </form>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['replyContent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-red-500 mt-1 ml-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <p class="text-sm text-gray-400 text-center py-4">Chưa có câu trả lời</p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$thread->is_locked): ?>
                        <div class="mt-4 border-t border-gray-100 pt-4">
                            <form wire:submit="submitReply">
                                <textarea wire:model="replyContent" rows="2"
                                          class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                          placeholder="Nhập câu trả lời với tư cách Admin..."></textarea>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['replyContent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <div class="flex justify-end mt-2">
                                    <button type="submit"
                                            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-colors">
                                        Gửi trả lời
                                    </button>
                                </div>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-xl text-sm text-yellow-700 text-center">
                            🔒 Chủ đề này đã bị khóa
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="mb-8 relative overflow-hidden rounded-3xl bg-gradient-to-br from-cyan-600 via-blue-600 to-indigo-600 p-8 text-white shadow-xl">
        <div class="absolute inset-0 bg-white opacity-[0.05]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="mb-5 inline-flex items-center gap-2 rounded-full bg-white/20 px-4 py-1.5 text-sm font-bold text-white hover:bg-white/30 backdrop-blur-md border border-white/10 transition-all shadow-sm w-max">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Quay lại Trang chủ
                </a>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight drop-shadow-md">Quản Lý Diễn Đàn</h1>
                <p class="mt-3 text-cyan-100 max-w-2xl leading-relaxed text-base">Quản lý các chủ đề thảo luận, kiểm duyệt bình luận và hỗ trợ học viên.</p>
            </div>
        </div>
    </div>

    
    <div class="mb-8 grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-4">
        <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Thảo luận</p>
            <p class="mt-1 text-2xl font-black text-slate-800"><?php echo e(number_format($this->stats['threads'])); ?></p>
        </div>
        <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Trả lời</p>
            <p class="mt-1 text-2xl font-black text-slate-800"><?php echo e(number_format($this->stats['posts'])); ?></p>
        </div>
        <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Thành viên</p>
            <p class="mt-1 text-2xl font-black text-slate-800"><?php echo e(number_format($this->stats['users'])); ?></p>
        </div>
        <div class="rounded-2xl border border-amber-100 bg-gradient-to-br from-amber-50 to-amber-100/50 p-4 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <p class="text-[10px] font-bold uppercase tracking-wider text-amber-600">Đã ghim</p>
            <p class="mt-1 text-2xl font-black text-amber-800"><?php echo e(number_format($this->stats['pinned'])); ?></p>
        </div>
        <div class="rounded-2xl border border-rose-100 bg-gradient-to-br from-rose-50 to-rose-100/50 p-4 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <p class="text-[10px] font-bold uppercase tracking-wider text-rose-600">Đã khóa</p>
            <p class="mt-1 text-2xl font-black text-rose-800"><?php echo e(number_format($this->stats['locked'])); ?></p>
        </div>
        <div class="rounded-2xl border border-emerald-100 bg-gradient-to-br from-emerald-50 to-emerald-100/50 p-4 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Đã giải đáp</p>
            <p class="mt-1 text-2xl font-black text-emerald-800"><?php echo e(number_format($this->stats['answered'])); ?></p>
        </div>
        <div class="rounded-2xl border border-blue-100 bg-gradient-to-br from-blue-50 to-blue-100/50 p-4 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <p class="text-[10px] font-bold uppercase tracking-wider text-blue-600">Lượt thích</p>
            <p class="mt-1 text-2xl font-black text-blue-800"><?php echo e(number_format($this->stats['likes'])); ?></p>
        </div>
        <div class="rounded-2xl border border-purple-100 bg-gradient-to-br from-purple-50 to-purple-100/50 p-4 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <p class="text-[10px] font-bold uppercase tracking-wider text-purple-600">Bookmark</p>
            <p class="mt-1 text-2xl font-black text-purple-800"><?php echo e(number_format($this->stats['bookmarks'])); ?></p>
        </div>
    </div>

    
    <div class="mb-6 bg-white rounded-3xl p-5 shadow-sm border border-slate-100 flex flex-col sm:flex-row gap-4">
        <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Tìm kiếm thảo luận..."
                   class="w-full rounded-2xl border-slate-200 bg-slate-50/50 hover:bg-slate-50 pl-11 pr-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 transition-colors font-medium">
        </div>
        <select wire:model.live="statusFilter"
                class="rounded-2xl border-slate-200 bg-slate-50/50 hover:bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 transition-colors font-medium text-slate-700 min-w-[200px] cursor-pointer">
            <option value="">Tất cả trạng thái</option>
            <option value="pinned">Đã ghim</option>
            <option value="locked">Đã khóa</option>
            <option value="answered">Đã giải đáp</option>
            <option value="unanswered">Chưa trả lời</option>
        </select>
    </div>

    
    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <th class="px-4 py-3 cursor-pointer hover:text-slate-700" wire:click="sortBy('title')">
                            Tiêu đề <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'title'): ?> <span class="text-blue-600"><?php echo e($sortDirection === 'asc' ? '↑' : '↓'); ?></span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </th>
                        <th class="px-4 py-3">Tác giả</th>
                        <th class="px-4 py-3">Lộ trình</th>
                        <th class="px-4 py-3">Trạng thái</th>
                        <th class="px-4 py-3 cursor-pointer hover:text-slate-700" wire:click="sortBy('view_count')">
                            Views <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'view_count'): ?> <span class="text-blue-600"><?php echo e($sortDirection === 'asc' ? '↑' : '↓'); ?></span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </th>
                        <th class="px-4 py-3 cursor-pointer hover:text-slate-700" wire:click="sortBy('last_post_at')">
                            Cập nhật <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'last_post_at'): ?> <span class="text-blue-600"><?php echo e($sortDirection === 'asc' ? '↑' : '↓'); ?></span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </th>
                        <th class="px-4 py-3 text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->threads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thread): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr class="hover:bg-slate-50 transition-colors <?php echo e($thread->trashed() ? 'opacity-50' : ''); ?>">
                            <td class="px-4 py-3">
                                <button wire:click="viewThread(<?php echo e($thread->id); ?>)" class="text-left font-medium text-slate-900 hover:text-blue-600 transition-colors">
                                    <span class="line-clamp-1"><?php echo e($thread->title); ?></span>
                                </button>
                                <div class="text-xs text-slate-400 mt-0.5">💬 <?php echo e($thread->posts()->count()); ?> trả lời</div>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-600">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-slate-200 rounded-full flex items-center justify-center text-[10px] font-bold text-slate-600"><?php echo e(strtoupper(substr($thread->user->name ?? '?', 0, 1))); ?></div>
                                    <?php echo e($thread->user->name ?? 'Ẩn danh'); ?>

                                </div>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500 max-w-[150px] truncate">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($thread->threadable): ?>
                                    <?php echo e($thread->threadable->title ?? 'N/A'); ?>

                                <?php else: ?>
                                    <span class="text-red-400">Đã xóa</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-1 flex-wrap">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($thread->is_pinned): ?> <span class="text-[10px] font-bold text-yellow-700 bg-yellow-100 px-1.5 py-0.5 rounded">Ghim</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($thread->is_locked): ?> <span class="text-[10px] font-bold text-red-700 bg-red-100 px-1.5 py-0.5 rounded">Khóa</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($thread->is_answered): ?> <span class="text-[10px] font-bold text-green-700 bg-green-100 px-1.5 py-0.5 rounded">✓</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($thread->trashed()): ?> <span class="text-[10px] font-bold text-red-700 bg-red-100 px-1.5 py-0.5 rounded">Đã xóa</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500"><?php echo e($thread->view_count); ?></td>
                            <td class="px-4 py-3 text-xs text-slate-500"><?php echo e($thread->last_post_at?->diffForHumans() ?? $thread->created_at->diffForHumans()); ?></td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="viewThread(<?php echo e($thread->id); ?>)" class="p-1.5 rounded-lg hover:bg-blue-50 text-slate-400 hover:text-blue-600 transition-colors" title="Xem chi tiết">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    <button wire:click="togglePin(<?php echo e($thread->id); ?>)" class="p-1.5 rounded-lg hover:bg-yellow-50 text-slate-400 hover:text-yellow-600 transition-colors" title="<?php echo e($thread->is_pinned ? 'Bỏ ghim' : 'Ghim'); ?>">
                                        <svg class="w-4 h-4" fill="<?php echo e($thread->is_pinned ? 'currentColor' : 'none'); ?>" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                    </button>
                                    <button wire:click="toggleLock(<?php echo e($thread->id); ?>)" class="p-1.5 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-600 transition-colors" title="<?php echo e($thread->is_locked ? 'Mở khóa' : 'Khóa'); ?>">
                                        <svg class="w-4 h-4" fill="<?php echo e($thread->is_locked ? 'currentColor' : 'none'); ?>" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    </button>
                                    <button wire:click="confirmDeleteThread(<?php echo e($thread->id); ?>)" class="p-1.5 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-600 transition-colors" title="Xóa">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-slate-400">
                                <p class="text-lg mb-1">📭 Không có thảo luận nào</p>
                                <p class="text-sm">Chưa có thảo luận nào trong hệ thống.</p>
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->threads instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
            <div class="px-4 py-3 border-t border-slate-100">
                <?php echo e($this->threads->links()); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </section>
</div>
<?php /**PATH D:\IT-Learning\Modules/Learning\resources/views/livewire/admin/forum-management.blade.php ENDPATH**/ ?>