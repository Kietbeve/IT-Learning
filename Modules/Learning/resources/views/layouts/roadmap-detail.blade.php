@extends('layouts.user')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="flex flex-col md:flex-row md:justify-between md:items-center border-b border-blue-100 pb-6 mb-8 gap-4">
        <div>
            <a href="{{ route('learning.roadmaps.index') }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                Quay lại danh sách
            </a>
            <h1 class="text-3xl font-bold text-blue-600">
                Chi tiết Lộ trình Học tập
            </h1>
            <p class="text-gray-500 text-sm mt-1">Mã lộ trình của bạn: #{{ $id }}</p>
        </div>

        <div class="bg-white border border-blue-100 rounded-xl p-4 shadow-sm min-w-[280px]">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-semibold text-gray-700">Tiến độ học tập</span>
                <span class="text-sm font-bold text-blue-600">40%</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2.5">
                <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500" style="width: 40%"></div>
            </div>
            <p class="text-xs text-gray-400 mt-1.5 text-right">Đã hoàn thành 2/5 bài học</p>
        </div>
    </div>

    <div class="space-y-4">
        @if(isset($lessons) && count($lessons) > 0)
            @foreach($lessons as $index => $lesson)
                @php
                    // Giả lập trạng thái cho bài học để bạn xem trước giao diện:
                    // Bài 1, 2: Hoàn thành | Bài 3, 4, 5: Chưa học
                    $isCompleted = ($index < 2); 
                @endphp

                <div class="bg-white border border-blue-50 hover:border-blue-300 rounded-xl p-5 shadow-sm hover:shadow-md transition duration-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-50 text-blue-600 font-bold rounded-lg flex items-center justify-center text-sm border border-blue-100">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 hover:text-blue-600 transition">
                                {{ $lesson['title'] }}
                            </h3>
                            <p class="text-gray-400 text-xs mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Thời lượng ước tính: 45 phút
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between sm:justify-end gap-4 border-t sm:border-t-0 pt-3 sm:pt-0">
                        
                        <div>
                            @if($isCompleted)
                                <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-3 py-1 rounded-full text-xs font-medium border border-green-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Hoàn thành
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 px-3 py-1 rounded-full text-xs font-medium border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Chưa học
                                </span>
                            @endif
                        </div>

                        <a href="{{ route('learning.lessons.show', ['roadmap_id' => $id, 'lesson_id' => $lesson['id']]) }}" 
                           class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-4 py-2 rounded-lg shadow-sm transition duration-150">
                            {{ $isCompleted ? 'Học lại' : 'Vào học' }}
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                        </a>

                    </div>
                </div>
            @endforeach
        @else
            <div class="bg-white border border-blue-50 rounded-xl p-8 text-center text-gray-400 shadow-sm">
                Không tìm thấy bài học nào cho lộ trình này.
            </div>
        @endif
    </div>

</div>
@endsection