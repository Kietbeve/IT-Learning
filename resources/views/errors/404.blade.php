@extends('layouts.user')

@section('content')
<section class="bg-white dark:bg-gray-900 ">
    <div class="container flex items-center justify-center min-h-screen px-6 py-12 mx-auto">
        <div class="w-full ">
            <main class="flex flex-col items-center text-center mb-16">
            {{-- Khối hình ảnh 404 (Sử dụng ảnh background đã cắt từ bước trước) --}}
            <div class="w-full max-w-2xl mb-8">
                <img  src="{{ asset('Image/bg_page_404.png') }}" 
                      alt="404 Not Found Illustration" 
                      class="w-full h-auto object-contain"
                      style="
                        -webkit-mask-image: linear-gradient(
                            to right,
                            transparent,
                            black 10%,
                            black 90%,
                            transparent
                        ),
                        linear-gradient(
                            to bottom,
                            transparent,
                            black 10%,
                            black 90%,
                            transparent
                        );
                        -webkit-mask-composite: source-in;
                        mask-composite: intersect;
                    ">
            </div>

            {{-- Thông báo lỗi --}}
            <h1 class="text-2xl md:text-3xl font-extrabold text-blue-950 mb-3">
                Rất tiếc! Trang bạn tìm kiếm không tồn tại.
            </h1>
            <p class="text-slate-500 text-sm md:text-base max-w-md mb-8">
                Đường dẫn có thể đã bị thay đổi, xóa hoặc bạn nhập sai địa chỉ.
            </p>

            {{-- Cặp nút bấm điều hướng chính --}}
            <div class="flex flex-col sm:flex-row gap-4 w-full justify-center max-w-md">
                {{-- Nút chính: Quay về --}}
                <a href="{{ url('/') }}" class="flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl font-medium shadow-lg shadow-blue-200 hover:bg-blue-700 transition w-full sm:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Quay về trang chủ
                </a>
            </div>
          </main>

            <section class="border-t border-slate-100 pt-8">
            <h3 class="text-base font-bold text-blue-950 mb-6 text-left">
                Bạn có thể quan tâm
            </h3>

            {{-- Grid 4 cột cho các thẻ gợi ý --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                
                {{-- Thẻ 1: Kho tài liệu --}}
                <a href="{{ route('documents.index') }}" class="flex items-start gap-4 p-4 border border-slate-100 bg-slate-50/50 rounded-2xl hover:border-blue-500 hover:bg-white hover:shadow-md transition group">
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-xl">
                        {{-- Icon cuốn sách --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-slate-800 mb-1 group-hover:text-blue-600">Kho tài liệu</h4>
                        <p class="text-xs text-slate-400 line-clamp-2">Học hỏi với hàng ngàn tài liệu chất lượng</p>
                    </div>
                    <span class="text-slate-300 group-hover:text-blue-600 self-center">&rarr;</span>
                </a>

                {{-- Thẻ 2: Đề thi --}}
                <a href="{{ route('exam.index') }}" class="flex items-start gap-4 p-4 border border-slate-100 bg-slate-50/50 rounded-2xl hover:border-emerald-500 hover:bg-white hover:shadow-md transition group">
                    <div class="p-3 bg-emerald-100 text-emerald-600 rounded-xl">
                        {{-- Icon bài thi/checklist --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-slate-800 mb-1 group-hover:text-emerald-600">Đề thi</h4>
                        <p class="text-xs text-slate-400 line-clamp-2">Luyện tập với các đề thi mới nhất</p>
                    </div>
                    <span class="text-slate-300 group-hover:text-emerald-600 self-center">&rarr;</span>
                </a>

                {{-- Thẻ 3: Lộ trình học --}}
                <a href="{{ route('learning.roadmaps.index') }}" class="flex items-start gap-4 p-4 border border-slate-100 bg-slate-50/50 rounded-2xl hover:border-purple-500 hover:bg-white hover:shadow-md transition group">
                    <div class="p-3 bg-purple-100 text-purple-600 rounded-xl">
                        {{-- Icon Bản đồ/Lộ trình --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-slate-800 mb-1 group-hover:text-purple-600">Lộ trình học</h4>
                        <p class="text-xs text-slate-400 line-clamp-2">Học theo lộ trình bài bản, khoa học</p>
                    </div>
                    <span class="text-slate-300 group-hover:text-purple-600 self-center">&rarr;</span>
                </a>

                {{-- Thẻ 4: Dự án thực hành --}}
                {{-- <a href="#" class="flex items-start gap-4 p-4 border border-slate-100 bg-slate-50/50 rounded-2xl hover:border-orange-500 hover:bg-white hover:shadow-md transition group">
                    <div class="p-3 bg-orange-100 text-orange-600 rounded-xl">
                        Icon Monitor/Code
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-slate-800 mb-1 group-hover:text-orange-600">Dự án thực hành</h4>
                        <p class="text-xs text-slate-400 line-clamp-2">Thực hành qua các dự án thực tế</p>
                    </div>
                    <span class="text-slate-300 group-hover:text-orange-600 self-center">&rarr;</span>
                </a> --}}

            </div>
        </section>

        </div>
    </div>
</section>


@endsection
