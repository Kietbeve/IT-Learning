<div class="space-y-8 pb-10">
    <!-- Compact Welcome & Creative Gradient Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 p-6 text-white shadow-2xl shadow-indigo-900/20 border border-slate-800/50">
        <!-- Glowing Mesh Blobs -->
        <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-indigo-500/20 blur-3xl"></div>
        <div class="absolute -left-10 -bottom-10 h-40 w-40 rounded-full bg-blue-500/20 blur-3xl"></div>

        <div class="relative flex flex-col justify-between gap-4 sm:flex-row sm:items-center z-10">
            <div class="space-y-2">
                <div class="flex flex-wrap items-center gap-3">
                    <h2 class="text-2xl font-black tracking-tight text-white drop-shadow-sm">
                        Xin chào, {{ auth()->user()->name }}
                    </h2>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 backdrop-blur-md px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white border border-white/10 shadow-sm">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_8px_rgba(52,211,153,0.8)]"></span>
                            Kênh Sáng Tạo
                        </span>
                        <span class="inline-flex items-center gap-1 rounded-full bg-gradient-to-r from-amber-300 to-amber-500 px-3 py-1 text-[10px] font-black uppercase tracking-wider text-amber-950 shadow-lg shadow-amber-500/20 border border-amber-300" title="Tỉ lệ hoa hồng bạn nhận được trên mỗi lượt bán hoặc lượt tải VIP">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Hoa hồng: {{ $contributorPercent }}%
                        </span>
                    </div>
                </div>
                <p class="text-xs text-slate-300 font-medium">Chào mừng bạn trở lại với không gian quản lý tài nguyên học tập.</p>
            </div>
            
            <!-- Glassmorphic Balance Widget -->
            <div class="shrink-0 rounded-2xl bg-white/15 backdrop-blur-xl border border-white/10 px-5 py-3 min-w-[200px] shadow-md">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-[9px] uppercase tracking-wider text-indigo-200 font-bold">Số dư ví</p>
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

    <!-- VIP Policy Notice -->
    <div class="rounded-2xl bg-indigo-50/50 border border-indigo-100 p-4 flex gap-4 items-start shadow-sm">
        <div class="flex-shrink-0 mt-0.5">
            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div>
            <h4 class="text-sm font-bold text-indigo-900">Cách tính hoa hồng khi khách tải bằng lượt VIP</h4>
            <p class="text-xs text-indigo-700/80 mt-1 leading-relaxed">
                Khi người dùng sử dụng lượt tải VIP để mua tài liệu của bạn, doanh thu sẽ được tính dựa trên <strong>Giá trị quy đổi của 1 lượt VIP</strong> chứ không dựa trên giá bán lẻ tài liệu. 
                Bạn vẫn sẽ nhận được chính xác <strong>{{ $contributorPercent }}%</strong> từ giá trị quy đổi đó. Điều này giúp đảm bảo thu nhập ổn định cho mọi lượt tải!
            </p>
        </div>
    </div>

    <!-- Revenue Stats Section -->
    <div class="space-y-4">
        
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
                <thead class="bg-slate-50/50">
                    <tr>
                        <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-gray-900">Tài liệu</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Danh mục</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Môn học</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Giá bán</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Trạng thái</th>
                        <th scope="col" class="py-3.5 pl-3 pr-6 text-right text-sm font-semibold text-gray-900">Ngày đăng</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white text-sm">
                    @forelse($latestDocs as $doc)
                        <tr class="transition hover:bg-gray-50/50">
                            <td class="py-4 pl-6 pr-3 align-top">
                                <div class="flex flex-col gap-1">
                                    <a href="{{ route('documents.show', [$doc->id, Str::slug($doc->currentVersion?->title ?? $doc->title ?? 'tai-lieu')]) }}" target="_blank" class="text-sm font-semibold text-gray-900 hover:text-blue-600 truncate max-w-[280px] lg:max-w-[350px]" title="{{ $doc->currentVersion?->title ?? $doc->title }}">
                                        {{ \Illuminate\Support\Str::limit($doc->title, 55) }}
                                    </a>
                                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                                        <span class="inline-flex items-center rounded-md bg-gray-50 px-1.5 py-0.5 text-[10px] font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10 uppercase">
                                            {{ $doc->file_type }}
                                        </span>
                                        <span class="text-xs text-gray-500">{{ number_format($doc->file_size / 1024 / 1024, 2) }} MB</span>
                                        <span class="text-gray-300">|</span>
                                        <span class="flex items-center text-[10px] font-medium text-gray-500 gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            {{ number_format($doc->download_count) }}
                                        </span>
                                        <span class="flex items-center text-[10px] font-medium text-gray-500 gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            {{ number_format($doc->view_count) }}
                                        </span>
                                        <span class="flex items-center text-[10px] font-medium text-rose-600 gap-1">
                                            <svg class="w-3 h-3 fill-rose-500" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                            {{ number_format($doc->favorite_count) }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 align-top">
                                <span class="inline-flex items-center gap-1 rounded-md bg-blue-50 px-1.5 py-0.5 text-[10px] font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">
                                    {{ $doc->category?->name ?? 'Chưa phân loại' }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 align-top">
                                @if($doc->subject)
                                    <span class="inline-flex items-center gap-1 rounded-md bg-indigo-50 px-1.5 py-0.5 text-[10px] font-medium text-indigo-700 ring-1 ring-inset ring-indigo-600/20">
                                        {{ $doc->subject->name }}
                                    </span>
                                @else
                                    <span class="text-[10px] font-medium text-gray-400">Không có</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 align-top">
                                @if($doc->product && $doc->product->is_active)
                                    @if($doc->product->sale_price)
                                        <span class="font-semibold text-blue-600">{{ number_format($doc->product->sale_price) }}đ</span>
                                        <span class="block text-xs text-gray-400 line-through">{{ number_format($doc->product->price) }}đ</span>
                                    @else
                                        <span class="font-semibold text-gray-900">{{ number_format($doc->product->price) }}đ</span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Miễn phí</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 align-top">
                                <div class="flex flex-col gap-1.5 items-start">
                                    @if($doc->trashed())
                                        <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Đã xóa</span>
                                    @elseif($doc->status === 'approved')
                                        <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Đã duyệt</span>
                                    @elseif($doc->status === 'pending')
                                        <span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-800 ring-1 ring-inset ring-amber-600/20">Chờ duyệt</span>
                                    @elseif($doc->status === 'rejected')
                                        <span class="inline-flex items-center rounded-md bg-rose-50 px-2 py-1 text-xs font-medium text-rose-700 ring-1 ring-inset ring-rose-600/10" title="Lý do: {{ $doc->rejected_reason }}">Bị từ chối</span>
                                    @elseif($doc->status === 'unpublished')
                                        <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Đã gỡ</span>
                                    @else
                                        <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Bản nháp</span>
                                    @endif
                                </div>
                            </td>
                            <td class="whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm text-gray-500 align-top">
                                <div class="text-gray-900">{{ $doc->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $doc->created_at->format('H:i') }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 px-4 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">Bạn chưa đăng tải tài liệu nào.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>


