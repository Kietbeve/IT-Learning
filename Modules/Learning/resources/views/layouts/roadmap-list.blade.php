@extends('learning::layouts.master')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 min-h-screen bg-slate-50 text-slate-800">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-blue-600">
            Danh sách lộ trình học tập
        </h1>
        <p class="text-gray-600 mt-2">
            Khám phá các lộ trình học tập chuẩn đầu ra dành cho học viên công nghệ.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($roadmaps as $roadmap)
            <div class="bg-white border border-blue-100 rounded-xl overflow-hidden shadow-sm flex flex-col justify-between">
                <div>
                    <div class="h-40 bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center p-4">
                        <span class="text-white font-bold text-lg text-center tracking-wide">{{ $roadmap->title }}</span>
                    </div>

                    <div class="p-5">
                        <h3 class="text-xl font-bold text-slate-800 mb-2 line-clamp-1">
                            {{ $roadmap->title }}
                        </h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                            {{ $roadmap->short_description }}
                        </p>
                        <div class="text-xs text-slate-500 mb-4 flex items-center gap-2">
                            <span>Tác giả: <strong>{{ $roadmap->author->name ?? 'Hệ thống' }}</strong></span>
                        </div>
                    </div>
                </div>

                <div class="p-5 pt-0">
                    <a href="{{ route('learning.roadmaps.show', $roadmap->id) }}"
                        class="block text-center bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-semibold transition">
                        Xem chi tiết lộ trình
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-gray-500">
                Hiện tại chưa có lộ trình nào được phê duyệt công khai.
            </div>
        @endforelse
    </div>
</div>
@endsection