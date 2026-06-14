@extends('learning::layouts.master')

@section('content')
<div class="min-h-screen bg-slate-900 text-slate-100 font-sans flex flex-col">
    <div class="bg-slate-800 border-b border-slate-700 px-6 py-4 flex justify-between items-center shadow-md">
        <div class="flex items-center gap-3">
            <a href="{{ route('learning.roadmaps.show', $roadmap->id) }}" class="text-slate-400 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <p class="text-xs text-blue-400 uppercase font-bold tracking-wider">Đang học lộ trình</p>
                <h1 class="text-base font-bold text-white">{{ $roadmap->title }}</h1>
            </div>
        </div>
        <div class="text-xs md:text-sm bg-blue-950 border border-blue-800 px-4 py-2 rounded-full text-blue-300 font-medium">
            Mã tiến độ: #EP-{{ $enrollment->id }} ({{ $enrollment->progress_percent }}%)
        </div>
    </div>

    <div class="flex-1 flex flex-col md:flex-row overflow-hidden">
        
        <div class="flex-1 p-6 overflow-y-auto space-y-6">
            @if(session('success'))
                <div class="bg-emerald-950/50 border border-emerald-500 text-emerald-300 p-4 rounded-xl text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <div class="max-w-3xl mx-auto space-y-4">
                <h2 class="text-2xl font-extrabold text-white">{{ $currentLesson->title }}</h2>
                
                @if($currentLesson->lesson_type == 'video' && $currentLesson->video_url)
                    <div class="aspect-video w-full bg-black rounded-2xl overflow-hidden shadow-2xl border border-slate-800">
                        <iframe class="w-full h-full" src="{{ $currentLesson->video_url }}" title="Video lesson" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                @endif

                <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 shadow-xl leading-relaxed text-slate-300 space-y-3 prose prose-invert">
                    {!! nl2br(e($currentLesson->content)) !!}
                </div>

                <div class="pt-4 border-t border-slate-800 flex justify-end">
                    <form action="{{ route('learning.lessons.complete', $currentLesson->id) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition transform active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Đánh dấu đã hoàn thành bài giảng
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="w-full md:w-80 bg-slate-800 border-t md:border-t-0 md:border-l border-slate-700 overflow-y-auto">
            <div class="p-4 border-b border-slate-700 bg-slate-800/50 sticky top-0 backdrop-blur-sm">
                <h3 class="font-bold text-slate-200 flex items-center gap-2 text-sm uppercase tracking-wider">
                    Cấu trúc khóa học
                </h3>
            </div>

            <div class="p-2 space-y-4">
                @foreach($roadmap->sections as $section)
                    <div class="space-y-1">
                        <h4 class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                            {{ $section->title }}
                        </h4>
                        
                        <div class="space-y-0.5">
                            @foreach($section->lessons as $lesson)
                                @php
                                    $isCurrent = $lesson->id == $currentLesson->id;
                                    $isCompleted = in_array($lesson->id, $completedLessonIds);
                                @endphp
                                <a href="{{ route('learning.roadmaps.learn', [$roadmap->id, $lesson->id]) }}" 
                                   class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition group
                                   {{ $isCurrent ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-700/50' }}">
                                    
                                    <div class="flex items-center gap-2 overflow-hidden mr-2">
                                        @if($isCompleted)
                                            <span class="text-emerald-400 text-xs flex-shrink-0">✓</span>
                                        @else
                                            <span class="text-slate-500 text-xs flex-shrink-0 font-mono">•</span>
                                        @endif
                                        <span class="truncate">{{ $lesson->title }}</span>
                                    </div>

                                    @if($lesson->lesson_type == 'video')
                                        <span class="text-[10px] {{ $isCurrent ? 'text-blue-200' : 'text-slate-500 group-hover:text-slate-400' }} flex-shrink-0 uppercase font-mono">Video</span>
                                    @else
                                        <span class="text-[10px] {{ $isCurrent ? 'text-blue-200' : 'text-slate-500 group-hover:text-slate-400' }} flex-shrink-0 uppercase font-mono">Text</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection