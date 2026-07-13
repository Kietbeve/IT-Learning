<?php $__env->startSection('content'); ?>
<?php echo $__env->make('learning::components.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-blue-600">
            Danh sách lộ trình học tập
        </h1>
        <p class="text-gray-600 mt-2">
            Khám phá các lộ trình học tập dành cho sinh viên công nghệ thông tin
        </p>
    </div>

    <form action="<?php echo e(request()->url()); ?>" method="GET" class="mb-8">
        <div class="flex flex-col lg:flex-row gap-4 items-center mb-4">
            <div class="relative w-full lg:flex-1">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Tìm kiếm lộ trình (Ví dụ: Frontend, Laravel...)" class="w-full border border-blue-200 rounded-lg px-12 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                <span class="absolute inset-y-0 left-4 flex items-center text-gray-400 pointer-events-none">
                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><path d="M21 21l-4.35-4.35"></path></svg>
                </span>
            </div>
            <button type="submit" class="w-full lg:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200 shadow-sm">
                Tìm kiếm
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="w-full">
                <select name="category" onchange="this.form.submit()" class="w-full border border-blue-200 rounded-lg px-4 py-3 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <option value="">Tất cả danh mục</option>
                    <option value="frontend" <?php echo e(request('category') == 'frontend' ? 'selected' : ''); ?>>Frontend</option>
                    <option value="backend" <?php echo e(request('category') == 'backend' ? 'selected' : ''); ?>>Backend</option>
                    <option value="fullstack" <?php echo e(request('category') == 'fullstack' ? 'selected' : ''); ?>>Fullstack</option>
                    <option value="mobile" <?php echo e(request('category') == 'mobile' ? 'selected' : ''); ?>>Mobile</option>
                    <option value="devops" <?php echo e(request('category') == 'devops' ? 'selected' : ''); ?>>DevOps</option>
                    <option value="data-science" <?php echo e(request('category') == 'data-science' ? 'selected' : ''); ?>>Data Science & AI</option>
                    <option value="security" <?php echo e(request('category') == 'security' ? 'selected' : ''); ?>>Security</option>
                    <option value="cloud" <?php echo e(request('category') == 'cloud' ? 'selected' : ''); ?>>Cloud Computing</option>
                    <option value="game" <?php echo e(request('category') == 'game' ? 'selected' : ''); ?>>Game Development</option>
                </select>
            </div>
            
            <div class="w-full">
                <select name="level" onchange="this.form.submit()" class="w-full border border-blue-200 rounded-lg px-4 py-3 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <option value="">Tất cả cấp độ</option>
                    <option value="beginner" <?php echo e(request('level') == 'beginner' ? 'selected' : ''); ?>>Người mới bắt đầu</option>
                    <option value="intermediate" <?php echo e(request('level') == 'intermediate' ? 'selected' : ''); ?>>Trung cấp</option>
                    <option value="advanced" <?php echo e(request('level') == 'advanced' ? 'selected' : ''); ?>>Nâng cao</option>
                    <option value="expert" <?php echo e(request('level') == 'expert' ? 'selected' : ''); ?>>Chuyên gia</option>
                </select>
            </div>
            
            <div class="w-full">
                <select name="sort_by" onchange="this.form.submit()" class="w-full border border-blue-200 rounded-lg px-4 py-3 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <option value="latest" <?php echo e(request('sort_by') == 'latest' ? 'selected' : ''); ?>>Mới nhất</option>
                    <option value="oldest" <?php echo e(request('sort_by') == 'oldest' ? 'selected' : ''); ?>>Cũ nhất</option>
                    <option value="popular" <?php echo e(request('sort_by') == 'popular' ? 'selected' : ''); ?>>Phổ biến nhất</option>
                </select>
            </div>
        </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $roadmaps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roadmap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <?php
            // ÉP KIỂU: Chuyển dữ liệu mảng thành Object nếu nó đang là mảng (dành cho dữ liệu mẫu)
            $item = is_array($roadmap) ? (object)$roadmap : $roadmap;
            
            // Xử lý ngày hiển thị linh hoạt cho cả DB thật và Dữ liệu mẫu
            $displayTime = isset($item->created_at) && $item->created_at instanceof \Carbon\Carbon 
                ? $item->created_at->format('H:i d/m/Y') 
                : ($item->time ?? 'Chưa cập nhật');
        ?>

        <div class="bg-white border border-blue-100 rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition duration-300 flex flex-col justify-between p-5" style="min-height: 420px;">
            <div>
                <div class="h-40 overflow-hidden bg-gray-100 mb-4 rounded-lg">
                    <img src="<?php echo e(!empty($item->thumbnail) ? asset('storage/' . $item->thumbnail) : ($item->img ?? 'https://roadmap.sh/roadmaps/frontend.png')); ?>" class="w-full h-full object-cover">
                </div>
                
                <div class="flex justify-between items-center mb-2">
                    <span class="text-xs text-gray-500 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <?php echo e($displayTime); ?>

                    </span>
                    
                    <div class="flex text-amber-400 text-sm">
                        <?php $stars = $item->level_stars ?? $item->stars ?? 3; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php echo $i <= $stars ? '★' : '<span class="text-gray-200">★</span>'; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
                
                <div class="flex gap-2 mb-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($item->category)): ?>
                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                        <?php echo e(ucfirst($item->category)); ?>

                    </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($item->level)): ?>
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">
                        <?php
                            $levelLabels = [
                                'beginner' => 'Cơ bản',
                                'intermediate' => 'Trung cấp',
                                'advanced' => 'Nâng cao',
                                'expert' => 'Chuyên gia'
                            ];
                        ?>
                        <?php echo e($levelLabels[$item->level] ?? ucfirst($item->level)); ?>

                    </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <h3 class="text-xl font-semibold text-blue-600 mb-2">
                    <?php echo e($item->title ?? ($item->name ?? '')); ?>

                </h3>
                <p class="text-gray-600 text-sm mb-4 line-clamp-3 leading-relaxed">
                    <?php echo e($item->description ?? 'Chưa có mô tả cho lộ trình này.'); ?>

                </p>
            </div>
            <div>
                <a href="<?php echo e(route('learning.roadmaps.show', $item->id)); ?>" class="block text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-medium transition duration-200">
                    Xem chi tiết
                </a>
            </div>
        </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        <div class="col-span-3 text-center py-12 text-gray-400">Không có dữ liệu lộ trình nào.</div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($roadmaps) && $roadmaps->count() > 0): ?>
        <div class="mt-8">
            <?php echo e($roadmaps->links()); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\IT-Learning\Modules/Learning\resources/views/layouts/roadmap-list.blade.php ENDPATH**/ ?>