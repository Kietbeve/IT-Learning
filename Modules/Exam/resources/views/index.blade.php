@extends('layouts.user')

@section('content')
    <div class="max-w-7xl mx-auto pb-8 pt-4">
        <!-- Search Bar -->
        <div class="mb-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-3 rounded-2xl shadow-sm border border-gray-200 flex items-center gap-3">
                <div class="flex-1">
                    <x-input icon="magnifying-glass" placeholder="Nhập tên bài thi, danh mục để tìm kiếm..." />
                </div>
                <x-button indigo label="Tìm kiếm" icon="magnifying-glass" class="hidden sm:flex" />
            </div>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-6 px-4 sm:px-6 lg:px-8">Danh sách bài thi</h1>

        <div class="flex flex-col gap-4 px-4 sm:px-6 lg:px-8">
            @include('exam::patials.exam_card')
            @include('exam::patials.exam_card')
            @include('exam::patials.exam_card')
            @include('exam::patials.exam_card')
            @include('exam::patials.exam_card')
            @include('exam::patials.exam_card')
        </div>
    </div>
@endsection