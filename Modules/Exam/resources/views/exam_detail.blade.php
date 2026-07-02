@extends('layouts.user')

@section('content')
<div class="bg-gradient-to-b from-blue-50 via-white to-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">

            <!-- Main Content -->
            <div class="xl:col-span-8 space-y-8">

                <!-- Hero -->
                <div class="relative overflow-hidden rounded-[32px] bg-gradient-to-r from-blue-700 via-blue-600 to-cyan-500 p-8 md:p-12 shadow-2xl">

                    <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-white/5"></div>

                    <div class="relative">
                        <div class="flex flex-wrap gap-2 mb-6">
                            <x-badge flat primary :label="$exam->category->name" />
                            <x-badge flat gray :label="ucfirst($exam->type)" />
                        </div>

                        <h1 class="text-4xl lg:text-5xl font-extrabold text-white leading-tight mb-5">
                            {{ $exam->title }}
                        </h1>

                        <p class="text-blue-100 text-lg leading-8 max-w-3xl">
                            {{ $exam->description }}
                        </p>

                        <div class="flex items-center gap-4 mt-10">
                            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-white text-xl font-bold">
                                {{ strtoupper(substr($exam->author->name, 0, 1)) }}
                            </div>

                            <div>
                                <div class="font-semibold text-white text-lg">
                                    {{ $exam->author->name }}
                                </div>
                                <div class="text-blue-100">
                                    Tác giả bài kiểm tra
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white rounded-[32px] border border-blue-100 shadow-lg overflow-hidden">

                    <div class="px-8 py-6 border-b border-blue-100 bg-blue-50">
                        <h2 class="text-xl font-bold text-blue-900">
                            Thông tin chi tiết
                        </h2>
                    </div>

                    <div class="p-8">
                        <p class="text-slate-600 leading-8 text-base">
                            {{ $exam->description }}
                        </p>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid md:grid-cols-2 gap-6">

                    <div class="bg-white rounded-[28px] p-6 border border-blue-100 shadow-md">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center">
                                <x-icon name="user" class="w-7 h-7 text-blue-700" />
                            </div>

                            <div>
                                <div class="text-sm text-slate-500">Tác giả</div>
                                <div class="font-bold text-slate-900">
                                    {{ $exam->author->name }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-[28px] p-6 border border-blue-100 shadow-md">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center">
                                <x-icon name="folder" class="w-7 h-7 text-blue-700" />
                            </div>

                            <div>
                                <div class="text-sm text-slate-500">Danh mục</div>
                                <div class="font-bold text-slate-900">
                                    {{ $exam->category->name }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Sidebar -->
            <div class="xl:col-span-4">

                <div class="sticky top-6 space-y-6">

                    <div class="bg-white rounded-[32px] overflow-hidden border border-blue-100 shadow-xl">

                        <div class="bg-gradient-to-r from-blue-700 to-blue-500 p-8 text-white">

                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="text-blue-100 text-sm">
                                        Bảng thông tin
                                    </div>

                                    <div class="text-2xl font-bold mt-1">
                                        Bài kiểm tra
                                    </div>
                                </div>

                                <x-icon
                                    name="clipboard-document-check"
                                    class="w-16 h-16 opacity-80"
                                />
                            </div>

                        </div>

                        <div class="p-6">

                            <div class="space-y-4">

                                <div class="rounded-2xl bg-slate-50 p-4 flex justify-between items-center">
                                    <span class="text-slate-500">Thời gian</span>
                                    <span class="font-bold text-slate-900">
                                        {{ $exam->duration_minutes }} phút
                                    </span>
                                </div>

                                <div class="rounded-2xl bg-slate-50 p-4 flex justify-between items-center">
                                    <span class="text-slate-500">Số câu hỏi</span>
                                    <span class="font-bold text-slate-900">
                                        {{ $exam->questions_count }}
                                    </span>
                                </div>

                                <div class="rounded-2xl bg-slate-50 p-4 flex justify-between items-center">
                                    <span class="text-slate-500">Điểm đạt</span>
                                    <span class="font-bold text-slate-900">
                                        {{ rtrim(rtrim($exam->pass_percent, '0'), '.') }}%
                                    </span>
                                </div>

                                <div class="rounded-2xl bg-slate-50 p-4 flex justify-between items-center">
                                    <span class="text-slate-500">Hình thức</span>
                                    <span class="font-bold text-slate-900">
                                        {{ ucfirst($exam->type) }}
                                    </span>
                                </div>

                            </div>

                            <div class="mt-8">

                                @if ($errors->has('exam'))
                                    <x-alert
                                        negative
                                        :title="$errors->first('exam')"
                                        class="mb-4"
                                    />
                                @endif

                                <form
                                    method="POST"
                                    action="{{ route('exam.attempt.start', $exam->slug) }}"
                                    x-data="{confirmModal:false,submitting:false}"
                                    @keydown.escape.window="confirmModal = false"
                                >
                                    @csrf

                                    <x-button
                                        type="button"
                                        indigo
                                        xl
                                        right-icon="arrow-right"
                                        class="w-full justify-center"
                                        label="Bắt đầu làm bài"
                                        @click="confirmModal = true"
                                    />

                                    <div
                                        x-show="confirmModal"
                                        x-cloak
                                        class="fixed inset-0 z-50"
                                        style="display:none;"
                                    >
                                        <div
                                            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
                                            x-transition.opacity
                                            @click="confirmModal = false"
                                        ></div>

                                        <div class="relative flex min-h-screen items-center justify-center p-4">

                                            <div
                                                @click.stop
                                                x-transition
                                                class="w-full max-w-lg bg-white rounded-[32px] overflow-hidden shadow-2xl"
                                            >

                                                <div class="p-8">

                                                    <div class="w-16 h-16 rounded-2xl bg-blue-100 flex items-center justify-center mb-5">
                                                        <x-icon
                                                            name="question-mark-circle"
                                                            class="w-8 h-8 text-blue-700"
                                                        />
                                                    </div>

                                                    <h3 class="text-xl font-bold text-slate-900 mb-2">
                                                        Xác nhận làm bài kiểm tra
                                                    </h3>

                                                    <p class="text-slate-600">
                                                        Bạn có chắc chắn muốn bắt đầu bài kiểm tra này?
                                                    </p>

                                                </div>

                                                <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3">

                                                    <x-button
                                                        type="button"
                                                        flat
                                                        gray
                                                        label="Hủy"
                                                        @click="confirmModal = false"
                                                    />

                                                    <x-button
                                                        type="submit"
                                                        indigo
                                                        label="Đồng ý"
                                                        x-bind:disabled="submitting"
                                                        x-on:click="submitting = true; confirmModal = false;"
                                                    />

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </form>

                                <div class="mt-3">
                                    <x-button
                                        outline
                                        gray
                                        xl
                                        icon="bookmark"
                                        class="w-full justify-center"
                                        label="Lưu bài thi"
                                    />
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-[28px] p-6">
                        <div class="flex gap-3">
                            <x-icon
                                name="information-circle"
                                class="w-6 h-6 text-blue-700 shrink-0"
                            />

                            <div>
                                <div class="font-semibold text-blue-900">
                                    Lưu ý
                                </div>

                                <div class="text-sm text-blue-700 mt-1">
                                    Hoàn thành tối thiểu
                                    {{ rtrim(rtrim($exam->pass_percent, '0'), '.') }}%
                                    số điểm để vượt qua bài thi.
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>
@endsection
