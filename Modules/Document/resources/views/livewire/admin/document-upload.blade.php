<div class="max-w-4xl mx-auto space-y-6 font-sans pb-10">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-850 tracking-tight">Đăng Tài Liệu (Admin)</h2>
            <p class="text-xs text-gray-400 mt-1">Đăng tải tài liệu trực tiếp - tự động phê duyệt.</p>
        </div>
        <a href="{{ route('admin.documents.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-2xl border border-gray-200 bg-white text-xs font-bold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Quay lại
        </a>
    </div>

    <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Form Columns -->
        <div class="md:col-span-2 space-y-6">
            <!-- Basic Information Card -->
            <div class="rounded-[2rem] border border-white bg-white/70 p-6 shadow-xl shadow-gray-100/50 backdrop-blur-md space-y-5">
                <h3 class="text-sm font-extrabold text-gray-800 border-b border-gray-100 pb-3 uppercase tracking-wider">Thông tin cơ bản</h3>
                
                <!-- Title -->
                <div class="space-y-1.5">
                    <label for="title" class="text-xs font-extrabold text-gray-700">Tiêu đề tài liệu <span class="text-rose-500">*</span></label>
                    <input type="text" id="title" wire:model.blur="title" placeholder="VD: Giáo trình cấu trúc dữ liệu và giải thuật" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-xs font-semibold text-gray-800 placeholder-gray-400 focus:border-indigo-400 focus:outline-none transition-colors" />
                    @error('title') <span class="text-xs text-rose-600 font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- Category -->
                <div class="space-y-1.5">
                    <label for="category_id" class="text-xs font-extrabold text-gray-700">Danh mục <span class="text-rose-500">*</span></label>
                    <select id="category_id" wire:model.live="category_id" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-xs font-semibold text-gray-700 focus:border-indigo-400 focus:outline-none bg-white transition-colors">
                        <option value="">-- Chọn danh mục tài liệu --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-xs text-rose-600 font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- Subject (Môn học) -->
                <div class="space-y-1.5">
                    <label for="subject_id" class="text-xs font-extrabold text-gray-700">Môn học <span class="text-rose-500">*</span></label>
                    <select id="subject_id" wire:model.live="subject_id" wire:key="subject-select-{{ $category_id }}" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-xs font-semibold text-gray-700 focus:border-indigo-400 focus:outline-none bg-white transition-colors">
                        <option value="">-- Chọn môn học --</option>
                        @foreach($subjects as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                        @endforeach
                    </select>
                    @error('subject_id') <span class="text-xs text-rose-600 font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- Short Description -->
                <div class="space-y-1.5">
                    <label for="short_description" class="text-xs font-extrabold text-gray-700">Mô tả ngắn</label>
                    <textarea id="short_description" wire:model.blur="short_description" rows="2" placeholder="Tóm tắt nội dung chính của tài liệu (không quá 500 ký tự)..." class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-xs font-semibold text-gray-800 placeholder-gray-400 focus:border-indigo-400 focus:outline-none transition-colors resize-none"></textarea>
                    @error('short_description') <span class="text-xs text-rose-600 font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- Detailed Description -->
                <div class="space-y-1.5">
                    <label for="description" class="text-xs font-extrabold text-gray-700">Mô tả chi tiết <span class="text-rose-500">*</span></label>
                    <textarea id="description" wire:model.blur="description" rows="6" placeholder="Mô tả cụ thể tài liệu gồm những phần nào, kiến thức gì, lợi ích khi học viên đọc tài liệu này..." class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-xs font-semibold text-gray-800 placeholder-gray-400 focus:border-indigo-400 focus:outline-none transition-colors"></textarea>
                    @error('description') <span class="text-xs text-rose-600 font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- Tags -->
                <div class="space-y-1.5" x-data="{ showCustom: false }">
                    <label class="text-xs font-extrabold text-gray-700">Thẻ (Tags)</label>

                    <!-- Tom-Select for predefined tags -->
                    <div wire:ignore x-init="
                        $nextTick(() => {
                            if ($refs.select.tomselect) $refs.select.tomselect.destroy();
                            const ts = new TomSelect($refs.select, {
                                maxItems: null,
                                plugins: ['remove_button'],
                                placeholder: 'Chọn tags...',
                                onChange: (values) => { 
                                    $wire.call('setTags', values);
                                },
                            });
                        });
                    ">
                        <select multiple x-ref="select" class="w-full text-xs font-semibold">
                            @foreach($allTags as $tag)
                                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Custom tags toggle -->
                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" id="customTagsToggle" x-model="showCustom" class="rounded border-gray-300">
                        <label for="customTagsToggle" class="text-xs font-bold text-gray-600 cursor-pointer">Tag không có trong danh sách? Nhập tại đây</label>
                    </div>

                    <!-- Custom tags input (shown when checkbox is checked) -->
                    <div x-show="showCustom" x-transition class="space-y-1">
                        <input type="text" wire:model="customTagsInput" placeholder="VD: tag tùy chỉnh 1, tag tùy chỉnh 2" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-xs font-semibold text-gray-800 placeholder-gray-400 focus:border-indigo-400 focus:outline-none transition-colors" />
                        <p class="text-[10px] text-gray-400 font-bold">Nhập các tag tùy chỉnh, cách nhau bằng dấu phẩy</p>
                    </div>

                    @error('selectedTags') <span class="text-xs text-rose-600 font-semibold">{{ $message }}</span> @enderror
                    @error('customTagsInput') <span class="text-xs text-rose-600 font-semibold">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Upload File Card -->
            <div class="rounded-[2rem] border border-white bg-white/70 p-6 shadow-xl shadow-gray-100/50 backdrop-blur-md space-y-5">
                <h3 class="text-sm font-extrabold text-gray-800 border-b border-gray-100 pb-3 uppercase tracking-wider">Tệp đính kèm</h3>

                <!-- File Dropzone -->
                <div class="space-y-3">
                    <label class="text-xs font-extrabold text-gray-700">Chọn tệp tài liệu <span class="text-rose-500">*</span></label>
                    <div class="relative group border-2 border-dashed border-gray-200 rounded-3xl p-6 text-center hover:border-indigo-400 transition-colors bg-gray-50/50">
                        <input type="file" id="originalFile" wire:model="originalFile" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".pdf,.doc,.docx,.zip" />
                        
                        <div class="space-y-2">
                            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-650 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            </div>
                            <div class="text-xs font-medium text-gray-500">
                                <span class="font-extrabold text-indigo-600 hover:text-indigo-500">Nhấn để chọn tệp</span> hoặc kéo thả vào đây
                            </div>
                            <p class="text-[10px] text-gray-400 font-bold">PDF, DOC, DOCX, ZIP tối đa 50MB</p>
                        </div>
                    </div>

                    <!-- Livewire upload progress -->
                    <div wire:loading wire:target="originalFile" class="w-full text-center">
                        <div class="inline-flex items-center gap-2 text-xs text-indigo-650 font-bold bg-indigo-50 px-3 py-1.5 rounded-full border border-indigo-100">
                            <svg class="animate-spin h-3.5 w-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                            Đang tải tệp lên máy chủ...
                        </div>
                    </div>

                    <!-- Selected file info -->
                    @if ($originalFile)
                        <div class="flex items-center justify-between bg-indigo-50/40 border border-indigo-100 rounded-2xl p-3 text-xs text-gray-700">
                            <div class="flex items-center gap-2 overflow-hidden">
                                <svg class="w-8 h-8 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <div class="min-w-0">
                                    <p class="font-bold text-gray-900 truncate" title="{{ $originalFile->getClientOriginalName() }}">{{ $originalFile->getClientOriginalName() }}</p>
                                    <p class="text-[9px] text-gray-450 font-bold uppercase mt-0.5">{{ number_format($originalFile->getSize() / 1024 / 1024, 2) }} MB</p>
                                </div>
                            </div>
                            <button type="button" wire:click="removeSelectedFile" class="text-rose-600 hover:text-rose-700 font-extrabold px-3 py-1.5 uppercase text-[9px] tracking-wider bg-rose-50 hover:bg-rose-100 rounded-xl transition-colors shrink-0">Hủy</button>
                        </div>
                    @endif

                    @error('originalFile') <span class="text-xs text-rose-600 font-semibold block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Sidebar Options Column -->
        <div class="space-y-6">
            <!-- Settings Card -->
            <div class="rounded-[2rem] border border-white bg-white/70 p-6 shadow-xl shadow-gray-100/50 backdrop-blur-md space-y-5">
                <h3 class="text-sm font-extrabold text-gray-800 border-b border-gray-100 pb-3 uppercase tracking-wider">Thiết lập bán</h3>

                <!-- Price Option -->
                <div class="space-y-3" x-data="{ paid: @entangle('isPaid') }">
                    <label class="text-xs font-extrabold text-gray-700 block">Hình thức xuất bản</label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" :checked="!paid" @click="paid = false" name="isPaidRadio" class="h-4 w-4 border-gray-200 text-indigo-650 focus:ring-indigo-500" />
                            <span class="text-xs text-gray-700 font-bold">Miễn phí</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" :checked="paid" @click="paid = true" name="isPaidRadio" class="h-4 w-4 border-gray-200 text-indigo-655 focus:ring-indigo-500" />
                            <span class="text-xs text-gray-700 font-bold">Bán có phí</span>
                        </label>
                    </div>

                    <!-- Price input field -->
                    <div x-show="paid" x-transition class="space-y-1.5 pt-1.5">
                        <label for="price" class="text-[10px] font-bold text-gray-500 uppercase">Giá bán (VND) <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <input type="number" id="price" wire:model.blur="price" placeholder="10000" 
                                x-on:input="$el.value = $el.value.replace(/^0+/, '') || '0'"
                                class="w-full rounded-2xl border border-gray-200 px-4 py-3 pr-16 text-xs font-semibold text-gray-800 placeholder-gray-400 focus:border-indigo-400 focus:outline-none transition-colors [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" />
                            <span class="absolute inset-y-0 right-4 inline-flex items-center text-xs font-bold text-gray-400 pointer-events-none">VND</span>
                        </div>
                        @error('price') <span class="text-xs text-rose-600 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <!-- Sale Price input field -->
                    <div x-show="paid" x-transition class="space-y-1.5 pt-1.5">
                        <label for="sale_price" class="text-[10px] font-bold text-gray-500 uppercase">Giá khuyến mãi (VND) <span class="text-gray-400 font-normal lowercase">(tùy chọn)</span></label>
                        <div class="relative">
                            <input type="number" id="sale_price" wire:model.blur="sale_price" placeholder="Giảm giá (nhỏ hơn giá gốc)" 
                                x-on:input="$el.value = $el.value.replace(/^0+/, '') || ''"
                                class="w-full rounded-2xl border border-gray-200 px-4 py-3 pr-16 text-xs font-semibold text-gray-800 placeholder-gray-400 focus:border-indigo-400 focus:outline-none transition-colors [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" />
                            <span class="absolute inset-y-0 right-4 inline-flex items-center text-xs font-bold text-gray-400 pointer-events-none">VND</span>
                        </div>
                        @error('sale_price') <span class="text-xs text-rose-600 font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Visibility -->
                <div class="space-y-1.5">
                    <label for="visibility" class="text-xs font-extrabold text-gray-700">Quyền riêng tư</label>
                    <select id="visibility" wire:model.live="visibility" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-xs font-semibold text-gray-700 focus:border-indigo-400 focus:outline-none bg-white transition-colors">
                        <option value="public">Công khai (Mọi học viên đều thấy)</option>
                        <option value="private">Riêng tư (Chỉ mình bạn xem)</option>
                    </select>
                </div>

                <!-- Downloadable -->
                <div class="flex items-center justify-between pt-2">
                    <div class="space-y-0.5">
                        <label class="text-xs font-extrabold text-gray-700 block">Tải về trực tiếp</label>
                        <span class="text-[10px] text-gray-450 font-bold block">Cho phép học viên lưu trữ file</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="is_downloadable" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-250 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-350 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-650"></div>
                    </label>
                </div>
            </div>

            <!-- Images Card (Thumbnail + Gallery) -->
            <div class="rounded-[2rem] border border-white bg-white/70 p-6 shadow-xl shadow-gray-100/50 backdrop-blur-md space-y-5">
                <h3 class="text-sm font-extrabold text-gray-800 border-b border-gray-100 pb-3 uppercase tracking-wider">Hình ảnh tài liệu</h3>
                
                <div class="space-y-3">
                    <label class="text-xs font-extrabold text-gray-700">Ảnh bìa <span class="text-rose-500">*</span></label>
                    <div class="relative group border-2 border-dashed border-gray-200 rounded-3xl p-4 text-center hover:border-indigo-400 transition-colors bg-gray-50/50">
                        <input type="file" id="thumbnailFile" wire:model="thumbnailFile" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" />
                        <div class="space-y-1.5">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a1 1 0 011.414 0L15 17m0 0l-3-3m3 3l3-3m0 0l-3-3m3 3V4"/></svg>
                            <div class="text-xs font-bold text-indigo-600 hover:text-indigo-500">Tải ảnh bìa lên</div>
                            <p class="text-[9px] text-gray-400 font-bold">JPG, PNG tối đa 2MB</p>
                        </div>
                    </div>

                    @if ($thumbnailFile)
                        <div class="relative rounded-2xl overflow-hidden border border-gray-250">
                            <img src="{{ $thumbnailFile->temporaryUrl() }}" class="w-full h-32 object-cover" alt="Cover preview" />
                            <button type="button" wire:click="removeSelectedThumbnail" class="absolute top-2 right-2 rounded-full bg-rose-600 text-white p-1 hover:bg-rose-700 shadow-md transition-colors" title="Xóa ảnh bìa">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    @endif

                    @error('thumbnailFile') <span class="text-xs text-rose-600 font-semibold block">{{ $message }}</span> @enderror
                </div>

                <div class="border-t border-gray-100 pt-4 space-y-3">
                    <label class="text-xs font-extrabold text-gray-700">Ảnh mô tả <span class="text-gray-400 font-medium">(có thể bỏ trống)</span></label>
                    <div class="relative group border-2 border-dashed border-gray-200 rounded-3xl p-4 text-center hover:border-indigo-400 transition-colors bg-gray-50/50">
                        <input type="file" id="galleryFiles" wire:model="galleryFiles" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" multiple />
                        <div class="space-y-1.5">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a1 1 0 011.414 0L15 17m0 0l-3-3m3 3l3-3m0 0l-3-3m3 3V4"/></svg>
                            <div class="text-xs font-bold text-indigo-600 hover:text-indigo-500">Thêm ảnh mô tả</div>
                            <p class="text-[9px] text-gray-400 font-bold">JPG, PNG - tối đa 10 ảnh, mỗi ảnh 5MB.</p>
                        </div>
                    </div>

                    <div wire:loading wire:target="galleryFiles" class="w-full text-center">
                        <div class="inline-flex items-center gap-2 text-xs text-indigo-650 font-bold bg-indigo-50 px-3 py-1.5 rounded-full border border-indigo-100">
                            <svg class="animate-spin h-3.5 w-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                            Đang tải ảnh lên...
                        </div>
                    </div>

                    @if ($galleryFiles && count($galleryFiles) > 0)
                        <div class="grid grid-cols-5 gap-2">
                            @foreach($galleryFiles as $index => $galleryFile)
                                @if ($galleryFile && !in_array($index, $this->excludedGalleryIndices))
                                    <div class="relative rounded-xl overflow-hidden border border-gray-250 aspect-square group">
                                        <img src="{{ $galleryFile->temporaryUrl() }}" class="w-full h-full object-cover" alt="Gallery {{ $index + 1 }}" />
                                        <button type="button" wire:click="removeGalleryImage({{ $index }})" class="absolute top-1 right-1 rounded-full bg-rose-600 text-white p-0.5 hover:bg-rose-700 shadow-md transition-all opacity-0 group-hover:opacity-100" title="Xóa ảnh này">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    @error('galleryFiles') <span class="text-xs text-rose-600 font-semibold block">{{ $message }}</span> @enderror
                    @error('galleryFiles.*') <span class="text-xs text-rose-600 font-semibold block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-bold py-4 text-xs shadow-lg shadow-indigo-600/15 hover:shadow-indigo-600/30 transition-all duration-200 flex items-center justify-center gap-2">
                <svg wire:loading class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                <span wire:loading.remove>Đăng tài liệu</span>
                <span wire:loading>Đang đăng...</span>
            </button>
        </div>
    </form>
</div>

