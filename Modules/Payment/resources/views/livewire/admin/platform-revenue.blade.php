<div class="space-y-6">
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-600">Tổng doanh thu</p>
                <span class="rounded-xl bg-green-50 p-2">
                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($totalRevenue) }}đ</p>
            <p class="mt-1 text-sm text-gray-500">Từ tất cả giao dịch</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-600">Doanh thu tháng này</p>
                <span class="rounded-xl bg-blue-50 p-2">
                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($revenueThisMonth) }}đ</p>
            <p class="mt-1 text-sm {{ $revenueGrowth >= 0 ? 'text-green-600' : 'text-rose-600' }}">
                {{ $revenueGrowth >= 0 ? '↑' : '↓' }} {{ abs($revenueGrowth) }}% so với tháng trước
            </p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-600">Chờ duyệt rút tiền</p>
                <span class="rounded-xl bg-amber-50 p-2">
                    <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($pendingPayouts) }}đ</p>
            <p class="mt-1 text-sm text-gray-500">Tổng yêu cầu đang chờ duyệt</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-600">Thu nhập nền tảng</p>
                <span class="rounded-xl bg-purple-50 p-2">
                    <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-purple-700">{{ number_format($netProfit) }}đ</p>
            <p class="mt-1 text-sm text-gray-500">Đã trả cho contributor: {{ number_format($totalPaid) }}đ</p>
        </div>
    </div>

    <!-- 3 Metrics mới -->
    <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-3">
        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-600">Tổng số tài liệu</p>
                <span class="rounded-xl bg-cyan-50 p-2">
                    <svg class="h-5 w-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($totalDocuments) }}</p>
            <p class="mt-1 text-sm text-gray-500">Tài liệu trên hệ thống</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-600">Tổng lượt tải tài liệu</p>
                <span class="rounded-xl bg-emerald-50 p-2">
                    <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($totalDownloads) }}</p>
            <p class="mt-1 text-sm text-gray-500">Tổng số lần tải xuống</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-600">Số dư ví</p>
                <span class="rounded-xl bg-rose-50 p-2">
                    <svg class="h-5 w-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($totalWalletBalance) }}đ</p>
            <p class="mt-1 text-sm text-gray-500">Tổng số dư trong ví contributor</p>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="border-b border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900">Doanh thu tuần này</h2>
            <p class="text-sm text-gray-500">Biểu đồ doanh thu 7 ngày gần nhất</p>
        </div>
        <div class="p-6" x-data="{
            chartData: @js($chartData),
            chartCategories: @js($chartCategories),
            maxValue: Math.max(...@js($chartData), 1),
        }">
            <div class="flex items-end gap-3 justify-center" style="height: 260px;">
                <template x-for="(value, index) in chartData" :key="index">
                    <div class="group relative flex flex-col items-center justify-end h-full" style="width: 80px;">
                        <div class="absolute bottom-full mb-3 hidden group-hover:flex z-20">
                            <div class="rounded-2xl bg-gray-900 px-5 py-3 text-sm font-bold text-white shadow-2xl border border-gray-700">
                                <div x-text="chartCategories[index]" class="text-xs text-gray-400 mb-1 text-center"></div>
                                <div x-text="new Intl.NumberFormat('vi-VN').format(value) + 'đ'" class="text-lg text-center"></div>
                            </div>
                        </div>
                        <div class="w-full rounded-2xl transition-all duration-300 hover:scale-105 cursor-pointer shadow-lg hover:shadow-xl"
                             :style="'height: ' + Math.max((value / maxValue) * 240, 4) + 'px;'"
                             :class="value > 0 ? 'bg-gradient-to-t from-purple-600 via-purple-500 to-purple-400' : 'bg-gray-200'">
                        </div>
                        <span class="mt-3 text-xs font-bold text-gray-600"
                              x-text="chartCategories[index]">
                        </span>
                    </div>
                </template>
            </div>
            <div class="mt-6 flex items-center justify-center gap-6 text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <div class="h-4 w-4 rounded bg-gradient-to-t from-purple-600 to-purple-400 shadow"></div>
                    <span class="font-medium">Doanh thu hàng ngày</span>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white" x-data="{ showDetails: false }">
        <div class="border-b border-gray-200 p-6 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Chi tiết 7 ngày</h2>
                <p class="text-sm text-gray-500">Xem số liệu từng ngày trong tuần</p>
            </div>
            <button @click="showDetails = !showDetails" 
                    class="rounded-xl bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200">
                <span x-show="!showDetails">Xem ↓</span>
                <span x-show="showDetails" x-cloak>Ẩn ↑</span>
            </button>
        </div>
        <div x-show="showDetails" x-cloak x-collapse class="p-6">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                <template x-for="(value, index) in @js($chartData)" :key="index">
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-3 hover:border-blue-300 hover:bg-blue-50 transition-colors">
                        <div class="text-xs font-semibold text-gray-500 mb-1" x-text="@js($chartCategories)[index]"></div>
                        <div class="text-base font-bold" :class="value > 0 ? 'text-blue-700' : 'text-gray-400'">
                            <span x-text="new Intl.NumberFormat('vi-VN').format(value)"></span><span class="text-xs">đ</span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white">
        <div class="border-b border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900">Top Contributor</h2>
            <p class="text-sm text-gray-500">10 người dùng có doanh thu cao nhất</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600">#</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600">Contributor</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase text-gray-600">Tổng doanh thu</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase text-gray-600">Số dư hiện tại</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-gray-600">Số giao dịch</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($topContributors as $index => $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-bold text-gray-400">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-green-700">
                                {{ number_format($user->total_earned ?? 0) }}đ
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-gray-700">
                                {{ number_format($user->contributor_balance ?? 0) }}đ
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-500">
                                {{ number_format($user->earnings_total ?? 0) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                Chưa có dữ liệu
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

