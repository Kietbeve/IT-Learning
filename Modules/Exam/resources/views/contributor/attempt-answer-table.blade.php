@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <x-notifications z-index="z-50" />
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
            href="{{ route('contributor.exams.detail', ['examId' => $examAttempt->exam->id]) }}"
            class="hover:text-gray-700"
        >
            {{ $examAttempt->exam->title }}
        </a>

        <span>/</span>

        <a
            href="{{ route('contributor.exams.attempts', ['examId' => $examAttempt->exam->id]) }}"
            class="hover:text-gray-700"
        >
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
                Exam: <span class="font-medium">{{ $examAttempt?->exam?->title }}</span>
                • User: <span class="font-medium">{{ $examAttempt?->user?->name }}</span>
            </p>

            <p class="text-sm text-gray-500">
                Attempt #{{ $examAttempt->id }}
                • Status: <span class="font-semibold">{{ $examAttempt->status }}</span>
            </p>
        </div>

    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <div class="p-4 bg-white border rounded-lg shadow-sm">
            <div class="text-sm text-gray-500">Score</div>
            <div class="text-2xl font-bold text-gray-900">
                {{ $examAttempt->score ?? 0 }}
            </div>
        </div>

        <div class="p-4 bg-white border rounded-lg shadow-sm">
            <div class="text-sm text-gray-500">Percent</div>
            <div class="text-2xl font-bold text-blue-600">
                {{ $examAttempt->percent_score ?? 0 }}%
            </div>
        </div>

        <div class="p-4 bg-white border rounded-lg shadow-sm">
            <div class="text-sm text-gray-500">Correct</div>
            <div class="text-2xl font-bold text-green-600">
                {{ $examAttempt->correct_answers ?? 0 }}
            </div>
        </div>

        <div class="p-4 bg-white border rounded-lg shadow-sm">
            <div class="text-sm text-gray-500">Wrong</div>
            <div class="text-2xl font-bold text-red-600">
                {{ $examAttempt->wrong_answers ?? 0 }}
            </div>
        </div>

    </div>

    {{-- Meta Info --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">

        <div class="p-3 bg-gray-50 rounded-lg">
            <span class="text-gray-500">Started:</span>
            <span class="font-medium">
                {{ $examAttempt->started_at?->format('d/m/Y H:i') }}
            </span>
        </div>

        <div class="p-3 bg-gray-50 rounded-lg">
            <span class="text-gray-500">Submitted:</span>
            <span class="font-medium">
                {{ $examAttempt->submitted_at?->format('d/m/Y H:i') ?? '—' }}
            </span>
        </div>

        <div class="p-3 bg-gray-50 rounded-lg">
            <span class="text-gray-500">Status:</span>
            <span class="font-semibold">
                {{ ucfirst($examAttempt->status) }}
            </span>
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
        <livewire:modules.exam.livewire.contributor.attempt-answer-table
            :attempt-id="$examAttempt->id"
        />

    </div>

</div>
@endsection