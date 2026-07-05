@extends('layouts.contributor')

@section('content')
    <div class="space-y-6">

        {{-- Thống kê --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">
                            Tổng quan đề thi
                        </h2>
                        <p class="text-sm text-slate-500">
                            Thống kê nhanh tình trạng đề thi.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 lg:grid-cols-6">
                {{-- Tổng đề thi --}}
                <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="absolute right-0 top-0 -mr-4 -mt-4 h-20 w-20 opacity-10 transition-transform duration-300 group-hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="text-slate-400">
                            <path d="M9 2a2 2 0 00-2 2v1H6a2 2 0 00-2 2v14a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H9zm0 2h6v2H9V4zm9 3v14H6V7h12z"/>
                            <path d="M8 10h8v2H8v-2zm0 4h8v2H8v-2zm0 4h5v2H8v-2z"/>
                        </svg>
                    </div>
                    <div class="relative">
                        <div class="flex items-center gap-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 transition-colors duration-300 group-hover:bg-slate-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-slate-500">Tổng đề thi</p>
                        </div>
                        <p class="mt-3 text-3xl font-bold text-slate-900 transition-transform duration-300 group-hover:scale-105">
                            {{ number_format($stats->total) }}
                        </p>
                    </div>
                </div>

                {{-- Đã duyệt --}}
                <div class="group relative overflow-hidden rounded-xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="absolute right-0 top-0 -mr-4 -mt-4 h-20 w-20 opacity-10 transition-transform duration-300 group-hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="text-emerald-500">
                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-10.293a1 1 0 00-1.414-1.414L9 9.586 7.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                    </div>
                    <div class="relative">
                        <div class="flex items-center gap-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 transition-colors duration-300 group-hover:bg-emerald-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-emerald-700">Đã duyệt</p>
                        </div>
                        <p class="mt-3 text-3xl font-bold text-emerald-900 transition-transform duration-300 group-hover:scale-105">
                            {{ number_format($stats->approved) }}
                        </p>
                    </div>
                </div>

                {{-- Chờ duyệt --}}
                <div class="group relative overflow-hidden rounded-xl border border-amber-200 bg-amber-50 p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="absolute right-0 top-0 -mr-4 -mt-4 h-20 w-20 opacity-10 transition-transform duration-300 group-hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="text-amber-500">
                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12zm1-8H9v5l4 2 1-1-3-2V8z"/>
                        </svg>
                    </div>
                    <div class="relative">
                        <div class="flex items-center gap-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 transition-colors duration-300 group-hover:bg-amber-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-amber-700">Chờ duyệt</p>
                        </div>
                        <p class="mt-3 text-3xl font-bold text-amber-900 transition-transform duration-300 group-hover:scale-105">
                            {{ number_format($stats->pending) }}
                        </p>
                    </div>
                </div>
                {{-- Từ chối --}}
                <div class="group relative overflow-hidden rounded-xl border border-red-200 bg-red-50 p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="absolute right-0 top-0 -mr-4 -mt-4 h-20 w-20 opacity-10 transition-transform duration-300 group-hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="text-red-500">
                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm3.536 10.121l-1.414 1.414L10 11.414l-2.121 2.121-1.414-1.414L8.586 10 6.464 7.879l1.414-1.414L10 8.586l2.121-2.121 1.414 1.414L11.414 10l2.122 2.121z"/>
                        </svg>
                    </div>
                    <div class="relative">
                        <div class="flex items-center gap-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-100 transition-colors duration-300 group-hover:bg-red-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-red-700">Từ chối</p>
                        </div>
                        <p class="mt-3 text-3xl font-bold text-red-900 transition-transform duration-300 group-hover:scale-105">
                            {{ number_format($stats->rejected) }}
                        </p>
                    </div>
                </div>

                {{-- Bản nháp --}}
                <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-slate-50 p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="absolute right-0 top-0 -mr-4 -mt-4 h-20 w-20 opacity-10 transition-transform duration-300 group-hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="text-slate-400">
                            <path d="M17 3a3 3 0 013 3v12a3 3 0 01-3 3H7a3 3 0 01-3-3V6a3 3 0 013-3h10zm-1 4H8v10h8V7zm-6 2h4v2h-4V9zm0 4h4v2h-4v-2z"/>
                        </svg>
                    </div>
                    <div class="relative">
                        <div class="flex items-center gap-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 transition-colors duration-300 group-hover:bg-slate-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-slate-700">Bản nháp</p>
                        </div>
                        <p class="mt-3 text-3xl font-bold text-slate-900 transition-transform duration-300 group-hover:scale-105">
                            {{ number_format($stats->draft) }}
                        </p>
                    </div>
                </div>

                {{-- Chưa có câu hỏi --}}
                <div class="group relative overflow-hidden rounded-xl border border-red-200 bg-red-50 p-4 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div class="absolute right-0 top-0 -mr-4 -mt-4 h-20 w-20 opacity-10 transition-transform duration-300 group-hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="text-red-500">
                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 12H9v-2h2v2zm0-4H9V5h2v5z"/>
                        </svg>
                    </div>
                    <div class="relative">
                        <div class="flex items-center gap-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-100 transition-colors duration-300 group-hover:bg-red-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-red-700">
                                Chưa có câu hỏi
                            </p>
                        </div>
                        <p class="mt-3 text-3xl font-bold text-red-900 transition-transform duration-300 group-hover:scale-105">
                            {{ number_format($stats->no_questions) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Danh sách --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">
                            Quản lý đề thi
                        </h2>
                        <p class="text-sm text-slate-500">
                            Danh sách toàn bộ đề thi.
                        </p>
                    </div>
                </div>
            </div>

            <livewire:modules.exam.livewire.contributor.exam-table />
        </div>

        <livewire:modules.exam.livewire.contributor.exam-modal />
    </div>
@endsection