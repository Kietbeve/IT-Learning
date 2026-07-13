<?php $__env->startSection('content'); ?>
    <div class="space-y-6">

        
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-4">
                <h2 class="text-lg font-semibold text-slate-900">
                    Tổng quan đề thi
                </h2>
                <p class="text-sm text-slate-500">
                    Thống kê nhanh tình trạng đề thi trong hệ thống.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-4 lg:grid-cols-6">
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Tổng đề thi</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        <?php echo e(number_format($stats->total)); ?>

                    </p>
                </div>
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm">
                    <p class="text-sm font-medium text-emerald-700">Đã duyệt</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-900">
                        <?php echo e(number_format($stats->approved)); ?>

                    </p>
                </div>
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 shadow-sm">
                    <p class="text-sm font-medium text-amber-700">Chờ duyệt</p>
                    <p class="mt-2 text-3xl font-bold text-amber-900">
                        <?php echo e(number_format($stats->pending)); ?>

                    </p>
                </div>
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 shadow-sm">
                    <p class="text-sm font-medium text-red-700">Từ chối</p>
                    <p class="mt-2 text-3xl font-bold text-red-900">
                        <?php echo e(number_format($stats->rejected)); ?>

                    </p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 shadow-sm">
                    <p class="text-sm font-medium text-slate-700">Bản nháp</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        <?php echo e(number_format($stats->draft)); ?>

                    </p>
                </div>
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 shadow-sm">
                    <p class="text-sm font-medium text-red-700">
                        Chưa có câu hỏi
                    </p>

                    <p class="mt-2 text-3xl font-bold text-red-900">
                        <?php echo e(number_format($stats->no_questions)); ?>

                    </p>
                </div>
            </div>
        </div>

        
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">
                        Quản lý đề thi
                    </h2>
                    <p class="text-sm text-slate-500">
                        Danh sách toàn bộ đề thi trong hệ thống.
                    </p>
                </div>
            </div>

            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('modules.exam.livewire.contributor.exam-table', []);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-2740424852-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
        </div>

        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('modules.exam.livewire.contributor.exam-modal', []);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-2740424852-1', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.contributor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\IT-Learning\Modules/Exam\resources/views/contributor/exam-table.blade.php ENDPATH**/ ?>