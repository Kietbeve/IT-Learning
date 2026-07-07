<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>ITLearning</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('Image/Logo.png') }}" type="image/png">

    <meta name="description" content="{{ $description ?? '' }}">
    <meta name="keywords" content="{{ $keywords ?? '' }}">
    <meta name="author" content="{{ $author ?? '' }}">

    {{-- Vite CSS & JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>[x-cloak] { display: none !important; }</style>
</head>

<body>
    <div class="flex flex-col min-h-screen font-sans text-gray-800">
        <!-- User Header -->
        @include('layouts.patials.user_header')

        {{-- VIP Promotion Banner (for non-VIP logged-in users) --}}
        @auth
            @php
                $isVip = Auth::user()->vip_expires_at && Auth::user()->vip_expires_at->isFuture();
            @endphp
            
            @if(!$isVip)
                <div x-data="{ 
                    show: !localStorage.getItem('vip_banner_dismissed'),
                    dismiss() {
                        this.show = false;
                        localStorage.setItem('vip_banner_dismissed', 'true');
                        setTimeout(() => localStorage.removeItem('vip_banner_dismissed'), 86400000); // 24h
                    }
                }" 
                     x-cloak
                     x-show="show"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-4"
                     class="bg-gradient-to-r from-amber-500 via-yellow-500 to-amber-500 shadow-lg">
                    <div class="max-w-7xl mx-auto px-4 md:px-8 py-3">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                <svg class="w-6 h-6 text-white shrink-0 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-white font-bold text-sm md:text-base">
                                        🎁 <span class="hidden sm:inline">Ưu đãi đặc biệt!</span> Nâng cấp VIP - Tải tài liệu Premium giá hời - Chỉ từ 99,000đ/tháng
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <a href="{{ route('user.subscription') }}" 
                                   class="px-4 py-2 bg-white hover:bg-gray-100 text-amber-600 rounded-lg text-sm font-bold shadow-md hover:shadow-lg transition-all duration-300">
                                    Xem ngay
                                </a>
                                <button @click="dismiss" 
                                        class="p-2 hover:bg-white/20 rounded-lg transition-colors duration-200" 
                                        title="Đóng">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endauth
        
        <main class="flex-1 p-8-4 bg-gray-50">
            @yield('content')
            {{ $slot ?? '' }}
        </main>

        <!-- User Footer -->
        @include('layouts.patials.user_footer')
    </div>
    @livewireScripts
    @wireUiScripts
</body>

</html>
