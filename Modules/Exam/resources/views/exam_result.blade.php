{{-- Kế thừa layout chính của user --}}
@extends('layouts.user')

@section('content')
{{-- Container chính với gradient background từ xanh nhạt đến trắng --}}
<div 
    x-data="{
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
            return this.passed ? 'text-cyan-600' : 'text-purple-600';
        },
        
        getPassBgColor() {
            return this.passed ? 'bg-cyan-50 border-cyan-200' : 'bg-purple-50 border-purple-200';
        },
        
        getScoreBorderColor() {
            return this.passed ? 'border-cyan-500' : 'border-purple-500';
        },
        
        getScoreBgColor() {
            return this.passed ? 'bg-cyan-50' : 'bg-purple-50';
        }
    }" 
    class="bg-gradient-to-b from-blue-50 via-white to-slate-50 min-h-screen"
>
    {{-- Wrapper với max-width và padding responsive --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- ===== KHỐI HERO: Kết quả điểm số nổi bật ===== --}}
        <div class="relative overflow-hidden rounded-[32px] bg-gradient-to-r from-blue-700 via-blue-600 to-cyan-500 p-8 md:p-12 shadow-2xl mb-8">
            
            {{-- Hiệu ứng trang trí: 2 vòng tròn mờ làm background --}}
            <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-white/5"></div>
            
            {{-- Nội dung chính của hero --}}
            <div class="relative">
                {{-- Badge: Kết quả bài thi --}}
                <div class="flex justify-center mb-6">
                    <x-badge flat white label="Kết quả bài thi" class="text-sm" />
                </div>
                
                {{-- Tiêu đề bài thi --}}
                <h1 class="text-3xl lg:text-4xl font-extrabold text-white text-center leading-tight mb-8" x-text="examTitle"></h1>
                
                {{-- Score Circle - Hiển thị điểm số --}}
                <div class="flex justify-center mb-8">
                    <div class="relative">
                        <div class="w-40 h-40 sm:w-48 sm:h-48 rounded-full flex items-center justify-center bg-white/20 backdrop-blur-sm border-8 border-white/40">
                            <div class="text-center">
                                <div class="text-5xl sm:text-6xl font-extrabold text-white" x-text="score"></div>
                                <div class="text-base sm:text-lg text-blue-100 font-medium">điểm</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Pass/Fail Status Badge --}}
                <div class="flex justify-center">
                    <div class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl bg-white/20 backdrop-blur-sm border-2 border-white/40">
                        <template x-if="passed">
                            <x-icon name="check-circle" class="w-7 h-7 text-white" />
                        </template>
                        <template x-if="!passed">
                            <x-icon name="x-circle" class="w-7 h-7 text-white" />
                        </template>
                        <span class="font-bold text-xl text-white" x-text="getPassStatus()"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== LƯỚI THỐNG KÊ: 3 cột hiển thị kết quả chi tiết ===== --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            
            {{-- Card 1: Câu trả lời đúng --}}
            <div class="bg-white rounded-[28px] p-6 border border-cyan-100 shadow-md hover:shadow-lg transition-shadow">
                <div class="flex items-center gap-4">
                    {{-- Icon check trong nền cyan --}}
                    <div class="w-16 h-16 rounded-2xl bg-cyan-100 flex items-center justify-center shrink-0">
                        <x-icon name="check-circle" class="w-8 h-8 text-cyan-700" />
                    </div>
                    
                    {{-- Nội dung: Số câu đúng và phần trăm --}}
                    <div class="flex-1">
                        <div class="text-sm text-slate-500 mb-1">Câu trả lời đúng</div>
                        <div class="text-3xl font-bold text-slate-900" x-text="correctAnswers"></div>
                        <div class="text-xs text-cyan-600 font-medium mt-1">
                            <span x-text="((correctAnswers / totalQuestions) * 100).toFixed(1)"></span>% tổng số câu
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: Câu trả lời sai --}}
            <div class="bg-white rounded-[28px] p-6 border border-purple-100 shadow-md hover:shadow-lg transition-shadow">
                <div class="flex items-center gap-4">
                    {{-- Icon x trong nền purple --}}
                    <div class="w-16 h-16 rounded-2xl bg-purple-100 flex items-center justify-center shrink-0">
                        <x-icon name="x-circle" class="w-8 h-8 text-purple-700" />
                    </div>
                    
                    {{-- Nội dung: Số câu sai và phần trăm --}}
                    <div class="flex-1">
                        <div class="text-sm text-slate-500 mb-1">Câu trả lời sai</div>
                        <div class="text-3xl font-bold text-slate-900" x-text="wrongAnswers"></div>
                        <div class="text-xs text-purple-600 font-medium mt-1">
                            <span x-text="((wrongAnswers / totalQuestions) * 100).toFixed(1)"></span>% tổng số câu
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 3: Câu bỏ qua --}}
            <div class="bg-white rounded-[28px] p-6 border border-slate-100 shadow-md hover:shadow-lg transition-shadow">
                <div class="flex items-center gap-4">
                    {{-- Icon minus trong nền slate --}}
                    <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center shrink-0">
                        <x-icon name="minus-circle" class="w-8 h-8 text-slate-700" />
                    </div>
                    
                    {{-- Nội dung: Số câu bỏ qua và phần trăm --}}
                    <div class="flex-1">
                        <div class="text-sm text-slate-500 mb-1">Câu bỏ qua</div>
                        <div class="text-3xl font-bold text-slate-900" x-text="skippedAnswers"></div>
                        <div class="text-xs text-slate-600 font-medium mt-1">
                            <span x-text="((skippedAnswers / totalQuestions) * 100).toFixed(1)"></span>% tổng số câu
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ===== NÚT XEM CHI TIẾT ===== --}}
        <div class="flex justify-center mb-8">
            <x-button 
                outline 
                indigo 
                xl
                @click="showDetails = !showDetails"
                class="font-semibold shadow-md hover:shadow-lg transition-shadow">
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

        {{-- ===== PHẦN XEM LẠI CHI TIẾT CÂU HỎI ===== --}}
        <div x-show="showDetails" x-cloak x-transition class="space-y-6">
            
            {{-- Header với background blue --}}
            <div class="bg-white rounded-[28px] border border-blue-100 shadow-lg overflow-hidden">
                <div class="px-8 py-6 bg-gradient-to-r from-blue-50 to-cyan-50 border-b border-blue-100">
                    <div class="flex items-center gap-3">
                        <x-icon name="clipboard-document-list" class="w-7 h-7 text-blue-700" />
                        <div>
                            <h2 class="text-2xl font-bold text-blue-900">Chi tiết đáp án</h2>
                            <p class="text-sm text-blue-600 mt-1">Xem lại các câu hỏi và đáp án chi tiết</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== CÂU HỎI 1: TRẢ LỜI ĐÚNG ===== --}}
            <div class="bg-white rounded-[32px] border-l-4 border-cyan-500 shadow-lg overflow-hidden">
                
                {{-- Header câu hỏi với badges --}}
                <div class="px-6 sm:px-8 pt-6 pb-4">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <x-badge flat positive label="Câu 1" class="text-sm font-semibold" />
                        <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-cyan-50 border border-cyan-200">
                            <x-icon name="check-circle" class="w-5 h-5 text-cyan-600" />
                            <span class="font-bold text-cyan-700 text-sm">Đúng</span>
                        </div>
                    </div>
                    
                    {{-- Nội dung câu hỏi --}}
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 leading-relaxed">
                        Trong mô hình OSI, tầng nào chịu trách nhiệm định tuyến và chuyển tiếp gói tin giữa các mạng khác nhau?
                    </h3>
                </div>

                {{-- Danh sách đáp án --}}
                <div class="px-6 sm:px-8 pb-6 space-y-3">
                    
                    {{-- Đáp án A --}}
                    <div class="flex items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-200">
                        <div class="shrink-0 mt-1">
                            <div class="w-6 h-6 rounded-full border-2 border-slate-300"></div>
                        </div>
                        <div class="flex-1">
                            <div class="text-base text-slate-700">A. Tầng Vật lý (Physical Layer)</div>
                        </div>
                    </div>

                    {{-- Đáp án B --}}
                    <div class="flex items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-200">
                        <div class="shrink-0 mt-1">
                            <div class="w-6 h-6 rounded-full border-2 border-slate-300"></div>
                        </div>
                        <div class="flex-1">
                            <div class="text-base text-slate-700">B. Tầng Liên kết dữ liệu (Data Link Layer)</div>
                        </div>
                    </div>

                    {{-- Đáp án C - ĐÚNG (cả đáp án của bạn và đáp án đúng) --}}
                    <div class="flex items-start gap-3 p-4 rounded-2xl bg-cyan-50 border-2 border-cyan-500 shadow-sm">
                        <div class="shrink-0 mt-1">
                            <div class="w-6 h-6 rounded-full bg-cyan-500 flex items-center justify-center">
                                <x-icon name="check" class="w-4 h-4 text-white" />
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="text-base font-semibold text-cyan-900 mb-2">
                                C. Tầng Mạng (Network Layer)
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <x-badge flat positive label="Đáp án của bạn" class="text-xs" />
                                <x-badge flat positive label="Đáp án đúng" class="text-xs" />
                            </div>
                        </div>
                    </div>

                    {{-- Đáp án D --}}
                    <div class="flex items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-200">
                        <div class="shrink-0 mt-1">
                            <div class="w-6 h-6 rounded-full border-2 border-slate-300"></div>
                        </div>
                        <div class="flex-1">
                            <div class="text-base text-slate-700">D. Tầng Vận chuyển (Transport Layer)</div>
                        </div>
                    </div>
                </div>

                {{-- Giải thích đáp án --}}
                <div class="px-6 sm:px-8 pb-6">
                    <div class="bg-blue-50 border-l-4 border-blue-500 rounded-2xl p-5">
                        <div class="flex gap-3">
                            <x-icon name="information-circle" class="w-6 h-6 text-blue-600 shrink-0 mt-0.5" />
                            <div class="flex-1">
                                <h4 class="font-bold text-blue-900 text-sm mb-2">Giải thích đáp án:</h4>
                                <p class="text-sm text-blue-800 leading-relaxed">
                                    Tầng Mạng (Network Layer) trong mô hình OSI chịu trách nhiệm định tuyến và chuyển tiếp các gói tin giữa các mạng khác nhau. Đây là tầng thứ 3 trong mô hình 7 tầng OSI, xử lý địa chỉ logic (IP address) và quyết định đường đi tốt nhất cho dữ liệu.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ===== CÂU HỎI 2: TRẢ LỜI SAI ===== --}}
            <div class="bg-white rounded-[32px] border-l-4 border-purple-500 shadow-lg overflow-hidden">
                
                {{-- Header câu hỏi với badges --}}
                <div class="px-6 sm:px-8 pt-6 pb-4">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <x-badge flat negative label="Câu 2" class="text-sm font-semibold" />
                        <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-purple-50 border border-purple-200">
                            <x-icon name="x-circle" class="w-5 h-5 text-purple-600" />
                            <span class="font-bold text-purple-700 text-sm">Sai</span>
                        </div>
                    </div>
                    
                    {{-- Nội dung câu hỏi --}}
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 leading-relaxed">
                        Thuật toán nào sau đây có độ phức tạp thời gian trung bình tốt nhất cho bài toán sắp xếp?
                    </h3>
                </div>

                {{-- Danh sách đáp án --}}
                <div class="px-6 sm:px-8 pb-6 space-y-3">
                    
                    {{-- Đáp án A --}}
                    <div class="flex items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-200">
                        <div class="shrink-0 mt-1">
                            <div class="w-6 h-6 rounded-full border-2 border-slate-300"></div>
                        </div>
                        <div class="flex-1">
                            <div class="text-base text-slate-700">A. Bubble Sort - O(n²)</div>
                        </div>
                    </div>

                    {{-- Đáp án B - SAI (đáp án của người dùng) --}}
                    <div class="flex items-start gap-3 p-4 rounded-2xl bg-purple-50 border-2 border-purple-500 shadow-sm">
                        <div class="shrink-0 mt-1">
                            <div class="w-6 h-6 rounded-full bg-purple-500 flex items-center justify-center">
                                <x-icon name="x-mark" class="w-4 h-4 text-white" />
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="text-base font-semibold text-purple-900 mb-2">
                                B. Selection Sort - O(n²)
                            </div>
                            <x-badge flat negative label="Đáp án của bạn" class="text-xs" />
                        </div>
                    </div>

                    {{-- Đáp án C - ĐÚNG (đáp án đúng) --}}
                    <div class="flex items-start gap-3 p-4 rounded-2xl bg-cyan-50 border-2 border-cyan-500 shadow-sm">
                        <div class="shrink-0 mt-1">
                            <div class="w-6 h-6 rounded-full bg-cyan-500 flex items-center justify-center">
                                <x-icon name="check" class="w-4 h-4 text-white" />
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="text-base font-semibold text-cyan-900 mb-2">
                                C. Quick Sort - O(n log n)
                            </div>
                            <x-badge flat positive label="Đáp án đúng" class="text-xs" />
                        </div>
                    </div>

                    {{-- Đáp án D --}}
                    <div class="flex items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-200">
                        <div class="shrink-0 mt-1">
                            <div class="w-6 h-6 rounded-full border-2 border-slate-300"></div>
                        </div>
                        <div class="flex-1">
                            <div class="text-base text-slate-700">D. Insertion Sort - O(n²)</div>
                        </div>
                    </div>
                </div>

                {{-- Giải thích đáp án --}}
                <div class="px-6 sm:px-8 pb-6">
                    <div class="bg-blue-50 border-l-4 border-blue-500 rounded-2xl p-5">
                        <div class="flex gap-3">
                            <x-icon name="information-circle" class="w-6 h-6 text-blue-600 shrink-0 mt-0.5" />
                            <div class="flex-1">
                                <h4 class="font-bold text-blue-900 text-sm mb-2">Giải thích đáp án:</h4>
                                <p class="text-sm text-blue-800 leading-relaxed">
                                    Quick Sort có độ phức tạp thời gian trung bình O(n log n), là một trong những thuật toán sắp xếp hiệu quả nhất. Các thuật toán Bubble Sort, Selection Sort và Insertion Sort đều có độ phức tạp O(n²), kém hiệu quả hơn với tập dữ liệu lớn.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ===== CÂU HỎI 3: BỎ QUA ===== --}}
            <div class="bg-white rounded-[32px] border-l-4 border-slate-400 shadow-lg overflow-hidden">
                
                {{-- Header câu hỏi với badges --}}
                <div class="px-6 sm:px-8 pt-6 pb-4">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <x-badge flat gray label="Câu 3" class="text-sm font-semibold" />
                        <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-50 border border-slate-300">
                            <x-icon name="minus-circle" class="w-5 h-5 text-slate-600" />
                            <span class="font-bold text-slate-700 text-sm">Bỏ qua</span>
                        </div>
                    </div>
                    
                    {{-- Nội dung câu hỏi --}}
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 leading-relaxed">
                        Trong cơ sở dữ liệu quan hệ, khái niệm "Foreign Key" dùng để làm gì?
                    </h3>
                </div>

                {{-- Danh sách đáp án --}}
                <div class="px-6 sm:px-8 pb-6 space-y-3">
                    
                    {{-- Đáp án A --}}
                    <div class="flex items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-200 opacity-60">
                        <div class="shrink-0 mt-1">
                            <div class="w-6 h-6 rounded-full border-2 border-slate-300"></div>
                        </div>
                        <div class="flex-1">
                            <div class="text-base text-slate-700">A. Tạo index cho bảng</div>
                        </div>
                    </div>

                    {{-- Đáp án B - ĐÚNG (đáp án đúng) --}}
                    <div class="flex items-start gap-3 p-4 rounded-2xl bg-cyan-50 border-2 border-cyan-500 shadow-sm">
                        <div class="shrink-0 mt-1">
                            <div class="w-6 h-6 rounded-full bg-cyan-500 flex items-center justify-center">
                                <x-icon name="check" class="w-4 h-4 text-white" />
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="text-base font-semibold text-cyan-900 mb-2">
                                B. Liên kết dữ liệu giữa các bảng
                            </div>
                            <x-badge flat positive label="Đáp án đúng" class="text-xs" />
                        </div>
                    </div>

                    {{-- Đáp án C --}}
                    <div class="flex items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-200 opacity-60">
                        <div class="shrink-0 mt-1">
                            <div class="w-6 h-6 rounded-full border-2 border-slate-300"></div>
                        </div>
                        <div class="flex-1">
                            <div class="text-base text-slate-700">C. Mã hóa dữ liệu</div>
                        </div>
                    </div>

                    {{-- Đáp án D --}}
                    <div class="flex items-start gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-200 opacity-60">
                        <div class="shrink-0 mt-1">
                            <div class="w-6 h-6 rounded-full border-2 border-slate-300"></div>
                        </div>
                        <div class="flex-1">
                            <div class="text-base text-slate-700">D. Sao lưu dữ liệu</div>
                        </div>
                    </div>
                </div>

                {{-- Thông báo đã bỏ qua --}}
                <div class="px-6 sm:px-8 pb-6">
                    <div class="bg-slate-50 border-l-4 border-slate-400 rounded-2xl p-5">
                        <p class="text-sm text-slate-600 italic flex items-center gap-2">
                            <x-icon name="information-circle" class="w-5 h-5 text-slate-500" />
                            Bạn đã bỏ qua câu hỏi này.
                        </p>
                    </div>
                </div>

                {{-- Giải thích đáp án --}}
                <div class="px-6 sm:px-8 pb-6">
                    <div class="bg-blue-50 border-l-4 border-blue-500 rounded-2xl p-5">
                        <div class="flex gap-3">
                            <x-icon name="information-circle" class="w-6 h-6 text-blue-600 shrink-0 mt-0.5" />
                            <div class="flex-1">
                                <h4 class="font-bold text-blue-900 text-sm mb-2">Giải thích đáp án:</h4>
                                <p class="text-sm text-blue-800 leading-relaxed">
                                    Foreign Key (Khóa ngoại) là một cột hoặc tập hợp các cột trong một bảng, tham chiếu đến Primary Key của bảng khác. Nó được sử dụng để thiết lập và duy trì mối quan hệ giữa các bảng trong cơ sở dữ liệu quan hệ, đảm bảo tính toàn vẹn tham chiếu của dữ liệu.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

        {{-- ===== KHỐI THÔNG BÁO CHO GUEST ===== --}}
        @guest
        <div class="bg-white rounded-[32px] overflow-hidden border border-blue-100 shadow-xl mt-8">
            <div class="relative bg-gradient-to-r from-blue-700 via-blue-600 to-cyan-500 p-8 sm:p-10 lg:p-12 text-white">
                
                {{-- Hiệu ứng trang trí background --}}
                <div class="absolute -right-12 -top-12 h-48 w-48 rounded-full bg-white/10"></div>
                <div class="absolute -bottom-16 -left-16 h-56 w-56 rounded-full bg-white/5"></div>
                
                <div class="relative text-center">
                    {{-- Icon bookmark --}}
                    <div class="mb-6">
                        <div class="w-20 h-20 mx-auto rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            <x-icon name="bookmark" class="w-10 h-10 text-white" />
                        </div>
                    </div>
                    
                    {{-- Tiêu đề --}}
                    <h3 class="text-3xl sm:text-4xl font-extrabold mb-4">
                        Đăng nhập để lưu kết quả
                    </h3>
                    
                    {{-- Mô tả --}}
                    <p class="text-blue-100 text-lg mb-8 max-w-2xl mx-auto leading-relaxed">
                        Đăng nhập ngay để lưu kết quả bài thi, theo dõi tiến độ học tập và nhận được các đề xuất cá nhân hóa từ hệ thống.
                    </p>
                    
                    {{-- Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <x-button 
                            white 
                            xl 
                            label="Đăng nhập ngay" 
                            href="{{ route('login') }}" 
                            icon="arrow-right"
                            class="font-semibold shadow-lg hover:shadow-xl transition-shadow" 
                        />
                        <x-button 
                            outline 
                            white 
                            xl 
                            label="Tìm hiểu thêm"
                            icon="information-circle"
                            class="font-semibold"
                        />
                    </div>
                </div>
            </div>
        </div>
        @endguest

        {{-- ===== NÚT HÀNH ĐỘNG ===== --}}
        <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8 mb-4">
            <x-button 
                outline 
                indigo 
                xl 
                label="Về trang chủ" 
                icon="home" 
                href="/" 
                class="font-semibold shadow-md hover:shadow-lg transition-shadow"
            />
            <x-button 
                outline 
                indigo 
                xl 
                label="Làm lại bài thi" 
                icon="arrow-path"
                class="font-semibold shadow-md hover:shadow-lg transition-shadow"
            />
            <x-button 
                indigo 
                xl 
                label="Thử đề thi khác" 
                right-icon="arrow-right"
                class="font-semibold shadow-md hover:shadow-lg transition-shadow"
            />
        </div>

    </div>
    {{-- Kết thúc max-width wrapper --}}
</div>
{{-- Kết thúc container chính --}}

<style>
    [x-cloak] { display: none !important; }
</style>

@endsection
