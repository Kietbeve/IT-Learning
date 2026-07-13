<div id="bookmarked-documents-list" class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    <!-- Header Section -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900">Tài liệu yêu thích</h1>
            <p class="text-sm text-slate-500 mt-1">Danh sách tất cả tài liệu bạn đã lưu để xem lại sau.</p>
        </div>
        <div>
            <a href="{{ route('documents.index') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 hover:bg-slate-800 text-white px-5 py-3 text-sm font-semibold transition-colors duration-200">
                Khám phá thêm tài liệu
            </a>
        </div>
    </div>

    <!-- Table Content with Loading State -->
    <div wire:loading.class="opacity-60 transition-opacity duration-200" class="transition-opacity duration-200">
        <!-- Bookmarked Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($favorites as $fav)
            @if($fav->document)
                <article class="flex flex-col rounded-3xl border border-slate-200 bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 relative group">
                    <div class="aspect-[16/9] w-full bg-slate-100 flex items-center justify-center relative border-b border-slate-100 overflow-hidden">
                        @if($fav->document->thumbnail)
                            <img src="{{ $fav->document->thumbnail_url }}" alt="{{ $fav->document->title }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        @else
                            <div class="flex flex-col items-center gap-2 text-slate-400">
                                @if($fav->document->file_type === 'pdf')
                                    <svg class="w-12 h-12 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 9h1.5M9 13h5m-5 4h5"/></svg>
                                @elseif($fav->document->file_type === 'zip')
                                    <svg class="w-12 h-12 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                @else
                                    <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                @endif
                                <span class="text-xs uppercase font-bold tracking-widest">{{ $fav->document->file_type }}</span>
                            </div>
                        @endif

                        <div class="absolute top-4 left-4 right-4 flex justify-between items-center">
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span class="inline-flex items-center gap-1 rounded-xl px-2.5 py-1 text-xs font-semibold bg-white/95 text-slate-800 shadow-sm backdrop-blur-sm">
                                    <svg class="w-3.5 h-3.5 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                    {{ $fav->document->category?->name ?? 'Tài liệu' }}
                                </span>
                                @if($fav->document->subject)
                                    <span class="inline-flex items-center gap-1 rounded-xl px-2.5 py-1 text-xs font-semibold bg-indigo-50 text-indigo-700 shadow-sm">
                                        <svg class="w-3.5 h-3.5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                        {{ $fav->document->subject->name }}
                                    </span>
                                @endif
                            </div>

                            <button wire:click="toggleFavorite({{ $fav->document_id }})" class="h-8 w-8 rounded-full bg-white/95 flex items-center justify-center text-red-500 shadow-sm hover:text-slate-400 transition-colors duration-200">
                                <svg class="w-5 h-5 fill-red-500 text-red-500" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="flex-1 p-6 flex flex-col justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900 group-hover:text-blue-600 transition-colors duration-200 line-clamp-2">
                                <a href="{{ route('documents.show', [$fav->document->id, Str::slug($fav->document->title)]) }}">{{ $fav->document->title }}</a>
                            </h3>
                            <p class="mt-2 text-sm text-slate-500 line-clamp-2">{{ $fav->document->short_description }}</p>
                        </div>

                        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="h-6 w-6 rounded-full bg-slate-900 text-white flex items-center justify-center text-[10px] font-bold">
                                    {{ substr($fav->document->author?->name ?? 'A', 0, 1) }}
                                </div>
                                <span class="text-xs text-slate-600 font-medium">{{ $fav->document->author?->name ?? 'Uploader' }}</span>
                            </div>
                            <div class="text-sm font-semibold text-slate-900">
                                @if($fav->document->product && $fav->document->product->price > 0)
                                    @if($fav->document->product->sale_price)
                                        <span class="text-blue-600 font-bold">{{ number_format($fav->document->product->sale_price) }}đ</span>
                                        <span class="text-xs text-slate-400 line-through ml-1">{{ number_format($fav->document->product->price) }}đ</span>
                                    @else
                                        <span class="text-blue-600 font-bold">{{ number_format($fav->document->product->price) }}đ</span>
                                    @endif
                                @else
                                    <span class="text-emerald-600 font-semibold">Miễn phí</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </article>
            @endif
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-200">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <h4 class="text-lg font-semibold text-slate-900">Chưa lưu tài liệu nào</h4>
                <p class="mt-1 text-sm text-slate-500">Hãy nhấn nút trái tim tại các tài liệu bạn thích để lưu lại đây.</p>
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
        {{ $favorites->links(data: ['scrollTo' => '#bookmarked-documents-list']) }}
    </div>
</div>

