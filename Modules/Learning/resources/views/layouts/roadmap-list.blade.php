@extends('learning::layouts.master')

@section('content')
<div class="min-h-screen bg-[#0b1329] text-slate-100 font-sans antialiased">
    <div class="relative py-16 px-4 border-b border-slate-800/60 bg-gradient-to-b from-[#070c1e] via-[#0b1329] to-[#0f1b3a]">
        <div class="relative max-w-5xl mx-auto text-center">
            <span class="inline-flex items-center gap-2 py-1 px-3 rounded-full text-xs font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20 mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                HỆ THỐNG ĐÀO TẠO CYBER STUDIO
            </span>
            <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight">Bản Đồ Lộ Trình Học Tập</h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 lg:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <div class="lg:col-span-1 bg-[#0d1b3e]/60 border border-slate-800/80 rounded-2xl p-5 h-fit sticky top-24 backdrop-blur-md">
                <form action="{{ route('learning.roadmaps.index') }}" method="GET" class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Tìm kiếm từ khóa</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm tên hoặc mô tả..." 
                            class="w-full bg-slate-950/60 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Chuyên ngành</label>
                        <select name="category" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl px-3 py-2.5 text-xs text-slate-300 focus:outline-none focus:border-blue-500">
                            <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>Tất cả chuyên ngành</option>
                            <option value="backend" {{ request('category') == 'backend' ? 'selected' : '' }}>Backend Developer</option>
                            <option value="frontend" {{ request('category') == 'frontend' ? 'selected' : '' }}>Frontend Developer</option>
                            <option value="devops" {{ request('category') == 'devops' ? 'selected' : '' }}>DevOps Engineer</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Trình độ</label>
                        <select name="level" class="w-full bg-slate-950/60 border border-slate-800 rounded-xl px-3 py-2.5 text-xs text-slate-300 focus:outline-none focus:border-blue-500">
                            <option value="all" {{ request('level') == 'all' ? 'selected' : '' }}>Mọi cấp độ</option>
                            <option value="beginner" {{ request('level') == 'beginner' ? 'selected' : '' }}>Cơ bản (Beginner)</option>
                            <option value="intermediate" {{ request('level') == 'intermediate' ? 'selected' : '' }}>Trung cấp (Intermediate)</option>
                            <option value="advanced" {{ request('level') == 'advanced' ? 'selected' : '' }}>Nâng cao (Advanced)</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs py-2.5 rounded-xl transition-all uppercase tracking-wide shadow-lg shadow-blue-500/20">
                        Áp Dụng Bộ Lọc
                    </button>
                </form>
            </div>

            <div class="lg:col-span-3">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($roadmaps as $roadmap)
                    <div class="group bg-[#0e1731] border border-slate-800/80 rounded-2xl overflow-hidden hover:border-blue-500/50 transition-all duration-300 flex flex-col justify-between">
                        <div class="p-5">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-[10px] font-black uppercase bg-blue-500/10 text-blue-400 px-2.5 py-1 rounded-md border border-blue-500/20">
                                    {{ $roadmap->category ?? 'Technology' }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-semibold">
                                    ● {{ ucfirst($roadmap->level ?? 'All') }}
                                </span>
                            </div>

                            <h3 class="text-lg font-bold text-white group-hover:text-blue-400 transition-colors line-clamp-1">
                                {{ $roadmap->title }}
                            </h3>

                            <p class="text-slate-400 text-xs mt-2 line-clamp-2 leading-relaxed">
                                {{ $roadmap->description }}
                            </p>

                            <div class="grid grid-cols-2 gap-2 mt-4 pt-4 border-t border-slate-800/60 text-[11px] text-slate-400">
                                <div class="flex items-center gap-1.5">
                                    <span>📦</span> <strong>{{ $roadmap->sections_count ?? 0 }}</strong> Chặng (Stages)
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span>📖</span> <strong>{{ $roadmap->lessons_count ?? 0 }}</strong> Bài học (Lessons)
                                </div>
                            </div>
                        </div>

                        <div class="p-5 bg-slate-950/40 border-t border-slate-800/50 flex items-center justify-between">
                            <span class="text-[11px] text-slate-500 font-medium">⭐⭐⭐⭐⭐ (5.0)</span>
                            <a href="{{ route('learning.roadmaps.show', $roadmap->id) }}" class="inline-flex items-center gap-1 bg-slate-900 border border-slate-800 hover:bg-blue-600 hover:border-blue-500 text-white rounded-xl px-4 py-2 text-xs font-bold transition-all">
                                Chi tiết <span>→</span>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-2 bg-slate-900/10 border border-slate-800 text-center py-16 rounded-2xl w-full">
                        <h4 class="text-sm font-bold text-white mb-1">Không tìm thấy lộ trình phù hợp</h4>
                        <p class="text-slate-500 text-xs max-w-xs mx-auto">Vui lòng thay đổi từ khóa tìm kiếm hoặc bộ lọc.</p>
                    </div>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $roadmaps->appends(request()->query())->links() }}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection