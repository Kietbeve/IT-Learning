<div>
    <?php if (isset($component)) { $__componentOriginal3dde83133891f87f89e964628fb558b6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3dde83133891f87f89e964628fb558b6 = $attributes; } ?>
<?php $component = WireUi\Components\Notifications\Index::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('notifications'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Notifications\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['z-index' => 'z-50']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3dde83133891f87f89e964628fb558b6)): ?>
<?php $attributes = $__attributesOriginal3dde83133891f87f89e964628fb558b6; ?>
<?php unset($__attributesOriginal3dde83133891f87f89e964628fb558b6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3dde83133891f87f89e964628fb558b6)): ?>
<?php $component = $__componentOriginal3dde83133891f87f89e964628fb558b6; ?>
<?php unset($__componentOriginal3dde83133891f87f89e964628fb558b6); ?>
<?php endif; ?>

    
    <?php if (isset($component)) { $__componentOriginal6a7d148983ed3ace55a3e668006b80a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6a7d148983ed3ace55a3e668006b80a5 = $attributes; } ?>
<?php $component = WireUi\Components\Modal\Card::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Modal\Card::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Chi tiết đề thi','blur' => true,'wire:model' => 'showViewModal','max-width' => '3xl']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($exam): ?>
                <div class="space-y-5 text-sm">

                    
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                        <h3 class="text-base font-semibold text-slate-900"><?php echo e($exam->title); ?></h3>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($exam->slug): ?>
                            <p class="mt-0.5 text-xs text-slate-400 font-mono"><?php echo e($exam->slug); ?></p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($exam->short_description): ?>
                            <p class="mt-2 text-sm text-slate-600"><?php echo e($exam->short_description); ?></p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Thông tin chính</p>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-3">

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Mã đề (Public ID)</span>
                                <span class="font-mono font-semibold text-slate-800 text-xs"><?php echo e($exam->public_id); ?></span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Danh mục</span>
                                <span class="text-slate-800"><?php echo e($exam->category?->name ?? '—'); ?></span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Loại đề</span>
                                <span class="text-slate-800">
                                    <?php echo e(match ($exam->type) {
                'multiple_choice' => 'Trắc nghiệm',
                'essay' => 'Tự luận',
                'hybrid' => 'Hỗn hợp',
                default => $exam->type,
            }); ?>

                                </span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Chế độ</span>
                                <span class="text-slate-800">
                                    <?php echo e(match ($exam->mode) {
                'practice' => 'Luyện tập',
                'official' => 'Thi chính thức',
                default => $exam->mode,
            }); ?>

                                </span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Thời lượng</span>
                                <span class="text-slate-800"><?php echo e($exam->duration_minutes); ?> phút</span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Điểm đạt</span>
                                <span class="text-slate-800"><?php echo e($exam->pass_percent); ?>%</span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Phạm vi</span>
                                <span class="text-slate-800">
                                    <?php echo e(match ($exam->visibility) {
                'public' => 'Công khai',
                'private' => 'Riêng tư',
                default => $exam->visibility,
            }); ?>

                                </span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Trạng thái</span>
                                <?php
                                    $statusMap = [
                                        'draft' => ['label' => 'Bản nháp', 'class' => 'bg-slate-100 text-slate-600'],
                                        'pending' => ['label' => 'Chờ duyệt', 'class' => 'bg-yellow-100 text-yellow-700'],
                                        'approved' => ['label' => 'Đã duyệt', 'class' => 'bg-green-100 text-green-700'],
                                        'rejected' => ['label' => 'Từ chối', 'class' => 'bg-red-100 text-red-700'],
                                    ];
                                    $s = $statusMap[$exam->status] ?? ['label' => $exam->status, 'class' => 'bg-slate-100 text-slate-600'];
                                ?>
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium <?php echo e($s['class']); ?>">
                                    <?php echo e($s['label']); ?>

                                </span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Số câu hỏi</span>
                                <span class="text-slate-800 font-semibold"><?php echo e($exam->questions_count ?? 0); ?></span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Số lượt làm</span>
                                <span class="text-slate-800"><?php echo e($exam->attempt_count); ?></span>
                            </div>

                        </div>
                    </div>

                    
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Quản lý & Thời gian</p>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-3">

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Người tạo</span>
                                <span class="text-slate-800"><?php echo e($exam->author?->name ?? '—'); ?></span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Người duyệt</span>
                                <span class="text-slate-800"><?php echo e($exam->reviewer?->name ?? '—'); ?></span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Ngày tạo</span>
                                <span class="text-slate-800"><?php echo e($exam->created_at?->format('d/m/Y H:i') ?? '—'); ?></span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Cập nhật lần cuối</span>
                                <span class="text-slate-800"><?php echo e($exam->updated_at?->format('d/m/Y H:i') ?? '—'); ?></span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Ngày duyệt</span>
                                <span class="text-slate-800"><?php echo e($exam->reviewed_at?->format('d/m/Y H:i') ?? '—'); ?></span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Ngày phát hành</span>
                                <span class="text-slate-800"><?php echo e($exam->publish_at?->format('d/m/Y H:i') ?? '—'); ?></span>
                            </div>

                        </div>
                    </div>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($exam->status === 'rejected' && $exam->rejected_reason): ?>
                        <div class="rounded-lg border border-red-200 bg-red-50 p-3">
                            <p class="text-xs font-semibold text-red-600 mb-1">Lý do từ chối</p>
                            <p class="text-sm text-red-700"><?php echo e($exam->rejected_reason); ?></p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($exam->description): ?>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Mô tả chi tiết</p>
                            <div class="text-sm text-slate-700 bg-slate-50 p-3 rounded border border-slate-200">
                                <?php echo $exam->description; ?>

                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

         <?php $__env->slot('footer', null, []); ?> 
            <div class="flex justify-end">
                <?php if (isset($component)) { $__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $attributes; } ?>
<?php $component = WireUi\Components\Button\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Button\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['flat' => true,'label' => 'Đóng','wire:click' => '$set(\'showViewModal\', false)']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1)): ?>
<?php $attributes = $__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1; ?>
<?php unset($__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1)): ?>
<?php $component = $__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1; ?>
<?php unset($__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1); ?>
<?php endif; ?>
            </div>
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6a7d148983ed3ace55a3e668006b80a5)): ?>
<?php $attributes = $__attributesOriginal6a7d148983ed3ace55a3e668006b80a5; ?>
<?php unset($__attributesOriginal6a7d148983ed3ace55a3e668006b80a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6a7d148983ed3ace55a3e668006b80a5)): ?>
<?php $component = $__componentOriginal6a7d148983ed3ace55a3e668006b80a5; ?>
<?php unset($__componentOriginal6a7d148983ed3ace55a3e668006b80a5); ?>
<?php endif; ?>

    
    <?php if (isset($component)) { $__componentOriginal6a7d148983ed3ace55a3e668006b80a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6a7d148983ed3ace55a3e668006b80a5 = $attributes; } ?>
<?php $component = WireUi\Components\Modal\Card::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Modal\Card::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Chỉnh sửa đề thi','blur' => true,'wire:model' => 'showEditModal','max-width' => 'xl']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>


        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showEditModal): ?>
            <div class="space-y-4">

                <?php if (isset($component)) { $__componentOriginal125559500674abc14ca4c750a63c3764 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal125559500674abc14ca4c750a63c3764 = $attributes; } ?>
<?php $component = WireUi\Components\TextField\Input::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\TextField\Input::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Tiêu đề','wire:model' => 'title']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal125559500674abc14ca4c750a63c3764)): ?>
<?php $attributes = $__attributesOriginal125559500674abc14ca4c750a63c3764; ?>
<?php unset($__attributesOriginal125559500674abc14ca4c750a63c3764); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal125559500674abc14ca4c750a63c3764)): ?>
<?php $component = $__componentOriginal125559500674abc14ca4c750a63c3764; ?>
<?php unset($__componentOriginal125559500674abc14ca4c750a63c3764); ?>
<?php endif; ?>

                

                <?php if (isset($component)) { $__componentOriginal766d51b9779a62d55606e4facdbf6fa8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal766d51b9779a62d55606e4facdbf6fa8 = $attributes; } ?>
<?php $component = WireUi\Components\TextField\Textarea::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\TextField\Textarea::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Mô tả ngắn','wire:model' => 'short_description']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal766d51b9779a62d55606e4facdbf6fa8)): ?>
<?php $attributes = $__attributesOriginal766d51b9779a62d55606e4facdbf6fa8; ?>
<?php unset($__attributesOriginal766d51b9779a62d55606e4facdbf6fa8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal766d51b9779a62d55606e4facdbf6fa8)): ?>
<?php $component = $__componentOriginal766d51b9779a62d55606e4facdbf6fa8; ?>
<?php unset($__componentOriginal766d51b9779a62d55606e4facdbf6fa8); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal766d51b9779a62d55606e4facdbf6fa8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal766d51b9779a62d55606e4facdbf6fa8 = $attributes; } ?>
<?php $component = WireUi\Components\TextField\Textarea::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\TextField\Textarea::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Mô tả chi tiết','wire:model' => 'description']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal766d51b9779a62d55606e4facdbf6fa8)): ?>
<?php $attributes = $__attributesOriginal766d51b9779a62d55606e4facdbf6fa8; ?>
<?php unset($__attributesOriginal766d51b9779a62d55606e4facdbf6fa8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal766d51b9779a62d55606e4facdbf6fa8)): ?>
<?php $component = $__componentOriginal766d51b9779a62d55606e4facdbf6fa8; ?>
<?php unset($__componentOriginal766d51b9779a62d55606e4facdbf6fa8); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginalb421b625325d6862bcafcfe35877042a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb421b625325d6862bcafcfe35877042a = $attributes; } ?>
<?php $component = WireUi\Components\Select\Native::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Select\Native::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Danh mục','wire:model' => 'category_id']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                    <option value="">-- Chọn danh mục --</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <option value="<?php echo e($category['id']); ?>" <?php if($category_id == $category['id']): echo 'selected'; endif; ?>>
                            <?php echo e($category['name']); ?>

                        </option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb421b625325d6862bcafcfe35877042a)): ?>
<?php $attributes = $__attributesOriginalb421b625325d6862bcafcfe35877042a; ?>
<?php unset($__attributesOriginalb421b625325d6862bcafcfe35877042a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb421b625325d6862bcafcfe35877042a)): ?>
<?php $component = $__componentOriginalb421b625325d6862bcafcfe35877042a; ?>
<?php unset($__componentOriginalb421b625325d6862bcafcfe35877042a); ?>
<?php endif; ?>

                <div class="grid grid-cols-2 gap-4">

                    <?php if (isset($component)) { $__componentOriginalb421b625325d6862bcafcfe35877042a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb421b625325d6862bcafcfe35877042a = $attributes; } ?>
<?php $component = WireUi\Components\Select\Native::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Select\Native::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Loại đề','wire:model' => 'type']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                        <option value="multiple_choice" <?php if($type === 'multiple_choice'): echo 'selected'; endif; ?>>Trắc nghiệm</option>
                        <option value="essay" <?php if($type === 'essay'): echo 'selected'; endif; ?>>Tự luận</option>
                        <option value="hybrid" <?php if($type === 'hybrid'): echo 'selected'; endif; ?>>Kết hợp</option>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb421b625325d6862bcafcfe35877042a)): ?>
<?php $attributes = $__attributesOriginalb421b625325d6862bcafcfe35877042a; ?>
<?php unset($__attributesOriginalb421b625325d6862bcafcfe35877042a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb421b625325d6862bcafcfe35877042a)): ?>
<?php $component = $__componentOriginalb421b625325d6862bcafcfe35877042a; ?>
<?php unset($__componentOriginalb421b625325d6862bcafcfe35877042a); ?>
<?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginalb421b625325d6862bcafcfe35877042a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb421b625325d6862bcafcfe35877042a = $attributes; } ?>
<?php $component = WireUi\Components\Select\Native::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Select\Native::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Chế độ','wire:model' => 'mode']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                        <option value="practice" <?php if($mode === 'practice'): echo 'selected'; endif; ?>>Luyện tập</option>
                        <option value="official" <?php if($mode === 'official'): echo 'selected'; endif; ?>>Chính thức</option>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb421b625325d6862bcafcfe35877042a)): ?>
<?php $attributes = $__attributesOriginalb421b625325d6862bcafcfe35877042a; ?>
<?php unset($__attributesOriginalb421b625325d6862bcafcfe35877042a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb421b625325d6862bcafcfe35877042a)): ?>
<?php $component = $__componentOriginalb421b625325d6862bcafcfe35877042a; ?>
<?php unset($__componentOriginalb421b625325d6862bcafcfe35877042a); ?>
<?php endif; ?>

                </div>

                <div class="grid grid-cols-2 gap-4">

                    <?php if (isset($component)) { $__componentOriginal125559500674abc14ca4c750a63c3764 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal125559500674abc14ca4c750a63c3764 = $attributes; } ?>
<?php $component = WireUi\Components\TextField\Input::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\TextField\Input::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'number','label' => 'Thời gian (phút)','wire:model' => 'duration_minutes']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal125559500674abc14ca4c750a63c3764)): ?>
<?php $attributes = $__attributesOriginal125559500674abc14ca4c750a63c3764; ?>
<?php unset($__attributesOriginal125559500674abc14ca4c750a63c3764); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal125559500674abc14ca4c750a63c3764)): ?>
<?php $component = $__componentOriginal125559500674abc14ca4c750a63c3764; ?>
<?php unset($__componentOriginal125559500674abc14ca4c750a63c3764); ?>
<?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginal125559500674abc14ca4c750a63c3764 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal125559500674abc14ca4c750a63c3764 = $attributes; } ?>
<?php $component = WireUi\Components\TextField\Input::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\TextField\Input::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'number','label' => 'Điểm đạt (%)','wire:model' => 'pass_percent']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal125559500674abc14ca4c750a63c3764)): ?>
<?php $attributes = $__attributesOriginal125559500674abc14ca4c750a63c3764; ?>
<?php unset($__attributesOriginal125559500674abc14ca4c750a63c3764); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal125559500674abc14ca4c750a63c3764)): ?>
<?php $component = $__componentOriginal125559500674abc14ca4c750a63c3764; ?>
<?php unset($__componentOriginal125559500674abc14ca4c750a63c3764); ?>
<?php endif; ?>

                </div>

                <div class="grid grid-cols-2 gap-4">

                    <?php if (isset($component)) { $__componentOriginalb421b625325d6862bcafcfe35877042a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb421b625325d6862bcafcfe35877042a = $attributes; } ?>
<?php $component = WireUi\Components\Select\Native::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('native-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Select\Native::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Hiển thị','wire:model' => 'visibility']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                        <option value="public" <?php if($visibility === 'public'): echo 'selected'; endif; ?>>Công khai</option>
                        <option value="private" <?php if($visibility === 'private'): echo 'selected'; endif; ?>>Riêng tư</option>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb421b625325d6862bcafcfe35877042a)): ?>
<?php $attributes = $__attributesOriginalb421b625325d6862bcafcfe35877042a; ?>
<?php unset($__attributesOriginalb421b625325d6862bcafcfe35877042a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb421b625325d6862bcafcfe35877042a)): ?>
<?php $component = $__componentOriginalb421b625325d6862bcafcfe35877042a; ?>
<?php unset($__componentOriginalb421b625325d6862bcafcfe35877042a); ?>
<?php endif; ?>

                    

                </div>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

         <?php $__env->slot('footer', null, []); ?> 
            <div class="flex justify-end gap-2">
                <?php if (isset($component)) { $__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $attributes; } ?>
<?php $component = WireUi\Components\Button\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Button\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['flat' => true,'label' => 'Hủy','wire:click' => '$set(\'showEditModal\', false)']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1)): ?>
<?php $attributes = $__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1; ?>
<?php unset($__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1)): ?>
<?php $component = $__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1; ?>
<?php unset($__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $attributes; } ?>
<?php $component = WireUi\Components\Button\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Button\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'label' => 'Lưu','wire:click' => 'save']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1)): ?>
<?php $attributes = $__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1; ?>
<?php unset($__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1)): ?>
<?php $component = $__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1; ?>
<?php unset($__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1); ?>
<?php endif; ?>
            </div>
         <?php $__env->endSlot(); ?>

     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6a7d148983ed3ace55a3e668006b80a5)): ?>
<?php $attributes = $__attributesOriginal6a7d148983ed3ace55a3e668006b80a5; ?>
<?php unset($__attributesOriginal6a7d148983ed3ace55a3e668006b80a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6a7d148983ed3ace55a3e668006b80a5)): ?>
<?php $component = $__componentOriginal6a7d148983ed3ace55a3e668006b80a5; ?>
<?php unset($__componentOriginal6a7d148983ed3ace55a3e668006b80a5); ?>
<?php endif; ?>

    
    <?php if (isset($component)) { $__componentOriginal6a7d148983ed3ace55a3e668006b80a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6a7d148983ed3ace55a3e668006b80a5 = $attributes; } ?>
<?php $component = WireUi\Components\Modal\Card::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Modal\Card::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Xác nhận xóa','blur' => true,'wire:model' => 'showDeleteModal']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($exam): ?>
            <div class="space-y-4">

                <p class="text-slate-600">
                    Bạn có chắc chắn muốn xóa đề thi sau?
                </p>

                <div class="rounded-lg border border-red-200 bg-red-50 p-4">
                    <?php echo $exam->title; ?>

                </div>

                <p class="text-sm text-red-600">
                    Dữ liệu sẽ được chuyển sang trạng thái đã xóa và có thể khôi phục sau.
                </p>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

         <?php $__env->slot('footer', null, []); ?> 
            <div class="flex justify-end gap-2">

                <?php if (isset($component)) { $__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $attributes; } ?>
<?php $component = WireUi\Components\Button\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Button\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['flat' => true,'label' => 'Hủy','wire:click' => '$set(\'showDeleteModal\', false)']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1)): ?>
<?php $attributes = $__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1; ?>
<?php unset($__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1)): ?>
<?php $component = $__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1; ?>
<?php unset($__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1 = $attributes; } ?>
<?php $component = WireUi\Components\Button\Base::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Button\Base::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['negative' => true,'label' => 'Xác nhận xóa','wire:click' => 'delete_one']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1)): ?>
<?php $attributes = $__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1; ?>
<?php unset($__attributesOriginalf04362c37f55b087f96f1c4fb07d5ce1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1)): ?>
<?php $component = $__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1; ?>
<?php unset($__componentOriginalf04362c37f55b087f96f1c4fb07d5ce1); ?>
<?php endif; ?>

            </div>
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6a7d148983ed3ace55a3e668006b80a5)): ?>
<?php $attributes = $__attributesOriginal6a7d148983ed3ace55a3e668006b80a5; ?>
<?php unset($__attributesOriginal6a7d148983ed3ace55a3e668006b80a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6a7d148983ed3ace55a3e668006b80a5)): ?>
<?php $component = $__componentOriginal6a7d148983ed3ace55a3e668006b80a5; ?>
<?php unset($__componentOriginal6a7d148983ed3ace55a3e668006b80a5); ?>
<?php endif; ?>
</div><?php /**PATH D:\IT-Learning\Modules/Exam\resources/views/contributor/livewire/exam-modals.blade.php ENDPATH**/ ?>