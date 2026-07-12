<div class="w-full py-4 max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('contributor.documents.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-gray-200 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Quay lại kho
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50/80 px-4 py-3 text-sm font-semibold text-green-700 shadow-sm mb-4" x-data x-init="window.scrollTo({top: 0, behavior: 'smooth'});">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-xl border border-rose-200 bg-rose-50/80 px-4 py-3 text-sm font-semibold text-rose-700 shadow-sm mb-4" x-data x-init="window.scrollTo({top: 0, behavior: 'smooth'});">
            {{ session('error') }}
        </div>
    @endif

    <!-- ===== FORM EDIT ===== -->
    <div class="w-full bg-white rounded-[20px] shadow-[0_20px_40px_rgba(0,0,0,0.06)] px-5 py-6 sm:px-9 sm:py-8 transition-all">
        <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-5">
            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-800">Chỉnh Sửa Tài Liệu</h1>
                <p class="text-[13px] text-gray-500 mt-0.5">Cập nhật thông tin chi tiết hoặc thay thế tệp tin tài liệu của bạn</p>
            </div>
        </div>

        <form wire:submit.prevent="save" novalidate>
            <!-- Basic Info Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-5">
                <!-- Left Column -->
                <div class="space-y-5">
                    <!-- Tiêu đề -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Tiêu đề tài liệu <span class="text-red-500">*</span></label>
                        <input type="text" wire:model.blur="title" placeholder="Nhập tiêu đề tài liệu..." required
                            class="w-full px-3.5 py-2.5 border-[1.5px] border-[#dce2ec] rounded-lg text-sm bg-[#fafcff] text-[#0b1e3a] focus:outline-none focus:border-[#2a7de1] focus:ring-4 focus:ring-[#2a7de1]/10 transition-all" />
                        @error('title') <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Danh mục + Môn học -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Danh mục <span class="text-red-500">*</span></label>
                            <select wire:model.live="category_id" required class="w-full px-3.5 py-2.5 border-[1.5px] border-[#dce2ec] rounded-lg text-sm bg-[#fafcff] text-[#0b1e3a] focus:outline-none focus:border-[#2a7de1] focus:ring-4 focus:ring-[#2a7de1]/10 transition-all">
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Môn học <span class="text-red-500">*</span></label>
                            <select wire:model.live="subject_id" wire:key="subject-select-{{ $category_id }}" required class="w-full px-3.5 py-2.5 border-[1.5px] border-[#dce2ec] rounded-lg text-sm bg-[#fafcff] text-[#0b1e3a] focus:outline-none focus:border-[#2a7de1] focus:ring-4 focus:ring-[#2a7de1]/10 transition-all">
                                <option value="">-- Chọn môn --</option>
                                @foreach($subjects as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                @endforeach
                            </select>
                            @error('subject_id') <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Mô tả ngắn -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Mô tả ngắn <span class="text-red-500">*</span></label>
                        <input type="text" wire:model.blur="short_description" placeholder="Tóm tắt nội dung chính (tối đa 200 ký tự)" required maxlength="200"
                            class="w-full px-3.5 py-2.5 border-[1.5px] border-[#dce2ec] rounded-lg text-sm bg-[#fafcff] text-[#0b1e3a] focus:outline-none focus:border-[#2a7de1] focus:ring-4 focus:ring-[#2a7de1]/10 transition-all" />
                        <div class="flex justify-between mt-1">
                            @error('short_description') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                            <span class="text-xs text-gray-400">{{ strlen($short_description ?? '') }}/200</span>
                        </div>
                    </div>

                    <!-- Mô tả chi tiết -->
                    <div>
                        <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Mô tả chi tiết <span class="text-red-500">*</span></label>
                        <textarea wire:model.blur="description" rows="5" placeholder="Mô tả cụ thể tài liệu gồm những phần nào, kiến thức gì..." required
                            class="w-full px-3.5 py-2.5 border-[1.5px] border-[#dce2ec] rounded-lg text-sm bg-[#fafcff] text-[#0b1e3a] focus:outline-none focus:border-[#2a7de1] focus:ring-4 focus:ring-[#2a7de1]/10 transition-all resize-none"></textarea>
                        @error('description') <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-5">
                    <!-- Thẻ (Tags) -->
                    <div x-data="{ showCustom: false }">
                        <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">
                            Thẻ (Tags) <span class="text-gray-400 font-normal text-[11px]">(chọn hoặc nhập)</span>
                        </label>
                        
                        <div wire:ignore x-init="
                            $nextTick(() => {
                                const select = $refs.tagSelect;
                                if (select.tomselect) select.tomselect.destroy();
                                const ts = new TomSelect(select, {
                                    maxItems: null,
                                    plugins: ['remove_button'],
                                    placeholder: 'Chọn tags...',
                                    items: {{ Js::from($selectedTags ?? []) }},
                                    onChange: (values) => { 
                                        @this.call('setTags', values);
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
                            <input type="checkbox" id="customTagsToggle" x-model="showCustom" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-3.5 w-3.5" />
                            <label for="customTagsToggle" class="text-[13px] font-medium text-gray-600 cursor-pointer">Thêm tag không có trong danh sách?</label>
                        </div>

                        <div x-show="showCustom" x-transition class="mt-2">
                            <input type="text" wire:model="customTagsInput" placeholder="VD: toán, đại số, bài tập (cách nhau bằng dấu phẩy)" 
                                class="w-full px-3.5 py-2.5 border-[1.5px] border-[#dce2ec] rounded-lg text-sm bg-[#fafcff] text-[#0b1e3a] focus:outline-none focus:border-[#2a7de1] focus:ring-4 focus:ring-[#2a7de1]/10 transition-all" />
                        </div>
                        @error('selectedTags') <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span> @enderror
                        @error('customTagsInput') <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tệp đính kèm -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tệp tài liệu đính kèm</label>
                        
                        <!-- Existing File Info -->
                        @if($doc)
                        <div class="mb-3 bg-[#f8faff] border-[1.5px] border-[#dce2ec] rounded-[14px] p-3 text-xs text-gray-700 flex items-center gap-3">
                            <svg class="w-7 h-7 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <div class="min-w-0 flex-1">
                                <span class="font-bold text-gray-800 block truncate text-sm" title="{{ $doc->title }}">{{ $doc->title }}</span>
                                <span class="block text-[11px] text-gray-500 font-medium uppercase mt-0.5">{{ strtoupper($doc->file_type) }} • Tệp đang lưu trữ</span>
                            </div>
                        </div>
                        @endif

                        <div class="relative border-2 border-dashed border-[#dce2ec] rounded-[14px] px-4 py-3 bg-[#f8faff] flex items-center gap-3 hover:border-[#2a7de1] hover:bg-[#f0f6ff] transition-all">
                            <input type="file" wire:model="newFile" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept=".pdf,.doc,.docx,.zip" />
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            
                            <div class="flex-1 min-w-0">
                                @if ($newFile)
                                    <span class="font-medium text-gray-700 block truncate text-sm">{{ $newFile->getClientOriginalName() }}</span>
                                    <small class="block text-gray-400 text-[11px]">{{ number_format($newFile->getSize() / 1024 / 1024, 2) }} MB</small>
                                @else
                                    <span class="font-medium text-gray-600 block text-sm">Tải tệp mới để thay thế</span>
                                    <small class="block text-gray-400 text-[11px]">PDF, DOCX, ZIP ... (tối đa {{ \App\Services\SettingService::get('max_document_size_mb', 50) }}MB)</small>
                                @endif
                            </div>
                            
                            @if ($newFile)
                                <button type="button" wire:click="removeSelectedFile" class="relative z-20 text-red-500 hover:bg-red-50 p-1.5 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            @endif
                        </div>

                        <div wire:loading wire:target="newFile" class="mt-2 text-sm text-blue-600 font-medium flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                            Đang tải tệp lên...
                        </div>
                        @error('newFile') <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Thiết lập bán -->
                    <div class="border-[1.5px] border-[#e5ebf3] rounded-[14px] p-4 bg-[#fafcff] space-y-3" x-data="{ paid: @entangle('isPaid') }">
                        <div class="text-[13px] font-semibold text-gray-700">Thiết lập bán</div>
                        <div class="flex gap-6">
                            <label class="flex items-center gap-2 font-medium text-[14px] text-[#1a2b4a] cursor-pointer">
                                <input type="radio" :checked="!paid" @click="paid = false" name="isPaidRadio" class="w-4 h-4 text-[#2a7de1] focus:ring-[#2a7de1] border-gray-300" />
                                Miễn phí
                            </label>
                            <label class="flex items-center gap-2 font-medium text-[14px] text-[#1a2b4a] cursor-pointer">
                                <input type="radio" :checked="paid" @click="paid = true" name="isPaidRadio" class="w-4 h-4 text-[#2a7de1] focus:ring-[#2a7de1] border-gray-300" />
                                Bán có phí
                            </label>
                        </div>
                        <div x-show="paid" x-transition class="grid grid-cols-2 gap-3 mt-3">
                            <div>
                                <label class="block text-[12px] font-medium text-gray-500 mb-1">Giá bán (VND) <span class="text-red-500">*</span></label>
                                <input type="number" wire:model.blur="price" placeholder="10000" x-on:input="$el.value = $el.value.replace(/^0+/, '') || '0'"
                                    class="w-full px-3 py-2 border-[1.5px] border-[#dce2ec] rounded-lg text-sm bg-white text-[#0b1e3a] focus:outline-none focus:border-[#2a7de1] focus:ring-4 focus:ring-[#2a7de1]/10 transition-all [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" />
                                @error('price') <span class="text-[11px] text-red-500 font-medium mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-[12px] font-medium text-gray-500 mb-1">Giá khuyến mãi (VND)</label>
                                <input type="number" wire:model.blur="sale_price" placeholder="Tùy chọn" x-on:input="$el.value = $el.value.replace(/^0+/, '') || ''"
                                    class="w-full px-3 py-2 border-[1.5px] border-[#dce2ec] rounded-lg text-sm bg-white text-[#0b1e3a] focus:outline-none focus:border-[#2a7de1] focus:ring-4 focus:ring-[#2a7de1]/10 transition-all [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" />
                                @error('sale_price') <span class="text-[11px] text-red-500 font-medium mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ảnh bìa & Ảnh mô tả -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                <!-- Ảnh bìa -->
                <div class="border-[1.5px] border-[#e5ebf3] rounded-[14px] p-3 bg-[#fafcff] flex flex-col gap-1.5">
                    <div class="text-sm font-semibold text-gray-700">Ảnh bìa <span class="text-gray-400 font-normal text-xs">(bỏ trống nếu không đổi)</span></div>
                    
                    <div class="w-full h-[140px] bg-[#eef3fa] rounded-[10px] flex items-center justify-center text-[#6b7f9e] text-[13px] overflow-hidden relative">
                        @if ($newThumbnailFile)
                            <img src="{{ $newThumbnailFile->temporaryUrl() }}" class="w-full h-full object-cover" />
                            <button type="button" wire:click="removeSelectedThumbnail" class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-md hover:bg-red-600 shadow z-20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        @elseif ($existingThumbnailPath)
                            @php
                                $thumbUrl = str_starts_with($existingThumbnailPath, 'http') ? $existingThumbnailPath : (str_starts_with($existingThumbnailPath, 'documents/') ? Storage::disk('public')->url($existingThumbnailPath) : rtrim(config('filesystems.disks.r2.url'), '/').'/'.ltrim($existingThumbnailPath, '/'));
                            @endphp
                            <img src="{{ $thumbUrl }}" class="w-full h-full object-cover" />
                        @elseif ($doc->thumbnail_url)
                            <img src="{{ $doc->thumbnail_url }}" class="w-full h-full object-cover" />
                        @else
                            <div class="flex flex-col items-center gap-1">
                                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a1 1 0 011.414 0L15 17m0 0l-3-3m3 3l3-3m0 0l-3-3m3 3V4"/></svg>
                                <span>Chưa có ảnh</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="relative border-2 border-dashed border-[#dce2ec] rounded-[14px] px-4 py-2 mt-1 bg-[#f8faff] flex items-center gap-2 cursor-pointer hover:border-[#2a7de1] hover:bg-[#f0f6ff] transition-all justify-center">
                        <input type="file" wire:model="newThumbnailFile" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*" />
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span class="text-sm text-gray-600 font-medium">Tải ảnh bìa mới <span class="font-normal text-xs text-gray-400">(tối đa {{ \App\Services\SettingService::get('max_thumbnail_size_mb', 5) }}MB)</span></span>
                    </div>
                    <div wire:loading wire:target="newThumbnailFile" class="text-xs text-blue-600 mt-1 text-center">Đang tải...</div>
                    @error('newThumbnailFile') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Ảnh mô tả -->
                <div class="border-[1.5px] border-[#e5ebf3] rounded-[14px] p-3 bg-[#fafcff] flex flex-col gap-1.5">
                    <div class="text-sm font-semibold text-gray-700">Ảnh mô tả <span class="text-gray-400 font-normal text-xs">(bỏ trống nếu không đổi)</span></div>
                    
                    <div class="w-full min-h-[140px] max-h-[280px] bg-[#eef3fa] rounded-[10px] flex flex-col text-[#6b7f9e] text-[13px] relative p-2 overflow-y-auto">
                        @if ($galleryFiles && count($galleryFiles) > 0)
                            <div class="grid grid-cols-3 gap-1.5 w-full">
                                @foreach($galleryFiles as $index => $galleryFile)
                                    @if ($galleryFile && !in_array($index, $this->excludedGalleryIndices))
                                        <div class="relative rounded-md overflow-hidden aspect-square border border-white">
                                            <img src="{{ $galleryFile->temporaryUrl() }}" class="w-full h-full object-cover" />
                                            <button type="button" wire:click="removeGalleryImage({{ $index }})" class="absolute top-0.5 right-0.5 bg-red-500/80 text-white p-0.5 rounded shadow z-20 hover:bg-red-600">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @elseif ($existingGalleryImages && is_array($existingGalleryImages) && count($existingGalleryImages) > 0)
                            <div class="grid grid-cols-3 gap-1.5 w-full">
                                @foreach($existingGalleryImages as $index => $galleryImage)
                                    <div class="relative rounded-md overflow-hidden aspect-square border border-white">
                                        @php
                                            $galPath = $galleryImage['path'] ?? $galleryImage;
                                            $galUrl = str_starts_with($galPath, 'http') ? $galPath : (str_starts_with($galPath, 'documents/') ? Storage::disk('public')->url($galPath) : Storage::disk('r2')->url($galPath));
                                        @endphp
                                        <img src="{{ $galUrl }}" class="w-full h-full object-cover" />
                                    </div>
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
                        <span class="text-sm text-gray-600 font-medium">Tải ảnh mô tả mới <span class="font-normal text-xs text-gray-400">(tối đa {{ \App\Services\SettingService::get('max_gallery_size_mb', 5) }}MB/ảnh)</span></span>
                    </div>
                    <div wire:loading wire:target="galleryFiles" class="text-xs text-blue-600 mt-1 text-center">Đang tải...</div>
                    @error('galleryFiles') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                    @error('galleryFiles.*') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Quyền riêng tư & Truy cập -->
            <div class="grid grid-cols-1 gap-5 mt-5">
                <div class="border-[1.5px] border-[#e5ebf3] rounded-[14px] p-4 bg-white">
                    <label class="block text-[13px] font-semibold text-gray-700 mb-2.5">Quyền riêng tư</label>
                    <div class="flex flex-wrap gap-x-6 gap-y-3">
                        <label class="flex items-center gap-2.5 font-medium text-[14px] text-[#1a2b4a] cursor-pointer">
                            <input type="radio" wire:model="visibility" value="public" class="w-4 h-4 text-[#2a7de1] focus:ring-[#2a7de1] border-gray-300" />
                            Công khai
                        </label>
                        <label class="flex items-center gap-2.5 font-medium text-[14px] text-[#1a2b4a] cursor-pointer">
                            <input type="radio" wire:model="visibility" value="private" class="w-4 h-4 text-[#2a7de1] focus:ring-[#2a7de1] border-gray-300" />
                            Riêng tư
                        </label>
                    </div>
                </div>


            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('contributor.documents.index') }}" wire:navigate class="px-5 py-2.5 text-[14px] font-medium text-gray-600 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                    Hủy bỏ
                </a>
                <button type="submit" 
                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-[14px] font-semibold rounded-xl transition-all duration-300 flex items-center gap-2 shadow-sm">
                    <svg wire:loading wire:target="save" class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                    <span wire:loading.remove wire:target="save">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    </span>
                    <span wire:loading.remove wire:target="save">Lưu thay đổi</span>
                    <span wire:loading wire:target="save">Đang lưu...</span>
                </button>
            </div>
        </form>
    </div>
</div>
