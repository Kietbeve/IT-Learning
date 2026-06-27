@extends('learning::layouts.master')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <div class="relative bg-white rounded-3xl p-6 sm:p-8 shadow-xl shadow-blue-500/5 border border-blue-50/80 overflow-hidden mb-10 transition-all">
        <div class="absolute right-0 top-0 w-80 h-80 bg-gradient-to-bl from-blue-100/40 to-transparent rounded-full -mr-20 -mt-20 blur-2xl pointer-events-none"></div>
        <div class="absolute left-1/3 bottom-0 w-40 h-40 bg-indigo-50/50 rounded-full blur-xl pointer-events-none"></div>

        <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Không gian quản trị Pro
                </div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight mt-2">Hệ Thống Quản Lý Học Thuật</h1>
                <p class="text-xs text-slate-500 font-medium">Thiết lập cấu trúc, điều phối bài học và tối ưu lộ trình DevAcademy.</p>
            </div>
            
            <div class="shrink-0 bg-slate-50 border border-slate-100 rounded-2xl px-4 py-3 text-right hidden md:block">
                <span class="block text-[10px] font-bold text-slate-400 uppercase">Hôm nay</span>
                <span class="text-xs font-black text-blue-600">{{ now()->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <a href="{{ route('manage.roadmap') }}" class="group relative bg-white border border-slate-100 rounded-3xl p-6 shadow-lg shadow-blue-900/[0.02] hover:shadow-xl hover:shadow-blue-500/10 hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col justify-between">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out opacity-60 pointer-events-none"></div>
            
            <div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20 mb-6 group-hover:rotate-6 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 4L9 7"/></svg>
                </div>
                <h3 class="text-base font-black text-slate-800 tracking-tight group-hover:text-blue-600 transition-colors">Quản Lý Lộ Trình</h3>
                <p class="text-xs text-slate-500 leading-relaxed mt-2">Xây dựng bản đồ định hướng nghề nghiệp, sắp xếp các chặng học từ Zero đến Hero.</p>
            </div>

            <div class="mt-8 pt-4 border-t border-slate-50 flex items-center justify-between text-[11px] font-bold text-blue-600">
                <span>Thiết lập sơ đồ</span>
                <svg class="w-4 h-4 transform group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </div>
        </a>

        <a href="{{ route('manage.detail') }}" class="group relative bg-white border border-slate-100 rounded-3xl p-6 shadow-lg shadow-blue-900/[0.02] hover:shadow-xl hover:shadow-blue-500/10 hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col justify-between">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-sky-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out opacity-60 pointer-events-none"></div>
            
            <div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-sky-500 to-blue-600 flex items-center justify-center text-white shadow-md shadow-sky-500/20 mb-6 group-hover:rotate-6 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375 0 11-.75 0 .375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375 0 11-.75 0 .375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375 0 11-.75 0 .375 0 01.75 0z"/></svg>
                </div>
                <h3 class="text-base font-black text-slate-800 tracking-tight group-hover:text-sky-600 transition-colors">Danh Sách Bài Học</h3>
                <p class="text-xs text-slate-500 leading-relaxed mt-2">Phân phối kho bài giảng, bài tập Project thực chiến và sắp xếp thứ tự học tập.</p>
            </div>

            <div class="mt-8 pt-4 border-t border-slate-50 flex items-center justify-between text-[11px] font-bold text-sky-600">
                <span>Cập nhật kho số</span>
                <svg class="w-4 h-4 transform group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </div>
        </a>

        <a href="{{ route('manage.lesson') }}" class="group relative bg-white border border-slate-100 rounded-3xl p-6 shadow-lg shadow-blue-900/[0.02] hover:shadow-xl hover:shadow-blue-500/10 hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col justify-between">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out opacity-60 pointer-events-none"></div>
            
            <div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white shadow-md shadow-indigo-500/20 mb-6 group-hover:rotate-6 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                <h3 class="text-base font-black text-slate-800 tracking-tight group-hover:text-indigo-600 transition-colors">Chi Tiết Bài Học</h3>
                <p class="text-xs text-slate-500 leading-relaxed mt-2">Biên tập mã nguồn, đính kèm tài liệu log debug và thiết lập môi trường Sandbox.</p>
            </div>

            <div class="mt-8 pt-4 border-t border-slate-50 flex items-center justify-between text-[11px] font-bold text-indigo-600">
                <span>Biên tập nội dung</span>
                <svg class="w-4 h-4 transform group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </div>
        </a>

    </div>

    <div class="mt-10 grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="p-4 rounded-2xl bg-white/5 border border-slate-800/60 backdrop-blur-sm text-center transition-all hover:border-blue-500/30">
            <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider">Lộ trình hoạt động</span>
            <span class="text-base font-black text-white mt-1 block">08</span>
        </div>
        <div class="p-4 rounded-2xl bg-white/5 border border-slate-800/60 backdrop-blur-sm text-center transition-all hover:border-sky-500/30">
            <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider">Video bài giảng</span>
            <span class="text-base font-black text-white mt-1 block">124</span>
        </div>
        <div class="p-4 rounded-2xl bg-white/5 border border-slate-800/60 backdrop-blur-sm text-center transition-all hover:border-indigo-500/30">
            <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider">Thử thách Code</span>
            <span class="text-base font-black text-white mt-1 block">45</span>
        </div>
        <div class="p-4 rounded-2xl bg-white/5 border border-slate-800/60 backdrop-blur-sm text-center transition-all hover:border-emerald-500/30">
            <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider">Tỷ lệ hoàn thành</span>
            <span class="text-base font-black text-emerald-400 mt-1 block">89.4%</span>
        </div>
    </div>
</div>
@endsection