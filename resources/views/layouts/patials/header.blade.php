<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 flex-shrink-0 sticky top-0 z-40">
    @php
        $isContributor = request()->is('contributor*') || request()->is('contributor');
        $activeUser = auth()->user() ?? \App\Models\User::whereHas('roles', function($q) {
            $q->where('name', 'contributor');
        })->first() ?? \App\Models\User::first();
    @endphp
    
    <!-- Left: Mobile toggle (Breadcrumb removed) -->
    <div class="flex items-center gap-4">
        <button @click="sidebarHidden = false" class="lg:hidden text-gray-500 hover:text-gray-700 transition-colors" aria-label="Open sidebar">
            <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <div>
            <h2 class="text-lg font-bold text-gray-800 leading-tight hidden sm:block">{{ $title ?? 'Admin Panel' }}</h2>
        </div>
    </div>

    <!-- Right: Notifications + Avatar (Search removed) -->
    <div class="flex items-center gap-5">

        <!-- Notification Bell -->
        <div class="flex items-center">
            {{-- @livewire('notification-bell') --}}
        </div>

        <!-- User Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 hover:bg-gray-50 p-1 rounded-lg transition-colors">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                    {{ $isContributor ? 'C' : 'A' }}
                </div>
                <span class="hidden sm:inline text-sm font-medium text-gray-700">{{ $activeUser?->name ?? ($isContributor ? 'Contributor' : 'Admin') }}</span>
                <svg class="w-3 h-3 text-gray-400 hidden sm:inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-100" 
                 x-transition:enter-start="transform opacity-0 scale-95" 
                 x-transition:enter-end="transform opacity-100 scale-100" 
                 x-transition:leave="transition ease-in duration-75" 
                 x-transition:leave-start="transform opacity-100 scale-100" 
                 x-transition:leave-end="transform opacity-0 scale-95" 
                 class="absolute right-0 mt-2 w-48 origin-top-right rounded-xl bg-white py-2 shadow-lg border border-gray-100 focus:outline-none z-50" 
                 style="display: none;">
                
                <a href="{{ url('/') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/></svg>
                    Về trang chủ
                </a>
                
                <form method="POST" action="{{ request()->is('admin*') ? route('auth.admin.logout') : route('auth.logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Đăng xuất
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
