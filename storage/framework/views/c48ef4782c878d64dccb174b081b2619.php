<header class="border-b border-indigo-50/80 bg-white/80 px-4 py-4 shadow-sm sm:px-6 lg:px-8 backdrop-blur-md font-sans text-slate-850 sticky top-0 z-30">
    <?php
        $activeUser = auth()->user() ?? \Modules\Auth\Models\User::whereHas('roles', function($q) {
            $q->where('name', 'contributor');
        })->first() ?? \Modules\Auth\Models\User::first();
    ?>
    <div class="flex flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <!-- Mobile Menu Button -->
            <button @click="sidebarHidden = false" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-600 shadow-sm lg:hidden" aria-label="Open sidebar">
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>
            
            <!-- Hamburger Button (Shows when sidebar is hidden) -->
            <button @click="sidebarHidden = false" 
                    x-show="sidebarHidden"
                    class="hidden lg:flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-md hover:bg-indigo-700 transition-all duration-200"
                    aria-label="Show sidebar">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            
            <!-- Logo and Branding -->
            <a href="<?php echo e(route('contributor.dashboard')); ?>" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                <img src="<?php echo e(asset('Image/logo.png')); ?>" alt="IT Learning Logo" class="h-14 w-14 rounded-2xl object-cover">
                <div>
                    <p class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-indigo-600 whitespace-nowrap">Kênh Đăng Tải</p>
                    <p class="text-base font-black text-slate-900 tracking-tight whitespace-nowrap">IT Learning</p>
                </div>
            </a>
        </div>

        <div class="flex items-center">
            <?php if (isset($component)) { $__componentOriginal18750b693f5654ce36c0da9097c948ab = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18750b693f5654ce36c0da9097c948ab = $attributes; } ?>
<?php $component = WireUi\Components\Dropdown\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Dropdown\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                 <?php $__env->slot('trigger', null, []); ?> 
                    <button class="flex items-center gap-3 rounded-3xl border border-slate-200 bg-slate-50/60 px-3 py-2 text-left focus:outline-none hover:bg-slate-100/50 transition-all">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeUser?->avatar): ?>
                            <img src="<?php echo e($activeUser->avatar); ?>" alt="<?php echo e($activeUser->name); ?>" class="h-10 w-10 rounded-full object-cover shadow-md shrink-0">
                        <?php else: ?>
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-200 text-slate-600 font-semibold shadow-md shrink-0">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div class="min-w-0 hidden md:block">
                            <p class="text-sm font-semibold text-slate-800 truncate"><?php echo e($activeUser?->name ?? 'Contributor'); ?></p>
                        </div>
                        <?php if (isset($component)) { $__componentOriginal8fb227d09011c9831b75a18671cea295 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8fb227d09011c9831b75a18671cea295 = $attributes; } ?>
<?php $component = WireUi\Components\Icon\Index::resolve(['name' => 'chevron-down'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Icon\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 text-slate-450 shrink-0']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8fb227d09011c9831b75a18671cea295)): ?>
<?php $attributes = $__attributesOriginal8fb227d09011c9831b75a18671cea295; ?>
<?php unset($__attributesOriginal8fb227d09011c9831b75a18671cea295); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8fb227d09011c9831b75a18671cea295)): ?>
<?php $component = $__componentOriginal8fb227d09011c9831b75a18671cea295; ?>
<?php unset($__componentOriginal8fb227d09011c9831b75a18671cea295); ?>
<?php endif; ?>
                    </button>
                 <?php $__env->endSlot(); ?>

                <?php if (isset($component)) { $__componentOriginal0e0c733fc4b7d84791e38fa8e7a5bb13 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0e0c733fc4b7d84791e38fa8e7a5bb13 = $attributes; } ?>
<?php $component = WireUi\Components\Dropdown\Item::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Dropdown\Item::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('contributor.transactions')).'','label' => 'Lịch sử giao dịch']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                     <?php $__env->slot('prepend', null, []); ?> 
                        <?php if (isset($component)) { $__componentOriginal8fb227d09011c9831b75a18671cea295 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8fb227d09011c9831b75a18671cea295 = $attributes; } ?>
<?php $component = WireUi\Components\Icon\Index::resolve(['name' => 'document-text'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Icon\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 mr-2']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8fb227d09011c9831b75a18671cea295)): ?>
<?php $attributes = $__attributesOriginal8fb227d09011c9831b75a18671cea295; ?>
<?php unset($__attributesOriginal8fb227d09011c9831b75a18671cea295); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8fb227d09011c9831b75a18671cea295)): ?>
<?php $component = $__componentOriginal8fb227d09011c9831b75a18671cea295; ?>
<?php unset($__componentOriginal8fb227d09011c9831b75a18671cea295); ?>
<?php endif; ?>
                     <?php $__env->endSlot(); ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0e0c733fc4b7d84791e38fa8e7a5bb13)): ?>
<?php $attributes = $__attributesOriginal0e0c733fc4b7d84791e38fa8e7a5bb13; ?>
<?php unset($__attributesOriginal0e0c733fc4b7d84791e38fa8e7a5bb13); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0e0c733fc4b7d84791e38fa8e7a5bb13)): ?>
<?php $component = $__componentOriginal0e0c733fc4b7d84791e38fa8e7a5bb13; ?>
<?php unset($__componentOriginal0e0c733fc4b7d84791e38fa8e7a5bb13); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal0e0c733fc4b7d84791e38fa8e7a5bb13 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0e0c733fc4b7d84791e38fa8e7a5bb13 = $attributes; } ?>
<?php $component = WireUi\Components\Dropdown\Item::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Dropdown\Item::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('contributor.payout-request')).'','label' => 'Ví']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                     <?php $__env->slot('prepend', null, []); ?> 
                        <?php if (isset($component)) { $__componentOriginal8fb227d09011c9831b75a18671cea295 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8fb227d09011c9831b75a18671cea295 = $attributes; } ?>
<?php $component = WireUi\Components\Icon\Index::resolve(['name' => 'banknotes'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Icon\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 mr-2']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8fb227d09011c9831b75a18671cea295)): ?>
<?php $attributes = $__attributesOriginal8fb227d09011c9831b75a18671cea295; ?>
<?php unset($__attributesOriginal8fb227d09011c9831b75a18671cea295); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8fb227d09011c9831b75a18671cea295)): ?>
<?php $component = $__componentOriginal8fb227d09011c9831b75a18671cea295; ?>
<?php unset($__componentOriginal8fb227d09011c9831b75a18671cea295); ?>
<?php endif; ?>
                     <?php $__env->endSlot(); ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0e0c733fc4b7d84791e38fa8e7a5bb13)): ?>
<?php $attributes = $__attributesOriginal0e0c733fc4b7d84791e38fa8e7a5bb13; ?>
<?php unset($__attributesOriginal0e0c733fc4b7d84791e38fa8e7a5bb13); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0e0c733fc4b7d84791e38fa8e7a5bb13)): ?>
<?php $component = $__componentOriginal0e0c733fc4b7d84791e38fa8e7a5bb13; ?>
<?php unset($__componentOriginal0e0c733fc4b7d84791e38fa8e7a5bb13); ?>
<?php endif; ?>

                <div class="border-t border-slate-100 my-1"></div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user() && auth()->user()->hasRole('admin')): ?>
                    <?php if (isset($component)) { $__componentOriginal0e0c733fc4b7d84791e38fa8e7a5bb13 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0e0c733fc4b7d84791e38fa8e7a5bb13 = $attributes; } ?>
<?php $component = WireUi\Components\Dropdown\Item::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Dropdown\Item::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.dashboard')).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                        <div class="flex items-center text-amber-600 font-semibold">
                            <?php if (isset($component)) { $__componentOriginal8fb227d09011c9831b75a18671cea295 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8fb227d09011c9831b75a18671cea295 = $attributes; } ?>
<?php $component = WireUi\Components\Icon\Index::resolve(['name' => 'shield-check'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Icon\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 mr-2']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8fb227d09011c9831b75a18671cea295)): ?>
<?php $attributes = $__attributesOriginal8fb227d09011c9831b75a18671cea295; ?>
<?php unset($__attributesOriginal8fb227d09011c9831b75a18671cea295); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8fb227d09011c9831b75a18671cea295)): ?>
<?php $component = $__componentOriginal8fb227d09011c9831b75a18671cea295; ?>
<?php unset($__componentOriginal8fb227d09011c9831b75a18671cea295); ?>
<?php endif; ?>
                            <span>Trang quản trị</span>
                        </div>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0e0c733fc4b7d84791e38fa8e7a5bb13)): ?>
<?php $attributes = $__attributesOriginal0e0c733fc4b7d84791e38fa8e7a5bb13; ?>
<?php unset($__attributesOriginal0e0c733fc4b7d84791e38fa8e7a5bb13); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0e0c733fc4b7d84791e38fa8e7a5bb13)): ?>
<?php $component = $__componentOriginal0e0c733fc4b7d84791e38fa8e7a5bb13; ?>
<?php unset($__componentOriginal0e0c733fc4b7d84791e38fa8e7a5bb13); ?>
<?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <form method="POST" action="<?php echo e(route('auth.logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php if (isset($component)) { $__componentOriginal0e0c733fc4b7d84791e38fa8e7a5bb13 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0e0c733fc4b7d84791e38fa8e7a5bb13 = $attributes; } ?>
<?php $component = WireUi\Components\Dropdown\Item::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Dropdown\Item::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Đăng xuất','onclick' => 'event.preventDefault(); this.closest(\'form\').submit();']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                         <?php $__env->slot('prepend', null, []); ?> 
                            <?php if (isset($component)) { $__componentOriginal8fb227d09011c9831b75a18671cea295 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8fb227d09011c9831b75a18671cea295 = $attributes; } ?>
<?php $component = WireUi\Components\Icon\Index::resolve(['name' => 'arrow-right-on-rectangle'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Icon\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4 mr-2']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8fb227d09011c9831b75a18671cea295)): ?>
<?php $attributes = $__attributesOriginal8fb227d09011c9831b75a18671cea295; ?>
<?php unset($__attributesOriginal8fb227d09011c9831b75a18671cea295); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8fb227d09011c9831b75a18671cea295)): ?>
<?php $component = $__componentOriginal8fb227d09011c9831b75a18671cea295; ?>
<?php unset($__componentOriginal8fb227d09011c9831b75a18671cea295); ?>
<?php endif; ?>
                         <?php $__env->endSlot(); ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0e0c733fc4b7d84791e38fa8e7a5bb13)): ?>
<?php $attributes = $__attributesOriginal0e0c733fc4b7d84791e38fa8e7a5bb13; ?>
<?php unset($__attributesOriginal0e0c733fc4b7d84791e38fa8e7a5bb13); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0e0c733fc4b7d84791e38fa8e7a5bb13)): ?>
<?php $component = $__componentOriginal0e0c733fc4b7d84791e38fa8e7a5bb13; ?>
<?php unset($__componentOriginal0e0c733fc4b7d84791e38fa8e7a5bb13); ?>
<?php endif; ?>
                </form>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18750b693f5654ce36c0da9097c948ab)): ?>
<?php $attributes = $__attributesOriginal18750b693f5654ce36c0da9097c948ab; ?>
<?php unset($__attributesOriginal18750b693f5654ce36c0da9097c948ab); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18750b693f5654ce36c0da9097c948ab)): ?>
<?php $component = $__componentOriginal18750b693f5654ce36c0da9097c948ab; ?>
<?php unset($__componentOriginal18750b693f5654ce36c0da9097c948ab); ?>
<?php endif; ?>
        </div>
    </div>
</header>
<?php /**PATH D:\IT-Learning\resources\views/layouts/patials/contributor_header.blade.php ENDPATH**/ ?>