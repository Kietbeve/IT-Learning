@extends('layouts.user')

@section('content')
    <div class="max-w-7xl mx-auto pb-8 pt-4">

        <!-- Search Bar -->
        @include('exam::partials._search_form')

        {{-- List Exams --}}

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 px-4 sm:px-6 lg:px-8">
          @forelse ( $exams as $exam )
            @include('exam::partials.exam_card',
            [
              'exam'=>$exam
            ])
          @empty
                <div class="flex flex-col items-center col-span-full py-12">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                        <x-icon
                            name="magnifying-glass"
                            class="w-8 h-8 text-gray-400"
                        />
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-800">
                        Không tìm thấy bài kiểm tra
                    </h3>
                    <p class="mt-2 text-sm text-gray-500 max-w-md text-center">
                        Hãy thử thay đổi từ khóa hoặc tìm kiếm bằng tên danh mục khác.
                    </p>
                </div> 
          @endforelse 
        </div>

        {{-- Pagination --}}

        {{-- Chỉ hiển thị nếu có nhiều hơn 1 trang --}}
        @if($exams->hasPages())

            <div class="mt-8 px-4 sm:px-6 lg:px-8">

                <div class="flex justify-center">

                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm px-3 py-3">

                        <div class="flex items-center gap-2">

                            {{-- Kiểm tra có phải trang đầu tiên không --}}
                            @if ($exams->onFirstPage())
                            {{-- Nếu đúng, không có trang trước --}}
                                <div class="w-10 h-10 flex items-center justify-center rounded-xl text-gray-300 cursor-not-allowed">
                                    <x-icon name="chevron-left" class="w-5 h-5" />
                                </div>

                            @else
                            {{-- Nếu sai, có trang trước --}}

                                <a
                                    href="{{ $exams->previousPageUrl() }}"
                                    class="w-10 h-10 flex items-center justify-center rounded-xl text-gray-600 hover:bg-gray-100 transition"
                                >
                                    <x-icon name="chevron-left" class="w-5 h-5" />
                                </a>

                            @endif

                            {{-- Tính toán hiển thị trang --}}
                            @php
                                $currentPage = $exams->currentPage();
                                $lastPage = $exams->lastPage();
                                // Nếu tổng số trang <= 3, hiển thị tất cả các trang
                                if ($lastPage <= 3) {
                                    $startPage = 1;
                                    $endPage = $lastPage;
                                // Nếu đang ở trang đầu hoặc trang thứ 2, hiển thị từ trang 1 đến trang 3
                                } elseif ($currentPage <= 2) {
                                    $startPage = 1;
                                    $endPage = 3;
                                // Nếu đang ở trang cuối hoặc trang áp cuối, hiển thị đến trang cuối
                                } elseif ($currentPage >= $lastPage - 1) {
                                    $startPage = $lastPage - 2;
                                    $endPage = $lastPage;
                                // Nếu đang ở các trang khác, hiển thị trang trước, trang hiện tại và trang sau
                                } else {
                                    $startPage = $currentPage - 1;
                                    $endPage = $currentPage + 1;
                                }
                            @endphp
                            {{-- Page Numbers --}}
                            {{-- Duyệt từ trang bắt đầu đến trang kết thúc --}}
                            @foreach(range($startPage, $endPage) as $page)

                                {{-- Hiện tại trang (active) --}}
                                @if($page == $exams->currentPage())

                                    <div
                                        class="min-w-10.5 h-10 px-3 rounded-xl bg-indigo-600 text-white font-bold text-base flex items-center justify-center shadow-sm"
                                    >
                                        {{ $page }}
                                    </div>

                                @else
                                    {{-- Các trang khác --}}
                                    <div class="min-w-10.5 h-10 px-3 rounded-xl text-gray-600 text-sm font-medium flex items-center justify-center">
                                        {{ $page }}
                                    </div>

                                @endif

                            @endforeach


                            {{-- Next --}}
                            {{-- Kiểm tra có trang tiếp theo không --}}
                            @if ($exams->hasMorePages())
                                {{-- Có trang tiếp theo --}}
                                <a
                                    href="{{ $exams->nextPageUrl() }}"
                                    class="w-10 h-10 flex items-center justify-center rounded-xl text-gray-600 hover:bg-gray-100 transition"
                                >
                                    <x-icon name="chevron-right" class="w-5 h-5" />
                                </a>

                            @else
                                {{-- Không có trang tiếp theo --}}
                                <div
                                    class="w-10 h-10 flex items-center justify-center rounded-xl text-gray-300 cursor-not-allowed"
                                >
                                    <x-icon name="chevron-right" class="w-5 h-5" />
                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        @endif
    </div>
@endsection