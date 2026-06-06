{{-- 
    Exam Card - Partial View (Static Mockup)
    File này chỉ dùng nội dung mẫu tĩnh để test giao diện.
    Không phụ thuộc vào Model Exam hay bất kỳ biến nào từ Controller.
--}}

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-300 flex flex-col h-full">
    <!-- Header/Cover -->
    <div class="h-32 bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center text-white relative">
        <x-icon name="document-text" class="w-12 h-12 opacity-75" />

        {{-- Badge danh mục --}}
        <div class="absolute top-3 right-3">
            <x-badge flat white label="Backend" />
        </div>

        {{-- Badge loại bài thi --}}
        <div class="absolute top-3 left-3">
            <x-badge positive label="Miễn phí" />
        </div>
    </div>

    <!-- Content -->
    <div class="p-5 flex-1 flex flex-col">
        <!-- Title & Description -->
        <div class="mb-4 flex-1">
            <h3 class="text-lg font-semibold text-gray-900 line-clamp-2 mb-2">
                Bài thi đánh giá năng lực PHP &amp; Laravel
            </h3>

            <p class="text-sm text-gray-500 line-clamp-2">
                Kiểm tra kiến thức cơ bản và nâng cao về ngôn ngữ PHP, kiến trúc MVC và framework Laravel.
            </p>
        </div>

        <!-- Metadata Grid -->
        <div class="grid grid-cols-2 gap-3 mb-5 text-sm text-gray-600">
            <div class="flex items-center gap-1.5" title="Thời gian làm bài">
                <x-icon name="clock" class="w-4 h-4 text-gray-400" />
                <span>60 phút</span>
            </div>
            <div class="flex items-center gap-1.5" title="Điểm đỗ">
                <x-icon name="check-circle" class="w-4 h-4 text-gray-400" />
                <span>70% đỗ</span>
            </div>
            <div class="flex items-center gap-1.5" title="Số lượt làm">
                <x-icon name="user-group" class="w-4 h-4 text-gray-400" />
                <span>1,250 lượt</span>
            </div>
            <div class="flex items-center gap-1.5" title="Số câu hỏi">
                <x-icon name="question-mark-circle" class="w-4 h-4 text-gray-400" />
                <span>30 câu</span>
            </div>
        </div>

        <!-- Footer / Actions -->
        <div class="pt-4 border-t border-gray-100 flex items-center justify-between mt-auto">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-full bg-primary-100 flex items-center justify-center text-xs font-semibold text-primary-700">
                    N
                </div>
                <span class="text-sm text-gray-600 truncate max-w-[120px]">
                    Nguyễn Văn A
                </span>
            </div>

            <x-button
                primary
                right-icon="arrow-right"
                label="Xem chi tiết"
                href="#"
            />
        </div>
    </div>
</div>
