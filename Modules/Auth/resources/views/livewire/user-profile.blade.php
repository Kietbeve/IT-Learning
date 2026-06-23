<div class="min-h-screen bg-white rounded-xl">

    {{-- ───────────────────────────────── WireUI Notifications ───────────────────────────────── --}}
    <x-notifications z-index="z-50" />

    {{-- ═══════════════════════════════════ PAGE HEADER ═══════════════════════════════════════ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 ">

        <div class="mb-8">
            <div class="flex items-center gap-3 mb-1">
                {{-- Heroicon: user-circle --}}
                <div class="p-2 bg-blue-100 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Hồ sơ cá nhân
                </h1>
            </div>
            <p class="text-sm text-gray-600 ml-14">
                Quản lý thông tin tài khoản của bạn.
            </p>
        </div>

        {{-- ══════════════════════════════════ MAIN GRID ══════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-[30%_1fr] gap-6 items-start">

            {{-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ LEFT SIDEBAR (30%) ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ --}}
            <div class="flex flex-col gap-4">

                {{-- ── Profile Card ── --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                    {{-- Gradient banner --}}
                    <div class="h-20 bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600"></div>

                    <div class="px-6 pb-6">
                        {{-- Avatar --}}
                        <div class="-mt-10 mb-4 flex justify-center">
                            @if ($avatar)
                                <img src="{{ $avatar }}" alt="Avatar của {{ $name }}"
                                    class="w-20 h-20 rounded-2xl object-cover border-4 border-white shadow-lg" />
                            @else
                                <div class="w-20 h-20 rounded-2xl border-4 border-white shadow-lg
                                                bg-gradient-to-br from-blue-400 to-indigo-600
                                                flex items-center justify-center">
                                    <span class="text-3xl font-bold text-white select-none">
                                        {{ strtoupper(mb_substr($name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Display name --}}
                        <h2 class="text-center text-lg font-bold text-gray-900 mb-1">
                            {{ $name }}
                        </h2>
                        <p class="text-center text-sm text-gray-700 mb-5 truncate">
                            {{ $email }}
                        </p>

                        <hr class="border-gray-100 mb-4">

                        {{-- Quick Info --}}
                        <div class="flex flex-col gap-3">

                            {{-- Name --}}
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                <div>
                                    <p class="text-xs text-gray-600">Họ tên</p>
                                    <p class="text-sm font-medium text-gray-800">{{ $name }}</p>
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                                <div>
                                    <p class="text-xs text-gray-600">Email</p>
                                    <p class="text-sm font-medium text-gray-800 break-all">{{ $email }}</p>
                                </div>
                            </div>

                            {{-- Phone --}}
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 6.75Z" />
                                </svg>
                                <div>
                                    <p class="text-xs text-gray-600">Điện thoại</p>
                                    <p class="text-sm font-medium text-gray-800">
                                        {{ $phone ?: '—' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Role --}}
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                <div class="min-w-0">
                                    <p class="text-xs text-gray-600">Vai trò</p>
                                    <div class="mt-1 flex flex-wrap gap-2">
                                        @forelse($roles as $role)
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                                {{ $role }}
                                            </span>
                                        @empty
                                            <span class="text-sm font-medium text-gray-900">Chưa có vai trò</span>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                        </div>

                        <hr class="border-gray-100 my-4">

                        {{-- ── Account Status Badge ── --}}
                        <div>
                            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                                Trạng thái tài khoản
                            </p>

                            @if($status === 'active')
                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full
                                                bg-emerald-50
                                                border border-emerald-200">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span class="text-sm font-semibold text-emerald-700">
                                        Đang hoạt động
                                    </span>
                                </div>
                            @else
                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full
                                                bg-red-50
                                                border border-red-200">
                                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                    <span class="text-sm font-semibold text-red-700">
                                        Đã khóa
                                    </span>
                                </div>

                                {{-- Block details --}}
                                @if($blocked_reason || $blocked_at)
                                    <div class="mt-3 p-3 rounded-xl bg-red-50 border border-red-100 space-y-1.5">
                                        @if($blocked_reason)
                                            <div class="flex items-start gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="w-3.5 h-3.5 text-red-400 mt-0.5 flex-shrink-0" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                                </svg>
                                                <div>
                                                    <p class="text-xs text-red-400">Lý do khóa</p>
                                                    <p class="text-xs font-medium text-red-700">{{ $blocked_reason }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if($blocked_at)
                                            <div class="flex items-start gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="w-3.5 h-3.5 text-red-400 mt-0.5 flex-shrink-0" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                                </svg>
                                                <div>
                                                    <p class="text-xs text-red-400">Ngày khóa</p>
                                                    <p class="text-xs font-medium text-red-700">{{ $blocked_at }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endif
                        </div>

                    </div>
                </div>

                {{-- ── Contributor Balance Card ── --}}
                <div class="bg-gradient-to-br from-indigo-600 via-blue-600 to-cyan-500
                            rounded-2xl shadow-md shadow-blue-500/20 p-5 text-white">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-xs font-semibold text-blue-100/80 uppercase tracking-wider mb-0.5">
                                Số dư cộng tác viên
                            </p>
                        </div>
                        {{-- Wallet icon --}}
                        <div class="p-2 bg-white/15 rounded-xl backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-extrabold tracking-tight">
                        {{ number_format($contributor_balance, 0, ',', '.') }}
                        <span class="text-lg font-semibold text-blue-100/70">₫</span>
                    </p>
                    <p class="text-xs text-gray-600 mt-1">Số dư hiện tại của bạn</p>
                </div>

                {{-- ── CTV Registration Card ── --}}
                <div class="bg-gradient-to-br from-emerald-500 via-green-500 to-teal-500
                            rounded-2xl shadow-md shadow-green-500/20 p-5 text-white">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-xs font-semibold text-green-100/80 uppercase tracking-wider mb-0.5">
                                Cộng tác viên
                            </p>
                        </div>
                        {{-- Handshake icon --}}
                        <div class="p-2 bg-white/15 rounded-xl backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Trở thành CTV</h3>
                    <p class="text-xs text-green-100/90 mb-4">
                        Đăng ký làm cộng tác viên để kiếm thêm thu nhập từ việc chia sẻ khóa học.
                    </p>
                    <a href="{{ route('auth.ctv.register') }}" 
                       class="inline-flex items-center justify-center w-full px-4 py-2.5 
                              bg-white/15 hover:bg-white/25 transition-colors duration-200
                              rounded-xl border border-white/30 backdrop-blur-sm
                              text-sm font-semibold text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                        </svg>
                        Đăng kí CTV
                    </a>
                </div>

            </div>
            {{-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ END LEFT SIDEBAR ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ --}}

            {{-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ RIGHT CONTENT (70%) ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">

                {{-- Card header --}}
                <div class="flex items-center gap-3 px-8 py-6 border-b border-gray-100">
                    <div class="p-2 bg-blue-50 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Thông tin cá nhân</h2>
                        <p class="text-sm text-gray-700">Cập nhật thông tin hồ sơ của bạn.</p>
                    </div>
                </div>

                {{-- Form --}}
                <form wire:submit.prevent="save" class="px-8 py-7 space-y-6">

                    {{-- Row 1: Name + Email --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                        {{-- Họ và tên --}}
                        <div>
                            <x-input wire:model="name" id="profile-name" label="Họ và tên" placeholder="Nhập họ và tên"
                                icon="user" :error="$errors->first('name')" />
                        </div>

                        {{-- Email — Readonly --}}
                        <div>
                            <x-input wire:model="email" id="profile-email" label="Email" placeholder="Email"
                                icon="envelope" readonly class="bg-gray-50 cursor-not-allowed text-gray-800"
                                hint="Email không thể thay đổi." />
                        </div>

                    </div>

                    {{-- Row 2: Phone --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <x-input wire:model="phone" id="profile-phone" label="Số điện thoại"
                                placeholder="Nhập số điện thoại" icon="phone" :error="$errors->first('phone')" />
                        </div>

                        {{-- Google ID — Readonly status display --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-800 mb-1.5">
                                Tài khoản Google
                            </label>
                            <div class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl
                                        border border-gray-200
                                        bg-gray-50">
                                {{-- Google icon --}}
                                <svg class="w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 48 48">
                                    <path fill="#EA4335"
                                        d="M24 9.5c3.54 0 6.06 1.53 7.46 2.81l5.47-5.43C34.98 4.07 29.9 2 24 2 14.98 2 7.62 7.9 4.6 15.8l6.62 5.14C12.92 16.1 18 9.5 24 9.5z" />
                                    <path fill="#4285F4"
                                        d="M46.5 24.5c0-1.58-.15-3.1-.43-4.57H24v9.07h12.7c-.55 2.97-2.36 5.48-5.03 7.15l7.76 6C43.8 38.43 46.5 31.9 46.5 24.5z" />
                                    <path fill="#FBBC05"
                                        d="M11.22 28.94A14.87 14.87 0 0 1 10 24.5c0-1.43.22-2.82.62-4.12L4 15.24A24.01 24.01 0 0 0 2 24.5c0 3.9.93 7.59 2.62 10.9l6.6-6.46z" />
                                    <path fill="#34A853"
                                        d="M24 46c6.64 0 12.24-2.2 16.32-5.96l-7.76-6C29.84 35.7 27.2 36.8 24 36.8c-5.98 0-11.02-4.1-12.8-9.7l-6.62 5.14C7.62 40.1 14.98 46 24 46z" />
                                </svg>
                                @if($google_id)
                                    <span class="text-sm font-medium text-emerald-600">
                                        Đã liên kết Google
                                    </span>
                                    <span
                                        class="ml-auto inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                        ✓ Liên kết
                                    </span>
                                @else
                                    <span class="text-sm text-gray-700">
                                        Chưa liên kết Google
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Bio --}}
                    <div>
                        <x-textarea wire:model="bio" id="profile-bio" label="Tiểu sử"
                            placeholder="Viết vài dòng giới thiệu bản thân..." rows="4"
                            :error="$errors->first('bio')" />
                        <p class="mt-1.5 text-xs text-gray-700 text-right">
                            {{ mb_strlen($bio) }} / 1000 ký tự
                        </p>
                    </div>

                    {{-- ── Action Buttons ── --}}
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-700">
                            * Email không thể thay đổi sau khi đăng ký.
                        </p>

                        <x-button type="submit" id="profile-save-btn" primary wire:loading.attr="disabled"
                            wire:target="save" class="min-w-[140px] justify-center">
                            <span wire:loading.remove wire:target="save" class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 3.75H6.912a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 0 2.013-1.244h3.859M12 3v8.25m0 0-3-3m3 3 3-3" />
                                </svg>
                                Lưu thay đổi
                            </span>
                            <span wire:loading wire:target="save" class="flex items-center gap-2">
                                <svg class="w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Đang lưu...
                            </span>
                        </x-button>
                    </div>

                </form>

            </div>
            {{-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ END RIGHT CONTENT ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ --}}

        </div>
        {{-- ══════════════════════════════ END MAIN GRID ═══════════════════════════════════════ --}}

    </div>
</div>