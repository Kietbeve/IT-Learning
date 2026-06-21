<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>DevAcademy - Hệ thống Lộ trình Học tập Công nghệ</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,900&display=swap" rel="stylesheet" />
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-[#070c1e] text-slate-100 font-sans antialiased flex flex-col min-h-screen selection:bg-blue-500/30 selection:text-blue-200">

    <header class="sticky top-0 z-50 w-full border-b border-slate-800/80 bg-slate-950/70 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            
            <div class="flex items-center space-x-8">
                <a href="{{ route('learning.roadmaps.index') }}" class="flex items-center space-x-2 group">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:rotate-6 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="text-base font-black tracking-wider bg-clip-text text-transparent bg-gradient-to-r from-white via-slate-200 to-slate-400">DEV<span class="text-blue-500">ACADEMY</span></span>
                </a>

                <nav class="hidden md:flex items-center space-x-1 text-xs font-bold text-slate-400">
                    <a href="{{ route('learning.roadmaps.index') }}" class="px-4 py-2 rounded-lg text-white bg-slate-900/80 border border-slate-800/50">Lộ Trình Học</a>
                    <a href="#" class="px-4 py-2 rounded-lg hover:text-slate-200 hover:bg-slate-900/40 transition-all">Khóa Học</a>
                    <a href="#" class="px-4 py-2 rounded-lg hover:text-slate-200 hover:bg-slate-900/40 transition-all">Sổ Tay Ghi Chú</a>
                    <a href="#" class="px-4 py-2 rounded-lg hover:text-slate-200 hover:bg-slate-900/40 transition-all">Cộng Đồng Q&A</a>
                </nav>
            </div>

            <div class="flex items-center space-x-4">
                <button class="p-2 text-slate-400 hover:text-white rounded-xl hover:bg-slate-900 border border-transparent hover:border-slate-800/60 relative transition-all">
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </button>

                <div class="flex items-center space-x-3 pl-2 border-l border-slate-800" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none group">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80" class="w-8 h-8 rounded-xl object-cover border border-slate-700 group-hover:border-blue-500 transition-all" alt="Avatar">
                        <div class="hidden lg:block text-left">
                            <p class="text-xs font-black text-slate-300 group-hover:text-white transition-colors">Học Viên Pro</p>
                            <p class="text-[10px] font-medium text-emerald-400 flex items-center gap-1"><span class="w-1 h-1 rounded-full bg-emerald-400"></span>Trực tuyến</p>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-slate-950 border-t border-slate-800/80 py-12 text-slate-500 text-xs mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-3">
                <span class="text-sm font-black text-white tracking-widest">DEV<span class="text-blue-500">ACADEMY</span></span>
                <p class="text-slate-400 leading-relaxed">Hệ thống hoạch định và phát triển lộ trình kỹ thuật phần mềm, lập trình viên chuyên nghiệp thực chiến hàng đầu.</p>
            </div>
            <div>
                <h4 class="font-bold text-slate-300 mb-3 text-[13px] uppercase tracking-wider">Nền tảng học thuật</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-blue-400 transition-colors">Bản đồ Lộ trình (Roadmaps)</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition-colors">Hệ thống bài tập Project</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition-colors">Thiết lập môi trường Sandbox</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-slate-300 mb-3 text-[13px] uppercase tracking-wider">Hỗ trợ kỹ thuật</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-blue-400 transition-colors">Trung tâm Debug lỗi log</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition-colors">Tài liệu API hướng dẫn</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition-colors">Kênh thảo luận Discord</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-slate-300 mb-3 text-[13px] uppercase tracking-wider">Bản quyền pháp lý</h4>
                <p class="leading-relaxed">© 2026 DevAcademy Platform. Đã được bảo hộ quyền tác giả và sở hữu trí tuệ sản phẩm công nghệ.</p>
            </div>
        </div>
    </footer>

</body>
</html>