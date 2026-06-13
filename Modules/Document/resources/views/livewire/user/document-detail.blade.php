<div class="max-w-7xl mx-auto py-6" x-data="{ notification: null }" x-on:notify.window="notification = $event.detail; setTimeout(() => notification = null, 3000)">
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

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('documents.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Quay lại kho tài liệu
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content (Left - 70%) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Document Info Card -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 md:p-8 shadow-sm space-y-6">
                <div>
                    <span class="rounded-xl px-2.5 py-1 text-xs font-semibold bg-slate-100 text-slate-800">
                        {{ $doc->category?->name ?? 'Tài liệu' }}
                    </span>
                    <h1 class="mt-4 text-2xl md:text-3xl font-bold tracking-tight text-slate-900 leading-tight">
                        {{ $doc->title }}
                    </h1>

                    <!-- Tags -->
                    @if($doc->tags->isNotEmpty())
                        <div class="mt-3 flex flex-wrap gap-1.5">
                            @foreach($doc->tags as $tag)
                                <span class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                    #{{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                    
                    <div class="mt-4 flex flex-wrap items-center gap-6 text-sm text-slate-500 font-medium">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>{{ number_format($doc->view_count) }} lượt xem</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>{{ number_format($doc->download_count) }} lượt tải</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-rose-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            <span>{{ number_format($doc->favorite_count) }} yêu thích</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-amber-500 fill-amber-500" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <span class="text-slate-900 font-bold">{{ $avgRating }}</span>
                            <span class="text-slate-400">({{ $totalReviews }} đánh giá)</span>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-6">
                    <h2 class="text-lg font-bold text-slate-900 mb-3">Mô tả tài liệu</h2>
                    <div class="text-slate-600 text-sm leading-relaxed prose prose-sm prose-slate max-w-none prose-headings:text-slate-900 prose-a:text-blue-600 prose-strong:text-slate-800 prose-code:text-rose-600 prose-code:bg-slate-100 prose-code:px-1 prose-code:py-0.5 prose-code:rounded">
                        {!! $doc->description !!}
                    </div>
                </div>
            </div>

            <!-- Preview Card -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 md:p-8 shadow-sm space-y-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-900">Xem trước tài nguyên</h2>
                    <span class="rounded-xl bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-600">Định dạng {{ strtoupper($doc->file_type) }}</span>
                </div>

                <!-- Interactive Viewer depending on File Type -->
                @if($doc->file_type === 'pdf')
                    @php
                        $watermarkedFile = ($doc->watermark_status === 'success' && $doc->file_watermarked_path) ? $doc->file_watermarked_path : null;
                        $pdfPath = $hasAccess ? ($watermarkedFile ?? $doc->file_original_path) : ($doc->preview_file_path ?? $doc->file_original_path);
                        $hasRealPdf = $pdfPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($pdfPath);
                    @endphp

                    @if($hasRealPdf)
                        <!-- Real PDF Reader iframe -->
                        <div class="rounded-2xl overflow-hidden h-[500px] border border-slate-200 shadow-inner">
                            <iframe src="{{ asset('storage/' . $pdfPath) }}#toolbar=0" class="w-full h-full border-0"></iframe>
                        </div>
                    @else
                        <!-- PDF Viewer Simulator -->
                        <div x-data="{ page: 1, maxPage: 3 }" class="rounded-2xl border border-slate-200 bg-slate-100 overflow-hidden flex flex-col h-[500px]">
                            <!-- Toolbar -->
                            <div class="bg-slate-800 text-white px-4 py-2.5 flex items-center justify-between text-xs">
                                <span class="font-medium">document_preview.pdf</span>
                                <div class="flex items-center gap-3">
                                    <button type="button" @click="if(page > 1) page--" :disabled="page === 1" class="p-1 hover:bg-slate-700 rounded transition disabled:opacity-30">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    </button>
                                    <span>Trang <span x-text="page"></span> / <span x-text="maxPage"></span></span>
                                    <button type="button" @click="if(page < maxPage) page++" :disabled="page === maxPage" class="p-1 hover:bg-slate-700 rounded transition disabled:opacity-30">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                                <span class="text-slate-400">Xem thử 60%</span>
                            </div>

                            <!-- Pages Container -->
                            <div class="flex-1 overflow-y-auto p-6 flex justify-center relative bg-slate-200/50">
                                <!-- Watermark Overlay (translucent, repeating text) -->
                                <div class="absolute inset-0 pointer-events-none z-10 flex flex-col justify-around items-center opacity-[0.04] select-none uppercase font-bold text-4xl tracking-widest text-slate-900 rotate-[-30deg]">
                                    <div>IT-Learning Watermark</div>
                                    <div>Tài liệu học tập</div>
                                    <div>IT-Learning Watermark</div>
                                </div>

                                <!-- Page 1 -->
                                <div x-show="page === 1" class="w-full max-w-lg bg-white shadow-md rounded border border-slate-100 p-8 flex flex-col justify-between min-h-[380px] relative">
                                    <div>
                                        <h3 class="text-xl font-bold text-slate-800 border-b border-slate-100 pb-3">{{ $doc->title }}</h3>
                                        <p class="mt-4 text-sm font-semibold text-slate-700">Mục lục & Giới thiệu:</p>
                                        <p class="mt-2 text-xs text-slate-600 leading-relaxed">{{ $doc->short_description }}</p>
                                        <p class="mt-4 text-xs text-slate-500 leading-relaxed">
                                            Tài liệu này được phát hành bởi hệ thống IT-Learning nhằm mục đích bổ trợ kiến thức lập trình cho học viên CNTT. Nghiêm cấm mọi hành vi sao chép và phân phối trái phép khi chưa được sự đồng ý của tác giả.
                                        </p>
                                    </div>
                                    <div class="text-[10px] text-slate-400 border-t border-slate-100 pt-3 text-center">Trang 1 - Bản quyền thuộc về IT-Learning</div>
                                </div>

                                <!-- Page 2 -->
                                <div x-show="page === 2" class="w-full max-w-lg bg-white shadow-md rounded border border-slate-100 p-8 flex flex-col justify-between min-h-[380px] relative">
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">Chương 1: Khởi đầu và Cấu trúc tổng quan</h4>
                                        <div class="mt-4 space-y-3">
                                            <p class="text-xs text-slate-600 leading-relaxed">
                                                {{ Str::limit($doc->description, 300) }}
                                            </p>
                                            <p class="text-xs text-slate-600 leading-relaxed">
                                                Dưới đây là một số nội dung tóm lược về chủ đề chính để người học có thể hình dung được quy mô kiến thức truyền đạt:
                                            </p>
                                            <ul class="list-disc list-inside text-xs text-slate-500 space-y-1 pl-2">
                                                <li>Khái niệm cơ bản và phương pháp cài đặt.</li>
                                                <li>Thiết lập môi trường làm việc chuẩn.</li>
                                                <li>Các mẫu thiết kế (Design patterns) ứng dụng phổ biến.</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="text-[10px] text-slate-400 border-t border-slate-100 pt-3 text-center">Trang 2 - Bản quyền thuộc về IT-Learning</div>
                                </div>

                                <!-- Page 3 (Locked page) -->
                                <div x-show="page === 3" class="w-full max-w-lg bg-white shadow-md rounded border border-slate-100 p-8 flex flex-col justify-center items-center min-h-[380px] relative overflow-hidden">
                                    <!-- Blurred background content -->
                                    <div class="absolute inset-0 p-8 opacity-25 filter blur-[3px] pointer-events-none select-none">
                                        <h4 class="text-sm font-bold text-slate-800">Chương 2: Triển khai thực tế & Tối ưu hóa</h4>
                                        <p class="mt-4 text-xs text-slate-600 leading-relaxed">
                                            Nội dung phần này đi sâu vào cách tối ưu hóa hiệu năng, xử lý lỗi ngoại lệ và các kỹ năng nâng cao phục vụ dự án thực tế.
                                        </p>
                                    </div>
                                    
                                    <!-- Lock Overlay -->
                                    <div class="relative z-10 flex flex-col items-center text-center p-6 bg-white/95 backdrop-blur-sm rounded-2xl max-w-xs shadow-lg border border-slate-100">
                                        <div class="h-10 w-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mb-3">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        </div>
                                        <h4 class="text-sm font-bold text-slate-900">Bản xem trước kết thúc</h4>
                                        <p class="mt-2 text-xs text-slate-500">Nội dung trang sau đã bị ẩn. Hãy tải xuống tài nguyên đầy đủ để tiếp tục đọc.</p>
                                        @if($hasAccess)
                                            <button wire:click="download" class="mt-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 text-xs font-semibold shadow transition-colors">
                                                Tải xuống đầy đủ
                                            </button>
                                        @else
                                            <button wire:click="buyDocument" class="mt-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 text-xs font-semibold shadow transition-colors">
                                                Mua để tải xuống
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                @elseif($doc->file_type === 'docx')
                    <!-- DOCX Viewer Simulator -->
                    <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden flex flex-col h-[500px]">
                        <!-- Office Header Ribbon -->
                        <div class="bg-blue-800 text-white px-4 py-2 flex items-center gap-4 text-xs font-medium border-b border-blue-900 shrink-0">
                            <span class="bg-blue-900 px-2 py-1 rounded text-[10px] font-bold">W</span>
                            <span class="hover:bg-blue-700 px-2 py-0.5 rounded cursor-pointer">Tệp</span>
                            <span class="hover:bg-blue-700 px-2 py-0.5 rounded cursor-pointer font-bold border-b-2 border-white">Trang chủ</span>
                            <span class="hover:bg-blue-700 px-2 py-0.5 rounded cursor-pointer">Chèn</span>
                            <span class="hover:bg-blue-700 px-2 py-0.5 rounded cursor-pointer">Bố cục</span>
                        </div>
                        
                        <!-- Word Body Container -->
                        <div class="flex-1 overflow-y-auto p-8 bg-slate-100 flex justify-center relative">
                            <!-- Watermark Overlay (translucent, repeating text) -->
                            <div class="absolute inset-0 pointer-events-none z-10 flex flex-col justify-around items-center opacity-[0.04] select-none uppercase font-bold text-4xl tracking-widest text-slate-900 rotate-[-30deg]">
                                <div>IT-Learning Watermark</div>
                                <div>Tài liệu Word</div>
                                <div>IT-Learning Watermark</div>
                            </div>

                            <div class="w-full max-w-lg bg-white shadow-md border border-slate-200 p-10 min-h-[500px] flex flex-col justify-between relative">
                                <div>
                                    <!-- Title -->
                                    <div class="text-center mb-8">
                                        <h3 class="text-2xl font-bold text-slate-800 uppercase tracking-tight">{{ $doc->title }}</h3>
                                        <div class="h-1 w-20 bg-blue-600 mx-auto mt-3"></div>
                                        <p class="text-xs text-slate-400 mt-2">Đăng tải bởi: {{ $doc->author?->name ?? 'IT-Learning' }}</p>
                                    </div>

                                    <!-- Content Mock -->
                                    <div class="space-y-4 text-xs text-slate-700">
                                        <p class="font-bold text-sm text-slate-800">I. GIỚI THIỆU CHUNG</p>
                                        <p class="leading-relaxed pl-4">{{ $doc->short_description }}</p>
                                        
                                        <p class="font-bold text-sm text-slate-800 mt-6">II. NỘI DUNG CHI TIẾT</p>
                                        <p class="leading-relaxed pl-4">{{ Str::limit($doc->description, 200) }}</p>
                                        
                                        <!-- Blurry end section -->
                                        <div class="relative pt-12">
                                            <div class="absolute inset-0 bg-gradient-to-t from-white via-white/80 to-transparent z-10"></div>
                                            <p class="font-bold text-sm text-slate-300">III. SƠ ĐỒ THIẾT KẾ & PHÂN TÍCH</p>
                                            <p class="text-slate-300 pl-4">Phần sơ đồ luồng dữ liệu DFD và thực thể ERD chi tiết mô hình hóa nghiệp vụ của hệ thống.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Purchase/Download Banner inside doc -->
                                <div class="mt-8 border-t border-slate-100 pt-6 flex flex-col items-center text-center relative z-20">
                                    <p class="text-xs font-semibold text-slate-600 mb-3">Tài liệu còn tiếp tục... Hãy tải file gốc để xem toàn bộ.</p>
                                    @if($hasAccess)
                                        <button wire:click="download" class="rounded-xl bg-blue-600 hover:bg-blue-500 text-white px-5 py-2 text-xs font-semibold shadow transition-colors">
                                            Tải file gốc (.docx)
                                        </button>
                                    @else
                                        <button wire:click="buyDocument" class="rounded-xl bg-blue-600 hover:bg-blue-500 text-white px-5 py-2 text-xs font-semibold shadow transition-colors">
                                            Mua để tải xuống (.docx)
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif($doc->file_type === 'zip')
                    <!-- ZIP File Explorer Simulator/Real Reader -->
                    <div x-data="{ 
                        selectedFile: @js(!empty($zipFiles) ? array_key_first($zipFiles) : 'README.md'),
                        files: @js($zipFiles)
                    }" class="rounded-2xl border border-slate-200 bg-slate-900 text-slate-300 overflow-hidden flex flex-col h-[500px] font-mono text-xs relative">
                        <!-- Watermark Overlay (translucent, repeating text) -->
                        <div class="absolute inset-0 pointer-events-none z-10 flex flex-col justify-around items-center opacity-[0.03] select-none uppercase font-bold text-3xl tracking-widest text-white rotate-[-30deg]">
                            <div>IT-Learning Source Code</div>
                            <div>Bản xem trước dự án</div>
                            <div>IT-Learning Source Code</div>
                        </div>

                        <!-- Editor Header -->
                        <div class="bg-slate-950 px-4 py-2.5 border-b border-slate-800 flex items-center justify-between shrink-0 font-sans">
                            <div class="flex items-center gap-2">
                                <span class="h-3 w-3 rounded-full bg-rose-500"></span>
                                <span class="h-3 w-3 rounded-full bg-amber-500"></span>
                                <span class="h-3 w-3 rounded-full bg-emerald-500"></span>
                                <span class="text-slate-400 font-semibold ml-2 font-mono">source_code.zip (Trình duyệt tệp tin)</span>
                            </div>
                            <span class="text-slate-500 text-[10px]">Nhấp để xem nội dung tệp</span>
                        </div>

                        <!-- Main Split View -->
                        <div class="flex-1 flex overflow-hidden">
                            <!-- Left Sidebar (Explorer) -->
                            <div class="w-64 border-r border-slate-800 bg-slate-950 overflow-y-auto p-3 space-y-1 select-none shrink-0 font-sans">
                                <div class="text-[10px] uppercase font-bold text-slate-500 tracking-wider mb-2">Thư mục nguồn</div>
                                <div class="space-y-1 text-slate-400 font-mono">
                                    <!-- File Tree -->
                                    <template x-for="(meta, path) in files">
                                        <div @click="selectedFile = path"
                                             :class="selectedFile === path ? 'bg-blue-600 text-white font-bold' : 'hover:bg-slate-800 hover:text-white'"
                                             class="flex items-center gap-2 px-2 py-1.5 rounded cursor-pointer transition">
                                            <svg class="w-3.5 h-3.5 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                            <span class="truncate" x-text="path"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Right Code Content -->
                            <div class="flex-1 flex flex-col overflow-hidden bg-slate-900">
                                <!-- Tab header -->
                                <div class="bg-slate-950 px-4 py-2 border-b border-slate-800 text-[10px] text-slate-400 flex items-center gap-2 shrink-0">
                                    <svg class="w-3 h-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    <span x-text="selectedFile"></span>
                                </div>
                                <!-- Code Editor Text Area -->
                                <div class="flex-1 overflow-auto p-4 leading-relaxed whitespace-pre font-mono text-emerald-400 selection:bg-slate-700 select-all">
                                    <code x-text="files[selectedFile] ? files[selectedFile].content : ''"></code>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Download / Purchase Prompt -->
                        <div class="bg-slate-950 px-6 py-4 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 shrink-0 font-sans">
                            <span class="text-xs text-slate-400">Đây là bản xem trước cấu trúc source code của tệp ZIP.</span>
                            @if($hasAccess)
                                <button wire:click="download" class="rounded-xl bg-slate-800 text-white border border-slate-700 hover:bg-slate-700 px-4 py-2 text-xs font-semibold transition-all">
                                    Tải xuống toàn bộ code (.zip)
                                </button>
                            @else
                                <button wire:click="buyDocument" class="rounded-xl bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 text-xs font-semibold shadow transition-all">
                                    Mua code để tải xuống
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Reviews and Comments -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 md:p-8 shadow-sm space-y-6">
                <h2 class="text-lg font-bold text-slate-900">Nhận xét từ người học</h2>

                @auth
                    @if($hasDownloaded && !$hasReviewed)
                        <form wire:submit.prevent="submitReview" class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-semibold text-slate-700">Đánh giá của bạn:</span>
                                <div class="flex items-center gap-1" x-data="{ r: @entangle('rating').live }">
                                    <template x-for="i in 5">
                                        <button type="button" @click="r = i" class="text-2xl focus:outline-none transition-transform active:scale-95">
                                            <span :class="i <= r ? 'text-amber-500' : 'text-slate-300'">★</span>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <div>
                                <textarea wire:model="reviewContent" 
                                          rows="3" 
                                          placeholder="Nhận xét của bạn về chất lượng tài liệu này (tối thiểu 20 ký tự, tối đa 500 ký tự)..."
                                          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-300"></textarea>
                                @error('reviewContent')
                                    <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="rounded-xl bg-slate-900 hover:bg-slate-800 text-white px-5 py-2 text-xs font-semibold shadow-md transition-colors">
                                    Gửi nhận xét
                                </button>
                            </div>
                        </form>
                    @endif
                @endauth

                <!-- Reviews list -->
                <div class="space-y-4">
                    @forelse($doc->reviews->where('status', 'visible') as $rev)
                        <div class="p-4 rounded-2xl border border-slate-100 bg-white flex gap-3">
                            <div class="h-9 w-9 shrink-0 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs uppercase">
                                {{ substr($rev->user?->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="flex-1 space-y-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-bold text-slate-900">{{ $rev->user?->name ?? 'Người dùng' }}</h4>
                                    <span class="text-xs text-slate-400">{{ $rev->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex items-center gap-0.5 text-xs text-amber-500">
                                    @for($i=1; $i<=5; $i++)
                                        <span>{{ $i <= $rev->rating ? '★' : '☆' }}</span>
                                    @endfor
                                </div>
                                <p class="text-sm text-slate-600 leading-relaxed">{{ $rev->review }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-slate-400 text-sm">
                            Chưa có nhận xét nào cho tài liệu này.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Action Column (Right - 30%) -->
        <div class="space-y-6 lg:col-span-1">
            <!-- Download / Price Card -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest block">Giá tài nguyên</span>
                    <div class="mt-2 flex items-baseline gap-2">
                        @if($doc->product)
                            <span class="text-3xl font-bold text-blue-600">{{ number_format($doc->product->price) }}đ</span>
                        @else
                            <span class="text-3xl font-bold text-emerald-600">Miễn phí</span>
                        @endif
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-6 space-y-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500 font-medium">Định dạng file:</span>
                        <span class="text-slate-900 font-semibold uppercase">{{ $doc->file_type }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500 font-medium">Dung lượng:</span>
                        <span class="text-slate-900 font-semibold">{{ number_format($doc->file_size / 1024 / 1024, 2) }} MB</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500 font-medium">Ngày đăng:</span>
                        <span class="text-slate-900 font-semibold">{{ $doc->published_at ? $doc->published_at->format('d/m/Y') : ($doc->created_at ? $doc->created_at->format('d/m/Y') : 'N/A') }}</span>
                    </div>
                    @if(!$doc->is_downloadable)
                        <div class="flex items-center gap-2 rounded-xl bg-amber-50 border border-amber-200 px-3 py-2 text-xs font-semibold text-amber-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Tài liệu này chỉ hỗ trợ xem online
                        </div>
                    @endif
                </div>

                <div class="space-y-3">
                    @guest
                        @if($doc->product)
                            <button wire:click="buyDocument" class="w-full rounded-2xl bg-blue-600 hover:bg-blue-700 text-white py-4 text-sm font-semibold shadow-lg shadow-blue-600/20 hover:shadow-blue-700/30 flex items-center justify-center gap-2 transition-all duration-200 active:scale-[0.98]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 7M7 13l-2 5m5-5v5m4-5v5m4-5l2 5"/></svg>
                                Mua tài nguyên ngay
                            </button>
                        @else
                            <button wire:click="download" class="w-full rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white py-4 text-sm font-semibold shadow-lg shadow-emerald-600/20 hover:shadow-emerald-700/30 flex items-center justify-center gap-2 transition-all duration-200 active:scale-[0.98]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Đăng nhập để tải xuống
                            </button>
                        @endif
                    @else
                        @if(!$doc->product)
                            <button wire:click="download" class="w-full rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white py-4 text-sm font-semibold shadow-lg shadow-emerald-600/20 hover:shadow-emerald-700/30 flex items-center justify-center gap-2 transition-all duration-200 active:scale-[0.98]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Tải xuống
                            </button>
                        @elseif($isVip)
                            <div class="space-y-3">
                                <button wire:click="download" class="w-full rounded-2xl bg-amber-600 hover:bg-amber-700 text-white py-4 text-sm font-semibold shadow-lg shadow-amber-600/20 hover:shadow-amber-700/30 flex items-center justify-center gap-2 transition-all duration-200 active:scale-[0.98]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                    Tải xuống với gói VIP
                                </button>
                                <button wire:click="buyDocument" class="w-full rounded-2xl bg-blue-600 hover:bg-blue-700 text-white py-4 text-sm font-semibold shadow-lg shadow-blue-600/20 hover:shadow-blue-700/30 flex items-center justify-center gap-2 transition-all duration-200 active:scale-[0.98]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 7M7 13l-2 5m5-5v5m4-5v5m4-5l2 5"/></svg>
                                    Mua tài nguyên ngay
                                </button>
                            </div>
                        @else
                            <button wire:click="buyDocument" class="w-full rounded-2xl bg-blue-600 hover:bg-blue-700 text-white py-4 text-sm font-semibold shadow-lg shadow-blue-600/20 hover:shadow-blue-700/30 flex items-center justify-center gap-2 transition-all duration-200 active:scale-[0.98]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 7M7 13l-2 5m5-5v5m4-5v5m4-5l2 5"/></svg>
                                Mua tài nguyên ngay
                            </button>
                        @endif
                    @endguest

                    <button wire:click="toggleFavorite" class="w-full rounded-2xl border-2 border-slate-200 bg-white hover:border-rose-200 hover:bg-rose-50 text-slate-700 hover:text-rose-600 py-3.5 text-sm font-semibold flex items-center justify-center gap-2 transition-all duration-200 active:scale-[0.98]">
                        @if($isBookmarked)
                            <svg class="w-4 h-4 fill-rose-500 text-rose-500" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            <span>Đã lưu tài liệu</span>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            <span>Lưu vào yêu thích</span>
                        @endif
                    </button>

                    <button wire:click="openReportModal" class="w-full rounded-2xl border-2 border-dashed border-slate-200 bg-white hover:border-red-200 hover:bg-red-50 text-slate-500 hover:text-red-600 py-3.5 text-sm font-semibold flex items-center justify-center gap-2 transition-all duration-200 active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                        Báo cáo vi phạm
                    </button>
                </div>
            </div>

            {{-- VIP Suggestion Card (for Premium documents, non-VIP users) --}}
            @auth
                @php
                    $isVip = Auth::user()->vip_expires_at && Auth::user()->vip_expires_at->isFuture();
                    $isPremium = $doc->product && $doc->product->price > 0;
                @endphp
                
                @if($isPremium && !$isVip && !$hasAccess)
                    <div class="rounded-3xl border-2 border-amber-400 bg-gradient-to-br from-amber-50 to-yellow-50 p-6 shadow-lg">
                        <div class="flex items-start gap-3 mb-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-yellow-500 flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">Nâng cấp VIP Premium</h3>
                                <p class="text-sm text-gray-600">Tiết kiệm hơn với gói VIP</p>
                            </div>
                        </div>
                        
                        <ul class="space-y-2 mb-4">
                            <li class="flex items-start gap-2 text-sm text-gray-700">
                                <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Gói <strong>1 tháng:</strong> Tải <strong>5 tài liệu</strong> Premium</span>
                            </li>
                            <li class="flex items-start gap-2 text-sm text-gray-700">
                                <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Gói <strong>3 tháng:</strong> Tải <strong>20 tài liệu</strong> - Tiết kiệm <strong>40%</strong></span>
                            </li>
                            <li class="flex items-start gap-2 text-sm text-gray-700">
                                <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Gói <strong>6 tháng:</strong> Tải <strong>50 tài liệu</strong> - Tiết kiệm <strong>60%</strong></span>
                            </li>
                        </ul>
                        
                        <a href="{{ route('student.subscription') }}" 
                           class="block w-full text-center rounded-2xl bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-white py-3 text-sm font-bold shadow-lg hover:shadow-xl transition-all duration-300">
                            ⚡ Xem các gói VIP
                        </a>
                    </div>
                @endif
            @endauth

            <!-- Author Card -->
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-slate-800 to-slate-950 text-white flex items-center justify-center font-bold text-xl uppercase shrink-0 shadow-md">
                        {{ substr($doc->author?->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest block">Tác giả đăng tải</span>
                        <h3 class="text-base font-bold text-slate-900 truncate">{{ $doc->author?->name ?? 'Giảng viên/CTV' }}</h3>
                    </div>
                </div>
            </div>

            <!-- Related Documents Card -->
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400">Tài liệu liên quan</h3>
                <div class="space-y-3">
                    @php
                        $relPlaceholders = [
                            'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=200&q=80',
                            'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=200&q=80',
                            'https://images.unsplash.com/photo-1542831371-29b0f74f9713?auto=format&fit=crop&w=200&q=80',
                            'https://images.unsplash.com/photo-1504639725590-34d0984388bd?auto=format&fit=crop&w=200&q=80',
                            'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=200&q=80',
                            'https://images.unsplash.com/photo-1607799279861-4dd421887fb3?auto=format&fit=crop&w=200&q=80',
                        ];
                    @endphp
                    @forelse($relatedDocuments as $rel)
                        @php
                            $relThumb = $rel->thumbnail 
                                ? asset('storage/' . $rel->thumbnail) 
                                : $relPlaceholders[$rel->id % count($relPlaceholders)];
                        @endphp
                        <a href="{{ route('documents.show', $rel->id) }}" class="flex gap-3 p-2 rounded-2xl hover:bg-slate-50 transition-all duration-200 group">
                            <!-- Thumbnail -->
                            <div class="h-16 w-20 shrink-0 rounded-xl overflow-hidden bg-slate-100 shadow-sm">
                                <img src="{{ $relThumb }}" alt="{{ $rel->title }}" class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-300" loading="lazy" />
                            </div>
                            <!-- Info -->
                            <div class="flex-1 min-w-0 flex flex-col justify-center">
                                <h4 class="text-xs font-bold text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-2 leading-snug">{{ $rel->title }}</h4>
                                <div class="flex items-center gap-3 mt-1.5">
                                    <span class="inline-flex items-center gap-1 text-[10px] text-slate-400 font-medium">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        {{ number_format($rel->download_count) }}
                                    </span>
                                    <span class="inline-block rounded px-1.5 py-0.5 text-[9px] font-bold uppercase bg-slate-100 text-slate-500">{{ $rel->file_type }}</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-xs text-slate-400 py-4 text-center">Không có tài liệu liên quan nào khác.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Checkout Modal --}}
    @livewire('payment-checkout-modal')

    <!-- Report Abuse Modal -->
    <div x-data="{ show: $wire.entangle('showReportModal') }"
         x-show="show" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="show = false"></div>

        <!-- Modal Content Container -->
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl border border-slate-200 transition-all transform scale-100 space-y-6 z-10">
                <!-- Header -->
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Báo cáo tài liệu vi phạm
                    </h3>
                    <button @click="show = false" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="submitReport" class="space-y-4">
                    <!-- Reason Select -->
                    <div class="space-y-1.5">
                        <label for="report-reason" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Lý do báo cáo <span class="text-red-500">*</span></label>
                        <select id="report-reason" wire:model="reportReason" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-slate-400 focus:outline-none">
                            <option value="Bản quyền">Vi phạm bản quyền / Sở hữu trí tuệ</option>
                            <option value="Nội dung sai">Nội dung sai lệch, không chính xác</option>
                            <option value="File hỏng">Tệp tin lỗi, không tải được hoặc chứa mã độc</option>
                            <option value="Spam">Spam, quảng cáo không phù hợp</option>
                            <option value="Khác">Lý do khác</option>
                        </select>
                        @error('reportReason') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Details Textarea -->
                    <div class="space-y-1.5">
                        <label for="report-details" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Chi tiết bổ sung (tùy chọn)</label>
                        <textarea id="report-details"
                                  wire:model="reportDetails" 
                                  rows="4" 
                                  placeholder="Mô tả cụ thể lý do hoặc bằng chứng vi phạm..."
                                  class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-slate-400 focus:outline-none"></textarea>
                        @error('reportDetails') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Footer buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="show = false" class="rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 px-5 py-2.5 text-xs font-semibold transition">
                            Hủy bỏ
                        </button>
                        <button type="submit" class="rounded-xl bg-red-600 hover:bg-red-500 text-white px-5 py-2.5 text-xs font-semibold shadow-md transition">
                            Gửi báo cáo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
