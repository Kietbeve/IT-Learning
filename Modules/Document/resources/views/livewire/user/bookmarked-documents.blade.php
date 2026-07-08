<div id="bookmarked-documents-list" class="max-w-7xl mx-auto pb-8 pt-4 px-4 sm:px-6 lg:px-8 space-y-8">

    <!-- Header Section -->
    <div class="bg-linear-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-2xl shadow-xl p-8 sm:p-12 text-white relative overflow-hidden">
        {{-- Decorative elements --}}
        <div class="absolute inset-0 overflow-hidden rounded-2xl">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        </div>
        
        <div class="relative z-20 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2">Tài liệu yêu thích</h1>
                <p class="text-indigo-50 text-sm sm:text-base max-w-xl">Danh sách tất cả tài liệu bạn đã lưu để xem lại sau.</p>
            </div>
            <div>
                <a href="{{ route('documents.index') }}" class="inline-flex items-center justify-center rounded-xl bg-white text-indigo-600 hover:bg-indigo-50 px-5 py-3 text-sm font-bold shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    Khám phá thêm tài liệu
                </a>
            </div>
        </div>
    </div>

    <!-- Table Content with Loading State -->
    <div wire:loading.class="opacity-60 transition-opacity duration-200" class="transition-opacity duration-200">
        <!-- Bookmarked Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5 lg:gap-6">
        @forelse($favorites as $fav)
            @if($fav->document)
                @php
                    $thumbnailUrl = $fav->document->thumbnail_url;
                    
                    // Lấy màu nền cho icon dựa trên file extension
                    $iconBgColor = 'bg-linear-to-br from-blue-50 to-indigo-50';
                    $iconColor = 'text-indigo-400';
                    $badgeColor = 'primary';
                    
                    if ($fav->document->file_type === 'pdf') {
                        $iconBgColor = 'bg-linear-to-br from-orange-50 to-red-50';
                        $iconColor = 'text-orange-400';
                        $badgeColor = 'warning';
                    } elseif ($fav->document->file_type === 'zip' || $fav->document->file_type === 'rar') {
                        $iconBgColor = 'bg-linear-to-br from-amber-50 to-yellow-50';
                        $iconColor = 'text-amber-400';
                        $badgeColor = 'warning';
                    } elseif ($fav->document->file_type === 'docx' || $fav->document->file_type === 'doc') {
                        $iconBgColor = 'bg-linear-to-br from-blue-50 to-indigo-50';
                        $iconColor = 'text-indigo-400';
                        $badgeColor = 'info';
                    } elseif ($fav->document->file_type === 'xlsx' || $fav->document->file_type === 'xls') {
                        $iconBgColor = 'bg-linear-to-br from-green-50 to-emerald-50';
                        $iconColor = 'text-green-400';
                        $badgeColor = 'positive';
                    }
                @endphp

                <article class="flex flex-col h-full group">
                    <x-card padding="none" class="overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1 flex flex-col h-full border border-gray-100">
                        {{-- Document Card Header Image --}}
                        @if($thumbnailUrl)
                            <div class="h-40 relative bg-gray-100 border-b border-gray-100 overflow-hidden">
                                <img src="{{ $thumbnailUrl }}" alt="{{ $fav->document->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                                
                                <div class="absolute top-4 right-4 z-10">
                                    <button wire:click="toggleFavorite({{ $fav->document_id }})" class="h-8 w-8 rounded-full bg-white/95 flex items-center justify-center text-red-500 shadow-sm hover:text-gray-400 hover:scale-110 transition-all duration-200" title="Bỏ lưu">
                                        <x-icon name="heart" class="w-5 h-5 fill-current" solid />
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="h-40 {{ $iconBgColor }} flex items-center justify-center border-b border-gray-100 overflow-hidden relative">
                                <x-icon name="document-text" class="w-16 h-16 {{ $iconColor }} group-hover:scale-110 transition-transform duration-300" />
                                
                                <div class="absolute top-4 right-4 z-10">
                                    <button wire:click="toggleFavorite({{ $fav->document_id }})" class="h-8 w-8 rounded-full bg-white/95 flex items-center justify-center text-red-500 shadow-sm hover:text-gray-400 hover:scale-110 transition-all duration-200" title="Bỏ lưu">
                                        <x-icon name="heart" class="w-5 h-5 fill-current" solid />
                                    </button>
                                </div>
                            </div>
                        @endif

                        {{-- Card Body --}}
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex flex-wrap items-center gap-1.5 mb-3">
                                    <x-badge flat :color="$badgeColor" label="{{ strtoupper($fav->document->file_type ?? 'DOC') }}" />
                                    <span class="inline-flex items-center gap-1 rounded-lg px-2 py-0.5 text-[10px] font-bold bg-indigo-50 text-indigo-700">
                                        {{ $fav->document->category?->name ?? 'Tài liệu' }}
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold text-blue-900 group-hover:text-indigo-600 transition-colors duration-200 line-clamp-2">
                                    <a href="{{ route('documents.show', [$fav->document_id, Str::slug($fav->document->title)]) }}" class="before:absolute before:inset-0">
                                        {{ $fav->document->title }}
                                    </a>
                                </h3>
                                <p class="mt-2 text-xs text-gray-500 line-clamp-2 leading-relaxed">{{ $fav->document->short_description }}</p>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-between relative z-20">
                                <div class="flex items-center gap-2">
                                    <div class="h-6 w-6 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-[10px] font-bold">
                                        {{ substr($fav->document->author?->name ?? 'A', 0, 1) }}
                                    </div>
                                    <span class="text-xs text-gray-600 font-medium">{{ $fav->document->author?->name ?? 'Uploader' }}</span>
                                </div>
                                <div class="text-sm font-bold">
                                    @if($fav->document->product && $fav->document->product->price > 0)
                                        @if($fav->document->product->sale_price)
                                            <span class="text-indigo-600">{{ number_format($fav->document->product->sale_price) }}đ</span>
                                            <span class="text-[10px] text-gray-400 line-through ml-1 font-medium">{{ number_format($fav->document->product->price) }}đ</span>
                                        @else
                                            <span class="text-indigo-600">{{ number_format($fav->document->product->price) }}đ</span>
                                        @endif
                                    @else
                                        <span class="text-green-600">Miễn phí</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </x-card>
                </article>
            @endif
        @empty
            <div class="col-span-full">
                <x-card padding="py-16" class="text-center shadow-sm border border-gray-100">
                    <x-icon name="heart" class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                    <h4 class="text-lg font-bold text-blue-900 mb-1">Chưa lưu tài liệu nào</h4>
                    <p class="text-sm text-gray-500">Hãy nhấn nút trái tim tại các tài liệu bạn thích để lưu lại đây.</p>
                    <x-button primary class="mt-6 font-bold shadow-md" href="{{ route('documents.index') }}" label="Khám phá tài liệu ngay" />
                </x-card>
            </div>
        @endforelse
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        @if ($favorites->hasPages())
            <x-card class="shadow-sm border border-gray-100">
                {{ $favorites->links(data: ['scrollTo' => '#bookmarked-documents-list']) }}
            </x-card>
        @endif
    </div>
</div>

