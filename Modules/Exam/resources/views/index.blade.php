@extends('layouts.user')

@section('content')
    <div class="max-w-7xl mx-auto py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Danh sách bài thi</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @include('exam::patials.exam_card')
            @include('exam::patials.exam_card')
            @include('exam::patials.exam_card')
            @include('exam::patials.exam_card')
            @include('exam::patials.exam_card')
            @include('exam::patials.exam_card')
        </div>
    </div>
@endsection