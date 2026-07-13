<div <?php echo e($attributes->class([
    data_get($roundedClasses, 'root', ''),
    data_get($colorClasses, 'root', ''),
    $shadowClasses => !$shadowless,
])); ?>>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($header)): ?>
        <div <?php echo e($header->attributes); ?>>
            <?php echo e($header); ?>

        </div>
    <?php elseif($title): ?>
        <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
            data_get($colorClasses, 'border', '') => !$borderless,
            'px-4 py-2.5 flex justify-between items-center',
            data_get($roundedClasses, 'header', ''),
            'border-b' => !$borderless,
        ]); ?>">
            <div <?php echo e(WireUi::extractAttributes($title)->class([
                'font-medium text-base whitespace-normal',
                data_get($colorClasses, 'text', ''),
            ])); ?>>
                <?php echo e($title); ?>

            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($action)): ?>
                <div <?php echo e($action->attributes); ?>>
                    <?php echo e($action); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div <?php echo e(WireUi::extractAttributes($slot)->class([
        data_get($colorClasses, 'text', ''),
        $paddingClasses,
        'grow',
    ])); ?>>
        <?php echo e($slot); ?>

    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($footer)): ?>
        <div <?php echo e($footer->attributes->class([
            data_get($colorClasses, 'border', '') => !$borderless,
            data_get($roundedClasses, 'footer', ''),
            data_get($colorClasses, 'footer', ''),
            'border-t' => !$borderless,
            'px-4 py-4 sm:px-6',
        ])); ?>>
            <?php echo e($footer); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH D:\IT-Learning\vendor\wireui\wireui\src/Components/Card/views/index.blade.php ENDPATH**/ ?>