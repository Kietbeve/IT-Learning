<!-- resources/views/livewire/admin/document/create.blade.php -->
<div class="w-full py-4">
    <!-- ===== FORM UPLOAD ===== -->
    <div class="w-full bg-white rounded-[20px] shadow-[0_20px_40px_rgba(0,0,0,0.06)] px-5 py-6 sm:px-9 sm:py-8 transition-all">

        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-800">Đăng tài liệu</h1>
                <p class="text-[13px] text-gray-500 mt-0.5">Chia sẻ kiến thức với cộng đồng IT Learning</p>
            </div>
        </div>

        <form wire:submit.prevent="save" novalidate>
            <!-- Tiêu đề -->
            <div class="mb-4">
                <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">
                    Tiêu đề tài liệu <span class="text-red-500">*</span>
                </label>
                <input type="text" wire:model.blur="title" placeholder="Nhập tiêu đề tài liệu..." required
                    class="w-full px-3.5 py-2.5 border-[1.5px] border-[#dce2ec] rounded-lg text-sm bg-[#fafcff] text-[#0b1e3a] focus:outline-none focus:border-[#2a7de1] focus:ring-4 focus:ring-[#2a7de1]/10 transition-all" />
                @error('title') <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Danh mục + Môn học -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div x-data="{ open: false }">
                    <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">
                        Danh mục <span class="text-red-500">*</span>
                    </label>
                    <div class="relative w-full" @click.away="open = false">
                        <div @click="open = !open" 
                             class="flex items-center justify-between w-full px-3.5 py-2.5 border-[1.5px] border-[#dce2ec] rounded-lg text-sm bg-[#fafcff] text-[#0b1e3a] cursor-pointer hover:border-[#2a7de1] transition-all">
                            <span class="truncate">
                                @if($category_id)
                                    {{ collect($categories)->firstWhere('id', $category_id)?->name ?? '-- Chọn danh mục --' }}
                                @else
                                    <span class="text-gray-400">-- Chọn danh mục --</span>
                                @endif
                            </span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                        <div x-show="open" x-cloak
                             class="absolute z-50 w-full mt-1 bg-white rounded-lg shadow-lg border border-gray-100 overflow-hidden"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95">
                            <div class="max-h-48 overflow-y-auto py-1">
                                @foreach($categories as $cat)
                                    <div wire:click="$set('category_id', '{{ $cat->id }}'); open = false"
                                         class="px-3.5 py-2 text-sm cursor-pointer hover:bg-[#f0f6ff] transition-colors {{ $category_id == $cat->id ? 'text-[#2a7de1] font-medium bg-[#f0f6ff]' : 'text-[#1a2b4a]' }}">
                                        {{ $cat->name }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @error('category_id') <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div x-data="{ open: false }">
                    <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">
                        Môn học <span class="text-red-500">*</span>
                    </label>
                    <div class="relative w-full" @click.away="open = false">
                        <div @click="open = !open" 
                             class="flex items-center justify-between w-full px-3.5 py-2.5 border-[1.5px] border-[#dce2ec] rounded-lg text-sm bg-[#fafcff] text-[#0b1e3a] cursor-pointer hover:border-[#2a7de1] transition-all"
                             :class="{ 'opacity-50 pointer-events-none': {{ count($subjects) == 0 ? 'true' : 'false' }} }">
                            <span class="truncate">
                                @if($subject_id)
                                    {{ collect($subjects)->firstWhere('id', $subject_id)?->name ?? '-- Chọn môn --' }}
                                @else
                                    <span class="text-gray-400">-- Chọn môn --</span>
                                @endif
                            </span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                        <div x-show="open" x-cloak
                             class="absolute z-50 w-full mt-1 bg-white rounded-lg shadow-lg border border-gray-100 overflow-hidden"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95">
                            <div class="max-h-48 overflow-y-auto py-1">
                                @foreach($subjects as $sub)
                                    <div wire:click="$set('subject_id', '{{ $sub->id }}'); open = false"
                                         class="px-3.5 py-2 text-sm cursor-pointer hover:bg-[#f0f6ff] transition-colors {{ $subject_id == $sub->id ? 'text-[#2a7de1] font-medium bg-[#f0f6ff]' : 'text-[#1a2b4a]' }}">
                                        {{ $sub->name }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @error('subject_id') <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Mô tả ngắn -->
            <div class="mb-4">
                <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">
                    Mô tả ngắn <span class="text-red-500">*</span>
                </label>
                <input type="text" wire:model.blur="short_description" placeholder="Tóm tắt nội dung chính (tối đa 200 ký tự)" required maxlength="200"
                    class="w-full px-3.5 py-2.5 border-[1.5px] border-[#dce2ec] rounded-lg text-sm bg-[#fafcff] text-[#0b1e3a] focus:outline-none focus:border-[#2a7de1] focus:ring-4 focus:ring-[#2a7de1]/10 transition-all" />
                <div class="flex justify-between mt-1">
                    @error('short_description') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                    <span class="text-xs text-gray-400">{{ strlen($short_description) }}/200</span>
                </div>
            </div>

            <!-- Mô tả chi tiết -->
            <div class="mb-4">
                <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Mô tả chi tiết</label>
                <textarea wire:model.blur="description" rows="3" placeholder="Mô tả chi tiết về tài liệu..." 
                    class="w-full px-3.5 py-2.5 border-[1.5px] border-[#dce2ec] rounded-lg text-sm bg-[#fafcff] text-[#0b1e3a] focus:outline-none focus:border-[#2a7de1] focus:ring-4 focus:ring-[#2a7de1]/10 transition-all resize-none"></textarea>
                @error('description') <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Thẻ (Tags) -->
            <div class="mb-4" x-data="{ showCustom: false }">
                <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">
                    Thẻ (Tags) <span class="text-gray-400 font-normal text-[11px]">(phân cách bằng dấu phẩy)</span>
                </label>
                
                <div wire:ignore x-init="
                    $nextTick(() => {
                        const select = $refs.tagSelect;
                        if (select.tomselect) select.tomselect.destroy();
                        const ts = new TomSelect(select, {
                            maxItems: null,
                            plugins: ['remove_button'],
                            placeholder: 'Chọn tags...',
                            onChange: (values) => { 
                                @this.set('selectedTags', values);
                            },
                        });
                    });
                ">
                    <select multiple x-ref="tagSelect" class="w-full text-sm">
                        @foreach($allTags as $tag)
                            <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2 mt-2.5">
                    <input type="checkbox" id="customTagsToggle" x-model="showCustom" 
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-3.5 w-3.5" />
                    <label for="customTagsToggle" class="text-[13px] font-medium text-gray-600 cursor-pointer">
                        Tag không có trong danh sách?
                    </label>
                </div>

                <div x-show="showCustom" x-transition class="mt-2">
                    <input type="text" wire:model="customTagsInput" placeholder="VD: toán, đại số, bài tập" 
                        class="w-full px-3.5 py-2.5 border-[1.5px] border-[#dce2ec] rounded-lg text-sm bg-[#fafcff] text-[#0b1e3a] focus:outline-none focus:border-[#2a7de1] focus:ring-4 focus:ring-[#2a7de1]/10 transition-all" />
                </div>
                @error('selectedTags') <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Tệp đính kèm -->
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Tệp đính kèm <span class="text-red-500">*</span>
                </label>
                
                <div class="relative border-2 border-dashed border-[#dce2ec] rounded-[14px] px-5 py-4 bg-[#f8faff] flex items-center gap-3.5 hover:border-[#2a7de1] hover:bg-[#f0f6ff] transition-all">
                    <input type="file" wire:model="originalFile" 
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" 
                        accept=".pdf,.doc,.docx,.zip" />
                    
                    <svg class="w-6 h-6 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    
                    <div class="flex-1 min-w-0">
                        @if ($originalFile)
                            <span class="font-medium text-gray-700 block truncate">{{ $originalFile->getClientOriginalName() }}</span>
                            <small class="block text-gray-400 text-xs">{{ number_format($originalFile->getSize() / 1024 / 1024, 2) }} MB</small>
                        @else
                            <span class="font-medium text-gray-700 block">Chọn file tài liệu</span>
                            <small class="block text-gray-400 text-xs">PDF, DOCX, ZIP ... (tối đa {{ \App\Services\SettingService::get('max_document_size_mb', 50) }}MB)</small>
                        @endif
                    </div>
                    
                    @if ($originalFile)
                        <button type="button" wire:click="removeSelectedFile" class="relative z-20 text-red-500 hover:bg-red-50 p-1.5 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    @else
                        <svg class="w-4 h-4 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    @endif
                </div>

                <div wire:loading wire:target="originalFile" class="mt-2 text-sm text-blue-600 font-medium flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                    Đang tải tệp lên...
                </div>
                @error('originalFile') <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Ảnh bìa & Ảnh mô tả -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <!-- Ảnh bìa -->
                <div class="border-[1.5px] border-[#e5ebf3] rounded-[14px] p-3 bg-[#fafcff] flex flex-col gap-1.5">
                    <div class="text-sm font-semibold text-gray-700">Ảnh bìa <span class="text-red-500">*</span></div>
                    
                    <div class="w-full h-[110px] bg-[#eef3fa] rounded-[10px] flex items-center justify-center text-[#6b7f9e] text-[13px] overflow-hidden relative">
                        @if ($thumbnailFile)
                            <img src="{{ $thumbnailFile->temporaryUrl() }}" class="w-full h-full object-cover" />
                            <button type="button" wire:click="removeSelectedThumbnail" class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-md hover:bg-red-600 shadow z-20">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        @else
                            <div class="flex flex-col items-center gap-1">
                                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a1 1 0 011.414 0L15 17m0 0l-3-3m3 3l3-3m0 0l-3-3m3 3V4"/></svg>
                                <span>Chưa có ảnh</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="relative border-2 border-dashed border-[#dce2ec] rounded-[14px] px-4 py-2 mt-1 bg-[#f8faff] flex items-center gap-2 cursor-pointer hover:border-[#2a7de1] hover:bg-[#f0f6ff] transition-all justify-center">
                        <input type="file" wire:model="thumbnailFile" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*" />
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span class="text-sm text-gray-600 font-medium">Tải ảnh bìa <span class="font-normal text-xs text-gray-400">(tối đa {{ \App\Services\SettingService::get('max_thumbnail_size_mb', 5) }}MB)</span></span>
                    </div>
                    <div wire:loading wire:target="thumbnailFile" class="text-xs text-blue-600 mt-1 text-center">Đang tải...</div>
                    @error('thumbnailFile') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Ảnh mô tả -->
                <div class="border-[1.5px] border-[#e5ebf3] rounded-[14px] p-3 bg-[#fafcff] flex flex-col gap-1.5">
                    <div class="text-sm font-semibold text-gray-700">Ảnh mô tả <span class="text-gray-400 font-normal text-xs">(có thể bỏ trống)</span></div>
                    
                    <div class="w-full min-h-[110px] bg-[#eef3fa] rounded-[10px] flex items-center justify-center text-[#6b7f9e] text-[13px] relative p-2">
                        @if ($galleryFiles && count($galleryFiles) > 0)
                            <div class="grid grid-cols-3 gap-1 w-full">
                                @foreach($galleryFiles as $index => $galleryFile)
                                    @if ($galleryFile && !in_array($index, $excludedGalleryIndices ?? []))
                                        <div class="relative rounded-md overflow-hidden aspect-square border border-white">
                                            <img src="{{ $galleryFile->temporaryUrl() }}" class="w-full h-full object-cover" />
                                            <button type="button" wire:click="removeGalleryImage({{ $index }})" class="absolute top-0.5 right-0.5 bg-red-500/80 text-white p-0.5 rounded shadow z-20 hover:bg-red-600">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center gap-1">
                                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                <span>Chưa có ảnh</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="relative border-2 border-dashed border-[#dce2ec] rounded-[14px] px-4 py-2 mt-1 bg-[#f8faff] flex items-center gap-2 cursor-pointer hover:border-[#2a7de1] hover:bg-[#f0f6ff] transition-all justify-center">
                        <input type="file" wire:model="galleryFiles" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*" multiple />
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span class="text-sm text-gray-600 font-medium">Tải ảnh mô tả <span class="font-normal text-xs text-gray-400">(tối đa {{ \App\Services\SettingService::get('max_gallery_size_mb', 5) }}MB/ảnh)</span></span>
                    </div>
                    <div wire:loading wire:target="galleryFiles" class="text-xs text-blue-600 mt-1 text-center">Đang tải...</div>
                    @error('galleryFiles') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Thiết lập bán -->
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Thiết lập bán <span class="text-red-500">*</span></label>
                <div class="flex flex-wrap gap-x-10 gap-y-3 pt-1.5">
                    <label class="flex items-center gap-2.5 font-medium text-[15px] text-[#1a2b4a] cursor-pointer">
                        <input type="radio" wire:model.live="isPaid" value="0" name="isPaidRadio" class="w-[18px] h-[18px] text-[#2a7de1] focus:ring-[#2a7de1]" />
                        Miễn phí
                    </label>
                    <label class="flex items-center gap-2.5 font-medium text-[15px] text-[#1a2b4a] cursor-pointer">
                        <input type="radio" wire:model.live="isPaid" value="1" name="isPaidRadio" class="w-[18px] h-[18px] text-[#2a7de1] focus:ring-[#2a7de1]" />
                        Bán có phí
                    </label>
                </div>
                @if((bool)$isPaid)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div class="flex flex-col h-full">
                        <label class="block text-[12px] font-medium text-gray-500 mb-1">Giá bán (VND) <span class="text-red-500">*</span></label>
                        <input type="number" wire:model.blur="price" placeholder="10000" x-on:input="$el.value = $el.value.replace(/^0+/, '') || '0'"
                            class="mt-auto w-full px-3.5 py-2.5 border-[1.5px] border-[#dce2ec] rounded-lg text-sm bg-white text-[#0b1e3a] focus:outline-none focus:border-[#2a7de1] focus:ring-4 focus:ring-[#2a7de1]/10 transition-all [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" />
                        @error('price') <span class="text-[11px] text-red-500 font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex flex-col h-full">
                        <label class="block text-[12px] font-medium text-gray-500 mb-1">Giá khuyến mãi (VND)</label>
                        <input type="number" wire:model.blur="sale_price" placeholder="Tùy chọn" x-on:input="$el.value = $el.value.replace(/^0+/, '') || ''"
                            class="mt-auto w-full px-3.5 py-2.5 border-[1.5px] border-[#dce2ec] rounded-lg text-sm bg-white text-[#0b1e3a] focus:outline-none focus:border-[#2a7de1] focus:ring-4 focus:ring-[#2a7de1]/10 transition-all [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" />
                        @error('sale_price') <span class="text-[11px] text-red-500 font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                @endif
            </div>

            <!-- Hình thức xuất bản -->
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Hình thức xuất bản <span class="text-red-500">*</span></label>
                <div class="flex flex-wrap gap-x-10 gap-y-3 pt-1.5">
                    <label class="flex items-center gap-2.5 font-medium text-[15px] text-[#1a2b4a] cursor-pointer">
                        <input type="radio" wire:model="visibility" value="public" class="w-[18px] h-[18px] text-[#2a7de1] focus:ring-[#2a7de1]" />
                        Công khai
                    </label>
                    <label class="flex items-center gap-2.5 font-medium text-[15px] text-[#1a2b4a] cursor-pointer">
                        <input type="radio" wire:model="visibility" value="private" class="w-[18px] h-[18px] text-[#2a7de1] focus:ring-[#2a7de1]" />
                        Riêng tư
                    </label>
                </div>
            </div>



            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.documents.index') }}" wire:navigate class="px-5 py-2.5 text-[15px] font-medium text-gray-600 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                    Hủy bỏ
                </a>
                <button type="submit" 
                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-[15px] font-semibold rounded-xl transition-all duration-300 flex items-center gap-2 hover:shadow-lg hover:shadow-blue-600/20">
                    
                    <svg wire:loading wire:target="save" class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                    
                    <span wire:loading.remove wire:target="save">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    </span>
                    
                    <span wire:loading.remove wire:target="save">Đăng tài liệu</span>
                    <span wire:loading wire:target="save">Đang xử lý...</span>
                </button>
            </div>
        </form>
    </div>
</div>