<!DOCTYPE html>
<html lang="vi" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $currentLesson->title }} - DevAcademy Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="h-full bg-[#070c1e] text-slate-100 font-sans antialiased flex flex-col overflow-hidden">

    <header class="bg-slate-950 border-b border-slate-800/80 px-6 py-3.5 flex items-center justify-between z-20 flex-shrink-0">
        <div class="flex items-center space-x-3">
            <a href="{{ route('learning.roadmaps.show', $roadmap->id) }}" class="text-slate-400 hover:text-white transition-colors" title="Quay lại">
                ◁ Trở về Lộ trình
            </a>
            <div class="h-4 w-[1px] bg-slate-800"></div>
            <h2 class="text-sm font-bold text-slate-200 truncate max-w-md">{{ $roadmap->title }}</h2>
        </div>
        <div class="text-xs font-bold text-slate-400">
            Học viên: <span class="text-blue-400">{{ Auth::user()->name }}</span>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden relative">
        
        <aside class="w-80 bg-slate-950/80 border-r border-slate-800/80 overflow-y-auto flex-shrink-0 hidden md:block">
            <div class="p-4 bg-slate-950 sticky top-0 border-b border-slate-900 z-10">
                <h3 class="text-xs font-black uppercase text-slate-400 tracking-wider">Mục lục lộ trình học</h3>
            </div>
            <div class="p-2 space-y-4">
                @foreach($roadmap->sections as $sIdx => $section)
                <div class="space-y-1">
                    <div class="px-3 py-1.5 text-[10px] font-black uppercase text-blue-400 bg-blue-500/5 rounded border border-blue-500/10">
                        STAGE {{ $sIdx + 1 }}: {{ $section->title }}
                    </div>
                    <div class="space-y-0.5">
                        @foreach($section->lessons as $lesson)
                        <a href="{{ route('learning.roadmaps.learn', [$roadmap->id, $lesson->id]) }}" 
                           class="flex items-center justify-between px-3 py-2 text-xs rounded-xl transition-all {{ $currentLesson->id == $lesson->id ? 'bg-blue-600 text-white font-bold' : 'text-slate-400 hover:bg-slate-900/60 hover:text-slate-200' }}">
                            <span class="truncate pr-2">○ {{ $lesson->title }}</span>
                            <span class="text-[9px] uppercase tracking-tight font-black opacity-60 px-1 bg-slate-950 rounded">{{ $lesson->type ?? 'text' }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </aside>

        <main class="flex-1 flex flex-col overflow-y-auto bg-[#0b1329] p-6 lg:p-10 pb-24" x-data="{ currentTab: 'content' }">
            
            <div class="bg-slate-950/40 border border-slate-800 rounded-2xl p-6 mb-6">
                <span class="text-[10px] font-black uppercase tracking-widest text-blue-400 bg-blue-500/10 px-2.5 py-1 rounded-md border border-blue-500/20 mb-3 inline-block">
                    Bài học dạng: {{ strtoupper($currentLesson->type ?? 'Text') }}
                </span>
                <h1 class="text-xl md:text-3xl font-black text-white tracking-tight mb-4">
                    {{ $currentLesson->title }}
                </h1>

                <div class="mt-4">
                    @if(($currentLesson->type ?? 'text') == 'video')
                        <div class="aspect-video w-full bg-slate-950 rounded-xl overflow-hidden border border-slate-800 flex items-center justify-center">
                            <iframe class="w-full h-full" src="{{ $currentLesson->video_url ?? 'https://www.youtube.com/embed/dQw4w9WgXcQ' }}" frameborder="0" allowfullscreen></iframe>
                        </div>
                    @elseif(($currentLesson->type ?? 'text') == 'exam')
                        <div class="bg-amber-500/5 border border-amber-500/20 p-6 rounded-xl text-center">
                            <p class="text-amber-400 font-bold mb-3">📝 Bài học này là một bài kiểm tra trắc nghiệm đánh giá năng lực!</p>
                            <a href="#" class="inline-block bg-amber-500 text-slate-950 font-black text-xs px-6 py-2.5 rounded-xl uppercase tracking-wider hover:opacity-90">Bắt đầu làm bài thi</a>
                        </div>
                    @elseif(($currentLesson->type ?? 'text') == 'project')
                        <div class="bg-purple-500/5 border border-purple-500/20 p-6 rounded-xl text-center">
                            <p class="text-purple-400 font-bold mb-3">🚀 Thử thách thực hành Đồ án lớn (Capstone Project)!</p>
                            <p class="text-slate-400 text-xs mb-4">Yêu cầu bạn Fork mã nguồn và triển khai đẩy link Github nghiệm thu.</p>
                            <a href="#" class="inline-block bg-purple-600 text-white font-black text-xs px-6 py-2.5 rounded-xl uppercase tracking-wider hover:bg-purple-500">Tải đề bài Đồ án</a>
                        </div>
                    @else
                        <div class="prose prose-invert max-w-none text-slate-300 text-sm leading-relaxed space-y-4">
                            {!! $currentLesson->content ?? '<p class="text-slate-500 italic">Nội dung văn bản kỹ thuật chi tiết của bài học hiện đang được cập nhật...</p>' !!}
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-slate-950/40 border border-slate-800 rounded-2xl overflow-hidden">
                <div class="bg-slate-950/80 border-b border-slate-800/60 flex text-xs font-bold">
                    <button @click="currentTab = 'content'" :class="currentTab === 'content' ? 'border-b-2 border-blue-500 text-blue-400 bg-slate-900/40' : 'text-slate-400'" class="px-6 py-3.5 focus:outline-none transition-all">📖 Chi tiết bổ sung</button>
                    <button @click="currentTab = 'note'" :class="currentTab === 'note' ? 'border-b-2 border-blue-500 text-blue-400 bg-slate-900/40' : 'text-slate-400'" class="px-6 py-3.5 focus:outline-none transition-all">📝 Sổ tay ghi chú</button>
                    <button @click="currentTab = 'qa'" :class="currentTab === 'qa' ? 'border-b-2 border-blue-500 text-blue-400 bg-slate-900/40' : 'text-slate-400'" class="px-6 py-3.5 focus:outline-none transition-all">💬 Hỏi đáp Q&A Thảo luận</button>
                </div>

                <div class="p-6 text-xs text-slate-300 min-h-[200px]">
                    <div x-show="currentTab === 'content'" class="space-y-2">
                        <h4 class="font-bold text-white text-sm mb-2">Tài liệu và chỉ dẫn kỹ thuật đi kèm</h4>
                        <p class="text-slate-400">Hãy đảm bảo bạn đã gõ lại mã nguồn theo hướng dẫn và kiểm tra log terminal trước khi nhấn nút hoàn thành.</p>
                    </div>

                    <div x-show="currentTab === 'note'" class="space-y-4">
                        <form action="{{ route('learning.lessons.note', $currentLesson->id) }}" method="POST">
                            @csrf
                            <textarea name="content" rows="4" placeholder="Ghi chú lại những kiến thức quan trọng hoặc câu lệnh cần nhớ tại đây..." class="w-full bg-slate-900 border border-slate-800 rounded-xl p-3 text-white text-xs focus:outline-none focus:border-blue-500/80 transition-all mb-3"></textarea>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold px-4 py-2 rounded-xl transition-all">Lưu vào Sổ tay</button>
                            </div>
                        </form>
                    </div>

                    <div x-show="currentTab === 'qa'" class="space-y-4">
                        <form action="{{ route('learning.lessons.question', $currentLesson->id) }}" method="POST" class="border-b border-slate-800/80 pb-4">
                            @csrf
                            <textarea name="content" rows="3" placeholder="Gặp lỗi log hoặc không chạy được code? Đặt câu hỏi tại đây để cộng đồng chuyên gia trợ giúp..." class="w-full bg-slate-900 border border-slate-800 rounded-xl p-3 text-white text-xs focus:outline-none focus:border-blue-500/80 transition-all mb-3"></textarea>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-bold px-4 py-2 rounded-xl transition-all">Gửi Câu Hỏi</button>
                            </div>
                        </form>
                        <div class="text-center text-slate-500 py-4 italic">Chưa có cuộc thảo luận nào cho bài học này. Hãy là người đặt câu hỏi đầu tiên!</div>
                    </div>
                </div>
            </div>

        </main>

        <footer class="fixed bottom-0 left-0 right-0 md:left-80 bg-slate-950 border-t border-slate-800/80 px-6 py-4 flex items-center justify-between z-10">
            <span class="text-[11px] text-slate-500 italic hidden sm:inline">Hãy chắc chắn đã hiểu bài trước khi chuyển chặng.</span>
            
            <form action="{{ route('learning.roadmaps.complete', [$roadmap->id, $currentLesson->id]) }}" method="POST" class="ml-auto">
                @csrf
                <button type="submit" class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:opacity-90 text-white text-xs font-black py-2.5 px-6 rounded-xl transition-all flex items-center space-x-2 shadow-lg tracking-wider uppercase">
                    <span>Hoàn thành & Sang bài tiếp theo ➔</span>
                </button>
            </form>
        </footer>

    </div>
</body>
</html>