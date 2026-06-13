@extends('learning::layouts.master')

@section('content')
<div class="min-h-screen bg-slate-50 text-slate-800 font-sans">
<div class="min-h-screen bg-slate-50 text-slate-800 font-sans">
    
    <div class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white py-12 px-6 shadow-md">
        <div class="max-w-5xl mx-auto">
            <span class="bg-blue-800 text-blue-200 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                Phân hệ Học Tập
            </span>
            <h1 class="text-3xl font-extrabold mt-3 tracking-tight">Lộ trình học: Trở thành Lập trình viên Full-stack</h1>
            <p class="text-blue-100 mt-2 max-w-2xl">Bắt đầu hành trình chinh phục kiến thức công nghệ từ con số 0 cùng IT-Learning.</p>
            
            <div class="mt-8 bg-blue-900/40 p-5 rounded-2xl backdrop-blur-sm border border-blue-400/20 max-w-xl">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-semibold text-blue-100 flex items-center gap-2">
                        <svg class="w-4 h-4 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Tiến độ lộ trình của bạn
                    </span>
                    <span class="text-lg font-bold text-cyan-300">45.00%</span>
                </div>
                <div class="w-full bg-blue-950 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-cyan-400 to-blue-400 h-3 rounded-full transition-all duration-500" style="width: 45%"></div>
                </div>
                <div class="flex justify-between items-center mt-3 text-xs text-blue-200">
                    <p>Trạng thái: <span class="text-cyan-300 font-medium">Đang học (learning)</span></p>
                    <p>Bắt đầu từ: 06/06/2026</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-6 py-10">
        <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            Danh sách bài học trong lộ trình
        </h2>

        <div class="space-y-4">
            <div class="bg-white border border-slate-200 p-5 rounded-xl shadow-sm hover:border-blue-300 transition flex items-center justify-between">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-green-50 text-green-600 rounded-lg font-bold mt-1 shadow-inner">01</div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg hover:text-blue-600 cursor-pointer">Tổng quan về Kiến trúc Web & HTTP</h3>
                        <p class="text-sm text-slate-500 mt-1 flex items-center gap-3">
                            <span>⏱ Thời gian hoàn thành: 05/06/2026</span>
                        </p>
                    </div>
                </div>
                <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1.5 rounded-md border border-green-200 uppercase">
                    Đã xong (completed)
                </span>
            </div>

            <div class="bg-white border-2 border-blue-500 p-5 rounded-xl shadow-sm hover:shadow-md transition flex items-center justify-between">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-lg font-bold mt-1 shadow-inner">02</div>
                    <div>
                        <h3 class="font-bold text-blue-600 text-lg">Xây dựng giao diện cơ bản với HTML5 và CSS3</h3>
                        <p class="text-sm text-slate-500 mt-1 flex items-center gap-3">
                            <span class="text-blue-500 font-medium animate-pulse">● Đang học dở dang...</span>
                        </p>
                    </div>
                </div>
                <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition shadow-sm shadow-blue-200">
                    Học tiếp
                </a>
            </div>

            <div class="bg-white border border-slate-200 p-5 rounded-xl shadow-sm opacity-75 hover:opacity-100 transition flex items-center justify-between">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-slate-100 text-slate-500 rounded-lg font-bold mt-1">03</div>
                    <div>
                        <h3 class="font-bold text-slate-700 text-lg">Lập trình Javascript cơ bản và xử lý mảng</h3>
                        <p class="text-sm text-slate-400 mt-1">Chưa bắt đầu học</p>
                    </div>
                </div>
                <span class="bg-slate-100 text-slate-600 text-xs font-semibold px-3 py-1.5 rounded-md uppercase">
                    Chưa học
                </span>
            </div>
        </div>
    </div>
</div>
@endsection