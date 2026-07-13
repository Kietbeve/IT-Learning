<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['margin' => false, 'rootClass' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['margin' => false, 'rootClass' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div
    x-cloak
    style="display: none;"
    :style="positionable.styles"
    x-show="positionable.state"
    x-ref="popover"
    x-on:click.outside.prevent="positionable.close()"
    x-on:keydown.escape.window="positionable.handleEscape()"
    class="<?php echo \Illuminate\Support\Arr::toCssClasses([
        'fixed inset-0 z-20 flex sm:w-full sm:justify-end items-end sm:z-10 sm:absolute sm:inset-auto',
        'pointer-events-none transition-all ease-linear duration-150',
        'sm:top-0 sm:right-0',
        $rootClass,
    ]); ?>"
>
    <div
        x-show="positionable.state"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-on:click="positionable.close()"
        aria-hidden="true"
        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
            'fixed inset-0 transition-opacity bg-secondary-400/60 sm:hidden',
            'pointer-events-auto dark:bg-secondary-700/60',
        ]); ?>"
    ></div>

    <div
        x-show="positionable.state"
        tabindex="-1"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        <?php echo e($attributes->class([
            'w-full rounded-t-md sm:rounded-xl border border-secondary-200 bg-white shadow-lg',
            'dark:bg-secondary-800 dark:border-secondary-600 transition-all relative overflow-hidden',
            'pointer-events-auto',
        ])); ?>

    >
        <?php echo e($slot); ?>

    </div>
</div>
<?php /**PATH D:\IT-Learning\vendor\wireui\wireui\src/Components/Popover/views/index.blade.php ENDPATH**/ ?>