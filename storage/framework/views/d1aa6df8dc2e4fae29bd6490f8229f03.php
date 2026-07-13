<div
    x-data="wireui_dropdown"
    x-props="{
        position: '<?php echo e($position); ?>',
    }"
    class="relative inline-block text-left"
    <?php echo e($attributes->only('wire:key')); ?>

>
    <div
        x-ref="triggerContainer"
        x-on:click="positionable.openIfClosed()"
        class="cursor-pointer focus:outline-none focus:outline-hidden"
    >
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($trigger)): ?>
            <?php echo e($trigger); ?>

        <?php else: ?>
            <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => WireUi::component('icon')] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => $icon,'class' => \Illuminate\Support\Arr::toCssClasses([
                    'dark:hover:text-secondary-600 transition duration-150 ease-in-out',
                    'w-4 h-4 text-secondary-500 hover:text-secondary-700',
                ])]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $attributes = $__attributesOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $component = $__componentOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__componentOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div
        x-ref="popover"
        x-show="positionable.state"
        x-on:click.outside="positionable.close()"
        x-on:keydown.escape.window="positionable.close()"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        <?php echo e($attributes->except('wire:key')->class([
            'z-30 absolute',
            'transition-all transform',
            $widthClasses,
        ])); ?>

        style="display: none;"
        <?php if (! ($persistent)): ?> x-on:click="positionable.close()" <?php endif; ?>
    >
        <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
            'soft-scrollbar overflow-auto' => $height !== WireUi\Enum\Packs\Height::AUTO,
            'shadow-lg p-1 bg-white dark:bg-secondary-800 dark:border-secondary-600',
            'relative border border-secondary-200 rounded-lg',
            $heightClasses,
        ]); ?>">
            <?php echo e($slot); ?>

        </div>
    </div>
</div>
<?php /**PATH D:\IT-Learning\vendor\wireui\wireui\src/Components/Dropdown/views/base.blade.php ENDPATH**/ ?>