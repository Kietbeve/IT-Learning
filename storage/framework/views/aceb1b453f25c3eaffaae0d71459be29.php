<?php extract((new \Illuminate\Support\Collection($attributes->getAttributes()))->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>

<?php if (isset($component)) { $__componentOriginal5f2e0d8aacbdae3f29d890683fd5d19c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5f2e0d8aacbdae3f29d890683fd5d19c = $attributes; } ?>
<?php $component = WireUi\Components\Popover\Index::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('popover'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Popover\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attributes)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>


<?php echo e($slot ?? ""); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5f2e0d8aacbdae3f29d890683fd5d19c)): ?>
<?php $attributes = $__attributesOriginal5f2e0d8aacbdae3f29d890683fd5d19c; ?>
<?php unset($__attributesOriginal5f2e0d8aacbdae3f29d890683fd5d19c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5f2e0d8aacbdae3f29d890683fd5d19c)): ?>
<?php $component = $__componentOriginal5f2e0d8aacbdae3f29d890683fd5d19c; ?>
<?php unset($__componentOriginal5f2e0d8aacbdae3f29d890683fd5d19c); ?>
<?php endif; ?><?php /**PATH D:\IT-Learning\storage\framework\views/49724c2d3992c0c5143ace743f7bdf1e.blade.php ENDPATH**/ ?>