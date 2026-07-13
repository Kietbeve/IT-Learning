<?php ($name = $name ?? $attributes->wire('model')->value()); ?>

<div x-data="wireui_modal({
        show: <?php echo json_encode($show, 15, 512) ?>,
        <?php if($attributes->wire('model')->value()): ?>
            model: <?php if ((object) ($attributes->wire('model')) instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e($attributes->wire('model')->value()); ?>')<?php echo e($attributes->wire('model')->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e($attributes->wire('model')); ?>')<?php endif; ?>
        <?php endif; ?>
    })"
    class="<?php echo \Illuminate\Support\Arr::toCssClasses([
        'soft-scrollbar' => data_get($typeClasses, 'soft-scrollbar', false),
        'hide-scrollbar' => data_get($typeClasses, 'hide-scrollbar', false),
        $zIndex  ?? data_get($typeClasses, 'z-index', 'z-40'),
        'fixed inset-0 overflow-y-auto',
    ]); ?>"
    <?php if (! ($persistent)): ?>
        x-on:keydown.escape.window="handleEscape"
    <?php endif; ?>
    x-on:keydown.tab.prevent="handleTab"
    x-on:keydown.shift.tab.prevent="handleShiftTab"
    x-on:open-wireui-modal:<?php echo e(Str::kebab($name)); ?>.window="open"
    x-on:close-wireui-modal:<?php echo e(Str::kebab($name)); ?>.window="close"
    <?php echo e($attributes
        ->whereDoesntStartWith('wire:model')
        ->whereStartsWith(['x-on:', '@', 'wire:'])); ?>

    style="display: none"
    x-cloak
    x-show="show"
    wireui-modal
>
    <div
        x-show="show"
        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
            'fixed inset-0 bg-secondary-400/60 dark:bg-secondary-700/60',
            'transform transition-opacity',
            $blurClasses => !$blurless,
        ]); ?>"
        <?php if (! ($persistent)): ?> x-on:click="close" <?php endif; ?>
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
    </div>

    <div
        x-show="show"
        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
            'w-full min-h-full transform flex items-end justify-center mx-auto',
            $spacing ?? data_get($typeClasses, 'spacing', 'p-4'),
            $alignClasses,
            $widthClasses,
        ]); ?>"
        <?php if (! ($persistent)): ?> x-on:click.self="close" <?php endif; ?>
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
        <?php echo e($slot); ?>

    </div>
</div>
<?php /**PATH D:\IT-Learning\vendor\wireui\wireui\src/Components/Modal/views/index.blade.php ENDPATH**/ ?>