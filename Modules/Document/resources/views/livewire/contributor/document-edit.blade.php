<div class="max-w-4xl mx-auto space-y-6 font-sans pb-10">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-850 tracking-tight">Chỉnh Sửa Tài Liệu</h2>
            <p class="text-xs text-slate-400 mt-1">Cập nhật thông tin chi tiết hoặc thay thế tệp tin tài liệu của bạn.</p>
        </div>
        <a href="{{ route('contributor.documents.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-2xl border border-slate-200 bg-white text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Quay lại kho
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-green-200 bg-green-50/80 px-4 py-3 text-sm font-semibold text-green-700 shadow-sm" x-data x-init="window.scrollTo({top: 0, behavior: 'smooth'});">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-rose-200 bg-rose-50/80 px-4 py-3 text-sm font-semibold text-rose-700 shadow-sm" x-data x-init="window.scrollTo({top: 0, behavior: 'smooth'});">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Form Columns -->
        <div class="md:col-span-2 space-y-6">
            <!-- Basic Information Card -->
            <div class="rounded-[2rem] border border-white bg-white/70 p-6 shadow-xl shadow-slate-100/50 backdrop-blur-md space-y-5">
                <h3 class="text-sm font-extrabold text-slate-800 border-b border-slate-100 pb-3 uppercase tracking-wider">Thông tin cơ bản</h3>
                
                <!-- Title -->
                <div class="space-y-1.5">
                    <label for="title" class="text-xs font-extrabold text-slate-700">Tiêu đề tài liệu <span class="text-rose-500">*</span></label>
                    <input type="text" id="title" wire:model.blur="title" placeholder="VD: Giáo trình cấu trúc dữ liệu và giải thuật" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none transition-colors" />
                    @error('title') <span class="text-xs text-rose-600 font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- Category -->
                <div class="space-y-1.5">
                    <label for="category_id" class="text-xs font-extrabold text-slate-700">Danh mục <span class="text-rose-500">*</span></label>
                    <select id="category_id" wire:model.change="category_id" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-xs font-semibold text-slate-700 focus:border-indigo-400 focus:outline-none bg-white transition-colors">
                        <option value="">-- Chọn danh mục tài liệu --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-xs text-rose-600 font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- Short Description -->
                <div class="space-y-1.5">
                    <label for="short_description" class="text-xs font-extrabold text-slate-700">Mô tả ngắn</label>
                    <textarea id="short_description" wire:model.blur="short_description" rows="2" placeholder="Tóm tắt nội dung chính của tài liệu (không quá 500 ký tự)..." class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none transition-colors resize-none"></textarea>
                    @error('short_description') <span class="text-xs text-rose-600 font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- Detailed Description -->
                <div class="space-y-1.5">
                    <label for="description" class="text-xs font-extrabold text-slate-700">Mô tả chi tiết <span class="text-rose-500">*</span></label>
                    <textarea id="description" wire:model.blur="description" rows="6" placeholder="Mô tả cụ thể tài liệu gồm những phần nào, kiến thức gì..." class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none transition-colors"></textarea>
                    @error('description') <span class="text-xs text-rose-600 font-semibold">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Upload File Card -->
            <div class="rounded-[2rem] border border-white bg-white/70 p-6 shadow-xl shadow-slate-100/50 backdrop-blur-md space-y-5">
                <h3 class="text-sm font-extrabold text-slate-800 border-b border-slate-100 pb-3 uppercase tracking-wider">Tệp tài liệu đính kèm</h3>

                <!-- Existing File Info -->
                <div class="space-y-1.5">
                    <span class="text-xs font-extrabold text-slate-500 uppercase">Tệp hiện tại</span>
                    <div class="flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-2xl p-4 text-xs text-slate-700">
                        <svg class="w-8 h-8 text-slate-450 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <div class="min-w-0 flex-1">
                            <span class="font-bold text-slate-900 block truncate text-sm" title="{{ $doc->title }}">{{ $doc->title }}</span>
                            <span class="block text-[10px] text-slate-400 font-bold uppercase mt-0.5">{{ strtoupper($doc->file_type) }} • Lưu trữ trên cloud</span>
                        </div>
                    </div>
                </div>

                <!-- Replace File Dropzone -->
                <div class="space-y-3">
                    <label class="text-xs font-extrabold text-slate-350">Thay thế bằng tệp mới (tùy chọn)</label>
                    <div class="relative group border-2 border-dashed border-slate-200 rounded-3xl p-6 text-center hover:border-indigo-400 transition-colors bg-slate-50/50">
                        <input type="file" id="newFile" wire:model="newFile" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".pdf,.docx,.zip" />
                        
                        <div class="space-y-2">
                            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            </div>
                            <div class="text-xs font-medium text-slate-500">
                                <span class="font-extrabold text-indigo-400 hover:text-indigo-300">Nhấn để chọn tệp mới</span> hoặc kéo thả vào đây
                            </div>
                            <p class="text-[10px] text-slate-450 font-bold">PDF, DOCX, ZIP tối đa 50MB</p>
                        </div>
                    </div>

                    <!-- Livewire upload progress -->
                    <div wire:loading wire:target="newFile" class="w-full text-center">
                        <div class="inline-flex items-center gap-2 text-xs text-indigo-650 font-bold bg-indigo-50 px-3 py-1.5 rounded-full border border-indigo-100">
                            <svg class="animate-spin h-3.5 w-3.5 text-indigo-650" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                            Đang tải lên máy chủ...
                        </div>
                    </div>

                    <!-- Selected new file info -->
                    @if ($newFile)
                        <div class="flex items-center justify-between bg-indigo-50/40 border border-indigo-100 rounded-2xl p-3 text-xs text-slate-700">
                            <div class="flex items-center gap-2 overflow-hidden">
                                <svg class="w-8 h-8 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 truncate" title="{{ $newFile->getClientOriginalName() }}">{{ $newFile->getClientOriginalName() }}</p>
                                    <p class="text-[9px] text-slate-450 font-bold uppercase mt-0.5">{{ number_format($newFile->getSize() / 1024 / 1024, 2) }} MB</p>
                                </div>
                            </div>
                            <button type="button" wire:click="$set('newFile', null)" class="text-rose-600 hover:text-rose-700 font-extrabold px-3 py-1.5 uppercase text-[9px] tracking-wider bg-rose-50 hover:bg-rose-100 rounded-xl transition-colors shrink-0">Hủy</button>
                        </div>
                    @endif

                    @error('newFile') <span class="text-xs text-rose-600 font-semibold block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Sidebar Options Column -->
        <div class="space-y-6">
            <!-- Settings Card -->
            <div class="rounded-[2rem] border border-white bg-white/70 p-6 shadow-xl shadow-slate-100/50 backdrop-blur-md space-y-5">
                <h3 class="text-sm font-extrabold text-slate-800 border-b border-slate-100 pb-3 uppercase tracking-wider">Thiết lập bán</h3>

                <!-- Price Option -->
                <div class="space-y-3" x-data="{ paid: @entangle('isPaid') }">
                    <label class="text-xs font-extrabold text-slate-350 block">Hình thức xuất bản</label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" :checked="!paid" @click="paid = false" name="isPaidRadio" class="h-4 w-4 border-slate-200 text-indigo-650 focus:ring-indigo-500" />
                            <span class="text-xs text-slate-700 font-bold">Miễn phí</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" :checked="paid" @click="paid = true" name="isPaidRadio" class="h-4 w-4 border-slate-200 text-indigo-655 focus:ring-indigo-500" />
                            <span class="text-xs text-slate-700 font-bold">Bán có phí</span>
                        </label>
                    </div>

                    <!-- Price input field -->
                    <div x-show="paid" x-transition class="space-y-1.5 pt-1.5">
                        <label for="price" class="text-[10px] font-bold text-slate-500 uppercase">Giá bán (VND) <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <input type="number" id="price" wire:model.blur="price" placeholder="10000" 
                                x-on:input="$el.value = $el.value.replace(/^0+/, '') || '0'"
                                class="w-full rounded-2xl border border-slate-200 px-4 py-3 pr-16 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:border-indigo-400 focus:outline-none transition-colors [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" />
                            <span class="absolute inset-y-0 right-4 inline-flex items-center text-xs font-bold text-slate-400 pointer-events-none">VND</span>
                        </div>
                        @error('price') <span class="text-xs text-rose-600 font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Visibility -->
                <div class="space-y-1.5">
                    <label for="visibility" class="text-xs font-extrabold text-slate-700">Quyền riêng tư</label>
                    <select id="visibility" wire:model.change="visibility" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-xs font-semibold text-slate-700 focus:border-indigo-400 focus:outline-none bg-white transition-colors">
                        <option value="public">Công khai (Mọi học viên đều thấy)</option>
                        <option value="private">Riêng tư (Chỉ mình bạn xem)</option>
                    </select>
                </div>

                <!-- Downloadable -->
                <div class="flex items-center justify-between pt-2">
                    <div class="space-y-0.5">
                        <label class="text-xs font-extrabold text-slate-700 block">Tải về trực tiếp</label>
                        <span class="text-[10px] text-slate-450 font-bold block">Cho phép học viên download file</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="is_downloadable" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-250 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-350 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-650"></div>
                    </label>
                </div>
            </div>

            <!-- Cover Image Card -->
            <div class="rounded-[2rem] border border-white bg-white/70 p-6 shadow-xl shadow-slate-100/50 backdrop-blur-md space-y-5">
                <h3 class="text-sm font-extrabold text-slate-800 border-b border-slate-100 pb-3 uppercase tracking-wider">Ảnh bìa tài liệu</h3>
                
                <div class="space-y-3">
                    <div class="relative group border-2 border-dashed border-slate-200 rounded-3xl p-4 text-center hover:border-indigo-400 transition-colors bg-slate-50/50">
                        <input type="file" id="newThumbnailFile" wire:model="newThumbnailFile" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" />
                        <div class="space-y-1.5">
                            <svg class="mx-auto h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a1 1 0 011.414 0L15 17m0 0l-3-3m3 3l3-3m0 0l-3-3m3 3V4"/></svg>
                            <div class="text-xs font-bold text-indigo-600 hover:text-indigo-500">Thay đổi ảnh bìa</div>
                            <p class="text-[9px] text-slate-400 font-bold">JPG, PNG tối đa 2MB</p>
                        </div>
                    </div>

                    <!-- Livewire preview of new thumbnail -->
                    @if ($newThumbnailFile)
                        <div class="relative rounded-2xl overflow-hidden border border-slate-255">
                            <img src="{{ $newThumbnailFile->temporaryUrl() }}" class="w-full h-32 object-cover" alt="New Cover preview" />
                            <button type="button" wire:click="$set('newThumbnailFile', null)" class="absolute top-2 right-2 rounded-full bg-rose-600 text-white p-1 hover:bg-rose-700 shadow-md transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    <!-- Existing thumbnail -->
                    @elseif ($existingThumbnailPath)
                        <div class="relative rounded-2xl overflow-hidden border border-slate-200">
                            <img src="{{ $doc->thumbnail_url }}" class="w-full h-32 object-cover" alt="Current Cover" />
                        </div>
                    @endif

                    @error('newThumbnailFile') <span class="text-xs text-rose-600 font-semibold block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-bold py-4 text-xs shadow-lg shadow-indigo-600/15 hover:shadow-indigo-600/30 transition-all duration-200 flex items-center justify-center gap-2">
                <svg wire:loading class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                <span wire:loading.remove>Lưu thay đổi</span>
                <span wire:loading>Đang lưu dữ liệu...</span>
            </button>
        </div>
    </form>
</div>
