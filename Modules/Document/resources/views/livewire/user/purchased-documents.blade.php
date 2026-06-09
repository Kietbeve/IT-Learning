<div id="purchased-documents-list" class="max-w-7xl mx-auto py-6" x-data="{ notification: null }" @notify.window="notification = $event.detail; setTimeout(() => notification = null, 3000)">
    <!-- Notification Toast -->
    <div x-show="notification" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-5 right-5 z-50 rounded-2xl border bg-white p-4 shadow-xl border-slate-200"
         style="display: none;">
        <div class="flex items-center gap-3">
            <template x-if="notification && notification.type === 'success'">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </template>
            <template x-if="notification && notification.type === 'info'">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </template>
            <div>
                <p class="text-sm font-semibold text-slate-900" x-text="notification ? notification.message : ''"></p>
            </div>
        </div>
    </div>

    <!-- Header Section -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900">Tài liệu đã mua</h1>
            <p class="text-sm text-slate-500 mt-1">Danh sách tất cả tài liệu trả phí bạn đã thanh toán thành công.</p>
        </div>
        <div>
            <a href="{{ route('documents.index') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 hover:bg-slate-800 text-white px-5 py-3 text-sm font-semibold transition-colors duration-200">
                Tìm thêm tài liệu
            </a>
        </div>
    </div>

    <!-- Table Content with Loading State -->
    <div wire:loading.class="opacity-60 transition-opacity duration-200" class="transition-opacity duration-200">
        <!-- Purchased Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($accesses as $access)
            @if($access->document)
                <article class="flex flex-col rounded-3xl border border-slate-200 bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 relative group">
                    <div class="aspect-[16/9] w-full bg-slate-100 flex items-center justify-center relative border-b border-slate-100 overflow-hidden">
                        @if($access->document->thumbnail)
                            <img src="{{ asset('storage/' . $access->document->thumbnail) }}" alt="{{ $access->document->title }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        @else
                            <div class="flex flex-col items-center gap-2 text-slate-400">
                                @if($access->document->file_type === 'pdf')
                                    <svg class="w-12 h-12 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 9h1.5M9 13h5m-5 4h5"/></svg>
                                @elseif($access->document->file_type === 'zip')
                                    <svg class="w-12 h-12 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                @else
                                    <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                @endif
                                <span class="text-xs uppercase font-bold tracking-widest">{{ $access->document->file_type }}</span>
                            </div>
                        @endif

                        <div class="absolute top-4 left-4">
                            <span class="rounded-xl px-2.5 py-1 text-xs font-semibold bg-white/95 text-slate-800 shadow-sm backdrop-blur-sm">
                                {{ $access->document->category?->name ?? 'Tài liệu' }}
                            </span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="flex-1 p-6 flex flex-col justify-between space-y-4">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900 group-hover:text-blue-600 transition-colors duration-200 line-clamp-2">
                                <a href="{{ route('documents.show', $access->document_id) }}">{{ $access->document->title }}</a>
                            </h3>
                            <p class="mt-2 text-sm text-slate-500 line-clamp-2">{{ $access->document->short_description }}</p>
                        </div>

                        <div class="space-y-3 pt-2">
                            <button wire:click="download({{ $access->document_id }})" class="w-full rounded-2xl bg-slate-900 hover:bg-slate-800 text-white py-3 text-xs font-semibold flex items-center justify-center gap-2 shadow-sm transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Tải xuống ngay
                            </button>
                        </div>
                    </div>
                </article>
            @endif
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-200">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 7M7 13l-2 5m5-5v5m4-5v5m4-5l2 5"/></svg>
                <h4 class="text-lg font-semibold text-slate-900">Chưa mua tài liệu nào</h4>
                <p class="mt-1 text-sm text-slate-500">Các tài liệu trả phí bạn mua sẽ được hiển thị và tải trực tiếp tại đây.</p>
                <div class="mt-6">
                    <a href="{{ route('documents.index') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 hover:bg-slate-800 text-white px-5 py-2.5 text-xs font-semibold shadow-md transition-colors">
                        Khám phá tài liệu ngay
                    </a>
                </div>
            </div>
        @endforelse
    </div>
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $accesses->links(data: ['scrollTo' => '#purchased-documents-list']) }}
    </div>
</div>
