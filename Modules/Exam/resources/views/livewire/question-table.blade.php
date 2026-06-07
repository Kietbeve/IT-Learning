@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
            <div class="mb-5 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Quản lý câu hỏi</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Danh sách toàn bộ câu hỏi trong hệ thống.</p>
                </div>
            </div>

            <livewire:modules.exam.livewire.question-table />
            <livewire:modules.exam.livewire.question-modal />
        </div>
    </div>
@endsection