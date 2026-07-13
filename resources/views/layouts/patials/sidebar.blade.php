<aside class="hidden w-72 shrink-0 border-r border-slate-200 bg-white px-4 py-6 lg:block overflow-y-auto h-screen sticky top-0 custom-scrollbar">
    <div class="mb-10 flex items-center gap-3">
        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900 text-white font-bold">A</div>
        <div>
            <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Admin</p>
            <p class="text-lg font-semibold text-slate-900">IT Learning</p>
        </div>
    </div>

    <nav class="space-y-1">
        @php
            $navItems = [
                ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6'],
                ['label' => 'Users', 'route' => 'admin.users', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                ['label' => 'Quản lý CTV', 'route' => 'admin.ctv.list', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                ['label' => 'Duyệt rút tiền', 'route' => 'admin.payouts.review', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
                ['label' => 'Duyệt bài kiểm tra', 'route' => 'admin.moderation.exam', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.952 11.952 0 01-7.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                ['label' => 'Quản lý tài liệu', 'route' => 'admin.documents.index', 'icon' => 'M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2'],
                ['label' => 'Báo cáo vi phạm', 'route' => 'admin.reports.documents', 'icon' => 'M3 3v18h18'],
                ['label' => 'Danh mục tài liệu', 'route' => 'admin.categories.documents.index', 'icon' => 'M4 6h16M4 12h16M4 18h16'],
                ['label' => 'Quản lý Môn học', 'route' => 'admin.subjects', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5A4.5 4.5 0 003 9.5v9A4.5 4.5 0 017.5 14c1.746 0 3.332.477 4.5 1.253m0-9C13.168 5.477 14.754 5 16.5 5A4.5 4.5 0 0121 9.5v9a4.5 4.5 0 00-4.5-4.5c-1.746 0-3.332.477-4.5 1.253'],
                ['label' => 'Diễn đàn', 'route' => 'admin.learning.forum', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
                ['label' => 'Chấm Đồ Án', 'route' => 'admin.reviewer.submissions.index', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 3h4m-4 4h4m-4 4h4m4-12h.01M17 8h.01M17 12h.01M17 16h.01'],
                ['label' => 'Phân công Reviewer', 'route' => 'admin.learning.projects.reviewers.manage', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                ['label' => 'Courses', 'route' => 'admin.dashboard', 'icon' => 'M12 6v6l4 2'],
                ['label' => 'Lessons', 'route' => 'admin.dashboard', 'icon' => 'M9 12l2 2 4-4'],
                ['label' => 'Orders', 'route' => 'admin.dashboard', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 7M7 13l-2 5m5-5v5m4-5v5m4-5l2 5'],
                ['label' => 'Quản lý Tags', 'route' => 'admin.tags','icon' => 'M4 9h16M3 15h16M10 3L8 21M16 3l-2 18'],
                ['label' => 'Settings', 'route' => 'admin.settings.revenue', 'icon' => 'M12 8.5a3.5 3.5 0 100 7 3.5 3.5 0 000-7z'],
            ];
        @endphp

    <nav class="space-y-1">
        @foreach($navItems as $item)
            @php 
                $active = request()->routeIs($item['route']);
                if ($item['route'] === 'admin.dashboard' && $item['label'] !== 'Dashboard') {
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
