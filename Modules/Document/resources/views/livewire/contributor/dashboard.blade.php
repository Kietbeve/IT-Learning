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
                    <a href="#" class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-white/10 hover:bg-white/20 text-white transition-colors" title="Rút tiền">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section - Light Glassmorphic Design -->
    <div class="grid gap-6 grid-cols-2 lg:grid-cols-4">
        <!-- Approved -->
        <article class="relative overflow-hidden rounded-3xl border border-white bg-white/70 p-6 shadow-xl shadow-slate-100/50 backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
            <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-emerald-500/10 blur-xl"></div>
            <div class="flex items-center justify-between">
                <p class="text-xs uppercase tracking-[0.15em] text-slate-500 font-bold">Đã duyệt</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-black text-slate-800 tracking-tight">{{ number_format($approvedDocs) }}</p>
            <p class="mt-1.5 text-[10px] text-emerald-700 font-bold bg-emerald-50/50 border border-emerald-100 px-2 py-0.5 rounded-lg inline-block">Xuất bản công khai</p>
        </article>

        <!-- Pending -->
        <article class="relative overflow-hidden rounded-3xl border border-white bg-white/70 p-6 shadow-xl shadow-slate-100/50 backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
            <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-amber-500/10 blur-xl"></div>
            <div class="flex items-center justify-between">
                <p class="text-xs uppercase tracking-[0.15em] text-slate-500 font-bold">Chờ duyệt</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-black text-slate-800 tracking-tight">{{ number_format($pendingDocs) }}</p>
            <p class="mt-1.5 text-[10px] text-amber-700 font-bold bg-amber-50/50 border border-amber-100 px-2 py-0.5 rounded-lg inline-block animate-pulse">Đang kiểm duyệt</p>
        </article>

        <!-- Downloads -->
        <article class="relative overflow-hidden rounded-3xl border border-white bg-white/70 p-6 shadow-xl shadow-slate-100/50 backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
            <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-blue-500/10 blur-xl"></div>
            <div class="flex items-center justify-between">
                <p class="text-xs uppercase tracking-[0.15em] text-slate-500 font-bold">Lượt tải</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-black text-slate-800 tracking-tight">{{ number_format($totalDownloads) }}</p>
            <p class="mt-1.5 text-[10px] text-indigo-700 font-bold bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-lg inline-block">Học viên đã nhận</p>
        </article>

        <!-- Views -->
        <article class="relative overflow-hidden rounded-3xl border border-white bg-white/70 p-6 shadow-xl shadow-slate-100/50 backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl">
            <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-purple-500/10 blur-xl"></div>
            <div class="flex items-center justify-between">
                <p class="text-xs uppercase tracking-[0.15em] text-slate-500 font-bold">Lượt xem</p>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-black text-slate-800 tracking-tight">{{ number_format($totalViews) }}</p>
            <p class="mt-1.5 text-[10px] text-purple-700 font-bold bg-purple-50/50 border border-purple-100 px-2 py-0.5 rounded-lg inline-block">Đọc thử tài nguyên</p>
        </article>
    </div>

    <!-- Quick Action / Interactive Grid -->
    <div class="space-y-4">
        <h3 class="text-lg font-extrabold text-slate-800 tracking-tight px-1">Lối Tắt Tính Năng</h3>
        <div class="grid gap-6 grid-cols-2 md:grid-cols-4">
            <!-- Upload Doc -->
            <a href="{{ route('contributor.documents.create') }}" class="group relative overflow-hidden rounded-[2rem] border border-white bg-white/70 p-6 shadow-xl shadow-slate-100/50 backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:border-indigo-200">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-650 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h4 class="mt-4 text-base font-extrabold text-slate-800 group-hover:text-indigo-600 transition-colors">Tải tài liệu</h4>
                <p class="mt-1 text-xs text-slate-400">PDF, DOCX, ZIP học thuật</p>
            </a>

            <!-- Manage Docs -->
            <a href="{{ route('contributor.documents.index') }}" class="group relative overflow-hidden rounded-[2rem] border border-white bg-white/70 p-6 shadow-xl shadow-slate-100/50 backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:border-indigo-200">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-500 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                </div>
                <h4 class="mt-4 text-base font-extrabold text-slate-800 group-hover:text-indigo-600 transition-colors">Kho tài liệu</h4>
                <p class="mt-1 text-xs text-slate-400">Danh sách & trạng thái duyệt</p>
            </a>

            <!-- Manage Questions (Placeholder) -->
            <a href="#" class="group relative overflow-hidden rounded-[2rem] border border-white bg-white/70 p-6 shadow-xl shadow-slate-100/50 backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:border-purple-200">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-50 text-purple-650 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h4 class="mt-4 text-base font-extrabold text-slate-800 group-hover:text-purple-600 transition-colors flex items-center gap-1.5">
                    Soạn câu hỏi
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-purple-400 animate-pulse"></span>
                </h4>
                <p class="mt-1 text-xs text-slate-400">Hệ thống trắc nghiệm</p>
            </a>

            <!-- Wallet/Earnings (Placeholder) -->
            <a href="#" class="group relative overflow-hidden rounded-[2rem] border border-white bg-white/70 p-6 shadow-xl shadow-slate-100/50 backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:border-emerald-200">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-650 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <h4 class="mt-4 text-base font-extrabold text-slate-800 group-hover:text-emerald-600 transition-colors flex items-center gap-1.5">
                    Ví & Doanh thu
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                </h4>
                <p class="mt-1 text-xs text-slate-400">Hệ thống ví tài chính</p>
            </a>
        </div>
    </div>

    <!-- Recent Uploads Table Section -->
    <section class="overflow-hidden rounded-[2rem] border border-white bg-white/70 shadow-xl shadow-slate-100/50 backdrop-blur-md">
        <div class="p-6 border-b border-indigo-50/50 bg-indigo-50/10 flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold text-slate-800">Tài liệu đăng gần đây</h3>
                <p class="text-xs text-slate-450 mt-0.5">Top 5 tài nguyên học thuật bạn đã đẩy lên hệ thống.</p>
            </div>
            <a href="{{ route('contributor.documents.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-550 transition-colors uppercase tracking-wider">Tất cả tài liệu →</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-indigo-50/50 text-[10px] font-bold uppercase tracking-widest text-slate-400 bg-indigo-50/5">
                        <th class="px-6 py-4">Tài liệu</th>
                        <th class="px-6 py-4">Danh mục</th>
                        <th class="px-6 py-4">Giá bán</th>
                        <th class="px-6 py-4">Trạng thái</th>
                        <th class="px-6 py-4 text-right">Ngày đăng</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-indigo-50/40 text-sm text-slate-650">
                    @forelse($latestDocs as $doc)
                        <tr class="hover:bg-indigo-50/10 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <span class="block max-w-xs truncate font-bold text-slate-800" title="{{ $doc->title }}">{{ $doc->title }}</span>
                                <div class="flex items-center gap-2 mt-1 flex-wrap">
                                    <span class="inline-block text-[9px] font-bold text-indigo-600 bg-indigo-50/80 border border-indigo-100/50 px-1.5 py-0.5 rounded uppercase">{{ $doc->file_type }} • {{ number_format($doc->file_size / 1024 / 1024, 2) }} MB</span>
                                    <span class="inline-flex items-center gap-1.5 text-[10px] text-slate-400 font-bold">
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
                            <td class="px-6 py-4 text-slate-500">
                                {{ $doc->category?->name ?? 'Chưa phân loại' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($doc->product)
                                    <span class="text-indigo-600 font-extrabold">{{ number_format($doc->product->price) }}đ</span>
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
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">
                                        Nháp
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-slate-450 whitespace-nowrap font-medium text-xs">
                                {{ $doc->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400 bg-indigo-50/5">
                                Bạn chưa đăng tải tài liệu nào. <a href="{{ route('contributor.documents.create') }}" class="text-indigo-600 hover:underline font-semibold">Tải lên ngay</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
