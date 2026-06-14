<aside class="hidden w-72 shrink-0 border-r border-slate-200 bg-white px-4 py-6 lg:block overflow-y-auto h-screen sticky top-0 custom-scrollbar">
    <div class="mb-10 flex items-center gap-3">
        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900 text-white font-bold">A</div>
        <div>
            <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Admin</p>
            <p class="text-lg font-semibold text-slate-900">IT Learning</p>
        </div>
    </div>

    @php
        $navItems = [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6'],
            ['label' => 'Duyệt tài liệu', 'route' => 'admin.moderation.documents.index', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.952 11.952 0 01-7.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
            ['label' => 'Quản lý tài liệu', 'route' => 'admin.documents.index', 'icon' => 'M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2'],
            ['label' => 'Báo cáo vi phạm', 'route' => 'admin.reports.documents', 'icon' => 'M3 3v18h18'],
            ['label' => 'Danh mục tài liệu', 'route' => 'admin.categories.documents.index', 'icon' => 'M4 6h16M4 12h16M4 18h16'],
            ['label' => 'Quản lý đơn hàng', 'route' => 'admin.orders.index', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
            ['label' => 'Duyệt rút tiền', 'route' => 'admin.payouts.review', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
            ['label' => 'Cài đặt', 'route' => 'admin.settings.revenue', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
        ];
    @endphp

    <nav class="space-y-1">
        @foreach($navItems as $item)
            @php 
                $active = request()->routeIs($item['route']);
                if ($item['route'] === 'auth.admin.dashboard' && $item['label'] !== 'Dashboard') {
                    $active = false;
                }
            @endphp
            <a href="{{ route($item['route']) }}" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm font-medium transition-colors duration-200 {{ $active ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/5' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl {{ $active ? 'bg-white/10' : 'bg-slate-100' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                        <path d="{{ $item['icon'] }}" />
                    </svg>
                </span>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>
</aside>
