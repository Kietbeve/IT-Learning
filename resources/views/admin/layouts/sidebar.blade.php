<aside class="hidden w-72 shrink-0 border-r border-slate-200 bg-white px-4 py-6 lg:block">
    <div class="mb-10 flex items-center gap-3">
        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900 text-white">A</div>
        <div>
            <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Admin</p>
            <p class="text-lg font-semibold text-slate-900">IT Learning</p>
        </div>
    </div>

    <nav class="space-y-1">
        @php
            $navItems = [
                ['label' => 'Dashboard', 'route' => 'auth.admin.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6'],
                ['label' => 'Users', 'route' => 'auth.admin.dashboard', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                ['label' => 'Courses', 'route' => 'auth.admin.dashboard', 'icon' => 'M12 6v6l4 2'],
                ['label' => 'Lessons', 'route' => 'auth.admin.dashboard', 'icon' => 'M9 12l2 2 4-4'],
                ['label' => 'Categories', 'route' => 'auth.admin.dashboard', 'icon' => 'M4 6h16M4 12h16M4 18h16'],
                ['label' => 'Orders', 'route' => 'auth.admin.dashboard', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 7M7 13l-2 5m5-5v5m4-5v5m4-5l2 5'],
                ['label' => 'Reports', 'route' => 'auth.admin.dashboard', 'icon' => 'M3 3v18h18'],
                ['label' => 'Settings', 'route' => 'auth.admin.dashboard', 'icon' => 'M12 8.5a3.5 3.5 0 100 7 3.5 3.5 0 000-7z'],
            ];
        @endphp

        @foreach($navItems as $item)
            @php $active = request()->routeIs($item['route']); @endphp
            <a href="{{ route($item['route']) }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-medium transition-colors duration-200 {{ $active ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/5' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100 {{ $active ? 'bg-white/10' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                        <path d="{{ $item['icon'] }}" />
                    </svg>
                </span>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>
</aside>
