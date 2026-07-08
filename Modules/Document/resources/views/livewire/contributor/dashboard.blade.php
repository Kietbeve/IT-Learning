<div class="space-y-8 font-sans pb-10">
    <!-- Compact Welcome & Creative Gradient Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-indigo-700 to-purple-600 p-6 text-white shadow-xl shadow-indigo-600/10">
        <!-- Glowing Mesh Blobs -->
        <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/10 blur-xl"></div>

        <div class="relative flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div class="space-y-1">
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-2xl font-black tracking-tight text-white">
                        Xin chào, {{ auth()->user()->name }}
                    </h2>
                    <span class="inline-flex items-center gap-1 rounded-full bg-white/15 px-2.5 py-0.5 text-[9px] font-bold uppercase tracking-wider text-white border border-white/20">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Kênh Sáng Tạo
                    </span>
                </div>
                <p class="text-xs text-indigo-100">Chào mừng bạn trở lại với không gian quản lý tài nguyên học tập.</p>
            </div>
            
            <!-- Glassmorphic Balance Widget -->
            <div class="shrink-0 rounded-2xl bg-white/15 backdrop-blur-xl border border-white/10 px-5 py-3 min-w-[200px] shadow-md">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-[9px] uppercase tracking-wider text-indigo-200 font-bold">Doanh thu</p>
                        <p class="text-xl font-black text-white tracking-tight">
                            {{ number_format($balance, 0, ',', '.') }}đ
                        </p>
                    </div>
                    <a href="{{ route('contributor.payout-request') }}" class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-white/10 hover:bg-white/20 text-white transition-colors" title="Rút tiền">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Stats Section -->
    <div class="space-y-4">
        <div class="flex items-center justify-between px-1">
            <h3 class="text-lg font-extrabold text-gray-800 tracking-tight">Thống Kê Doanh Thu</h3>
            <a href="{{ route('contributor.payout-request') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-550 transition-colors uppercase tracking-wider">Ví & Giao dịch →</a>
        </div>
        <div class="grid gap-6 grid-cols-2 lg:grid-cols-3">
            <!-- Today's Earnings - FIRST -->
            <article class="relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-xl shadow-gray-100/50 backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
                <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-teal-500/10 blur-xl"></div>
                <div class="flex items-center justify-between">
                    <p class="text-xs uppercase tracking-[0.15em] text-gray-500 font-bold">Hôm nay</p>
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-teal-50 text-teal-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                </div>
                <p class="mt-4 text-3xl font-black text-gray-800 tracking-tight">{{ number_format($todayEarnings) }}đ</p>
                <p class="mt-1.5 text-[10px] text-teal-700 font-bold bg-teal-50/50 border border-teal-100 px-2 py-0.5 rounded-lg inline-block">Doanh thu hôm nay</p>
            </article>

            <!-- This Month -->
            <article class="relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-xl shadow-gray-100/50 backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
                <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-green-500/10 blur-xl"></div>
                <div class="flex items-center justify-between">
                    <p class="text-xs uppercase tracking-[0.15em] text-gray-500 font-bold">Tháng này</p>
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-green-50 text-green-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                </div>
                <p class="mt-4 text-3xl font-black text-gray-800 tracking-tight">{{ number_format($earningsThisMonth) }}đ</p>
                <p class="text-xs {{ $changePercent >= 0 ? 'text-green-700 bg-green-50/50 border-green-100' : 'text-red-700 bg-red-50/50 border-red-100' }} font-bold border px-2 py-0.5 rounded-lg inline-block mt-1.5">
                    {{ $changePercent >= 0 ? '↑' : '↓' }} {{ abs($changePercent) }}% vs tháng trước
                </p>
            </article>

            <!-- Last Month -->
            <article class="relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-xl shadow-gray-100/50 backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
                <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-blue-500/10 blur-xl"></div>
                <div class="flex items-center justify-between">
                    <p class="text-xs uppercase tracking-[0.15em] text-gray-500 font-bold">Tháng trước</p>
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                </div>
                <p class="mt-4 text-3xl font-black text-gray-800 tracking-tight">{{ number_format($earningsLastMonth) }}đ</p>
                <p class="mt-1.5 text-[10px] text-gray-500 font-bold bg-gray-50/50 border border-gray-100 px-2 py-0.5 rounded-lg inline-block">So sánh với tháng trước</p>
            </article>
        </div>
    </div>

    <!-- Recent Uploads Table Section -->
    <section class="overflow-hidden rounded-[2rem] border border-white bg-white/70 shadow-xl shadow-gray-100/50 backdrop-blur-md">
        <div class="p-6 border-b border-indigo-50/50 bg-indigo-50/10 flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold text-gray-800">Tài liệu bán chạy</h3>
                <p class="text-xs text-gray-450 mt-0.5">Top 5 tài nguyên có doanh thu cao nhất.</p>
            </div>
            <a href="{{ route('contributor.documents.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-550 transition-colors uppercase tracking-wider">Tất cả tài liệu →</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-indigo-50/50 text-[10px] font-bold uppercase tracking-widest text-gray-400 bg-indigo-50/5">
                        <th class="px-6 py-4">Tài liệu</th>
                        <th class="px-6 py-4">Danh mục</th>
                        <th class="px-6 py-4">Giá bán</th>
                        <th class="px-6 py-4">Trạng thái</th>
                        <th class="px-6 py-4 text-right">Ngày đăng</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-indigo-50/40 text-sm text-gray-650">
                    @forelse($latestDocs as $doc)
                        <tr class="hover:bg-indigo-50/10 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <span class="block max-w-xs truncate font-bold text-gray-800" title="{{ $doc->title }}">{{ $doc->title }}</span>
                                <div class="flex items-center gap-2 mt-1 flex-wrap">
                                    <span class="inline-block text-[9px] font-bold text-indigo-600 bg-indigo-50/80 border border-indigo-100/50 px-1.5 py-0.5 rounded uppercase">{{ $doc->file_type }} • {{ number_format($doc->file_size / 1024 / 1024, 2) }} MB</span>
                                    <span class="inline-flex items-center gap-1.5 text-[10px] text-gray-400 font-bold">
                                        <span>Tải: {{ number_format($doc->download_count) }}</span>
                                        <span>•</span>
                                        <span>Xem: {{ number_format($doc->view_count) }}</span>
                                        <span>•</span>
                                        <span class="flex items-center text-rose-600">
                                            <svg class="w-3 h-3 fill-rose-500 mr-0.5" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                            {{ number_format($doc->favorite_count) }}
                                        </span>
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <span class="inline-flex items-center gap-1 rounded bg-blue-50 px-1.5 py-0.5 text-[10px] font-bold text-blue-700">
                                        <svg class="w-3 h-3 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                        {{ $doc->category?->name ?? 'Chưa phân loại' }}
                                    </span>
                                    @if($doc->subject)
                                    <span class="inline-flex items-center gap-1 rounded bg-indigo-50 px-1.5 py-0.5 text-[10px] font-bold text-indigo-700">
                                        <svg class="w-3 h-3 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                        {{ $doc->subject->name }}
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($doc->product && $doc->product->is_active)
                                    @if($doc->product->sale_price)
                                        <span class="text-indigo-600 font-extrabold">{{ number_format($doc->product->sale_price) }} VND</span>
                                        <div class="text-xs text-gray-400 line-through">{{ number_format($doc->product->price) }} VND</div>
                                    @else
                                        <span class="text-indigo-600 font-extrabold">{{ number_format($doc->product->price) }} VND</span>
                                    @endif
                                @else
                                    <span class="inline-flex rounded-lg bg-emerald-50 text-[10px] font-bold text-emerald-700 border border-emerald-100">Miễn phí</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($doc->status === 'approved')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 text-xs font-bold text-emerald-700 border border-emerald-100 px-2.5 py-0.5">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Đã duyệt
                                    </span>
                                @elseif($doc->status === 'pending')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 text-xs font-bold text-amber-700 border border-amber-100 px-2.5 py-0.5">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Chờ duyệt
                                    </span>
                                @elseif($doc->status === 'rejected')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 text-xs font-bold text-rose-700 border border-rose-100 px-2.5 py-0.5">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                        Bị từ chối
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-600">
                                        Nháp
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-gray-450 whitespace-nowrap font-medium text-xs">
                                {{ $doc->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400 bg-indigo-50/5">
                                Bạn chưa đăng tải tài liệu nào. <a href="{{ route('contributor.documents.create') }}" class="text-indigo-600 hover:underline font-semibold">Tải lên ngay</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

