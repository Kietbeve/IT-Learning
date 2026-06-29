{{-- Header mới  --}}
<header class=" sticky top-0 z-50 w-full">
    <nav x-data="{ isOpen: false }" class="relative bg-slate-900 shadow dark:bg-gray-800">
        <div class="container px-6 py-2.5 mx-auto md:flex md:justify-between md:items-center">
            <div class="flex items-center justify-between">

                <a href="{{ route('home.dashboard') }}"
                    class="flex items-center gap-1 text-white text-2xl font-bold font-sans hover:opacity-80 transition-opacity whitespace-nowrap">
                    <img src="{{ asset('Image/logo.png') }}" alt="Logo" class="h-9 w-auto object-contain">
                    <span class="tracking-tight">IT<span class="text-blue-500">Learning</span></span>
                </a>

                <!-- Nút Mobile menu -->
                <div class="flex lg:hidden">
                    <button x-cloak @click="isOpen = !isOpen" type="button"
                        class="text-gray-500 dark:text-gray-200 hover:text-gray-600 dark:hover:text-gray-400 focus:outline-none focus:text-gray-600 dark:focus:text-gray-400"
                        aria-label="toggle menu">
                        <svg x-show="!isOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16" />
                        </svg>

                        <svg x-show="isOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

            </div>

            <!-- Mobile Menu open: "block", Menu closed: "hidden" -->
            <div x-cloak :class="[isOpen ? 'translate-x-0 opacity-100 ' : 'opacity-0 -translate-x-full']"
                class="absolute inset-x-0 z-20 w-full px-6 py-4 transition-all duration-300 ease-in-out bg-slate-900 dark:bg-gray-800 md:mt-0 md:p-0 md:top-0 md:relative md:bg-transparent md:w-auto md:opacity-100 md:translate-x-0 md:flex md:items-center">

                <div class="flex flex-col md:flex-row md:mx-6">
                    <a class="{{ request()->is('/') ? 'text-blue-500 font-bold' : 'text-slate-300 hover:text-white' }} my-2 transition-colors duration-300 transform dark:text-gray-200 hover:text-blue-500 dark:hover:text-blue-400 md:mx-4 md:my-0"
                        href="/">Trang chủ</a>
                    <a class="{{ request()->is('documents*') ? 'text-blue-500 font-bold' : 'text-slate-300 hover:text-white' }} my-2 transition-colors duration-300 transform dark:text-gray-200 hover:text-blue-500 dark:hover:text-blue-400 md:mx-4 md:my-0"
                        href="{{ route('documents.index') }}">Kho tài liệu</a>
                    <a class="{{ request()->is('exam*') ? 'text-blue-500 font-bold' : 'text-slate-300 hover:text-white' }} my-2 transition-colors duration-300 transform dark:text-gray-200 hover:text-blue-500 dark:hover:text-blue-400 md:mx-4 md:my-0"
                        href="{{ route('exam.index') }}">Đề thi</a>
                    <a class="{{ request()->is('learning*') ? 'text-blue-500 font-bold' : 'text-slate-300 hover:text-white' }} my-2 transition-colors duration-300 transform dark:text-gray-200 hover:text-blue-500 dark:hover:text-blue-400 md:mx-4 md:my-0"
                        href="{{ route('learning.roadmaps.index') }}">Lộ trình học tập</a>

                </div>

                @auth
                    {{-- Check VIP user --}}
                    @php
                        $isVip = Auth::user()->vip_expires_at && Auth::user()->vip_expires_at->isFuture();
                    @endphp

                    @if ($isVip)
                        <a href="{{ route('student.subscription') }}"
                            class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 text-amber-400 rounded-lg text-sm font-semibold shadow-sm backdrop-blur-sm transition-all duration-300 md:mr-4 md:my-0 my-2"
                            title="Bạn đang là thành viên VIP">
                            <svg class="w-4 h-4 drop-shadow-[0_0_8px_rgba(251,191,36,0.8)]" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                            </svg>
                            <span class="whitespace-nowrap">Thành viên VIP</span>
                        </a>
                    @else
                        {{-- Upgrade CTA for non-VIP users --}}
                        <a href="{{ route('student.subscription') }}"
                            class="flex items-center gap-2 px-4 py-1.5 bg-linear-to-r from-amber-500/10 to-yellow-500/10 hover:from-amber-500/20 hover:to-yellow-500/20 border border-amber-500/50 hover:border-amber-400 text-amber-400 hover:text-amber-300 rounded-lg text-sm font-bold shadow-[0_0_10px_rgba(245,158,11,0.15)] hover:shadow-[0_0_15px_rgba(245,158,11,0.3)] backdrop-blur-sm transition-all duration-300 md:mr-4 md:my-0 my-2">
                            <x-icon name="sparkles"
                                class="w-4 h-4 animate-pulse drop-shadow-[0_0_8px_rgba(251,191,36,0.8)]" />
                            <span class="whitespace-nowrap">Nâng cấp VIP</span>
                        </a>
                    @endif

                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false"
                            class="flex items-center gap-2 text-white hover:text-blue-400 transition-colors duration-300 focus:outline-none">
                            <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=2563eb&color=fff' }}"
                                alt="User Avatar" class="w-9 h-9 rounded-full border border-slate-700 shadow-sm shrink-0">
                            <span
                                class="font-medium font-sans truncate max-w-[120px] sm:max-w-none">{{ Auth::user()->name }}</span>
                            <x-icon name="chevron-down" class="w-4 h-4 shrink-0" />
                        </button>

                        <div x-show="dropdownOpen" x-cloak x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 z-50 w-56 py-2 mt-2 bg-slate-800 rounded-md shadow-xl border border-slate-700 overflow-hidden">

                            <a href="{{ route('auth.profile') }}"
                                class="block px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
                                <div class="flex items-center">
                                    <x-icon name="user" class="w-4 h-4 mr-2" />
                                    <span>Hồ sơ cá nhân</span>
                                </div>
                            </a>

                            @if (Route::has('student.purchases'))
                                <a href="{{ route('student.purchases') }}"
                                    class="block px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
                                    <div class="flex items-center">
                                        <x-icon name="shopping-bag" class="w-4 h-4 mr-2" />
                                        <span>Tài liệu đã mua</span>
                                    </div>
                                </a>
                            @endif

                            @if (Route::has('student.transactions'))
                                <a href="{{ route('student.transactions') }}"
                                    class="block px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
                                    <div class="flex items-center">
                                        <x-icon name="receipt-percent" class="w-4 h-4 mr-2" />
                                        <span>Lịch sử giao dịch</span>
                                    </div>
                                </a>
                            @endif

                            @if (Route::has('student.bookmarks'))
                                <a href="{{ route('student.bookmarks') }}"
                                    class="block px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
                                    <div class="flex items-center">
                                        <x-icon name="heart" class="w-4 h-4 mr-2" />
                                        <span>Tài liệu yêu thích</span>
                                    </div>
                                </a>
                            @endif

                            @if (auth()->user()->hasRole('contributor'))
                                <hr class="border-slate-700 my-1">
                                <a href="{{ route('contributor.dashboard') }}"
                                    class="block px-4 py-2 text-sm text-blue-400 hover:bg-slate-700 hover:text-blue-300 transition-colors font-semibold">
                                    <div class="flex items-center">
                                        <x-icon name="cloud-arrow-up" class="w-4 h-4 mr-2" />
                                        <span>Kênh người đăng tải</span>
                                    </div>
                                </a>
                            @endif

                            @if (auth()->user()->hasRole('admin'))
                                <a href="{{ route('admin.dashboard') }}"
                                    class="block px-4 py-2 text-sm text-amber-400 hover:bg-slate-700 hover:text-amber-300 transition-colors font-semibold">
                                    <div class="flex items-center">
                                        <x-icon name="shield-check" class="w-4 h-4 mr-2" />
                                        <span>Trang quản trị</span>
                                    </div>
                                </a>
                            @endif

                            <hr class="border-slate-700 my-1">

                            <form method="POST" action="{{ route('auth.logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left block px-4 py-2 text-sm text-red-400 hover:bg-slate-700 hover:text-red-300 transition-colors">
                                    <div class="flex items-center">
                                        <x-icon name="arrow-right-on-rectangle" class="w-4 h-4 mr-2" />
                                        <span>Đăng xuất</span>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('auth.google.redirect') }}"
                        class="flex items-center gap-2 my-2 text-white transition-colors duration-300 transform dark:text-gray-200 hover:text-blue-500 dark:hover:text-blue-400 md:mx-4 md:my-0">
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
                        <span>Đăng nhập bằng Google</span>
                    </a>
                @endauth

            </div>
        </div>
    </nav>
</header>
