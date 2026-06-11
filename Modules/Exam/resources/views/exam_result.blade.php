@extends('layouts.user')

@section('content')
<div x-data="{
    examTitle: 'Đề thi Đánh giá năng lực CNTT 2024',
    score: 85,
    totalQuestions: 50,
    correctAnswers: 42,
    wrongAnswers: 5,
    skippedAnswers: 3,
    passed: true,
    showDetails: false,
    
    getPassStatus() {
        return this.passed ? 'Đạt' : 'Không đạt';
    },
    
    getPassColor() {
        return this.passed ? 'text-green-600' : 'text-red-600';
    },
    
    getPassBgColor() {
        return this.passed ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200';
    }
}" 
class="min-h-screen bg-gray-50 py-8">

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        {{-- Score Display Card --}}
        <x-card padding="p-6 sm:p-8 lg:p-10">
            <div class="text-center">
                <div class="mb-4">
                    <x-badge flat gray label="Kết quả bài thi" class="text-sm" />
                </div>
                
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4" x-text="examTitle"></h1>
                
                {{-- Score Circle --}}
                <div class="flex justify-center mb-6">
                    <div class="relative">
                        <div class="w-32 h-32 sm:w-40 sm:h-40 rounded-full border-8 flex items-center justify-center"
                             :class="passed ? 'border-green-500 bg-green-50' : 'border-red-500 bg-red-50'">
                            <div class="text-center">
                                <div class="text-4xl sm:text-5xl font-bold" x-bind:class="getPassColor()" x-text="score"></div>
                                <div class="text-sm sm:text-base text-gray-600">điểm</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Pass/Fail Badge --}}
                <div class="inline-flex items-center gap-2 px-6 py-3 rounded-full border-2" x-bind:class="getPassBgColor()">
                    <x-icon name="check-circle" class="w-6 h-6" x-bind:class="getPassColor()" x-show="passed" />
                    <x-icon name="x-circle" class="w-6 h-6" x-bind:class="getPassColor()" x-show="!passed" />
                    <span class="font-bold text-lg" x-bind:class="getPassColor()" x-text="getPassStatus()"></span>
                </div>
            </div>
        </x-card>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {{-- Correct Answers --}}
            <x-card padding="p-5 sm:p-6" class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-green-500 rounded-xl">
                        <x-icon name="check-circle" class="w-6 h-6 text-white" />
                    </div>
                    <div class="flex-1">
                        <div class="text-sm text-green-700 font-medium mb-1">Câu trả lời đúng</div>
                        <div class="text-3xl font-bold text-green-900" x-text="correctAnswers"></div>
                        <div class="text-xs text-green-600 mt-1">
                            <span x-text="((correctAnswers / totalQuestions) * 100).toFixed(1)"></span>% tổng số câu
                        </div>
                    </div>
                </div>
            </x-card>

            {{-- Wrong Answers --}}
            <x-card padding="p-5 sm:p-6" class="bg-gradient-to-br from-red-50 to-rose-50 border-2 border-red-200">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-red-500 rounded-xl">
                        <x-icon name="x-circle" class="w-6 h-6 text-white" />
                    </div>
                    <div class="flex-1">
                        <div class="text-sm text-red-700 font-medium mb-1">Câu trả lời sai</div>
                        <div class="text-3xl font-bold text-red-900" x-text="wrongAnswers"></div>
                        <div class="text-xs text-red-600 mt-1">
                            <span x-text="((wrongAnswers / totalQuestions) * 100).toFixed(1)"></span>% tổng số câu
                        </div>
                    </div>
                </div>
            </x-card>

            {{-- Skipped Answers --}}
            <x-card padding="p-5 sm:p-6" class="bg-gradient-to-br from-gray-50 to-slate-50 border-2 border-gray-200">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-gray-500 rounded-xl">
                        <x-icon name="minus-circle" class="w-6 h-6 text-white" />
                    </div>
                    <div class="flex-1">
                        <div class="text-sm text-gray-700 font-medium mb-1">Câu bỏ qua</div>
                        <div class="text-3xl font-bold text-gray-900" x-text="skippedAnswers"></div>
                        <div class="text-xs text-gray-600 mt-1">
                            <span x-text="((skippedAnswers / totalQuestions) * 100).toFixed(1)"></span>% tổng số câu
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        {{-- View Details Toggle --}}
        <div class="flex justify-center">
            <x-button 
                outline 
                indigo 
                xl
                @click="showDetails = !showDetails"
                class="font-semibold">
                <span x-show="!showDetails">
                    <x-icon name="eye" class="w-5 h-5 mr-2 inline" />
                    Xem chi tiết đáp án
                </span>
                <span x-show="showDetails">
                    <x-icon name="eye-slash" class="w-5 h-5 mr-2 inline" />
                    Ẩn chi tiết
                </span>
            </x-button>
        </div>

        {{-- Question Review Section --}}
        <div x-show="showDetails" x-cloak x-transition class="space-y-6">
            
            <div class="text-center mb-6">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Chi tiết đáp án</h2>
                <p class="text-sm text-gray-600">Xem lại các câu hỏi và đáp án chi tiết</p>
            </div>

            {{-- Question 1: Correct Answer --}}
            <x-card padding="p-5 sm:p-6" class="border-l-4 border-green-500">
                <div class="flex items-start gap-3 mb-4">
                    <x-badge flat positive label="Câu 1" class="shrink-0" />
                    <x-badge flat positive>
                        <x-icon name="check-circle" class="w-4 h-4 mr-1 inline" />
                        Đúng
                    </x-badge>
                </div>
                
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">
                    Trong mô hình OSI, tầng nào chịu trách nhiệm định tuyến và chuyển tiếp gói tin giữa các mạng khác nhau?
                </h3>

                <div class="space-y-3 mb-4">
                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 border border-gray-200">
                        <div class="shrink-0 mt-1">
                            <div class="w-5 h-5 rounded-full border-2 border-gray-300"></div>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm sm:text-base text-gray-700">A. Tầng Vật lý (Physical Layer)</div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 border border-gray-200">
                        <div class="shrink-0 mt-1">
                            <div class="w-5 h-5 rounded-full border-2 border-gray-300"></div>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm sm:text-base text-gray-700">B. Tầng Liên kết dữ liệu (Data Link Layer)</div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 rounded-lg bg-green-50 border-2 border-green-500">
                        <div class="shrink-0 mt-1">
                            <div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center">
                                <x-icon name="check" class="w-3 h-3 text-white" />
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm sm:text-base font-semibold text-green-900">
                                C. Tầng Mạng (Network Layer) 
                                <x-badge flat positive label="Đáp án của bạn" class="ml-2" />
                                <x-badge flat positive label="Đáp án đúng" class="ml-1" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 border border-gray-200">
                        <div class="shrink-0 mt-1">
                            <div class="w-5 h-5 rounded-full border-2 border-gray-300"></div>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm sm:text-base text-gray-700">D. Tầng Vận chuyển (Transport Layer)</div>
                        </div>
                    </div>
                </div>

                {{-- Explanation --}}
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <div class="flex gap-3">
                        <x-icon name="information-circle" class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" />
                        <div>
                            <h4 class="font-semibold text-blue-900 text-sm mb-1">Giải thích đáp án:</h4>
                            <p class="text-sm text-blue-800">
                                Tầng Mạng (Network Layer) trong mô hình OSI chịu trách nhiệm định tuyến và chuyển tiếp các gói tin giữa các mạng khác nhau. Đây là tầng thứ 3 trong mô hình 7 tầng OSI, xử lý địa chỉ logic (IP address) và quyết định đường đi tốt nhất cho dữ liệu.
                            </p>
                        </div>
                    </div>
                </div>
            </x-card>

            {{-- Question 2: Wrong Answer --}}
            <x-card padding="p-5 sm:p-6" class="border-l-4 border-red-500">
                <div class="flex items-start gap-3 mb-4">
                    <x-badge flat negative label="Câu 2" class="shrink-0" />
                    <x-badge flat negative>
                        <x-icon name="x-circle" class="w-4 h-4 mr-1 inline" />
                        Sai
                    </x-badge>
                </div>
                
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">
                    Thuật toán nào sau đây có độ phức tạp thời gian trung bình tốt nhất cho bài toán sắp xếp?
                </h3>

                <div class="space-y-3 mb-4">
                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 border border-gray-200">
                        <div class="shrink-0 mt-1">
                            <div class="w-5 h-5 rounded-full border-2 border-gray-300"></div>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm sm:text-base text-gray-700">A. Bubble Sort - O(n²)</div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 rounded-lg bg-red-50 border-2 border-red-500">
                        <div class="shrink-0 mt-1">
                            <div class="w-5 h-5 rounded-full bg-red-500 flex items-center justify-center">
                                <x-icon name="x-mark" class="w-3 h-3 text-white" />
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm sm:text-base font-semibold text-red-900">
                                B. Selection Sort - O(n²)
                                <x-badge flat negative label="Đáp án của bạn" class="ml-2" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 rounded-lg bg-green-50 border-2 border-green-500">
                        <div class="shrink-0 mt-1">
                            <div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center">
                                <x-icon name="check" class="w-3 h-3 text-white" />
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm sm:text-base font-semibold text-green-900">
                                C. Quick Sort - O(n log n)
                                <x-badge flat positive label="Đáp án đúng" class="ml-2" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 border border-gray-200">
                        <div class="shrink-0 mt-1">
                            <div class="w-5 h-5 rounded-full border-2 border-gray-300"></div>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm sm:text-base text-gray-700">D. Insertion Sort - O(n²)</div>
                        </div>
                    </div>
                </div>

                {{-- Explanation --}}
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <div class="flex gap-3">
                        <x-icon name="information-circle" class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" />
                        <div>
                            <h4 class="font-semibold text-blue-900 text-sm mb-1">Giải thích đáp án:</h4>
                            <p class="text-sm text-blue-800">
                                Quick Sort có độ phức tạp thời gian trung bình O(n log n), là một trong những thuật toán sắp xếp hiệu quả nhất. Các thuật toán Bubble Sort, Selection Sort và Insertion Sort đều có độ phức tạp O(n²), kém hiệu quả hơn với tập dữ liệu lớn.
                            </p>
                        </div>
                    </div>
                </div>
            </x-card>

            {{-- Question 3: Skipped --}}
            <x-card padding="p-5 sm:p-6" class="border-l-4 border-gray-400">
                <div class="flex items-start gap-3 mb-4">
                    <x-badge flat gray label="Câu 3" class="shrink-0" />
                    <x-badge flat gray>
                        <x-icon name="minus-circle" class="w-4 h-4 mr-1 inline" />
                        Bỏ qua
                    </x-badge>
                </div>
                
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">
                    Trong cơ sở dữ liệu quan hệ, khái niệm "Foreign Key" dùng để làm gì?
                </h3>

                <div class="space-y-3 mb-4">
                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 border border-gray-200 opacity-60">
                        <div class="shrink-0 mt-1">
                            <div class="w-5 h-5 rounded-full border-2 border-gray-300"></div>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm sm:text-base text-gray-700">A. Tạo index cho bảng</div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 rounded-lg bg-green-50 border-2 border-green-500">
                        <div class="shrink-0 mt-1">
                            <div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center">
                                <x-icon name="check" class="w-3 h-3 text-white" />
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm sm:text-base font-semibold text-green-900">
                                B. Liên kết dữ liệu giữa các bảng
                                <x-badge flat positive label="Đáp án đúng" class="ml-2" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 border border-gray-200 opacity-60">
                        <div class="shrink-0 mt-1">
                            <div class="w-5 h-5 rounded-full border-2 border-gray-300"></div>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm sm:text-base text-gray-700">C. Mã hóa dữ liệu</div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 border border-gray-200 opacity-60">
                        <div class="shrink-0 mt-1">
                            <div class="w-5 h-5 rounded-full border-2 border-gray-300"></div>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm sm:text-base text-gray-700">D. Sao lưu dữ liệu</div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 border-l-4 border-gray-400 p-4 rounded">
                    <p class="text-sm text-gray-600 italic">Bạn đã bỏ qua câu hỏi này.</p>
                </div>

                {{-- Explanation --}}
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mt-4">
                    <div class="flex gap-3">
                        <x-icon name="information-circle" class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" />
                        <div>
                            <h4 class="font-semibold text-blue-900 text-sm mb-1">Giải thích đáp án:</h4>
                            <p class="text-sm text-blue-800">
                                Foreign Key (Khóa ngoại) là một cột hoặc tập hợp các cột trong một bảng, tham chiếu đến Primary Key của bảng khác. Nó được sử dụng để thiết lập và duy trì mối quan hệ giữa các bảng trong cơ sở dữ liệu quan hệ, đảm bảo tính toàn vẹn tham chiếu của dữ liệu.
                            </p>
                        </div>
                    </div>
                </div>
            </x-card>

        </div>

    </div>
</div>

        {{-- Guest CTA Banner (for non-logged-in users) --}}
        @guest
        <x-card padding="none" class="overflow-hidden mt-8">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6 sm:p-8 lg:p-10 text-white relative">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                
                <div class="relative z-10 text-center">
                    <div class="mb-4">
                        <x-icon name="bookmark" class="w-16 h-16 mx-auto text-white opacity-90" />
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-bold mb-3">
                        Đăng nhập để lưu kết quả
                    </h3>
                    <p class="text-indigo-100 mb-6 max-w-2xl mx-auto text-base sm:text-lg">
                        Đăng nhập ngay để lưu kết quả bài thi, theo dõi tiến độ học tập và nhận được các đề xuất cá nhân hóa từ hệ thống.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <x-button white xl label="Đăng nhập ngay" href="{{ route('login') }}" class="font-semibold shadow-lg" />
                        <x-button outline white xl label="Tìm hiểu thêm" />
                    </div>
                </div>
            </div>
        </x-card>
        @endguest

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
            <x-button outline indigo xl label="Về trang chủ" icon="home" href="/" />
            <x-button outline indigo xl label="Làm lại bài thi" icon="arrow-path" />
            <x-button primary xl label="Thử đề thi khác" right-icon="arrow-right" />
        </div>

    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

@endsection
