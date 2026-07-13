{{-- ========================================
HEADER NAVIGATION - PHONG CÁCH HIỆN ĐẠI
Thiết kế theo phong cách exam_detail.blade.php
======================================== --}}

{{-- Container header chính: Sticky top với gradient background --}}
<header
  class="sticky top-0 z-50 w-full bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 shadow-xl border-b border-slate-700/50 backdrop-blur-sm">

  {{-- Navigation bar với Alpine.js để xử lý mobile menu --}}
  <nav x-data="{ isOpen: false }" class="relative">

    {{-- Container chính với max-width và padding --}}
    <div class="container px-4 sm:px-6 py-3 mx-auto flex items-center justify-between">

      {{-- ===== PHẦN LOGO & NÚT MOBILE ===== --}}
      <div class="flex items-center gap-2 sm:gap-4 z-50">
        {{-- Nút toggle mobile menu (chỉ hiện trên màn hình nhỏ và tablet) --}}
        <button x-cloak @click="isOpen = !isOpen" type="button" class="lg:hidden text-slate-300 hover:text-white focus:outline-none transition-colors p-1.5 sm:p-2 rounded-lg hover:bg-slate-700/50" aria-label="toggle menu">
          <svg x-show="!isOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16" /></svg>
          <svg x-show="isOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>

        <a href="{{ route('home.dashboard') }}"
          class="flex items-center gap-1.5 text-white text-xl sm:text-2xl font-bold font-sans hover:opacity-90 transition-all duration-300 whitespace-nowrap group">
          <img src="{{ asset('Image/logo.png') }}" alt="Logo"
            class="h-8 sm:h-10 w-auto object-contain group-hover:scale-105 transition-transform duration-300">
          <span class="tracking-tight hidden sm:inline">IT<span
              class="text-blue-400 group-hover:text-blue-300 transition-colors">Learning</span></span>
        </a>
      </div>

      {{-- ===== PHẦN MENU CHÍNH (DESKTOP) VÀ MOBILE MENU =====
      - Mobile: slide in từ trái với animation
      - Desktop: hiển thị ngang inline
      --}}
      <div x-cloak :class="[isOpen ? 'translate-x-0 opacity-100 ' : 'opacity-0 -translate-x-full']"
        class="absolute inset-x-0 z-20 w-full px-6 py-6 transition-all duration-300 ease-in-out bg-slate-900/95 backdrop-blur-md border-t border-slate-700/50 top-full lg:mt-0 lg:p-0 lg:top-0 lg:relative lg:bg-transparent lg:w-auto lg:opacity-100 lg:translate-x-0 lg:flex lg:items-center lg:justify-center lg:flex-1 lg:border-0 rounded-b-2xl lg:rounded-none shadow-lg lg:shadow-none">

        {{-- Danh sách menu navigation --}}
        <div class="flex flex-col lg:flex-row lg:mx-6 gap-1 lg:gap-0">
          <a class="{{ request()->is('/') ? 'text-blue-400 font-bold bg-blue-500/10' : 'text-slate-300 hover:text-white hover:bg-slate-700/30' }} px-4 py-2.5 rounded-xl transition-all duration-300 transform lg:mx-2 lg:my-0 my-1 whitespace-nowrap" href="/">Trang chủ</a>
          <a class="{{ request()->is('documents*') ? 'text-blue-400 font-bold bg-blue-500/10' : 'text-slate-300 hover:text-white hover:bg-slate-700/30' }} px-4 py-2.5 rounded-xl transition-all duration-300 transform lg:mx-2 lg:my-0 my-1 whitespace-nowrap" href="{{ route('documents.index') }}">Kho tài liệu</a>
          <a class="{{ request()->is('exam*') ? 'text-blue-400 font-bold bg-blue-500/10' : 'text-slate-300 hover:text-white hover:bg-slate-700/30' }} px-4 py-2.5 rounded-xl transition-all duration-300 transform lg:mx-2 lg:my-0 my-1 whitespace-nowrap" href="{{ route('exam.index') }}">Đề thi</a>
          <a class="{{ request()->is('learning*') ? 'text-blue-400 font-bold bg-blue-500/10' : 'text-slate-300 hover:text-white hover:bg-slate-700/30' }} px-4 py-2.5 rounded-xl transition-all duration-300 transform lg:mx-2 lg:my-0 my-1 whitespace-nowrap" href="{{ route('learning.roadmaps.index') }}">Lộ trình học tập</a>
        </div>
      </div>

      {{-- ===== PHẦN USER AUTHENTICATION & NÚT MOBILE ===== --}}
      <div class="flex items-center gap-1 sm:gap-2 z-50">
        @auth
          @php
            $isVip = Auth::user()->vip_expires_at && Auth::user()->vip_expires_at->isFuture();
          @endphp
          @if (!Auth::user()->hasRole('admin'))
            @if ($isVip)
              <a href="{{ route('user.subscription') }}" class="flex items-center gap-1 sm:gap-2 px-2 sm:px-4 py-1.5 sm:py-2 bg-gradient-to-r from-amber-500/20 to-yellow-500/20 hover:from-amber-500/30 hover:to-yellow-500/30 border border-amber-400/50 text-amber-300 rounded-xl text-xs sm:text-sm font-bold shadow-lg shadow-amber-500/20 backdrop-blur-sm transition-all duration-300 hover:scale-105" title="Bạn đang là thành viên VIP">
                <svg class="w-4 h-4 drop-shadow-[0_0_8px_rgba(251,191,36,0.8)]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" /></svg>
                <span class="hidden sm:inline whitespace-nowrap">Thành viên VIP</span>
              </a>
            @else
              <a href="{{ route('user.subscription') }}" class="flex items-center gap-1 sm:gap-2 px-2 sm:px-4 py-1.5 sm:py-2 bg-gradient-to-r from-amber-500/15 to-yellow-500/15 hover:from-amber-500/25 hover:to-yellow-500/25 border border-amber-400/60 hover:border-amber-300 text-amber-300 hover:text-amber-200 rounded-xl text-xs sm:text-sm font-bold shadow-lg shadow-amber-500/20 hover:shadow-amber-500/30 backdrop-blur-sm transition-all duration-300 hover:scale-105">
                <x-icon name="sparkles" class="w-4 h-4 animate-pulse drop-shadow-[0_0_8px_rgba(251,191,36,0.8)]" />
                <span class="hidden sm:inline whitespace-nowrap">Nâng cấp VIP</span>
              </a>
            @endif
          @endif

          <div class="flex items-center">
              @livewire('notification-bell')
          </div>

          <div x-data="{ dropdownOpen: false }" class="relative">
            <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false" class="flex items-center gap-1 sm:gap-2 px-2 py-1.5 sm:px-3 sm:py-2 text-white hover:text-blue-300 transition-all duration-300 focus:outline-none rounded-xl hover:bg-slate-700/50 group">
              <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=3b82f6&color=fff&bold=true' }}" alt="User Avatar" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full border-2 border-blue-400/50 shadow-md group-hover:border-blue-300 transition-all shrink-0">
              <span class="hidden lg:inline font-medium font-sans truncate max-w-[120px]">{{ Auth::user()->name }}</span>
              <x-icon name="chevron-down" class="hidden sm:inline w-3 h-3 sm:w-4 sm:h-4 shrink-0 group-hover:text-blue-300 transition-colors" />
            </button>

            <div x-show="dropdownOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 -translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 z-[60] w-64 py-2 mt-2 bg-slate-800/95 backdrop-blur-md rounded-2xl shadow-2xl border border-slate-700/50 overflow-hidden">
              <a href="{{ route('auth.profile') }}" class="block px-4 py-3 text-sm text-slate-300 hover:bg-slate-700/70 hover:text-white transition-all group"><div class="flex items-center gap-3"><div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center group-hover:bg-blue-500/30 transition-colors"><x-icon name="user" class="w-4 h-4 text-blue-400" /></div><span>Hồ sơ cá nhân</span></div></a>
              @if (Route::has('user.purchases'))
                <a href="{{ route('user.purchases') }}" class="block px-4 py-3 text-sm text-slate-300 hover:bg-slate-700/70 hover:text-white transition-all group"><div class="flex items-center gap-3"><div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center group-hover:bg-emerald-500/30 transition-colors"><x-icon name="shopping-bag" class="w-4 h-4 text-emerald-400" /></div><span>Tài liệu đã mua</span></div></a>
              @endif
              @if (Route::has('exam.results'))
                <a href="{{ route('exam.results') }}" class="block px-4 py-3 text-sm text-slate-300 hover:bg-slate-700/70 hover:text-white transition-all group"><div class="flex items-center gap-3"><div class="w-8 h-8 rounded-lg bg-amber-500/20 flex items-center justify-center group-hover:bg-amber-500/30 transition-colors"><x-icon name="chart-bar" class="w-4 h-4 text-amber-400" /></div><span>Thống kê kiểm tra</span></div></a>
              @endif
              @if (Route::has('user.transactions'))
                <a href="{{ route('user.transactions') }}" class="block px-4 py-3 text-sm text-slate-300 hover:bg-slate-700/70 hover:text-white transition-all group"><div class="flex items-center gap-3"><div class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center group-hover:bg-purple-500/30 transition-colors"><x-icon name="receipt-percent" class="w-4 h-4 text-purple-400" /></div><span>Lịch sử giao dịch</span></div></a>
              @endif
              @if (Route::has('user.bookmarks'))
                <a href="{{ route('user.bookmarks') }}" class="block px-4 py-3 text-sm text-slate-300 hover:bg-slate-700/70 hover:text-white transition-all group"><div class="flex items-center gap-3"><div class="w-8 h-8 rounded-lg bg-pink-500/20 flex items-center justify-center group-hover:bg-pink-500/30 transition-colors"><x-icon name="heart" class="w-4 h-4 text-pink-400" /></div><span>Tài liệu yêu thích</span></div></a>
              @endif
              @if (auth()->user()->hasRole('contributor') || auth()->user()->hasRole('admin'))
                <hr class="border-slate-700/70 my-2">
              @endif
              @if (auth()->user()->hasRole('contributor'))
                <a href="{{ route('contributor.dashboard') }}" class="block px-4 py-3 text-sm text-blue-300 hover:bg-slate-700/70 hover:text-blue-200 transition-all font-semibold group"><div class="flex items-center gap-3"><div class="w-8 h-8 rounded-lg bg-blue-500/30 flex items-center justify-center group-hover:bg-blue-500/40 transition-colors"><x-icon name="cloud-arrow-up" class="w-4 h-4 text-blue-300" /></div><span>Kênh người đăng tải</span></div></a>
              @endif
              @if (auth()->user()->hasRole('admin'))
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 text-sm text-amber-300 hover:bg-slate-700/70 hover:text-amber-200 transition-all font-semibold group"><div class="flex items-center gap-3"><div class="w-8 h-8 rounded-lg bg-amber-500/30 flex items-center justify-center group-hover:bg-amber-500/40 transition-colors"><x-icon name="shield-check" class="w-4 h-4 text-amber-300" /></div><span>Trang quản trị</span></div></a>
              @endif
              <hr class="border-slate-700/70 my-2">
              <form method="POST" action="{{ route('auth.logout') }}">
                @csrf
                <button type="submit" class="w-full text-left block px-4 py-3 text-sm text-red-400 hover:bg-slate-700/70 hover:text-red-300 transition-all group"><div class="flex items-center gap-3"><div class="w-8 h-8 rounded-lg bg-red-500/20 flex items-center justify-center group-hover:bg-red-500/30 transition-colors"><x-icon name="arrow-right-on-rectangle" class="w-4 h-4 text-red-400" /></div><span>Đăng xuất</span></div></button>
              </form>
            </div>
          </div>
        @else
          <a href="{{ route('auth.google.redirect') }}" class="flex items-center gap-2 px-3 sm:px-5 py-2 sm:py-2.5 bg-white hover:bg-gray-50 text-gray-700 rounded-xl transition-all duration-300 font-medium shadow-lg hover:shadow-xl hover:scale-105 border border-gray-200">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" /><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" /><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" /><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" /></svg>
            <span class="hidden sm:inline whitespace-nowrap">Đăng nhập</span>
          </a>
        @endauth

      </div>
    </div>
  </nav>
</header>