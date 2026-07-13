<div class="relative" x-data="{ open: false }" wire:poll.1s="updateUnreadCount">
    <!-- Bell Icon Button -->
    <button 
        @click="open = !open"
        wire:click="loadNotifications"
        type="button"
        class="relative p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-full transition-colors duration-200 focus:outline-none"
        aria-label="Thông báo"
    >
        <!-- Bell Icon SVG -->
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>

        <!-- Unread Badge -->
        @if($unreadCount > 0)
        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full min-w-[20px]">
            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
        </span>
        @endif
    </button>

    <!-- Dropdown Menu -->
    <div 
        x-show="open"
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed left-4 right-4 top-[70px] sm:absolute sm:top-auto sm:left-auto sm:right-0 sm:mt-2 sm:w-96 bg-white rounded-xl shadow-2xl border border-gray-100 z-[100]"
        style="display: none;"
    >
        <!-- Dropdown Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-blue-50">
            <h3 class="text-lg font-semibold text-gray-800">Thông báo</h3>
            @if($unreadCount > 0)
            <button 
                wire:click="markAllAsRead"
                class="text-sm text-purple-600 hover:text-purple-800 font-medium transition-colors duration-200"
            >
                Đánh dấu tất cả đã đọc
            </button>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="max-h-80 overflow-y-auto custom-scrollbar">
            @forelse($this->notifications as $notification)
            <div 
                wire:key="notif-{{ $notification['id'] }}-{{ $lastLoadedTime }}"
                wire:click="markAsRead('{{ $notification['id'] }}')"
                class="px-3 py-2.5 hover:bg-gray-50 transition-colors duration-200 cursor-pointer border-b border-gray-100 {{ $notification['is_unread'] ? 'bg-blue-50/60' : '' }}"
            >
                <div class="flex items-start space-x-3">
                    <!-- Icon -->
                    <div class="flex-shrink-0">
                        <span class="text-2xl">
                            {{ $notification['data']['icon'] ?? '🔔' }}
                        </span>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold text-gray-900 line-clamp-1 pr-2">
                                {{ $notification['data']['title'] ?? 'Thông báo' }}
                            </p>
                            @if($notification['is_unread'])
                            <span class="flex-shrink-0 w-2 h-2 bg-blue-600 rounded-full mt-1"></span>
                            @endif
                        </div>
                        
                        <p class="mt-1 text-[13px] leading-relaxed text-gray-600 break-words whitespace-normal">
                            {{ $notification['data']['message'] ?? '' }}
                        </p>

                        <!-- Additional Info -->
                        @if(isset($notification['data']['amount']) && isset($notification['data']['type']) && $notification['data']['type'] === 'document_sold')
                        <div class="mt-1.5 flex items-center space-x-4 text-[13px] text-gray-500">
                            <span class="font-medium text-purple-600">
                                Bạn nhận được: +{{ is_numeric($notification['data']['amount']) ? number_format($notification['data']['amount']) : $notification['data']['amount'] }}đ
                            </span>
                        </div>
                        @endif

                        <!-- Timestamp -->
                        <p class="mt-1.5 text-xs text-gray-400 font-medium">
                            {{ $notification['time_ago'] }}
                        </p>
                    </div>
                </div>
            </div>
            @empty
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center py-12 px-4">
                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="text-gray-500 font-medium">Không có thông báo mới</p>
                <p class="text-gray-400 text-sm mt-1">Bạn sẽ nhận thông báo ở đây</p>
            </div>
            @endforelse
        </div>


    </div>
</div>
