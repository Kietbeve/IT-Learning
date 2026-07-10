<header class="glass-header border-b border-slate-200/40 sticky top-0 z-30 shadow-sm">
    @php
        $activeUser = auth()->user() ?? \Modules\Auth\Models\User::whereHas('roles', function($q) {
            $q->where('name', 'contributor');
        })->first() ?? \Modules\Auth\Models\User::first();
    @endphp
    <div class="px-4 lg:px-8 py-4 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <!-- Mobile Menu Button -->
            <button @click="sidebarHidden = false" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm lg:hidden hover:bg-slate-50 transition-colors" aria-label="Open sidebar">
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>

            <!-- Page Title or Greeting -->
            <h1 class="text-xl font-bold text-slate-800 tracking-tight hidden sm:block">
                👋 Chào mừng, {{ explode(' ', trim($activeUser?->name ?? 'Contributor'))[0] }}
            </h1>
        </div>

        <div class="flex items-center gap-3 sm:gap-5">
            <!-- search (hidden on small screens) -->
            <div class="hidden md:flex items-center bg-white/60 rounded-full px-4 py-2 border border-slate-200/40 focus-within:ring-2 focus-within:ring-indigo-300/50 focus-within:border-indigo-300 transition-all shadow-sm backdrop-blur-sm">
                <svg class="w-4 h-4 text-slate-400 mr-2 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <input type="text" placeholder="Tìm kiếm..." class="bg-transparent text-sm outline-none w-32 lg:w-48 placeholder-slate-400" />
            </div>

            <!-- notification -->
            <div class="relative flex items-center justify-center">
                @livewire('notification-bell')
            </div>

            <!-- Profile Dropdown (Simplified for just admin panel link if applicable) -->
            <x-dropdown>
                <x-slot name="trigger">
                    <button class="focus:outline-none hover:opacity-80 transition-opacity">
                        @if($activeUser?->avatar)
                            <img src="{{ $activeUser->avatar }}" alt="{{ $activeUser->name }}" class="h-9 w-9 rounded-full object-cover shadow-md ring-2 ring-white">
                        @else
                            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 text-white flex items-center justify-center font-bold shadow-md shadow-indigo-200/60 ring-2 ring-white text-xs">
                                {{ substr($activeUser?->name ?? 'C', 0, 2) }}
                            </div>
                        @endif
                    </button>
                </x-slot>

                <div class="px-4 py-3 border-b border-slate-100">
                    <p class="text-sm font-semibold text-slate-800">{{ $activeUser?->name ?? 'Contributor' }}</p>
                    <p class="text-xs text-slate-500 truncate">{{ $activeUser?->email ?? '' }}</p>
                </div>
                
                <x-dropdown.item href="{{ route('contributor.dashboard') }}" label="Dashboard" />
                
                @if(auth()->user() && auth()->user()->hasRole('admin'))
                    <div class="border-t border-slate-100 my-1"></div>
                    <x-dropdown.item href="{{ route('admin.dashboard') }}">
                        <div class="flex items-center text-amber-600 font-semibold">
                            <x-icon name="shield-check" class="w-4 h-4 mr-2" />
                            <span>Trang quản trị</span>
                        </div>
                    </x-dropdown.item>
                @endif
                
                <div class="border-t border-slate-100 my-1"></div>
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
