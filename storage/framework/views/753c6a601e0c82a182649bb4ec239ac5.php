<div>

    <?php if (isset($component)) { $__componentOriginal3dde83133891f87f89e964628fb558b6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3dde83133891f87f89e964628fb558b6 = $attributes; } ?>
<?php $component = WireUi\Components\Notifications\Index::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('notifications'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Notifications\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['z-index' => 'z-50']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3dde83133891f87f89e964628fb558b6)): ?>
<?php $attributes = $__attributesOriginal3dde83133891f87f89e964628fb558b6; ?>
<?php unset($__attributesOriginal3dde83133891f87f89e964628fb558b6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3dde83133891f87f89e964628fb558b6)): ?>
<?php $component = $__componentOriginal3dde83133891f87f89e964628fb558b6; ?>
<?php unset($__componentOriginal3dde83133891f87f89e964628fb558b6); ?>
<?php endif; ?>

    <div
        x-data="{ showLockModal: <?php if ((object) ('showLockModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showLockModal'->value()); ?>')<?php echo e('showLockModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showLockModal'); ?>')<?php endif; ?>, showUnlockModal: <?php if ((object) ('showUnlockModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showUnlockModal'->value()); ?>')<?php echo e('showUnlockModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showUnlockModal'); ?>')<?php endif; ?>, showDetailModal: <?php if ((object) ('showDetailModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showDetailModal'->value()); ?>')<?php echo e('showDetailModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showDetailModal'); ?>')<?php endif; ?>, showUserModal: <?php if ((object) ('showUserModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showUserModal'->value()); ?>')<?php echo e('showUserModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showUserModal'); ?>')<?php endif; ?> }">
        
        

        
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Quản lý người dùng</h1>
                <p class="mt-1 text-sm text-slate-600">Quản lý tài khoản và phân quyền người dùng trong hệ thống</p>
            </div>
            <button wire:click="openCreateModal" 
                    class="rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <span class="flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Thêm người dùng
                </span>
            </button>
        </div>

        
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
            
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Tổng user</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900"><?php echo e(number_format($statistics['total'])); ?></p>
                    </div>
                    <div class="rounded-lg bg-blue-100 p-3">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Hoạt động</p>
                        <p class="mt-1 text-2xl font-bold text-emerald-600"><?php echo e(number_format($statistics['active'])); ?>

                        </p>
                    </div>
                    <div class="rounded-lg bg-emerald-100 p-3">
                        <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Bị khóa</p>
                        <p class="mt-1 text-2xl font-bold text-rose-600"><?php echo e(number_format($statistics['blocked'])); ?></p>
                    </div>
                    <div class="rounded-lg bg-rose-100 p-3">
                        <svg class="h-6 w-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                </div>
            </div>

            
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Admin</p>
                        <p class="mt-1 text-2xl font-bold text-purple-600"><?php echo e(number_format($statistics['admin'])); ?></p>
                    </div>
                    <div class="rounded-lg bg-purple-100 p-3">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
            </div>

            
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Student</p>
                        <p class="mt-1 text-2xl font-bold text-cyan-600"><?php echo e(number_format($statistics['student'])); ?></p>
                    </div>
                    <div class="rounded-lg bg-cyan-100 p-3">
                        <svg class="h-6 w-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
            </div>

            
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Contributor</p>
                        <p class="mt-1 text-2xl font-bold text-amber-600">
                            <?php echo e(number_format($statistics['contributor'])); ?>

                        </p>
                    </div>
                    <div class="rounded-lg bg-amber-100 p-3">
                        <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="mb-6 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                
                <div class="md:col-span-2">
                    <label for="search" class="mb-2 block text-sm font-medium text-slate-700">Tìm kiếm</label>
                    <input type="text" id="search" wire:model.live.debounce.300ms="search"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Tìm theo tên hoặc email...">
                </div>

                
                <div>
                    <label for="roleFilter" class="mb-2 block text-sm font-medium text-slate-700">Vai trò</label>
                    <select id="roleFilter" wire:model.live="roleFilter"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tất cả vai trò</option>
                        <option value="admin">Admin</option>
                        <option value="student">Student</option>
                        <option value="contributor">Contributor</option>
                    </select>
                </div>

                
                <div>
                    <label for="statusFilter" class="mb-2 block text-sm font-medium text-slate-700">Trạng thái</label>
                    <select id="statusFilter" wire:model.live="statusFilter"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tất cả trạng thái</option>
                        <option value="active">Hoạt động</option>
                        <option value="blocked">Bị khóa</option>
                    </select>
                </div>
            </div>
        </div>

        
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700">
                                Avatar</th>
                            <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700"
                                wire:click="sortBy('name')">
                                <div class="flex items-center gap-1">
                                    Tên
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'name'): ?>
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortDirection === 'asc'): ?>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 15l7-7 7 7" />
                                            <?php else: ?>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </svg>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </th>
                            <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700"
                                wire:click="sortBy('email')">
                                <div class="flex items-center gap-1">
                                    Email
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'email'): ?>
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortDirection === 'asc'): ?>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 15l7-7 7 7" />
                                            <?php else: ?>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </svg>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700">
                                Vai trò</th>
                            <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700"
                                wire:click="sortBy('created_at')">
                                <div class="flex items-center gap-1">
                                    Ngày tham gia
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'created_at'): ?>
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortDirection === 'asc'): ?>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 15l7-7 7 7" />
                                            <?php else: ?>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </svg>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700">
                                Trạng thái</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-slate-700">
                                Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <tr class="hover:bg-slate-50">
                                
                                <td class="px-6 py-4">
                                    <img src="<?php echo e($user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name)); ?>"
                                        alt="<?php echo e($user->name); ?>" class="h-10 w-10 rounded-full object-cover">
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-slate-900"><?php echo e($user->name); ?></div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-600"><?php echo e($user->email); ?></div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold
                                                            <?php if($role->name === 'admin'): ?> bg-purple-100 text-purple-800
                                                            <?php elseif($role->name === 'student'): ?> bg-cyan-100 text-cyan-800
                                                            <?php elseif($role->name === 'contributor'): ?> bg-amber-100 text-amber-800
                                                            <?php else: ?> bg-slate-100 text-slate-800
                                                            <?php endif; ?>">
                                                <?php echo e(ucfirst($role->name)); ?>

                                            </span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-600"><?php echo e($user->created_at->format('d/m/Y')); ?></div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->status === 'active'): ?>
                                        <span
                                            class="inline-flex rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-800">Hoạt
                                            động</span>
                                    <?php else: ?>
                                        <span
                                            class="inline-flex rounded-full bg-rose-100 px-2 py-1 text-xs font-semibold text-rose-800">Bị
                                            khóa</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button wire:click="viewDetail(<?php echo e($user->id); ?>)"
                                            class="rounded-lg bg-blue-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-600">
                                            Chi tiết
                                        </button>
                                        <button wire:click="openEditModal(<?php echo e($user->id); ?>)"
                                            class="rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-amber-600">
                                            Sửa
                                        </button>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->status === 'active'): ?>
                                            <button wire:click="openLockModal(<?php echo e($user->id); ?>)"
                                                class="rounded-lg bg-rose-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-rose-600">
                                                Khóa
                                            </button>
                                        <?php else: ?>
                                            <button wire:click="openUnlockModal(<?php echo e($user->id); ?>)"
                                                class="rounded-lg bg-emerald-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-emerald-600">
                                                Mở khóa
                                            </button>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-slate-400">
                                        <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p class="mt-2 text-sm">Không tìm thấy người dùng nào</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>

            
            <div class="border-t border-slate-200 bg-white px-6 py-4">
                <?php echo e($users->links()); ?>

            </div>
        </div>

        
        <div x-show="showLockModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50"
            @click.self="showLockModal = false">
            <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl" @click.away="showLockModal = false">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">Khóa tài khoản</h3>
                    <button @click="showLockModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mb-4">
                    <label for="blockReason" class="mb-2 block text-sm font-medium text-slate-700">
                        Lý do khóa <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="blockReason" wire:model="blockReason" rows="4"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Nhập lý do khóa tài khoản..."></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button @click="showLockModal = false"
                        class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        Hủy
                    </button>
                    <button wire:click="confirmLock"
                        class="rounded-lg bg-rose-500 px-4 py-2 text-sm font-medium text-white hover:bg-rose-600">
                        Xác nhận khóa
                    </button>
                </div>
            </div>
        </div>

        
        <div x-show="showUnlockModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50"
            @click.self="showUnlockModal = false">
            <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl" @click.away="showUnlockModal = false">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">Mở khóa tài khoản</h3>
                    <button @click="showUnlockModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <p class="mb-6 text-sm text-slate-600">
                    Bạn có chắc chắn muốn mở khóa tài khoản này không?
                </p>

                <div class="flex justify-end gap-3">
                    <button @click="showUnlockModal = false"
                        class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        Hủy
                    </button>
                    <button wire:click="confirmUnlock"
                        class="rounded-lg bg-emerald-500 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-600">
                        Xác nhận mở khóa
                    </button>
                </div>
            </div>
        </div>

        
        <div x-show="showDetailModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50"
            @click.self="showDetailModal = false">
            <div class="w-full max-w-2xl rounded-xl bg-white p-6 shadow-xl" @click.away="showDetailModal = false">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">Chi tiết người dùng</h3>
                    <button @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedUser): ?>
                    <div class="space-y-6">
                        
                        <div class="flex items-center gap-4">
                            <img src="<?php echo e($selectedUser->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($selectedUser->name)); ?>"
                                alt="<?php echo e($selectedUser->name); ?>" class="h-20 w-20 rounded-full object-cover">
                            <div>
                                <h4 class="text-xl font-semibold text-slate-900"><?php echo e($selectedUser->name); ?></h4>
                                <p class="text-sm text-slate-600"><?php echo e($selectedUser->email); ?></p>
                            </div>
                        </div>

                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-slate-700">Số điện thoại</p>
                                <p class="mt-1 text-sm text-slate-900"><?php echo e($selectedUser->phone ?? 'Chưa cập nhật'); ?></p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-700">Ngày tham gia</p>
                                <p class="mt-1 text-sm text-slate-900"><?php echo e($selectedUser->created_at->format('d/m/Y H:i')); ?>

                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-700">Vai trò</p>
                                <div class="mt-1 flex flex-wrap gap-1">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $selectedUser->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold
                                                        <?php if($role->name === 'admin'): ?> bg-purple-100 text-purple-800
                                                        <?php elseif($role->name === 'student'): ?> bg-cyan-100 text-cyan-800
                                                        <?php elseif($role->name === 'contributor'): ?> bg-amber-100 text-amber-800
                                                        <?php else: ?> bg-slate-100 text-slate-800
                                                        <?php endif; ?>">
                                            <?php echo e(ucfirst($role->name)); ?>

                                        </span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-700">Trạng thái</p>
                                <p class="mt-1">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedUser->status === 'active'): ?>
                                        <span
                                            class="inline-flex rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-800">Hoạt
                                            động</span>
                                    <?php else: ?>
                                        <span
                                            class="inline-flex rounded-full bg-rose-100 px-2 py-1 text-xs font-semibold text-rose-800">Bị
                                            khóa</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </p>
                            </div>
                        </div>

                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedUser->bio): ?>
                            <div>
                                <p class="text-sm font-medium text-slate-700">Giới thiệu</p>
                                <p class="mt-1 text-sm text-slate-900"><?php echo e($selectedUser->bio); ?></p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedUser->status === 'blocked'): ?>
                            <div class="rounded-lg border border-rose-200 bg-rose-50 p-4">
                                <h5 class="mb-2 font-semibold text-rose-900">Thông tin khóa</h5>
                                <div class="space-y-2 text-sm">
                                    <div>
                                        <span class="font-medium text-rose-700">Lý do:</span>
                                        <span class="text-rose-900"><?php echo e($selectedUser->blocked_reason); ?></span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-rose-700">Thời gian:</span>
                                        <span
                                            class="text-rose-900"><?php echo e($selectedUser->blocked_at ? $selectedUser->blocked_at->format('d/m/Y H:i') : 'N/A'); ?></span>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedUser->blocked_by): ?>
                                        <div>
                                            <span class="font-medium text-rose-700">Người thực hiện:</span>
                                            <span class="text-rose-900"><?php echo e($selectedUser->blocker->name ?? 'N/A'); ?>

                                                (<?php echo e($selectedUser->blocker->email ?? ''); ?>)</span>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedUser->vip_expires_at): ?>
                            <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                                <h5 class="mb-2 font-semibold text-amber-900">Thông tin VIP</h5>
                                <div class="space-y-2 text-sm">
                                    <div>
                                        <span class="font-medium text-amber-700">Hết hạn:</span>
                                        <span
                                            class="text-amber-900"><?php echo e($selectedUser->vip_expires_at->format('d/m/Y H:i')); ?></span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-amber-700">Hạn mức tải:</span>
                                        <span class="text-amber-900"><?php echo e($selectedUser->vip_download_quota ?? 0); ?> file</span>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        
        <div x-show="showUserModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50"
            @click.self="$wire.closeUserModal()">
            <div class="w-full max-w-2xl rounded-xl bg-white p-6 shadow-xl" @click.away="$wire.closeUserModal()">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">
                        <?php echo e($modalMode === 'create' ? 'Thêm người dùng mới' : 'Cập nhật người dùng'); ?>

                    </h3>
                    <button @click="$wire.closeUserModal()" class="text-slate-400 hover:text-slate-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    
                    <div>
                        <label for="userName" class="mb-2 block text-sm font-medium text-slate-700">
                            Tên người dùng <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="userName" wire:model="form.name"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Nhập tên người dùng...">
                    </div>

                    
                    <div>
                        <label for="userEmail" class="mb-2 block text-sm font-medium text-slate-700">
                            Email <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" id="userEmail" wire:model="form.email"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="example@email.com">
                    </div>

                    
                    <div>
                        <label for="userPassword" class="mb-2 block text-sm font-medium text-slate-700">
                            Mật khẩu 
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($modalMode === 'create'): ?>
                                <span class="text-rose-500">* (Bắt buộc cho Admin)</span>
                            <?php else: ?>
                                <span class="text-slate-500">(Để trống nếu không đổi)</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </label>
                        <input type="password" id="userPassword" wire:model="form.password"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="••••••••">
                        <p class="mt-1 text-xs text-slate-500">
                            Lưu ý: Role Student/Contributor có thể đăng nhập qua Google (không cần mật khẩu)
                        </p>
                    </div>

                    
                    <div>
                        <label for="userPhone" class="mb-2 block text-sm font-medium text-slate-700">
                            Số điện thoại
                        </label>
                        <input type="text" id="userPhone" wire:model="form.phone"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="0912345678">
                    </div>

                    
                    <div>
                        <label for="userBio" class="mb-2 block text-sm font-medium text-slate-700">
                            Giới thiệu
                        </label>
                        <textarea id="userBio" wire:model="form.bio" rows="3"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Giới thiệu ngắn về người dùng..."></textarea>
                    </div>

                    
                    <div>
                        <label for="userRoles" class="mb-2 block text-sm font-medium text-slate-700">
                            Vai trò <span class="text-rose-500">* (Chọn ít nhất 1)</span>
                        </label>
                        <select id="userRoles" wire:model="form.roles"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Chọn vai trò --</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <option value="<?php echo e($role->name); ?>"><?php echo e(ucfirst($role->name)); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button @click="$wire.closeUserModal()"
                        class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        Hủy
                    </button>
                    <button wire:click="saveUser"
                        class="rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white hover:bg-blue-600">
                        <?php echo e($modalMode === 'create' ? 'Tạo người dùng' : 'Cập nhật'); ?>

                    </button>
                </div>
            </div>
        </div>

        <?php $__env->startPush('scripts'); ?>
            <script>
                // Livewire event listeners for notifications
                document.addEventListener('livewire:initialized', () => {
                    Livewire.on('show-success', (event) => {
                        window.$wireui.notify({
                            title: 'Thành công!',
                            description: event.message,
                            icon: 'success'
                        });
                    });

                    Livewire.on('show-error', (event) => {
                        window.$wireui.notify({
                            title: 'Lỗi!',
                            description: event.message,
                            icon: 'error'
                        });
                    });
                });
            </script>
        <?php $__env->stopPush(); ?>

        <?php $__env->startPush('styles'); ?>
            <style>
                [x-cloak] {
                    display: none !important;
                }
            </style>
        <?php $__env->stopPush(); ?>
    </div>
</div><?php /**PATH D:\IT-Learning\Modules/Auth\resources/views/livewire/admin/user-management.blade.php ENDPATH**/ ?>