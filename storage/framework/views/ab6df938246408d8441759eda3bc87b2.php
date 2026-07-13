<input
    <?php echo e($attributes->merge([
        'type'         => 'text',
        'autocomplete' => 'off',
        'placeholder'  => ' ',
    ])->class([
        'bg-transparent block w-full border-0 text-gray-900 dark:text-gray-400',
        'p-0 outline-none outline-hidden ring-0 sm:text-sm sm:leading-6',
        'focus:ring-0 focus:border-0',
        'placeholder:text-gray-400 dark:placeholder:text-gray-500',
        'invalidated:text-negative-800 invalidated:dark:text-negative-600',
        'invalidated:placeholder-negative-400 invalidated:dark:placeholder-negative-600/70',
    ])); ?>

/>
<?php /**PATH D:\IT-Learning\vendor\wireui\wireui\src/Components/Wrapper/views/components/element.blade.php ENDPATH**/ ?>