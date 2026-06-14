@extends('layouts.user')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-blue-600">Danh sách lộ trình học tập</h1>
        <p class="text-gray-600 mt-2">Khám phá các lộ trình học tập dành cho sinh viên công nghệ thông tin</p>
    </div>

    <div class="mb-8">
        <input type="text" placeholder="Tìm kiếm lộ trình..." class="w-full border border-blue-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($roadmaps as $roadmap)
            <div class="bg-white border border-blue-100 rounded-xl overflow-hidden shadow-sm">
                <div class="h-40 bg-blue-100 flex items-center justify-center text-blue-300 font-bold">Image Placeholder</div>

                <div class="p-5">
                    <h3 class="text-xl font-semibold text-blue-600 mb-2">
                        {{ $roadmap->title }}
                    </h3>

                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                        {{ $roadmap->short_description }}
                    </p>

                    <div class="flex justify-between text-sm text-gray-500 mb-4">
                        <span>Tác giả: <b>{{ $roadmap->author->name ?? 'Hệ thống' }}</b></span>
                    </div>

                    <a href="{{ route('learning.roadmaps.show', $roadmap->id) }}"
                        class="block text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg">
                        Xem chi tiết
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-gray-500">
                Chưa có lộ trình nào được xuất bản.
            </div>
        @endforelse
    </div>
</div>
@endsection