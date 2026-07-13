<div>
    <x-notifications z-index="z-50" />

    {{-- Modal: Chi tiết đề thi --}}
    <x-modal-card title="Chi tiết đề thi" blur wire:model="showViewModal" max-width="3xl">
        @if ($exam)
                <div class="space-y-6 text-sm">

                    {{-- Tiêu đề & Mô tả ngắn --}}
                    <div class="relative overflow-hidden rounded-xl border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-5 shadow-sm">
                        <div class="absolute right-0 top-0 h-32 w-32 -translate-y-8 translate-x-8 transform opacity-5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 2a2 2 0 00-2 2v1H6a2 2 0 00-2 2v14a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H9zm0 2h6v2H9V4zm9 3v14H6V7h12z"/>
                                <path d="M8 10h8v2H8v-2zm0 4h8v2H8v-2zm0 4h5v2H8v-2z"/>
                            </svg>
                        </div>
                        <div class="relative">
                            <div class="flex items-start gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-500 text-white shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-lg font-bold text-slate-900 leading-tight">{{ $exam->title }}</h3>
                                    @if ($exam->slug)
                                        <p class="mt-1 font-mono text-xs text-slate-400">{{ $exam->slug }}</p>
                                    @endif
                                </div>
                            </div>
                            @if ($exam->short_description)
                                <p class="mt-3 text-sm leading-relaxed text-slate-600 border-t border-slate-100 pt-3">{{ $exam->short_description }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Thông tin chính --}}
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Thông tin chính</p>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-3">

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Mã đề (Public ID)</span>
                                <span class="font-mono font-semibold text-slate-800 text-xs">{{ $exam->public_id }}</span>
                            </div>

                                {{-- Danh mục --}}
                                <div class="group flex items-start gap-3 rounded-lg bg-slate-50 p-3 transition-all hover:bg-slate-100">
                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-slate-500">Danh mục</p>
                                        <p class="mt-0.5 text-sm font-semibold text-slate-900">{{ $exam->category?->name ?? '—' }}</p>
                                    </div>
                                </div>

                                {{-- Loại đề --}}
                                <div class="group flex items-start gap-3 rounded-lg bg-slate-50 p-3 transition-all hover:bg-slate-100">
                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-slate-500">Loại đề</p>
                                        <p class="mt-0.5 text-sm font-semibold text-slate-900">
                                            {{ match ($exam->type) {
                                                'multiple_choice' => 'Trắc nghiệm',
                                                'essay' => 'Tự luận',
                                                'hybrid' => 'Hỗn hợp',
                                                default => $exam->type,
                                            } }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Chế độ --}}
                                <div class="group flex items-start gap-3 rounded-lg bg-slate-50 p-3 transition-all hover:bg-slate-100">
                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-cyan-100 text-cyan-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-slate-500">Chế độ</p>
                                        <p class="mt-0.5 text-sm font-semibold text-slate-900">
                                            {{ match ($exam->mode) {
                                                'practice' => 'Luyện tập',
                                                'official' => 'Thi chính thức',
                                                default => $exam->mode,
                                            } }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Thời lượng --}}
                                <div class="group flex items-start gap-3 rounded-lg bg-slate-50 p-3 transition-all hover:bg-slate-100">
                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-orange-100 text-orange-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-slate-500">Thời lượng</p>
                                        <p class="mt-0.5 text-sm font-semibold text-slate-900">{{ $exam->duration_minutes }} phút</p>
                                    </div>
                                </div>

                                {{-- Điểm đạt --}}
                                <div class="group flex items-start gap-3 rounded-lg bg-slate-50 p-3 transition-all hover:bg-slate-100">
                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-slate-500">Điểm đạt</p>
                                        <p class="mt-0.5 text-sm font-semibold text-slate-900">{{ $exam->pass_percent }}%</p>
                                    </div>
                                </div>

                                {{-- Phạm vi --}}
                                <div class="group flex items-start gap-3 rounded-lg bg-slate-50 p-3 transition-all hover:bg-slate-100">
                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-pink-100 text-pink-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-slate-500">Phạm vi</p>
                                        <p class="mt-0.5 text-sm font-semibold text-slate-900">
                                            {{ match ($exam->visibility) {
                                                'public' => 'Công khai',
                                                'private' => 'Riêng tư',
                                                default => $exam->visibility,
                                            } }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Trạng thái --}}
                                <div class="group flex items-start gap-3 rounded-lg bg-slate-50 p-3 transition-all hover:bg-slate-100">
                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-slate-500">Trạng thái</p>
                                        @php
                                            $statusMap = [
                                                'draft' => ['label' => 'Bản nháp', 'class' => 'bg-slate-100 text-slate-700 ring-1 ring-slate-200'],
                                                'pending' => ['label' => 'Chờ duyệt', 'class' => 'bg-yellow-100 text-yellow-800 ring-1 ring-yellow-200'],
                                                'approved' => ['label' => 'Đã duyệt', 'class' => 'bg-green-100 text-green-800 ring-1 ring-green-200'],
                                                'rejected' => ['label' => 'Từ chối', 'class' => 'bg-red-100 text-red-800 ring-1 ring-red-200'],
                                            ];
                                            $s = $statusMap[$exam->status] ?? ['label' => $exam->status, 'class' => 'bg-slate-100 text-slate-700 ring-1 ring-slate-200'];
                                        @endphp
                                        <span class="mt-0.5 inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold {{ $s['class'] }}">
                                            {{ $s['label'] }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Số câu hỏi --}}
                                <div class="group flex items-start gap-3 rounded-lg bg-slate-50 p-3 transition-all hover:bg-slate-100">
                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-violet-100 text-violet-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-slate-500">Số câu hỏi</p>
                                        <p class="mt-0.5 text-sm font-bold text-slate-900">{{ $exam->questions_count ?? 0 }}</p>
                                    </div>
                                </div>

                                {{-- Số lượt làm --}}
                                <div class="group flex items-start gap-3 rounded-lg bg-slate-50 p-3 transition-all hover:bg-slate-100">
                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-teal-100 text-teal-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-slate-500">Số lượt làm</p>
                                        <p class="mt-0.5 text-sm font-semibold text-slate-900">{{ $exam->attempt_count }}</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Thông tin quản lý --}}
                    <div>
                        <div class="mb-4 flex items-center gap-2">
                            <div class="h-1 w-1 rounded-full bg-blue-500"></div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-700">Quản lý & Thời gian</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                            <div class="grid grid-cols-2 gap-4">

                                {{-- Người tạo --}}
                                <div class="group flex items-start gap-3 rounded-lg bg-slate-50 p-3 transition-all hover:bg-slate-100">
                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-slate-500">Người tạo</p>
                                        <p class="mt-0.5 text-sm font-semibold text-slate-900">{{ $exam->author?->name ?? '—' }}</p>
                                    </div>
                                </div>

                                {{-- Người duyệt --}}
                                <div class="group flex items-start gap-3 rounded-lg bg-slate-50 p-3 transition-all hover:bg-slate-100">
                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-green-100 text-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-slate-500">Người duyệt</p>
                                        <p class="mt-0.5 text-sm font-semibold text-slate-900">{{ $exam->reviewer?->name ?? '—' }}</p>
                                    </div>
                                </div>

                                {{-- Ngày tạo --}}
                                <div class="group flex items-start gap-3 rounded-lg bg-slate-50 p-3 transition-all hover:bg-slate-100">
                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-slate-500">Ngày tạo</p>
                                        <p class="mt-0.5 text-sm font-semibold text-slate-900">{{ $exam->created_at?->format('d/m/Y H:i') ?? '—' }}</p>
                                    </div>
                                </div>

                                {{-- Cập nhật lần cuối --}}
                                <div class="group flex items-start gap-3 rounded-lg bg-slate-50 p-3 transition-all hover:bg-slate-100">
                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-slate-500">Cập nhật lần cuối</p>
                                        <p class="mt-0.5 text-sm font-semibold text-slate-900">{{ $exam->updated_at?->format('d/m/Y H:i') ?? '—' }}</p>
                                    </div>
                                </div>

                                {{-- Ngày duyệt --}}
                                <div class="group flex items-start gap-3 rounded-lg bg-slate-50 p-3 transition-all hover:bg-slate-100">
                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-slate-500">Ngày duyệt</p>
                                        <p class="mt-0.5 text-sm font-semibold text-slate-900">{{ $exam->reviewed_at?->format('d/m/Y H:i') ?? '—' }}</p>
                                    </div>
                                </div>

                                {{-- Ngày phát hành --}}
                                <div class="group flex items-start gap-3 rounded-lg bg-slate-50 p-3 transition-all hover:bg-slate-100">
                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-purple-100 text-purple-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-slate-500">Ngày phát hành</p>
                                        <p class="mt-0.5 text-sm font-semibold text-slate-900">{{ $exam->publish_at?->format('d/m/Y H:i') ?? '—' }}</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Lý do từ chối (nếu có) --}}
                    @if ($exam->status === 'rejected' && $exam->rejected_reason)
                        <div class="rounded-xl border-2 border-red-200 bg-gradient-to-br from-red-50 to-white p-5 shadow-sm">
                            <div class="flex items-start gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-red-100 ring-4 ring-red-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-bold text-red-900">Lý do từ chối</p>
                                    <p class="mt-2 text-sm leading-relaxed text-red-700">{{ $exam->rejected_reason }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Mô tả chi tiết --}}
                    @if ($exam->description)
                        <div>
                            <div class="mb-4 flex items-center gap-2">
                                <div class="h-1 w-1 rounded-full bg-blue-500"></div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-700">Mô tả chi tiết</p>
                            </div>
                            <div class="prose prose-sm max-w-none rounded-xl border border-slate-200 bg-gradient-to-br from-slate-50 to-white p-5 text-slate-700 shadow-sm">
                                {!! $exam->description !!}
                            </div>
                        </div>
                    @endif

                </div>
        @endif

        <x-slot name="footer">
            <div class="flex justify-end">
                <x-button flat label="Đóng" wire:click="$set('showViewModal', false)" />
            </div>
        </x-slot>
    </x-modal-card>

    {{-- Modal: Chỉnh sửa đề thi --}}
    <x-modal-card title="Chỉnh sửa đề thi" blur wire:model="showEditModal" max-width="2xl">

        @if ($showEditModal)
            <div class="space-y-6">

                {{-- Section: Thông tin cơ bản --}}
                <div class="rounded-xl border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-5">
                    <div class="mb-4 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">Thông tin cơ bản</h3>
                    </div>
                    <div class="space-y-4">
                        <x-input label="Tiêu đề" wire:model="title" placeholder="Nhập tiêu đề đề thi..." />
                        <x-textarea label="Mô tả ngắn" wire:model="short_description" placeholder="Mô tả ngắn gọn về đề thi..." rows="2" />
                        <x-textarea label="Mô tả chi tiết" wire:model="description" placeholder="Mô tả chi tiết về đề thi, yêu cầu, nội dung..." rows="3" />
                    </div>
                </div>

                {{-- Section: Phân loại --}}
                <div class="rounded-xl border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-5">
                    <div class="mb-4 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-500 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">Phân loại & Cấu hình</h3>
                    </div>
                    <div class="space-y-4">
                        <x-native-select label="Danh mục" wire:model="category_id">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category['id'] }}" @selected($category_id == $category['id'])>
                                    {{ $category['name'] }}
                                </option>
                            @endforeach
                        </x-native-select>

                        <div class="grid grid-cols-2 gap-4">
                            <x-native-select label="Loại đề" wire:model="type">
                                <option value="multiple_choice" @selected($type === 'multiple_choice')>Trắc nghiệm</option>
                                <option value="essay" @selected($type === 'essay')>Tự luận</option>
                                <option value="hybrid" @selected($type === 'hybrid')>Kết hợp</option>
                            </x-native-select>

                            <x-native-select label="Chế độ" wire:model="mode">
                                <option value="practice" @selected($mode === 'practice')>Luyện tập</option>
                                <option value="official" @selected($mode === 'official')>Chính thức</option>
                            </x-native-select>
                        </div>
                    </div>
                </div>

                {{-- Section: Cài đặt thi --}}
                <div class="rounded-xl border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-5">
                    <div class="mb-4 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-500 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">Cài đặt thi</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <x-input type="number" label="Thời gian (phút)" wire:model="duration_minutes" placeholder="60" />
                        <x-input type="number" label="Điểm đạt (%)" wire:model="pass_percent" placeholder="70" />
                    </div>
                </div>

                {{-- Section: Xuất bản --}}
                <div class="rounded-xl border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-5">
                    <div class="mb-4 flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-500 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800">Xuất bản</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <x-native-select label="Hiển thị" wire:model="visibility">
                            <option value="public" @selected($visibility === 'public')>Công khai</option>
                            <option value="private" @selected($visibility === 'private')>Riêng tư</option>
                        </x-native-select>

                        <x-input label="Thời gian xuất bản" type="datetime-local" wire:model="publish_at" />
                    </div>
                </div>

            </div>
        @endif

        <x-slot name="footer">
            <div class="flex justify-end gap-2">
                <x-button flat label="Hủy" wire:click="$set('showEditModal', false)" />
                <x-button primary label="Lưu" wire:click="save" />
            </div>
        </x-slot>

    </x-modal-card>

    {{-- Modal: Xác nhận xóa --}}
    <x-modal-card blur wire:model="showDeleteModal" max-width="md">
        @if ($exam)
            <div class="space-y-6">
                {{-- Header --}}
                <div class="flex flex-col items-center text-center">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-red-100 ring-8 ring-red-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v2m0 4h.01M10.29 3.86l-7.5 13A1 1 0 003.67 18h16.66a1 1 0 00.88-1.5l-7.5-13a1 1 0 00-1.74 0z" />
                        </svg>
                    </div>

                    <h3 class="mt-5 text-xl font-bold text-slate-900">
                        Xác nhận xóa đề thi
                    </h3>

                    <p class="mt-2 text-sm text-slate-500">
                        Hành động này sẽ chuyển đề thi sang trạng thái
                        <span class="font-semibold text-red-600">đã xóa</span>.
                        Bạn vẫn có thể khôi phục nếu hệ thống hỗ trợ.
                    </p>
                </div>

                {{-- Exam Card --}}
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                                Đề thi
                            </p>

                            <h4 class="mt-1 truncate text-base font-semibold text-slate-900">
                                {{ $exam->title }}
                            </h4>

                            @if ($exam->slug)
                                <p class="mt-1 font-mono text-xs text-slate-400">
                                    {{ $exam->slug }}
                                </p>
                            @endif
                        </div>

                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                            Sẽ bị xóa
                        </span>
                    </div>
                </div>

                {{-- Warning --}}
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">
                    <div class="flex gap-3">
                        <div>
                            <p class="font-semibold text-amber-800">
                                Lưu ý
                            </p>

                            <p class="mt-1 text-sm text-amber-700">
                                Sau khi xác nhận, đề thi sẽ không còn xuất hiện
                                trong danh sách mặc định.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <x-slot name="footer">
            <div class="flex items-center justify-end gap-3">
                <x-button flat label="Hủy" wire:click="$set('showDeleteModal', false)" />
                <x-button negative label="🗑 Xác nhận xóa" wire:click="delete_one"
                    class="transition-all duration-200 hover:scale-[1.02] active:scale-95" />
            </div>
        </x-slot>
    </x-modal-card>
</div>