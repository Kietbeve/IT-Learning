@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        {{-- Nav --}}
        <nav class="mb-4 flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('contributor.exams') }}" class="hover:text-gray-700">
                Danh sách đề thi
            </a>
            <span>/</span>
            <a href="{{ route('contributor.exams.detail', $exam->id) }}" class="hover:text-gray-700">
                {{ $exam->title }}
            </a>
            <span>/</span>
            <span class="text-gray-900">
                Quản lý câu hỏi
            </span>
        </nav>

        {{-- Header --}}
        <div class="rounded-lg border bg-white p-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">
                        {{ $exam->title }}
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $exam->code }}
                    </p>

                    <div class="mt-3 flex gap-4 text-sm text-gray-600">
                        <span>{{ $exam->questions()->count() }} câu hỏi</span>
                        <span>{{ $exam->duration }} phút</span>
                        <span>Passing: {{ $exam->passing_score }}%</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-4 gap-4">

            <div class="rounded-lg border bg-white p-4">
                <p class="text-sm text-gray-500">Tổng câu hỏi</p>
                <p class="text-2xl font-bold">
                    {{ $exam->questions()->count() }}
                </p>
            </div>

            <div class="rounded-lg border bg-white p-4">
                <p class="text-sm text-gray-500">Trắc nghiệm</p>
                <p class="text-2xl font-bold">
                    {{ $stats['multiple_choice'] + $stats['single_choice'] }}
                </p>
            </div>

            <div class="rounded-lg border bg-white p-4">
                <p class="text-sm text-gray-500">Tự luận</p>
                <p class="text-2xl font-bold">
                    {{ $stats['essay'] }}
                </p>
            </div>

            <div class="rounded-lg border bg-white p-4">
                <p class="text-sm text-gray-500">Tổng điểm</p>
                <p class="text-2xl font-bold">
                    {{ $stats['total_score'] }}
                </p>
            </div>

        </div>

        {{-- PowerGrid --}}
        <div>
            <div class="rounded-lg border bg-white p-4">
                <livewire:modules.exam.livewire.contributor.exam-question-table :exam-id="$exam->id" />
            </div>

            <livewire:modules.exam.livewire.contributor.exam-question-modal :exam-id="$exam->id" />

            <livewire:modules.exam.livewire.contributor.question-modal />
        </div>
    </div>
@endsection