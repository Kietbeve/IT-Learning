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

    @if(!empty($changes))
    <!-- Changes Section -->
    <div class="rounded-3xl border-2 border-amber-200 bg-amber-50 p-6 shadow-sm">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="flex-1">
                <h3 class="text-base font-bold text-amber-900 mb-3">Những thay đổi so với bản gốc</h3>
                <div class="space-y-4">
                    @foreach($changes as $field => $change)
                        <div class="bg-white rounded-2xl p-4 border border-amber-200">
                            <div class="text-xs font-semibold text-amber-700 uppercase mb-3">
                                @if($field === 'title') Tiêu đề
                                @elseif($field === 'short_description') Mô tả ngắn
                                @elseif($field === 'description') Nội dung chi tiết
                                @elseif($field === 'category') Danh mục
                                @elseif($field === 'visibility') Hiển thị
                                @elseif($field === 'is_downloadable') Cho phép tải xuống
                                @elseif($field === 'price') Giá
                                @elseif($field === 'file') Tệp tin
                                @else {{ ucfirst($field) }}
                                @endif
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <div class="text-xs font-medium text-slate-500 mb-1.5">Cũ:</div>
                                    <div class="text-sm text-slate-700 bg-red-50 border border-red-200 rounded-xl p-3">
                                        {{ Str::limit($change['old'], 200) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-xs font-medium text-slate-500 mb-1.5">Mới:</div>
                                    <div class="text-sm text-slate-700 bg-green-50 border border-green-200 rounded-xl p-3">
                                        {{ Str::limit($change['new'], 200) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Layout 2 Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column (70%) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Details Card -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 md:p-8 shadow-sm space-y-6">
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
            </div>

            <!-- Original File Info & Preview -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 md:p-8 shadow-sm space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-bold text-slate-900">Tệp tin đính kèm gốc</h2>
                    <button wire:click="downloadOriginal" class="inline-flex rounded-xl bg-slate-900 hover:bg-slate-800 text-white px-4 py-2.5 text-xs font-semibold shadow-sm transition-all">
                        Tải xuống file gốc
                    </button>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between text-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-8 h-8 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        <div class="min-w-0 flex-1">
                            @php
                                $originalExt = pathinfo($doc->file_original_path, PATHINFO_EXTENSION);
                            @endphp
                            <p class="font-semibold text-slate-900 truncate" title="{{ $doc->slug }}.{{ $originalExt }}">{{ $doc->slug }}.{{ $originalExt }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">Dung lượng: {{ number_format($doc->file_size / 1024 / 1024, 2) }} MB | Định dạng: {{ strtoupper($originalExt) }}</p>
                        </div>
                    </div>
                </div>

                <!-- File Variants -->
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <!-- Preview File -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-sm">
                        <div class="min-w-0 flex-1">
                            <span class="font-bold text-slate-900 block">Tệp xem trước (Preview File):</span>
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
            @if($doc->status === 'pending')
                <div class="rounded-3xl border border-yellow-200 bg-yellow-50/50 p-6 shadow-sm space-y-6">
                    <div>
                        <span class="text-xs font-bold text-yellow-600 uppercase tracking-widest block">Trạng thái</span>
                        <span class="mt-2 inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">
                            Chờ kiểm duyệt
                        </span>
                    </div>

                    <div class="space-y-3 pt-4 border-t border-yellow-200/50">
                        <button wire:click="approve" class="w-full rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white py-3.5 text-xs font-semibold shadow-md shadow-emerald-600/10 transition-all">
                            Phê duyệt tài liệu này
                        </button>
                        <button wire:click="openRejectionModal" class="w-full rounded-2xl bg-rose-600 hover:bg-rose-500 text-white py-3.5 text-xs font-semibold shadow-md shadow-rose-600/10 transition-all">
                            Từ chối tài liệu này
                        </button>
                    </div>
                </div>
            @else
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
                    <div class="flex items-center justify-between">
                        <span>Đóng dấu Watermark:</span>
                        <span class="font-bold text-slate-800 uppercase">{{ $doc->watermark_status }}</span>
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
</div>
