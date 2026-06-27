<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ $title ?? 'Học tập' }} - ITLearning</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('Image/Logo.png') }}" type="image/png">

    {{-- Vite CSS & JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Alpine.js for interactive components --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="antialiased">
    {{-- TOPBAR NAVIGATION FOR LEARNING PAGES --}}
    <header class="bg-slate-900 shadow-md sticky top-0 z-50">
        <div class="px-4 md:px-8 py-2.5 flex justify-between items-center">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                {{-- Logo --}}
                <a href="/" class="flex items-center gap-1 text-white text-lg font-bold hover:opacity-80 transition-opacity shrink-0">
                    <img src="{{ asset('Image/logo.png') }}" alt="Logo" class="h-8 w-auto object-contain">
                    <span class="tracking-tight hidden lg:inline">IT<span class="text-blue-500">Learning</span></span>
                </a>
                
                {{-- Divider --}}
                <div class="h-5 w-[1px] bg-slate-700 shrink-0"></div>
                
                {{-- Breadcrumb Navigation - Allow pages to customize --}}
                <nav class="flex items-center gap-2 text-sm flex-1 min-w-0">
                    @hasSection('breadcrumb')
                        @yield('breadcrumb')
                    @else
                        <a href="{{ route('learning.roadmaps.index') }}" class="text-slate-300 hover:text-white transition-colors">
                            Lộ trình học tập
                        </a>
                    @endif
                </nav>
            </div>

            {{-- User Profile --}}
            <div class="flex items-center gap-3 shrink-0" x-data="{ open: false }">
                @auth
                    <div class="relative">
                        <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 text-white hover:text-blue-400 transition-colors focus:outline-none">
                            <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=2563eb&color=fff' }}"
                                alt="User Avatar"
                                class="w-8 h-8 rounded-full border border-slate-700 shadow-sm">
                            <span class="font-medium hidden sm:block text-sm">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             x-cloak
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                            <a href="{{ route('auth.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                Hồ sơ cá nhân
                            </a>
                            <a href="/" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                Trang chủ
                            </a>
                            <form method="POST" action="{{ route('auth.logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                    Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('auth.google.redirect') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                        Đăng nhập
                    </a>
                @endauth
            </div>
        </div>
    </header>

    @yield('content')
</body>

</html>
