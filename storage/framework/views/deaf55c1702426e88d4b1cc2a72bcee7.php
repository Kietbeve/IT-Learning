<label <?php echo e($attributes->class([
    'block text-sm font-medium disabled:opacity-60',
    'text-gray-700 dark:text-gray-400',
    'invalidated:text-negative-600 dark:invalidated:text-negative-700',
])); ?>>
    <?php echo e($text ?? $slot); ?>

</label>
<?php /**PATH D:\IT-Learning\vendor\wireui\wireui\src/Components/Label/views/base.blade.php ENDPATH**/ ?>