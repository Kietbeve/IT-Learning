<?php extract((new \Illuminate\Support\Collection($attributes->getAttributes()))->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['config']));

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

foreach (array_filter((['config']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<?php if (isset($component)) { $__componentOriginale342ffe19350145a4df8476b9cf8e431 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale342ffe19350145a4df8476b9cf8e431 = $attributes; } ?>
<?php $component = WireUi\Components\Wrapper\Switcher::resolve(['config' => $config] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('switcher'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Wrapper\Switcher::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attributes)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>


<?php echo e($slot ?? ""); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale342ffe19350145a4df8476b9cf8e431)): ?>
<?php $attributes = $__attributesOriginale342ffe19350145a4df8476b9cf8e431; ?>
<?php unset($__attributesOriginale342ffe19350145a4df8476b9cf8e431); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale342ffe19350145a4df8476b9cf8e431)): ?>
<?php $component = $__componentOriginale342ffe19350145a4df8476b9cf8e431; ?>
<?php unset($__componentOriginale342ffe19350145a4df8476b9cf8e431); ?>
<?php endif; ?><?php /**PATH D:\IT-Learning\storage\framework\views/6832b98bc550b3525d3614035201550e.blade.php ENDPATH**/ ?>