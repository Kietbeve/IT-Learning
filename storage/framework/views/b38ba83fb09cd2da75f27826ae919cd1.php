<?php extract((new \Illuminate\Support\Collection($attributes->getAttributes()))->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>

<?php if (isset($component)) { $__componentOriginalfd74c95b088f533f45bcdf250b1ea973 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfd74c95b088f533f45bcdf250b1ea973 = $attributes; } ?>
<?php $component = WireUi\Components\Modal\Index::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Modal\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attributes)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>


<?php echo e($slot ?? ""); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfd74c95b088f533f45bcdf250b1ea973)): ?>
<?php $attributes = $__attributesOriginalfd74c95b088f533f45bcdf250b1ea973; ?>
<?php unset($__attributesOriginalfd74c95b088f533f45bcdf250b1ea973); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfd74c95b088f533f45bcdf250b1ea973)): ?>
<?php $component = $__componentOriginalfd74c95b088f533f45bcdf250b1ea973; ?>
<?php unset($__componentOriginalfd74c95b088f533f45bcdf250b1ea973); ?>
<?php endif; ?><?php /**PATH D:\IT-Learning\storage\framework\views/43a1a1485cf2ae2c8471067915e5af4e.blade.php ENDPATH**/ ?>