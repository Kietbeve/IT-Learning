@extends('learning::layouts.master')

@section('content')
<div class="min-h-screen bg-slate-50 text-slate-800 font-sans">
    <div class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white py-12 px-6 shadow-md">
        <div class="max-w-5xl mx-auto">
            <span class="bg-blue-800 text-blue-200 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                Chi tiết lộ trình
            </span>
            <h1 class="text-3xl font-extrabold mt-3 tracking-tight">{{ $roadmap->title }}</h1>
            <p class="text-blue-100 mt-2 max-w-2xl">{{ $roadmap->short_description }}</p>
            
            <div class="mt-6 text-sm text-blue-50 flex items-center gap-4">
                <span>Người hướng dẫn: <b>{{ $roadmap->author->name ?? 'Giảng viên' }}</b></span>
                <span>•</span>
                <span>Cập nhật: <b>{{ $roadmap->updated_at->format('d/m/Y') }}</b></span>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-6 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    Nội dung chương trình học
                </h2>

                @forelse($roadmap->sections as $section)
                    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                        <div class="bg-slate-100 px-5 py-4 border-b border-slate-200">
                            <h3 class="font-bold text-slate-700 text-base flex items-center gap-2">
                                <span class="bg-blue-600 text-white rounded-md w-6 h-6 inline-flex items-center justify-center text-xs font-mono">{{ $section->sort_order }}</span>
                                {{ $section->title }}
                            </h3>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @forelse($section->lessons as $lesson)
                                <div class="p-4 hover:bg-slate-50/80 transition flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        @if($lesson->lesson_type == 'video')
                                            <span class="p-2 bg-amber-50 text-amber-600 rounded-lg text-sm font-bold">🎬 Video</span>
                                        @else
                                            <span class="p-2 bg-blue-50 text-blue-600 rounded-lg text-sm font-bold">📄 Text</span>
                                        @endif
                                        <p class="font-medium text-slate-700 text-sm md:text-base">{{ $lesson->title }}</p>
                                    </div>
                                    <a href="{{ route('learning.roadmaps.learn', [$roadmap->id, $lesson->id]) }}" 
                                       class="text-xs bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition shadow-sm">
                                        Vào học
                                    </a>
                                </div>
                            @empty
                                <p class="p-4 text-xs text-slate-400 italic">Chương này chưa có bài học nào công khai.</p>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-slate-400 italic">Lộ trình này hiện tại chưa được phân chia chương mục.</div>
                @endforelse
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm sticky top-6">
                    <h3 class="font-bold text-slate-800 text-lg mb-4">Tiến độ của bạn</h3>
                    
                    @if($enrollment)
                        <div class="space-y-4">
                            <div class="flex justify-between text-sm font-semibold text-slate-600 mb-1">
                                <span>Tiến độ hoàn thành:</span>
                                <span class="text-blue-600 font-mono">{{ $enrollment->progress_percent }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $enrollment->progress_percent }}%"></div>
                            </div>
                            <a href="{{ route('learning.roadmaps.learn', $roadmap->id) }}" class="block text-center w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition">
                                Tiếp tục học tập
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-sm text-slate-500 mb-4">Bạn chưa bắt đầu học lộ trình này.</p>
                            <a href="{{ route('learning.roadmaps.learn', $roadmap->id) }}" class="block text-center w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold py-3 rounded-xl transition shadow-md shadow-blue-200">
                                Bắt đầu học ngay
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection