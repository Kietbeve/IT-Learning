<?php $__env->startSection('title', 'Login'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-xl mx-auto p-4">
        <h1 class="text-2xl text-red-300 mb-4">Google Login</h1>

        <a href="<?php echo e(route('auth.google.redirect')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.54 0 6.06 1.53 7.46 2.81l5.47-5.43C34.98 4.07 29.9 2 24 2 14.98 2 7.62 7.9 4.6 15.8l6.62 5.14C12.92 16.1 18 9.5 24 9.5z"/><path fill="#4285F4" d="M46.5 24.5c0-1.58-.15-3.1-.43-4.57H24v9.07h12.7c-.55 2.97-2.36 5.48-5.03 7.15l7.76 6C43.8 38.43 46.5 31.9 46.5 24.5z"/><path fill="#FBBC05" d="M11.22 28.94A14.87 14.87 0 0 1 10 24.5c0-1.43.22-2.82.62-4.12L4 15.24A24.01 24.01 0 0 0 2 24.5c0 3.9.93 7.59 2.62 10.9l6.6-6.46z"/><path fill="#34A853" d="M24 46c6.64 0 12.24-2.2 16.32-5.96l-7.76-6C29.84 35.7 27.2 36.8 24 36.8c-5.98 0-11.02-4.1-12.8-9.7l-6.62 5.14C7.62 40.1 14.98 46 24 46z"/></svg>
            Đăng nhập bằng Google
        </a>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('auth::layouts.UserLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\IT-Learning\Modules/Auth\resources/views/login.blade.php ENDPATH**/ ?>