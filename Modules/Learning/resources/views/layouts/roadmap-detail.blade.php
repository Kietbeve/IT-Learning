@extends('layouts.user')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="flex flex-col md:flex-row md:justify-between md:items-start border-b border-blue-100 pb-6 mb-8 gap-4">
        <div class="flex-1">
            <a href="{{ route('learning.roadmaps.index') }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                Quay lại danh sách
            </a>
            <h1 class="text-3xl font-bold text-blue-600 mb-2">
                {{ $roadmapTitle }}
            </h1>
            
            @if($roadmap)
                <div class="flex gap-2 mb-3">
                    @if($roadmap->level)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst($roadmap->level) }}
                        </span>
                    @endif
                    @if($roadmap->category)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                            {{ $roadmap->category }}
                        </span>
                    @endif
                </div>
                @if($roadmap->description)
                    <p class="text-gray-600 text-sm mt-2">{{ $roadmap->description }}</p>
                @endif
            @else
                <p class="text-gray-500 text-sm mt-1">Mã lộ trình: #{{ $id }}</p>
            @endif
        </div>

        @if($isEnrolled)
            <div class="bg-white border border-blue-100 rounded-xl p-4 shadow-sm min-w-[280px]">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-semibold text-gray-700">Tiến độ học tập</span>
                    <span class="text-sm font-bold text-blue-600">{{ $progressPercent }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5">
                    <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
                </div>
                @php
                    // Tính số chương hoàn thành (section-based progress)
                    $totalSectionsCount = 0;
                    $completedSectionsCount = 0;
                    $totalLessonsCount = count($lessons);
                    $completedLessonsCount = collect($lessonProgress)->where('status', 'completed')->count();
                    
                    // Group lessons by section to count completed sections
                    $lessonsBySection = collect($lessons)->groupBy('section_id');
                    $totalSectionsCount = $lessonsBySection->count();
                    
                    foreach ($lessonsBySection as $sectionId => $sectionLessons) {
                        $allComplete = true;
                        foreach ($sectionLessons as $lesson) {
                            $lessonId = $lesson['id'];
                            if (!isset($lessonProgress[$lessonId]) || $lessonProgress[$lessonId]['status'] !== 'completed') {
                                $allComplete = false;
                                break;
                            }
                        }
                        if ($allComplete) {
                            $completedSectionsCount++;
                        }
                    }
                @endphp
                <p class="text-xs text-gray-400 mt-1.5 text-right">
                    {{ $completedSectionsCount }}/{{ $totalSectionsCount }} chương • {{ $completedLessonsCount }}/{{ $totalLessonsCount }} bài học
                </p>
            </div>
        @else
            <div class="bg-white border border-blue-100 rounded-xl p-4 shadow-sm min-w-[280px]">
                @auth
                    <form action="{{ route('learning.roadmaps.enroll', $id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-150 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                            Đăng ký học ngay
                        </button>
                    </form>
                    <p class="text-xs text-gray-500 text-center mt-2">Miễn phí - {{ count($lessons) }} bài học</p>
                @else
                    <a href="{{ route('login') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-150 text-center">
                        Đăng nhập để học
                    </a>
                @endauth
            </div>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="space-y-4">
        @if(isset($lessons) && count($lessons) > 0)
            @foreach($lessons as $index => $lesson)
                @php
                    $lessonId = $lesson['id'];
                    $progress = $lessonProgress[$lessonId] ?? null;
                    $isCompleted = $progress && $progress['status'] === 'completed';
                @endphp

                <div class="bg-white border border-blue-50 hover:border-blue-300 rounded-xl p-5 shadow-sm hover:shadow-md transition duration-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    
                    <div class="flex items-start gap-4 flex-1">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-50 text-blue-600 font-bold rounded-lg flex items-center justify-center text-sm border border-blue-100">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-800 hover:text-blue-600 transition">
                                {{ $lesson['title'] }}
                            </h3>
                            <div class="flex items-center gap-2 mt-1">
                                <p class="text-gray-400 text-xs flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    45 phút
                                </p>
                                @if(isset($lesson['lesson_type']))
                                    <span class="text-xs text-gray-400">• {{ ucfirst($lesson['lesson_type']) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between sm:justify-end gap-4 border-t sm:border-t-0 pt-3 sm:pt-0">
                        
                        <div>
                            @if($isCompleted)
                                <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-3 py-1 rounded-full text-xs font-medium border border-green-200">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    Hoàn thành
                                </span>
                            @elseif($progress && $progress['status'] === 'in_progress')
                                <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-medium border border-blue-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                    Đang học
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-gray-50 text-gray-700 px-3 py-1 rounded-full text-xs font-medium border border-gray-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                    Chưa học
                                </span>
                            @endif
                        </div>

                        @if($isEnrolled)
                            <a href="{{ route('learning.roadmaps.learn', [$id, $lessonId]) }}" 
                               class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-4 py-2 rounded-lg shadow-sm transition duration-150">
                                Xem chi tiết
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        @else
                            <a href="{{ route('learning.lessons.show', ['roadmap_id' => $id, 'lesson_id' => $lessonId]) }}" 
                               class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-4 py-2 rounded-lg shadow-sm transition duration-150">
                                Xem chi tiết
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        @endif

                    </div>
                </div>
            @endforeach
        @else
            <div class="bg-white border border-blue-50 rounded-xl p-8 text-center text-gray-400 shadow-sm">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                Chưa có bài học nào trong lộ trình này.
            </div>
        @endif
    </div>

</div>
@endsection