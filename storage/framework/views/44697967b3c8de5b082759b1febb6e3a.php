<button id="openSidebarBtn" onclick="openSidebar()" class="fixed top-20 left-4 z-[50] bg-blue-600 text-white w-10 h-10 rounded-lg flex items-center justify-center shadow-lg hover:bg-blue-700 transition-all hidden">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
</button>

<div id="learningSidebar" class="fixed top-0 left-0 h-full w-72 bg-white shadow-xl z-[70] transform transition-transform duration-300">
    <div class="p-5 border-b flex justify-between items-center">
        <h2 class="font-bold text-xl text-blue-600">Lộ trình học</h2>
        
        <button onclick="closeSidebar()" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 w-8 h-8 rounded-full flex items-center justify-center transition-colors" title="Đóng">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div class="p-4 overflow-y-auto h-[calc(100%-80px)]">
        <h3 class="font-semibold text-green-600 mb-3">✓ Đã đăng ký</h3>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $registeredRoadmaps ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roadmap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <a href="<?php echo e(route('learning.roadmaps.show', $roadmap->id)); ?>" class="block p-3 rounded-lg hover:bg-blue-50 mb-2 transition-colors">
                <?php echo e($roadmap->title); ?>

            </a>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <p class="text-gray-400 text-sm mb-2">Chưa có lộ trình</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <hr class="my-5">

        <h3 class="font-semibold text-gray-700 mb-3">📚 Chưa đăng ký</h3>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $unregisteredRoadmaps ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roadmap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <a href="<?php echo e(route('learning.roadmaps.show', $roadmap->id)); ?>" class="block p-3 rounded-lg hover:bg-gray-100 mb-2 transition-colors">
                <?php echo e($roadmap->title); ?>

            </a>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <p class="text-gray-400 text-sm">Không còn lộ trình</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>

<div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-[60] hidden transition-opacity duration-300" onclick="closeSidebar()"></div>

<script>
function openSidebar() {
    const sidebar = document.getElementById("learningSidebar");
    const openBtn = document.getElementById("openSidebarBtn");
    const overlay = document.getElementById("sidebarOverlay");
    
    if (sidebar && openBtn && overlay) {
        sidebar.classList.remove("-translate-x-full");
        openBtn.classList.add("hidden");
        overlay.classList.remove("hidden");
    }
}

function closeSidebar() {
    const sidebar = document.getElementById("learningSidebar");
    const openBtn = document.getElementById("openSidebarBtn");
    const overlay = document.getElementById("sidebarOverlay");
    
    if (sidebar && openBtn && overlay) {
        sidebar.classList.add("-translate-x-full");
        openBtn.classList.remove("hidden");
        overlay.classList.add("hidden");
    }
}
</script><?php /**PATH D:\IT-Learning\Modules/Learning\resources/views/components/sidebar.blade.php ENDPATH**/ ?>