@extends('layouts.user')

@section('content')

@section('content')
<div class="flex flex-col h-[calc(100vh-64px)] bg-gray-50 text-gray-900 font-sans antialiased overflow-hidden" x-data="lessonController()">
    
    {{-- HEADER HIỂN THỊ TIẾN ĐỘ --}}
    <header class="bg-gradient-to-r from-cyan-50 to-blue-50 border-b border-cyan-200 z-20 flex-shrink-0 shadow-sm">
        <div class="px-6 py-3.5 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('learning.roadmaps.show', $roadmap->id) }}" class="text-gray-700 hover:text-cyan-600 transition-colors font-medium text-sm flex items-center gap-1">
                    ◁ Trở về Lộ trình
                </a>
                <div class="h-4 w-[1px] bg-gray-300"></div>
                <h2 class="text-sm font-bold text-cyan-700 tracking-wider uppercase truncate max-w-xs md:max-w-md">{{ $roadmap->title }}</h2>
            </div>
            <div class="text-sm font-bold text-gray-700 hidden sm:block">
                Học viên: <span class="text-cyan-600">{{ Auth::user()->name }}</span>
            </div>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden relative">
        
        {{-- SIDEBAR MỤC LỤC TRÊN BÊN TRÁI (ĐÃ KHÔI PHỤC HOÀN CHỈNH) --}}
        <aside class="w-80 bg-white border-r border-gray-200 overflow-y-auto flex-shrink-0 hidden md:block shadow-sm z-10">
            <div class="p-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between sticky top-0 z-10">
                <h3 class="font-bold text-gray-800 text-xs tracking-wider uppercase">Nội dung lộ trình</h3>
                <span class="text-xs bg-cyan-100 text-cyan-800 font-bold px-2 py-0.5 rounded-full">
                    {{ is_array($progressList) ? count($progressList) : $progressList->count() }} Bài học
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
        <main class="flex-1 flex flex-col overflow-y-auto bg-gray-50/50 p-4 md:p-8 pb-32 scroll-smooth" id="main-scroll-area" @scroll="checkScroll">
            
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
            </div>
        </main>
    </div>

    {{-- THANH TIẾN ĐỘ & DIỀU HƯỚNG HOÀN THÀNH (DƯỚI CÙNG TRANG) --}}
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 md:pl-80 z-30 shadow-md">
        <div class="max-w-5xl mx-auto flex items-center justify-end">
            <form action="{{ route('learning.lessons.complete', [$roadmap->id, $currentLesson->id]) }}" method="POST">
                @csrf
                <button type="submit" 
                        :disabled="!canComplete"
                        :class="canComplete ? 'bg-cyan-600 hover:bg-cyan-700 text-white shadow-md' : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                        class="font-black text-xs px-6 py-3 rounded-xl uppercase tracking-wider flex items-center gap-2 transition-all">
                    <span x-text="canComplete ? 'HOÀN THÀNH & SANG BÀI TIẾP THEO' : 'CẦN XEM HẾT TÀI LIỆU ĐỂ TIẾP TỤC'"></span>
                </button>
            </form>
        </div>
    </div>

</div>

<script>
    function lessonController() {
        return {
            lessonType: '{{ $currentLesson->lesson_type ?? "text" }}',
            canComplete: false,
            
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
            }
        }
    }
</script>
@endsection