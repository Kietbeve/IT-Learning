{{-- 
    Exam Card - Partial View (Static Mockup)
    File này chỉ dùng nội dung mẫu tĩnh để test giao diện.
    Không phụ thuộc vào Model Exam hay bất kỳ biến nào từ Controller.
--}}

<div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-300 flex flex-col sm:flex-row">
    <!-- Header/Cover -->
    <div class="h-20 sm:h-auto sm:w-48 shrink-0 bg-blue-800 flex items-center justify-center text-white relative">
        <x-icon name="document-text" class="w-6 h-6 sm:w-8 sm:h-8 opacity-75" />

        {{-- Badge danh mục --}}
        <div class="absolute top-2 right-2">
            <x-badge flat white label="Backend" />
        </div>

        {{-- Badge loại bài thi --}}
        <div class="absolute bottom-2 left-2">
            <x-badge positive label="Miễn phí" />
        </div>
    </div>

    <!-- Content -->
    <div class="p-3 sm:p-4 flex-1 flex flex-col justify-between">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3 sm:gap-4">
            <!-- Title, Description & Meta -->
            <div class="flex-1">
                <h3 class="text-sm sm:text-base font-semibold text-gray-900 line-clamp-1 mb-0.5 sm:mb-1">
                    Bài thi đánh giá năng lực PHP &amp; Laravel
                </h3>

                <p class="text-[11px] sm:text-xs text-gray-500 line-clamp-1 mb-2 sm:mb-3">
                    Kiểm tra kiến thức cơ bản và nâng cao về ngôn ngữ PHP, kiến trúc MVC và framework Laravel.
                </p>

                <!-- Metadata Grid -->
                <div class="flex flex-wrap gap-x-3 sm:gap-x-4 gap-y-1.5 sm:gap-y-2 text-[11px] sm:text-xs text-gray-600">
                    <div class="flex items-center gap-1" title="Thời gian làm bài">
                        <x-icon name="clock" class="w-3.5 h-3.5 text-gray-400" />
                        <span>60 phút</span>
                    </div>
                    <div class="flex items-center gap-1" title="Điểm đỗ">
                        <x-icon name="check-circle" class="w-3.5 h-3.5 text-gray-400" />
                        <span>70% đỗ</span>
                    </div>
                    <div class="flex items-center gap-1" title="Số lượt làm">
                        <x-icon name="user-group" class="w-3.5 h-3.5 text-gray-400" />
                        <span>1,250 lượt</span>
                    </div>
                    <div class="flex items-center gap-1" title="Số câu hỏi">
                        <x-icon name="question-mark-circle" class="w-3.5 h-3.5 text-gray-400" />
                        <span>30 câu</span>
                    </div>
                </div>
            </div>

            <!-- Footer / Actions -->
            <div class="flex md:flex-col items-center md:items-end justify-between md:justify-start gap-3 mt-3 md:mt-0 pt-3 md:pt-0 border-t border-gray-100 md:border-t-0 shrink-0">
                <x-button indigo href="#" label="Xem chi tiết" right-icon="arrow-right" sm />

                <div class="flex items-center gap-1.5">
                    <div class="w-6 h-6 rounded-full bg-primary-100 flex items-center justify-center text-[10px] font-semibold text-primary-700">
                        N
                    </div>
                    <span class="text-xs text-gray-600 truncate max-w-24">
                        Nguyễn Văn A
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
