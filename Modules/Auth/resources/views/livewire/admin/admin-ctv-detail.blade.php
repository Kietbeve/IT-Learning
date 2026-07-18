<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Breadcrumb --}}
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                    <a href="{{ route('admin.ctv.list') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-primary-600 md:ml-2">Quản lý CTV</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Chi tiết đơn #{{ $application->id }}</span>
                </div>
            </li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-3xl font-bold text-gray-900">Chi tiết đơn đăng ký CTV #{{ $application->id }}</h1>
            <p class="mt-2 text-sm text-gray-600">Xem chi tiết và xử lý đơn đăng ký cộng tác viên</p>
        </div>
        <button wire:click="backToList" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Quay lại danh sách
        </button>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- User Information Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-primary-50 to-primary-100 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-8 h-8 mr-2 rounded-full object-cover ring-2 ring-white shadow-sm">
                        @else
                            <svg class="w-5 h-5 mr-2 text-primary-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                        @endif
                        Thông tin người dùng
                    </h2>
                    {!! $this->getStatusBadge() !!}
                </div>
                <div class="px-6 py-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Họ tên</label>
                            <p class="text-base text-gray-900 font-medium">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                            <p class="text-base text-gray-900">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Số điện thoại</label>
                            <p class="text-base text-gray-900">{{ $user->phone ?? 'Chưa cập nhật' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Ngày tham gia</label>
                            <p class="text-base text-gray-900">{{ $user->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    @if($user->bio)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-500 mb-2">Giới thiệu</label>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <p class="text-sm text-gray-700">{{ $user->bio }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Application Details Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-blue-100 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                        Chi tiết đơn đăng ký
                    </h2>
                </div>
                <div class="px-6 py-5 space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Lĩnh vực chuyên môn</label>
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <p class="text-base text-gray-900">{{ $application->expertise }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Lý do muốn trở thành CTV</label>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $application->reason }}</p>
                        </div>
                    </div>

                    @if($this->hasCV())
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">CV đính kèm</label>
                        <a href="{{ $this->getCVUrl() }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg text-sm font-medium text-red-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                            Xem CV
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Ngày đăng ký</label>
                            <p class="text-sm text-gray-900 font-medium">{{ $this->formatCreatedAt() }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Ngày duyệt</label>
                            <p class="text-sm text-gray-900 font-medium">{{ $this->formatReviewedAt() }}</p>
                        </div>
                    </div>

                    @if($application->reviewed_by)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Người duyệt</label>
                        <p class="text-sm text-gray-900 font-medium">{{ $this->getReviewerName() }}</p>
                    </div>
                    @endif

                    @if($application->rejected_reason)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <label class="block text-sm font-semibold text-red-800 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            Lý do từ chối
                        </label>
                        <p class="text-sm text-red-700 whitespace-pre-wrap">{{ $application->rejected_reason }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            
            {{-- Status & Actions Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-purple-100 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg>
                        Trạng thái & Thao tác
                    </h2>
                </div>
                <div class="px-6 py-5">
                    <div class="flex justify-center mb-6">
                        {!! $this->getStatusBadge() !!}
                    </div>

                    @if($this->canProcess())
                    <div class="space-y-3">
                        <button wire:click="openApproveModal" class="w-full inline-flex items-center justify-center px-4 py-3 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Duyệt đơn
                        </button>
                        <button wire:click="openRejectModal" class="w-full inline-flex items-center justify-center px-4 py-3 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            Từ chối đơn
                        </button>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <div class="inline-flex items-center px-4 py-2 bg-gray-100 rounded-lg">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            <span class="text-sm font-medium text-gray-600">Đơn đã được xử lý</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Statistics Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-indigo-100 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/></svg>
                        Thống kê người dùng
                    </h2>
                </div>
                <div class="px-6 py-5 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Số dư CTV</span>
                        <span class="text-base font-bold text-gray-900">{{ number_format($user->contributor_balance ?? 0) }} ₫</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Trạng thái tài khoản</span>
                        @if($this->isContributor())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Đã là CTV</span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Chưa là CTV</span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Ngày tham gia</span>
                        <span class="text-sm font-medium text-gray-900">{{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Duyệt đơn - Alpine.js --}}
    <div x-data="{ showApproveModal: @entangle('showApproveModal') }" x-effect="document.body.style.overflow = showApproveModal ? 'hidden' : ''">
        <template x-teleport="body">
            <div x-show="showApproveModal" class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4 sm:p-0" style="display: none;">
                <div x-show="showApproveModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="$wire.closeApproveModal()"></div>

                <div x-show="showApproveModal"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-2xl bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">

                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold leading-6 text-gray-900">Duyệt đơn đăng ký CTV</h3>
                            <p class="mt-2 text-sm text-gray-600">
                                Bạn có chắc chắn muốn duyệt đơn đăng ký này không?
                            </p>
                            <p class="mt-1 text-sm text-gray-600">
                                User <span class="font-semibold text-green-600">{{ $user->name }}</span> sẽ trở thành <strong>Cộng tác viên</strong>.
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <button 
                            @click="$wire.closeApproveModal()"
                            class="inline-flex w-full sm:w-auto justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors"
                        >
                            Hủy bỏ
                        </button>
                        <button
                            wire:click="confirmApprove"
                            wire:loading.attr="disabled"
                            wire:target="confirmApprove"
                            class="inline-flex w-full sm:w-auto justify-center rounded-xl bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-green-500 transition-colors disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <span wire:loading.remove wire:target="confirmApprove">Xác nhận duyệt</span>
                            <span wire:loading wire:target="confirmApprove" class="flex items-center gap-2">
                                <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                Đang xử lý...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- Modal Từ chối đơn - Alpine.js --}}
    <div x-data="{ showRejectModal: @entangle('showRejectModal') }" x-effect="document.body.style.overflow = showRejectModal ? 'hidden' : ''">
        <template x-teleport="body">
            <div x-show="showRejectModal" class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4 sm:p-0" style="display: none;">
                <div x-show="showRejectModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="$wire.closeRejectModal()"></div>

                <div x-show="showRejectModal"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-2xl bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">

                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold leading-6 text-gray-900">Từ chối đơn đăng ký CTV</h3>
                            <p class="mt-2 text-sm text-gray-600">
                                Vui lòng nhập lý do từ chối để gửi phản hồi cho người dùng.
                            </p>
                        </div>
                    </div>

                    <form wire:submit="confirmReject" class="mt-4 space-y-4">
                        <div>
                            <label for="rejectReason" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Lý do từ chối <span class="text-rose-500">*</span>
                            </label>
                            <textarea 
                                id="rejectReason"
                                wire:model="rejectReason"
                                rows="4"
                                class="block w-full rounded-xl border-0 py-2.5 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-red-600 sm:text-sm sm:leading-6 transition-all @error('rejectReason') ring-rose-500 @enderror"
                                placeholder="Nhập lý do từ chối đơn đăng ký (tối thiểu 10 ký tự)..."
                            ></textarea>
                            @error('rejectReason')
                                <p class="mt-1.5 text-sm text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 border-t border-gray-100 pt-4">
                            <button 
                                type="button"
                                @click="$wire.closeRejectModal()"
                                class="inline-flex w-full sm:w-auto justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors"
                            >
                                Hủy bỏ
                            </button>
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                wire:target="confirmReject"
                                class="inline-flex w-full sm:w-auto justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-rose-600 shadow-sm ring-1 ring-inset ring-rose-300 hover:bg-rose-50 transition-colors disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                <span wire:loading.remove wire:target="confirmReject">Xác nhận từ chối</span>
                                <span wire:loading wire:target="confirmReject" class="flex items-center gap-2">
                                    <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    Đang xử lý...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
</div>
