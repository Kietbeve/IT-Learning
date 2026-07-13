<div class="bg-slate-50 antialiased min-h-screen" x-data="{ notification: null }" x-on:notify.window="notification = $event.detail; setTimeout(() => notification = null, 3000)">
    @php
        $placeholders = [
            'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1542831371-29b0f74f9713?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1504639725590-34d0984388bd?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1607799279861-4dd421887fb3?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=600&q=80',
        ];
    @endphp

    <!-- ===== HERO SEARCH ===== -->
    <section class="relative bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 text-white">
        <div class="absolute inset-0 opacity-10 overflow-hidden pointer-events-none">
            <div class="absolute top-10 left-10 w-72 h-72 bg-blue-500 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-purple-600 rounded-full blur-3xl"></div>
        </div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20 text-center relative z-30">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight leading-tight">
                Kho tài liệu <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-300">chất lượng</span>
            </h1>
            <p class="text-lg text-gray-300 mt-3 mb-8 max-w-2xl mx-auto">Khám phá hàng ngàn tài liệu học tập, luận văn và mã nguồn được chọn lọc kỹ càng</p>
            <div class="relative max-w-2xl mx-auto" x-data="{ isOpen: false }" @click.away="isOpen = false">
                <div class="flex items-center bg-white rounded-2xl shadow-2xl overflow-hidden p-1.5 ring-1 ring-white/20 relative z-20">
                    <div class="flex-1 flex items-center pl-3 sm:pl-5 min-w-0">
                        <i class="fas fa-search text-gray-400 mr-2 sm:mr-3 shrink-0"></i>
                        <input type="text" 
                               wire:model.live.debounce.300ms="search"
                               @focus="isOpen = true"
                               @input="isOpen = true"
                               placeholder="Tìm kiếm tài liệu, môn học..." 
                               class="w-full py-2.5 sm:py-3.5 pr-2 text-gray-800 placeholder-gray-400 bg-transparent outline-none text-sm sm:text-base min-w-0">
                        
                        <!-- Loading spinner -->
                        <span wire:loading wire:target="search" class="absolute inset-y-0 right-32 sm:right-40 flex items-center pointer-events-none text-blue-500">
                            <svg class="animate-spin h-4 w-4 sm:h-5 sm:w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </span>
                    </div>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-8 py-2.5 sm:py-3.5 rounded-xl font-semibold flex items-center gap-1 sm:gap-2 transition shrink-0">
                        <span class="hidden sm:inline">Tìm kiếm</span>
                        <span class="sm:hidden">Tìm</span>
                        <i class="fas fa-arrow-right text-xs sm:text-sm"></i>
                    </button>
                </div>

                <!-- Autocomplete Dropdown -->
                <div x-show="isOpen && $wire.search && $wire.search.trim() !== ''"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-1"
                     class="absolute left-0 right-0 mt-2 z-50 rounded-xl border border-gray-200 bg-white shadow-xl max-h-[380px] overflow-y-auto overflow-hidden text-gray-800 text-left"
                     style="display: none;">
                    
                    <div class="px-4 py-2.5 bg-gray-50 text-[11px] font-bold uppercase tracking-wider text-gray-500 flex justify-between items-center border-b border-gray-100">
                        <span>Gợi ý tài liệu</span>
                        <span class="text-blue-600 font-semibold">{{ $documents->total() }} kết quả</span>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @forelse($documents->take(5) as $doc)
                            @php
                                $docThumbnailUrl = $doc->thumbnail_url ?? $placeholders[$doc->id % count($placeholders)];
                            @endphp
                            <a href="{{ route('documents.show', [$doc->id, Str::slug($doc->title)]) }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors duration-200 border-b border-gray-100">
                                <img src="{{ $docThumbnailUrl }}" class="w-12 h-12 rounded-lg object-cover bg-gray-100 border border-gray-200 shrink-0" alt="" />
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-base font-semibold text-blue-900 truncate">{{ strip_tags($doc->title) }}</h4>
                                    <div class="flex items-center gap-2 mt-0.5 text-xs text-gray-500">
                                        <span class="px-1.5 py-0.5 rounded-md bg-blue-50 text-blue-700 font-medium">{{ $doc->category?->name ?? 'Tài liệu' }}</span>
                                        <span>•</span>
                                        <span class="uppercase font-bold text-[10px] text-gray-600">{{ $doc->file_type }}</span>
                                        <span>•</span>
                                        <span>{{ $doc->download_count }} tải</span>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    @if($doc->product && $doc->product->price > 0)
                                        @if($doc->product->sale_price)
                                            <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-lg">{{ number_format($doc->product->sale_price) }}đ</span>
                                        @else
                                            <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-lg">{{ number_format($doc->product->price) }}đ</span>
                                        @endif
                                    @else
                                        <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg">Miễn phí</span>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="px-4 py-6 text-center text-sm text-gray-500">
                                Không tìm thấy tài liệu phù hợp
                            </div>
                        @endforelse
                    </div>

                    @if($documents->total() > 0)
                        <div class="p-2 bg-gray-50 text-center border-t border-gray-100">
                            <button type="button" 
                                    @click="isOpen = false"
                                    class="w-full text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline py-1.5 transition-colors duration-150">
                                Xem tất cả {{ $documents->total() }} kết quả bên dưới &darr;
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FILTER BAR ===== -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100/80 p-4 md:p-5 flex flex-wrap items-center gap-3 md:gap-4">
            <span class="text-sm font-semibold text-gray-500 hidden sm:flex items-center">
                <i class="fas fa-sliders-h mr-2 text-gray-400"></i>Bộ lọc:
            </span>

            <!-- Danh mục -->
            @php
                $catName = 'Danh mục';
                if(!empty($category)) {
                    $found = collect($categories)->firstWhere('slug', $category);
                    if($found) $catName = $found->name;
                }
            @endphp
            <div class="filter-item relative min-w-[150px]" x-data="{ open: false }" @click.away="open = false">
                <div @click="open = !open" class="flex items-center w-full cursor-pointer select-none">
                    <i class="fas fa-folder-open text-slate-400 mr-2"></i>
                    <span class="flex-1 whitespace-nowrap overflow-hidden text-ellipsis mr-3 text-slate-700 font-medium">{{ $catName }}</span>
                    <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200" :class="{'rotate-180': open}"></i>
                </div>
                <div x-show="open" x-transition.opacity.duration.200ms style="display: none;"
                     class="absolute top-full left-0 mt-2 w-56 bg-white border border-slate-100 rounded-xl shadow-[0_10px_25px_-5px_rgba(0,0,0,0.1)] py-1.5 z-[60] max-h-64 overflow-y-auto">
                    <div wire:click="$set('category', '')" @click="open = false" 
                         class="px-4 py-2.5 text-[14px] cursor-pointer transition-colors {{ empty($category) ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                        Tất cả danh mục
                    </div>
                    @foreach($categories as $cat)
                        <div wire:click="$set('category', '{{ $cat->slug }}')" @click="open = false" 
                             class="px-4 py-2.5 text-[14px] cursor-pointer transition-colors {{ $category == $cat->slug ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                            {{ $cat->name }}
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Định dạng -->
            @php
                $typeMap = ['pdf' => 'PDF', 'docx' => 'Word (DOCX)', 'source_code' => 'Source code'];
                $typeName = $typeMap[$selectedResourceType] ?? 'Định dạng';
            @endphp
            <div class="filter-item relative min-w-[140px]" x-data="{ open: false }" @click.away="open = false">
                <div @click="open = !open" class="flex items-center w-full cursor-pointer select-none">
                    <i class="fas fa-file-alt text-slate-400 mr-2"></i>
                    <span class="flex-1 whitespace-nowrap overflow-hidden text-ellipsis mr-3 text-slate-700 font-medium">{{ $typeName }}</span>
                    <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200" :class="{'rotate-180': open}"></i>
                </div>
                <div x-show="open" x-transition.opacity.duration.200ms style="display: none;"
                     class="absolute top-full left-0 mt-2 w-48 bg-white border border-slate-100 rounded-xl shadow-[0_10px_25px_-5px_rgba(0,0,0,0.1)] py-1.5 z-[60]">
                    <div wire:click="$set('selectedResourceType', '')" @click="open = false" 
                         class="px-4 py-2.5 text-[14px] cursor-pointer transition-colors {{ empty($selectedResourceType) ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Tất cả định dạng</div>
                    @foreach($typeMap as $key => $label)
                        <div wire:click="$set('selectedResourceType', '{{ $key }}')" @click="open = false" 
                             class="px-4 py-2.5 text-[14px] cursor-pointer transition-colors {{ $selectedResourceType == $key ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">{{ $label }}</div>
                    @endforeach
                </div>
            </div>

            <!-- Môn học -->
            @php
                $subName = 'Môn học';
                if(!empty($selectedSubject)) {
                    $foundSub = collect($subjects)->firstWhere('id', $selectedSubject);
                    if($foundSub) $subName = $foundSub->name;
                }
            @endphp
            <div class="filter-item relative min-w-[140px]" x-data="{ open: false }" @click.away="open = false">
                <div @click="open = !open" class="flex items-center w-full cursor-pointer select-none">
                    <i class="fas fa-book-open text-slate-400 mr-2"></i>
                    <span class="flex-1 whitespace-nowrap overflow-hidden text-ellipsis mr-3 text-slate-700 font-medium">{{ $subName }}</span>
                    <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200" :class="{'rotate-180': open}"></i>
                </div>
                <div x-show="open" x-transition.opacity.duration.200ms style="display: none;"
                     class="absolute top-full left-0 mt-2 w-56 bg-white border border-slate-100 rounded-xl shadow-[0_10px_25px_-5px_rgba(0,0,0,0.1)] py-1.5 z-[60] max-h-64 overflow-y-auto">
                    <div wire:click="$set('selectedSubject', '')" @click="open = false" 
                         class="px-4 py-2.5 text-[14px] cursor-pointer transition-colors {{ empty($selectedSubject) ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Tất cả môn học</div>
                    @foreach($subjects as $sub)
                        <div wire:click="$set('selectedSubject', '{{ $sub->id }}')" @click="open = false" 
                             class="px-4 py-2.5 text-[14px] cursor-pointer transition-colors {{ $selectedSubject == $sub->id ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">{{ $sub->name }}</div>
                    @endforeach
                </div>
            </div>

            <!-- Giá -->
            @php
                $priceMap = ['free' => 'Miễn phí', 'paid' => 'Có phí'];
                $priceName = $priceMap[$selectedPrice] ?? 'Khoảng giá';
            @endphp
            <div class="filter-item relative min-w-[140px]" x-data="{ open: false }" @click.away="open = false">
                <div @click="open = !open" class="flex items-center w-full cursor-pointer select-none">
                    <i class="fas fa-tag text-slate-400 mr-2"></i>
                    <span class="flex-1 whitespace-nowrap overflow-hidden text-ellipsis mr-3 text-slate-700 font-medium">{{ $priceName }}</span>
                    <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200" :class="{'rotate-180': open}"></i>
                </div>
                <div x-show="open" x-transition.opacity.duration.200ms style="display: none;"
                     class="absolute top-full left-0 mt-2 w-48 bg-white border border-slate-100 rounded-xl shadow-[0_10px_25px_-5px_rgba(0,0,0,0.1)] py-1.5 z-[60]">
                    <div wire:click="$set('selectedPrice', '')" @click="open = false" 
                         class="px-4 py-2.5 text-[14px] cursor-pointer transition-colors {{ empty($selectedPrice) ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">Tất cả mức giá</div>
                    @foreach($priceMap as $key => $label)
                        <div wire:click="$set('selectedPrice', '{{ $key }}')" @click="open = false" 
                             class="px-4 py-2.5 text-[14px] cursor-pointer transition-colors {{ $selectedPrice == $key ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">{{ $label }}</div>
                    @endforeach
                </div>
            </div>

            @if(!empty($category) || !empty($selectedPrice) || !empty($selectedResourceType) || !empty($selectedSubject))
                <button wire:click="resetFilters" class="btn-reset shrink-0">
                    <i class="fas fa-undo-alt"></i> Đặt lại
                </button>
            @endif

            <!-- Sắp xếp -->
            @php
                $sortMap = ['newest' => 'Mới nhất', 'popular' => 'Tải nhiều nhất', 'highest_rated' => 'Đánh giá cao nhất'];
                $sortName = $sortMap[$sort] ?? 'Mới nhất';
            @endphp
            <div class="filter-sort ml-auto relative min-w-[180px]" x-data="{ open: false }" @click.away="open = false">
                <div @click="open = !open" class="flex items-center w-full cursor-pointer select-none">
                    <i class="fas fa-sort-amount-down-alt text-blue-500 mr-2"></i>
                    <span class="flex-1 whitespace-nowrap overflow-hidden text-ellipsis mr-3 text-blue-700 font-semibold">{{ $sortName }}</span>
                    <i class="fas fa-chevron-down text-[10px] text-blue-400 transition-transform duration-200" :class="{'rotate-180': open}"></i>
                </div>
                <div x-show="open" x-transition.opacity.duration.200ms style="display: none;"
                     class="absolute top-full right-0 mt-2 w-56 bg-white border border-slate-100 rounded-xl shadow-[0_10px_25px_-5px_rgba(0,0,0,0.1)] py-1.5 z-[60]">
                    @foreach($sortMap as $key => $label)
                        <div wire:click="$set('sort', '{{ $key }}')" @click="open = false" 
                             class="px-4 py-2.5 text-[14px] cursor-pointer transition-colors flex items-center justify-between {{ $sort == $key ? 'bg-blue-50/60 text-blue-600 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                            {{ $label }}
                            @if($sort == $key) <i class="fas fa-check text-blue-500 text-xs"></i> @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- ===== MAIN CONTENT ===== -->
    <main id="document-list-container" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Tài liệu nổi bật</h2>
                <p class="text-sm text-gray-500 mt-0.5">Hiển thị <span class="font-semibold text-gray-700">{{ $documents->firstItem() ?? 0 }}-{{ $documents->lastItem() ?? 0 }}</span> / <span class="font-semibold text-gray-700">{{ $documents->total() }}</span> tài liệu</p>
            </div>
        </div>

        <!-- Grid -->
        <div wire:loading.class="opacity-60 transition-opacity duration-200" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($documents as $doc)
                @php
                    $thumbnailUrl = $doc->thumbnail_url ?? $placeholders[$doc->id % count($placeholders)];
                @endphp
                
                <div class="doc-card flex flex-col h-full group relative">
                    <!-- Overlay link covering the entire card -->
                    <a href="{{ route('documents.show', [$doc->id, Str::slug($doc->title)]) }}" class="absolute inset-0 z-0"></a>

                    <div class="card-img-wrap block">
                        <img src="{{ $thumbnailUrl }}" alt="{{ $doc->title }}" loading="lazy">
                        <div class="card-img-overlay"></div>
                        
                        <span class="card-badge">{{ strtoupper($doc->file_type) }}</span>
                        
                        @auth
                            @php
                                $isFavorite = $doc->favorites()->where('user_id', auth()->id())->exists();
                            @endphp
                            <button wire:click.prevent="toggleFavorite({{ $doc->id }})" class="card-wishlist relative z-10 {{ $isFavorite ? 'active' : '' }}">
                                <i class="{{ $isFavorite ? 'fas' : 'far' }} fa-heart"></i>
                            </button>
                        @else
                            <button onclick="window.location.href='{{ route('login') }}'" class="card-wishlist relative z-10">
                                <i class="far fa-heart"></i>
                            </button>
                        @endauth
                    </div>

                    <div class="card-body flex flex-col flex-1 relative z-10 pointer-events-none">
                        <h3 class="card-title">
                            <span class="group-hover:text-blue-600 transition-colors">{{ strip_tags($doc->title) }}</span>
                        </h3>
                        <p class="card-desc">{{ Str::limit(strip_tags($doc->description), 80) }}</p>

                        <div class="mt-4 mb-auto pb-4 border-b border-dashed border-gray-200">
                            <div class="flex flex-wrap items-center gap-2 mb-3">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-blue-50/80 text-blue-700 text-[13px] font-semibold border border-blue-100/50">
                                    <i class="fas fa-folder-open text-blue-500"></i> {{ $doc->category?->name ?? 'Tài liệu' }}
                                </span>
                                @if($doc->subject)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-indigo-50/80 text-indigo-700 text-[13px] font-semibold border border-indigo-100/50">
                                        <i class="fas fa-book text-indigo-500"></i> {{ $doc->subject->name }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center gap-5 text-[13px] text-gray-500 px-1">
                                <div class="flex items-center gap-1.5">
                                    <i class="fas fa-cloud-download-alt text-emerald-500 text-sm"></i>
                                    <span class="font-bold text-gray-700">{{ number_format($doc->download_count) }}</span> lượt tải
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <i class="fas fa-star text-amber-400 text-sm"></i>
                                    <span class="font-bold text-gray-700">{{ number_format($doc->reviews_avg_rating ?? 0, 1) }}</span>
                                    <span class="text-gray-400">({{ $doc->reviews_count ?? 0 }})</span>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer mt-auto pt-4">
                            <span class="card-price">
                                @if($doc->product && $doc->product->price > 0)
                                    @if($doc->product->sale_price)
                                        {{ number_format($doc->product->sale_price) }}<small>đ</small>
                                        <span class="original">{{ number_format($doc->product->price) }}đ</span>
                                    @else
                                        {{ number_format($doc->product->price) }}<small>đ</small>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 font-bold text-sm border border-emerald-100">
                                        <i class="fas fa-gift text-emerald-500"></i> Miễn phí
                                    </span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="col-span-full py-16 text-center">
                    <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h4 class="text-xl font-bold text-blue-900">Không tìm thấy tài liệu</h4>
                    <p class="mt-1 text-sm text-slate-500">Thử thay đổi từ khóa hoặc bộ lọc tìm kiếm xem sao nhé.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12 flex justify-center custom-pagination">
            @if ($documents->hasPages())
                {{ $documents->links() }}
            @endif
        </div>
    </main>
</div>

