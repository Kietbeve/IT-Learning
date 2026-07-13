@extends('layouts.contributor')

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
                {{-- Form modal nhận xét chung --}}
                <form x-ref="finalizeForm" method="POST"
                    action="{{ route('contributor.exams.attempts.finalize', ['attemptId' => $examAttempt->id]) }}"
                    x-data="{ confirmModal: false, submitting: false }">
                    @csrf

                    <x-button type="button" purple label="Chốt kết quả" @click="
                        confirmModal = true;
                        $nextTick(() => $refs.teacherComment.focus())
                        " />

                    <div x-show="confirmModal" x-cloak class="fixed inset-0 z-50" style="display:none;">
                        <div class="fixed inset-0 bg-black/50" @click="confirmModal = false"></div>

                        <div class="relative flex min-h-screen items-center justify-center p-4">
                            <div @click.stop class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl">
                                {{-- Header --}}
                                <div class="flex items-start gap-4 p-6 border-b">

                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100">
                                        <x-icon name="check-badge" class="w-6 h-6 text-purple-600" />
                                    </div>

                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-900">
                                            Chốt kết quả bài thi
                                        </h3>

                                        <p class="mt-1 text-sm text-gray-500">
                                            Hệ thống sẽ tính điểm, cập nhật trạng thái và gửi email kết quả
                                            cho thí sinh.
                                        </p>
                                    </div>

                                </div>

                                {{-- Body --}}
                                <div class="p-6 space-y-5">

                                    <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3">
                                        <p class="text-sm text-amber-700">
                                            Sau khi xác nhận, thao tác này không thể hoàn tác.
                                        </p>
                                    </div>

                                    <div>
                                        <label for="teacher_comment" class="mb-2 block text-sm font-medium text-gray-700">
                                            Nhận xét của giáo viên
                                            <span class="text-gray-400 font-normal">(không bắt buộc)</span>
                                        </label>

                                        <textarea x-ref="teacherComment" id="teacher_comment" name="teacher_comment"
                                            rows="5" maxlength="1000"
                                            class="w-full rounded-lg border-gray-300 p-3 text-sm focus:border-purple-500 focus:ring-purple-500"
                                            placeholder="Ví dụ: Em làm bài khá tốt, tuy nhiên cần ôn tập thêm phần Cấu trúc dữ liệu và Giải thuật...">{{ old('teacher_comment', $examAttempt->teacher_comment) }}</textarea>

                                        <p class="mt-2 text-xs text-gray-500">
                                            Nhận xét sẽ hiển thị trong kết quả và email gửi đến thí sinh.
                                        </p>
                                    </div>

                                </div>

                                {{-- Button --}}
                                <div class="flex justify-end gap-3 border-t bg-gray-50 px-6 py-4">

                                    <x-button flat gray type="button" label="Hủy" @click="confirmModal = false" />

                                    <button type="button"
                                        class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-5 py-2.5 text-white transition hover:bg-purple-700 disabled:opacity-50"
                                        :disabled="submitting" @click="submitting = true; $refs.finalizeForm.submit()">
                                        <svg x-show="submitting" class="h-4 w-4 animate-spin"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0A12 12 0 000 12h4z"></path>
                                        </svg>

                                        <x-icon x-show="!submitting" name="check" class="w-4 h-4" />

                                        <span x-text="submitting ? 'Đang xử lý...' : 'Xác nhận chốt kết quả'"></span>
                                    </button>

                                </div>

                            </div>
                        </div>
                    </div>
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