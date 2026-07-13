<div id="contributor-document-list" wire:poll.10s.keep-alive
     x-data="{ notification: null }" 
     x-on:notify.window="notification = $event.detail; setTimeout(() => notification = null, 3000)"
     class="space-y-8 font-sans pb-10">

    <style>
        #contributor-document-list, 
        #contributor-document-list.wire-loading,
        #contributor-document-list * { 
            transition: none !important; 
            animation: none !important;
            opacity: 1 !important;
            transform: none !important;
            visibility: visible !important;
        }
        [wire\:loading], [wire\:loading] * {
            opacity: 1 !important;
        }
    </style>

    <!-- Notification Toast -->
    <div x-show="notification" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 transform translate-y-2 scale-95"
         class="fixed bottom-5 right-5 z-50 rounded-2xl border border-indigo-100 bg-white/95 p-4 shadow-2xl backdrop-blur-md"
         style="display: none;">
         <div class="flex items-center gap-3">
             <template x-if="notification && notification.type === 'success'">
                 <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                 </div>
             </template>
             <div>
                 <p class="text-sm font-bold text-slate-800" x-text="notification ? notification.message : ''"></p>
             </div>
         </div>
    </div>

    <!-- Stats Section - Light Glassmorphic Design -->
    <div class="grid gap-6 grid-cols-2 lg:grid-cols-4">
        <!-- Approved -->
        <article class="relative overflow-hidden rounded-3xl border border-white bg-white/70 p-6 shadow-xl shadow-slate-100/50 backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
            <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-emerald-500/10 blur-xl"></div>
            <div class="flex items-center justify-between">
                <p class="text-xs uppercase tracking-[0.15em] text-slate-500 font-bold">Đã duyệt</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-black text-slate-800 tracking-tight"><?php echo e(number_format($approvedCount)); ?></p>
            <p class="mt-1.5 text-[10px] text-emerald-700 font-bold bg-emerald-50/50 border border-emerald-100 px-2 py-0.5 rounded-lg inline-block">Xuất bản công khai</p>
        </article>

        <!-- Pending -->
        <article class="relative overflow-hidden rounded-3xl border border-white bg-white/70 p-6 shadow-xl shadow-slate-100/50 backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
            <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-amber-500/10 blur-xl"></div>
            <div class="flex items-center justify-between">
                <p class="text-xs uppercase tracking-[0.15em] text-slate-500 font-bold">Chờ duyệt</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-black text-slate-800 tracking-tight"><?php echo e(number_format($pendingCount)); ?></p>
            <p class="mt-1.5 text-[10px] text-amber-700 font-bold bg-amber-50/50 border border-amber-100 px-2 py-0.5 rounded-lg inline-block animate-pulse">Đang kiểm duyệt</p>
        </article>

        <!-- Downloads -->
        <article class="relative overflow-hidden rounded-3xl border border-white bg-white/70 p-6 shadow-xl shadow-slate-100/50 backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
            <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-blue-500/10 blur-xl"></div>
            <div class="flex items-center justify-between">
                <p class="text-xs uppercase tracking-[0.15em] text-slate-500 font-bold">Lượt tải</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-black text-slate-800 tracking-tight"><?php echo e(number_format($totalDownloads)); ?></p>
            <p class="mt-1.5 text-[10px] text-indigo-700 font-bold bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-lg inline-block">Tổng lượt tải xuống</p>
        </article>

        <!-- Views -->
        <article class="relative overflow-hidden rounded-3xl border border-white bg-white/70 p-6 shadow-xl shadow-slate-100/50 backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
            <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-purple-500/10 blur-xl"></div>
            <div class="flex items-center justify-between">
                <p class="text-xs uppercase tracking-[0.15em] text-slate-500 font-bold">Lượt xem</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-black text-slate-800 tracking-tight"><?php echo e(number_format($totalViews)); ?></p>
            <p class="mt-1.5 text-[10px] text-purple-700 font-bold bg-purple-50/50 border border-purple-100 px-2 py-0.5 rounded-lg inline-block">Xem thử tài nguyên</p>
        </article>
    </div>

    <!-- Main List Card -->
    <section class="overflow-hidden rounded-[2rem] border border-white bg-white/75 shadow-xl shadow-slate-100/50 backdrop-blur-md">
        <!-- Filter Header -->
        <div class="p-6 border-b border-indigo-50/50 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-indigo-50/10">
            <div>
                <h2 class="text-lg font-extrabold text-slate-800">Quản Lý Tài Liệu</h2>
                <p class="text-xs text-slate-400 mt-0.5">Tìm kiếm, cập nhật trạng thái hiển thị, chỉnh sửa thông tin tài nguyên.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
                <a href="<?php echo e(route('contributor.documents.create')); ?>" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white px-4.5 py-2.5 text-xs font-bold shadow-lg shadow-indigo-600/15 hover:shadow-indigo-600/30 transition-all duration-200 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Đăng tài liệu mới
                </a>

                <!-- Status Filter -->
                <select wire:model.live="statusFilter" aria-label="Lọc theo trạng thái" class="rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 focus:border-indigo-400 focus:outline-none">
                    <option value="all">Tất cả trạng thái</option>
                    <option value="approved">Đã phê duyệt</option>
                    <option value="pending">Chờ phê duyệt</option>
                    <option value="rejected">Bị từ chối</option>
                    <option value="draft">Bản nháp</option>
                    <option value="deleted">Đã ẩn</option>
                </select>

                <!-- Category Filter -->
                <select wire:model.live="categoryFilter" aria-label="Lọc theo danh mục" class="rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 focus:border-indigo-400 focus:outline-none">
                    <option value="all">Tất cả danh mục</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </select>

                <!-- Search Input -->
                <div class="relative flex-1 sm:w-60 sm:flex-initial">
                    <input type="search" 
                           id="search-contributor-documents"
                           aria-label="Tìm kiếm tài liệu"
                           wire:model.live.debounce.300ms="search" 
                           placeholder="Tìm tên tài liệu..." 
                           class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 pl-10 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:border-indigo-400 focus:outline-none" />
                    <span class="absolute inset-y-0 left-3.5 inline-flex items-center text-slate-400">
                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8" /><path d="M21 21l-4.35-4.35" /></svg>
                    </span>
                </div>
            </div>
        </div>

        <!-- Table Content with Loading State -->
        <div wire:loading.class="opacity-60 transition-opacity duration-200" class="transition-opacity duration-200">
            <!-- Mobile Card View (hidden on md and up) -->
            <div class="block md:hidden divide-y divide-indigo-50/30">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="p-5 space-y-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1.5 flex-1 min-w-0">
                            <span class="font-bold text-slate-800 block leading-snug text-sm truncate" title="<?php echo e($doc->title); ?>">
                                <?php echo e($doc->title); ?>

                            </span>
                            
                            <div class="flex flex-wrap items-center gap-1.5 text-[10px] text-slate-400 font-bold">
                                <span class="inline-flex items-center rounded-lg bg-slate-100 px-2 py-0.5 uppercase text-slate-500 font-bold">
                                    <?php echo e($doc->file_type); ?>

                                </span>
                                <span><?php echo e(number_format($doc->file_size / 1024 / 1024, 2)); ?> MB</span>
                                <span class="inline-flex items-center gap-1 rounded-lg bg-blue-50 px-2 py-0.5 font-bold text-blue-700">
                                    <svg class="w-3 h-3 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                    <?php echo e($doc->category?->name ?? 'Mặc định'); ?>

                                </span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($doc->subject): ?>
                                <span class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 px-2 py-0.5 font-bold text-indigo-700">
                                    <svg class="w-3 h-3 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    <?php echo e($doc->subject->name); ?>

                                </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($doc->status === 'rejected' && $doc->rejected_reason) || ($doc->status === 'approved' && $doc->rejectedVersion)): ?>
                                <?php 
                                    $isEditRejection = $doc->status === 'approved' && $doc->rejectedVersion;
                                    $rejectedV = $doc->rejectedVersion;
                                    
                                    if ($isEditRejection) {
                                        $reason = $rejectedV->rejected_reason;
                                        $date = $rejectedV->updated_at->format('d/m/Y H:i');
                                        $title = 'Bản cập nhật bị từ chối';
                                        $bgColor = 'bg-amber-50';
                                        $borderColor = 'border-amber-400';
                                        $textColor = 'text-amber-900';
                                        $textColorLight = 'text-amber-800';
                                        $textColorLighter = 'text-amber-600';
                                        $iconColor = 'text-amber-600';
                                    } else {
                                        $reason = $doc->rejected_reason;
                                        $date = $doc->updated_at->format('d/m/Y H:i');
                                        $title = 'Bị từ chối';
                                        $bgColor = 'bg-rose-50';
                                        $borderColor = 'border-rose-500';
                                        $textColor = 'text-rose-900';
                                        $textColorLight = 'text-rose-800';
                                        $textColorLighter = 'text-rose-600';
                                        $iconColor = 'text-rose-600';
                                    }
                                ?>
                                <div class="mt-3 p-2 <?php echo e($bgColor); ?> border-l-4 <?php echo e($borderColor); ?> rounded-r-lg" x-data="{ expanded: false }">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex items-start gap-2 flex-1 min-w-0">
                                            <svg class="w-4 h-4 <?php echo e($iconColor); ?> shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            <div class="flex-1 text-[11px] min-w-0 leading-snug">
                                                <div :class="expanded ? '' : 'line-clamp-1'">
                                                    <span class="font-bold <?php echo e($textColor); ?>"><?php echo e($title); ?>:</span>
                                                    <span class="<?php echo e($textColorLight); ?> ml-1 break-words"><?php echo e($reason); ?></span>
                                                    <span class="<?php echo e($textColorLighter); ?> ml-2 whitespace-nowrap">(<?php echo e($date); ?>)</span>
                                                </div>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(strlen($reason) > 50): ?>
                                                    <button @click="expanded = !expanded" class="text-[11px] font-bold <?php echo e($textColor); ?> underline hover:opacity-80 mt-1 inline-flex items-center gap-1">
                                                        <span x-show="!expanded">▼ Xem thêm</span>
                                                        <span x-show="expanded" style="display: none;">▲ Thu gọn</span>
                                                    </button>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </div>
                                        <button wire:click="dismissRejectedVersion(<?php echo e($doc->id); ?>)" 
                                                class="shrink-0 <?php echo e($textColor); ?> hover:<?php echo e($textColorLight); ?> transition-colors mt-0.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($doc->status === 'approved' && $doc->pendingVersion): ?>
                                <a href="<?php echo e(route('contributor.documents.edit', ['id' => $doc->id])); ?>" class="mt-3 p-2.5 bg-indigo-50 border-l-4 border-indigo-400 rounded-r-lg hover:bg-indigo-100 transition-colors block">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-indigo-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div class="flex-1 text-[11px] min-w-0 leading-snug">
                                            <span class="font-bold text-indigo-900">Đang cập nhật:</span>
                                            <span class="text-indigo-800 ml-1">Có bản cập nhật đang chờ phê duyệt. Nhấn để xem chi tiết.</span>
                                        </div>
                                        <svg class="w-4 h-4 text-indigo-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($doc->tags->isNotEmpty()): ?>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $doc->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <span class="inline-flex items-center rounded bg-indigo-50 px-1.5 py-0.5 text-[9px] font-bold text-indigo-650 border border-indigo-100/30">#<?php echo e($tag->name); ?></span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <!-- Price tag -->
                        <div class="shrink-0 text-right space-y-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($doc->product && $doc->product->is_active): ?>
                                <span class="text-indigo-650 font-extrabold text-sm"><?php echo e(number_format($doc->product->price)); ?> VND</span>
                            <?php else: ?>
                                <span class="inline-flex rounded-lg bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-100">Miễn phí</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <!-- Meta info (Stats) -->
                    <div class="flex flex-wrap items-center justify-between gap-2 text-[10px] font-bold text-slate-400 bg-slate-50/50 p-3 rounded-2xl">
                        <div class="flex items-center gap-3">
                            <span>Tải về: <strong class="text-slate-700 font-extrabold"><?php echo e(number_format($doc->download_count)); ?></strong></span>
                            <span class="text-slate-350">|</span>
                            <span>Xem thử: <strong class="text-slate-700 font-extrabold"><?php echo e(number_format($doc->view_count)); ?></strong></span>
                            <span class="text-slate-350">|</span>
                            <span class="flex items-center text-rose-600">
                                <svg class="w-3 h-3 fill-rose-500 mr-0.5" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                Yêu thích: <strong class="font-extrabold"><?php echo e(number_format($doc->favorite_count)); ?></strong>
                            </span>
                        </div>
                        <span class="text-[9px] font-semibold text-slate-400"><?php echo e($doc->created_at->format('d/m/Y H:i')); ?></span>
                    </div>

                    <!-- Actions & Status -->
                    <div class="flex items-center justify-between gap-4 pt-1">
                        <div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($doc->trashed()): ?>
                                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 border border-slate-200 px-2.5 py-0.5 text-xs font-semibold text-slate-500">Đã ẩn</span>
                            <?php elseif($doc->status === 'approved'): ?>
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 text-xs font-bold text-emerald-700 border border-emerald-100 px-2.5 py-0.5">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Đã duyệt
                                </span>
                            <?php elseif($doc->status === 'pending'): ?>
                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 text-xs font-bold text-amber-700 border border-amber-100 px-2.5 py-0.5">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    Chờ duyệt
                                </span>
                            <?php elseif($doc->status === 'rejected'): ?>
                                <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 text-xs font-bold text-rose-700 border border-rose-100 px-2.5 py-0.5">
                                    <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                    Bị từ chối
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-650">Nháp</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold <?php echo e($doc->visibility === 'public' ? 'bg-indigo-50 text-indigo-600 border border-indigo-100' : 'bg-slate-50 text-slate-400'); ?>">
                                <?php echo e($doc->visibility === 'public' ? 'Công khai' : 'Riêng tư'); ?>

                            </span>
                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$doc->trashed() && $doc->status !== 'pending'): ?>
                                <a href="<?php echo e(route('contributor.documents.edit', ['id' => $doc->id])); ?>" class="inline-flex rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 text-xs font-bold transition-all">
                                    Sửa
                                </a>
                                <button onclick="confirm('Bạn có chắc chắn muốn ẩn tài liệu này không?') || event.stopImmediatePropagation()" 
                                        wire:click="deleteDocument(<?php echo e($doc->id); ?>)" 
                                        class="inline-flex rounded-xl bg-rose-50 hover:bg-rose-100 border border-rose-100 text-rose-600 px-3 py-1.5 text-xs font-bold transition-all">
                                    Ẩn
                                </button>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div class="p-8 text-center text-slate-400">
                    Không tìm thấy tài liệu nào khớp với bộ lọc.
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Desktop Table View (hidden on mobile) -->
        <div class="hidden md:block overflow-hidden">
            <table class="w-full text-left border-collapse table-fixed">
                <thead>
                    <tr class="border-b border-indigo-50/50 text-[10px] font-bold uppercase tracking-widest text-slate-400 bg-indigo-50/5">
                        <th class="px-6 py-4 cursor-pointer hover:bg-indigo-50/10 transition-colors w-[42%]" wire:click="sortBy('title')">
                            Tài liệu
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'title'): ?>
                                <span class="ml-1 text-[9px]"><?php echo e($sortDirection === 'asc' ? '▲' : '▼'); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </th>
                        <th class="px-6 py-4 w-[16%]">Hình thức</th>
                        <th class="px-6 py-4 w-[14%]">Trạng thái</th>
                        <th class="px-6 py-4 w-[14%]">Hiển thị</th>
                        <th class="px-6 py-4 cursor-pointer hover:bg-indigo-50/10 transition-colors w-[14%]" wire:click="sortBy('created_at')">
                            Ngày đăng
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'created_at'): ?>
                                <span class="ml-1 text-[9px]"><?php echo e($sortDirection === 'asc' ? '▲' : '▼'); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </th>
                        <th class="px-6 py-4 text-right w-[14%]">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-indigo-50/40 text-sm text-slate-650">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr class="hover:bg-indigo-50/10 transition-colors duration-200">
                            <td class="px-6 py-4 overflow-hidden">
                                <div class="space-y-1">
                                    <span class="font-bold text-slate-800 block leading-tight truncate" title="<?php echo e($doc->title); ?>">
                                        <?php echo e($doc->title); ?>

                                    </span>
                                    
                                    <div class="flex flex-wrap items-center gap-1.5 text-[10px] text-slate-400 font-bold">
                                        <span class="inline-flex items-center rounded bg-slate-100 px-1.5 py-0.5 uppercase text-slate-500 font-bold">
                                            <?php echo e($doc->file_type); ?>

                                        </span>
                                        <span><?php echo e(number_format($doc->file_size / 1024 / 1024, 2)); ?> MB</span>
                                        <span class="inline-flex items-center gap-1 rounded bg-blue-50 px-1.5 py-0.5 font-bold text-blue-700">
                                            <svg class="w-3 h-3 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                            <?php echo e($doc->category?->name ?? 'Mặc định'); ?>

                                        </span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($doc->subject): ?>
                                        <span class="inline-flex items-center gap-1 rounded bg-indigo-50 px-1.5 py-0.5 font-bold text-indigo-700">
                                            <svg class="w-3 h-3 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                            <?php echo e($doc->subject->name); ?>

                                        </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <span class="text-slate-300">|</span>
                                        <span class="inline-flex items-center gap-1.5 flex-wrap">
                                            <span>Tải về: <strong class="text-slate-600 font-extrabold"><?php echo e(number_format($doc->download_count)); ?></strong></span>
                                            <span class="text-slate-300">|</span>
                                            <span>Xem thử: <strong class="text-slate-600 font-extrabold"><?php echo e(number_format($doc->view_count)); ?></strong></span>
                                            <span class="text-slate-300">|</span>
                                            <span class="flex items-center text-rose-600">
                                                <svg class="w-3.5 h-3.5 fill-rose-500 mr-0.5 inline" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                                Yêu thích: <strong class="font-extrabold"><?php echo e(number_format($doc->favorite_count)); ?></strong>
                                            </span>
                                        </span>
                                    </div>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($doc->status === 'rejected' && $doc->rejected_reason) || ($doc->status === 'approved' && $doc->rejectedVersion)): ?>
                                        <?php 
                                            $isEditRejection = $doc->status === 'approved' && $doc->rejectedVersion;
                                            $rejectedV = $doc->rejectedVersion;
                                            
                                            if ($isEditRejection) {
                                                $reason = $rejectedV->rejected_reason;
                                                $date = $rejectedV->updated_at->format('d/m/Y H:i');
                                                $title = 'Bản cập nhật bị từ chối';
                                                $bgColor = 'bg-amber-50';
                                                $borderColor = 'border-amber-400';
                                                $textColor = 'text-amber-900';
                                                $textColorLight = 'text-amber-800';
                                                $textColorLighter = 'text-amber-600';
                                                $iconColor = 'text-amber-600';
                                            } else {
                                                $reason = $doc->rejected_reason;
                                                $date = $doc->updated_at->format('d/m/Y H:i');
                                                $title = 'Bị từ chối';
                                                $bgColor = 'bg-rose-50';
                                                $borderColor = 'border-rose-500';
                                                $textColor = 'text-rose-900';
                                                $textColorLight = 'text-rose-800';
                                                $textColorLighter = 'text-rose-600';
                                                $iconColor = 'text-rose-600';
                                            }
                                        ?>
                                        <div class="mt-3 p-2 <?php echo e($bgColor); ?> border-l-4 <?php echo e($borderColor); ?> rounded-r-lg" x-data="{ expanded: false }">
                                            <div class="flex items-start justify-between gap-2">
                                                <div class="flex items-start gap-2 flex-1 min-w-0">
                                                    <svg class="w-4 h-4 <?php echo e($iconColor); ?> shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                    </svg>
                                                    <div class="flex-1 text-[11px] min-w-0 leading-snug">
                                                        <div :class="expanded ? '' : 'line-clamp-1'">
                                                            <span class="font-bold <?php echo e($textColor); ?>"><?php echo e($title); ?>:</span>
                                                            <span class="<?php echo e($textColorLight); ?> ml-1 break-words"><?php echo e($reason); ?></span>
                                                            <span class="<?php echo e($textColorLighter); ?> ml-2 whitespace-nowrap">(<?php echo e($date); ?>)</span>
                                                        </div>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(strlen($reason) > 50): ?>
                                                            <button @click="expanded = !expanded" class="text-[11px] font-bold <?php echo e($textColor); ?> underline hover:opacity-80 mt-1 inline-flex items-center gap-1">
                                                                <span x-show="!expanded">▼ Xem thêm</span>
                                                                <span x-show="expanded" style="display: none;">▲ Thu gọn</span>
                                                            </button>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </div>
                                                </div>
                                                <button wire:click="dismissRejectedVersion(<?php echo e($doc->id); ?>)" 
                                                        class="shrink-0 <?php echo e($textColor); ?> hover:<?php echo e($textColorLight); ?> transition-colors mt-0.5">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($doc->status === 'approved' && $doc->pendingVersion): ?>
                                        <a href="<?php echo e(route('contributor.documents.edit', ['id' => $doc->id])); ?>" class="mt-3 p-2.5 bg-indigo-50 border-l-4 border-indigo-400 rounded-r-lg hover:bg-indigo-100 transition-colors block">
                                            <div class="flex items-start gap-2">
                                                <svg class="w-4 h-4 text-indigo-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <div class="flex-1 text-[11px] min-w-0 leading-snug">
                                                    <span class="font-bold text-indigo-900">Đang cập nhật:</span>
                                                    <span class="text-indigo-800 ml-1">Có bản cập nhật đang chờ phê duyệt.</span>
                                                </div>
                                                <svg class="w-4 h-4 text-indigo-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </div>
                                        </a>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($doc->tags->isNotEmpty()): ?>
                                        <div class="flex flex-wrap gap-1 mt-1.5">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $doc->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                                <span class="inline-flex items-center rounded bg-indigo-50 px-1.5 py-0.5 text-[9px] font-bold text-indigo-600 border border-indigo-100/30">#<?php echo e($tag->name); ?></span>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($doc->product && $doc->product->is_active): ?>
                                    <span class="text-indigo-600 font-extrabold"><?php echo e(number_format($doc->product->price)); ?> VND</span>
                                <?php else: ?>
                                    <span class="inline-flex rounded-lg bg-emerald-50 text-[10px] font-bold text-emerald-700 border border-emerald-100">Miễn phí</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($doc->trashed()): ?>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 border border-slate-200 px-2.5 py-0.5 text-xs font-semibold text-slate-500">Đã ẩn</span>
                                <?php elseif($doc->status === 'approved'): ?>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 text-xs font-bold text-emerald-700 border border-emerald-100 px-2.5 py-0.5">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Đã duyệt
                                    </span>
                                <?php elseif($doc->status === 'pending'): ?>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 text-xs font-bold text-amber-700 border border-amber-100 px-2.5 py-0.5">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Chờ duyệt
                                    </span>
                                <?php elseif($doc->status === 'rejected'): ?>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 text-xs font-bold text-rose-700 border border-rose-100 px-2.5 py-0.5" title="Lý do: <?php echo e($doc->rejected_reason); ?>">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                        Bị từ chối
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">Nháp</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold <?php echo e($doc->visibility === 'public' ? 'bg-indigo-50 text-indigo-600 border border-indigo-100' : 'bg-slate-50 text-slate-400'); ?>">
                                    <span class="h-1.5 w-1.5 rounded-full <?php echo e($doc->visibility === 'public' ? 'bg-indigo-500' : 'bg-slate-400'); ?>"></span>
                                    <?php echo e($doc->visibility === 'public' ? 'Công khai' : 'Riêng tư'); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-400 font-medium whitespace-nowrap text-xs">
                                <?php echo e($doc->created_at->format('d/m/Y')); ?>

                                <span class="block text-[10px] text-slate-400 font-normal mt-0.5"><?php echo e($doc->created_at->format('H:i')); ?></span>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$doc->trashed() && $doc->status !== 'pending'): ?>
                                        <a href="<?php echo e(route('contributor.documents.edit', ['id' => $doc->id])); ?>" class="inline-flex rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-2 text-xs font-bold shadow-sm transition-all duration-200">
                                            Sửa
                                        </a>
                                        <button onclick="confirm('Bạn có chắc chắn muốn ẩn tài liệu này không?') || event.stopImmediatePropagation()" 
                                                wire:click="deleteDocument(<?php echo e($doc->id); ?>)" 
                                                class="inline-flex rounded-xl bg-rose-50 hover:bg-rose-100 border border-rose-100 text-rose-600 px-3.5 py-2 text-xs font-bold transition-all duration-200">
                                            Ẩn đi
                                        </button>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                <span class="font-medium text-slate-450">Không tìm thấy tài liệu phù hợp.</span>
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
        </div>

        <!-- Pagination -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($documents->hasPages()): ?>
            <div class="p-6 border-t border-indigo-50/50 bg-indigo-50/5">
                <?php echo e($documents->links(data: ['scrollTo' => '#contributor-document-list'])); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </section>
</div>
<?php /**PATH D:\IT-Learning\Modules/Document\resources/views/livewire/contributor/document-list.blade.php ENDPATH**/ ?>