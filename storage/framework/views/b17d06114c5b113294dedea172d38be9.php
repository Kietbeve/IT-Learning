<div id="admin-category-list"
     x-data="{ notification: null, showFormModal: <?php if ((object) ('isFormOpen') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isFormOpen'->value()); ?>')<?php echo e('isFormOpen'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isFormOpen'); ?>')<?php endif; ?> }" 
     x-on:notify.window="notification = $event.detail; setTimeout(() => notification = null, 3000)"
     @open-modal.window="if ($event.detail === 'category-form-modal') showFormModal = true"
     @close-modal.window="if ($event.detail === 'category-form-modal') showFormModal = false"
     class="space-y-6">

    <!-- Notification Toast -->
    <div x-show="notification" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-5 right-5 z-50 rounded-2xl border bg-white p-4 shadow-xl border-slate-200"
         style="display: none;">
        <div class="flex items-center gap-3">
            <template x-if="notification && notification.type === 'success'">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </template>
            <template x-if="notification && notification.type === 'error'">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-rose-100 text-rose-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </template>
            <div>
                <p class="text-sm font-semibold text-slate-900" x-text="notification ? notification.message : ''"></p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid gap-4 md:gap-6 grid-cols-1 md:grid-cols-3">
        <!-- Total -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-600">Tổng danh mục</p>
                <span class="rounded-xl bg-slate-50 p-2">
                    <svg class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-slate-900"><?php echo e(number_format($totalCount)); ?></p>
            <p class="mt-1 text-sm text-slate-500">Danh mục tài nguyên</p>
        </div>

        <!-- Active -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-600">Đang hoạt động</p>
                <span class="rounded-xl bg-green-50 p-2">
                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-slate-900"><?php echo e(number_format($activeCount)); ?></p>
            <p class="mt-1 text-sm text-slate-500">Khả dụng đăng tải</p>
        </div>

        <!-- Inactive -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-600">Tạm khóa</p>
                <span class="rounded-xl bg-amber-50 p-2">
                    <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-slate-900"><?php echo e(number_format($inactiveCount)); ?></p>
            <p class="mt-1 text-sm text-slate-500">Ẩn trên bộ lọc</p>
        </div>
    </div>

    <!-- Main List Card -->
    <section class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <!-- Filter Header -->
        <div class="p-6 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-slate-50/50">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Danh mục tài liệu</h2>
                <p class="text-sm text-slate-500 mt-1">Quản lý các danh mục phân loại tài liệu học tập trong hệ thống.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">
                <!-- Status Filter -->
                <select wire:model.live="statusFilter" aria-label="Bộ lọc trạng thái" class="w-full sm:w-auto rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none">
                    <option value="all">Tất cả trạng thái</option>
                    <option value="active">Đang hoạt động</option>
                    <option value="inactive">Tạm dừng</option>
                </select>

                <!-- Search Input -->
                <div class="relative flex-1 sm:w-64 sm:flex-initial">
                    <input type="search" 
                           id="search-admin-categories"
                           aria-label="Tìm kiếm danh mục"
                           wire:model.live.debounce.300ms="search" 
                           placeholder="Tìm kiếm danh mục..." 
                           class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2.5 pl-10 text-sm text-slate-900 focus:border-slate-400 focus:outline-none" />
                    <span class="absolute inset-y-0 left-3.5 inline-flex items-center text-slate-400">
                        <svg viewBox="0 0 24 24" class="h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8" /><path d="M21 21l-4.35-4.35" /></svg>
                    </span>
                </div>

                <!-- Add Button -->
                <button type="button" 
                        wire:click="openCreateModal"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white px-4 py-2.5 text-sm font-semibold transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Thêm danh mục
                </button>
            </div>
        </div>

        <!-- Table Content with Loading State -->
        <div wire:loading.class="opacity-60 transition-opacity duration-200" class="transition-opacity duration-200">
            <!-- Mobile Card View (hidden on md and up) -->
            <div class="block md:hidden divide-y divide-slate-100">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="p-4 space-y-3">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1 flex-1 min-w-0">
                            <span class="font-bold text-slate-950 block leading-tight text-base truncate"><?php echo e($cat->name); ?></span>
                            <div class="text-[10px] font-mono text-slate-400 truncate">Đường dẫn: /documents?category=<?php echo e($cat->slug); ?></div>
                        </div>
                        <span class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-700 shrink-0">
                            Thứ tự: <?php echo e($cat->sort_order); ?>

                        </span>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cat->description): ?>
                        <p class="text-xs text-slate-500 line-clamp-2"><?php echo e($cat->description); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="flex items-center justify-between gap-4 pt-2">
                        <button wire:click="toggleStatus(<?php echo e($cat->id); ?>)" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors <?php echo e($cat->is_active ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-rose-50 text-rose-700 hover:bg-rose-100'); ?>">
                            <span class="h-1.5 w-1.5 rounded-full <?php echo e($cat->is_active ? 'bg-emerald-600' : 'bg-rose-600'); ?>"></span>
                            <?php echo e($cat->is_active ? 'Hoạt động' : 'Tạm khóa'); ?>

                        </button>
                        <div class="flex items-center gap-2">
                            <button wire:click="editCategory(<?php echo e($cat->id); ?>)" 
                                    class="inline-flex rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 text-xs font-bold transition-all shadow-sm">
                                Sửa
                            </button>
                            <button onclick="confirm('Bạn có chắc chắn muốn xóa danh mục này? Thao tác không thể hoàn tác.') || event.stopImmediatePropagation()" 
                                    wire:click="deleteCategory(<?php echo e($cat->id); ?>)" 
                                    class="inline-flex rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 px-3 py-1.5 text-xs font-bold transition-all shadow-sm">
                                Xóa
                            </button>
                        </div>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div class="p-8 text-center text-slate-400">
                    Không tìm thấy danh mục nào.
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Desktop Table View (hidden on mobile) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-full">
                <thead>
                    <tr class="border-b border-slate-200 text-xs font-semibold uppercase tracking-wider text-slate-500 bg-slate-50/70">
                        <th class="px-6 py-4 cursor-pointer hover:bg-slate-100 transition-colors" wire:click="sortBy('name')">
                            Danh mục
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'name'): ?>
                                <span class="ml-1 text-[10px]"><?php echo e($sortDirection === 'asc' ? '▲' : '▼'); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </th>
                        <th class="px-6 py-4 cursor-pointer hover:bg-slate-100 transition-colors" wire:click="sortBy('sort_order')">
                            Thứ tự ưu tiên
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'sort_order'): ?>
                                <span class="ml-1 text-[10px]"><?php echo e($sortDirection === 'asc' ? '▲' : '▼'); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </th>
                        <th class="px-6 py-4">Trạng thái</th>
                        <th class="px-6 py-4 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="space-y-1 max-w-sm sm:max-w-md lg:max-w-lg">
                                    <span class="font-bold text-slate-950 block leading-tight text-base"><?php echo e($cat->name); ?></span>
                                    <div class="text-[10px] font-mono text-slate-400">Đường dẫn: /documents?category=<?php echo e($cat->slug); ?></div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cat->description): ?>
                                        <p class="text-xs text-slate-500 mt-1 line-clamp-2"><?php echo e($cat->description); ?></p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-3 py-1.5 font-bold text-slate-700">
                                    <?php echo e($cat->sort_order); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="toggleStatus(<?php echo e($cat->id); ?>)" 
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors <?php echo e($cat->is_active ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-rose-50 text-rose-700 hover:bg-rose-100'); ?>">
                                    <span class="h-1.5 w-1.5 rounded-full <?php echo e($cat->is_active ? 'bg-emerald-600' : 'bg-rose-600'); ?>"></span>
                                    <?php echo e($cat->is_active ? 'Hoạt động' : 'Tạm khóa'); ?>

                                </button>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="editCategory(<?php echo e($cat->id); ?>)" 
                                            class="inline-flex rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 px-3.5 py-2 text-xs font-bold transition-all shadow-sm">
                                        Sửa
                                    </button>
                                    <button onclick="confirm('Bạn có chắc chắn muốn xóa danh mục này? Thao tác không thể hoàn tác.') || event.stopImmediatePropagation()" 
                                            wire:click="deleteCategory(<?php echo e($cat->id); ?>)" 
                                            class="inline-flex rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 px-3.5 py-2 text-xs font-bold transition-all shadow-sm">
                                        Xóa
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                Không tìm thấy danh mục nào.
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
        </div>

        <!-- Pagination -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($categories->hasPages()): ?>
            <div class="p-6 border-t border-slate-200 bg-slate-50/50">
                <?php echo e($categories->links(data: ['scrollTo' => '#admin-category-list'])); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </section>

    <!-- Create/Edit Form Modal -->
    <div x-show="showFormModal" 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" 
         style="display: none;"
         x-transition>
        <div class="bg-white rounded-3xl max-w-lg w-full border border-slate-200 shadow-2xl p-6 space-y-6" @click.away="showFormModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-lg font-bold text-slate-900">
                    <?php echo e($categoryId ? 'Cập nhật danh mục' : 'Thêm danh mục tài liệu mới'); ?>

                </h3>
                <button @click="showFormModal = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>
            
            <form wire:submit.prevent="saveCategory" class="space-y-4">
                <!-- Name -->
                <div class="space-y-1">
                    <label for="cat-name" class="block text-sm font-semibold text-slate-700">Tên danh mục <span class="text-red-500">*</span></label>
                    <input id="cat-name"
                           type="text" 
                           wire:model.live="name"
                           placeholder="Ví dụ: Lập trình PHP, Thiết kế đồ họa..." 
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none" />
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-xs text-red-500 font-medium"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Sort Order -->
                <div class="space-y-1">
                    <label for="cat-sort-order" class="block text-sm font-semibold text-slate-700">Thứ tự sắp xếp ưu tiên</label>
                    <input id="cat-sort-order"
                           type="number" 
                           wire:model="sort_order"
                           min="0"
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none" />
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['sort_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-xs text-red-500 font-medium"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Description -->
                <div class="space-y-1">
                    <label for="cat-description" class="block text-sm font-semibold text-slate-700">Mô tả danh mục</label>
                    <textarea id="cat-description"
                              wire:model="description" 
                              rows="3" 
                              placeholder="Mô tả tóm tắt về các tài liệu thuộc danh mục này..."
                              class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:outline-none"></textarea>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-xs text-red-500 font-medium"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Active Toggle -->
                <div class="flex items-center gap-3 py-2">
                    <input id="cat-is-active"
                           type="checkbox" 
                           wire:model="is_active" 
                           class="h-4.5 w-4.5 rounded border-slate-300 text-slate-900 focus:ring-slate-500" />
                    <label for="cat-is-active" class="text-sm font-semibold text-slate-700 cursor-pointer">Kích hoạt hoạt động ngay</label>
                </div>

                <!-- Submit buttons -->
                <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="showFormModal = false" class="rounded-xl border border-slate-200 hover:bg-slate-50 px-4 py-2.5 text-xs font-semibold text-slate-700 transition-colors">
                        Hủy bỏ
                    </button>
                    <button type="submit" class="rounded-xl bg-slate-900 hover:bg-slate-800 text-white px-5 py-2.5 text-xs font-semibold shadow-sm transition-colors">
                        Xác nhận lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH D:\IT-Learning\Modules/Document\resources/views/livewire/admin/category-list.blade.php ENDPATH**/ ?>