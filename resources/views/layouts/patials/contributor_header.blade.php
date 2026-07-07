<header class="border-b border-indigo-50/80 bg-white/80 px-4 py-4 shadow-sm sm:px-6 lg:px-8 backdrop-blur-md font-sans text-slate-850 sticky top-0 z-30">
    @php
        $activeUser = auth()->user() ?? \Modules\Auth\Models\User::whereHas('roles', function($q) {
            $q->where('name', 'contributor');
        })->first() ?? \Modules\Auth\Models\User::first();
    @endphp
    <div class="flex flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <!-- Mobile Menu Button -->
            <button @click="sidebarHidden = false" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-600 shadow-sm lg:hidden" aria-label="Open sidebar">
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>
            
            <!-- Hamburger Button (Shows when sidebar is hidden) -->
            <button @click="sidebarHidden = false" 
                    x-show="sidebarHidden"
                    class="hidden lg:flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-md hover:bg-indigo-700 transition-all duration-200"
                    aria-label="Show sidebar">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            
            <!-- Logo and Branding -->
            <a href="{{ route('contributor.dashboard') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                <img src="{{ asset('Image/logo.png') }}" alt="IT Learning Logo" class="h-14 w-14 rounded-2xl object-cover">
                <div>
                    <p class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-indigo-600 whitespace-nowrap">Kênh Đăng Tải</p>
                    <p class="text-base font-black text-slate-900 tracking-tight whitespace-nowrap">IT Learning</p>
                </div>
            </a>
        </div>

        <div class="flex items-center gap-2">

            {{-- Notification Bell --}}
            @livewire('notification-bell')

            <x-dropdown>
                <x-slot name="trigger">
                    <button class="flex items-center gap-3 rounded-3xl border border-slate-200 bg-slate-50/60 px-3 py-2 text-left focus:outline-none hover:bg-slate-100/50 transition-all">
                        @if($activeUser?->avatar)
                            <img src="{{ $activeUser->avatar }}" alt="{{ $activeUser->name }}" class="h-10 w-10 rounded-full object-cover shadow-md shrink-0">
                        @else
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-200 text-slate-600 font-semibold shadow-md shrink-0">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="min-w-0 hidden md:block">
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ $activeUser?->name ?? 'Contributor' }}</p>
                        </div>
                        <x-icon name="chevron-down" class="w-4 h-4 text-slate-450 shrink-0" />
                    </button>
                </x-slot>

                <x-dropdown.item href="{{ route('contributor.transactions') }}" label="Lịch sử giao dịch">
                    <x-slot name="prepend">
                        <x-icon name="document-text" class="w-4 h-4 mr-2" />
                    </x-slot>
                </x-dropdown.item>

                <x-dropdown.item href="{{ route('contributor.payout-request') }}" label="Ví">
                    <x-slot name="prepend">
                        <x-icon name="banknotes" class="w-4 h-4 mr-2" />
                    </x-slot>
                </x-dropdown.item>

                <div class="border-t border-slate-100 my-1"></div>

                @if(auth()->user() && auth()->user()->hasRole('admin'))
                    <x-dropdown.item href="{{ route('admin.dashboard') }}">
                        <div class="flex items-center text-amber-600 font-semibold">
                            <x-icon name="shield-check" class="w-4 h-4 mr-2" />
                            <span>Trang quản trị</span>
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
        </div>
    </div>
</header>
