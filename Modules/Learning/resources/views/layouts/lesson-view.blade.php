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
             class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"></div>
        
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

                    <div class="mt-4">
                        {{-- 1. BÀI HỌC DẠNG VIDEO (NHÚNG EMBED CHUẨN - KHÔNG BỊ THANH CUỘN CHI TIẾT VIDEO) --}}
                        @if(($currentLesson->lesson_type ?? 'text') == 'video')
                            @if($currentLesson->video_url)
                                @php
                                    // Tự động chuyển đổi link youtube thường thành link embed để nhúng trực tiếp
                                    $embedUrl = $currentLesson->video_url;
                                    if (str_contains($embedUrl, 'youtube.com/watch?v=')) {
                                        $embedUrl = str_replace('watch?v=', 'embed/', $embedUrl);
                                        if (str_contains($embedUrl, '&')) { $embedUrl = explode('&', $embedUrl)[0]; }
                                    } elseif (str_contains($embedUrl, 'youtu.be/')) {
                                        $embedUrl = str_replace('youtu.be/', 'youtube.com/embed/', $embedUrl);
                                    }
                                @endphp
                                <div class="aspect-video w-full bg-black rounded-xl overflow-hidden shadow-md border border-gray-300 mb-4 relative">
                                    <iframe src="{{ $embedUrl }}" 
                                            class="absolute top-0 left-0 w-full h-full border-0" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                            allowfullscreen>
                                    </iframe>
                                </div>
                            @endif
                            <div class="text-sm font-medium text-green-700 bg-green-50 p-3 rounded-xl border border-green-200">
                                💡 Hệ thống nhận diện bài học Video. Bạn có thể nhấn nút "Hoàn thành" ngay lập tức mà không cần thực hiện thao tác cuộn trang.
                            </div>

                        {{-- 2. BÀI HỌC DẠNG TEXT (CHỈ CÓ BÀI NÀY MỚI CẦN HIỂN THỊ THANH CUỘN VÀ ĐIỂM NEO) --}}
                        @elseif(($currentLesson->lesson_type ?? 'text') == 'text')
                            <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl mb-4 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="text-xl">📖</span>
                                    <div>
                                        <h3 class="font-bold text-blue-900 text-sm">Tài liệu học tập đính kèm</h3>
                                        <p class="text-xs text-blue-700">Vui lòng cuộn xem hết toàn bộ tài liệu PDF bên dưới để được tính hoàn thành bài học này.</p>
                                    </div>
                                </div>
                                @if($currentLesson->document || !empty($pdfFile))
    @php $pdfLink = $currentLesson->document ? asset('storage/' . $currentLesson->document->file_path) : asset('storage/learning/' . $pdfFile); @endphp
                                    <a href="{{ $pdfLink }}" download class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2 rounded-lg flex items-center gap-2 transition-colors whitespace-nowrap shadow-sm">
                                        Tải PDF
                                    </a>
                                @endif
                            </div>

                            @if($currentLesson->document || !empty($pdfFile))
                                <div class="bg-gray-200 rounded-xl overflow-hidden border border-gray-300 shadow-inner mb-4">
                                    <iframe src="{{ $pdfLink }}#toolbar=0" width="100%" height="650px" class="border-none block"></iframe>
                                </div>
                            @endif

                            {{-- ĐIỂM NEO THEO DÕI CUỘN - CHỈ DÀNH RIÊNG CHO BÀI DẠNG TEXT/PDF --}}
                            <div id="scroll-anchor" class="h-10 mt-2 flex items-center justify-center text-gray-400 text-xs border-t border-dashed border-gray-300 pt-4">
                                <span x-show="!canComplete" class="flex items-center gap-1 animate-pulse">⬇️ Đang đọc tài liệu... Vui lòng cuộn xuống hết trang</span>
                                <span x-show="canComplete" class="text-green-600 font-bold flex items-center gap-1">✓ Hệ thống xác nhận đã hoàn thành đọc tài liệu!</span>
                            </div>
                        @endif
                    </div>
                </div>

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

                {{-- TABS HỎI ĐÁP THẢO LUẬN & CHỨC NĂNG XOÁ BÌNH LUẬN --}}
                <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                    <div class="bg-gray-50 border-b border-gray-200 flex text-sm font-bold">
                        <button class="px-6 py-4 border-b-2 border-cyan-600 text-cyan-700 bg-white">💬 Hỏi đáp & Thảo luận</button>
                    </div>

                    <div class="p-5 min-h-[250px]">
                        <div class="space-y-6">
                            
                            {{-- Form Thêm Bình Luận --}}
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                <h4 class="font-bold text-gray-800 text-xs mb-2">Đặt câu hỏi thảo luận về bài học:</h4>
                                <form action="{{ route('learning.lessons.question', $currentLesson->id) }}" method="POST">
                                    @csrf
                                    <textarea name="content" rows="3" placeholder="Nhập nội dung thắc mắc tại đây..." class="w-full bg-white border border-gray-300 rounded-xl p-3 text-gray-900 text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 mb-3 shadow-sm" required></textarea>
                                    <div class="flex justify-end">
                                        <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-xs px-5 py-2 rounded-xl shadow-sm transition-colors">Gửi bình luận</button>
                                    </div>
                                </form>
                            </div>
                            
                            {{-- Danh sách Bình luận & Nút Xóa Chính Chủ --}}
                            @if(isset($lessonQuestions) && $lessonQuestions->count() > 0)
                                <div class="space-y-4">
                                    @foreach($lessonQuestions as $question)
                                        <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm hover:shadow transition-shadow">
                                            <div class="flex items-start gap-3">
                                                <div class="w-9 h-9 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                                    {{ strtoupper(substr($question->user->name, 0, 1)) }}
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex items-center justify-between mb-1">
                                                        <span class="text-sm font-bold text-gray-900">{{ $question->user->name }}</span>
                                                        <span class="text-[11px] text-gray-400 font-medium">{{ $question->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-sm text-gray-700 leading-relaxed">{{ $question->content }}</p>
                                                    
                                                    <div class="mt-2.5 flex items-center gap-3">
                                                        @if($question->is_answered)
                                                            <span class="text-[10px] font-bold text-green-700 bg-green-50 px-2 py-0.5 rounded-md border border-green-100">✓ Đã trả lời</span>
                                                        @endif
                                                        
                                                        {{-- KHU VỰC HIỂN THỊ NÚT XOÁ NẾU LÀ USER ĐĂNG NHẬP --}}
                                                        @if(Auth::check() && Auth::id() === $question->user_id)
                                                            <form action="{{ route('learning.questions.destroy', $question->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xoá vĩnh viễn bình luận này?');" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-[11px] font-semibold text-red-400 hover:text-red-600 underline underline-offset-2 transition-colors">
                                                                    Xoá bình luận
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                        </div>
                    </div>
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
    function lessonController() {
        return {
            lessonType: '{{ $currentLesson->lesson_type ?? "text" }}',
            canComplete: false,
            sidebarOpen: window.innerWidth >= 768,
            
            init() {
                // Nếu là bài học dạng video, canComplete lập tức chuyển thành true (không ép cuộn trang)
                if (this.lessonType !== 'text') {
                    this.canComplete = true;
                } else {
                    // Nếu là bài text, cài đặt IntersectionObserver theo dõi điểm neo
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
                if (this.lessonType === 'text' && !this.canComplete) {
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