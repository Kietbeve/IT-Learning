@extends('layouts.contributor')
@section('content')
    <div class="space-y-6">
        {{-- ========================================================= --}}
        {{-- Hero Header --}}
        {{-- ========================================================= --}}
        <section class="rounded-3xl bg-gradient-to-r from-indigo-600 via-indigo-800 to-purple-700 shadow-lg">
            {{-- ========================================================= --}}
            {{-- Hero Header --}}
            {{-- ========================================================= --}}
            <section
                class="overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-indigo-800 to-purple-700 shadow-xl shadow-indigo-900/20">
                <div class="flex flex-col gap-8 p-8 lg:flex-row lg:items-center lg:justify-between">
                    {{-- ================================================= --}}
                    {{-- Left --}}
                    {{-- ================================================= --}}
                    <div class="flex-1">
                        {{-- Breadcrumb --}}
                        <div class="mb-4 flex items-center gap-2 text-sm text-indigo-200">
                            <a href="{{ route('contributor.exams') }}" class="transition hover:text-white">
                                Bài kiểm tra
                            </a>
                            <x-icon name="chevron-right" class="h-4 w-4 opacity-60" />
                            <span class="font-medium text-white">
                                Chi tiết bài kiểm tra
                            </span>
                        </div>
                        {{-- Title --}}
                        <h1 class="max-w-4xl text-3xl font-bold tracking-tight text-white lg:text-4xl">
                            {{ $exam->title }}
                        </h1>
                        {{-- Description --}}
                        <p class="mt-3 max-w-3xl text-sm leading-7 text-indigo-100 lg:text-base">
                            {{ $exam->description }}
                        </p>
                    </div>
                </div>
                {{-- ========================================================= --}}
                {{-- Bottom Statistics --}}
                {{-- ========================================================= --}}
                <div
                    class="grid grid-cols-2 divide-x divide-y divide-white/10 bg-white/5 backdrop-blur lg:grid-cols-4 lg:divide-y-0">
                    {{-- Questions --}}
                    <div class="flex items-center gap-4 p-5">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10">
                            <x-icon name="document-text" class="h-6 w-6 text-white" />
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-white">
                                {{ $exam->questions()->count() }}
                            </p>
                            <p class="text-xs uppercase tracking-wide text-indigo-200">
                                Câu hỏi
                            </p>
                        </div>
                    </div>
                    {{-- Attempts --}}
                    <div class="flex items-center gap-4 p-5">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10">
                            <x-icon name="users" class="h-6 w-6 text-white" />
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-white">
                                {{ $exam->attempts()->count() }}
                            </p>
                            <p class="text-xs uppercase tracking-wide text-indigo-200">
                                Lượt làm
                            </p>
                        </div>
                    </div>
                    {{-- Passing --}}
                    <div class="flex items-center gap-4 p-5">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10">
                            <x-icon name="academic-cap" class="h-6 w-6 text-white" />
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-white">
                                {{ $exam->passing_score }}%
                            </p>
                            <p class="text-xs uppercase tracking-wide text-indigo-200">
                                Điểm đạt
                            </p>
                        </div>
                    </div>
                    {{-- Max Attempts --}}
                    <div class="flex items-center gap-4 p-5">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10">
                            <x-icon name="arrow-path" class="h-6 w-6 text-white" />
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-white">
                                {{ $exam->max_attempts }}
                            </p>
                            <p class="text-xs uppercase tracking-wide text-indigo-200">
                                Lượt tối đa
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </section>
        {{-- ========================================================= --}}
        {{-- Main Content --}}
        {{-- ========================================================= --}}
        <section class="grid grid-cols-1 gap-6 xl:grid-cols-12">
            {{-- ===================================================== --}}
            {{-- Exam Information --}}
            {{-- ===================================================== --}}
            <div class="xl:col-span-8">
                {{-- <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm"> --}}
                <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                    {{-- Header --}}
                    <div class="border-b border-gray-100 bg-gradient-to-r from-slate-50 to-indigo-50 px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-100">
                                <x-icon name="document-text" class="h-6 w-6 text-indigo-700" />
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-800">
                                    Thông tin bài kiểm tra
                                </h2>
                                <p class="text-sm text-gray-500">
                                    Thông tin tổng quan của bài kiểm tra.
                                </p>
                            </div>
                        </div>
                    </div>
                    {{-- Body --}}
                    <div class="divide-y divide-gray-100">
                        {{-- Title --}}
                        <div class="grid gap-2 px-6 py-5 md:grid-cols-3">
                            <div class="text-sm font-semibold text-gray-500">
                                Tiêu đề
                            </div>
                            <div class="md:col-span-2 text-gray-800 font-medium">
                                {{ $exam->title }}
                            </div>
                        </div>
                        {{-- Description --}}
                        <div class="grid gap-2 px-6 py-5 md:grid-cols-3">
                            <div class="text-sm font-semibold text-gray-500">
                                Mô tả
                            </div>
                            <div class="md:col-span-2 leading-7 text-gray-700">
                                {{ $exam->description }}
                            </div>
                        </div>
                        {{-- Type --}}
                        <div class="grid gap-2 px-6 py-5 md:grid-cols-3">
                            <div class="text-sm font-semibold text-gray-500">
                                Loại bài thi
                            </div>
                            <div class="md:col-span-2">
                                <span
                                    class="inline-flex rounded-full border border-indigo-200 bg-indigo-50 px-3 py-1 text-sm font-semibold text-indigo-700">
                                    {{ ucfirst(str_replace('_', ' ', $exam->type)) }}
                                </span>
                            </div>
                        </div>
                        {{-- Duration --}}
                        <div class="grid gap-2 px-6 py-5 md:grid-cols-3">
                            <div class="text-sm font-semibold text-gray-500">
                                Thời gian làm bài
                            </div>
                            <div class="md:col-span-2">
                                <span
                                    class="inline-flex items-center gap-2 rounded-full border border-sky-200 bg-sky-50 px-3 py-1 text-sm font-semibold text-sky-700">
                                    <x-icon name="clock" class="h-4 w-4" />
                                    {{ $exam->duration }} phút
                                </span>
                            </div>
                        </div>
                        {{-- Status --}}
                        <div class="grid gap-2 px-6 py-5 md:grid-cols-3">
                            <div class="text-sm font-semibold text-gray-500">
                                Trạng thái
                            </div>
                            <div class="md:col-span-2">
                                @if ($exam->status == 'approved')
                                    <span
                                        class="inline-flex rounded-full border border-green-200 bg-green-50 px-3 py-1 text-sm font-semibold text-green-700">
                                        Đã duyệt
                                    </span>
                                @elseif($exam->status == 'pending')
                                    <span
                                        class="inline-flex rounded-full border border-yellow-200 bg-yellow-50 px-3 py-1 text-sm font-semibold text-yellow-700">
                                        Chờ duyệt
                                    </span>
                                @else
                                    <span
                                        class="inline-flex rounded-full border border-red-200 bg-red-50 px-3 py-1 text-sm font-semibold text-red-700">
                                        Từ chối
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ===================================================== --}}
            {{-- Quick Actions --}}
            {{-- ===================================================== --}}
            <div class="xl:col-span-4">
                <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                    {{-- Header --}}
                    <div class="border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-5">
                        <h2 class="text-lg font-bold text-gray-800">
                            Thao tác nhanh
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Quản lý bài kiểm tra nhanh chóng.
                        </p>
                    </div>
                    <div class="space-y-4 p-6">
                        {{-- ================================================= --}}
                        {{-- Question --}}
                        {{-- ================================================= --}}
                        <a href="{{ route('contributor.exams.questions', $exam->id) }}"
                            class="group flex items-center gap-4 rounded-2xl border border-gray-200 bg-white px-5 py-4 transition-all duration-200 hover:-translate-y-0.5 hover:border-indigo-300 hover:bg-indigo-50 hover:shadow-md">
                            {{-- Icon --}}
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl
                                bg-indigo-100 text-indigo-700
                                transition-all duration-200
                                group-hover:bg-indigo-600
                                group-hover:text-white">
                                <x-icon name="document-text" class="h-5 w-5" />
                            </div>
                            {{-- Nội dung --}}
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold text-gray-800">
                                        Quản lý câu hỏi
                                    </h3>
                                    <x-icon name="chevron-right"
                                        class="h-5 w-5 text-gray-300 transition-all duration-200 group-hover:translate-x-1 group-hover:text-indigo-600" />
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    Thêm, sửa hoặc xóa câu hỏi của bài kiểm tra.
                                </p>
                            </div>
                        </a>
                        {{-- ================================================= --}}
                        {{-- Attempts --}}
                        {{-- ================================================= --}}
                        <a href="{{ route('contributor.exams.attempts', $exam->id) }}"
                            class="group flex items-center gap-4 rounded-2xl border border-gray-200 bg-white px-5 py-4 transition-all duration-200 hover:-translate-y-0.5 hover:border-green-300 hover:bg-green-50 hover:shadow-md">
                            {{-- Icon --}}
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl
                                   bg-green-100 text-green-700
                                   transition-all duration-200
                                   group-hover:bg-green-600
                                   group-hover:text-white">
                                <x-icon name="chart-bar" class="h-5 w-5" />
                            </div>
                            {{-- Nội dung --}}
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold text-gray-800">
                                        Lượt làm bài
                                    </h3>
                                    <x-icon name="chevron-right"
                                        class="h-5 w-5 text-gray-300 transition-all duration-200 group-hover:translate-x-1 group-hover:text-green-600" />
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    Theo dõi kết quả và thống kê người học.
                                </p>
                            </div>
                        </a>
                        {{-- ================================================= --}}
                        {{-- Edit --}}
                        {{-- ================================================= --}}
                        <a href="#"
                            class="group flex items-center gap-4 rounded-2xl border border-gray-200 bg-white px-5 py-4 transition-all duration-200 hover:-translate-y-0.5 hover:border-amber-300 hover:bg-amber-50 hover:shadow-md">
                            {{-- Icon --}}
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl
                                   bg-amber-100 text-amber-700
                                   transition-all duration-200
                                   group-hover:bg-amber-500
                                   group-hover:text-white">
                                <x-icon name="pencil-square" class="h-5 w-5" />
                            </div>
                            {{-- Nội dung --}}
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold text-gray-800">
                                        Chỉnh sửa bài thi
                                    </h3>
                                    <x-icon name="chevron-right"
                                        class="h-5 w-5 text-gray-300 transition-all duration-200 group-hover:translate-x-1 group-hover:text-amber-600" />
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    Cập nhật tiêu đề, mô tả và các thiết lập.
                                </p>
                            </div>
                        </a>
                        {{-- ================================================= --}}
                        {{-- Back --}}
                        {{-- ================================================= --}}
                        <a href="{{ route('contributor.exams') }}"
                            class="group flex items-center gap-4 rounded-2xl border border-gray-200 bg-white px-5 py-4 transition-all duration-200 hover:-translate-y-0.5 hover:border-slate-300 hover:bg-slate-50 hover:shadow-md">
                            {{-- Icon --}}
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl
                                   bg-slate-100 text-slate-700
                                   transition-all duration-200
                                   group-hover:bg-slate-600
                                   group-hover:text-white">
                                <x-icon name="arrow-left" class="h-5 w-5" />
                            </div>
                            {{-- Nội dung --}}
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold text-gray-800">
                                        Quay lại
                                    </h3>
                                    <x-icon name="chevron-right"
                                        class="h-5 w-5 text-gray-300 transition-all duration-200 group-hover:translate-x-1 group-hover:text-slate-600" />
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    Trở về danh sách tất cả bài kiểm tra.
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
        </section>
        {{-- ========================================================= --}}
        {{-- Activity --}}
        {{-- ========================================================= --}}
        <section class="grid grid-cols-1 gap-6 lg:grid-cols-1">
            {{-- ===================================================== --}}
            {{-- Activity --}}
            {{-- ===================================================== --}}
            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                {{-- Header --}}
                <div class="border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-100">
                            <x-icon name="clock" class="h-6 w-6 text-indigo-700" />
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">
                                Hoạt động
                            </h2>
                            <p class="text-sm text-gray-500">
                                Thông tin cập nhật của bài kiểm tra.
                            </p>
                        </div>
                    </div>
                </div>
                {{-- Timeline --}}
                <div class="p-8">
                    <div class="relative">
                        {{-- Timeline line --}}
                        <div class="absolute left-4 top-2 h-full w-0.5 bg-gray-200">
                        </div>
                        {{-- Created --}}
                        <div class="relative mb-10 flex gap-5">
                            <div class="z-10 flex h-8 w-8 items-center justify-center rounded-full bg-green-100">
                                <x-icon name="plus" class="h-4 w-4 text-green-600" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">
                                    Bài kiểm tra được tạo
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $exam->created_at?->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                        {{-- Updated --}}
                        <div class="relative flex gap-5">
                            <div class="z-10 flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100">
                                <x-icon name="pencil-square" class="h-4 w-4 text-indigo-600" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">
                                    Cập nhật gần nhất
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $exam->updated_at?->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
