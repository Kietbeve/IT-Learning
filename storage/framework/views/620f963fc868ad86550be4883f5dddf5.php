<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => null,
    'placeholder' => '',
]));

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

foreach (array_filter(([
    'label' => null,
    'placeholder' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $model = $attributes->wire('model')->value();
?>

<div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($label): ?>
        <label class="mb-2 block text-sm font-medium text-gray-700">
            <?php echo e($label); ?>

        </label>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    
    <div  class="rounded-md overflow-hidden border transition-colors
        <?php echo e($errors->has($model)
            ? 'border-red-500 ring-1 ring-red-500'
            : 'border-gray-300'); ?>">
        <div
            wire:ignore
            x-data="{
                editor: null,

                init() {
                    this.editor = new Quill(this.$refs.editor, {
                        theme: 'snow',
                        placeholder: '<?php echo e($placeholder); ?>',
                        modules: {
                        toolbar: [
                                [{ font: [] }],
                                [{ header: [1, 2, false] }],

                                ['bold', 'italic', 'underline'],
                                ['blockquote', 'code-block'],

                                [{ list: 'ordered' }, { list: 'bullet' }],

                                ['link'],

                                ['clean']
                            ]
                        }
                    });

                    this.editor.root.innerHTML = $wire.get('<?php echo e($model); ?>') ?? '';

                    this.editor.on('text-change', () => {
                        $wire.set('<?php echo e($model); ?>', this.editor.root.innerHTML);
                    });
                }
            }"
        >
            <div
                x-ref="editor"
                class="min-h-[180px] rounded-md bg-white"
            ></div>
        </div>
    </div>
    
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = [$model];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <p class="mt-2 text-sm text-red-600">
            <?php echo e($message); ?>

        </p>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div><?php /**PATH D:\IT-Learning\resources\views/components/text-editor.blade.php ENDPATH**/ ?>