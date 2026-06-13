<div>
    <div class="max-w-7xl mx-auto py-6 space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Báo cáo doanh thu</h1>
                <p class="text-gray-500 mt-1">Theo dõi và phân tích doanh thu từ các tài liệu của bạn</p>
            </div>
            <button wire:click="exportReport" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl font-semibold transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Xuất báo cáo
            </button>
        </div>

        <!-- Date Range Filter -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4">
            <div class="flex gap-2 flex-wrap">
                <button wire:click="$set('dateRange', '7days')" 
                        :class="$dateRange === '7days' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-4 py-2 rounded-lg font-medium text-sm transition-colors">
                    7 ngày
                </button>
                <button wire:click="$set('dateRange', '30days')" 
                        :class="$dateRange === '30days' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-4 py-2 rounded-lg font-medium text-sm transition-colors">
                    30 ngày
                </button>
                <button wire:click="$set('dateRange', '3months')" 
                        :class="$dateRange === '3months' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-4 py-2 rounded-lg font-medium text-sm transition-colors">
                    3 tháng
                </button>
                <button wire:click="$set('dateRange', '1year')" 
                        :class="$dateRange === '1year' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        class="px-4 py-2 rounded-lg font-medium text-sm transition-colors">
                    1 năm
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-green-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-gray-500 text-sm font-medium">Tháng này</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['earningsThisMonth']) }}đ</p>
                <p class="text-xs {{ $stats['changePercent'] >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                    {{ $stats['changePercent'] >= 0 ? '↑' : '↓' }} {{ abs($stats['changePercent']) }}% vs tháng trước
                </p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-gray-500 text-sm font-medium">TB/tài liệu</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['avgRevenuePerDoc']) }}đ</p>
                <p class="text-xs text-gray-400 mt-2">Trung bình trên tất cả tài liệu</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-purple-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-gray-500 text-sm font-medium">Tỷ lệ chuyển đổi</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['conversionRate'] }}%</p>
                <p class="text-xs text-gray-400 mt-2">Views → Downloads</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-orange-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-gray-500 text-sm font-medium">Tháng trước</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['earningsLastMonth']) }}đ</p>
                <p class="text-xs text-gray-400 mt-2">So sánh với tháng trước</p>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-6">Biểu đồ doanh thu</h2>
            <div class="h-80 flex items-center justify-center bg-gray-50 rounded-xl border border-gray-200">
                <div class="text-center text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="text-sm">Cài đặt Chart.js để hiển thị biểu đồ</p>
                    <p class="text-xs text-gray-300 mt-1">Dữ liệu: {{ count($chartData['labels']) }} ngày</p>
                </div>
            </div>
        </div>

        <!-- Top Documents -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900">Top 10 tài liệu (doanh thu)</h2>
            </div>

            @if($topDocuments->isEmpty())
                <div class="p-12 text-center text-gray-400">
                    <p>Chưa có tài liệu nào được bán</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">#</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tên tài liệu</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Lượt bán</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Tổng doanh thu</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">% Tổng</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($topDocuments as $idx => $doc)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $idx + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('documents.show', $doc->id) }}" 
                                           class="text-sm font-medium text-blue-600 hover:text-blue-700 line-clamp-1">
                                            {{ $doc->title }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                        {{ $doc->total_sales }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-bold text-gray-900">
                                        {{ number_format($doc->total_revenue) }}đ
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm text-gray-600">
                                        @php
                                            $totalRevenue = $topDocuments->sum('total_revenue');
                                            $percentage = $totalRevenue > 0 ? ($doc->total_revenue / $totalRevenue) * 100 : 0;
                                        @endphp
                                        {{ number_format($percentage, 1) }}%
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
