@extends('learning::layouts.master')

@section('content')
<div class="min-h-screen bg-[#0b1329] text-slate-100 font-sans antialiased py-10">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-gradient-to-b from-[#0e1b3e] to-[#0a122c] border border-slate-800 rounded-2xl p-6 shadow-xl">
                    <h1 class="text-2xl md:text-4xl font-black text-white leading-tight mb-3">
                        {{ $roadmap->title }}
                    </h1>
                    <p class="text-slate-300 text-sm leading-relaxed mb-6">
                        {{ $roadmap->description }}
                    </p>

                    <div class="bg-slate-950/60 border border-slate-800/80 rounded-xl p-5">
                        <h3 class="text-xs font-black uppercase tracking-wider text-blue-400 mb-3">Mục tiêu bạn sẽ đạt được:</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @if(!empty($roadmap->objective))
                                @foreach(explode(',', $roadmap->objective) as $obj)
                                    <div class="flex items-start gap-2 text-xs text-slate-300">
                                        <span class="text-emerald-400 font-bold">✓</span>
                                        <span>{{ trim($obj) }}</span>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-xs text-slate-500 italic">✓ Làm chủ nền tảng kiến trúc lõi cốt truyện từ cơ bản đến nâng cao.</div>
                                <div class="text-xs text-slate-500 italic">✓ Triển khai giải pháp thực chiến chuẩn Clean Code doanh nghiệp lớn.</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-base font-black uppercase tracking-wider text-slate-400 mb-4">Nội Dung Chi Tiết Của Lộ Trình</h2>
                    <div class="space-y-3">
                        @foreach($roadmap->sections as $index => $section)
                        <div class="bg-[#0c142c] border border-slate-800/80 rounded-xl overflow-hidden">
                            <div class="bg-slate-950/40 px-4 py-3 border-b border-slate-800/40 flex justify-between items-center">
                                <h3 class="text-xs font-bold text-white flex items-center gap-2">
                                    <span class="bg-blue-500/10 text-blue-400 px-2 py-0.5 rounded text-[10px] font-black">STAGE {{ $index + 1 }}</span>
                                    {{ $section->title }}
                                </h3>
                                <span class="text-[11px] text-slate-500 font-medium">{{ $section->lessons->count() }} bài học</span>
                            </div>

                            <div class="divide-y divide-slate-800/40 bg-slate-900/10">
                                @foreach($section->lessons as $lesson)
                                <div class="px-4 py-3 flex items-center justify-between text-xs hover:bg-slate-800/20 transition-all">
                                    <div class="flex items-center gap-3">
                                        @if(in_array($lesson->id, $completedLessonIds ?? []))
                                            <span class="text-emerald-500 font-bold text-sm">✓</span>
                                        @else
                                            <span class="text-slate-600 text-sm">○</span>
                                        @endif
                                        <span class="text-slate-300 font-medium">{{ $lesson->title }}</span>
                                    </div>
                                    <span class="text-[10px] uppercase font-black tracking-widest text-slate-500 px-2 py-0.5 bg-slate-950/40 rounded border border-slate-800/60">
                                        {{ $lesson->type ?? 'Text' }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1 space-y-6 lg:sticky lg:top-24">
                <div class="bg-gradient-to-b from-[#0d1632] to-[#060b1c] border border-slate-800 rounded-2xl p-5 shadow-2xl relative overflow-hidden">
                    
                    <div class="mb-4 text-center text-xs font-bold bg-slate-950/50 py-2 rounded-xl border border-slate-800/60 text-slate-400">
                        📊 {{ $roadmap->sections_count }} Stages • {{ $roadmap->lessons_count }} Lessons
                    </div>

                    @if($isEnrolled)
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between items-center text-xs font-bold text-slate-300 mb-1.5">
                                    <span>Tiến độ học tập của bạn</span>
                                    <span class="text-blue-400">{{ $progressPercent }}%</span>
                                </div>
                                <div class="w-full bg-slate-950 rounded-full h-2.5 overflow-hidden border border-slate-800">
                                    <div class="bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-1.5 text-center">Đã hoàn thành {{ $completedLessonsCount }}/{{ $roadmap->lessons_count }} bài học</p>
                            </div>

                            @php
                                $firstLessonId = $roadmap->sections->first()?->lessons->first()?->id;
                            @endphp
                            @if($firstLessonId)
                                <a href="{{ route('learning.roadmaps.learn', [$roadmap->id, $firstLessonId]) }}" 
                                   class="w-full block text-center bg-blue-600 hover:bg-blue-500 text-white font-black text-xs py-3 rounded-xl shadow-xl transition-all tracking-wider uppercase">
                                    Tiếp Tục Học Tập 🚀
                                </a>
                            @endif
                        </div>
                    @else
                        <form action="{{ route('learning.roadmaps.enroll', $roadmap->id) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full text-center bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white font-black text-xs py-3.5 px-6 rounded-xl shadow-xl hover:from-blue-500 transition-all tracking-widest uppercase">
                                [ THAM GIA LỘ TRÌNH NGAY ]
                            </button>
                        </form>
                    @endif
                </div>
            </div>

        </div>

    </div>
</div>
@endsection