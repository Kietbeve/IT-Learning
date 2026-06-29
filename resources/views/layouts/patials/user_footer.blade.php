<footer class="bg-slate-900 border-t border-slate-800 text-slate-300 font-sans mt-auto">
    <div class="max-w-7xl mx-auto px-6 pt-8 pb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
            <!-- Brand / Logo -->
            <div class="col-span-1">
                <a href="{{ route('home.dashboard') }}" class="flex items-center gap-1 text-white text-2xl font-bold font-sans hover:opacity-80 transition-opacity mb-4">
                    <img src="{{ asset('Image/logo.png') }}" alt="Logo" class="h-9 w-auto object-contain">
                    <span class="tracking-tight">IT<span class="text-blue-500">Learning</span></span>
                </a>
                <p class="text-sm text-slate-400 leading-relaxed max-w-sm">
                    Nền tảng học tập và thi trắc nghiệm trực tuyến hàng đầu, giúp bạn đánh giá năng lực một cách chính xác và hiệu quả.
                </p>
            </div>

            <!-- Liên hệ -->
            <div class="col-span-1 md:flex md:justify-end">
                <div>
                    <h3 class="text-white font-semibold mb-4 uppercase text-sm tracking-wider">Liên hệ</h3>
                    <ul class="flex flex-col gap-3 text-sm">
                        <li class="flex items-center gap-3">
                            <x-icon name="envelope" class="w-4 h-4 text-slate-500 shrink-0" />
                            <a href="mailto:support@itlearning.com" class="text-slate-400 hover:text-blue-400 transition-colors">support@itlearning.com</a>
                        </li>
                        <li class="flex items-center gap-3">
                            <x-icon name="phone" class="w-4 h-4 text-slate-500 shrink-0" />
                            <a href="tel:+84123456789" class="text-slate-400 hover:text-blue-400 transition-colors">+84 123 456 789</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="mt-8 pt-4 border-t border-slate-800 flex flex-col md:flex-row justify-between items-center text-sm text-slate-500 gap-2">
            <p>&copy; {{ date('Y') }} ITLearning. All rights reserved.</p>
        </div>
    </div>
</footer>
