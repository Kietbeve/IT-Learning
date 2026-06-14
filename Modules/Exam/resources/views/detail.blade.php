@extends('layouts.user')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Header Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sm:p-8">
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <x-badge flat primary label="Backend" />
                    <x-badge flat gray label="PHP" />
                    <x-badge flat gray label="Laravel" />
                </div>
                
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4 leading-tight">
                    Bài thi đánh giá năng lực PHP & Laravel
                </h1>
                
                <p class="text-base font-normal text-gray-600 mb-6 leading-relaxed">
                    Kiểm tra toàn diện kiến thức cơ bản và nâng cao về ngôn ngữ PHP, kiến trúc MVC, bảo mật và framework Laravel phiên bản mới nhất. Bài thi phù hợp cho các ứng viên ứng tuyển vị trí Backend Developer hoặc muốn tự đánh giá năng lực.
                </p>

                <div class="flex items-center gap-3 border-t border-gray-100 pt-5">
                    <div class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center text-base font-bold text-primary-700">
                        N
                    </div>
                    <div>
                        <p class="text-base font-medium text-gray-900">Nguyễn Văn A</p>
                        <p class="text-sm font-normal text-gray-500">Giảng viên / Chuyên gia Backend</p>
                    </div>
                </div>
            </div>

            {{-- Detail Tabs/Sections --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="border-b border-gray-200 bg-gray-50/50 overflow-x-auto">
                    <nav class="flex whitespace-nowrap min-w-max sm:min-w-0">
                        <a href="#" class="w-1/2 sm:w-auto py-4 px-6 text-center border-b-2 border-primary-600 font-medium text-base text-primary-600">
                            Giới thiệu
                        </a>
                        <a href="#" class="w-1/2 sm:w-auto py-4 px-6 text-center border-b-2 border-transparent font-medium text-base text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors">
                            Cấu trúc bài thi
                        </a>
                        <a href="#" class="w-1/2 sm:w-auto py-4 px-6 text-center border-b-2 border-transparent font-medium text-base text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors">
                            Đánh giá
                        </a>
                    </nav>
                </div>
                
                <div class="p-5 sm:p-8">
                    <h3 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-4">Mục tiêu bài thi</h3>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start gap-3 text-base font-normal text-gray-600">
                            <x-icon name="check-circle" class="w-6 h-6 text-green-500 shrink-0" />
                            <span>Đánh giá mức độ am hiểu về cú pháp và tính năng mới của PHP 8.x.</span>
                        </li>
                        <li class="flex items-start gap-3 text-base font-normal text-gray-600">
                            <x-icon name="check-circle" class="w-6 h-6 text-green-500 shrink-0" />
                            <span>Kiểm tra khả năng sử dụng Eloquent ORM, Query Builder và Database Migration.</span>
                        </li>
                        <li class="flex items-start gap-3 text-base font-normal text-gray-600">
                            <x-icon name="check-circle" class="w-6 h-6 text-green-500 shrink-0" />
                            <span>Hiểu rõ về Lifecycle, Middleware, Request/Response trong Laravel.</span>
                        </li>
                    </ul>

                    <h3 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-4">Yêu cầu tham gia</h3>
                    <ul class="space-y-2 text-base font-normal text-gray-600 list-disc pl-5">
                        <li>Đã hoàn thành khóa học PHP cơ bản hoặc có kinh nghiệm làm việc tối thiểu 6 tháng.</li>
                        <li>Nắm vững mô hình MVC và lập trình hướng đối tượng (OOP).</li>
                        <li>Chuẩn bị máy tính có kết nối mạng ổn định trong suốt quá trình làm bài.</li>
                    </ul>
                </div>
            </div>
            
        </div>

        {{-- Sidebar (Payment/Action Card) --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
                <!-- Cover Image/Color -->
                <div class="h-48 bg-blue-800 flex items-center justify-center text-white relative">
                    <x-icon name="document-text" class="w-20 h-20 opacity-75" />
                    
                    <div class="absolute bottom-3 left-3">
                        <x-badge positive label="Miễn phí" />
                    </div>
                </div>

                <div class="p-6">
                    <div class="text-4xl font-bold text-gray-900 mb-6 flex items-baseline gap-2">
                        0đ
                        <span class="text-base font-normal text-gray-400 line-through">200,000đ</span>
                    </div>

                    <div class="space-y-4 mb-8">
                        <div class="flex items-center justify-between text-base">
                            <div class="flex items-center gap-2 text-gray-500">
                                <x-icon name="clock" class="w-5 h-5" />
                                Thời gian
                            </div>
                            <span class="font-semibold text-gray-900">60 phút</span>
                        </div>
                        
                        <div class="flex items-center justify-between text-base">
                            <div class="flex items-center gap-2 text-gray-500">
                                <x-icon name="question-mark-circle" class="w-5 h-5" />
                                Số câu hỏi
                            </div>
                            <span class="font-semibold text-gray-900">30 câu</span>
                        </div>

                        <div class="flex items-center justify-between text-base">
                            <div class="flex items-center gap-2 text-gray-500">
                                <x-icon name="check-circle" class="w-5 h-5" />
                                Điểm đỗ
                            </div>
                            <span class="font-semibold text-gray-900">70%</span>
                        </div>
                        
                        <div class="flex items-center justify-between text-base">
                            <div class="flex items-center gap-2 text-gray-500">
                                <x-icon name="user-group" class="w-5 h-5" />
                                Số lượt thi
                            </div>
                            <span class="font-semibold text-gray-900">1,250</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        {{-- Nút thanh toán / Vào thi --}}
                        <x-button indigo xl right-icon="arrow-right" class="w-full flex justify-center" label="Bắt đầu thi ngay" />

                        <x-button outline gray xl icon="bookmark" class="w-full flex justify-center" label="Lưu bài thi" />
                    </div>
                </div>
                
                {{-- Payment Guarantee (Optional for Payment context) --}}
                <div class="bg-gray-50 p-4 border-t border-gray-100 flex items-start gap-3">
                    <x-icon name="shield-check" class="w-6 h-6 text-green-600 shrink-0" />
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Thanh toán an toàn</p>
                        <p class="text-xs font-normal text-gray-500 mt-0.5">Hỗ trợ đa dạng phương thức, đảm bảo quyền lợi khi thanh toán.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
