<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'Contributor'); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="<?php echo e(asset('Image/logo.png')); ?>" type="image/png">
    
    <?php echo app('Illuminate\Foundation\Vite')([
        'resources/css/app.css',
        'resources/js/app.js'
    ]); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>

<body class="min-h-screen bg-gradient-to-br from-[#f3f5fc] via-white to-[#f9f8ff] text-slate-700 antialiased font-sans">
    <div x-data="{ sidebarHidden: true }" class="flex min-h-screen">
        <?php echo $__env->make('layouts.patials.contributor_sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="flex-1 flex flex-col min-w-0">
            <?php echo $__env->make('layouts.patials.contributor_header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                <div class="mx-auto w-full max-w-7xl">
                    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-2xl font-black tracking-tight text-slate-800">
                                <?php echo e($pageTitle ?? ''); ?>

                            </h1>
                        </div>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                        <div class="mb-6 rounded-2xl border border-emerald-100 bg-emerald-50/80 backdrop-blur-md px-4 py-4 text-emerald-800 shadow-md">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                        <div class="mb-6 rounded-2xl border border-rose-100 bg-rose-50/80 backdrop-blur-md px-4 py-4 text-rose-800 shadow-md">
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php echo $__env->yieldContent('content'); ?>
                    <?php echo e($slot ?? ''); ?>

                </div>
            </main>

            <?php echo $__env->make('layouts.patials.contributor_footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
    <?php echo WireUi::directives()->scripts(attributes: []); ?>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>

</html>
<?php /**PATH D:\IT-Learning\resources\views/layouts/contributor.blade.php ENDPATH**/ ?>