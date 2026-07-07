<div>
    @section('content')
    <div class="max-w-7xl mx-auto py-6">
        <div class="mb-6 flex justify-between items-center">
            <h3 class="text-2xl font-bold text-slate-900">Tủ tài liệu của bạn</h3>
        </div>

        @if($purchasedDocuments->isEmpty())
            {{-- Empty State --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-12 text-center shadow-sm">
                <x-icon name="document-text" class="w-16 h-16 mx-auto text-slate-300 mb-4" />
                <h3 class="text-lg font-semibold text-slate-900">Bạn chưa mua tài liệu nào</h3>
                <p class="mt-1 text-sm text-slate-500 mb-6">Hãy khám phá các tài liệu hữu ích và nâng cao kiến thức của bạn.</p>
                <x-button primary href="{{ route('documents.index') }}" label="Khám phá ngay" icon="magnifying-glass" />
            </div>
        @else
            {{-- Document Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($purchasedDocuments as $item)
                    @php $doc = $documents[$item->document_id] ?? null; @endphp
                    @if($doc)
                    <div class="rounded-xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition-shadow overflow-hidden flex flex-col">
                        {{-- Thumbnail --}}
                        <div class="h-40 bg-slate-100 flex items-center justify-center border-b border-slate-100">
                            @if($doc->thumbnail)
                                <img src="{{ $doc->thumbnail_url }}" alt="{{ $doc->title }}" class="w-full h-full object-cover">
                            @else
                                <x-icon name="document" class="w-16 h-16 text-slate-300" />
                            @endif
                        </div>

                        <div class="p-4 flex-grow flex flex-col">
                            <div class="mb-2 text-xs font-semibold text-blue-600 uppercase tracking-wider">
                                {{ $doc->currentVersion?->category?->name ?? 'Tài liệu' }}
                            </div>

                            <h4 class="text-base font-bold text-slate-900 mb-2 line-clamp-2" title="{{ $doc->title }}">
                                {{ $doc->title }}
                            </h4>

                            <div class="mt-auto pt-4 border-t border-slate-100">
                                <div class="mb-3"></div>

                                <a href="{{ route('documents.show', [$doc->id, \Illuminate\Support\Str::slug($doc->title)]) }}" 
                                   class="block w-full text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>

            <div class="mt-8">
                {{ $purchasedDocuments->links() }}
            </div>
        @endif
    </div>
    @endsection
</div>
