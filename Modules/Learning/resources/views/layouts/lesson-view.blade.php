<!DOCTYPE html>
<html lang="vi" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $currentLesson->title }} - DevAcademy Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="h-full bg-gray-50 text-gray-900 font-sans antialiased flex flex-col overflow-hidden">

    <header class="bg-gradient-to-r from-cyan-50 to-blue-50 border-b border-cyan-200 z-20 flex-shrink-0">
        <div class="px-6 py-3.5 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('learning.roadmaps.show', $roadmap->id) }}" class="text-gray-800 hover:text-gray-900 transition-colors" title="Quay lại">
                    ◁ Trở về Lộ trình
                </a>
                <div class="h-4 w-[1px] bg-gray-300"></div>
                <h2 class="text-sm font-bold text-cyan-600 tracking-wider">{{ $roadmap->title }}</h2>
            </div>
            <div class="text-base font-bold text-gray-800">
                Học viên: <span class="text-cyan-600">{{ Auth::user()->name }}</span>
            </div>
        </div>
        
        @php
            // Tính tiến độ theo chương (section-based progress)
            $totalSections = $roadmap->sections->count();
            $completedSections = 0;
            $totalLessonsInRoadmap = 0;
            $completedLessonsInRoadmap = 0;
            
            foreach ($roadmap->sections as $section) {
                $lessonsInSection = $section->lessons->count();
                $totalLessonsInRoadmap += $lessonsInSection;
                
                if ($lessonsInSection > 0) {
                    $completedInSection = 0;
                    foreach ($section->lessons as $lesson) {
                        if (isset($progressList[$lesson->id]) && $progressList[$lesson->id]['status'] === 'completed') {
                            $completedInSection++;
                            $completedLessonsInRoadmap++;
                        }
                    }
                    // Chỉ tính chương hoàn thành khi TẤT CẢ bài học trong chương đã xong
                    if ($completedInSection === $lessonsInSection) {
                        $completedSections++;
                    }
                }
            }
            
            $progressPercent = $totalSections > 0 ? round(($completedSections / $totalSections) * 100) : 0;
        @endphp
        
        <div class="px-6 pb-3">
            <div class="flex items-center justify-between text-base mb-1.5">
                <span class="text-gray-800 font-medium">Tiến độ hoàn thành</span>
                <span class="text-cyan-600 font-bold">{{ $completedSections }}/{{ $totalSections }} chương ({{ $completedLessonsInRoadmap }}/{{ $totalLessonsInRoadmap }} bài) • {{ $progressPercent }}%</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                <div class="bg-gradient-to-r from-cyan-500 via-blue-500 to-indigo-500 h-2 rounded-full transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
            </div>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden relative">
        
        <aside class="w-80 bg-gray-50/80 border-r border-gray-200/80 overflow-y-auto flex-shrink-0 hidden md:block">
            <div class="p-4 bg-white sticky top-0 border-b border-gray-200 z-10">
                <h3 class="text-base font-black uppercase text-gray-800 tracking-wider">Mục lục lộ trình học</h3>
            </div>
            <div class="p-2 space-y-4">
                @foreach($roadmap->sections as $sIdx => $section)
                <div class="space-y-1">
                    <div class="px-3 py-1.5 text-[10px] font-black uppercase text-cyan-700 bg-cyan-100 rounded border border-cyan-200">
                        STAGE {{ $sIdx + 1 }}: {{ $section->title }}
                    </div>
                    <div class="space-y-0.5">
                        @foreach($section->lessons as $lesson)
                        @php
                            $isCompleted = isset($progressList[$lesson->id]) && $progressList[$lesson->id]['status'] === 'completed';
                        @endphp
                        <a href="{{ route('learning.roadmaps.learn', [$roadmap->id, $lesson->id]) }}" 
                           class="flex items-center justify-between px-3 py-2 text-base rounded-xl transition-all {{ $currentLesson->id == $lesson->id ? 'bg-cyan-600 text-white font-bold' : ($isCompleted ? 'text-green-400 hover:bg-gray-200/60' : 'text-gray-800 hover:bg-gray-200/60 hover:text-gray-800') }}">
                            <span class="truncate pr-2 flex items-center gap-1">
                                @if($isCompleted)
                                    <span class="text-green-500">✓</span>
                                @else
                                    <span class="opacity-50">○</span>
                                @endif
                                {{ $lesson->title }}
                            </span>
                            <span class="text-[9px] uppercase tracking-tight font-black opacity-60 px-1 bg-white rounded">{{ $lesson->lesson_type ?? 'text' }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endforeach
                
                {{-- Standalone Project Lessons --}}
                @if($roadmap->lessons->whereNull('section_id')->count() > 0)
                <div class="space-y-1">
                    <div class="px-3 py-1.5 text-[10px] font-black uppercase text-purple-700 bg-purple-100 rounded border border-purple-200">
                        🚀 PROJECTS & ASSIGNMENTS
                    </div>
                    <div class="space-y-0.5">
                        @foreach($roadmap->lessons->whereNull('section_id')->sortBy('sort_order') as $lesson)
                        @php
                            $isCompleted = isset($progressList[$lesson->id]) && $progressList[$lesson->id]['status'] === 'completed';
                        @endphp
                        <a href="{{ route('learning.roadmaps.learn', [$roadmap->id, $lesson->id]) }}" 
                           class="flex items-center justify-between px-3 py-2 text-base rounded-xl transition-all {{ $currentLesson->id == $lesson->id ? 'bg-gradient-to-r from-purple-500 to-pink-500 text-white font-bold shadow-md' : ($isCompleted ? 'text-green-600 hover:bg-purple-50' : 'text-gray-800 hover:bg-purple-50/50 hover:text-gray-900') }}">
                            <span class="truncate pr-2 flex items-center gap-1">
                                @if($isCompleted)
                                    <span class="text-green-500">✓</span>
                                @else
                                    <span class="opacity-50">🎯</span>
                                @endif
                                {{ $lesson->title }}
                            </span>
                            <span class="text-[9px] uppercase tracking-tight font-black opacity-60 px-1 bg-white rounded">{{ $lesson->lesson_type ?? 'text' }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </aside>

        <main class="flex-1 flex flex-col overflow-y-auto bg-gray-50 p-6 lg:p-10 pb-24" x-data="{ currentTab: 'content' }">
            
            <div class="bg-gray-50/40 border border-gray-200 rounded-2xl p-6 mb-6">
                <span class="text-[10px] font-black uppercase tracking-widest text-cyan-700 bg-cyan-100 px-2.5 py-1 rounded-md border border-cyan-200 mb-3 inline-block">
                    Bài học dạng: {{ strtoupper($currentLesson->lesson_type ?? 'Text') }}
                </span>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight mb-4">
                    {{ $currentLesson->title }}
                </h1>

                <div class="mt-4">
                    @if(($currentLesson->lesson_type ?? 'text') == 'video')
                        @if($currentLesson->video_url)
                            <div class="bg-gray-200/50 rounded-xl border border-gray-200 p-6 mb-4">
                                <div class="flex items-center gap-3 mb-3">
                                    <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                    <div>
                                        <p class="text-sm font-bold text-white mb-1">Video bài giảng</p>
                                        <p class="text-base text-gray-800">Nhấn vào link bên dưới để xem video trên YouTube</p>
                                    </div>
                                </div>
                                <a href="{{ $currentLesson->video_url }}" target="_blank" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-medium text-sm px-5 py-2.5 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                                    Xem video trên YouTube
                                </a>
                            </div>
                        @else
                            <div class="bg-gray-200/50 rounded-xl border border-gray-200 p-6 mb-4">
                                <p class="text-gray-500 text-sm">📹 Video đang được cập nhật...</p>
                            </div>
                        @endif
                        
                        @if($currentLesson->content)
                            <div class="prose prose-invert max-w-none text-gray-800 text-base leading-relaxed">
                                {!! $currentLesson->content !!}
                            </div>
                        @endif
                    @elseif(($currentLesson->lesson_type ?? 'text') == 'exam')
                        <div class="bg-amber-500/5 border border-amber-500/20 p-6 rounded-xl text-center">
                            <p class="text-amber-400 font-bold mb-3">📝 Bài học này là một bài kiểm tra trắc nghiệm đánh giá năng lực!</p>
                            <a href="#" class="inline-block bg-amber-500 text-gray-900 font-black text-base px-6 py-2.5 rounded-xl uppercase tracking-wider hover:opacity-90">Bắt đầu làm bài thi</a>
                        </div>
                    @elseif(($currentLesson->lesson_type ?? 'text') == 'project')
                        <div class="space-y-4">
                            <div class="bg-purple-500/5 border border-purple-500/20 p-6 rounded-xl">
                                <h3 class="text-purple-400 font-bold mb-3 flex items-center gap-2">
                                    <span>🚀 Thử thách thực hành Đồ án lớn (Capstone Project)</span>
                                </h3>
                                <div class="prose prose-invert prose-sm max-w-none text-gray-700 mb-4">
                                    {!! $currentLesson->content ?? '<p class="text-gray-800 text-base">Yêu cầu bạn Fork mã nguồn và triển khai đẩy link Github nghiệm thu.</p>' !!}
                                </div>
                                @if($currentLesson->project && $currentLesson->project->starter_code_url)
                                    <a href="{{ $currentLesson->project->starter_code_url }}" target="_blank" class="inline-block bg-purple-600 text-white font-black text-base px-6 py-2.5 rounded-xl uppercase tracking-wider hover:bg-purple-500 transition-all">
                                        📦 Tải đề bài & Starter Code
                                    </a>
                                @endif
                            </div>

                            @if($projectSubmission)
                                <div class="bg-gray-200/50 border border-gray-300 rounded-xl p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-sm font-bold text-white">Dự án đã nộp</h4>
                                        <span class="text-base px-3 py-1 rounded-full font-bold {{ $projectSubmission->status == 'approved' ? 'bg-green-500/20 text-green-400' : ($projectSubmission->status == 'rejected' ? 'bg-red-500/20 text-red-400' : 'bg-yellow-500/20 text-yellow-400') }}">
                                            {{ $projectSubmission->status == 'approved' ? '✓ Đã duyệt' : ($projectSubmission->status == 'rejected' ? '✗ Cần sửa' : '⏳ Chờ review') }}
                                        </span>
                                    </div>
                                    <div class="space-y-2 text-base text-gray-700">
                                        <div class="flex items-start gap-2">
                                            <span class="text-gray-500 min-w-[100px]">GitHub URL:</span>
                                            <a href="{{ $projectSubmission->github_url }}" target="_blank" class="text-cyan-600 hover:underline break-all">{{ $projectSubmission->github_url }}</a>
                                        </div>
                                        @if($projectSubmission->live_demo_url)
                                            <div class="flex items-start gap-2">
                                                <span class="text-gray-500 min-w-[100px]">Live Demo:</span>
                                                <a href="{{ $projectSubmission->live_demo_url }}" target="_blank" class="text-cyan-600 hover:underline break-all">{{ $projectSubmission->live_demo_url }}</a>
                                            </div>
                                        @endif
                                        @if($projectSubmission->note)
                                            <div class="flex items-start gap-2">
                                                <span class="text-gray-500 min-w-[100px]">Ghi chú:</span>
                                                <span class="text-gray-700">{{ $projectSubmission->note }}</span>
                                            </div>
                                        @endif
                                        <div class="flex items-start gap-2">
                                            <span class="text-gray-500 min-w-[100px]">Nộp lúc:</span>
                                            <span class="text-gray-800">{{ $projectSubmission->submitted_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        @if($projectSubmission->feedback)
                                            <div class="mt-4 p-4 bg-gray-300/50 rounded-lg">
                                                <p class="text-base font-bold text-cyan-600 mb-2">💬 Nhận xét từ giảng viên:</p>
                                                <p class="text-base text-gray-700">{{ $projectSubmission->feedback }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="bg-gray-200/50 border border-gray-300 rounded-xl p-6">
                                    <h4 class="text-sm font-bold text-white mb-4">Nộp dự án của bạn</h4>
                                    <form action="{{ route('learning.lessons.submitProject', [$roadmap->id, $currentLesson->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label class="block text-base font-medium text-gray-700 mb-2">GitHub Repository URL <span class="text-red-400">*</span></label>
                                            <input type="url" name="github_url" required placeholder="https://github.com/username/project-repo" class="w-full bg-gray-300 border border-gray-300 rounded-lg p-3 text-white text-base focus:outline-none focus:border-cyan-500 transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-base font-medium text-gray-700 mb-2">Live Demo URL (optional)</label>
                                            <input type="url" name="live_demo_url" placeholder="https://your-project.vercel.app" class="w-full bg-gray-300 border border-gray-300 rounded-lg p-3 text-white text-base focus:outline-none focus:border-cyan-500 transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-base font-medium text-gray-700 mb-2">Ghi chú / Mô tả (optional)</label>
                                            <textarea name="note" rows="3" placeholder="Mô tả về dự án, các tính năng đã hoàn thành..." class="w-full bg-gray-300 border border-gray-300 rounded-lg p-3 text-white text-base focus:outline-none focus:border-cyan-500 transition-all"></textarea>
                                        </div>
                                        <div>
                                            <label class="block text-base font-medium text-gray-700 mb-2">Đính kèm file (ZIP, PDF, PNG, JPG - Max 100MB)</label>
                                            <input type="file" name="attachment" accept=".zip,.pdf,.png,.jpg" class="w-full bg-gray-300 border border-gray-300 rounded-lg p-3 text-white text-base focus:outline-none focus:border-cyan-500 transition-all">
                                        </div>
                                        <div class="flex justify-end">
                                            <button type="submit" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:opacity-90 text-white font-black text-base px-6 py-3 rounded-xl uppercase tracking-wider transition-all">
                                                🚀 Nộp Dự Án
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="prose prose-invert max-w-none text-gray-700 text-sm leading-relaxed space-y-4">
                            {!! $currentLesson->content ?? '<p class="text-gray-500 italic">Nội dung văn bản kỹ thuật chi tiết của bài học hiện đang được cập nhật...</p>' !!}
                        </div>
                    @endif
                </div>
            </div>

            @if($currentLesson->document)
            <div class="bg-gray-50/40 border border-gray-200 rounded-2xl p-6 mb-6">
                <h3 class="text-sm font-bold text-white mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Tài liệu đính kèm
                </h3>
                <a href="{{ asset('storage/' . $currentLesson->document->file_path) }}" target="_blank" class="flex items-center justify-between p-4 bg-gray-200/50 hover:bg-gray-200 border border-gray-200 rounded-xl transition-all group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-cyan-500/10 rounded-lg flex items-center justify-center text-cyan-600 text-base font-bold">PDF</div>
                        <div>
                            <p class="text-sm font-medium text-white">{{ $currentLesson->document->title ?? 'Tài liệu bài học' }}</p>
                            <p class="text-base text-gray-800">Click để xem hoặc tải xuống</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-800 group-hover:text-cyan-600 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </a>
            </div>
            @endif

            <div class="bg-gray-50/40 border border-gray-200 rounded-2xl overflow-hidden">
                <div class="bg-gray-50/80 border-b border-gray-200/60 flex text-base font-bold">
                    <button @click="currentTab = 'content'" :class="currentTab === 'content' ? 'border-b-2 border-cyan-500 text-cyan-600 bg-gray-100/40' : 'text-gray-800'" class="px-6 py-3.5 focus:outline-none transition-all">📖 Chi tiết bổ sung</button>
                    <button @click="currentTab = 'note'" :class="currentTab === 'note' ? 'border-b-2 border-cyan-500 text-cyan-600 bg-gray-100/40' : 'text-gray-800'" class="px-6 py-3.5 focus:outline-none transition-all">📝 Sổ tay ghi chú</button>
                    <button @click="currentTab = 'qa'" :class="currentTab === 'qa' ? 'border-b-2 border-cyan-500 text-cyan-600 bg-gray-100/40' : 'text-gray-800'" class="px-6 py-3.5 focus:outline-none transition-all">💬 Hỏi đáp Q&A Thảo luận</button>
                    @if($currentLesson->project)
                    <button @click="currentTab = 'project'" :class="currentTab === 'project' ? 'border-b-2 border-cyan-500 text-cyan-600 bg-gray-100/40' : 'text-gray-800'" class="px-6 py-3.5 focus:outline-none transition-all">🚀 Nộp bài Project</button>
                    @endif
                </div>

                <div class="p-6 text-base text-gray-700 min-h-[200px]">
                    <div x-show="currentTab === 'content'" class="space-y-2">
                        <h4 class="font-bold text-white text-sm mb-2">Tài liệu và chỉ dẫn kỹ thuật đi kèm</h4>
                        <p class="text-gray-800">Hãy đảm bảo bạn đã gõ lại mã nguồn theo hướng dẫn và kiểm tra log terminal trước khi nhấn nút hoàn thành.</p>
                    </div>

                    <div x-show="currentTab === 'note'" class="space-y-4">
                        @if($userNote)
                            <div class="text-base text-green-400 mb-2">✓ Bạn đã có ghi chú cho bài học này. Chỉnh sửa và lưu lại để cập nhật.</div>
                        @endif
                        <form action="{{ route('learning.lessons.note', $currentLesson->id) }}" method="POST">
                            @csrf
                            <textarea name="content" rows="6" placeholder="Ghi chú lại những kiến thức quan trọng hoặc câu lệnh cần nhớ tại đây..." class="w-full bg-gray-100 border border-gray-200 rounded-xl p-3 text-white text-base focus:outline-none focus:border-cyan-500/80 transition-all mb-3">{{ $userNote->content ?? '' }}</textarea>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-cyan-600 hover:bg-cyan-500 text-white font-bold px-4 py-2 rounded-xl transition-all">{{ $userNote ? 'Cập nhật ghi chú' : 'Lưu vào Sổ tay' }}</button>
                            </div>
                        </form>
                    </div>

                    <div x-show="currentTab === 'qa'" class="space-y-4">
                        <form action="{{ route('learning.lessons.question', $currentLesson->id) }}" method="POST" class="border-b border-gray-200/80 pb-4 mb-4">
                            @csrf
                            <textarea name="content" rows="3" placeholder="Gặp lỗi log hoặc không chạy được code? Đặt câu hỏi tại đây để cộng đồng chuyên gia trợ giúp..." class="w-full bg-gray-100 border border-gray-200 rounded-xl p-3 text-white text-base focus:outline-none focus:border-cyan-500/80 transition-all mb-3"></textarea>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-gray-300 hover:bg-gray-600 text-gray-800 border border-gray-300 font-bold px-4 py-2 rounded-xl transition-all">Gửi Câu Hỏi</button>
                            </div>
                        </form>
                        
                        @if($lessonQuestions && $lessonQuestions->count() > 0)
                            <div class="space-y-3">
                                <h4 class="text-base font-bold text-gray-800 uppercase tracking-wider">Câu hỏi từ cộng đồng ({{ $lessonQuestions->count() }})</h4>
                                @foreach($lessonQuestions as $question)
                                    <div class="bg-gray-200/50 border border-gray-200 rounded-xl p-4">
                                        <div class="flex items-start gap-3">
                                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white text-base font-bold flex-shrink-0">
                                                {{ strtoupper(substr($question->user->name, 0, 1)) }}
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="text-base font-bold text-gray-800">{{ $question->user->name }}</span>
                                                    <span class="text-[10px] text-gray-500">{{ $question->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-base text-gray-700 leading-relaxed">{{ $question->content }}</p>
                                                @if($question->is_answered)
                                                    <span class="inline-block mt-2 text-[10px] text-green-400 bg-green-500/10 px-2 py-0.5 rounded">✓ Đã trả lời</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-gray-500 py-6 italic text-base">
                                Chưa có cuộc thảo luận nào cho bài học này. Hãy là người đặt câu hỏi đầu tiên!
                            </div>
                        @endif
                    </div>

                    <div x-show="currentTab === 'project'" class="space-y-4">
                        @if($currentLesson->project)
                            <div class="bg-purple-500/5 border border-purple-500/20 p-4 rounded-xl mb-4">
                                <h3 class="text-purple-400 font-bold mb-2 flex items-center gap-2 text-sm">
                                    <span>🚀 Thông tin Project</span>
                                </h3>
                                <div class="prose prose-invert prose-sm max-w-none text-gray-700 mb-3 text-base">
                                    {!! $currentLesson->content ?? '<p class="text-gray-800">Yêu cầu bạn Fork mã nguồn và triển khai đẩy link Github nghiệm thu.</p>' !!}
                                </div>
                                @if($currentLesson->project->starter_code_url)
                                    <a href="{{ $currentLesson->project->starter_code_url }}" target="_blank" class="inline-block bg-purple-600 text-white font-black text-base px-5 py-2 rounded-lg uppercase tracking-wider hover:bg-purple-500 transition-all">
                                        📦 Tải Starter Code
                                    </a>
                                @endif
                            </div>

                            @if($projectSubmission)
                                <div class="bg-gray-200/50 border border-gray-300 rounded-xl p-5">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-sm font-bold text-white">Dự án đã nộp</h4>
                                        <span class="text-base px-3 py-1 rounded-full font-bold {{ $projectSubmission->status == 'approved' ? 'bg-green-500/20 text-green-400' : ($projectSubmission->status == 'rejected' ? 'bg-red-500/20 text-red-400' : 'bg-yellow-500/20 text-yellow-400') }}">
                                            {{ $projectSubmission->status == 'approved' ? '✓ Đã duyệt' : ($projectSubmission->status == 'rejected' ? '✗ Cần sửa' : '⏳ Chờ review') }}
                                        </span>
                                    </div>
                                    <div class="space-y-2 text-base text-gray-700">
                                        <div class="flex items-start gap-2">
                                            <span class="text-gray-500 min-w-[100px]">GitHub URL:</span>
                                            <a href="{{ $projectSubmission->github_url }}" target="_blank" class="text-cyan-600 hover:underline break-all">{{ $projectSubmission->github_url }}</a>
                                        </div>
                                        @if($projectSubmission->live_demo_url)
                                            <div class="flex items-start gap-2">
                                                <span class="text-gray-500 min-w-[100px]">Live Demo:</span>
                                                <a href="{{ $projectSubmission->live_demo_url }}" target="_blank" class="text-cyan-600 hover:underline break-all">{{ $projectSubmission->live_demo_url }}</a>
                                            </div>
                                        @endif
                                        @if($projectSubmission->note)
                                            <div class="flex items-start gap-2">
                                                <span class="text-gray-500 min-w-[100px]">Ghi chú:</span>
                                                <span class="text-gray-700">{{ $projectSubmission->note }}</span>
                                            </div>
                                        @endif
                                        <div class="flex items-start gap-2">
                                            <span class="text-gray-500 min-w-[100px]">Nộp lúc:</span>
                                            <span class="text-gray-800">{{ $projectSubmission->submitted_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        @if($projectSubmission->feedback)
                                            <div class="mt-4 p-4 bg-gray-300/50 rounded-lg">
                                                <p class="text-base font-bold text-cyan-600 mb-2">💬 Nhận xét từ giảng viên:</p>
                                                <p class="text-base text-gray-700">{{ $projectSubmission->feedback }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="bg-gray-200/50 border border-gray-300 rounded-xl p-5">
                                    <h4 class="text-sm font-bold text-white mb-4">Nộp dự án của bạn</h4>
                                    <form action="{{ route('learning.lessons.submitProject', [$roadmap->id, $currentLesson->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label class="block text-base font-medium text-gray-700 mb-2">GitHub Repository URL <span class="text-red-400">*</span></label>
                                            <input type="url" name="github_url" required placeholder="https://github.com/username/project-repo" class="w-full bg-gray-300 border border-gray-300 rounded-lg p-3 text-white text-base focus:outline-none focus:border-cyan-500 transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-base font-medium text-gray-700 mb-2">Live Demo URL (optional)</label>
                                            <input type="url" name="live_demo_url" placeholder="https://your-project.vercel.app" class="w-full bg-gray-300 border border-gray-300 rounded-lg p-3 text-white text-base focus:outline-none focus:border-cyan-500 transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-base font-medium text-gray-700 mb-2">Ghi chú / Mô tả (optional)</label>
                                            <textarea name="note" rows="3" placeholder="Mô tả về dự án, các tính năng đã hoàn thành..." class="w-full bg-gray-300 border border-gray-300 rounded-lg p-3 text-white text-base focus:outline-none focus:border-cyan-500 transition-all"></textarea>
                                        </div>
                                        <div>
                                            <label class="block text-base font-medium text-gray-700 mb-2">Đính kèm file (ZIP, PDF, PNG, JPG - Max 100MB)</label>
                                            <input type="file" name="attachment" accept=".zip,.pdf,.png,.jpg" class="w-full bg-gray-300 border border-gray-300 rounded-lg p-3 text-white text-base focus:outline-none focus:border-cyan-500 transition-all">
                                        </div>
                                        <div class="flex justify-end">
                                            <button type="submit" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:opacity-90 text-white font-black text-base px-6 py-3 rounded-xl uppercase tracking-wider transition-all">
                                                🚀 Nộp Dự Án
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        @else
                            <div class="text-center text-gray-500 py-8 italic text-base">
                                Bài học này không có project assignment.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </main>

        <footer class="fixed bottom-0 left-0 right-0 md:left-80 bg-white border-t border-gray-200/80 px-6 py-4 flex items-center justify-between z-10">
            <span class="text-[11px] text-gray-500 italic hidden sm:inline">Hãy chắc chắn đã hiểu bài trước khi chuyển chặng.</span>
            
            <form action="{{ route('learning.lessons.complete', [$roadmap->id, $currentLesson->id]) }}" method="POST" class="ml-auto">
                @csrf
                <button type="submit" class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:opacity-90 text-white text-base font-black py-2.5 px-6 rounded-xl transition-all flex items-center space-x-2 shadow-lg tracking-wider uppercase">
                    <span>Hoàn thành & Sang bài tiếp theo ➔</span>
                </button>
            </form>
        </footer>

    </div>
</body>
</html>