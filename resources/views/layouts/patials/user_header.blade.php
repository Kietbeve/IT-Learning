<header
    class="bg-slate-900 shadow-md px-4 md:px-8 py-4 flex justify-between items-center sticky top-0 z-50">
    <div class="logo">
        <a href="#"
            class="flex items-center gap-1 text-white text-2xl font-bold font-sans hover:opacity-80 transition-opacity whitespace-nowrap">
            <img src="{{ asset('Image/logo.png') }}" alt="Logo" class="h-12 w-auto object-contain">
            <span class="tracking-tight">IT<span class="text-blue-500">Learning</span></span>
        </a>
    </div>

    <!-- Navigation Menu -->
    <nav class="hidden md:flex items-center gap-8 font-sans font-semibold text-sm">
        <a href="/" 
           class="{{ request()->is('/') ? 'text-blue-500 font-bold' : 'text-slate-300 hover:text-white' }} transition-colors duration-200">
            Trang chủ
        </a>
        <a href="{{ route('documents.index') }}" 
           class="{{ request()->is('documents*') ? 'text-blue-500 font-bold' : 'text-slate-300 hover:text-white' }} transition-colors duration-200">
            Kho tài liệu
        </a>
        <a href="/exam" 
           class="{{ request()->is('exam*') ? 'text-blue-500 font-bold' : 'text-slate-300 hover:text-white' }} transition-colors duration-200">
            Đề thi
        </a>
        <a href="/learning" 
           class="{{ request()->is('learning*') ? 'text-blue-500 font-bold' : 'text-slate-300 hover:text-white' }} transition-colors duration-200">
            Lộ trình học tập
        </a>
    </nav>

    <div class="user-profile flex items-center">
        @auth
            <x-dropdown>
                <x-slot name="trigger">
                    <button
                        class="flex items-center gap-2 text-white hover:text-blue-400 transition-colors duration-300 focus:outline-none">
                        <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=2563eb&color=fff' }}"
                            alt="User Avatar"
                            class="w-10 h-10 rounded-full border border-slate-700 shadow-sm">
                        <span class="font-medium font-sans hidden sm:block">{{ Auth::user()->name }}</span>
                        <x-icon name="chevron-down" class="w-4 h-4" />
                    </button>
                </x-slot>

                {{-- Hồ sơ --}}
                <x-dropdown.item href="{{ route('auth.profile') }}">
                    <div class="flex items-center">
                        <x-icon name="user" class="w-4 h-4 mr-2" />
                        <span>Hồ sơ cá nhân</span>
                    </div>
                </x-dropdown.item>

                @if(Route::has('purchases.index'))
                    <x-dropdown.item href="{{ route('purchases.index') }}">
                        <div class="flex items-center">
                            <x-icon name="shopping-bag" class="w-4 h-4 mr-2" />
                            <span>Tài liệu đã mua</span>
                        </div>
                    </x-dropdown.item>
                @endif

                @if(Route::has('student.bookmarks'))
                    <x-dropdown.item href="{{ route('student.bookmarks') }}">
                        <div class="flex items-center">
                            <x-icon name="heart" class="w-4 h-4 mr-2" />
                            <span>Tài liệu yêu thích</span>
                        </div>
                    </x-dropdown.item>
                @endif

                <form method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <x-dropdown.item label="Đăng xuất" onclick="event.preventDefault(); this.closest('form').submit();">
                        <x-slot name="prepend">
                            <x-icon name="arrow-right-on-rectangle" class="w-4 h-4 mr-2" />
                        </x-slot>
                    </x-dropdown.item>
                </form>

            </x-dropdown>
        @else
            <a href="{{ route('auth.google.redirect') }}"
                class="flex items-center gap-2 px-4 py-2 border border-slate-700 rounded-lg text-sm font-medium text-white bg-slate-800 hover:bg-slate-700 transition-colors duration-300 focus:outline-none shadow-sm">
                <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                        fill="#4285F4" />
                    <path
                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                        fill="#34A853" />
                    <path
                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                        fill="#FBBC05" />
                    <path
                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                        fill="#EA4335" />
                </svg>
                <span class="hidden sm:block">Đăng nhập bằng Google</span>
            </a>
        @endauth
    </div>
</header>