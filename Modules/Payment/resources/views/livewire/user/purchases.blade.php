<div>
    @section('content')
    <div class="max-w-7xl mx-auto pb-8 pt-4 px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header Section -->
        <div class="bg-linear-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-2xl shadow-xl p-8 sm:p-12 text-white relative overflow-hidden">
            {{-- Decorative elements --}}
            <div class="absolute inset-0 overflow-hidden rounded-2xl">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            </div>
            
            <div class="relative z-20 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold mb-2 text-white leading-tight">Tủ tài liệu của bạn</h1>
                    <p class="text-indigo-50 text-sm sm:text-base max-w-xl">Danh sách tất cả tài liệu bạn đã thanh toán và sở hữu vĩnh viễn.</p>
                </div>
                <div>
                    <a href="{{ route('documents.index') }}" class="inline-flex items-center justify-center rounded-xl bg-white text-indigo-600 hover:bg-indigo-50 px-5 py-3 text-sm font-bold shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                        Khám phá thêm tài liệu
                    </a>
                </div>
            </div>
        </div>

        @if($purchasedDocuments->isEmpty())
            {{-- Empty State --}}
            <x-card padding="py-16" class="text-center shadow-sm border border-gray-100">
                <x-icon name="document-text" class="w-16 h-16 mx-auto text-gray-300 mb-4" />
                <h3 class="text-xl font-bold text-blue-900 mb-1">Bạn chưa mua tài liệu nào</h3>
                <p class="text-sm text-gray-500 mb-6">Hãy khám phá các tài liệu hữu ích và nâng cao kiến thức của bạn.</p>
                <x-button primary href="{{ route('documents.index') }}" class="font-bold shadow-md" label="Khám phá ngay" icon="magnifying-glass" />
            </x-card>
        @else
            {{-- Document Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5 lg:gap-6">
                @foreach($purchasedDocuments as $item)
                    @php $doc = $documents[$item->document_id] ?? null; @endphp
                    @if($doc)
                    <article class="flex flex-col h-full group">
                        <x-card padding="none" class="overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1 flex flex-col h-full border border-gray-100">
                            {{-- Thumbnail --}}
                            <div class="h-40 bg-gray-100 flex items-center justify-center border-b border-gray-100 relative overflow-hidden">
                                @if($doc->thumbnail)
                                    <img src="{{ $doc->thumbnail_url }}" alt="{{ $doc->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <x-icon name="document-text" class="w-16 h-16 text-gray-300 group-hover:scale-110 transition-transform duration-300" />
                                @endif
                                <div class="absolute top-3 right-3">
                                    <x-badge flat positive label="Đã sở hữu" />
                                </div>
                            </div>

                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex flex-wrap items-center gap-1.5 mb-3">
                                        <x-badge flat info label="{{ strtoupper($doc->file_type ?? 'DOC') }}" />
                                        <span class="inline-flex items-center gap-1 rounded-lg px-2 py-0.5 text-[10px] font-bold bg-indigo-50 text-indigo-700">
                                            {{ $doc->category?->name ?? 'Tài liệu' }}
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-bold text-blue-900 group-hover:text-indigo-600 transition-colors duration-200 line-clamp-2">
                                        <a href="{{ route('documents.show', [$doc->id, \Illuminate\Support\Str::slug($doc->title)]) }}" class="before:absolute before:inset-0">
                                            {{ $doc->title }}
                                        </a>
                                    </h3>
                                    <p class="mt-2 text-xs text-gray-500 line-clamp-2 leading-relaxed">{{ $doc->short_description }}</p>
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-50 relative z-20">
                                    <a href="{{ route('documents.show', [$doc->id, \Illuminate\Support\Str::slug($doc->title)]) }}" 
                                       class="flex items-center justify-center gap-2 w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-colors duration-200 shadow-sm">
                                        Đọc tài liệu ngay
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                </div>
                            </div>
                        </x-card>
                    </article>
                    @endif
                @endforeach
            </div>

            <div class="mt-8">
                <x-card class="shadow-sm border border-gray-100">
                    {{ $purchasedDocuments->links() }}
                </x-card>
            </div>
        @endif
    </div>
    @endsection
</div>

