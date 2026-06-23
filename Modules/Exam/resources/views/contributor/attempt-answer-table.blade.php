@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <x-notifications z-index="z-50" />

        {{-- Flash Messages --}}
        {{-- @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        </div>
        @endif --}}

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- Nav --}}
        <nav class="mb-4 flex items-center gap-2 text-sm text-gray-500">

            <a href="{{ route('contributor.exams') }}" href="#" class="hover:text-gray-700">
                Danh sách đề thi
            </a>

            <span>/</span>

            <a href="{{ route('contributor.exams.detail', ['examId' => $examAttempt->exam->id]) }}"
                class="hover:text-gray-700">
                {{ $examAttempt->exam->title }}
            </a>

            <span>/</span>

            <a href="{{ route('contributor.exams.attempts', ['examId' => $examAttempt->exam->id]) }}"
                class="hover:text-gray-700">
                Lịch sử làm bài
            </a>

            <span>/</span>

            <span class="text-gray-900">
                Attempt #{{ $examAttempt->id }}
            </span>

        </nav>

        {{-- Header --}}
        <div class="flex items-start justify-between">

            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Exam Attempt Detail
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Bài kiểm tra: <span class="font-medium">{{ $examAttempt?->exam?->title }}</span>
                    • Người làm: <span class="font-medium">{{ $examAttempt?->user?->name }}</span>
                </p>

                <p class="text-sm text-gray-500">
                    Attempt #{{ $examAttempt->id }}
                    • Trạng thái: <span class="font-semibold">
                        {{
                            match ($examAttempt->status) {
                                'in_progress' => 'Đang làm bài',
                                'submitted' => 'Đã nộp bài',
                                // 'graded' => 'Đã chấm',
                                'canceled' => 'Đã hủy',
                                'completed' => 'Đã chấm',
                                default => $examAttempt->status,
                            }
                        }}
                    </span>
                </p>
            </div>

        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <div class="p-4 bg-white border rounded-lg shadow-sm">
                <div class="text-sm text-gray-500">Tổng điểm</div>
                <div class="text-2xl font-bold text-gray-900">
                    {{ $examAttempt->score ?? 0 }}
                </div>
            </div>

            <div class="p-4 bg-white border rounded-lg shadow-sm">
                <div class="text-sm text-gray-500">Tỷ lệ</div>
                <div class="text-2xl font-bold text-blue-600">
                    {{ $examAttempt->percent_score ?? 0 }}%
                </div>
            </div>

            <div class="p-4 bg-white border rounded-lg shadow-sm">
                <div class="text-sm text-gray-500">Câu đúng</div>
                <div class="text-2xl font-bold text-green-600">
                    {{ $examAttempt->correct_answers ?? 0 }}
                </div>
            </div>

            <div class="p-4 bg-white border rounded-lg shadow-sm">
                <div class="text-sm text-gray-500">Câu sai</div>
                <div class="text-2xl font-bold text-red-600">
                    {{ $examAttempt->wrong_answers ?? 0 }}
                </div>
            </div>

        </div>

        {{-- Meta Info --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">

            <div class="p-3 bg-gray-50 rounded-lg">
                <span class="text-gray-500">Thời gian bắt đầu:</span>
                <span class="font-medium">
                    {{ $examAttempt->started_at?->format('d/m/Y H:i') }}
                </span>
            </div>

            <div class="p-3 bg-gray-50 rounded-lg">
                <span class="text-gray-500">Thời gian nộp bài:</span>
                <span class="font-medium">
                    {{ $examAttempt->submitted_at?->format('d/m/Y H:i') ?? '—' }}
                </span>
            </div>

            <div class="p-3 bg-gray-50 rounded-lg">
                <span class="text-gray-500">Trạng thái:</span>
                <span class="font-semibold">
                     {{
                        match ($examAttempt->status) {
                            'in_progress' => 'Đang làm bài',
                            'submitted' => 'Đã nộp bài',
                            'canceled' => 'Đã hủy',
                            'completed' => 'Đã chấm',
                            default => $examAttempt->status,
                        }
                    }}
                </span>
            </div>

        </div>

        {{-- Finalize Button --}}
        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 border-2 border-purple-200 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-purple-900">Chốt kết quả chấm bài</h3>
                    <p class="text-sm text-purple-700 mt-1">
                        Tính toán lại điểm số và chuyển trạng thái thành "Đã nộp"
                    </p>
                </div>
                <form action="{{ route('contributor.exams.attempts.finalize', ['attemptId' => $examAttempt->id]) }}"
                    method="POST">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200"
                        {{--
                        onclick="return confirm('Bạn có chắc chắn muốn chốt kết quả? Hệ thống sẽ tính toán lại tất cả điểm số.')">
                        --}}
                        >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Chốt kết quả
                    </button>
                </form>
            </div>
        </div>

        {{-- Answers Table --}}
        <div class="bg-white border rounded-xl shadow-sm p-4">

            {{-- <div class="mb-3">
                <h2 class="text-lg font-semibold text-gray-800">
                    Answers
                </h2>
            </div> --}}

            {{-- PowerGrid Table --}}
            <livewire:modules.exam.livewire.contributor.attempt-answer-table :attempt-id="$examAttempt->id" />

        </div>

        {{-- Answer Detail Modal --}}
        <livewire:modules.exam.livewire.contributor.attempt-answer-modal />

    </div>
@endsection