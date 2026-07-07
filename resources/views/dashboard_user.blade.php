

@extends('layouts.user')

@section('content')
    <div class="max-w-7xl mx-auto pb-8 pt-4 px-4 sm:px-6 lg:px-8 space-y-10">

      @auth
            @php
                $isVip = Auth::user()->vip_expires_at && Auth::user()->vip_expires_at->isFuture();
            @endphp

            @if (!$isVip) 
                <div x-data="{ 
                    show: true,
                    dismiss() { this.show = false; }
                }" 
                     x-show="show" 
                     x-cloak 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-4" 
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0" 
                     x-transition:leave-end="opacity-0 -translate-y-4">
                    
                    <div class="relative bg-gradient-to-br from-amber-400 via-yellow-400 to-orange-400 rounded-xl shadow-lg overflow-hidden">
                        {{-- Close button --}}
                        <button @click="dismiss" 
                            class="absolute top-2 right-2 z-20 p-1.5 hover:bg-white/20 rounded-lg transition-all duration-200 group"
                            title="Đóng">
                            <svg class="w-4 h-4 text-white group-hover:rotate-90 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <div class="relative z-10 p-3 sm:p-4">
                            <div class="flex flex-col md:flex-row items-center gap-3">
                                {{-- Icon section --}}
                                <div class="shrink-0">
                                    <div class="relative">
                                        <div class="w-12 h-12 bg-gradient-to-br from-white to-amber-50 rounded-xl shadow-md flex items-center justify-center">
                                            <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                            </svg>
                                        </div>
                                        <div class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 rounded-full flex items-center justify-center text-white text-[9px] font-bold">
                                            VIP
                                        </div>
                                    </div>
                                </div>

                                {{-- Content section --}}
                                <div class="flex-1 text-center md:text-left">
                                    <h3 class="text-base sm:text-lg font-bold text-white mb-1">
                                        Nâng cấp tài khoản VIP ngay!
                                    </h3>
                                    <p class="text-amber-50 text-xs sm:text-sm mb-2">
                                        Tải không giới hạn tài liệu Premium & Hỗ trợ ưu tiên. 
                                        <span class="font-bold text-white">Chỉ 99k/tháng</span>
                                    </p>
                                    
                                    {{-- Features list --}}
                                    <div class="flex flex-wrap gap-1.5 justify-center md:justify-start">
                                        <div class="flex items-center gap-1 bg-white/10 backdrop-blur-sm px-2 py-0.5 rounded">
                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-[10px] sm:text-xs text-white font-medium">Tải không giới hạn</span>
                                        </div>
                                        <div class="flex items-center gap-1 bg-white/10 backdrop-blur-sm px-2 py-0.5 rounded">
                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-[10px] sm:text-xs text-white font-medium">Tài liệu độc quyền</span>
                                        </div>
                                        <div class="flex items-center gap-1 bg-white/10 backdrop-blur-sm px-2 py-0.5 rounded">
                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-[10px] sm:text-xs text-white font-medium">Hỗ trợ ưu tiên</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- CTA section --}}
                                <div class="shrink-0">
                                    <a href="{{ route('user.subscription') }}"
                                        class="px-4 py-2 bg-white hover:bg-gray-50 text-amber-600 rounded-lg text-sm font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 text-center inline-block">
                                        <span class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                            Nâng cấp
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endauth
        
        {{-- Welcome Bar --}}
        @auth
        {{-- Chao khi da danh nhap --}}
          <div class="bg-linear-to-r from-blue-50 to-indigo-50 rounded-xl border border-indigo-100 shadow-sm p-4 sm:p-5 flex items-center justify-between">
              <div class="flex items-center gap-3">
                  <div class="hidden sm:flex items-center justify-center bg-indigo-100 w-12 h-12 rounded-full">
                      <x-icon name="hand-raised" class="w-6 h-6 text-indigo-600" />
                  </div>
                  <div>
                      <h2 class="text-lg sm:text-xl font-bold text-gray-900">
                          Chào mừng trở lại, {{ auth()->user()->name }}! 👋
                      </h2>
                      <p class="text-sm text-gray-600 mt-0.5">Hôm nay bạn muốn học gì?</p>
                  </div>
              </div>
          </div>
        @endauth


        {{-- 1. Hero Section --}}
        <div class="bg-linear-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-2xl shadow-xl p-8 sm:p-12 lg:p-16 text-center text-white relative overflow-hidden">
            {{-- Decorative elements --}}
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            
            <div class="relative z-10">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4 sm:mb-6">
                    Khám phá kho tài liệu IT hàng đầu
                </h1>
                <p class="text-base sm:text-lg lg:text-xl text-indigo-50 mb-6 sm:mb-8 max-w-3xl mx-auto">
                    Truy cập hàng nghìn tài liệu chất lượng cao, đề thi thực tế và lộ trình học tập được xây dựng bởi các chuyên gia trong ngành. Bắt đầu hành trình chinh phục kiến thức IT của bạn ngay hôm nay.
                </p>
                <x-button primary xl href="/documents" label="Khám phá tài liệu" right-icon="arrow-right" class="shadow-lg shadow-indigo-900/30 font-semibold" />
            </div>
        </div>

        {{-- 2. Danh mục nổi bật --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Danh mục nổi bật</h2>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3 sm:gap-4">
                {{-- Category Card 1: Lập trình Web --}}
                <a href="#" class="group">
                    <x-card padding="p-5 sm:p-6" class="hover:shadow-lg hover:border-indigo-300 transition-all cursor-pointer border-2 border-transparent h-full">
                        <div class="flex flex-col items-center text-center gap-3">
                            <div class="p-3 bg-blue-50 rounded-xl text-blue-600 group-hover:bg-blue-100 transition-colors">
                                <x-icon name="code-bracket" class="w-8 h-8" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-sm sm:text-base text-gray-900 mb-1">Lập trình Web</h3>
                                <p class="text-xs text-gray-500">156 tài liệu</p>
                            </div>
                        </div>
                    </x-card>
                </a>

                {{-- Category Card 2: Mobile App --}}
                <a href="#" class="group">
                    <x-card padding="p-5 sm:p-6" class="hover:shadow-lg hover:border-green-300 transition-all cursor-pointer border-2 border-transparent h-full">
                        <div class="flex flex-col items-center text-center gap-3">
                            <div class="p-3 bg-green-50 rounded-xl text-green-600 group-hover:bg-green-100 transition-colors">
                                <x-icon name="device-phone-mobile" class="w-8 h-8" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-sm sm:text-base text-gray-900 mb-1">Mobile App</h3>
                                <p class="text-xs text-gray-500">98 tài liệu</p>
                            </div>
                        </div>
                    </x-card>
                </a>

                {{-- Category Card 3: Database --}}
                <a href="#" class="group">
                    <x-card padding="p-5 sm:p-6" class="hover:shadow-lg hover:border-purple-300 transition-all cursor-pointer border-2 border-transparent h-full">
                        <div class="flex flex-col items-center text-center gap-3">
                            <div class="p-3 bg-purple-50 rounded-xl text-purple-600 group-hover:bg-purple-100 transition-colors">
                                <x-icon name="circle-stack" class="w-8 h-8" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-sm sm:text-base text-gray-900 mb-1">Database</h3>
                                <p class="text-xs text-gray-500">124 tài liệu</p>
                            </div>
                        </div>
                    </x-card>
                </a>

                {{-- Category Card 4: DevOps --}}
                <a href="#" class="group">
                    <x-card padding="p-5 sm:p-6" class="hover:shadow-lg hover:border-orange-300 transition-all cursor-pointer border-2 border-transparent h-full">
                        <div class="flex flex-col items-center text-center gap-3">
                            <div class="p-3 bg-orange-50 rounded-xl text-orange-600 group-hover:bg-orange-100 transition-colors">
                                <x-icon name="server" class="w-8 h-8" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-sm sm:text-base text-gray-900 mb-1">DevOps</h3>
                                <p class="text-xs text-gray-500">87 tài liệu</p>
                            </div>
                        </div>
                    </x-card>
                </a>

                {{-- Category Card 5: AI & ML --}}
                <a href="#" class="group">
                    <x-card padding="p-5 sm:p-6" class="hover:shadow-lg hover:border-pink-300 transition-all cursor-pointer border-2 border-transparent h-full">
                        <div class="flex flex-col items-center text-center gap-3">
                            <div class="p-3 bg-pink-50 rounded-xl text-pink-600 group-hover:bg-pink-100 transition-colors">
                                <x-icon name="cpu-chip" class="w-8 h-8" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-sm sm:text-base text-gray-900 mb-1">AI & ML</h3>
                                <p class="text-xs text-gray-500">143 tài liệu</p>
                            </div>
                        </div>
                    </x-card>
                </a>

                {{-- Category Card 6: Security --}}
                <a href="#" class="group">
                    <x-card padding="p-5 sm:p-6" class="hover:shadow-lg hover:border-red-300 transition-all cursor-pointer border-2 border-transparent h-full">
                        <div class="flex flex-col items-center text-center gap-3">
                            <div class="p-3 bg-red-50 rounded-xl text-red-600 group-hover:bg-red-100 transition-colors">
                                <x-icon name="shield-check" class="w-8 h-8" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-sm sm:text-base text-gray-900 mb-1">Security</h3>
                                <p class="text-xs text-gray-500">76 tài liệu</p>
                            </div>
                        </div>
                    </x-card>
                </a>

                {{-- Category Card 7: Cloud Computing --}}
                <a href="#" class="group">
                    <x-card padding="p-5 sm:p-6" class="hover:shadow-lg hover:border-cyan-300 transition-all cursor-pointer border-2 border-transparent h-full">
                        <div class="flex flex-col items-center text-center gap-3">
                            <div class="p-3 bg-cyan-50 rounded-xl text-cyan-600 group-hover:bg-cyan-100 transition-colors">
                                <x-icon name="cloud" class="w-8 h-8" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-sm sm:text-base text-gray-900 mb-1">Cloud Computing</h3>
                                <p class="text-xs text-gray-500">112 tài liệu</p>
                            </div>
                        </div>
                    </x-card>
                </a>

                {{-- Category Card 8: UI/UX Design --}}
                <a href="#" class="group">
                    <x-card padding="p-5 sm:p-6" class="hover:shadow-lg hover:border-amber-300 transition-all cursor-pointer border-2 border-transparent h-full">
                        <div class="flex flex-col items-center text-center gap-3">
                            <div class="p-3 bg-amber-50 rounded-xl text-amber-600 group-hover:bg-amber-100 transition-colors">
                                <x-icon name="sparkles" class="w-8 h-8" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-sm sm:text-base text-gray-900 mb-1">UI/UX Design</h3>
                                <p class="text-xs text-gray-500">91 tài liệu</p>
                            </div>
                        </div>
                    </x-card>
                </a>
            </div>
        </div>

        {{-- 3. Top tài liệu (8 cards) --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Top tài liệu tải nhiều nhất</h2>
                <a href="/documents" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Xem thêm &rarr;</a>
            </div>
            {{-- Document card --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 lg:gap-6">
                {{-- Document Card 1 --}}
                <x-card padding="none" class="overflow-hidden hover:shadow-lg transition-shadow flex flex-col h-full border border-gray-100 group">
                    <div class="h-40 bg-linear-to-br from-blue-50 to-indigo-50 flex items-center justify-center border-b border-gray-100">
                        <x-icon name="document-text" class="w-16 h-16 text-indigo-400 group-hover:scale-110 transition-transform" />
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 mb-2">
                            <x-badge flat primary label="PDF" />
                            <span class="text-[11px] text-gray-500">Vừa cập nhật</span>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 line-clamp-2 mb-2">Laravel 11 - Hướng dẫn toàn tập từ cơ bản đến nâng cao</h3>
                        <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                            <x-icon name="arrow-down-tray" class="w-4 h-4" />
                            <span class="font-medium">2,847 lượt tải</span>
                        </div>
                        <div class="mt-auto pt-4 flex items-center justify-between border-t border-gray-50">
                            <span class="text-sm font-bold text-green-600">Miễn phí</span>
                            <x-button outline indigo sm label="Xem chi tiết" />
                        </div>
                    </div>
                </x-card>

                {{-- Document Card 2 --}}
                <x-card padding="none" class="overflow-hidden hover:shadow-lg transition-shadow flex flex-col h-full border border-gray-100 group">
                    <div class="h-40 bg-linear-to-br from-green-50 to-emerald-50 flex items-center justify-center border-b border-gray-100">
                        <x-icon name="document-text" class="w-16 h-16 text-green-400 group-hover:scale-110 transition-transform" />
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 mb-2">
                            <x-badge flat positive label="Ebook" />
                            <span class="text-[11px] text-gray-500">2 ngày trước</span>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 line-clamp-2 mb-2">ReactJS & NextJS 14 - Xây dựng ứng dụng web hiện đại</h3>
                        <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                            <x-icon name="arrow-down-tray" class="w-4 h-4" />
                            <span class="font-medium">2,456 lượt tải</span>
                        </div>
                        <div class="mt-auto pt-4 flex items-center justify-between border-t border-gray-50">
                            <span class="text-sm font-bold text-green-600">Miễn phí</span>
                            <x-button outline indigo sm label="Xem chi tiết" />
                        </div>
                    </div>
                </x-card>

                {{-- Document Card 3 --}}
                <x-card padding="none" class="overflow-hidden hover:shadow-lg transition-shadow flex flex-col h-full border border-gray-100 group">
                    <div class="h-40 bg-linear-to-br from-purple-50 to-pink-50 flex items-center justify-center border-b border-gray-100">
                        <x-icon name="document-text" class="w-16 h-16 text-purple-400 group-hover:scale-110 transition-transform" />
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 mb-2">
                            <x-badge flat secondary label="Video" />
                            <span class="text-[11px] text-gray-500">3 ngày trước</span>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 line-clamp-2 mb-2">Python cho Data Science - Khóa học thực hành</h3>
                        <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                            <x-icon name="arrow-down-tray" class="w-4 h-4" />
                            <span class="font-medium">2,189 lượt tải</span>
                        </div>
                        <div class="mt-auto pt-4 flex items-center justify-between border-t border-gray-50">
                            <span class="text-sm font-bold text-indigo-600">299,000đ</span>
                            <x-button outline indigo sm label="Xem chi tiết" />
                        </div>
                    </div>
                </x-card>

                {{-- Document Card 4 --}}
                <x-card padding="none" class="overflow-hidden hover:shadow-lg transition-shadow flex flex-col h-full border border-gray-100 group">
                    <div class="h-40 bg-linear-to-br from-orange-50 to-red-50 flex items-center justify-center border-b border-gray-100">
                        <x-icon name="document-text" class="w-16 h-16 text-orange-400 group-hover:scale-110 transition-transform" />
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 mb-2">
                            <x-badge flat warning label="PDF" />
                            <span class="text-[11px] text-gray-500">1 tuần trước</span>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 line-clamp-2 mb-2">Docker & Kubernetes - DevOps từ A đến Z</h3>
                        <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                            <x-icon name="arrow-down-tray" class="w-4 h-4" />
                            <span class="font-medium">1,976 lượt tải</span>
                        </div>
                        <div class="mt-auto pt-4 flex items-center justify-between border-t border-gray-50">
                            <span class="text-sm font-bold text-green-600">Miễn phí</span>
                            <x-button outline indigo sm label="Xem chi tiết" />
                        </div>
                    </div>
                </x-card>

                {{-- Document Card 5 --}}
                <x-card padding="none" class="overflow-hidden hover:shadow-lg transition-shadow flex flex-col h-full border border-gray-100 group">
                    <div class="h-40 bg-linear-to-br from-cyan-50 to-blue-50 flex items-center justify-center border-b border-gray-100">
                        <x-icon name="document-text" class="w-16 h-16 text-cyan-400 group-hover:scale-110 transition-transform" />
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 mb-2">
                            <x-badge flat info label="Ebook" />
                            <span class="text-[11px] text-gray-500">5 ngày trước</span>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 line-clamp-2 mb-2">AWS Cloud Architecture - Thiết kế hệ thống cloud</h3>
                        <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                            <x-icon name="arrow-down-tray" class="w-4 h-4" />
                            <span class="font-medium">1,834 lượt tải</span>
                        </div>
                        <div class="mt-auto pt-4 flex items-center justify-between border-t border-gray-50">
                            <span class="text-sm font-bold text-indigo-600">399,000đ</span>
                            <x-button outline indigo sm label="Xem chi tiết" />
                        </div>
                    </div>
                </x-card>

                {{-- Document Card 6 --}}
                <x-card padding="none" class="overflow-hidden hover:shadow-lg transition-shadow flex flex-col h-full border border-gray-100 group">
                    <div class="h-40 bg-linear-to-br from-amber-50 to-yellow-50 flex items-center justify-center border-b border-gray-100">
                        <x-icon name="document-text" class="w-16 h-16 text-amber-400 group-hover:scale-110 transition-transform" />
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 mb-2">
                            <x-badge flat warning label="PDF" />
                            <span class="text-[11px] text-gray-500">1 tuần trước</span>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 line-clamp-2 mb-2">Thiết kế UI/UX chuyên nghiệp với Figma</h3>
                        <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                            <x-icon name="arrow-down-tray" class="w-4 h-4" />
                            <span class="font-medium">1,723 lượt tải</span>
                        </div>
                        <div class="mt-auto pt-4 flex items-center justify-between border-t border-gray-50">
                            <span class="text-sm font-bold text-green-600">Miễn phí</span>
                            <x-button outline indigo sm label="Xem chi tiết" />
                        </div>
                    </div>
                </x-card>

                {{-- Document Card 7 --}}
                <x-card padding="none" class="overflow-hidden hover:shadow-lg transition-shadow flex flex-col h-full border border-gray-100 group">
                    <div class="h-40 bg-linear-to-br from-rose-50 to-pink-50 flex items-center justify-center border-b border-gray-100">
                        <x-icon name="document-text" class="w-16 h-16 text-rose-400 group-hover:scale-110 transition-transform" />
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 mb-2">
                            <x-badge flat negative label="Video" />
                            <span class="text-[11px] text-gray-500">4 ngày trước</span>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 line-clamp-2 mb-2">Flutter - Xây dựng app mobile đa nền tảng</h3>
                        <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                            <x-icon name="arrow-down-tray" class="w-4 h-4" />
                            <span class="font-medium">1,645 lượt tải</span>
                        </div>
                        <div class="mt-auto pt-4 flex items-center justify-between border-t border-gray-50">
                            <span class="text-sm font-bold text-indigo-600">249,000đ</span>
                            <x-button outline indigo sm label="Xem chi tiết" />
                        </div>
                    </div>
                </x-card>

                {{-- Document Card 8 --}}
                <x-card padding="none" class="overflow-hidden hover:shadow-lg transition-shadow flex flex-col h-full border border-gray-100 group">
                    <div class="h-40 bg-linear-to-br from-teal-50 to-emerald-50 flex items-center justify-center border-b border-gray-100">
                        <x-icon name="document-text" class="w-16 h-16 text-teal-400 group-hover:scale-110 transition-transform" />
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 mb-2">
                            <x-badge flat positive label="Ebook" />
                            <span class="text-[11px] text-gray-500">6 ngày trước</span>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 line-clamp-2 mb-2">Cybersecurity Fundamentals - Bảo mật cơ bản</h3>
                        <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                            <x-icon name="arrow-down-tray" class="w-4 h-4" />
                            <span class="font-medium">1,512 lượt tải</span>
                        </div>
                        <div class="mt-auto pt-4 flex items-center justify-between border-t border-gray-50">
                            <span class="text-sm font-bold text-green-600">Miễn phí</span>
                            <x-button outline indigo sm label="Xem chi tiết" />
                        </div>
                    </div>
                </x-card>
            </div>
        </div>

        {{-- 4. Đề thi mới nhất (4 cards) --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Đề thi mới nhất</h2>
                <a href="/exam" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Xem thêm &rarr;</a>
            </div>
            {{-- <div class="grid grid-cols-1 gap-4 sm:gap-5"> --}}
            <div class="relative">
                <div class="flex gap-6 overflow-x-auto pb-4 px-4 sm:px-6 lg:px-8 scroll-smooth">
                    @foreach ($exams as $exam)
                        <div class="flex-none w-72">
                            @include('exam::partials.exam_card', ['exam' => $exam])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- 5. Lộ trình học (3 cards - GIỮ NGUYÊN) --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Lộ trình học tập</h2>
                <a href="/roadmaps" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Xem thêm &rarr;</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @for ($i = 0; $i < 3; $i++)
                <x-card padding="p-5" class="hover:border-indigo-300 transition-colors border-2 border-transparent">
                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600 shrink-0">
                            <x-icon name="map" class="w-8 h-8" />
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 mb-1.5">Backend Developer</h3>
                            <p class="text-sm text-gray-500 mb-3 line-clamp-2">Lộ trình chi tiết từng bước để trở thành Backend Developer chuyên nghiệp.</p>
                            <div class="flex items-center gap-4 text-xs font-medium text-gray-500">
                                <span class="flex items-center gap-1"><x-icon name="document-text" class="w-4 h-4 text-gray-400"/> 15 khóa học</span>
                                <span class="flex items-center gap-1"><x-icon name="clock" class="w-4 h-4 text-gray-400"/> 3 tháng</span>
                            </div>
                        </div>
                    </div>
                </x-card>
                @endfor
            </div>
        </div>

        {{-- 6. CTA Đăng nhập Google --}}

        @guest
        <div class="bg-linear-to-r from-blue-600 to-indigo-600 rounded-3xl shadow-xl p-8 sm:p-10 lg:p-12 text-white relative overflow-hidden">
            {{-- Decorative pattern --}}
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="text-center md:text-left md:w-2/3">
                    <x-badge flat white label="Thành viên" class="mb-4" />
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-4">
                        Đăng nhập để mở khóa toàn bộ tính năng
                    </h2>
                    <p class="text-blue-50 mb-6 sm:mb-8 max-w-2xl text-base sm:text-lg mx-auto md:mx-0">
                        Lưu tài liệu yêu thích, theo dõi tiến độ học tập, tham gia cộng đồng và nhận thông báo về tài liệu mới. Đăng nhập ngay để trải nghiệm đầy đủ các tính năng của nền tảng.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center md:justify-start">
                        <x-button white xl href="{{ route('auth.google.redirect') }}" label="Đăng nhập với Google" class="font-semibold shadow-lg" />
                        {{-- <x-button outline white xl label="Tìm hiểu thêm" /> --}}
                    </div>
                </div>
                <div class="shrink-0 hidden md:flex items-center justify-center">
                    <div class="w-40 h-40 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                        <x-icon name="user-circle" class="w-24 h-24 text-white drop-shadow-lg" />
                    </div>
                </div>
            </div>
        </div>
        @endguest

    </div>
@endsection
