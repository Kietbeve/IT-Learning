<div class="max-w-7xl mx-auto py-6" x-data="{ notification: null }" x-on:notify.window="notification = $event.detail; setTimeout(() => notification = null, 3000)">
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

    <!-- Hero / Search Section -->
    <div class="mb-8 rounded-3xl bg-slate-900 text-white p-6 md:p-8 shadow-xl relative">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/30 to-purple-600/30 opacity-50 rounded-3xl"></div>
        <div class="relative z-10 max-w-3xl">
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white">Tìm kiếm tài liệu & Đồ án mẫu</h1>
            <div class="mt-5 flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1" x-data="{ isOpen: false }" @click.away="isOpen = false">
                    <input type="text" 
                           id="search-documents"
                           aria-label="Tìm kiếm tài liệu"
                           wire:model.live.debounce.300ms="search" 
                           @focus="isOpen = true"
                           @input="isOpen = true"
                           @keydown.enter="isOpen = false; document.getElementById('document-list-container').scrollIntoView({behavior: 'smooth'})"
                           placeholder="Nhập tiêu đề, mô tả tài liệu hoặc từ khóa..." 
                           autocomplete="off"
                           class="peer w-full rounded-2xl border-0 bg-white/10 px-5 py-4 pl-12 pr-28 text-white placeholder-slate-400 backdrop-blur-md focus:bg-white focus:text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300" />
                    
                    <!-- Search Icon (hidden when loading search) -->
                    <span wire:loading.remove wire:target="search" class="absolute inset-y-0 left-4 inline-flex items-center text-slate-400 pointer-events-none">
                        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8" /><path d="M21 21l-4.35-4.35" /></svg>
                    </span>
                    
                    <!-- Loading Spinner (shown when loading search) -->
                    <span wire:loading wire:target="search" class="absolute inset-y-0 left-4 inline-flex items-center text-blue-500 pointer-events-none">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>

                    <!-- Search count badge -->
                    @if(!empty($search))
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[11px] font-bold px-2.5 py-1 rounded-full bg-white/20 text-slate-200 peer-focus:bg-blue-100 peer-focus:text-blue-700 transition-all duration-200 pointer-events-none"
                              wire:loading.class="hidden" wire:target="search">
                            {{ $documents->total() }} kết quả
                        </span>
                    @endif

                    <!-- Autocomplete Dropdown -->
                    <div x-show="isOpen && $wire.search && $wire.search.trim() !== ''"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="absolute left-0 right-0 mt-2 z-50 rounded-2xl border border-slate-200 bg-white shadow-xl max-h-[380px] overflow-y-auto overflow-hidden text-slate-800"
                         style="display: none;">
                        
                        <div class="px-4 py-2.5 bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-400 flex justify-between items-center border-b border-slate-100">
                            <span>Gợi ý tài liệu</span>
                            <span class="text-blue-600 font-semibold">{{ $documents->total() }} kết quả</span>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @forelse($documents->take(5) as $doc)
                                @php
                                    $docThumbnailUrl = $doc->thumbnail_url ?? $placeholders[$doc->id % count($placeholders)];
                                @endphp
                                <a href="{{ route('documents.show', [$doc->id, Str::slug($doc->title)]) }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition-colors duration-200 border-b border-slate-100">
                                    <img src="{{ $docThumbnailUrl }}" class="w-12 h-12 rounded-lg object-cover bg-slate-100 border border-slate-100 shrink-0" alt="" />
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-sm font-semibold text-slate-800 truncate">{{ $doc->title }}</h4>
                                        <div class="flex items-center gap-2 mt-0.5 text-xs text-slate-500">
                                            <span class="px-1.5 py-0.5 rounded bg-blue-50 text-blue-600 font-medium">{{ $doc->category?->name ?? 'Tài liệu' }}</span>
                                            <span>•</span>
                                            <span class="uppercase font-bold text-[10px] text-slate-600">{{ $doc->file_type }}</span>
                                            <span>•</span>
                                            <span>{{ $doc->download_count }} tải</span>
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        @if($doc->product)
                                            <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-100">{{ number_format($doc->product->price) }}đ</span>
                                        @else
                                            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-100">Miễn phí</span>
                                        @endif
                                    </div>
                                </a>
                            @empty
                                <div class="px-4 py-6 text-center text-sm text-slate-500">
                                    Không tìm thấy tài liệu phù hợp
                                </div>
                            @endforelse
                        </div>

                        @if($documents->total() > 0)
                            <div class="p-2 bg-slate-50 text-center border-t border-slate-100">
                                <button type="button" 
                                        @click="isOpen = false; document.getElementById('document-list-container').scrollIntoView({behavior: 'smooth'})"
                                        class="w-full text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline py-1.5 transition-colors duration-150">
                                    Xem tất cả {{ $documents->total() }} kết quả bên dưới &darr;
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div id="document-list-container" class="grid grid-cols-1 lg:grid-cols-4 gap-8 scroll-mt-24">
        <!-- Sidebar Filters -->
        <div class="space-y-6 lg:col-span-1">
            <!-- Filter Category Card -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-sm font-semibold uppercase tracking-[0.15em] text-slate-500 mb-4">Danh mục</h3>
                <div class="space-y-2">
                    <button wire:click="$set('selectedCategory', null)" 
                            class="w-full text-left flex items-center justify-between rounded-xl px-3 py-2.5 text-sm font-medium transition-colors duration-200 {{ is_null($selectedCategory) ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span>Tất cả danh mục</span>
                    </button>
                    @foreach($categories as $category)
                        <button wire:click="$set('selectedCategory', {{ $category->id }})" 
                                class="w-full text-left flex items-center justify-between rounded-xl px-3 py-2.5 text-sm font-medium transition-colors duration-200 {{ $selectedCategory == $category->id ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900' }}">
                            <span>{{ $category->name }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Filter Attributes Card -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-5">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.15em] text-slate-500">Bộ lọc nâng cao</h3>
                    @if(!empty($search) || !is_null($selectedCategory) || !empty($selectedFileType) || !empty($selectedPrice) || !empty($selectedYear) || !empty($selectedResourceType) || !empty($selectedCustomCategory) || !empty($selectedSubject) || !empty($selectedLanguage))
                        <button wire:click="resetFilters" class="text-xs font-semibold text-rose-500 hover:text-rose-600 transition-colors duration-150">
                            Xóa lọc
                        </button>
                    @endif
                </div>

                <!-- Loại tài nguyên -->
                <div>
                    <label for="filter-resource-type" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Loại tài nguyên</label>
                    <select id="filter-resource-type" wire:model.live="selectedResourceType" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:ring-1 focus:ring-slate-400 focus:outline-none transition-all duration-200">
                        <option value="">Tất cả tài nguyên</option>
                        <option value="pdf">Tài liệu PDF</option>
                        <option value="docx">Văn bản DOCX</option>
                        <option value="source_code">Source Code</option>
                        <option value="do_an">Đồ án</option>
                        <option value="ebook">Ebook / Sách</option>
                    </select>
                </div>

                <!-- Danh mục -->
                <div>
                    <label for="filter-custom-category" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Chủ đề / Lĩnh vực</label>
                    <select id="filter-custom-category" wire:model.live="selectedCustomCategory" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:ring-1 focus:ring-slate-400 focus:outline-none transition-all duration-200">
                        <option value="">Tất cả chủ đề</option>
                        <option value="web">Web Development</option>
                        <option value="mobile">Mobile App</option>
                        <option value="ai_ml">Trí tuệ nhân tạo (AI/ML)</option>
                        <option value="devops">DevOps & Cloud</option>
                        <option value="database">Cơ sở dữ liệu (Database)</option>
                    </select>
                </div>

                <!-- Môn học -->
                <div>
                    <label for="filter-subject" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Môn học</label>
                    <select id="filter-subject" wire:model.live="selectedSubject" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:ring-1 focus:ring-slate-400 focus:outline-none transition-all duration-200">
                        <option value="">Tất cả môn học</option>
                        <option value="lap_trinh_web">Lập trình Web</option>
                        <option value="csdl">Cơ sở dữ liệu</option>
                        <option value="mang_may_tinh">Mạng máy tính</option>
                    </select>
                </div>

                <!-- Ngôn ngữ lập trình -->
                <div>
                    <label for="filter-language" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Ngôn ngữ lập trình</label>
                    <select id="filter-language" wire:model.live="selectedLanguage" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:ring-1 focus:ring-slate-400 focus:outline-none transition-all duration-200">
                        <option value="">Tất cả ngôn ngữ</option>
                        <option value="php">PHP</option>
                        <option value="python">Python</option>
                        <option value="javascript">JavaScript</option>
                        <option value="java">Java</option>
                    </select>
                </div>

                <!-- Định dạng tệp -->
                <div>
                    <label for="filter-file-type" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Định dạng tệp</label>
                    <select id="filter-file-type" wire:model.live="selectedFileType" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:ring-1 focus:ring-slate-400 focus:outline-none transition-all duration-200">
                        <option value="">Tất cả định dạng</option>
                        <option value="pdf">Tài liệu (PDF)</option>
                        <option value="docx">Văn bản (DOCX)</option>
                        <option value="zip">Nén (ZIP)</option>
                    </select>
                </div>

                <!-- Giá thành -->
                <div>
                    <label for="filter-price" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Giá tài liệu</label>
                    <select id="filter-price" wire:model.live="selectedPrice" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:ring-1 focus:ring-slate-400 focus:outline-none transition-all duration-200">
                        <option value="">Tất cả giá</option>
                        <option value="free">Miễn phí</option>
                        <option value="paid">Có phí</option>
                    </select>
                </div>

                <!-- Năm đăng tải -->
                <div>
                    <label for="filter-year" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Năm đăng tải</label>
                    <select id="filter-year" wire:model.live="selectedYear" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-900 focus:border-slate-400 focus:ring-1 focus:ring-slate-400 focus:outline-none transition-all duration-200">
                        <option value="">Tất cả các năm</option>
                        <option value="2026">2026</option>
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                    </select>
                </div>

                <!-- Reset Filters Big Button -->
                @if(!empty($search) || !is_null($selectedCategory) || !empty($selectedFileType) || !empty($selectedPrice) || !empty($selectedYear) || !empty($selectedResourceType) || !empty($selectedCustomCategory) || !empty($selectedSubject) || !empty($selectedLanguage))
                    <button wire:click="resetFilters" class="w-full mt-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2.5 text-sm transition-all duration-200 flex items-center justify-center gap-1.5 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Xóa tất cả bộ lọc
                    </button>
                @endif
            </div>
        </div>

        <!-- Documents Section -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Toolbar -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-3xl border border-slate-200 shadow-sm">
                <div class="text-sm text-slate-500 font-medium">
                    Tìm thấy <span class="text-slate-900 font-semibold">{{ $documents->total() }}</span> tài liệu
                </div>
                <div class="flex items-center gap-3 self-end sm:self-auto">
                    <span class="text-sm text-slate-500 font-medium whitespace-nowrap">Sắp xếp:</span>
                    <select wire:model.live="sort" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-900 focus:border-slate-400 focus:outline-none">
                        <option value="newest">Mới nhất</option>
                        <option value="popular">Tải nhiều nhất</option>
                        <option value="highest_rated">Đánh giá cao nhất</option>
                    </select>
                </div>
            </div>

            <!-- Documents Grid -->
            <div wire:loading.class="opacity-60 transition-opacity duration-200" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($documents as $doc)
                    @php
                        $thumbnailUrl = $doc->thumbnail_url ?? $placeholders[$doc->id % count($placeholders)];
                    @endphp

                    <a href="{{ route('documents.show', [$doc->id, Str::slug($doc->title)]) }}" class="group flex flex-col rounded-2xl border border-slate-200/80 bg-white overflow-hidden shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        <!-- Thumbnail -->
                        <div class="aspect-[16/10] w-full overflow-hidden relative bg-slate-100">
                            <img src="{{ $thumbnailUrl }}" 
                                 alt="{{ $doc->title }}" 
                                 class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                 loading="lazy" />
                            <!-- File type badge top-left -->
                            <div class="absolute top-3 left-3">
                                <span class="inline-flex items-center gap-1 rounded-lg px-2 py-1 text-[10px] font-bold uppercase tracking-wide bg-white/90 text-slate-700 shadow-sm backdrop-blur-sm border border-white/50">
                                    @if($doc->file_type === 'pdf')
                                        <svg class="w-3 h-3 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                                    @elseif($doc->file_type === 'zip')
                                        <svg class="w-3 h-3 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                    @else
                                        <svg class="w-3 h-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                                    @endif
                                    {{ strtoupper($doc->file_type) }}
                                </span>
                            </div>

                            <!-- Price badge top-right -->
                            <div class="absolute top-3 right-3">
                                @if($doc->product)
                                    <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-[10px] font-bold bg-white/90 text-amber-600 shadow-sm backdrop-blur-sm border border-white/50">
                                        {{ number_format($doc->product->price) }}đ
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-[10px] font-bold bg-white/90 text-emerald-600 shadow-sm backdrop-blur-sm border border-white/50">
                                        Miễn phí
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="flex-1 px-5 pt-4 pb-4 flex flex-col">
                            <!-- Category badge -->
                            <span class="inline-block self-start rounded-lg px-2.5 py-1 text-xs font-semibold bg-blue-50 text-blue-600 mb-2.5">
                                {{ $doc->category?->name ?? 'Tài liệu' }}
                            </span>

                            <!-- Title -->
                            <h3 class="text-base font-bold text-slate-900 leading-snug line-clamp-2 group-hover:text-blue-600 transition-colors duration-200">
                                {{ $doc->title }}
                            </h3>

                            <!-- Short Description -->
                            @if($doc->short_description)
                                <p class="mt-1.5 text-xs text-slate-500 leading-relaxed line-clamp-2">
                                    {{ $doc->short_description }}
                                </p>
                            @endif

                            <!-- Tags -->
                            @if($doc->tags->isNotEmpty())
                                <div class="mt-2 flex flex-wrap gap-1">
                                    @foreach($doc->tags->take(3) as $tag)
                                        <span class="inline-flex items-center rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-medium text-slate-600">
                                            #{{ $tag->name }}
                                        </span>
                                    @endforeach
                                    @if($doc->tags->count() > 3)
                                        <span class="inline-flex items-center text-[10px] text-slate-400 font-medium">+{{ $doc->tags->count() - 3 }}</span>
                                    @endif
                                </div>
                            @endif

                            <!-- Footer: meta + bookmark -->
                            <div class="mt-auto pt-4 flex items-center justify-between border-t border-slate-100">
                                <div class="flex items-center gap-3 text-xs text-slate-500 font-medium">
                                    <!-- Download count -->
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        {{ $doc->download_count >= 1000 ? number_format($doc->download_count / 1000, 1) . 'k' : number_format($doc->download_count) }}
                                    </span>
                                    <!-- Favorite count -->
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-rose-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                        {{ $doc->favorite_count }}
                                    </span>
                                    <!-- Published date -->
                                    <span class="inline-flex items-center gap-1 text-slate-400">
                                        {{ $doc->published_at ? $doc->published_at->format('d/m/Y') : ($doc->created_at ? $doc->created_at->format('d/m/Y') : '') }}
                                    </span>
                                </div>

                                <!-- Favorite button -->
                                <button wire:click.prevent="toggleFavorite({{ $doc->id }})" 
                                        class="shrink-0 h-9 w-9 rounded-xl flex items-center justify-center text-slate-300 hover:text-red-500 hover:bg-red-50 transition-all duration-200"
                                        title="Lưu vào yêu thích">
                                    @if(Auth::check() && $doc->favorites->isNotEmpty())
                                        <svg class="w-5 h-5 fill-red-500 text-red-500" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    @endif
                                </button>
                            </div>
                        </div>
                    </a>
                @empty
                    <!-- Empty State -->
                    <div class="col-span-full py-16 text-center">
                        <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h4 class="text-lg font-semibold text-slate-900">Không tìm thấy tài liệu</h4>
                        <p class="mt-1 text-sm text-slate-500">Thử thay đổi từ khóa hoặc bộ lọc tìm kiếm xem sao nhé.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                @if ($documents->hasPages())
                    <div class="flex items-center justify-between border-t border-slate-100 pt-6">
                        <!-- Mobile pagination -->
                        <div class="flex flex-1 justify-between sm:hidden">
                            @if ($documents->onFirstPage())
                                <span class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-slate-400 bg-white border border-slate-200 rounded-xl cursor-not-allowed select-none">
                                    Trước
                                </span>
                            @else
                                <button wire:click="previousPage('page')" 
                                        x-on:click="document.getElementById('document-list-container').scrollIntoView({behavior: 'smooth'})"
                                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition duration-150">
                                    Trước
                                </button>
                            @endif

                            @if ($documents->hasMorePages())
                                <button wire:click="nextPage('page')" 
                                        x-on:click="document.getElementById('document-list-container').scrollIntoView({behavior: 'smooth'})"
                                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition duration-150">
                                    Sau
                                </button>
                            @else
                                <span class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-slate-400 bg-white border border-slate-200 rounded-xl cursor-not-allowed select-none">
                                    Sau
                                </span>
                            @endif
                        </div>

                        <!-- Desktop pagination -->
                        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-slate-500 font-medium">
                                    Hiển thị từ <span class="font-semibold text-slate-900">{{ $documents->firstItem() }}</span> đến <span class="font-semibold text-slate-900">{{ $documents->lastItem() }}</span> trong tổng số <span class="font-semibold text-slate-900">{{ $documents->total() }}</span> tài liệu
                                </p>
                            </div>

                            <div>
                                <nav class="isolate inline-flex -space-x-px rounded-xl gap-1" aria-label="Pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($documents->onFirstPage())
                                        <span class="relative inline-flex items-center justify-center w-10 h-10 text-slate-300 bg-white border border-slate-200 rounded-xl cursor-not-allowed select-none">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                        </span>
                                    @else
                                        <button wire:click="previousPage('page')" 
                                                x-on:click="document.getElementById('document-list-container').scrollIntoView({behavior: 'smooth'})"
                                                class="relative inline-flex items-center justify-center w-10 h-10 text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition duration-150">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                        </button>
                                    @endif

                                    {{-- Page Links --}}
                                    @php
                                        $start = max($documents->currentPage() - 2, 1);
                                        $end = min($start + 4, $documents->lastPage());
                                        if ($end - $start < 4) {
                                            $start = max($end - 4, 1);
                                        }
                                    @endphp

                                    @if ($start > 1)
                                        <button wire:click="gotoPage(1, 'page')" 
                                                x-on:click="document.getElementById('document-list-container').scrollIntoView({behavior: 'smooth'})"
                                                class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition duration-150">
                                            1
                                        </button>
                                        @if ($start > 2)
                                            <span class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-slate-400 select-none">...</span>
                                        @endif
                                    @endif

                                    @for ($page = $start; $page <= $end; $page++)
                                        @if ($page == $documents->currentPage())
                                            <span class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-bold text-white bg-blue-600 rounded-xl shadow-md shadow-blue-500/20 select-none">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <button wire:click="gotoPage({{ $page }}, 'page')" 
                                                    x-on:click="document.getElementById('document-list-container').scrollIntoView({behavior: 'smooth'})"
                                                    class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition duration-150">
                                                {{ $page }}
                                            </button>
                                        @endif
                                    @endfor

                                    @if ($end < $documents->lastPage())
                                        @if ($end < $documents->lastPage() - 1)
                                            <span class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-slate-400 select-none">...</span>
                                        @endif
                                        <button wire:click="gotoPage({{ $documents->lastPage() }}, 'page')" 
                                                x-on:click="document.getElementById('document-list-container').scrollIntoView({behavior: 'smooth'})"
                                                class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition duration-150">
                                            {{ $documents->lastPage() }}
                                        </button>
                                    @endif

                                    {{-- Next Page Link --}}
                                    @if ($documents->hasMorePages())
                                        <button wire:click="nextPage('page')" 
                                                x-on:click="document.getElementById('document-list-container').scrollIntoView({behavior: 'smooth'})"
                                                class="relative inline-flex items-center justify-center w-10 h-10 text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition duration-150">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                        </button>
                                    @else
                                        <span class="relative inline-flex items-center justify-center w-10 h-10 text-slate-300 bg-white border border-slate-200 rounded-xl cursor-not-allowed select-none">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                        </span>
                                    @endif
                                </nav>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
