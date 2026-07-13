<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-xl mx-auto p-4">
        <h1 class="text-2xl">user dashboard</h1>

        <p class="mt-2">Xin chào, <?php echo e(auth()->user()->name); ?></p>

        <form method="POST" action="<?php echo e(route('auth.logout')); ?>" class="mt-4">
            <?php echo csrf_field(); ?>
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Đăng xuất</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('auth::layouts.UserLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\IT-Learning\Modules/Auth\resources/views/dashboard.blade.php ENDPATH**/ ?>