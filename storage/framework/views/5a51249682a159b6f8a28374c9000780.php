<?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => WireUi::component('text-field')] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => 'none','config' => $config,'attributes' => $wrapper,'with-error-icon' => false]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <?php echo $__env->make('wireui-wrapper::components.slots', [
        'except' => ['prepend', 'append'],
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <textarea
        <?php echo e($input
            ->merge([
                'type' => 'text',
                'autocomplete' => 'off',
                'placeholder' => ' ',
                'rows' => $rows,
                'cols' => $cols,
            ])
            ->class([
                'bg-transparent block !border-0 border-0! text-gray-900 dark:text-gray-400',
                'pl-3 pr-2.5 py-2 !outline-0 outline-0! !ring-0 ring-0! sm:text-sm sm:leading-normal',
                'placeholder:text-gray-400 dark:placeholder:text-gray-500',
                'invalidated:text-negative-800 invalidated:dark:text-negative-600',
                'invalidated:placeholder-negative-400 invalidated:dark:placeholder-negative-600/70',
                'w-full' => $cols === 'auto'
            ])); ?>

    ><?php echo e($slot); ?></textarea>
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
<?php /**PATH D:\IT-Learning\vendor\wireui\wireui\src/Components/TextField/views/textarea.blade.php ENDPATH**/ ?>