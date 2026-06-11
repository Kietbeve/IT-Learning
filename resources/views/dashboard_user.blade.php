@extends('layouts.user')

@section('content')
    <div class="max-w-7xl mx-auto pb-8 pt-4 px-4 sm:px-6 lg:px-8 space-y-10">
        
        {{-- Welcome Section --}}
        <div class="bg-linear-to-r from-blue-50 to-indigo-50 rounded-2xl border border-indigo-100 shadow-sm p-6 sm:p-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Chào mừng trở lại, {{ auth()->user()->name ?? 'Người dùng' }}! 👋</h1>
                <p class="text-gray-600 text-sm sm:text-base">Hôm nay bạn muốn học gì nào? Tiếp tục hành trình chinh phục kiến thức nhé.</p>
            </div>
            <div class="hidden md:flex items-center justify-center bg-white w-24 h-24 rounded-full shadow-sm border border-indigo-100 shrink-0">
                <x-icon name="academic-cap" class="w-12 h-12 text-indigo-500" />
            </div>
        </div>

        {{-- Quick Access (QA1 - QA4) --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
            <a href="#" class="rounded-2xl p-5 sm:p-6 bg-linear-to-br from-blue-500 to-blue-600 shadow-lg shadow-blue-500/20 hover:-translate-y-1 hover:shadow-blue-500/40 transition-all cursor-pointer flex flex-col items-center justify-center gap-3 text-center group">
                <x-icon name="document-text" class="w-8 h-8 sm:w-10 sm:h-10 text-white opacity-90 group-hover:scale-110 transition-transform" />
                <div class="font-bold text-white text-sm sm:text-base">Kho tài liệu</div>
            </a>
            <a href="#" class="rounded-2xl p-5 sm:p-6 bg-linear-to-br from-green-500 to-green-600 shadow-lg shadow-green-500/20 hover:-translate-y-1 hover:shadow-green-500/40 transition-all cursor-pointer flex flex-col items-center justify-center gap-3 text-center group">
                <x-icon name="pencil-square" class="w-8 h-8 sm:w-10 sm:h-10 text-white opacity-90 group-hover:scale-110 transition-transform" />
                <div class="font-bold text-white text-sm sm:text-base">Luyện thi</div>
            </a>
            <a href="#" class="rounded-2xl p-5 sm:p-6 bg-linear-to-br from-purple-500 to-purple-600 shadow-lg shadow-purple-500/20 hover:-translate-y-1 hover:shadow-purple-500/40 transition-all cursor-pointer flex flex-col items-center justify-center gap-3 text-center group">
                <x-icon name="map" class="w-8 h-8 sm:w-10 sm:h-10 text-white opacity-90 group-hover:scale-110 transition-transform" />
                <div class="font-bold text-white text-sm sm:text-base">Lộ trình</div>
            </a>
            <a href="#" class="rounded-2xl p-5 sm:p-6 bg-linear-to-br from-amber-400 to-amber-500 shadow-lg shadow-amber-500/20 hover:-translate-y-1 hover:shadow-amber-500/40 transition-all cursor-pointer flex flex-col items-center justify-center gap-3 text-center group">
                <x-icon name="bookmark" class="w-8 h-8 sm:w-10 sm:h-10 text-white opacity-90 group-hover:scale-110 transition-transform" />
                <div class="font-bold text-white text-sm sm:text-base">Đã lưu</div>
            </a>
        </div>

        {{-- Notify --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Thông báo mới</h2>
                <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Xem tất cả &rarr;</a>
            </div>
            <x-card padding="none" class="overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-gray-100">
                    <div class="flex gap-4 p-5 hover:bg-gray-50 transition-colors cursor-pointer">
                        <div class="shrink-0 mt-1">
                            <div class="w-3 h-3 rounded-full bg-blue-500 shadow-sm shadow-blue-500/50"></div>
                        </div>
                        <div>
                            <p class="text-sm sm:text-base font-semibold text-gray-800 leading-snug">Cập nhật đề thi mới</p>
                            <p class="text-sm text-gray-500 mt-1 line-clamp-2">Hệ thống đã cập nhật thêm 50 câu hỏi cho bài Đánh giá năng lực.</p>
                            <p class="text-xs font-medium text-blue-600 mt-2">2 giờ trước</p>
                        </div>
                    </div>
                    <div class="flex gap-4 p-5 hover:bg-gray-50 transition-colors cursor-pointer">
                        <div class="shrink-0 mt-1">
                            <div class="w-3 h-3 rounded-full bg-green-500 shadow-sm shadow-green-500/50"></div>
                        </div>
                        <div>
                            <p class="text-sm sm:text-base font-semibold text-gray-800 leading-snug">Hoàn thành Lộ trình</p>
                            <p class="text-sm text-gray-500 mt-1 line-clamp-2">Chúc mừng bạn đã hoàn thành xuất sắc Lộ trình PHP Cơ bản.</p>
                            <p class="text-xs font-medium text-green-600 mt-2">Hôm qua</p>
                        </div>
                    </div>
                    <div class="flex gap-4 p-5 hover:bg-gray-50 transition-colors cursor-pointer">
                        <div class="shrink-0 mt-1">
                            <div class="w-3 h-3 rounded-full bg-purple-500 shadow-sm shadow-purple-500/50"></div>
                        </div>
                        <div>
                            <p class="text-sm sm:text-base font-semibold text-gray-800 leading-snug">Sự kiện tháng 6</p>
                            <p class="text-sm text-gray-500 mt-1 line-clamp-2">Tham gia đóng góp tài liệu để nhận các badge độc quyền.</p>
                            <p class="text-xs font-medium text-purple-600 mt-2">3 ngày trước</p>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        {{-- Documents (4 cards) --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Tài liệu mới nổi bật</h2>
                <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Xem thêm &rarr;</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 gap-6">
                {{-- Mocking 4 Document Cards --}}
                @for ($i = 0; $i < 4; $i++)
                <x-card padding="none" class="overflow-hidden hover:shadow-lg transition-shadow flex flex-col h-full border border-gray-100">
                    <div class="h-40 bg-gray-50 flex items-center justify-center border-b border-gray-100">
                        <x-icon name="document" class="w-12 h-12 text-gray-300" />
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 mb-2">
                            <x-badge flat gray label="Ebook" />
                            <span class="text-[11px] text-gray-500">Vừa cập nhật</span>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 line-clamp-2 mb-2">Tài liệu Lập trình Laravel 11 toàn tập từ A-Z cho người mới</h3>
                        <div class="mt-auto pt-4 flex items-center justify-between border-t border-gray-50">
                            <span class="text-sm font-bold text-green-600">Miễn phí</span>
                            <x-button outline indigo sm label="Xem chi tiết" />
                        </div>
                    </div>
                </x-card>
                @endfor
            </div>
        </div>

        {{-- Exams (3 cards) --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Đề thi khuyên dùng</h2>
                <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Xem thêm &rarr;</a>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:gap-5">
                {{-- Include Exam Card Partial --}}
                @include('exam::patials.exam_card')
                @include('exam::patials.exam_card')
                @include('exam::patials.exam_card')
            </div>
        </div>

        {{-- Roadmaps (3 cards) --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Lộ trình học tập</h2>
                <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Xem thêm &rarr;</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @for ($i = 0; $i < 3; $i++)
                <x-card padding="p-5" class="hover:border-indigo-300 transition-colors border-2 border-transparent">
                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600 shrink-0">
                            <x-icon name="map" class="w-8 h-8" />
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 mb-1.5">Backend Developer</h3>
                            <p class="text-sm text-gray-500 mb-3 line-clamp-2">Lộ trình chi tiết từng bước để trở thành Backend Developer chuyên nghiệp.</p>
                            <div class="flex items-center gap-4 text-xs font-medium text-gray-500">
                                <span class="flex items-center gap-1"><x-icon name="document-text" class="w-4 h-4 text-gray-400"/> 15 khóa học</span>
                                <span class="flex items-center gap-1"><x-icon name="clock" class="w-4 h-4 text-gray-400"/> 3 tháng</span>
                            </div>
                        </div>
                    </div>
                </x-card>
                @endfor
            </div>
        </div>

        {{-- Become Contributor --}}
        <div class="bg-slate-900 rounded-3xl shadow-xl p-8 sm:p-10 text-white relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8 mt-12">
            <div class="relative z-10 md:w-2/3 text-center md:text-left">
                <x-badge flat white label="Cộng đồng" class="mb-4" />
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4">Trở thành Người đóng góp</h2>
                <p class="text-slate-300 mb-8 max-w-xl text-base sm:text-lg mx-auto md:mx-0">
                    Chia sẻ kiến thức của bạn với cộng đồng. Đóng góp tài liệu, đề thi hoặc lộ trình học tập để giúp đỡ những người khác và nhận được nhiều quyền lợi đặc biệt.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center md:justify-start">
                    <x-button primary xl label="Tham gia ngay" right-icon="arrow-right" class="font-semibold shadow-lg shadow-indigo-500/30" />
                    <x-button outline white xl label="Tìm hiểu quyền lợi" />
                </div>
            </div>
            <div class="relative z-10 shrink-0 hidden md:flex items-center justify-center p-6">
                <div class="w-48 h-48 bg-linear-to-tr from-indigo-500 to-purple-500 rounded-full opacity-20 blur-3xl absolute"></div>
                <x-icon name="globe-asia-australia" class="w-40 h-40 text-indigo-400 opacity-90 drop-shadow-2xl" />
            </div>
            
            {{-- Decorative pattern --}}
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5"></div>
        </div>

    </div>
@endsection
