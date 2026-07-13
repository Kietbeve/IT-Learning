


<footer class="bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 border-t border-slate-700/50 text-slate-300 font-sans mt-auto">
    
    
    <div class="max-w-7xl mx-auto px-6 pt-12 pb-8">
        
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-16">
            
            
            <div class="col-span-1">
                
                
                <a href="<?php echo e(route('home.dashboard')); ?>" 
                   class="flex items-center gap-2 text-white text-2xl font-bold font-sans hover:opacity-90 transition-all duration-300 mb-5 group inline-flex">
                    
                    <img src="<?php echo e(asset('Image/logo.png')); ?>" 
                         alt="Logo" 
                         class="h-10 w-auto object-contain group-hover:scale-105 transition-transform duration-300">
                    
                    <span class="tracking-tight">
                        IT<span class="text-blue-400 group-hover:text-blue-300 transition-colors">Learning</span>
                    </span>
                </a>
                
                
                <p class="text-sm text-slate-400 leading-relaxed max-w-md mb-6">
                    Nền tảng học tập và thi trắc nghiệm trực tuyến hàng đầu, giúp bạn đánh giá năng lực một cách chính xác và hiệu quả.
                </p>

                
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-500/10 border border-blue-500/30 backdrop-blur-sm">
                    <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                    </svg>
                    <span class="text-xs font-medium text-blue-300">Học tập thông minh, tiến bộ vượt trội</span>
                </div>
            </div>

            
            <div class="col-span-1 md:flex md:justify-end">
                <div class="w-full md:w-auto">
                    
                    
                    <h3 class="text-white font-bold mb-5 uppercase text-sm tracking-wider flex items-center gap-2">
                        <div class="w-1 h-5 bg-gradient-to-b from-blue-400 to-cyan-400 rounded-full"></div>
                        Liên hệ
                    </h3>
                    
                    
                    <ul class="flex flex-col gap-4 text-sm">
                        
                        
                        <li class="flex items-center gap-3 group">
                            
                            <div class="w-10 h-10 rounded-xl bg-blue-500/10 group-hover:bg-blue-500/20 flex items-center justify-center transition-all duration-300 border border-blue-500/20 group-hover:border-blue-500/40">
                                <?php if (isset($component)) { $__componentOriginal8fb227d09011c9831b75a18671cea295 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8fb227d09011c9831b75a18671cea295 = $attributes; } ?>
<?php $component = WireUi\Components\Icon\Index::resolve(['name' => 'envelope'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Icon\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 text-blue-400 group-hover:text-blue-300 transition-colors']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8fb227d09011c9831b75a18671cea295)): ?>
<?php $attributes = $__attributesOriginal8fb227d09011c9831b75a18671cea295; ?>
<?php unset($__attributesOriginal8fb227d09011c9831b75a18671cea295); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8fb227d09011c9831b75a18671cea295)): ?>
<?php $component = $__componentOriginal8fb227d09011c9831b75a18671cea295; ?>
<?php unset($__componentOriginal8fb227d09011c9831b75a18671cea295); ?>
<?php endif; ?>
                            </div>
                            
                            <a href="mailto:support@itlearning.com" 
                               class="text-slate-400 hover:text-blue-300 transition-colors duration-300 font-medium">
                                support@itlearning.com
                            </a>
                        </li>
                        
                        
                        <li class="flex items-center gap-3 group">
                            
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 group-hover:bg-emerald-500/20 flex items-center justify-center transition-all duration-300 border border-emerald-500/20 group-hover:border-emerald-500/40">
                                <?php if (isset($component)) { $__componentOriginal8fb227d09011c9831b75a18671cea295 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8fb227d09011c9831b75a18671cea295 = $attributes; } ?>
<?php $component = WireUi\Components\Icon\Index::resolve(['name' => 'phone'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Icon\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 text-emerald-400 group-hover:text-emerald-300 transition-colors']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8fb227d09011c9831b75a18671cea295)): ?>
<?php $attributes = $__attributesOriginal8fb227d09011c9831b75a18671cea295; ?>
<?php unset($__attributesOriginal8fb227d09011c9831b75a18671cea295); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8fb227d09011c9831b75a18671cea295)): ?>
<?php $component = $__componentOriginal8fb227d09011c9831b75a18671cea295; ?>
<?php unset($__componentOriginal8fb227d09011c9831b75a18671cea295); ?>
<?php endif; ?>
                            </div>
                            
                            <a href="tel:+84123456789" 
                               class="text-slate-400 hover:text-emerald-300 transition-colors duration-300 font-medium">
                                +84 123 456 789
                            </a>
                        </li>

                        
                        <li class="flex items-center gap-3 group">
                            
                            <div class="w-10 h-10 rounded-xl bg-purple-500/10 group-hover:bg-purple-500/20 flex items-center justify-center transition-all duration-300 border border-purple-500/20 group-hover:border-purple-500/40">
                                <?php if (isset($component)) { $__componentOriginal8fb227d09011c9831b75a18671cea295 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8fb227d09011c9831b75a18671cea295 = $attributes; } ?>
<?php $component = WireUi\Components\Icon\Index::resolve(['name' => 'map-pin'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\WireUi\Components\Icon\Index::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 text-purple-400 group-hover:text-purple-300 transition-colors']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8fb227d09011c9831b75a18671cea295)): ?>
<?php $attributes = $__attributesOriginal8fb227d09011c9831b75a18671cea295; ?>
<?php unset($__attributesOriginal8fb227d09011c9831b75a18671cea295); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8fb227d09011c9831b75a18671cea295)): ?>
<?php $component = $__componentOriginal8fb227d09011c9831b75a18671cea295; ?>
<?php unset($__componentOriginal8fb227d09011c9831b75a18671cea295); ?>
<?php endif; ?>
                            </div>
                            
                            <span class="text-slate-400 font-medium">
                                Việt Nam
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        
        <div class="mt-10 mb-6 h-px bg-gradient-to-r from-transparent via-slate-700 to-transparent"></div>
        
        
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            
            
            <p class="text-sm text-slate-500 flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12zm0-10a4 4 0 00-4 4h2a2 2 0 114 0 2 2 0 01-4 0H6a4 4 0 108 0z" clip-rule="evenodd"/>
                </svg>
                <span>&copy; <?php echo e(date('Y')); ?> ITLearning. All rights reserved.</span>
            </p>

            
            <div class="flex items-center gap-3">
                
                <span class="flex items-center gap-1.5 text-xs text-slate-500">
                    Made with 
                    <svg class="w-4 h-4 text-red-400 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                    in Vietnam
                </span>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH D:\IT-Learning\resources\views/layouts/patials/user_footer.blade.php ENDPATH**/ ?>