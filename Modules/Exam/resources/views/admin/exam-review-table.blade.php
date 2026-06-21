@extends("layouts.admin")

@section('content')
    <div class="max-w-7xl mx-auto p-6">

        <livewire:modules.exam.livewire.admin.exam-review-table />

        <livewire:modules.exam.livewire.admin.exam-review-modal />
    </div>
@endsection
