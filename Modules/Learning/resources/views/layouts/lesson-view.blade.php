@extends('learning::layouts.learning-layout')

@section('breadcrumb')
    <a href="{{ route('learning.roadmaps.index') }}" class="text-slate-300 hover:text-white transition-colors flex items-center gap-1">
        Lộ trình học tập
    </a>
    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
    <a href="{{ route('learning.roadmaps.show', $roadmap->id) }}" class="text-slate-300 hover:text-white transition-colors truncate max-w-[200px] md:max-w-xs">
        {{ $roadmap->title }}
    </a>
@endsection

@section('content')
<div class="flex flex-col h-[calc(100vh-52px)] bg-gray-50 text-gray-900 font-sans antialiased overflow-hidden" x-data="lessonController()">
    
    <div class="flex flex-1 overflow-hidden relative">
        
        {{-- SIDEBAR MỤC LỤC TRÊN BÊN TRÁI (ĐÃ KHÔI PHỤC HOÀN CHỈNH) --}}
        {{-- Overlay khi sidebar mở trên mobile --}}
        <div x-show="sidebarOpen" 
             @click="toggleSidebar" 
             x-transition:enter="transition-opacity ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-opacity-50 z-30 md:hidden"></div>
        
        <aside x-show="sidebarOpen"
               x-transition:enter="transition-transform ease-out duration-200"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition-transform ease-in duration-150"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="w-80 bg-white border-r border-gray-200 overflow-y-auto flex-shrink-0 shadow-sm z-40 fixed md:relative h-full">
            <div class="p-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between sticky top-0 z-10">
                <h3 class="font-bold text-gray-800 text-xs tracking-wider uppercase">Nội dung lộ trình</h3>
                <span class="text-xs bg-cyan-100 text-cyan-800 font-bold px-2 py-0.5 rounded-full">
                    {{ $roadmap->lessons->count() }} Bài học
                </span>
            </div>
            
            <div class="divide-y divide-gray-200">
                {{-- Duyệt các Chương/Section --}}
                @foreach($roadmap->sections as $section)
                    <div class="bg-gray-50/70">
                        <div class="px-4 py-2.5 font-bold text-xs text-gray-500 uppercase tracking-wider bg-gray-100/60 border-b border-gray-200/50">
                            {{ $section->title }}
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach($section->lessons as $lesson)
                                <a href="{{ route('learning.roadmaps.learn', [$roadmap->id, $lesson->id]) }}" 
                                   class="flex items-center justify-between px-4 py-3 text-sm transition-all {{ $currentLesson->id == $lesson->id ? 'bg-cyan-50 text-cyan-700 font-bold border-l-4 border-cyan-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                    <div class="flex items-center gap-2 truncate">
                                        @if(($lesson->lesson_type ?? 'text') == 'video')
                                            <span class="text-xs">▶️</span>
                                        @else
                                            <span class="text-xs">📄</span>
                                        @endif
                                        <span class="truncate">{{ $lesson->title }}</span>
                                    </div>
                                    @if(in_array($lesson->id, is_array($progressList) ? $progressList : $progressList->toArray()))
                                        <span class="text-green-600 font-bold text-xs">✓</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                {{-- Duyệt bài học không nằm trong chương nào --}}
                @if($roadmap->lessons->whereNull('section_id')->count() > 0)
                    <div>
                        <div class="px-4 py-2.5 font-bold text-xs text-gray-500 uppercase tracking-wider bg-gray-100/60 border-b border-gray-200/50">
                            Bài học bổ sung
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach($roadmap->lessons->whereNull('section_id') as $lesson)
                                <a href="{{ route('learning.roadmaps.learn', [$roadmap->id, $lesson->id]) }}" 
                                   class="flex items-center justify-between px-4 py-3 text-sm transition-all {{ $currentLesson->id == $lesson->id ? 'bg-cyan-50 text-cyan-700 font-bold border-l-4 border-cyan-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                    <div class="flex items-center gap-2 truncate">
                                        @if(($lesson->lesson_type ?? 'text') == 'video')
                                            <span class="text-xs">▶️</span>
                                        @else
                                            <span class="text-xs">📄</span>
                                        @endif
                                        <span class="truncate">{{ $lesson->title }}</span>
                                    </div>
                                    @if(in_array($lesson->id, is_array($progressList) ? $progressList : $progressList->toArray()))
                                        <span class="text-green-600 font-bold text-xs">✓</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </aside>

        {{-- NÚT TOGGLE SIDEBAR - FIXED BUTTON --}}
        <button @click="toggleSidebar" 
                class="fixed top-20 left-4 z-50 bg-white border-2 border-gray-300 rounded-xl p-3 shadow-lg hover:bg-gray-50 transition-all">
            <svg x-show="!sidebarOpen" class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg x-show="sidebarOpen" class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        {{-- MAIN CONTENT KHÔNG GIAN HỌC --}}
        <main class="flex-1 flex flex-col overflow-y-auto bg-gray-50/50 px-4 md:px-8 pb-32 scroll-smooth" id="main-scroll-area" @scroll="checkScroll">
            
            <div class="max-w-5xl mx-auto w-full">
                <div class="bg-white border border-gray-200 rounded-2xl p-5 md:p-7 mb-6 shadow-sm">
                    <span class="text-[10px] font-black uppercase tracking-widest text-cyan-800 bg-cyan-100 px-2.5 py-1 rounded-md border border-cyan-200 mb-3 inline-block">
                        Bài học dạng: {{ strtoupper($currentLesson->lesson_type ?? 'Text') }}
                    </span>
                    <h1 class="text-xl md:text-2xl font-black text-gray-900 tracking-tight mb-4">
                        {{ $currentLesson->title }}
                    </h1>

                    {{-- TÀI NGUYÊN BÀI HỌC - HIỂN THỊ TẤT CẢ TÀI NGUYÊN CÓ SẴN --}}
                    
                    {{-- 1. BANNER NỘI DUNG TEXT (Content) --}}
                    @if(!empty($currentLesson->content))
                        <div class="mt-4 bg-gradient-to-br from-cyan-50 to-blue-50 border border-cyan-200 rounded-2xl p-6 mb-6 shadow-sm">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 bg-cyan-600 rounded-xl flex items-center justify-center text-2xl">
                                    📝
                                </div>
                                <div>
                                    <h3 class="font-bold text-cyan-900 text-lg">Nội dung bài học</h3>
                                    <p class="text-xs text-cyan-700">Tài liệu học tập chi tiết</p>
                                </div>
                            </div>
                            <div class="bg-white rounded-xl p-6 shadow-sm border border-cyan-100">
                                <div class="ql-editor prose max-w-none">
                                    {!! $currentLesson->content !!}
                                </div>
                            </div>
                            
                            {{-- ĐIỂM NEO THEO DÕI CUỘN CHỈ CHO CONTENT --}}
                            <div id="scroll-anchor" class="h-10 mt-4 flex items-center justify-center text-cyan-600 text-xs border-t border-dashed border-cyan-300 pt-4">
                                <span x-show="!canComplete" class="flex items-center gap-1 animate-pulse">⬇️ Đang đọc tài liệu... Vui lòng cuộn xuống hết trang</span>
                                <span x-show="canComplete" class="text-green-600 font-bold flex items-center gap-1">✓ Hệ thống xác nhận đã hoàn thành đọc tài liệu!</span>
                            </div>
                        </div>
                    @endif

                    {{-- 2. BANNER VIDEO --}}
                    @if(!empty($currentLesson->video_url))
                        <div class="mt-4 bg-white border border-gray-200 rounded-2xl p-6 mb-6 shadow-sm">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 bg-red-600 rounded-xl flex items-center justify-center text-2xl">
                                    ▶️
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 text-lg">Video bài giảng</h3>
                                    <p class="text-xs text-gray-600">Xem video để hiểu rõ hơn</p>
                                </div>
                            </div>
                            @php
                                // Tự động chuyển đổi link youtube thường thành link embed
                                $embedUrl = $currentLesson->video_url;
                                if (str_contains($embedUrl, 'youtube.com/watch?v=')) {
                                    $embedUrl = str_replace('watch?v=', 'embed/', $embedUrl);
                                    if (str_contains($embedUrl, '&')) { $embedUrl = explode('&', $embedUrl)[0]; }
                                } elseif (str_contains($embedUrl, 'youtu.be/')) {
                                    $embedUrl = str_replace('youtu.be/', 'youtube.com/embed/', $embedUrl);
                                }
                            @endphp
                            <div class="aspect-video w-full bg-black rounded-xl overflow-hidden shadow-md border border-gray-300 relative">
                                <iframe src="{{ $embedUrl }}" 
                                        class="absolute top-0 left-0 w-full h-full border-0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                        allowfullscreen>
                                </iframe>
                            </div>
                            <div class="mt-4 text-sm font-medium text-green-700 bg-green-50 p-3 rounded-xl border border-green-200">
                                💡 Hệ thống nhận diện bài học Video. Bạn có thể nhấn nút "Hoàn thành" ngay lập tức.
                            </div>
                        </div>
                    @endif
                </div>

                {{-- 3. BANNER BÀI KIỂM TRA (EXAM) --}}
                @if($currentLesson->exam_id && $currentLesson->exam)
                    <div class="bg-white border border-blue-100 rounded-2xl overflow-hidden shadow-lg mb-6">
                        
                        {{-- Header Exam --}}
                        <div class="bg-gradient-to-r from-blue-700 to-blue-500 p-6 text-white">
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="text-blue-100 text-sm mb-1">Bài kiểm tra</div>
                                    <h3 class="text-2xl font-bold">{{ $currentLesson->exam->title }}</h3>
                                    <p class="text-blue-100 text-sm mt-2">{{ $currentLesson->exam->description }}</p>
                                </div>
                                <div class="hidden md:block text-6xl opacity-20">📝</div>
                            </div>
                        </div>

                        {{-- Thông tin Exam --}}
                        <div class="p-6">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <div class="text-slate-500 text-xs mb-1">Thời gian</div>
                                    <div class="font-bold text-slate-900 text-lg">{{ $currentLesson->exam->duration_minutes }} phút</div>
                                </div>

                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <div class="text-slate-500 text-xs mb-1">Số câu hỏi</div>
                                    <div class="font-bold text-slate-900 text-lg">{{ $currentLesson->exam->questions->count() }}</div>
                                </div>

                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <div class="text-slate-500 text-xs mb-1">Điểm đạt</div>
                                    <div class="font-bold text-slate-900 text-lg">{{ rtrim(rtrim($currentLesson->exam->pass_percent, '0'), '.') }}%</div>
                                </div>

                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <div class="text-slate-500 text-xs mb-1">Hình thức</div>
                                    <div class="font-bold text-slate-900 text-lg">{{ ucfirst($currentLesson->exam->type) }}</div>
                                </div>
                            </div>

                            {{-- Nút bắt đầu làm bài --}}
                            <form method="POST"
                                x-ref="examForm" 
                                  action="{{ route('exam.attempt.start', $currentLesson->exam->slug) }}"
                                  x-data="{confirmModal:false,submitting:false}"
                                  @keydown.escape.window="confirmModal = false">
                                @csrf

                                <button type="button"
                                        @click="confirmModal = true"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-lg px-8 py-4 rounded-xl shadow-lg transition-all flex items-center justify-center gap-3">
                                    <span>Bắt đầu làm bài</span>
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </button>

                                {{-- Modal xác nhận --}}
                                <div x-show="confirmModal"
                                     x-cloak
                                     class="fixed inset-0 z-50"
                                     style="display:none;">
                                    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
                                         x-transition.opacity
                                         @click="confirmModal = false"></div>

                                    <div class="relative flex min-h-screen items-center justify-center p-4">
                                        <div @click.stop
                                             x-transition
                                             class="w-full max-w-lg bg-white rounded-3xl overflow-hidden shadow-2xl">
                                            
                                            <div class="p-8">
                                                <div class="w-16 h-16 rounded-2xl bg-blue-100 flex items-center justify-center mb-5">
                                                    <svg class="w-8 h-8 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </div>

                                                <h3 class="text-xl font-bold text-slate-900 mb-2">
                                                    Xác nhận làm bài kiểm tra
                                                </h3>

                                                <p class="text-slate-600 mb-4">
                                                    Bạn có chắc chắn muốn bắt đầu bài kiểm tra này? Bài kiểm tra có thời gian giới hạn {{ $currentLesson->exam->duration_minutes }} phút.
                                                </p>

                                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-800">
                                                    💡 <strong>Lưu ý:</strong> Hoàn thành tối thiểu {{ rtrim(rtrim($currentLesson->exam->pass_percent, '0'), '.') }}% số điểm để vượt qua bài thi.
                                                </div>
                                            </div>

                                            <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3">
                                                <button type="button"
                                                        @click="confirmModal = false"
                                                        class="px-6 py-2.5 border border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition-colors">
                                                    Hủy
                                                </button>

    <button
                                                        type="button"
                                                        class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
                                                        x-bind:disabled="submitting"
                                                        x-on:click="submitting = true; $refs.examForm.submit()"
                                                    >
                                                        {{-- Spinner icon - chỉ hiện khi đang submit --}}
                                                        <svg x-show="submitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                        {{-- Text động: "Đồng ý" hoặc "Đang xử lý..." --}}
                                                        <span x-text="submitting ? 'Đang xử lý...' : 'Đồng ý'"></span>
                                                    </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </form>

                            {{-- Lưu ý --}}
                            <div class="mt-4 bg-blue-50 border border-blue-200 rounded-xl p-4 flex gap-3">
                                <svg class="w-6 h-6 text-blue-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <div class="font-semibold text-blue-900 text-sm">Lưu ý quan trọng</div>
                                    <div class="text-sm text-blue-700 mt-1">
                                        • Bài kiểm tra có thời gian giới hạn {{ $currentLesson->exam->duration_minutes }} phút<br>
                                        • Cần đạt tối thiểu {{ rtrim(rtrim($currentLesson->exam->pass_percent, '0'), '.') }}% để hoàn thành<br>
                                        • Hãy đọc kỹ câu hỏi trước khi trả lời
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- 4. BANNER TÀI LIỆU (DOCUMENT) - REFACTORED --}}
                @if($currentLesson->document_id && $currentLesson->document)
                @php
                    $doc = $currentLesson->document;
                    $fileType = strtolower($doc->file_type ?? '');
                    $documentId = $doc->id;
                    //KIETBEVE
                    //File  Hiện tại dc đọc từ file URL = $doc->file_original_url với $doc thuộc bẳng document
                    $fileUrl = $doc->file_original_url ?? '';
                    
                    // Fix: Loại bỏ bucket name khỏi URL nếu có
                    // VD: https://pub-xxx.r2.dev/it-learning-documents/path -> https://pub-xxx.r2.dev/path
                    $bucket = config('filesystems.disks.r2.bucket');
                    if ($bucket && str_contains($fileUrl, "/{$bucket}/")) {
                        $fileUrl = str_replace("/{$bucket}/", '/', $fileUrl);
                    }
                    
                    $zipFiles = [];
                    if ($fileType === 'zip') {
                        $activeVer = $doc->currentVersion ?? $doc->latestVersion;
                        if ($activeVer) {
                            $zipFiles = \Illuminate\Support\Facades\Cache::rememberForever('zip_structure_lesson_' . $activeVer->id, function() use ($doc, $activeVer) {
                                $zipPath = ($doc->watermark_status === 'success' && $activeVer->file_watermarked_path)
                                    ? $activeVer->file_watermarked_path
                                    : $activeVer->file_original_path;
                                
                                $localZip = null;
                                $zipFiles = [];
                                
                                $localPath = storage_path('app/public/' . $zipPath);
                                if (file_exists($localPath)) {
                                    $localZip = $localPath;
                                }
                                
                                if (!$localZip && $zipPath && \Illuminate\Support\Facades\Storage::disk('r2')->exists($zipPath)) {
                                    $tempZip = storage_path('app/temp/' . uniqid('zip_') . '.zip');
                                    $dir = dirname($tempZip);
                                    if (!is_dir($dir)) mkdir($dir, 0755, true);
                                    file_put_contents($tempZip, \Illuminate\Support\Facades\Storage::disk('r2')->get($zipPath));
                                    $localZip = $tempZip;
                                }
                                
                                if ($localZip) {
                                    $zip = new ZipArchive();
                                    if ($zip->open($localZip) === TRUE) {
                                        for ($i = 0; $i < $zip->numFiles; $i++) {
                                            $filename = $zip->getNameIndex($i);
                                            if (substr($filename, -1) !== '/' && !str_contains($filename, '__MACOSX')) {
                                                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                                                $allowedExts = ['php','js','jsx','ts','tsx','py','java','css','scss','html','json','xml','md','sql','yml','yaml','txt'];
                                                if (in_array($ext, $allowedExts)) {
                                                    $content = $zip->getFromIndex($i);
                                                    if ($content !== false && mb_strlen($content) < 500000) {
                                                        $zipFiles[$filename] = ['content' => $content];
                                                    }
                                                }
                                            }
                                        }
                                        $zip->close();
                                    }
                                    if (isset($tempZip) && file_exists($tempZip)) @unlink($tempZip);
                                }
                                
                                return $zipFiles;
                            });
                        }
                    }
                @endphp

                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-lg mb-6">
                        
                        <div class="bg-gradient-to-r from-orange-50 to-amber-50 border-b border-orange-200 p-6">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-orange-600 rounded-xl flex items-center justify-center text-2xl">
                                        @if($fileType === 'zip') 📦 @else 📄 @endif
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-lg">{{ $doc->title }}</h3>
                                        <p class="text-xs text-gray-600 mt-1">
                                            {{ strtoupper($fileType) }} • {{ number_format($doc->file_size / 1024 / 1024, 2) }} MB
                                        </p>
                                    </div>
                                </div>
                                @if($doc->is_downloadable && !empty($fileUrl))
                                    <a href="{{ $fileUrl }}" 
                                       download 
                                       class="bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold px-4 py-2 rounded-lg flex items-center gap-2 transition-colors whitespace-nowrap shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Tải xuống
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="p-6">
                            @if($fileType === 'zip' && count($zipFiles) > 0)
                                <div x-data="{ 
                                    isFullscreen: false,
                                    selectedFile: null, 
                                    showTree: true,
                                    selectFile(path) {
                                        this.selectedFile = path;
                                        this.$nextTick(() => {
                                            let container = document.getElementById('lesson-code-preview-{{ $documentId }}');
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
                                    
                                    <div class="rounded-2xl border border-blue-200 bg-gradient-to-br from-blue-50 to-cyan-50 p-5 flex items-center justify-between gap-4 hover:shadow-md transition-shadow">
                                        <div class="flex items-center gap-3">
                                            <div class="p-3 bg-blue-600 text-white rounded-xl shrink-0">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-900 text-sm">Xem cấu trúc source code</h4>
                                                <p class="text-xs text-gray-600 mt-0.5">{{ count($zipFiles) }} files trong ZIP</p>
                                            </div>
                                        </div>
                                        <button @click="isFullscreen = true" 
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 text-xs font-bold rounded-xl shadow-sm transition-colors whitespace-nowrap">
                                            Xem source code
                                        </button>
                                    </div>

                                    <div x-show="isFullscreen" 
                                         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40" 
                                         style="display: none;" 
                                         @click="isFullscreen = false"></div>

                                    <div x-show="isFullscreen" 
                                         class="fixed inset-4 md:inset-8 z-50 rounded-2xl bg-white border border-slate-200 p-6 flex flex-col shadow-2xl"
                                         style="display: none;"
                                         x-transition>
                                         
                                        <div class="flex items-center justify-between mb-4 shrink-0">
                                            <div class="flex items-center gap-3">
                                                <h3 class="text-lg font-bold text-gray-900">Xem source code</h3>
                                                <span class="rounded-lg bg-blue-100 px-2.5 py-1 text-xs font-bold text-blue-700">ZIP</span>
                                            </div>
                                            <button @click="isFullscreen = false" 
                                                    class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="flex-1 min-h-0">
                                            <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism.min.css" rel="stylesheet" />
                                            
                                            <div class="rounded-xl border border-gray-200 bg-gray-50 overflow-hidden flex h-full">
                                                <div x-show="showTree" class="w-64 border-r border-gray-200 bg-white overflow-y-auto shrink-0">
                                                    <div class="p-4 border-b border-gray-200 sticky top-0 bg-white z-10">
                                                        <h4 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                                            </svg>
                                                            Cấu trúc project
                                                        </h4>
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
                                                            
                                                            function renderLessonTree($tree, $depth = 0) {
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
                                                                            default => 'text-gray-500'
                                                                        };
                                                                        echo '<div @click="selectFile('.htmlspecialchars(json_encode($item['path'])).')" 
                                                                              class="flex items-center gap-2 px-3 py-1.5 hover:bg-blue-50 cursor-pointer rounded-lg text-xs transition-all"
                                                                              :class="selectedFile === '.htmlspecialchars(json_encode($item['path'])).' ? \'bg-blue-100 text-blue-800 font-bold\' : \'text-gray-700\'"
                                                                              style="margin-left: '.($depth * 12).'px">
                                                                              <svg class="w-3.5 h-3.5 '.$iconClass.'" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                                                                              <span class="truncate">'.htmlspecialchars($name).'</span>
                                                                        </div>';
                                                                    } else {
                                                                        echo '<div class="mt-1">';
                                                                        echo '<div class="flex items-center gap-1.5 px-2 py-1 text-xs font-bold text-gray-700" style="margin-left: '.($depth * 12).'px">
                                                                              <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                                                              '.htmlspecialchars($name).'
                                                                        </div>';
                                                                        renderLessonTree($item, $depth + 1);
                                                                        echo '</div>';
                                                                    }
                                                                }
                                                            }
                                                            renderLessonTree($tree);
                                                        @endphp
                                                    </div>
                                                </div>
                                                
                                                <div class="flex-1 bg-white overflow-hidden flex flex-col" id="lesson-code-preview-{{ $documentId }}">
                                                    <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 flex items-center justify-between shrink-0">
                                                        <div class="flex items-center gap-2">
                                                            <button @click="showTree = !showTree" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                                                </svg>
                                                            </button>
                                                            <div x-show="!selectedFile" class="text-gray-500 text-xs">Chọn file để xem code</div>
                                                            <div x-show="selectedFile" class="font-mono text-xs text-gray-800 font-bold truncate max-w-md" x-text="selectedFile"></div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-1 overflow-y-auto overflow-x-auto">
                                                        <div x-show="!selectedFile" class="h-full flex items-center justify-center bg-gray-50">
                                                            <div class="text-center p-6">
                                                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                                                </svg>
                                                                <p class="text-xs font-bold text-gray-600">Chưa chọn file</p>
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
                                                                <pre class="!m-0 !rounded-none" style="font-size: 13px; line-height: 1.6; padding: 1.5rem; background: #fafafa;"><code class="language-{{ $lang }}" style="font-size: 13px; line-height: 1.6;">{{ $fileData['content'] }}</code></pre>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($fileType === 'pdf' && !empty($fileUrl))
                                <div class="space-y-4">
                                    <div class="flex items-center gap-3 pb-4 border-b border-gray-200">
                                        <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900 text-sm">Xem trước tài liệu PDF</h4>
                                            <p class="text-xs text-gray-500 mt-0.5">Hỗ trợ đọc trực tuyến trên trình duyệt</p>
                                        </div>
                                    </div>
                                    
                                    <div class="rounded-xl overflow-hidden border border-gray-300 shadow-inner bg-gray-100" style="height: 750px;">
                                        <iframe src="{{ $fileUrl }}#toolbar=0" 
                                                class="w-full h-full border-0"
                                                id="pdf-frame-{{ $documentId }}">
                                        </iframe>
                                    </div>

                                    <div class="text-xs text-gray-600 bg-blue-50 border border-blue-200 p-3 rounded-lg">
                                        💡 <strong>Mẹo:</strong> Nếu tài liệu không hiển thị, hãy thử tải xuống để xem đầy đủ.
                                    </div>
                                </div>

                            @else
                                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-8 text-center">
                                    <div class="text-5xl mb-4">📄</div>
                                    <h4 class="font-bold text-yellow-800 text-lg mb-2">Tài liệu không hỗ trợ xem trực tuyến</h4>
                                    <p class="text-yellow-700 text-sm mb-4">
                                        File {{ strtoupper($fileType) }} không thể hiển thị trên trình duyệt.<br>
                                        Vui lòng tải xuống để xem.
                                    </p>
                                    @if($doc->is_downloadable && !empty($fileUrl))
                                        <a href="{{ $fileUrl }}" 
                                           download
                                           class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white font-bold px-6 py-3 rounded-xl transition-colors shadow-md">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Tải xuống tài liệu
                                        </a>
                                    @endif
                                </div>
                            @endif
                    </div>
                @endif

                {{-- PROJECT SUBMISSION SECTION --}}
                @if($currentLesson->project_id && isset($currentLesson->project))
                    @php
                        $project = $currentLesson->project;
                        $submissionService = app(\Modules\Learning\Services\ProjectSubmissionService::class);
                        $projectSubmission = $submissionService->getUserSubmission(Auth::id(), $project->id);
                        $canSubmit = $submissionService->canUserSubmit(Auth::id(), $project->id);
                    @endphp

                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm mb-6">
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 border-b border-purple-200 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <h3 class="font-black text-lg text-purple-800 flex items-center gap-2">
                                    🚀 Nộp Project
                                </h3>
                                @if($projectSubmission)
                                    <span class="text-xs font-bold px-3 py-1 rounded-full
                                        {{ $projectSubmission->status === 'passed' ? 'bg-green-100 text-green-700 border border-green-300' : '' }}
                                        {{ $projectSubmission->status === 'failed' ? 'bg-red-100 text-red-700 border border-red-300' : '' }}
                                        {{ in_array($projectSubmission->status, ['submitted', 'resubmitted', 'in_review']) ? 'bg-yellow-100 text-yellow-700 border border-yellow-300' : '' }}">
                                        @if($projectSubmission->status === 'passed') ✓ Đã đạt
                                        @elseif($projectSubmission->status === 'failed') ✗ Cần làm lại
                                        @elseif($projectSubmission->status === 'in_review') 👁 Đang review
                                        @elseif($projectSubmission->status === 'resubmitted') 🔄 Đã nộp lại
                                        @else ⏳ Chờ đánh giá
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="p-6 space-y-5">
                            {{-- Project Information --}}
                            <div class="bg-purple-50 border border-purple-200 rounded-xl p-4">
                                <h4 class="font-bold text-purple-900 text-sm mb-2">📋 Mô tả dự án:</h4>
                                <p class="text-sm text-gray-700 leading-relaxed mb-3">{{ $project->description }}</p>
                                @if($project->starter_code_url)
                                    <a href="{{ $project->starter_code_url }}" target="_blank" 
                                       class="inline-flex items-center gap-2 text-xs font-semibold text-purple-700 hover:text-purple-900 underline underline-offset-2">
                                        📦 Starter Code GitHub
                                    </a>
                                @endif
                                @if($project->deadline_at)
                                    <p class="text-xs text-gray-500 mt-2">⏰ Hạn nộp: {{ \Carbon\Carbon::parse($project->deadline_at)->format('d/m/Y H:i') }}</p>
                                @endif
                            </div>

                            {{-- Existing Submission Status --}}
                            @if($projectSubmission)
                                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                    <h4 class="font-bold text-gray-900 text-sm mb-3">📊 Trạng thái nộp bài của bạn:</h4>
                                    <div class="grid grid-cols-2 gap-3 text-xs">
                                        <div>
                                            <span class="text-gray-500">Lần nộp:</span>
                                            <span class="font-bold text-gray-900">{{ $projectSubmission->submission_no }}/{{ $project->max_resubmissions }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Thời gian nộp:</span>
                                            <span class="font-bold text-gray-900">{{ $projectSubmission->submitted_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </div>
                                    @if($projectSubmission->reviewed_at)
                                        <div class="mt-3 pt-3 border-t border-gray-300">
                                            <p class="text-xs text-gray-500 mb-1">Đánh giá từ giảng viên:</p>
                                            <p class="text-sm text-gray-700 bg-white rounded p-2 border border-gray-200">{{ $projectSubmission->feedback ?? 'Chưa có phản hồi' }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            {{-- Submission Form --}}
                            @if($canSubmit['can_submit'])
                                <form action="{{ route('learning.roadmaps.lessons.submit-project', [$roadmap->id, $currentLesson->id]) }}" 
                                      method="POST" 
                                      enctype="multipart/form-data"
                                      class="space-y-4">
                                    @csrf

                                    <div>
                                        <label class="block text-sm font-bold text-gray-800 mb-2">GitHub Repository URL <span class="text-red-500">*</span></label>
                                        <input type="url" 
                                               name="github_url" 
                                               value="{{ old('github_url', $projectSubmission->github_url ?? '') }}"
                                               placeholder="https://github.com/username/project-name" 
                                               class="w-full border border-gray-300 rounded-xl p-3 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500"
                                               required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-800 mb-2">Live Demo URL (không bắt buộc)</label>
                                        <input type="url" 
                                               name="live_demo_url" 
                                               value="{{ old('live_demo_url', $projectSubmission->live_demo_url ?? '') }}"
                                               placeholder="https://your-project-demo.com" 
                                               class="w-full border border-gray-300 rounded-xl p-3 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-800 mb-2">File đính kèm (ZIP, PDF, PNG, JPG - Max 100MB)</label>
                                        <input type="file" 
                                               name="attachment" 
                                               accept=".zip,.pdf,.png,.jpg,.jpeg"
                                               class="w-full border border-gray-300 rounded-xl p-3 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500">
                                        @if($projectSubmission && $projectSubmission->attachment_path)
                                            <p class="text-xs text-gray-500 mt-1">
                                                📎 File hiện tại: <a href="{{ asset('storage/' . $projectSubmission->attachment_path) }}" target="_blank" class="text-purple-600 hover:underline">Xem file</a>
                                            </p>
                                        @endif
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-800 mb-2">Ghi chú cho giảng viên</label>
                                        <textarea name="note" 
                                                  rows="4" 
                                                  placeholder="Mô tả ngắn về project của bạn, những khó khăn gặp phải, hoặc những điểm bạn muốn giảng viên lưu ý..."
                                                  class="w-full border border-gray-300 rounded-xl p-3 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500">{{ old('note', $projectSubmission->note ?? '') }}</textarea>
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="submit" 
                                                class="bg-purple-600 hover:bg-purple-700 text-white font-black text-sm px-8 py-3 rounded-xl shadow-md transition-colors uppercase tracking-wider">
                                            {{ $projectSubmission ? '🔄 Nộp lại Project' : '📤 Nộp Project' }}
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="bg-yellow-50 border border-yellow-300 rounded-xl p-4 text-center">
                                    <p class="text-sm font-bold text-yellow-800">⚠️ {{ $canSubmit['reason'] }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- FORUM THẢO LUẬN (dùng Livewire component) --}}
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm p-5">
                    @livewire(\Modules\Learning\Livewire\Forum\LessonDiscussion::class, [
                        'lessonId' => $currentLesson->id,
                    ])
                </div>

                {{-- NÚT HOÀN THÀNH BÀI HỌC --}}
                <div class="mt-8 bg-gradient-to-r from-cyan-50 to-blue-50 border border-cyan-200 rounded-2xl p-6 shadow-sm">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Hoàn thành bài học này</h3>
                            <p class="text-sm text-gray-600" x-show="!canComplete">Vui lòng xem hết nội dung tài liệu để tiếp tục</p>
                            <p class="text-sm text-green-600 font-medium" x-show="canComplete">✓ Bạn đã hoàn thành xem tài liệu. Nhấn nút bên cạnh để chuyển sang bài tiếp theo!</p>
                        </div>
                        <form action="{{ route('learning.lessons.complete', [$roadmap->id, $currentLesson->id]) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    :disabled="!canComplete"
                                    :class="canComplete ? 'bg-cyan-500 hover:bg-cyan-600 text-white shadow-lg hover:shadow-xl' : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
                                    class="font-bold text-sm px-8 py-3 rounded-xl uppercase tracking-wide transition-all whitespace-nowrap">
                                <span x-show="canComplete">✓ Hoàn thành & Tiếp tục</span>
                                <span x-show="!canComplete">⏳ Chưa thể hoàn thành</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

</div>

<script>
    function lessonController(hasContent = false) {
        return {
            // Nhận giá trị được truyền từ x-data ngoài HTML vào
            hasContent: hasContent,
            canComplete: false,
            sidebarOpen: window.innerWidth >= 768,
            
            init() {
                // Nếu KHÔNG có content text editor, không cần scroll tracking
                if (!this.hasContent) {
                    this.canComplete = true;
                } else {
                    // Nếu có content, cài đặt IntersectionObserver theo dõi điểm neo
                    this.$nextTick(() => {
                        this.setupScrollObserver();
                    });
                }
            },

            setupScrollObserver() {
                const anchor = document.getElementById('scroll-anchor');
                if (!anchor) {
                    this.canComplete = true; 
                    return;
                }

                const observer = new IntersectionObserver((entries) => {
                    if (entries[0].isIntersecting) {
                        this.canComplete = true;
                        observer.disconnect(); 
                    }
                }, { threshold: 0.1 });

                observer.observe(anchor);
            },

            checkScroll(e) {
                // Chỉ check scroll nếu có content và chưa hoàn thành
                if (this.hasContent && !this.canComplete) {
                    const el = e.target;
                    if (el.scrollHeight - el.scrollTop <= el.clientHeight + 60) {
                        this.canComplete = true;
                    }
                }
            },

            toggleSidebar() {
                this.sidebarOpen = !this.sidebarOpen;
            }
        }
    }
</script>
@endsection