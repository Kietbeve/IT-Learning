@extends('layouts.user')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="mb-4">
        <button onclick="history.back()"
                class="flex items-center gap-2 text-sm text-gray-600 hover:text-cyan-700 transition-colors">
            ← Quay lại
        </button>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl p-5 sm:p-6 shadow-sm">
        @livewire('modules.learning.livewire.forum.thread-list', [
            'threadableType' => $type,
            'threadableId' => $id,
        ])
    </div>
</div>
@endsection
