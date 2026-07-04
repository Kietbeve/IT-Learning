<aside x-show="!sidebarHidden" 
       x-transition:enter="transition ease-out duration-300 transform"
       x-transition:enter-start="-translate-x-full"
       x-transition:enter-end="translate-x-0"
       x-transition:leave="transition ease-in duration-300 transform"
       x-transition:leave-start="translate-x-0"
       x-transition:leave-end="-translate-x-full"
       @click.away="if (window.innerWidth < 1024) { sidebarHidden = true }"
       class="w-72 shrink-0 border-r border-indigo-50 bg-white px-4 py-6 fixed lg:sticky inset-y-0 left-0 z-50 lg:z-auto overflow-y-auto h-screen top-0 text-slate-600 font-sans custom-scrollbar shadow-2xl lg:shadow-none">
    <!-- Toggle Button to Hide Sidebar -->
    <button @click="sidebarHidden = true" 
            class="absolute top-6 right-4 flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-md hover:bg-indigo-700 transition-all duration-200 z-10"
            aria-label="Hide sidebar">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>

    @php
        $navItems = [
            ['label' => 'Dashboard', 'route' => 'contributor.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6'],
            ['label' => 'Quản lý tài liệu', 'route' => 'contributor.documents.index', 'icon' => 'M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2'],
            ['label' => 'Quản lý câu hỏi', 'route' => 'contributor.questions', 'icon' => 'M9 12l2 2 4-4'],  
            ['label' => 'Quản lý đề thi', 'route' => 'contributor.exams', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
            ['label' => 'Quản lý lộ trình', 'route' => '#', 'icon' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7'],
            ['label' => 'Dự án chờ chấm', 'route' => '#', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
            ['label' => 'Hồ sơ cá nhân', 'route' => '#', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        ];
    @endphp

    <nav class="space-y-1 mt-16">
        @foreach($navItems as $item)
            @php 
                $isLink = $item['route'] !== '#';
                $href = $isLink ? route($item['route']) : '#';
                $active = $isLink && request()->routeIs($item['route']);
                if ($item['route'] === 'contributor.dashboard' && $item['label'] !== 'Dashboard') {
                    $active = false;
                }
            @endphp
            <a href="{{ $href }}" 
               class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition-all duration-200 {{ $active ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 text-white shadow-lg shadow-indigo-600/15' : 'text-slate-650 hover:bg-slate-50 hover:text-indigo-600' }}">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl transition-all duration-200 {{ $active ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-600' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4.5 w-4.5">
                        <path d="{{ $item['icon'] }}" />
                    </svg>
                </span>
                <span class="whitespace-nowrap">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>
</aside>
