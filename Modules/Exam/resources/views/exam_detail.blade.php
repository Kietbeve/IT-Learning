@extends('layouts.user')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

    {{-- Main Content --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Header Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sm:p-8">

            <div class="flex flex-wrap items-center gap-2 mb-4">
                <x-badge flat primary :label="$exam->category->name" />

                <x-badge
                    flat
                    gray
                    :label="ucfirst($exam->type)"
                />
            </div>

            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4 leading-tight">
                {{ $exam->title }}
            </h1>

            <p class="text-base font-normal text-gray-600 mb-6 leading-relaxed">
                {{ $exam->description }}
            </p>

            <div class="flex items-center gap-3 border-t border-gray-100 pt-5">
                <div class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center text-base font-bold text-primary-700">
                    {{ strtoupper(substr($exam->author->name, 0, 1)) }}
                </div>

                <div>
                    <p class="text-base font-medium text-gray-900">
                        {{ $exam->author->name }}
                    </p>

                    <p class="text-sm font-normal text-gray-500">
                        Tác giả bài thi
                    </p>
                </div>
            </div>

        </div>

        {{-- Detail Section --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            <div class="border-b border-gray-200 bg-gray-50/50">
                <div class="py-4 px-6 border-b-2 border-primary-600 font-medium text-base text-primary-600">
                    Thông tin bài thi
                </div>
            </div>

            <div class="p-5 sm:p-8">

                <h3 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-4">
                    Mô tả chi tiết
                </h3>

                <p class="text-base text-gray-600 leading-relaxed mb-8">
                    {{ $exam->description }}
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="flex items-center gap-3">
                        <x-icon name="user" class="w-6 h-6 text-indigo-600" />
                        <div>
                            <p class="text-sm text-gray-500">Tác giả</p>
                            <p class="font-semibold text-gray-900">
                                {{ $exam->author->name }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <x-icon name="folder" class="w-6 h-6 text-indigo-600" />
                        <div>
                            <p class="text-sm text-gray-500">Danh mục</p>
                            <p class="font-semibold text-gray-900">
                                {{ $exam->category->name }}
                            </p>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Sidebar --}}
    <div class="lg:col-span-1">

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">

            <div class="h-48 bg-blue-800 flex items-center justify-center text-white relative">

                <x-icon
                    name="clipboard-document-check"
                    class="w-20 h-20 opacity-75"
                />

                <div class="absolute bottom-3 left-3">
                    <x-badge positive label="Sẵn sàng làm bài" />
                </div>

            </div>

            <div class="p-6">

                <h3 class="text-2xl font-bold text-gray-900 mb-6">
                    Thông tin nhanh
                </h3>

                <div class="space-y-4 mb-8">

                    <div class="flex items-center justify-between text-base">
                        <div class="flex items-center gap-2 text-gray-500">
                            <x-icon name="clock" class="w-5 h-5" />
                            Thời gian làm bài
                        </div>

                        <span class="font-semibold text-gray-900">
                            {{ $exam->duration_minutes }} phút
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-base">
                        <div class="flex items-center gap-2 text-gray-500">
                            <x-icon name="question-mark-circle" class="w-5 h-5" />
                            Số câu hỏi
                        </div>

                        <span class="font-semibold text-gray-900">
                            {{ $exam->questions_count }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-base">
                        <div class="flex items-center gap-2 text-gray-500">
                            <x-icon name="check-circle" class="w-5 h-5" />
                            Điểm đạt
                        </div>

                        <span class="font-semibold text-gray-900">
                            {{ rtrim(rtrim($exam->pass_percent, '0'), '.') }}%
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-base">
                        <div class="flex items-center gap-2 text-gray-500">
                            <x-icon name="academic-cap" class="w-5 h-5" />
                            Hình thức
                        </div>

                        <span class="font-semibold text-gray-900">
                            {{ ucfirst($exam->type) }}
                        </span>
                    </div>

                </div>

                <div class="space-y-3">

                    <x-button
                        indigo
                        xl
                        right-icon="arrow-right"
                        class="w-full flex justify-center"
                        label="Bắt đầu làm bài"
                    />

                    <x-button
                        outline
                        gray
                        xl
                        icon="bookmark"
                        class="w-full flex justify-center"
                        label="Lưu bài thi"
                    />

                </div>

            </div>

            <div class="bg-gray-50 p-4 border-t border-gray-100 flex items-start gap-3">

                <x-icon
                    name="information-circle"
                    class="w-6 h-6 text-blue-600 shrink-0"
                />

                <div>
                    <p class="text-sm font-semibold text-gray-900">
                        Lưu ý
                    </p>

                    <p class="text-xs font-normal text-gray-500 mt-0.5">
                        Hoàn thành tối thiểu {{ rtrim(rtrim($exam->pass_percent, '0'), '.') }}%
                        số điểm để vượt qua bài thi.
                    </p>
                </div>

            </div>

        </div>

    </div>

</div>
```

</div>

@endsection
