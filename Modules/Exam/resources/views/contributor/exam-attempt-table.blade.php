@extends('layouts.contributor')

@section('content')
<div class="space-y-6">

    {{-- Nav --}}
     <nav class="mb-4 flex items-center gap-2 text-sm text-gray-500">

        <a
            href="{{ route('contributor.exams') }}"
            href="#"
            class="hover:text-gray-700"
        >
            Danh sách đề thi
        </a>

        <span>/</span>

        <a
            href="{{ route('contributor.exams.detail', ['examId' => $exam->id]) }}"
            class="hover:text-gray-700"
        >
            {{ $exam->title }}
        </a>

        <span>/</span>

        <a
            href="{{ route('contributor.exams.attempts', ['examId' => $exam->id]) }}"
            class="hover:text-gray-700"
        >
            Lịch sử làm bài
        </a>
    </nav>

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">
            Quản lý lượt làm bài
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Theo dõi và quản lý kết quả làm bài của người dùng.
        </p>
    </div>

    {{-- Statistics --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">
                Tổng lượt làm bài
            </p>

            <p class="mt-3 text-3xl font-bold text-gray-900">
                {{ number_format($stats['total_attempts'] ?? 0) }}
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">
                Đã nộp bài
            </p>

            <p class="mt-3 text-3xl font-bold text-green-600">
                {{ number_format($stats['submitted_attempts'] ?? 0) }}
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">
                Tỷ lệ đạt
            </p>

            <p class="mt-3 text-3xl font-bold text-blue-600">
                {{ $stats['pass_rate'] ?? 0 }}%
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">
                Điểm trung bình
            </p>

            <p class="mt-3 text-3xl font-bold text-purple-600">
                {{ $stats['average_score'] ?? 0 }}%
            </p>
        </div>

    </div>

    {{-- Table --}}
    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">

        <livewire:modules.exam.livewire.contributor.exam-attempt-table />

    </div>

</div>
@endsection