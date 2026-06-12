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
                @foreach($purchasedDocuments as $access)
                    <div class="rounded-xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition-shadow overflow-hidden flex flex-col">
                        {{-- Thumbnail --}}
                        <div class="h-40 bg-slate-100 flex items-center justify-center border-b border-slate-100">
                            @if(isset($access->document) && $access->document->thumbnail)
                                <img src="{{ asset('storage/' . $access->document->thumbnail) }}" alt="{{ $access->document->title }}" class="w-full h-full object-cover">
                            @else
                                <x-icon name="document" class="w-16 h-16 text-slate-300" />
                            @endif
                        </div>

                        <div class="p-4 flex-grow flex flex-col">
                            <div class="mb-2 text-xs font-semibold text-blue-600 uppercase tracking-wider">
                                {{ $access->document->category->name ?? 'Tài liệu' }}
                            </div>

                            <h4 class="text-base font-bold text-slate-900 mb-2 line-clamp-2" title="{{ $access->document->title ?? 'Tài liệu bị xóa' }}">
                                {{ $access->document->title ?? 'Tài liệu này không còn tồn tại' }}
                            </h4>

                            <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-xs text-slate-500">
                                    Đã mua: {{ $access->created_at->format('d/m/Y') }}
                                </span>

                                @if($access->document)
                                    <x-button
                                        primary
                                        sm
                                        icon="download"
                                        label="Tải xuống"
                                        wire:click="downloadDocument({{ $access->document->id }})"
                                        wire:target="downloadDocument({{ $access->document->id }})"
                                        wire:loading.attr="disabled"
                                    />
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $purchasedDocuments->links() }}
            </div>
        @endif
    </div>
    @endsection
</div>
