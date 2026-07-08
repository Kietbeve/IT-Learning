<div x-data="{ showRejectModal: @entangle('showRejectModal'), showImageModal: false }" 
     x-effect="document.body.style.overflow = (showRejectModal || showImageModal) ? 'hidden' : ''"
     @open-modal.window="let d = $event.detail; if (d === 'reject-detail-modal' || d?.[0] === 'reject-detail-modal' || d?.id === 'reject-detail-modal') showRejectModal = true"
     @close-modal.window="let d = $event.detail; if (d === 'reject-detail-modal' || d?.[0] === 'reject-detail-modal' || d?.id === 'reject-detail-modal') showRejectModal = false"
     class="space-y-6">

    <div class="mb-4">
        @if($from === 'list')
            <a href="{{ route('admin.documents.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Quay lại quản lý tài liệu
            </a>
        @else
            <a href="{{ route('admin.documents.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Quay lại hàng đợi
            </a>
        @endif
    </div>

    <!-- Status & Action Buttons Bar (Top) -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Status Badge -->
            <div>
                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider block mb-2">Trạng thái</span>
                @if($pendingVersion)
                    <span class="inline-flex rounded-full bg-amber-100 px-4 py-1.5 text-xs font-bold text-amber-800">
                        Chờ duyệt
                    </span>
                @elseif($doc->status === 'pending')
                    <span class="inline-flex rounded-full bg-amber-100 px-4 py-1.5 text-xs font-bold text-amber-800">
                        Chờ kiểm duyệt
                    </span>
                @elseif($doc->status === 'approved')
                    <span class="inline-flex rounded-full bg-emerald-100 px-4 py-1.5 text-xs font-bold text-emerald-800">
                        Đã phê duyệt
                    </span>
                @elseif($doc->status === 'rejected')
                    <span class="inline-flex rounded-full bg-rose-100 px-4 py-1.5 text-xs font-bold text-rose-800">
                        Đã từ chối
                    </span>
                @else
                    <span class="inline-flex rounded-full bg-gray-100 px-4 py-1.5 text-xs font-bold text-gray-800">
                        {{ ucfirst($doc->status) }}
                    </span>
                @endif
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-2">
                @if($editMode)
                    <!-- Edit Mode Buttons -->
                    <button wire:click="saveChanges" class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white px-5 py-2 text-sm font-bold shadow-md transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Lưu thay đổi
                    </button>
                    <button wire:click="cancelEdit" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-700 px-4 py-2 text-sm font-semibold transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Hủy
                    </button>
                @else
                    <!-- Normal Mode Buttons -->
                    <button wire:click="showHistory" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-700 px-4 py-2 text-sm font-semibold transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Xem nhật ký
                    </button>
                    <button wire:click="editDocument" class="inline-flex items-center gap-1.5 rounded-xl border border-blue-200 bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-2 text-sm font-semibold transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Sửa
                    </button>
                    @if($doc->status === 'pending' || $pendingVersion)
                        <button wire:click="approve" class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white px-5 py-2 text-sm font-bold shadow-md transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Phê duyệt
                        </button>
                        <button wire:click="openRejectionModal" class="inline-flex items-center gap-1.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white px-5 py-2 text-sm font-bold shadow-md transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Từ chối
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Version Changes Comparison -->
    @if(isset($changes) && count($changes) > 0)
        <div class="rounded-2xl border border-amber-200 bg-amber-50/50 p-5 shadow-sm space-y-3">
            <h3 class="text-sm font-bold text-amber-900 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Bản cập nhật này có sự thay đổi so với phiên bản đã duyệt trước đó:
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-gray-700">
                    <thead class="text-[10px] text-gray-500 uppercase bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">Trường thay đổi</th>
                            <th class="px-4 py-2">Giá trị trước đó (Đã duyệt)</th>
                            <th class="px-4 py-2">Giá trị mới (Chờ duyệt)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($changes as $field => $change)
                            <tr>
                                <td class="px-4 py-2.5 font-bold uppercase text-gray-500">{{ $field }}</td>
                                <td class="px-4 py-2.5 text-gray-600 line-through bg-rose-50/50 break-words max-w-xs">{{ $change['old'] }}</td>
                                <td class="px-4 py-2.5 text-gray-900 font-semibold bg-emerald-50/50 break-words max-w-xs">{{ $change['new'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Layout 2 Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column (70%) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Details Card -->
            <div class="rounded-3xl border border-gray-200 bg-white p-6 md:p-8 shadow-sm space-y-6">
                @if($editMode)
                    <!-- Edit Mode -->
                    <div class="space-y-4">
                        <!-- Category -->
                        <div>
                            <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider block mb-2">Danh mục</label>
                            <select wire:model.live="editCategoryId" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:outline-none">
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('editCategoryId') <span class="text-xs text-rose-600 font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <!-- Subject -->
                        <div>
                            <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider block mb-2">Môn học</label>
                            <select wire:model.live="editSubjectId" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:outline-none">
                                <option value="">-- Chọn môn học --</option>
                                @foreach($subjectsForEdit as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                @endforeach
                            </select>
                            @error('editSubjectId') <span class="text-xs text-rose-600 font-semibold">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Title -->
                        <div>
                            <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider block mb-2">Tiêu đề</label>
                            <input type="text" wire:model.blur="editTitle" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-lg font-bold text-gray-900 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:outline-none">
                            @error('editTitle') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Short Description -->
                        <div>
                            <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider block mb-2">Mô tả ngắn</label>
                            <textarea wire:model.blur="editShortDescription" rows="3" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:outline-none"></textarea>
                        </div>
                        
                        <!-- Description -->
                        <div>
                            <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider block mb-2">Nội dung chi tiết</label>
                            <textarea wire:model.blur="editDescription" rows="8" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:outline-none"></textarea>
                            @error('editDescription') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Price -->
                        <div class="space-y-3" x-data="{ paid: @entangle('editIsPaid') }">
                            <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider block">Hình thức</label>
                            <div class="flex items-center gap-4 mb-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" :checked="!paid" @click="paid = false" name="editIsPaidRadio" class="h-4 w-4 border-gray-200 text-blue-600 focus:ring-blue-500" />
                                    <span class="text-xs text-gray-700 font-bold">Miễn phí</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" :checked="paid" @click="paid = true" name="editIsPaidRadio" class="h-4 w-4 border-gray-200 text-blue-600 focus:ring-blue-500" />
                                    <span class="text-xs text-gray-700 font-bold">Có phí</span>
                                </label>
                            </div>

                            <div x-show="paid" x-transition class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider block mb-2">Giá (VND)</label>
                                    <input type="number" wire:model.blur="editPrice" min="0" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:outline-none" placeholder="10000">
                                    @error('editPrice') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider block mb-2">Giá KM (VND) - tùy chọn</label>
                                    <input type="number" wire:model.blur="editSalePrice" min="0" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:outline-none" placeholder="Khuyến mãi">
                                    @error('editSalePrice') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Visibility -->
                        <div>
                            <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider block mb-2">Chế độ hiển thị</label>
                            <select wire:model.live="editVisibility" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:outline-none">
                                <option value="public">Công khai (Public)</option>
                                <option value="private">Riêng tư (Private)</option>
                                <option value="unlisted">Không liệt kê (Unlisted)</option>
                            </select>
                        </div>
                        
                        <!-- Is Downloadable -->
                        <div>
                            <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider block mb-2">Quyền tải xuống</label>
                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" wire:model.live="editIsDownloadable" value="1" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">Cho phép tải xuống</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" wire:model.live="editIsDownloadable" value="0" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">Không cho phép</span>
                                </label>
                            </div>
                        </div>

                        <!-- Tags Selection -->
                        <div class="col-span-2 space-y-3" x-data="{ showCustom: @entangle('editCustomTagsInput').defer !== '' }">
                            <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
                            <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
                            
                            <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider block">Tags (Thẻ)</label>
                            
                            <!-- Tom-Select for predefined tags -->
                            <div wire:ignore x-init="
                                $nextTick(() => {
                                    if ($refs.select.tomselect) $refs.select.tomselect.destroy();
                                    const ts = new TomSelect($refs.select, {
                                        maxItems: null,
                                        plugins: ['remove_button'],
                                        placeholder: 'Chọn tags...',
                                        items: {{ Js::from($editSelectedTags ?? []) }},
                                        onChange: (values) => { 
                                            $wire.call('setEditTags', values);
                                        },
                                    });
                                    window.addEventListener('tags-updated', (e) => {
                                        if (ts) {
                                            ts.clear(true);
                                            const tags = Array.isArray(e.detail) ? e.detail : (e.detail.tags || []);
                                            ts.setValue(tags, true);
                                        }
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

                            <!-- Custom tags input -->
                            <div x-show="showCustom" x-transition class="space-y-1">
                                <input type="text" wire:model="editCustomTagsInput" placeholder="VD: tag tùy chỉnh 1, tag tùy chỉnh 2" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-xs font-semibold text-gray-800 placeholder-gray-400 focus:border-indigo-400 focus:outline-none transition-colors" />
                                <p class="text-[10px] text-gray-400 font-bold">Nhập các tag tùy chỉnh, cách nhau bằng dấu phẩy</p>
                            </div>

                            @error('editSelectedTags') <span class="text-xs text-rose-600 font-semibold">{{ $message }}</span> @enderror
                            @error('editCustomTagsInput') <span class="text-xs text-rose-600 font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <!-- File Upload -->
                        <div class="col-span-2 space-y-1">
                            <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider block">Thay đổi file tài liệu (Bỏ trống nếu giữ nguyên)</label>
                            @if($doc->file_original_path)
                                <div class="text-xs bg-gray-50 border border-gray-100 rounded-xl p-2.5 flex items-center justify-between text-gray-600 mb-2 gap-2">
                                    <span class="truncate font-mono">Tệp hiện tại: {{ basename($doc->file_original_path) }} ({{ strtoupper($doc->file_type) }})</span>
                                    <a href="{{ $doc->file_original_url }}" target="_blank" class="text-blue-600 hover:underline font-semibold whitespace-nowrap">Xem tệp cũ</a>
                                </div>
                            @endif

                            @if($editFile)
                                <div class="text-xs bg-emerald-50 border border-emerald-200 rounded-xl p-2.5 flex items-center justify-between text-emerald-800 mb-2 gap-2">
                                    <span class="truncate font-medium flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        Tệp mới đã chọn: {{ $editFile->getClientOriginalName() }}
                                    </span>
                                    <button type="button" wire:click="removeSelectedFile" class="text-rose-600 hover:text-rose-800 font-bold whitespace-nowrap">Hủy chọn</button>
                                </div>
                            @endif

                            <input type="file" wire:model="editFile" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm text-gray-900 focus:border-blue-400 focus:outline-none">
                            <div wire:loading wire:target="editFile" class="text-xs text-blue-600 mt-1 font-semibold">⏳ Đang tải file lên... vui lòng đợi</div>
                            @error('editFile') <span class="text-xs text-red-500 mt-1 block font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <!-- Images (Thumbnail + Gallery) -->
                        <div class="col-span-2 space-y-4">
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider block">Ảnh bìa (Bỏ trống nếu giữ nguyên)</label>
                                @if($activeVersion?->thumbnail)
                                    <div class="flex items-center gap-3 bg-gray-50 border border-gray-100 rounded-xl p-2.5 mb-2">
                                        <img src="{{ $activeVersion?->thumbnail_url }}" class="h-10 w-16 object-cover rounded-lg border border-gray-200" alt="Current thumb">
                                        <span class="text-xs text-gray-500 truncate">Ảnh bìa hiện tại</span>
                                    </div>
                                @endif

                                @if($editThumbnail)
                                    <div class="flex items-center justify-between bg-emerald-50 border border-emerald-200 rounded-xl p-2.5 mb-2 gap-2">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <img src="{{ $editThumbnail->temporaryUrl() }}" class="h-10 w-16 object-cover rounded-lg border border-emerald-250 shrink-0" alt="New thumb preview">
                                            <span class="text-xs text-emerald-800 font-medium truncate">Ảnh bìa mới đã chọn</span>
                                        </div>
                                        <button type="button" wire:click="removeSelectedThumbnail" class="text-rose-600 hover:text-rose-800 font-bold whitespace-nowrap text-xs">Hủy chọn</button>
                                    </div>
                                @endif

                                <input type="file" wire:model="editThumbnail" accept="image/*" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm text-gray-900 focus:border-blue-400 focus:outline-none">
                                <div wire:loading wire:target="editThumbnail" class="text-xs text-blue-600 mt-1 font-semibold">⏳ Đang tải ảnh lên... vui lòng đợi</div>
                                @error('editThumbnail') <span class="text-xs text-red-500 mt-1 block font-semibold">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-700 uppercase tracking-wider block">Ảnh mô tả <span class="text-gray-400 font-medium">(có thể bỏ trống)</span></label>
                                @if(!$editGalleryFiles && $activeVersion && $activeVersion->gallery_images && is_array($activeVersion->gallery_images))
                                    <div class="grid grid-cols-5 gap-2 mb-2">
                                        @foreach($activeVersion->gallery_images as $img)
                                            <div class="rounded-lg overflow-hidden border border-gray-200 aspect-square">
                                                <img src="{{ Storage::disk('r2')->url($img['path']) }}" class="w-full h-full object-cover" loading="lazy" />
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <input type="file" wire:model="editGalleryFiles" accept="image/*" multiple class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm text-gray-900 focus:border-blue-400 focus:outline-none">
                                <div wire:loading wire:target="editGalleryFiles" class="text-xs text-blue-600 mt-1 font-semibold">⏳ Đang tải ảnh lên... vui lòng đợi</div>
                                @if($editGalleryFiles && count($editGalleryFiles) > 0)
                                    <div class="grid grid-cols-5 gap-2 mt-2">
                                        @foreach($editGalleryFiles as $index => $galleryFile)
                                            @if ($galleryFile && !in_array($index, $this->excludedEditGalleryIndices))
                                                <div class="relative rounded-lg overflow-hidden border border-gray-200 aspect-square group">
                                                    <img src="{{ $galleryFile->temporaryUrl() }}" class="w-full h-full object-cover" alt="Gallery {{ $index + 1 }}" />
                                                    <button type="button" wire:click="removeEditGalleryImage({{ $index }})" class="absolute top-1 right-1 rounded-full bg-rose-600 text-white p-0.5 hover:bg-rose-700 shadow-md transition-all opacity-0 group-hover:opacity-100" title="Xóa ảnh này">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                                @error('editGalleryFiles') <span class="text-xs text-red-500 mt-1 block font-semibold">{{ $message }}</span> @enderror
                                @error('editGalleryFiles.*') <span class="text-xs text-red-500 mt-1 block font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                @else
                    <!-- View Mode -->
                    <div>
                        <span class="rounded-xl px-2.5 py-1 text-xs font-semibold bg-gray-100 text-gray-800">
                            {{ $activeVersion->category?->name ?? 'Tài liệu' }}
                        </span>
                        @if($activeVersion->subject)
                            <span class="rounded-xl px-2.5 py-1 text-xs font-semibold bg-blue-50 text-blue-800 ml-2">
                                Môn: {{ $activeVersion->subject->name }}
                            </span>
                        @endif
                        <h1 class="mt-4 text-2xl font-bold tracking-tight text-gray-900">
                            {{ $activeVersion->title }}
                        </h1>
                    </div>

                    <div class="border-t border-gray-100 pt-6 space-y-3">
                        <h2 class="text-base font-bold text-gray-900">Mô tả ngắn</h2>
                        <p class="text-sm text-gray-600 leading-relaxed break-words">{{ $activeVersion->short_description }}</p>
                    </div>

                    <div class="border-t border-gray-100 pt-6 space-y-3">
                        <h2 class="text-base font-bold text-gray-900">Nội dung chi tiết</h2>
                        <div class="text-sm text-gray-600 leading-relaxed whitespace-pre-line break-words">{{ $activeVersion->description }}</div>
                    </div>

                    @if($doc->tags && $doc->tags->isNotEmpty())
                        <div class="border-t border-gray-100 pt-6 space-y-3">
                            <h2 class="text-base font-bold text-gray-900">Tags / Thẻ</h2>
                            <div class="flex flex-wrap gap-2">
                                @foreach($doc->tags as $tag)
                                    <span class="inline-flex items-center rounded-lg bg-gray-50 border border-gray-200 px-2.5 py-1 text-xs font-semibold text-gray-600">
                                        #{{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Original File Info & Preview -->
            <div class="rounded-3xl border border-gray-200 bg-white p-6 md:p-8 shadow-sm space-y-6">
            <!-- File Variants -->
                <div class="space-y-4">
                    <!-- Original File -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-sm">
                        <div class="min-w-0 flex-1">
                            <span class="font-bold text-gray-900 block">Tệp gốc (Original File):</span>
                            @if($activeVersion->file_type && $originalFileSize)
                                <span class="text-xs text-gray-600 font-semibold block mt-1">{{ strtoupper($activeVersion->file_type) }} • {{ $originalFileSize }}</span>
                            @endif
                            <span class="text-xs text-gray-500 font-mono break-all block mt-0.5">{{ $activeVersion->file_original_path ?? 'Không có tệp gốc' }}</span>
                        </div>
                        @if($activeVersion->file_original_path)
                            <a href="{{ $activeVersion->file_original_view_url }}" target="_blank" class="inline-flex rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 px-3.5 py-2 text-xs font-semibold shadow-sm transition-all whitespace-nowrap self-start sm:self-center">
                                Xem tệp
                            </a>
                        @endif
                    </div>

                    @if(in_array(strtolower($activeVersion->file_type), ['pdf', 'doc', 'docx']))
                        <!-- Preview File -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-sm pt-4 border-t border-gray-50">
                            <div class="min-w-0 flex-1">
                                <span class="font-bold text-gray-900 block">Tệp xem trước (Preview File):</span>
                                @if($previewFileSize)
                                    <span class="text-xs text-gray-600 font-semibold block mt-1">PDF • {{ $previewFileSize }}</span>
                                @endif
                                <span class="text-xs text-gray-500 font-mono break-all block mt-0.5">{{ $activeVersion->preview_file_path ?? 'Không có tệp xem trước' }}</span>
                            </div>
                            @if($activeVersion->preview_file_path)
                                <a href="{{ $activeVersion->preview_file_view_url }}" target="_blank" class="inline-flex rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 px-3.5 py-2 text-xs font-semibold shadow-sm transition-all whitespace-nowrap self-start sm:self-center">
                                    Xem tệp
                                </a>
                            @endif
                        </div>

                        <!-- Watermarked File -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-sm pt-4 border-t border-gray-50">
                            <div class="min-w-0 flex-1">
                                <span class="font-bold text-gray-900 block">Tệp đóng dấu (Watermarked File):</span>
                                @if($watermarkedFileSize)
                                    <span class="text-xs text-gray-600 font-semibold block mt-1">{{ strtoupper(pathinfo($activeVersion->file_watermarked_path, PATHINFO_EXTENSION)) }} • {{ $watermarkedFileSize }}</span>
                                @endif
                                <span class="text-xs text-gray-500 font-mono break-all block mt-0.5">{{ $activeVersion->file_watermarked_path ?? 'Chưa tạo tệp đóng dấu' }}</span>
                            </div>
                            @if($activeVersion->file_watermarked_path)
                                <a href="{{ $activeVersion->file_watermarked_view_url }}" target="_blank" class="inline-flex rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 px-3.5 py-2 text-xs font-semibold shadow-sm transition-all whitespace-nowrap self-start sm:self-center">
                                    Xem tệp
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Preview Section (Modal Trigger & Fullscreen Viewer) -->
            @if(strtolower($activeVersion->file_type) === 'zip')
            <div x-data="{ 
                     isFullscreen: false,
                     selectedFile: null, 
                     copied: false, 
                     showTree: true,
                     selectFile(path) {
                         this.selectedFile = path;
                         this.$nextTick(() => {
                             let container = document.getElementById('admin-code-preview-container');
                             if (container) {
                                 let el = container.querySelector('[data-path=\'' + path + '\'] code');
                                 if (el && !el.classList.contains('prism-highlighted')) {
                                     Prism.highlightElement(el);
                                     el.classList.add('prism-highlighted');
                                 }
                             }
                         });
                     }
                 }">
                 
                <!-- Compact Trigger Card -->
                <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm flex items-center justify-between gap-4 mt-6">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-gray-900 text-sm">Xem trước nội dung (Preview)</h3>
                            <p class="text-xs text-gray-500 mt-0.5 truncate">Hỗ trợ đọc thử PDF và duyệt cây thư mục code của tệp ZIP</p>
                        </div>
                    </div>
                    <button @click="isFullscreen = true" class="inline-flex rounded-xl bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 text-xs font-semibold shadow-sm transition-all whitespace-nowrap">
                        Xem thử tài liệu
                    </button>
                </div>

                <!-- Backdrop when Fullscreen -->
                <div x-show="isFullscreen" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-40 transition-opacity duration-300" style="display: none;" @click="isFullscreen = false"></div>

                <!-- Fullscreen Modal Container -->
                <div x-show="isFullscreen" 
                     class="fixed inset-4 md:inset-8 z-50 rounded-3xl bg-white border border-gray-200 p-6 md:p-8 flex flex-col h-[calc(100vh-64px)] shadow-2xl space-y-4"
                     style="display: none;"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 scale-95">
                     
                    <div class="flex items-center justify-between shrink-0">
                        <div class="flex items-center gap-3">
                            <h2 class="text-lg font-bold text-gray-900">Xem trước nội dung (Preview)</h2>
                            <span class="rounded-xl bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-600">Định dạng {{ strtoupper($activeVersion?->file_type ?? 'PDF') }}</span>
                        </div>
                        <button @click="isFullscreen = false" class="p-2 rounded-xl hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition-colors flex items-center gap-1.5 text-xs font-bold border border-gray-200 bg-gray-50 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Đóng
                        </button>
                    </div>

                    <!-- Content Area -->
                    <div class="flex-1 min-h-0 w-full">
                        @if($activeVersion && strtolower($activeVersion->file_type) === 'pdf')
                            @php
                                $watermarkedUrl = ($activeVersion->watermark_status === 'success' && $activeVersion->file_watermarked_path) ? $activeVersion->file_watermarked_url : null;
                                $pdfUrl = $watermarkedUrl ?? $activeVersion->file_original_url;
                            @endphp

                            @if($pdfUrl && $originalFileExists)
                                <div class="rounded-2xl overflow-hidden border border-gray-200 shadow-inner h-full w-full">
                                    <iframe src="{{ $pdfUrl }}#toolbar=0" class="w-full h-full border-0"></iframe>
                                </div>
                            @else
                                <div class="rounded-2xl border border-gray-200 bg-gray-50 p-8 text-center text-gray-500 h-full w-full flex items-center justify-center">
                                    <p class="text-sm font-semibold">⚠️ Tệp PDF chưa được đóng dấu hoặc không tồn tại trên Cloudflare.</p>
                                </div>
                            @endif

                        @elseif($activeVersion && strtolower($activeVersion->file_type) === 'docx')
                            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-8 text-center text-gray-500 h-full w-full flex items-center justify-center">
                                <p class="text-sm font-semibold">Tài liệu DOCX không hỗ trợ xem trực tiếp. Vui lòng tải xuống để xem.</p>
                            </div>

                        @elseif($activeVersion && strtolower($activeVersion->file_type) === 'zip')
                            <!-- Prism.js CSS - LIGHT THEME -->
                            <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism.min.css" rel="stylesheet" />
                            
                            <div class="rounded-2xl border border-gray-200 bg-gradient-to-br from-gray-50 to-white shadow-md overflow-hidden flex h-full w-full">
                                <!-- File Tree (Left Panel) -->
                                <div x-show="showTree" class="w-64 border-r border-gray-200 bg-gradient-to-b from-white to-gray-50 overflow-y-auto shrink-0 h-full">
                                    <div class="p-4 border-b border-gray-200 bg-white sticky top-0 z-10">
                                        <div class="flex items-center justify-between">
                                            <h3 class="font-bold text-gray-900 flex items-center gap-2 text-sm">
                                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                                Cấu trúc Project
                                            </h3>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">{{ count($zipFiles) }} files</p>
                                    </div>
                                    <div class="p-3">
                                        @php
                                            $tree = [];
                                            foreach($zipFiles as $path => $file) {
                                                $parts = explode('/', $path);
                                                $current = &$tree;
                                                foreach($parts as $i => $part) {
                                                    if($i === count($parts) - 1) {
                                                        $current[$part] = ['path' => $path, 'isFile' => true];
                                                    } else {
                                                        if(!isset($current[$part])) $current[$part] = [];
                                                        $current = &$current[$part];
                                                    }
                                                }
                                            }
                                            if (!function_exists('renderAdminTree')) {
                                                function renderAdminTree($tree, $prefix = '', $depth = 0) {
                                                    foreach($tree as $name => $item) {
                                                        if(isset($item['isFile'])) {
                                                            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                                                            $iconClass = match($ext) {
                                                                'php' => 'text-indigo-600',
                                                                'js', 'jsx' => 'text-yellow-600',
                                                                'ts', 'tsx' => 'text-blue-600',
                                                                'py' => 'text-green-600',
                                                                'java' => 'text-red-600',
                                                                'css', 'scss' => 'text-pink-600',
                                                                'html' => 'text-orange-600',
                                                                'json', 'xml' => 'text-purple-600',
                                                                'md' => 'text-gray-600',
                                                                default => 'text-gray-500'
                                                            };
                                                            echo '<div @click="selectFile('.htmlspecialchars(json_encode($item['path'])).')" 
                                                                  class="flex items-center gap-2 px-3 py-1.5 hover:bg-blue-50 cursor-pointer rounded-lg text-xs transition-all group"
                                                                  :class="selectedFile === '.htmlspecialchars(json_encode($item['path'])).' ? \'bg-blue-100 text-blue-800 font-semibold shadow-sm\' : \'text-gray-700 hover:text-blue-700\'"
                                                                  style="margin-left: '.($depth * 12).'px">
                                                                  <svg class="w-3.5 h-3.5 '.$iconClass.'" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                                                                  <span class="truncate flex-1">'.htmlspecialchars($name).'</span>
                                                            </div>';
                                                        } else {
                                                            echo '<div class="mt-1">';
                                                            echo '<div class="flex items-center gap-1.5 px-2 py-1 text-xs font-semibold text-gray-700" style="margin-left: '.($depth * 12).'px">
                                                                  <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                                                  '.htmlspecialchars($name).'
                                                            </div>';
                                                            renderAdminTree($item, $prefix.$name.'/', $depth + 1);
                                                            echo '</div>';
                                                        }
                                                    }
                                                }
                                            }
                                            renderAdminTree($tree);
                                        @endphp
                                    </div>
                                </div>

                                 <!-- Code Preview (Right Panel) -->
                                <div class="flex-1 bg-white overflow-hidden flex flex-col h-full" id="admin-code-preview-container">
                                    <div class="px-4 py-3 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white sticky top-0 z-10 flex items-center justify-between shrink-0">
                                        <div class="flex items-center gap-2">
                                            <button @click="showTree = !showTree" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors shrink-0">
                                                <svg x-show="showTree" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
                                                <svg x-show="!showTree" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                                            </button>
                                            <div x-show="!selectedFile" class="text-gray-500 text-xs">Chọn file để xem thử code</div>
                                            <div x-show="selectedFile" class="flex items-center truncate max-w-xs md:max-w-md">
                                                <span class="font-mono text-xs text-gray-800 font-semibold truncate" x-text="selectedFile"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-1 overflow-y-auto overflow-x-auto bg-white min-h-0">
                                        <div x-show="!selectedFile" class="h-full flex items-center justify-center text-gray-400 bg-gradient-to-br from-gray-50 to-gray-100">
                                            <div class="text-center p-6">
                                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                                <p class="text-xs font-semibold text-gray-600">Chưa chọn file</p>
                                            </div>
                                        </div>
                                        @foreach($zipFiles as $path => $fileData)
                                            @php
                                                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                                $lang = match($ext) {
                                                    'php' => 'php',
                                                    'js', 'jsx' => 'javascript',
                                                    'ts', 'tsx' => 'typescript',
                                                    'py' => 'python',
                                                    'java' => 'java',
                                                    'css' => 'css',
                                                    'scss' => 'scss',
                                                    'html' => 'markup',
                                                    'json' => 'json',
                                                    'xml' => 'xml',
                                                    'md' => 'markdown',
                                                    'sql' => 'sql',
                                                    'yml', 'yaml' => 'yaml',
                                                    default => 'markup'
                                                };
                                            @endphp
                                            <div x-show="selectedFile === '{{ $path }}'" data-path="{{ $path }}" style="display: none;">
                                                <pre class="!m-0 !rounded-none" style="font-size: 14px !important; line-height: 1.8 !important; padding: 1.5rem !important; background: #fafafa !important;"><code class="language-{{ $lang }}" style="font-size: 14px !important; line-height: 1.8 !important;">{{ $fileData['content'] }}</code></pre>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Prism.js Scripts -->
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup-templating.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-clike.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-typescript.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-python.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-java.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-css.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-json.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markdown.min.js"></script>
                            <script>
                                document.addEventListener('alpine:initialized', () => {
                                    // Prism.js managed manually
                                });
                            </script>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column (30%) -->
        <div class="space-y-6 lg:col-span-1">
            <!-- Thumbnail Card -->
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm space-y-4">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block">Ảnh bìa tài liệu</span>
                @if($activeVersion?->thumbnail)
                    <div @click="showImageModal = true" class="aspect-video w-full rounded-2xl overflow-hidden bg-gray-100 border border-gray-200 relative group cursor-pointer hover:border-blue-400 hover:shadow-lg transition-all">
                        <img src="{{ $activeVersion?->thumbnail_url }}" alt="{{ $doc->title }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" />
                    </div>
                @else
                    <div class="aspect-video w-full rounded-2xl bg-gray-50 border border-gray-200 border-dashed flex flex-col items-center justify-center text-gray-400">
                        <svg class="w-8 h-8 mb-2 text-gray-300 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="text-xs font-medium text-gray-400">Không có ảnh bìa</span>
                    </div>
                @endif
            </div>

            <!-- Gallery Images -->
            @if(!$editMode && $activeVersion && $activeVersion->gallery_images && is_array($activeVersion->gallery_images) && count($activeVersion->gallery_images) > 0)
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm space-y-4">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block">Ảnh gallery</span>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($activeVersion->gallery_images as $image)
                            <div class="rounded-xl overflow-hidden border border-gray-200 aspect-video">
                                <img src="{{ Storage::disk('r2')->url($image['path']) }}" 
                                     alt="Gallery" 
                                     class="w-full h-full object-cover"
                                     loading="lazy" />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Moderation Panel -->
            @if($doc->status !== 'pending')
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm space-y-6">
                    <div>
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest block">Trạng thái duyệt</span>
                        @if($doc->status === 'approved')
                            <span class="mt-2 inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">
                                Đã phê duyệt
                            </span>
                        @elseif($doc->status === 'rejected')
                            <span class="mt-2 inline-flex rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-800">
                                Đã từ chối
                            </span>
                        @else
                            <span class="mt-2 inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-800">
                                {{ ucfirst($doc->status) }}
                            </span>
                        @endif
                    </div>

                    @if($doc->status === 'rejected' && $doc->rejected_reason)
                        <div class="border-t border-gray-100 pt-4">
                            <span class="text-xs font-semibold text-gray-400 block mb-1">Lý do từ chối:</span>
                            <p class="text-xs bg-rose-50 text-rose-800 p-3 rounded-xl border border-rose-100 font-medium">
                                {{ $doc->rejected_reason }}
                            </p>
                        </div>
                    @endif

                    @if($doc->reviewer)
                        <div class="border-t border-gray-100 pt-4 text-xs text-gray-500 space-y-1">
                            <p>Người duyệt: <span class="font-bold text-gray-900">{{ $doc->reviewer->name }}</span></p>
                            @if($doc->reviewed_at)
                                <p>Ngày duyệt: <span class="font-medium text-gray-700">{{ $doc->reviewed_at->format('d/m/Y H:i') }}</span></p>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            <!-- Author & Category Info -->
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm space-y-6">
                <!-- Author -->
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-gray-900 text-white flex items-center justify-center font-bold text-sm uppercase">
                        {{ substr($doc->author?->name ?? 'A', 0, 1) }}
                    </div>
                    <div>
                        <span class="text-[10px] text-gray-400 font-semibold uppercase block">Tác giả tải lên</span>
                        <h3 class="text-sm font-bold text-gray-900">{{ $doc->author?->name ?? 'Giảng viên / CTV' }}</h3>
                    </div>
                </div>

                <!-- Price Model -->
                <div class="border-t border-gray-100 pt-6">
                    <span class="text-xs text-gray-400 uppercase tracking-widest block font-medium">Hình thức</span>
                    <div class="mt-2">
                        @if($doc->product && $doc->product->price > 0)
                            @if($doc->product->sale_price)
                                <span class="text-lg font-bold text-blue-600">Trả phí: {{ number_format($doc->product->sale_price) }}đ</span>
                                <span class="text-sm text-gray-400 line-through font-medium ml-2">{{ number_format($doc->product->price) }}đ</span>
                            @else
                                <span class="text-lg font-bold text-blue-600">Trả phí: {{ number_format($doc->product->price) }}đ</span>
                            @endif
                        @else
                            <span class="text-lg font-bold text-emerald-600">Miễn phí</span>
                        @endif
                    </div>
                </div>

                <!-- Details -->
                <div class="border-t border-gray-100 pt-6 space-y-3 text-xs text-gray-500 font-medium">
                    <div class="flex items-center justify-between">
                        <span>Chế độ hiển thị:</span>
                        <span class="font-bold text-gray-800 uppercase">{{ $activeVersion?->visibility }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Được tải xuống:</span>
                        <span class="font-bold text-gray-800">{{ $activeVersion?->is_downloadable ? 'Có' : 'Không' }}</span>
                    </div>
                    <div class="flex items-center justify-between pt-2 border-t border-gray-100/50">
                        <span>Lượt xem:</span>
                        <span class="font-bold text-gray-800">{{ number_format($doc->view_count) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Lượt tải:</span>
                        <span class="font-bold text-gray-800">{{ number_format($doc->download_count) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Yêu thích:</span>
                        <span class="font-bold text-gray-800">{{ number_format($doc->favorite_count) }}</span>
                    </div>
                    @if($doc->published_at)
                        <div class="flex items-center justify-between pt-2 border-t border-gray-100/50">
                            <span>Ngày xuất bản:</span>
                            <span class="font-bold text-gray-850">{{ $doc->published_at->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Rejection Modal -->
    <template x-teleport="body">
        <div x-show="showRejectModal" 
             class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" 
             style="display: none;"
             x-transition>
            <div class="bg-white rounded-3xl max-w-lg w-full border border-gray-200 shadow-2xl p-6 space-y-6" @click.away="showRejectModal = false">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-lg font-bold text-gray-900">Từ chối phê duyệt tài liệu</h3>
                <button @click="showRejectModal = false" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            
            <div class="space-y-2">
                <label for="admin-rejection-reason" class="block text-sm font-semibold text-gray-700">Lý do từ chối <span class="text-red-500">*</span> (tối thiểu 10 ký tự):</label>
                <textarea id="admin-rejection-reason"
                          wire:model="rejectionReason" 
                          rows="4" 
                          placeholder="Ví dụ: Tài liệu tải lên bị lỗi font chữ, tài liệu có bản quyền, file bị hỏng không giải nén được..."
                          class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:border-gray-400 focus:outline-none"></textarea>
                @error('rejectionReason')
                    <span class="text-xs text-red-500 font-medium">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-gray-100">
                <button type="button" @click="showRejectModal = false" class="rounded-xl border border-gray-200 hover:bg-gray-50 px-4 py-2.5 text-xs font-semibold text-gray-700 transition-colors">
                    Hủy bỏ
                </button>
                <button type="button" wire:click="confirmRejection" class="rounded-xl bg-rose-600 hover:bg-rose-500 text-white px-5 py-2.5 text-xs font-semibold shadow-sm transition-colors">
                    Xác nhận từ chối
                </button>
            </div>
        </div>
    </template>

    <!-- Image Lightbox Modal -->
    @if($activeVersion?->thumbnail)
    <div x-show="showImageModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-75 backdrop-blur-sm" 
         style="display: none;"
         x-transition
         @click="showImageModal = false">
        <div class="relative max-w-[85vw] max-h-[85vh] flex flex-col items-center" @click.stop>
            <img src="{{ $activeVersion?->thumbnail_url }}" alt="{{ $doc->title }}" class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl" />
        </div>
    </div>
    @endif

    <!-- History Modal -->
    @if($showHistoryModal)
    <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-4xl w-full border border-gray-200 shadow-2xl p-6 space-y-4 max-h-[90vh] overflow-y-auto 
                    [&::-webkit-scrollbar]:w-2 
                    [&::-webkit-scrollbar-track]:bg-gray-100 
                    [&::-webkit-scrollbar-track]:rounded-full
                    [&::-webkit-scrollbar-thumb]:bg-gray-300 
                    [&::-webkit-scrollbar-thumb]:rounded-full
                    [&::-webkit-scrollbar-thumb]:hover:bg-gray-400">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3 sticky top-0 bg-white z-10 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900">📋 Lịch sử gửi & duyệt tài liệu</h3>
                <button wire:click="closeHistoryModal" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            
            @if($submissionHistory && count($submissionHistory) > 0)
                <div class="space-y-0">
                    @foreach($submissionHistory as $index => $event)
                        <div class="relative pl-8 pb-6 {{ $index < count($submissionHistory) - 1 ? 'border-l-2 border-gray-200' : '' }}">
                            <div class="absolute left-0 top-0 -ml-[7px] h-3.5 w-3.5 rounded-full border-2 
                                {{ $event->type === 'submission' ? 'bg-indigo-500 border-indigo-600' : '' }}
                                {{ $event->type === 'review' && $event->status === 'approved' ? 'bg-emerald-500 border-emerald-600' : '' }}
                                {{ $event->type === 'review' && $event->status === 'rejected' ? 'bg-rose-500 border-rose-600' : '' }}">
                            </div>
                            
                            <div class="space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold text-gray-800">
                                        @if($event->type === 'submission')
                                            @if(isset($event->version_number) && $event->version_number > 1)
                                                {{ $event->user && $event->user->hasRole('admin') ? '🔄 Cập nhật tài liệu' : '🔄 Gửi bản cập nhật' }}
                                            @else
                                                {{ $event->user && $event->user->hasRole('admin') ? '📝 Đăng tài liệu' : '📤 Gửi tài liệu mới' }}
                                            @endif
                                        @elseif($event->type === 'review')
                                            @if($event->status === 'approved')
                                                ✅ Phê duyệt
                                            @elseif($event->status === 'rejected')
                                                ❌ Từ chối
                                            @endif
                                        @endif
                                    </span>
                                    <span class="text-xs text-gray-400 whitespace-nowrap ml-2">
                                        {{ $event->timestamp instanceof \Carbon\Carbon ? $event->timestamp->format('d/m/Y H:i') : (is_string($event->timestamp) ? date('d/m/Y H:i', strtotime($event->timestamp)) : '') }}
                                    </span>
                                </div>
                                
                                <div class="text-xs text-gray-500">
                                    @if($event->user)
                                        <div class="break-words">
                                            <span class="font-medium text-gray-600">{{ $event->user->name ?? 'Unknown' }}</span>
                                            @if($event->type === 'submission')
                                                @if(isset($event->version_number) && $event->version_number > 1)
                                                    <span>{{ $event->user && $event->user->hasRole('admin') ? 'đã cập nhật tài liệu' : 'đã gửi bản cập nhật mới' }}</span>
                                                @else
                                                    <span>{{ $event->user && $event->user->hasRole('admin') ? 'đã đăng tài liệu' : 'đã gửi tài liệu' }}</span>
                                                @endif
                                            @elseif($event->type === 'review')
                                                <span>đã {{ $event->status === 'approved' ? 'phê duyệt' : 'từ chối' }}</span>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    @if(isset($event->details) && $event->details)
                                        <div class="mt-1.5 p-2 bg-rose-50 border-l-2 border-rose-400 rounded text-[11px]">
                                            <span class="font-bold text-rose-800">Lý do từ chối:</span>
                                            <span class="text-rose-700">"{{ $event->details }}"</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Chưa có lịch sử gửi & duyệt
                </div>
            @endif
            
            <div class="pt-3 border-t border-gray-100">
                <button wire:click="closeHistoryModal" class="w-full rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 text-sm font-bold transition-colors">
                    Đóng
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

