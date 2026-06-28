<div x-data="{ showRejectModal: false, showImageModal: false }" 
     @open-modal.window="let d = $event.detail; if (d === 'reject-detail-modal' || d?.[0] === 'reject-detail-modal' || d?.id === 'reject-detail-modal') showRejectModal = true"
     @close-modal.window="let d = $event.detail; if (d === 'reject-detail-modal' || d?.[0] === 'reject-detail-modal' || d?.id === 'reject-detail-modal') showRejectModal = false"
     class="space-y-6">

    <!-- Back Button -->
    <div class="mb-4">
        @if($from === 'list')
            <a href="{{ route('admin.documents.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Quay lại quản lý tài liệu
            </a>
        @else
            <a href="{{ route('admin.moderation.documents.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Quay lại hàng đợi
            </a>
        @endif
    </div>

    <!-- Status & Action Buttons Bar (Top) -->
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Status Badge -->
            <div>
                <span class="text-xs font-medium text-slate-500 uppercase tracking-wider block mb-2">Trạng thái</span>
                @if($doc->status === 'pending')
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
                    <span class="inline-flex rounded-full bg-slate-100 px-4 py-1.5 text-xs font-bold text-slate-800">
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
                    <button wire:click="cancelEdit" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 px-4 py-2 text-sm font-semibold transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Hủy
                    </button>
                @else
                    <!-- Normal Mode Buttons -->
                    <button wire:click="showHistory" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 px-4 py-2 text-sm font-semibold transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Xem nhật ký
                    </button>
                    <button wire:click="editDocument" class="inline-flex items-center gap-1.5 rounded-xl border border-blue-200 bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-2 text-sm font-semibold transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Sửa
                    </button>
                    @if($doc->status === 'pending')
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

    <!-- Layout 2 Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column (70%) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Details Card -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 md:p-8 shadow-sm space-y-6">
                @if($editMode)
                    <!-- Edit Mode -->
                    <div class="space-y-4">
                        <!-- Category -->
                        <div>
                            <label class="text-xs font-semibold text-slate-700 uppercase tracking-wider block mb-2">Danh mục</label>
                            <select wire:model="editCategoryId" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:outline-none">
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Title -->
                        <div>
                            <label class="text-xs font-semibold text-slate-700 uppercase tracking-wider block mb-2">Tiêu đề</label>
                            <input type="text" wire:model="editTitle" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-lg font-bold text-slate-900 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:outline-none">
                            @error('editTitle') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Short Description -->
                        <div>
                            <label class="text-xs font-semibold text-slate-700 uppercase tracking-wider block mb-2">Mô tả ngắn</label>
                            <textarea wire:model="editShortDescription" rows="3" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:outline-none"></textarea>
                        </div>
                        
                        <!-- Description -->
                        <div>
                            <label class="text-xs font-semibold text-slate-700 uppercase tracking-wider block mb-2">Nội dung chi tiết</label>
                            <textarea wire:model="editDescription" rows="8" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:outline-none"></textarea>
                            @error('editDescription') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Price -->
                        <div>
                            <label class="text-xs font-semibold text-slate-700 uppercase tracking-wider block mb-2">Giá (VND) - 0 là miễn phí</label>
                            <input type="number" wire:model="editPrice" min="0" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:outline-none" placeholder="0">
                            @error('editPrice') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Visibility -->
                        <div>
                            <label class="text-xs font-semibold text-slate-700 uppercase tracking-wider block mb-2">Chế độ hiển thị</label>
                            <select wire:model="editVisibility" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 focus:outline-none">
                                <option value="public">Công khai (Public)</option>
                                <option value="private">Riêng tư (Private)</option>
                                <option value="unlisted">Không liệt kê (Unlisted)</option>
                            </select>
                        </div>
                        
                        <!-- Is Downloadable -->
                        <div>
                            <label class="text-xs font-semibold text-slate-700 uppercase tracking-wider block mb-2">Quyền tải xuống</label>
                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" wire:model="editIsDownloadable" value="1" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-slate-700">Cho phép tải xuống</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" wire:model="editIsDownloadable" value="0" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-slate-700">Không cho phép</span>
                                </label>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- View Mode -->
                    <div>
                        <span class="rounded-xl px-2.5 py-1 text-xs font-semibold bg-slate-100 text-slate-800">
                            {{ $doc->category?->name ?? 'Tài liệu' }}
                        </span>
                        <h1 class="mt-4 text-2xl font-bold tracking-tight text-slate-900">
                            {{ $doc->title }}
                        </h1>
                    </div>

                    <div class="border-t border-slate-100 pt-6 space-y-3">
                        <h2 class="text-base font-bold text-slate-900">Mô tả ngắn</h2>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $doc->short_description }}</p>
                    </div>

                    <div class="border-t border-slate-100 pt-6 space-y-3">
                        <h2 class="text-base font-bold text-slate-900">Nội dung chi tiết</h2>
                        <div class="text-sm text-slate-600 leading-relaxed whitespace-pre-line">{{ $doc->description }}</div>
                    </div>
                @endif
            </div>

            <!-- Original File Info & Preview -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 md:p-8 shadow-sm space-y-6">
            <!-- File Variants -->
                <div class="space-y-4">
                    <!-- Original File -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-sm">
                        <div class="min-w-0 flex-1">
                            <span class="font-bold text-slate-900 block">Tệp gốc (Original File):</span>
                            @if($doc->file_type && $originalFileSize)
                                <span class="text-xs text-slate-600 font-semibold block mt-1">{{ strtoupper($doc->file_type) }} • {{ $originalFileSize }}</span>
                            @endif
                            <span class="text-xs text-slate-500 font-mono break-all block mt-0.5">{{ $doc->file_original_path ?? 'Không có tệp gốc' }}</span>
                        </div>
                        @if($doc->file_original_path)
                            <a href="{{ $doc->file_original_url }}" target="_blank" class="inline-flex rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 px-3.5 py-2 text-xs font-semibold shadow-sm transition-all whitespace-nowrap self-start sm:self-center">
                                Xem tệp
                            </a>
                        @endif
                    </div>

                    <!-- Preview File -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-sm pt-4 border-t border-slate-50">
                        <div class="min-w-0 flex-1">
                            <span class="font-bold text-slate-900 block">Tệp xem trước (Preview File):</span>
                            @if($previewFileSize)
                                <span class="text-xs text-slate-600 font-semibold block mt-1">PDF • {{ $previewFileSize }}</span>
                            @endif
                            <span class="text-xs text-slate-500 font-mono break-all block mt-0.5">{{ $doc->preview_file_path ?? 'Không có tệp xem trước' }}</span>
                        </div>
                        @if($doc->preview_file_path)
                            <a href="{{ $doc->preview_file_url }}" target="_blank" class="inline-flex rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 px-3.5 py-2 text-xs font-semibold shadow-sm transition-all whitespace-nowrap self-start sm:self-center">
                                Xem tệp
                            </a>
                        @endif
                    </div>

                    <!-- Watermarked File -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-sm pt-4 border-t border-slate-50">
                        <div class="min-w-0 flex-1">
                            <span class="font-bold text-slate-900 block">Tệp đóng dấu (Watermarked File):</span>
                            @if($watermarkedFileSize)
                                <span class="text-xs text-slate-600 font-semibold block mt-1">PDF • {{ $watermarkedFileSize }}</span>
                            @endif
                            <span class="text-xs text-slate-500 font-mono break-all block mt-0.5">{{ $doc->file_watermarked_path ?? 'Chưa tạo tệp đóng dấu' }}</span>
                        </div>
                        @if($doc->file_watermarked_path)
                            <a href="{{ $doc->file_watermarked_url }}" target="_blank" class="inline-flex rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 px-3.5 py-2 text-xs font-semibold shadow-sm transition-all whitespace-nowrap self-start sm:self-center">
                                Xem tệp
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (30%) -->
        <div class="space-y-6 lg:col-span-1">
            <!-- Thumbnail Card -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block">Ảnh bìa tài liệu</span>
                @if($doc->thumbnail)
                    <div @click="showImageModal = true" class="aspect-video w-full rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 relative group cursor-pointer hover:border-blue-400 hover:shadow-lg transition-all">
                        <img src="{{ $doc->thumbnail_url }}" alt="{{ $doc->title }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" />
                    </div>
                @else
                    <div class="aspect-video w-full rounded-2xl bg-slate-50 border border-slate-200 border-dashed flex flex-col items-center justify-center text-slate-400">
                        <svg class="w-8 h-8 mb-2 text-slate-300 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="text-xs font-medium text-slate-400">Không có ảnh bìa</span>
                    </div>
                @endif
            </div>

            <!-- Moderation Panel -->
            @if($doc->status !== 'pending')
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest block">Trạng thái duyệt</span>
                        @if($doc->status === 'approved')
                            <span class="mt-2 inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">
                                Đã phê duyệt
                            </span>
                        @elseif($doc->status === 'rejected')
                            <span class="mt-2 inline-flex rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-800">
                                Đã từ chối
                            </span>
                        @else
                            <span class="mt-2 inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-800">
                                {{ ucfirst($doc->status) }}
                            </span>
                        @endif
                    </div>

                    @if($doc->status === 'rejected' && $doc->rejected_reason)
                        <div class="border-t border-slate-100 pt-4">
                            <span class="text-xs font-semibold text-slate-400 block mb-1">Lý do từ chối:</span>
                            <p class="text-xs bg-rose-50 text-rose-800 p-3 rounded-xl border border-rose-100 font-medium">
                                {{ $doc->rejected_reason }}
                            </p>
                        </div>
                    @endif

                    @if($doc->reviewer)
                        <div class="border-t border-slate-100 pt-4 text-xs text-slate-500 space-y-1">
                            <p>Người duyệt: <span class="font-bold text-slate-900">{{ $doc->reviewer->name }}</span></p>
                            @if($doc->reviewed_at)
                                <p>Ngày duyệt: <span class="font-medium text-slate-700">{{ $doc->reviewed_at->format('d/m/Y H:i') }}</span></p>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            <!-- Author & Category Info -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
                <!-- Author -->
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-sm uppercase">
                        {{ substr($doc->author?->name ?? 'A', 0, 1) }}
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 font-semibold uppercase block">Tác giả tải lên</span>
                        <h3 class="text-sm font-bold text-slate-900">{{ $doc->author?->name ?? 'Giảng viên / CTV' }}</h3>
                    </div>
                </div>

                <!-- Price Model -->
                <div class="border-t border-slate-100 pt-6">
                    <span class="text-xs text-slate-400 uppercase tracking-widest block font-medium">Hình thức</span>
                    <div class="mt-2">
                        @if($doc->product)
                            <span class="text-lg font-bold text-blue-600">Trả phí: {{ number_format($doc->product->price) }}đ</span>
                        @else
                            <span class="text-lg font-bold text-emerald-600">Miễn phí</span>
                        @endif
                    </div>
                </div>

                <!-- Details -->
                <div class="border-t border-slate-100 pt-6 space-y-3 text-xs text-slate-500 font-medium">
                    <div class="flex items-center justify-between">
                        <span>Chế độ hiển thị:</span>
                        <span class="font-bold text-slate-800 uppercase">{{ $doc->visibility }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Được tải xuống:</span>
                        <span class="font-bold text-slate-800">{{ $doc->is_downloadable ? 'Có' : 'Không' }}</span>
                    </div>
                    <div class="flex items-center justify-between pt-2 border-t border-slate-100/50">
                        <span>Lượt xem:</span>
                        <span class="font-bold text-slate-800">{{ number_format($doc->view_count) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Lượt tải:</span>
                        <span class="font-bold text-slate-800">{{ number_format($doc->download_count) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Yêu thích:</span>
                        <span class="font-bold text-slate-800">{{ number_format($doc->favorite_count) }}</span>
                    </div>
                    @if($doc->published_at)
                        <div class="flex items-center justify-between pt-2 border-t border-slate-100/50">
                            <span>Ngày xuất bản:</span>
                            <span class="font-bold text-slate-850">{{ $doc->published_at->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div x-show="showRejectModal" 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" 
         style="display: none;"
         x-transition>
        <div class="bg-white rounded-3xl max-w-lg w-full border border-slate-200 shadow-2xl p-6 space-y-6" @click.away="showRejectModal = false">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-lg font-bold text-slate-900">Từ chối phê duyệt tài liệu</h3>
                <button @click="showRejectModal = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>
            
            <div class="space-y-2">
                <label for="admin-rejection-reason" class="block text-sm font-semibold text-slate-700">Lý do từ chối <span class="text-red-500">*</span> (tối thiểu 10 ký tự):</label>
                <textarea id="admin-rejection-reason"
                          wire:model="rejectionReason" 
                          rows="4" 
                          placeholder="Ví dụ: Tài liệu tải lên bị lỗi font chữ, tài liệu có bản quyền, file bị hỏng không giải nén được..."
                          class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-slate-400 focus:outline-none"></textarea>
                @error('rejectionReason')
                    <span class="text-xs text-red-500 font-medium">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" @click="showRejectModal = false" class="rounded-xl border border-slate-200 hover:bg-slate-50 px-4 py-2.5 text-xs font-semibold text-slate-700 transition-colors">
                    Hủy bỏ
                </button>
                <button type="button" wire:click="confirmRejection" class="rounded-xl bg-rose-600 hover:bg-rose-500 text-white px-5 py-2.5 text-xs font-semibold shadow-sm transition-colors">
                    Xác nhận từ chối
                </button>
            </div>
        </div>
    </div>

    <!-- Image Lightbox Modal -->
    @if($doc->thumbnail)
    <div x-show="showImageModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-75 backdrop-blur-sm" 
         style="display: none;"
         x-transition
         @click="showImageModal = false">
        <div class="relative max-w-[85vw] max-h-[85vh] flex flex-col items-center" @click.stop>
            <img src="{{ $doc->thumbnail_url }}" alt="{{ $doc->title }}" class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl" />
        </div>
    </div>
    @endif

    <!-- History Modal -->
    @if($showHistoryModal)
    <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-4xl w-full border border-slate-200 shadow-2xl p-6 space-y-4 max-h-[90vh] overflow-y-auto 
                    [&::-webkit-scrollbar]:w-2 
                    [&::-webkit-scrollbar-track]:bg-slate-100 
                    [&::-webkit-scrollbar-track]:rounded-full
                    [&::-webkit-scrollbar-thumb]:bg-slate-300 
                    [&::-webkit-scrollbar-thumb]:rounded-full
                    [&::-webkit-scrollbar-thumb]:hover:bg-slate-400">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 sticky top-0 bg-white z-10 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900">📋 Lịch sử gửi & duyệt tài liệu</h3>
                <button wire:click="closeHistoryModal" class="text-slate-400 hover:text-slate-600 text-2xl">&times;</button>
            </div>
            
            @if($submissionHistory && count($submissionHistory) > 0)
                <div class="space-y-0">
                    @foreach($submissionHistory as $index => $event)
                        <div class="relative pl-8 pb-6 {{ $index < count($submissionHistory) - 1 ? 'border-l-2 border-slate-200' : '' }}">
                            <div class="absolute left-0 top-0 -ml-[7px] h-3.5 w-3.5 rounded-full border-2 
                                {{ $event->type === 'submission' ? 'bg-indigo-500 border-indigo-600' : '' }}
                                {{ $event->type === 'review' && $event->status === 'approved' ? 'bg-emerald-500 border-emerald-600' : '' }}
                                {{ $event->type === 'review' && $event->status === 'rejected' ? 'bg-rose-500 border-rose-600' : '' }}">
                            </div>
                            
                            <div class="space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold text-slate-800">
                                        @if($event->type === 'submission')
                                            📤 Gửi tài liệu
                                        @elseif($event->type === 'review')
                                            @if($event->status === 'approved')
                                                ✅ Phê duyệt
                                            @elseif($event->status === 'rejected')
                                                ❌ Từ chối
                                            @endif
                                        @endif
                                    </span>
                                    <span class="text-xs text-slate-400 whitespace-nowrap ml-2">
                                        {{ $event->timestamp instanceof \Carbon\Carbon ? $event->timestamp->format('d/m/Y H:i') : (is_string($event->timestamp) ? date('d/m/Y H:i', strtotime($event->timestamp)) : '') }}
                                    </span>
                                </div>
                                
                                <div class="text-xs text-slate-500">
                                    @if($event->user)
                                        <div class="break-words">
                                            <span class="font-medium text-slate-600">{{ $event->user->name ?? 'Unknown' }}</span>
                                            @if($event->type === 'submission')
                                                <span>đã gửi tài liệu</span>
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
                <div class="text-center py-8 text-slate-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Chưa có lịch sử gửi & duyệt
                </div>
            @endif
            
            <div class="pt-3 border-t border-slate-100">
                <button wire:click="closeHistoryModal" class="w-full rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 text-sm font-bold transition-colors">
                    Đóng
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
