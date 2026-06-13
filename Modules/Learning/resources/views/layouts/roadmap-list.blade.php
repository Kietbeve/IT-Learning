@extends('layouts.user')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

<!-- Tiêu đề -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-blue-600">
        Danh sách lộ trình học tập
    </h1>

    <p class="text-gray-600 mt-2">
        Khám phá các lộ trình học tập dành cho sinh viên công nghệ thông tin
    </p>
</div>

<!-- Thanh tìm kiếm -->
<div class="mb-8">
    <input
        type="text"
        placeholder="Tìm kiếm lộ trình..."
        class="w-full border border-blue-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
    >
</div>

<!-- Danh sách roadmap -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

    <!-- Card 1 -->
    <div class="bg-white border border-blue-100 rounded-xl overflow-hidden shadow-sm">
        <div class="h-40 bg-blue-100"></div>

        <div class="p-5">
            <h3 class="text-xl font-semibold text-blue-600 mb-2">
                Lộ trình Frontend
            </h3>

            <p class="text-gray-600 text-sm mb-4">
                Học HTML, CSS, JavaScript, Bootstrap, ReactJS và xây dựng giao diện hiện đại.
            </p>

            <div class="flex justify-between text-sm text-gray-500 mb-4">
                <span>12 khóa học</span>
                <span>Cơ bản</span>
            </div>

            <a href="#"
                class="block text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg">
                Xem chi tiết
            </a>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-white border border-blue-100 rounded-xl overflow-hidden shadow-sm">
        <div class="h-40 bg-blue-100"></div>

        <div class="p-5">
            <h3 class="text-xl font-semibold text-blue-600 mb-2">
                Lộ trình Backend
            </h3>

            <p class="text-gray-600 text-sm mb-4">
                PHP, Laravel, MySQL, API RESTful và phát triển hệ thống máy chủ.
            </p>

            <div class="flex justify-between text-sm text-gray-500 mb-4">
                <span>10 khóa học</span>
                <span>Trung cấp</span>
            </div>

            <a href="#"
                class="block text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg">
                Xem chi tiết
            </a>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="bg-white border border-blue-100 rounded-xl overflow-hidden shadow-sm">
        <div class="h-40 bg-blue-100"></div>

        <div class="p-5">
            <h3 class="text-xl font-semibold text-blue-600 mb-2">
                Lộ trình Full Stack
            </h3>

            <p class="text-gray-600 text-sm mb-4">
                Kết hợp Frontend và Backend để xây dựng ứng dụng web hoàn chỉnh.
            </p>

            <div class="flex justify-between text-sm text-gray-500 mb-4">
                <span>18 khóa học</span>
                <span>Nâng cao</span>
            </div>

            <a href="#"
                class="block text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg">
                Xem chi tiết
            </a>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="bg-white border border-blue-100 rounded-xl overflow-hidden shadow-sm">
        <div class="h-40 bg-blue-100"></div>

        <div class="p-5">
            <h3 class="text-xl font-semibold text-blue-600 mb-2">
                Lộ trình DevOps
            </h3>

            <p class="text-gray-600 text-sm mb-4">
                Docker, Linux, Kubernetes, CI/CD và triển khai hệ thống.
            </p>

            <div class="flex justify-between text-sm text-gray-500 mb-4">
                <span>15 khóa học</span>
                <span>Nâng cao</span>
            </div>

            <a href="#"
                class="block text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg">
                Xem chi tiết
            </a>
        </div>
    </div>

    <!-- Card 5 -->
    <div class="bg-white border border-blue-100 rounded-xl overflow-hidden shadow-sm">
        <div class="h-40 bg-blue-100"></div>

        <div class="p-5">
            <h3 class="text-xl font-semibold text-blue-600 mb-2">
                Khoa học dữ liệu
            </h3>

            <p class="text-gray-600 text-sm mb-4">
                Python, Machine Learning, Data Analysis và AI.
            </p>

            <div class="flex justify-between text-sm text-gray-500 mb-4">
                <span>14 khóa học</span>
                <span>Nâng cao</span>
            </div>

            <a href="#"
                class="block text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg">
                Xem chi tiết
            </a>
        </div>
    </div>

    <!-- Card 6 -->
    <div class="bg-white border border-blue-100 rounded-xl overflow-hidden shadow-sm">
        <div class="h-40 bg-blue-100"></div>

        <div class="p-5">
            <h3 class="text-xl font-semibold text-blue-600 mb-2">
                An toàn thông tin
            </h3>

            <p class="text-gray-600 text-sm mb-4">
                Mạng máy tính, bảo mật hệ thống và kiểm thử xâm nhập.
            </p>

            <div class="flex justify-between text-sm text-gray-500 mb-4">
                <span>11 khóa học</span>
                <span>Trung cấp</span>
            </div>

            <a href="#"
                class="block text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg">
                Xem chi tiết
            </a>
        </div>
    </div>

</div>
```

</div>
@endsection
