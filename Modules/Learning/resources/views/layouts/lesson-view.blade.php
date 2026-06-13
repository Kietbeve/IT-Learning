@extends('learning::layouts.master')

@section('content')
<div class="min-h-screen bg-slate-900 text-slate-100 font-sans flex flex-col">
<div class="min-h-screen bg-slate-900 text-slate-100 font-sans flex flex-col">
    
    <div class="bg-slate-800 border-b border-slate-700 px-6 py-4 flex justify-between items-center shadow-md">
        <div class="flex items-center gap-3">
            <a href="#" class="text-slate-400 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <p class="text-xs text-blue-400 uppercase font-bold tracking-wider">Bài số 02</p>
                <h1 class="text-base font-bold text-white">Xây dựng giao diện cơ bản với HTML5 và CSS3</h1>
            </div>
        </div>
        <div class="text-sm bg-blue-950 border border-blue-800 px-4 py-2 rounded-full text-blue-300 font-medium">
            Mã tiến độ: #EP-{{ $enrollment_id ?? '002' }}
        </div>
    </div>

    <div class="flex-1 grid grid-cols-1 lg:grid-cols-3">
        
        <div class="lg:col-span-2 p-6 flex flex-col gap-6 bg-slate-950">
            <div class="aspect-video bg-slate-800 rounded-2xl border border-slate-700 flex items-center justify-center relative overflow-hidden shadow-2xl group">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition duration-300"></div>
                <button class="w-16 h-16 bg-blue-600 hover:bg-blue-500 text-white rounded-full flex items-center justify-center shadow-lg transform group-hover:scale-110 transition z-10">
                    <svg class="w-8 h-8 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </button>
                <span class="absolute bottom-4 left-4 text-xs text-slate-300 z-10 bg-black/40 px-3 py-1 rounded-md backdrop-blur-sm">Video bài giảng: 15:45</span>
            </div>

            <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl">
                <h3 class="text-lg font-bold text-blue-400 mb-3">Tóm tắt nội dung học</h3>
                <p class="text-slate-300 text-sm leading-relaxed">
                    Trong bài học này, chúng ta sẽ cùng nhau tìm hiểu về cấu trúc các thẻ ngữ nghĩa (Semantic Tags) trong HTML5 như header, nav, section, article, footer và cách ứng dụng các thuộc tính CSS cơ bản để định hình màu sắc, khoảng cách (margin, padding) cho trang web.
                </p>
            </div>
        </div>

        <div class="bg-slate-900 border-t lg:border-t-0 lg:border-l border-slate-800 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Trạng thái bài học của bạn</h3>
                
                <div class="bg-slate-800 border border-slate-700 p-5 rounded-2xl shadow-inner">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-3 h-3 rounded-full bg-amber-500 animate-ping"></div>
                        <p class="text-sm font-semibold">Trạng thái: <span class="text-amber-400">Đang tiến hành (in_progress)</span></p>
                    </div>
                    
                    <p class="text-xs text-slate-400 leading-normal">
                        Hệ thống đã ghi nhận bạn bắt đầu bài học này vào lúc <span class="text-slate-300 font-mono">21:36:47</span>. Hoàn thành bài giảng để mở khóa bài tiếp theo.
                    </p>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-800 space-y-3">
                <button class="w-full bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-blue-900/30 transition transform active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Đánh dấu đã hoàn thành bài học
                </button>

                <button class="w-full bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-slate-200 font-medium py-2 px-4 rounded-xl text-xs transition">
                    Tạm dừng học bài này
                </button>
            </div>

        </div>
    </div>
</div>
@endsection