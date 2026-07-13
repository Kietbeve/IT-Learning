<aside x-cloak
       x-show="!sidebarHidden"
       x-transition:enter="transition ease-out duration-300 transform"
       x-transition:enter-start="-translate-x-full"
       x-transition:enter-end="translate-x-0"
       x-transition:leave="transition ease-in duration-300 transform"
       x-transition:leave-start="translate-x-0"
       x-transition:leave-end="-translate-x-full"
       @click.away="if (window.innerWidth < 1024) { sidebarHidden = true }"
       class="w-72 shrink-0 border-r border-gray-200 bg-white flex-col h-screen overflow-hidden fixed lg:sticky top-0 inset-y-0 left-0 z-50 lg:z-20 transition-all flex">
    <!-- Logo -->
    <div class="flex items-center gap-3 px-6 h-16 border-b border-gray-100 flex-shrink-0">
        <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
        </div>
        <span class="text-xl font-bold text-gray-800 tracking-tight">IT Learning</span>
    </div>

    @php
        $navItems = [
            ['label' => 'Bảng điều khiển', 'route' => 'admin.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6'],
            ['label' => 'Quản lý đơn hàng', 'route' => 'admin.orders.index', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],

            ['label' => 'Quản lý tài liệu', 'route' => 'admin.documents.index', 'icon' => 'M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2', 'badge_type' => 'documents'],
            ['label' => 'Danh mục tài liệu', 'route' => 'admin.categories.documents.index', 'icon' => 'M4 6h16M4 12h16M4 18h16'],
            ['label' => 'Quản lý Môn học', 'route' => 'admin.subjects', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5A4.5 4.5 0 003 9.5v9A4.5 4.5 0 017.5 14c1.746 0 3.332.477 4.5 1.253m0-9C13.168 5.477 14.754 5 16.5 5A4.5 4.5 0 0121 9.5v9a4.5 4.5 0 00-4.5-4.5c-1.746 0-3.332.477-4.5 1.253'],
            ['label' => 'Báo cáo vi phạm', 'route' => 'admin.reports.documents', 'icon' => 'M3 3v18h18', 'badge_type' => 'document_reports'],
            ['label' => 'Duyệt rút tiền', 'route' => 'admin.payouts.review', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', 'badge_type' => 'payouts'],
            ['label' => 'Quản lý Tags', 'route' => 'admin.tags','icon' => 'M4 9h16M3 15h16M10 3L8 21M16 3l-2 18'],
            ['label' => 'Báo cáo doanh thu', 'route' => 'admin.reports', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['label' => 'Người dùng', 'route' => 'admin.users', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
            ['label' => 'Quản lý CTV', 'route' => 'admin.ctv.list', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
            ['label' => 'Duyệt bài kiểm tra', 'route' => 'admin.moderation.exam', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.952 11.952 0 01-7.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
            ['label' => 'Diễn đàn', 'route' => 'admin.learning.forum', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
            ['label' => 'Chấm Đồ Án', 'route' => 'admin.reviewer.submissions.index', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 3h4m-4 4h4m-4 4h4m4-12h.01M17 8h.01M17 12h.01M17 16h.01'],
            ['label' => 'Phân công Reviewer', 'route' => 'admin.learning.projects.reviewers.manage', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
            ['label' => 'Cài đặt', 'route' => 'admin.settings.revenue', 'icon' => 'M12 8.5a3.5 3.5 0 100 7 3.5 3.5 0 000-7z'],
        ];
    @endphp

    <nav class="flex-1 overflow-y-auto sidebar-scroll px-3 py-4 space-y-1">
        @foreach($navItems as $item)
            @php 
                $active = request()->routeIs($item['route']);
                if ($item['route'] === 'admin.dashboard' && $item['label'] !== 'Bảng điều khiển') {
                    $active = false;
                }
            @endphp
            <a href="{{ route($item['route']) }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 {{ $active ? 'bg-blue-50 text-blue-800 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <span class="inline-flex h-6 w-6 items-center justify-center {{ $active ? 'text-blue-600' : 'text-gray-400' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                        <path d="{{ $item['icon'] }}" />
                    </svg>
                </span>
                <span class="flex-1">{{ $item['label'] }}</span>
                @if(isset($item['badge_type']))
                    @livewire('admin-badge', ['type' => $item['badge_type']], key('badge-'.$item['badge_type']))
                @elseif(isset($item['badge']) && $item['badge'] > 0)
                    <span class="inline-flex items-center justify-center rounded-full bg-red-500 px-2 py-0.5 text-[10px] font-bold text-white min-w-[20px]">{{ $item['badge'] }}</span>
                @endif
            </a>
        @endforeach
    </nav>
</aside>

<style>
/* Sidebar scroll */
.sidebar-scroll::-webkit-scrollbar {
    width: 4px;
}
.sidebar-scroll::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 8px;
}
.sidebar-scroll::-webkit-scrollbar-track {
    background: transparent;
}
</style>
