@extends('layouts.admin')
@section('content')
    <div class="space-y-6">
        <nav class="mb-4 flex items-center gap-2 text-sm text-gray-500">
    <a
        href="{{ route('contributor.exams') }}"
        class="hover:text-gray-700"
    >
        Exams
    </a>

    <span>/</span>

    <span class="text-gray-900">
        {{ $exam->title }}
    </span>
</nav>
        {{-- Header --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">
                        {{ $exam->title }}
                    </h1>

                    <p class="mt-2 text-sm text-gray-500">
                        {{ $exam->description ?: 'Không có mô tả' }}
                    </p>
                </div>

                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                    {{ $exam->status }}
                </span>

            </div>

        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">

            <div class="rounded-xl border bg-white p-4 shadow-sm">
                <div class="text-sm text-gray-500">Questions</div>
                <div class="mt-2 text-2xl font-bold">
                    {{ $exam->questions()->count() }}
                </div>
            </div>

            <div class="rounded-xl border bg-white p-4 shadow-sm">
                <div class="text-sm text-gray-500">Attempts</div>
                <div class="mt-2 text-2xl font-bold">
                    {{ $exam->attempts()->count() }}
                </div>
            </div>

            <div class="rounded-xl border bg-white p-4 shadow-sm">
                <div class="text-sm text-gray-500">Passing Score</div>
                <div class="mt-2 text-2xl font-bold">
                    {{ $exam->passing_score }}%
                </div>
            </div>

            <div class="rounded-xl border bg-white p-4 shadow-sm">
                <div class="text-sm text-gray-500">Max Attempts</div>
                <div class="mt-2 text-2xl font-bold">
                    {{ $exam->max_attempts }}
                </div>
            </div>

        </div>

        {{-- Quick Actions --}}
        <div class="grid gap-4 md:grid-cols-2">

            <a href="{{ route('contributor.exams.questions', $exam->id) }}"
              {{-- <a href="#" --}}
                class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-blue-500">
                <div class="font-semibold">
                    Manage Questions
                </div>

                <div class="mt-1 text-sm text-gray-500">
                    View and manage exam questions
                </div>
            </a>

            <a href="{{ route('contributor.exams.attempts', $exam->id) }}"
              {{-- <a href="#" --}}
                class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-blue-500">
                <div class="font-semibold">
                    Manage Attempts
                </div>

                <div class="mt-1 text-sm text-gray-500">
                    View participant attempts
                </div>
            </a>

        </div>

        {{-- General Information --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b px-6 py-4">
                <h2 class="font-semibold text-gray-900">
                    General Information
                </h2>
            </div>

            <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">

                <div>
                    <div class="text-sm text-gray-500">Type</div>
                    <div class="mt-1 font-medium">
                        {{ ucfirst(str_replace('_', ' ', $exam->type)) }}
                    </div>
                </div>

                <div>
                    <div class="text-sm text-gray-500">Duration</div>
                    <div class="mt-1 font-medium">
                        {{ $exam->duration }} minutes
                    </div>
                </div>

                <div>
                    <div class="text-sm text-gray-500">Status</div>
                    <div class="mt-1 font-medium">
                        {{ $exam->status }}
                    </div>
                </div>

                <div>
                    <div class="text-sm text-gray-500">Passing Score</div>
                    <div class="mt-1 font-medium">
                        {{ $exam->passing_score }}%
                    </div>
                </div>

                <div>
                    <div class="text-sm text-gray-500">Created At</div>
                    <div class="mt-1 font-medium">
                        {{ $exam->created_at?->format('d/m/Y H:i') }}
                    </div>
                </div>

                <div>
                    <div class="text-sm text-gray-500">Updated At</div>
                    <div class="mt-1 font-medium">
                        {{ $exam->updated_at?->format('d/m/Y H:i') }}
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection