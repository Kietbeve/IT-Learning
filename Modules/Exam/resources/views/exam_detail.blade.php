{{-- Kế thừa layout chính của user --}}
@extends('layouts.user')

@section('content')
{{-- Container chính với gradient background từ xanh nhạt đến trắng --}}
<div class="bg-gradient-to-b from-blue-50 via-white to-slate-50 min-h-screen">
    {{-- Wrapper với max-width và padding responsive --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Grid layout: 8 cột cho nội dung chính, 4 cột cho sidebar trên màn hình lớn --}}
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">

            {{-- ========== PHẦN NỘI DUNG CHÍNH (BÊN TRÁI) ========== --}}
            <div class="xl:col-span-8 space-y-8">

                {{-- ===== KHỐI HERO: Tiêu đề và thông tin nổi bật ===== --}}
                <div class="relative overflow-hidden rounded-[32px] bg-gradient-to-r from-blue-700 via-blue-600 to-cyan-500 p-8 md:p-12 shadow-2xl">

                    {{-- Hiệu ứng trang trí: 2 vòng tròn mờ làm background --}}
                    <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-white/5"></div>

                    {{-- Nội dung chính của hero --}}
                    <div class="relative">
                        {{-- Badges: Hiển thị danh mục và loại bài thi --}}
                        <div class="flex flex-wrap gap-2 mb-6">
                            <x-badge flat primary :label="$exam->category->name" />
                            <x-badge flat gray :label="ucfirst($exam->type)" />
                        </div>

                        {{-- Tiêu đề bài thi: Lấy từ database --}}
                        <h1 class="text-4xl lg:text-5xl font-extrabold text-white leading-tight mb-5">
                            {{ $exam->title }}
                        </h1>

                        {{-- Mô tả ngắn về bài thi --}}
                        <p class="text-blue-100 text-lg leading-8 max-w-3xl">
                            {{ $exam->description }}
                        </p>

                        {{-- Thông tin tác giả --}}
                        <div class="flex items-center gap-4 mt-10">
                            {{-- Avatar tác giả: Hiển thị chữ cái đầu tiên của tên --}}
                            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-white text-xl font-bold">
                                {{ strtoupper(substr($exam->author->name, 0, 1)) }}
                            </div>

                            {{-- Tên và vai trò của tác giả --}}
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

                {{-- ===== KHỐI MÔ TẢ CHI TIẾT ===== --}}
                <div class="bg-white rounded-[32px] border border-blue-100 shadow-lg overflow-hidden">

                    {{-- Header của khung mô tả --}}
                    <div class="px-8 py-6 border-b border-blue-100 bg-blue-50">
                        <h2 class="text-xl font-bold text-blue-900">
                            Thông tin chi tiết
                        </h2>
                    </div>

                    {{-- Nội dung mô tả chi tiết bài thi --}}
                    <div class="p-8">
                        <p class="text-slate-600 leading-8 text-base">
                            {{ $exam->description }}
                        </p>
                    </div>
                </div>

                {{-- ===== LƯỚI THÔNG TIN: 2 cột hiển thị tác giả và danh mục ===== --}}
                <div class="grid md:grid-cols-2 gap-6">

                    {{-- Card 1: Thông tin tác giả --}}
                    <div class="bg-white rounded-[28px] p-6 border border-blue-100 shadow-md">
                        <div class="flex items-center gap-4">
                            {{-- Icon người dùng --}}
                            <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center">
                                <x-icon name="user" class="w-7 h-7 text-blue-700" />
                            </div>

                            {{-- Nội dung: Tên tác giả --}}
                            <div>
                                <div class="text-sm text-slate-500">Tác giả</div>
                                <div class="font-bold text-slate-900">
                                    {{ $exam->author->name }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Thông tin danh mục --}}
                    <div class="bg-white rounded-[28px] p-6 border border-blue-100 shadow-md">
                        <div class="flex items-center gap-4">
                            {{-- Icon thư mục --}}
                            <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center">
                                <x-icon name="folder" class="w-7 h-7 text-blue-700" />
                            </div>

                            {{-- Nội dung: Tên danh mục --}}
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

            {{-- ========== SIDEBAR (BÊN PHẢI) - 4 cột trên màn hình lớn ========== --}}
            <div class="xl:col-span-4">

                {{-- Sticky container: Giữ sidebar cố định khi scroll --}}
                <div class="sticky top-6 space-y-6">

                    {{-- ===== CARD CHÍNH: Thông tin bài thi và nút bắt đầu ===== --}}
                    <div class="bg-white rounded-[32px] overflow-hidden border border-blue-100 shadow-xl">

                        {{-- Header gradient xanh với icon --}}
                        <div class="bg-gradient-to-r from-blue-700 to-blue-500 p-8 text-white">

                            <div class="flex justify-between items-center">
                                <div>
                                    {{-- Tiêu đề phụ --}}
                                    <div class="text-blue-100 text-sm">
                                        Bảng thông tin
                                    </div>

                                    {{-- Tiêu đề chính --}}
                                    <div class="text-2xl font-bold mt-1">
                                        Bài kiểm tra
                                    </div>
                                </div>

                                {{-- Icon clipboard trang trí --}}
                                <x-icon
                                    name="clipboard-document-check"
                                    class="w-16 h-16 opacity-80"
                                />
                            </div>

                        </div>

                        {{-- Nội dung bên trong card --}}
                        <div class="p-6">

                            {{-- Danh sách thông tin chi tiết bài thi (4 mục) --}}
                            <div class="space-y-4">

                                {{-- Mục 1: Thời gian làm bài --}}
                                <div class="rounded-2xl bg-slate-50 p-4 flex justify-between items-center">
                                    <span class="text-slate-500">Thời gian</span>
                                    <span class="font-bold text-slate-900">
                                        {{ $exam->duration_minutes }} phút
                                    </span>
                                </div>

                                {{-- Mục 2: Tổng số câu hỏi --}}
                                <div class="rounded-2xl bg-slate-50 p-4 flex justify-between items-center">
                                    <span class="text-slate-500">Số câu hỏi</span>
                                    <span class="font-bold text-slate-900">
                                        {{ $exam->questions_count }}
                                    </span>
                                </div>

                                {{-- Mục 3: Điểm đạt tối thiểu (%) - Xóa số 0 thừa ở cuối --}}
                                <div class="rounded-2xl bg-slate-50 p-4 flex justify-between items-center">
                                    <span class="text-slate-500">Điểm đạt</span>
                                    <span class="font-bold text-slate-900">
                                        {{ rtrim(rtrim($exam->pass_percent, '0'), '.') }}%
                                    </span>
                                </div>

                                {{-- Mục 4: Hình thức bài thi (quiz/test/exam) --}}
                                <div class="rounded-2xl bg-slate-50 p-4 flex justify-between items-center">
                                    <span class="text-slate-500">Hình thức</span>
                                    <span class="font-bold text-slate-900">
                                        {{ ucfirst($exam->type) }}
                                    </span>
                                </div>

                            </div>

                            {{-- Phần nút bắt đầu làm bài và xử lý lỗi --}}
                            <div class="mt-8">

                                {{-- Hiển thị thông báo lỗi nếu có --}}
                                @if ($errors->has('exam'))
                                    <x-alert
                                        negative
                                        :title="$errors->first('exam')"
                                        class="mb-4"
                                    />
                                @endif

                                {{-- Form bắt đầu làm bài thi
                                     - x-data: Khởi tạo Alpine.js với 2 biến state
                                       + confirmModal: điều khiển hiển thị modal xác nhận
                                       + submitting: ngăn submit nhiều lần
                                     - x-ref: Tham chiếu form để submit từ modal
                                     - @keydown.escape: Đóng modal khi nhấn ESC
                                --}}
                                <form
                                    x-ref="examForm"
                                    method="POST"
                                    action="{{ route('exam.attempt.start', $exam->slug) }}"
                                    x-data="{confirmModal:false,submitting:false}"
                                    @keydown.escape.window="confirmModal = false"
                                    onsubmit="console.log('submit');"
                                >
                                    @csrf

                                    {{-- Nút chính: Bắt đầu làm bài (mở modal xác nhận) --}}
                                    <x-button
                                        type="button"
                                        indigo
                                        xl
                                        right-icon="arrow-right"
                                        class="w-full justify-center"
                                        label="Bắt đầu làm bài"
                                        @click="confirmModal = true"
                                    />

                                    {{-- ===== MODAL XÁC NHẬN BẮT ĐẦU LÀM BÀI =====
                                         - x-show: Hiển thị khi confirmModal = true
                                         - x-cloak: Ẩn element trước khi Alpine.js load
                                         - z-50: Đặt modal lên trên cùng
                                    --}}
                                    <div
                                        x-show="confirmModal"
                                        x-cloak
                                        class="fixed inset-0 z-50"
                                        style="display:none;"
                                    >
                                        {{-- Overlay mờ đen phía sau modal
                                             - Click vào overlay sẽ đóng modal
                                             - x-transition.opacity: Hiệu ứng fade in/out
                                        --}}
                                        <div
                                            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
                                            x-transition.opacity
                                            @click="confirmModal = false"
                                        ></div>

                                        {{-- Container căn giữa modal --}}
                                        <div class="relative flex min-h-screen items-center justify-center p-4">

                                            {{-- Hộp modal chính
                                                 - @click.stop: Ngăn click vào modal đóng modal
                                                 - x-transition: Hiệu ứng animation xuất hiện
                                            --}}
                                            <div
                                                @click.stop
                                                x-transition
                                                class="w-full max-w-lg bg-white rounded-[32px] overflow-hidden shadow-2xl"
                                            >

                                                {{-- Nội dung modal --}}
                                                <div class="p-8">

                                                    {{-- Icon dấu hỏi --}}
                                                    <div class="w-16 h-16 rounded-2xl bg-blue-100 flex items-center justify-center mb-5">
                                                        <x-icon
                                                            name="question-mark-circle"
                                                            class="w-8 h-8 text-blue-700"
                                                        />
                                                    </div>

                                                    {{-- Tiêu đề modal --}}
                                                    <h3 class="text-xl font-bold text-slate-900 mb-2">
                                                        Xác nhận làm bài kiểm tra
                                                    </h3>

                                                    {{-- Nội dung thông báo --}}
                                                    <p class="text-slate-600">
                                                        Bạn có chắc chắn muốn bắt đầu bài kiểm tra này?
                                                    </p>

                                                </div>

                                                {{-- Footer modal với 2 nút: Hủy và Đồng ý --}}
                                                <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3">

                                                    {{-- Nút Hủy: Đóng modal --}}
                                                    <x-button
                                                        type="button"
                                                        flat
                                                        gray
                                                        label="Hủy"
                                                        @click="confirmModal = false"
                                                    />

                                                    {{-- Nút Đồng ý: Submit form bắt đầu làm bài
                                                         - x-bind:disabled: Vô hiệu hóa khi đang submit
                                                         - x-on:click: Set submitting=true rồi submit form
                                                    --}}
                                                    <x-button
                                                        type="button"
                                                        indigo
                                                        label="Đồng ý"
                                                        x-bind:disabled="submitting"
                                                        x-on:click="submitting = true; $refs.examForm.submit()"
                                                    />

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </form>

                                {{-- Nút phụ: Lưu bài thi (chức năng bookmark) --}}
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

                    {{-- ===== KHỐI THÔNG BÁO LƯU Ý ===== --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-[28px] p-6">
                        <div class="flex gap-3">
                            {{-- Icon thông tin --}}
                            <x-icon
                                name="information-circle"
                                class="w-6 h-6 text-blue-700 shrink-0"
                            />

                            {{-- Nội dung lưu ý --}}
                            <div>
                                <div class="font-semibold text-blue-900">
                                    Lưu ý
                                </div>

                                {{-- Hiển thị % điểm cần đạt để pass --}}
                                <div class="text-sm text-blue-700 mt-1">
                                    Hoàn thành tối thiểu
                                    {{ rtrim(rtrim($exam->pass_percent, '0'), '.') }}%
                                    số điểm để vượt qua bài thi.
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                {{-- Kết thúc sticky container --}}

            </div>
            {{-- Kết thúc sidebar --}}

        </div>
        {{-- Kết thúc grid layout chính --}}

    </div>
    {{-- Kết thúc max-width wrapper --}}
</div>
{{-- Kết thúc container chính --}}
@endsection
