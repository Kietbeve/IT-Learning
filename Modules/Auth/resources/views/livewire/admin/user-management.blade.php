<div>



    <div
        x-data="{ showLockModal: @entangle('showLockModal'), showUnlockModal: @entangle('showUnlockModal'), showDetailModal: @entangle('showDetailModal'), showUserModal: @entangle('showUserModal') }">
        {{-- Loading Overlay --}}
        {{-- <div wire:loading class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50">
            <div class="rounded-lg bg-white px-6 py-4 shadow-xl">
                <div class="flex items-center gap-3">
                    <svg class="h-6 w-6 animate-spin text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span class="text-slate-700">Đang tải...</span>
                </div>
            </div>
        </div> --}}

        {{-- Page Title --}}
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Quản lý người dùng</h1>
                <p class="mt-1 text-sm text-slate-600">Quản lý tài khoản và phân quyền người dùng trong hệ thống</p>
            </div>
            <button wire:click="openCreateModal" 
                    class="rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <span class="flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Thêm người dùng
                </span>
            </button>
        </div>

        {{-- Statistics Cards --}}
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
            {{-- Tổng user --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Tổng user</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($statistics['total']) }}</p>
                    </div>
                    <div class="rounded-lg bg-blue-100 p-3">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Hoạt động --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Hoạt động</p>
                        <p class="mt-1 text-2xl font-bold text-emerald-600">{{ number_format($statistics['active']) }}
                        </p>
                    </div>
                    <div class="rounded-lg bg-emerald-100 p-3">
                        <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Bị khóa --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Bị khóa</p>
                        <p class="mt-1 text-2xl font-bold text-rose-600">{{ number_format($statistics['blocked']) }}</p>
                    </div>
                    <div class="rounded-lg bg-rose-100 p-3">
                        <svg class="h-6 w-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Admin --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Admin</p>
                        <p class="mt-1 text-2xl font-bold text-purple-600">{{ number_format($statistics['admin']) }}</p>
                    </div>
                    <div class="rounded-lg bg-purple-100 p-3">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Student --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Người học</p>
                        <p class="mt-1 text-2xl font-bold text-cyan-600">{{ number_format($statistics['user']) }}</p>
                    </div>
                    <div class="rounded-lg bg-cyan-100 p-3">
                        <svg class="h-6 w-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Contributor --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Cộng tác viên</p>
                        <p class="mt-1 text-2xl font-bold text-amber-600">
                            {{ number_format($statistics['contributor']) }}
                        </p>
                    </div>
                    <div class="rounded-lg bg-amber-100 p-3">
                        <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search and Filter Bar --}}
        <div class="mb-6 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                {{-- Search --}}
                <div class="md:col-span-2">
                    <label for="search" class="mb-2 block text-sm font-medium text-slate-700">Tìm kiếm</label>
                    <input type="text" id="search" wire:model.live.debounce.300ms="search"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Tìm theo tên hoặc email...">
                </div>

                {{-- Role Filter --}}
                <div>
                    <label for="roleFilter" class="mb-2 block text-sm font-medium text-slate-700">Vai trò</label>
                    <select id="roleFilter" wire:model.live="roleFilter"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tất cả vai trò</option>
                        <option value="admin">Admin</option>
                        <option value="student">Student</option>
                        <option value="contributor">Contributor</option>
                    </select>
                </div>

                {{-- Status Filter --}}
                <div>
                    <label for="statusFilter" class="mb-2 block text-sm font-medium text-slate-700">Trạng thái</label>
                    <select id="statusFilter" wire:model.live="statusFilter"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tất cả trạng thái</option>
                        <option value="active">Hoạt động</option>
                        <option value="blocked">Bị khóa</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700">
                                Avatar</th>
                            <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700"
                                wire:click="sortBy('name')">
                                <div class="flex items-center gap-1">
                                    Tên
                                    @if($sortField === 'name')
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($sortDirection === 'asc')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 15l7-7 7 7" />
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            @endif
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700"
                                wire:click="sortBy('email')">
                                <div class="flex items-center gap-1">
                                    Email
                                    @if($sortField === 'email')
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($sortDirection === 'asc')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 15l7-7 7 7" />
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            @endif
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700">
                                Vai trò</th>
                            <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700"
                                wire:click="sortBy('created_at')">
                                <div class="flex items-center gap-1">
                                    Ngày tham gia
                                    @if($sortField === 'created_at')
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($sortDirection === 'asc')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 15l7-7 7 7" />
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            @endif
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-700">
                                Trạng thái</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-slate-700">
                                Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50">
                                {{-- Avatar --}}
                                <td class="px-6 py-4">
                                    <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                                        alt="{{ $user->name }}" class="h-10 w-10 rounded-full object-cover">
                                </td>
                                {{-- Name --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-slate-900">{{ $user->name }}</div>
                                </td>
                                {{-- Email --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-600">{{ $user->email }}</div>
                                </td>
                                {{-- Roles --}}
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($user->roles as $role)
                                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold
                                                            @if($role->name === 'admin') bg-purple-100 text-purple-800
                                                            @elseif($role->name === 'student') bg-cyan-100 text-cyan-800
                                                            @elseif($role->name === 'contributor') bg-amber-100 text-amber-800
                                                            @else bg-slate-100 text-slate-800
                                                            @endif">
                                                {{ ucfirst($role->name) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                {{-- Created At --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-600">{{ $user->created_at->format('d/m/Y') }}</div>
                                </td>
                                {{-- Status --}}
                                <td class="px-6 py-4">
                                    @if($user->status === 'active')
                                        <span
                                            class="inline-flex rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-800">Hoạt
                                            động</span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-rose-100 px-2 py-1 text-xs font-semibold text-rose-800">Bị
                                            khóa</span>
                                    @endif
                                </td>
                                {{-- Actions --}}
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button wire:click="viewDetail({{ $user->id }})"
                                            class="rounded-lg bg-blue-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-600">
                                            Chi tiết
                                        </button>
                                        <button wire:click="openEditModal({{ $user->id }})"
                                            class="rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-amber-600">
                                            Sửa
                                        </button>
                                        @if($user->status === 'active')
                                            <button wire:click="openLockModal({{ $user->id }})"
                                                class="rounded-lg bg-rose-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-rose-600">
                                                Khóa
                                            </button>
                                        @else
                                            <button wire:click="openUnlockModal({{ $user->id }})"
                                                class="rounded-lg bg-emerald-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-emerald-600">
                                                Mở khóa
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-slate-400">
                                        <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p class="mt-2 text-sm">Không tìm thấy người dùng nào</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="border-t border-slate-200 bg-white px-6 py-4">
                {{ $users->links() }}
            </div>
        </div>

        {{-- Lock Modal --}}
        <div x-show="showLockModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50"
            @click.self="showLockModal = false">
            <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl" @click.away="showLockModal = false">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">Khóa tài khoản</h3>
                    <button @click="showLockModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mb-4">
                    <label for="blockReason" class="mb-2 block text-sm font-medium text-slate-700">
                        Lý do khóa <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="blockReason" wire:model="blockReason" rows="4"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Nhập lý do khóa tài khoản..."></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button @click="showLockModal = false"
                        class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        Hủy
                    </button>
                    <button wire:click="confirmLock"
                        class="rounded-lg bg-rose-500 px-4 py-2 text-sm font-medium text-white hover:bg-rose-600">
                        Xác nhận khóa
                    </button>
                </div>
            </div>
        </div>

        {{-- Unlock Modal --}}
        <div x-show="showUnlockModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50"
            @click.self="showUnlockModal = false">
            <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl" @click.away="showUnlockModal = false">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">Mở khóa tài khoản</h3>
                    <button @click="showUnlockModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <p class="mb-6 text-sm text-slate-600">
                    Bạn có chắc chắn muốn mở khóa tài khoản này không?
                </p>

                <div class="flex justify-end gap-3">
                    <button @click="showUnlockModal = false"
                        class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        Hủy
                    </button>
                    <button wire:click="confirmUnlock"
                        class="rounded-lg bg-emerald-500 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-600">
                        Xác nhận mở khóa
                    </button>
                </div>
            </div>
        </div>

        {{-- Detail Modal --}}
        <div x-show="showDetailModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50"
            @click.self="showDetailModal = false">
            <div class="w-full max-w-2xl rounded-xl bg-white p-6 shadow-xl" @click.away="showDetailModal = false">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">Chi tiết người dùng</h3>
                    <button @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                @if($selectedUser)
                    <div class="space-y-6">
                        {{-- Avatar and Basic Info --}}
                        <div class="flex items-center gap-4">
                            <img src="{{ $selectedUser->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($selectedUser->name) }}"
                                alt="{{ $selectedUser->name }}" class="h-20 w-20 rounded-full object-cover">
                            <div>
                                <h4 class="text-xl font-semibold text-slate-900">{{ $selectedUser->name }}</h4>
                                <p class="text-sm text-slate-600">{{ $selectedUser->email }}</p>
                            </div>
                        </div>

                        {{-- Info Grid --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-slate-700">Số điện thoại</p>
                                <p class="mt-1 text-sm text-slate-900">{{ $selectedUser->phone ?? 'Chưa cập nhật' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-700">Ngày tham gia</p>
                                <p class="mt-1 text-sm text-slate-900">{{ $selectedUser->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-700">Vai trò</p>
                                <div class="mt-1 flex flex-wrap gap-1">
                                    @foreach($selectedUser->roles as $role)
                                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold
                                                        @if($role->name === 'admin') bg-purple-100 text-purple-800
                                                        @elseif($role->name === 'student') bg-cyan-100 text-cyan-800
                                                        @elseif($role->name === 'contributor') bg-amber-100 text-amber-800
                                                        @else bg-slate-100 text-slate-800
                                                        @endif">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-700">Trạng thái</p>
                                <p class="mt-1">
                                    @if($selectedUser->status === 'active')
                                        <span
                                            class="inline-flex rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-800">Hoạt
                                            động</span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-rose-100 px-2 py-1 text-xs font-semibold text-rose-800">Bị
                                            khóa</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- Bio --}}
                        @if($selectedUser->bio)
                            <div>
                                <p class="text-sm font-medium text-slate-700">Giới thiệu</p>
                                <p class="mt-1 text-sm text-slate-900">{{ $selectedUser->bio }}</p>
                            </div>
                        @endif

                        {{-- Blocked Info --}}
                        @if($selectedUser->status === 'blocked')
                            <div class="rounded-lg border border-rose-200 bg-rose-50 p-4">
                                <h5 class="mb-2 font-semibold text-rose-900">Thông tin khóa</h5>
                                <div class="space-y-2 text-sm">
                                    <div>
                                        <span class="font-medium text-rose-700">Lý do:</span>
                                        <span class="text-rose-900">{{ $selectedUser->blocked_reason }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-rose-700">Thời gian:</span>
                                        <span
                                            class="text-rose-900">{{ $selectedUser->blocked_at ? $selectedUser->blocked_at->format('d/m/Y H:i') : 'N/A' }}</span>
                                    </div>
                                    @if($selectedUser->blocked_by)
                                        <div>
                                            <span class="font-medium text-rose-700">Người thực hiện:</span>
                                            <span class="text-rose-900">{{ $selectedUser->blocker->name ?? 'N/A' }}
                                                ({{ $selectedUser->blocker->email ?? '' }})</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- VIP Info --}}
                        @if($selectedUser->vip_expires_at)
                            <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                                <h5 class="mb-2 font-semibold text-amber-900">Thông tin VIP</h5>
                                <div class="space-y-2 text-sm">
                                    <div>
                                        <span class="font-medium text-amber-700">Hết hạn:</span>
                                        <span
                                            class="text-amber-900">{{ $selectedUser->vip_expires_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-amber-700">Hạn mức tải:</span>
                                        <span class="text-amber-900">{{ $selectedUser->vip_download_quota ?? 0 }} file</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- User Modal (Add/Edit) --}}
        <div x-show="showUserModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50"
            @click.self="$wire.closeUserModal()">
            <div class="w-full max-w-2xl rounded-xl bg-white p-6 shadow-xl" @click.away="$wire.closeUserModal()">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">
                        {{ $modalMode === 'create' ? 'Thêm người dùng mới' : 'Cập nhật người dùng' }}
                    </h3>
                    <button @click="$wire.closeUserModal()" class="text-slate-400 hover:text-slate-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    {{-- Name --}}
                    <div>
                        <label for="userName" class="mb-2 block text-sm font-medium text-slate-700">
                            Tên người dùng <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="userName" wire:model="form.name"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Nhập tên người dùng...">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="userEmail" class="mb-2 block text-sm font-medium text-slate-700">
                            Email <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" id="userEmail" wire:model="form.email"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="example@email.com">
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="userPassword" class="mb-2 block text-sm font-medium text-slate-700">
                            Mật khẩu 
                            @if($modalMode === 'create')
                                <span class="text-rose-500">* (Bắt buộc cho Admin)</span>
                            @else
                                <span class="text-slate-500">(Để trống nếu không đổi)</span>
                            @endif
                        </label>
                        <input type="password" id="userPassword" wire:model="form.password"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="••••••••">
                        <p class="mt-1 text-xs text-slate-500">
                            Lưu ý: Role Student/Contributor có thể đăng nhập qua Google (không cần mật khẩu)
                        </p>
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="userPhone" class="mb-2 block text-sm font-medium text-slate-700">
                            Số điện thoại
                        </label>
                        <input type="text" id="userPhone" wire:model="form.phone"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="0912345678">
                    </div>

                    {{-- Bio --}}
                    <div>
                        <label for="userBio" class="mb-2 block text-sm font-medium text-slate-700">
                            Giới thiệu
                        </label>
                        <textarea id="userBio" wire:model="form.bio" rows="3"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Giới thiệu ngắn về người dùng..."></textarea>
                    </div>

                    {{-- Roles --}}
                    <div>
                        <label for="userRoles" class="mb-2 block text-sm font-medium text-slate-700">
                            Vai trò <span class="text-rose-500">* (Chọn ít nhất 1)</span>
                        </label>
                        <select id="userRoles" wire:model="form.roles"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Chọn vai trò --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button @click="$wire.closeUserModal()"
                        class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        Hủy
                    </button>
                    <button wire:click="saveUser"
                        class="rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white hover:bg-blue-600">
                        {{ $modalMode === 'create' ? 'Tạo người dùng' : 'Cập nhật' }}
                    </button>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                // Livewire event listeners for notifications
                document.addEventListener('livewire:initialized', () => {
                    Livewire.on('show-success', (event) => {
                        window.$wireui.notify({
                            title: 'Thành công!',
                            description: event.message,
                            icon: 'success'
                        });
                    });

                    Livewire.on('show-error', (event) => {
                        window.$wireui.notify({
                            title: 'Lỗi!',
                            description: event.message,
                            icon: 'error'
                        });
                    });
                });
            </script>
        @endpush

        @push('styles')
            <style>
                [x-cloak] {
                    display: none !important;
                }
            </style>
        @endpush
    </div>
</div>