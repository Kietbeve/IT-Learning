<aside x-cloak
       x-show="!sidebarHidden" 
       x-transition:enter="transition ease-out duration-300 transform"
       x-transition:enter-start="-translate-x-full"
       x-transition:enter-end="translate-x-0"
       x-transition:leave="transition ease-in duration-300 transform"
       x-transition:leave-start="translate-x-0"
       x-transition:leave-end="-translate-x-full"
       @click.away="if (window.innerWidth < 1024) { sidebarHidden = true }"
       class="w-[250px] bg-gradient-to-b from-white via-white to-slate-50/90 border-r border-slate-200/50 shadow-[4px_0_28px_-12px_rgba(0,0,0,0.08)] flex-shrink-0 flex flex-col h-screen fixed lg:sticky top-0 inset-y-0 left-0 z-50 lg:z-20 overflow-y-auto sidebar-scroll">
    
    <!-- Toggle Button to Hide Sidebar (Mobile) -->
    <button @click="sidebarHidden = true" 
            class="absolute top-6 right-4 flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-md hover:bg-indigo-700 transition-all duration-200 lg:hidden z-50"
            aria-label="Hide sidebar">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>

    <!-- brand -->
    <div class="px-6 py-7 border-b border-slate-200/40">
        <a href="{{ route('contributor.dashboard') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-200/70">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <span class="text-2xl font-extrabold tracking-tight text-slate-800">IT<span class="text-indigo-600">Learning</span></span>
        </a>
    </div>

    @php
        $navItems = [
            ['label' => 'Dashboard', 'route' => 'contributor.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6'],
            ['label' => 'Quản lý tài liệu', 'route' => 'contributor.documents.index', 'icon' => 'M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2'],
            ['label' => 'Quản lý câu hỏi', 'route' => 'contributor.questions', 'icon' => 'M9 12l2 2 4-4'],  
            ['label' => 'Quản lý đề thi', 'route' => 'contributor.exams', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
            ['label' => 'Quản lý lộ trình', 'route' => 'manage.roadmap', 'icon' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7'],
            ['label' => 'Lịch sử thu nhập', 'route' => 'contributor.transactions', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
            ['label' => 'Ví / Rút tiền', 'route' => 'contributor.payout-request', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ];
    @endphp

    <!-- nav -->
    <nav class="flex-1 px-4 py-6 space-y-0.5">

        @foreach($navItems as $item)
            @php 
                $isLink = $item['route'] !== '#';
                $href = $isLink && Route::has($item['route']) ? route($item['route']) : '#';
                $active = $isLink && request()->routeIs($item['route']);
                if ($item['route'] === 'contributor.dashboard' && $item['label'] !== 'Dashboard') {
                    $active = false;
                }
            @endphp
            
            <a href="{{ $href }}" 
               class="nav-link {{ $active ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors {{ $active ? 'bg-gradient-to-r from-indigo-50 to-indigo-100/50 text-indigo-700 font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-100/70' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                </svg>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

</aside>
