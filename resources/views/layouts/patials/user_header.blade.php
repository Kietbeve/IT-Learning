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
    <div class="container px-6 py-3 mx-auto md:flex md:justify-between md:items-center">

      {{-- ===== PHẦN LOGO VÀ NÚT MOBILE MENU ===== --}}
      <div class="flex items-center justify-between">

        {{-- Logo và tên thương hiệu --}}
        <a href="{{ route('home.dashboard') }}"
          class="flex items-center gap-1.5 text-white text-2xl font-bold font-sans hover:opacity-90 transition-all duration-300 whitespace-nowrap group">
          <img src="{{ asset('Image/logo.png') }}" alt="Logo"
            class="h-10 w-auto object-contain group-hover:scale-105 transition-transform duration-300">
          <span class="tracking-tight">IT<span
              class="text-blue-400 group-hover:text-blue-300 transition-colors">Learning</span></span>
        </a>

        {{-- Nút toggle mobile menu (chỉ hiện trên màn hình nhỏ) --}}
        <div class="flex lg:hidden">
          <button x-cloak @click="isOpen = !isOpen" type="button"
            class="text-slate-300 hover:text-white focus:outline-none focus:text-white transition-colors p-2 rounded-lg hover:bg-slate-700/50"
            aria-label="toggle menu">
            {{-- Icon hamburger (hiện khi menu đóng) --}}
            <svg x-show="!isOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16" />
            </svg>

            {{-- Icon X (hiện khi menu mở) --}}
            <svg x-show="isOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

      </div>

      {{-- ===== PHẦN MENU CHÍNH (DESKTOP) VÀ MOBILE MENU =====
      - Mobile: slide in từ trái với animation
      - Desktop: hiển thị ngang inline
      --}}
      <div x-cloak :class="[isOpen ? 'translate-x-0 opacity-100 ' : 'opacity-0 -translate-x-full']"
        class="absolute inset-x-0 z-20 w-full px-6 py-6 transition-all duration-300 ease-in-out bg-slate-900/95 backdrop-blur-md border-t border-slate-700/50 md:mt-0 md:p-0 md:top-0 md:relative md:bg-transparent md:w-auto md:opacity-100 md:translate-x-0 md:flex md:items-center md:border-0 rounded-b-2xl md:rounded-none shadow-lg md:shadow-none">

        {{-- Danh sách menu navigation --}}
        <div class="flex flex-col md:flex-row md:mx-6 gap-1 md:gap-0">

          {{-- Menu item: Trang chủ --}}
          <a class="{{ request()->is('/') ? 'text-blue-400 font-bold bg-blue-500/10' : 'text-slate-300 hover:text-white hover:bg-slate-700/30' }} 
                              px-4 py-2.5 rounded-xl transition-all duration-300 transform md:mx-2 md:my-0 my-1"
            href="/">
            Trang chủ
          </a>

          {{-- Menu item: Kho tài liệu --}}
          <a class="{{ request()->is('documents*') ? 'text-blue-400 font-bold bg-blue-500/10' : 'text-slate-300 hover:text-white hover:bg-slate-700/30' }} 
                              px-4 py-2.5 rounded-xl transition-all duration-300 transform md:mx-2 md:my-0 my-1"
            href="{{ route('documents.index') }}">
            Kho tài liệu
          </a>

          {{-- Menu item: Đề thi --}}
          <a class="{{ request()->is('exam*') ? 'text-blue-400 font-bold bg-blue-500/10' : 'text-slate-300 hover:text-white hover:bg-slate-700/30' }} 
                              px-4 py-2.5 rounded-xl transition-all duration-300 transform md:mx-2 md:my-0 my-1"
            href="{{ route('exam.index') }}">
            Đề thi
          </a>

          {{-- Menu item: Lộ trình học tập --}}
          <a class="{{ request()->is('learning*') ? 'text-blue-400 font-bold bg-blue-500/10' : 'text-slate-300 hover:text-white hover:bg-slate-700/30' }} 
                              px-4 py-2.5 rounded-xl transition-all duration-300 transform md:mx-2 md:my-0 my-1"
            href="{{ route('learning.roadmaps.index') }}">
            Lộ trình học tập
          </a>

        </div>

        {{-- ===== PHẦN USER AUTHENTICATION ===== --}}
        @auth
          {{-- Kiểm tra user có phải VIP không --}}
          @php
            $isVip = Auth::user()->vip_expires_at && Auth::user()->vip_expires_at->isFuture();
          @endphp

          {{-- Badge VIP hoặc nút Nâng cấp VIP --}}
          @if ($isVip)
            {{-- Badge VIP cho user đã là thành viên VIP --}}
            <a href="{{ route('student.subscription') }}"
              class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-amber-500/20 to-yellow-500/20 hover:from-amber-500/30 hover:to-yellow-500/30 border border-amber-400/50 text-amber-300 rounded-xl text-sm font-bold shadow-lg shadow-amber-500/20 backdrop-blur-sm transition-all duration-300 md:mr-4 md:my-0 my-2 hover:scale-105"
              title="Bạn đang là thành viên VIP">
              {{-- Icon ngôi sao với hiệu ứng glow --}}
              <svg class="w-4 h-4 drop-shadow-[0_0_8px_rgba(251,191,36,0.8)]" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
              </svg>
              <span class="whitespace-nowrap">Thành viên VIP</span>
            </a>
          @else
            {{-- Call-to-action nút Nâng cấp VIP cho non-VIP users --}}
            <a href="{{ route('student.subscription') }}"
              class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-amber-500/15 to-yellow-500/15 hover:from-amber-500/25 hover:to-yellow-500/25 border border-amber-400/60 hover:border-amber-300 text-amber-300 hover:text-amber-200 rounded-xl text-sm font-bold shadow-lg shadow-amber-500/20 hover:shadow-amber-500/30 backdrop-blur-sm transition-all duration-300 md:mr-4 md:my-0 my-2 hover:scale-105">
              {{-- Icon sparkles với animation pulse --}}
              <x-icon name="sparkles" class="w-4 h-4 animate-pulse drop-shadow-[0_0_8px_rgba(251,191,36,0.8)]" />
              <span class="whitespace-nowrap">Nâng cấp VIP</span>
            </a>
          @endif

          {{-- ===== DROPDOWN MENU USER ===== --}}
          <div x-data="{ dropdownOpen: false }" class="relative my-2 md:my-0">

            {{-- Nút mở dropdown: Avatar + tên user --}}
            <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false"
              class="flex items-center gap-2.5 px-3 py-2 text-white hover:text-blue-300 transition-all duration-300 focus:outline-none rounded-xl hover:bg-slate-700/50 group">
              {{-- Avatar user: Nếu có dùng avatar upload, không thì dùng UI Avatars --}}
              <img
                src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=3b82f6&color=fff&bold=true' }}"
                alt="User Avatar"
                class="w-9 h-9 rounded-full border-2 border-blue-400/50 shadow-md group-hover:border-blue-300 transition-all shrink-0">
              {{-- Tên user --}}
              <span class="font-medium font-sans truncate max-w-[120px] sm:max-w-none">{{ Auth::user()->name }}</span>
              {{-- Icon chevron down --}}
              <x-icon name="chevron-down" class="w-4 h-4 shrink-0 group-hover:text-blue-300 transition-colors" />
            </button>

            {{-- Menu dropdown với animation --}}
            <div x-show="dropdownOpen" x-cloak x-transition:enter="transition ease-out duration-200"
              x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
              x-transition:enter-end="opacity-100 scale-100 translate-y-0"
              x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
              x-transition:leave-end="opacity-0 scale-95"
              class="absolute right-0 z-50 w-64 py-2 mt-2 bg-slate-800/95 backdrop-blur-md rounded-2xl shadow-2xl border border-slate-700/50 overflow-hidden">

              {{-- Menu item: Hồ sơ cá nhân --}}
              <a href="{{ route('auth.profile') }}"
                class="block px-4 py-3 text-sm text-slate-300 hover:bg-slate-700/70 hover:text-white transition-all group">
                <div class="flex items-center gap-3">
                  <div
                    class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center group-hover:bg-blue-500/30 transition-colors">
                    <x-icon name="user" class="w-4 h-4 text-blue-400" />
                  </div>
                  <span>Hồ sơ cá nhân</span>
                </div>
              </a>

              {{-- Menu item: Tài liệu đã mua --}}
              @if (Route::has('student.purchases'))
                <a href="{{ route('student.purchases') }}"
                  class="block px-4 py-3 text-sm text-slate-300 hover:bg-slate-700/70 hover:text-white transition-all group">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center group-hover:bg-emerald-500/30 transition-colors">
                      <x-icon name="shopping-bag" class="w-4 h-4 text-emerald-400" />
                    </div>
                    <span>Tài liệu đã mua</span>
                  </div>
                </a>
              @endif

              {{-- Menu item: Thống kê kiểm tra --}}
              @if (Route::has('exam.results'))
                <a href="{{ route('exam.results') }}"
                  class="block px-4 py-3 text-sm text-slate-300 hover:bg-slate-700/70 hover:text-white transition-all group">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-8 h-8 rounded-lg bg-amber-500/20 flex items-center justify-center group-hover:bg-amber-500/30 transition-colors">
                      <x-icon name="chart-bar" class="w-4 h-4 text-amber-400" />
                    </div>
                    <span>Thống kê kiểm tra</span>
                  </div>
                </a>
              @endif

              {{-- Menu item: Lịch sử giao dịch --}}
              @if (Route::has('student.transactions'))
                <a href="{{ route('student.transactions') }}"
                  class="block px-4 py-3 text-sm text-slate-300 hover:bg-slate-700/70 hover:text-white transition-all group">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center group-hover:bg-purple-500/30 transition-colors">
                      <x-icon name="receipt-percent" class="w-4 h-4 text-purple-400" />
                    </div>
                    <span>Lịch sử giao dịch</span>
                  </div>
                </a>
              @endif

              {{-- Menu item: Tài liệu yêu thích --}}
              @if (Route::has('student.bookmarks'))
                <a href="{{ route('student.bookmarks') }}"
                  class="block px-4 py-3 text-sm text-slate-300 hover:bg-slate-700/70 hover:text-white transition-all group">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-8 h-8 rounded-lg bg-pink-500/20 flex items-center justify-center group-hover:bg-pink-500/30 transition-colors">
                      <x-icon name="heart" class="w-4 h-4 text-pink-400" />
                    </div>
                    <span>Tài liệu yêu thích</span>
                  </div>
                </a>
              @endif

              {{-- Divider trước các menu đặc biệt --}}
              @if (auth()->user()->hasRole('contributor') || auth()->user()->hasRole('admin'))
                <hr class="border-slate-700/70 my-2">
              @endif

              {{-- Menu item: Kênh người đăng tải (chỉ cho contributor) --}}
              @if (auth()->user()->hasRole('contributor'))
                <a href="{{ route('contributor.dashboard') }}"
                  class="block px-4 py-3 text-sm text-blue-300 hover:bg-slate-700/70 hover:text-blue-200 transition-all font-semibold group">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-8 h-8 rounded-lg bg-blue-500/30 flex items-center justify-center group-hover:bg-blue-500/40 transition-colors">
                      <x-icon name="cloud-arrow-up" class="w-4 h-4 text-blue-300" />
                    </div>
                    <span>Kênh người đăng tải</span>
                  </div>
                </a>
              @endif

              {{-- Menu item: Trang quản trị (chỉ cho admin) --}}
              @if (auth()->user()->hasRole('admin'))
                <a href="{{ route('admin.dashboard') }}"
                  class="block px-4 py-3 text-sm text-amber-300 hover:bg-slate-700/70 hover:text-amber-200 transition-all font-semibold group">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-8 h-8 rounded-lg bg-amber-500/30 flex items-center justify-center group-hover:bg-amber-500/40 transition-colors">
                      <x-icon name="shield-check" class="w-4 h-4 text-amber-300" />
                    </div>
                    <span>Trang quản trị</span>
                  </div>
                </a>
              @endif

              {{-- Divider trước nút đăng xuất --}}
              <hr class="border-slate-700/70 my-2">

              {{-- Form đăng xuất --}}
              <form method="POST" action="{{ route('auth.logout') }}">
                @csrf
                <button type="submit"
                  class="w-full text-left block px-4 py-3 text-sm text-red-400 hover:bg-slate-700/70 hover:text-red-300 transition-all group">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-8 h-8 rounded-lg bg-red-500/20 flex items-center justify-center group-hover:bg-red-500/30 transition-colors">
                      <x-icon name="arrow-right-on-rectangle" class="w-4 h-4 text-red-400" />
                    </div>
                    <span>Đăng xuất</span>
                  </div>
                </button>
              </form>
            </div>
          </div>

        @else
          {{-- ===== NÚT ĐĂNG NHẬP GOOGLE (CHO USER CHƯA LOGIN) ===== --}}
          <a href="{{ route('auth.google.redirect') }}"
            class="flex items-center gap-2.5 px-5 py-2.5 my-2 bg-white hover:bg-gray-50 text-gray-700 rounded-xl transition-all duration-300 font-medium shadow-lg hover:shadow-xl md:mx-4 md:my-0 hover:scale-105 border border-gray-200">
            {{-- Google icon --}}
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
            <span class="whitespace-nowrap">Đăng nhập Google</span>
          </a>
        @endauth

      </div>
    </div>
  </nav>
</header>