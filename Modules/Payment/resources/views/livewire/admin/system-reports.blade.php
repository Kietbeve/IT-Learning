<div class="space-y-6">
    {{-- Date Filter Section --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Lọc theo ngày</h3>
        
        {{-- Custom Date Range Inputs --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <label for="start_date" class="block text-sm font-medium text-slate-700 mb-2">Từ ngày</label>
                <input type="date" 
                    id="start_date"
                    wire:model="start_date" 
                    class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-slate-700 mb-2">Đến ngày</label>
                <input type="date" 
                    id="end_date"
                    wire:model="end_date" 
                    class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-end">
                <button wire:click="setFilterPreset('custom')" 
                    class="w-full rounded-lg bg-slate-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-slate-700">
                    Áp dụng bộ lọc
                </button>
            </div>
        </div>

        @if($start_date || $end_date)
            <div class="mt-4 flex items-center gap-2 text-sm text-slate-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>
                    Đang lọc dữ liệu
                    @if($start_date && $end_date)
                        từ {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} đến {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}
                    @elseif($start_date)
                        từ {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }}
                    @elseif($end_date)
                        đến {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}
                    @endif
                </span>
            </div>
        @endif
    </div>


    {{-- Export Report Section --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <h2 class="text-xl font-semibold text-slate-900">Xuất báo cáo thống kê</h2>
                <p class="mt-2 text-sm text-slate-600">
                    Tạo báo cáo Excel chi tiết bao gồm thống kê về người dùng, tài liệu, lộ trình học, bài kiểm tra và doanh thu.
                </p>
                
                <div class="mt-6 space-y-3">
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm text-slate-700">Thống kê tổng quan về toàn bộ hệ thống</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm text-slate-700">Danh sách chi tiết người dùng, tài liệu, lộ trình và bài kiểm tra</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm text-slate-700">Báo cáo doanh thu chi tiết theo từng đơn hàng</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm text-slate-700">Định dạng Excel với nhiều sheet, dễ dàng phân tích và xử lý</span>
                    </div>
                </div>

                <div class="mt-8">
                    <button 
                        wire:click="exportReport" 
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/30 transition-all hover:bg-blue-700 hover:shadow-xl disabled:cursor-not-allowed disabled:opacity-50">
                        <svg wire:loading.remove class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <svg wire:loading class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove>Xuất báo cáo Excel</span>
                        <span wire:loading>Đang tạo báo cáo...</span>
                    </button>
                </div>
            </div>

            <div class="hidden lg:block">
                <div class="flex h-32 w-32 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-blue-500">
                    <svg class="h-16 w-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Additional Statistics --}}
    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Revenue Statistics --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900">Thống kê doanh thu</h3>
            <div class="mt-4 space-y-4">
                @php
                    $totalRevenue = \Modules\Payment\Models\Order::where('payment_status', 'paid')->sum('total_amount');
                    $totalOrders = \Modules\Payment\Models\Order::where('payment_status', 'paid')->count();
                    $contributorEarnings = \Modules\Payment\Models\OrderItem::sum('contributor_amount');
                    $platformRevenue = \Modules\Payment\Models\OrderItem::sum('platform_amount');
                @endphp
                
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Tổng doanh thu</span>
                    <span class="text-lg font-semibold text-slate-900">{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Tổng đơn hàng</span>
                    <span class="text-lg font-semibold text-slate-900">{{ number_format($totalOrders) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Thu nhập Contributor</span>
                    <span class="text-lg font-semibold text-emerald-600">{{ number_format($contributorEarnings, 0, ',', '.') }} VNĐ</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Doanh thu Platform</span>
                    <span class="text-lg font-semibold text-blue-600">{{ number_format($platformRevenue, 0, ',', '.') }} VNĐ</span>
                </div>
            </div>
        </div>

        {{-- Content Statistics --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900">Thống kê nội dung</h3>
            <div class="mt-4 space-y-4">
                @php
                    $totalDownloads = \Modules\Document\Models\Document::sum('download_count');
                    $totalViews = \Modules\Document\Models\Document::sum('view_count');
                    $vipUsers = \App\Models\User::whereNotNull('vip_expires_at')->where('vip_expires_at', '>', now())->count();
                    $contributors = \App\Models\User::role('contributor')->count();
                @endphp
                
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Tổng lượt xem tài liệu</span>
                    <span class="text-lg font-semibold text-slate-900">{{ number_format($totalViews) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Tổng lượt tải xuống</span>
                    <span class="text-lg font-semibold text-slate-900">{{ number_format($totalDownloads) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Người dùng VIP</span>
                    <span class="text-lg font-semibold text-purple-600">{{ number_format($vipUsers) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Contributors</span>
                    <span class="text-lg font-semibold text-amber-600">{{ number_format($contributors) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
