<div class="relative">
    <!-- Notification Bell Button -->
    <button 
        wire:click="toggleDropdown" 
        class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg"
        type="button"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown Menu -->
    @if($showDropdown)
        <div 
            class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-50"
            x-data
            @click.away="$wire.showDropdown = false"
        >
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Thông báo</h3>
                
                @if($unreadCount > 0)
                    <button 
                        wire:click="markAllAsRead"
                        class="text-sm text-blue-600 hover:text-blue-800"
                    >
                        Đánh dấu tất cả đã đọc
                    </button>
                @endif
            </div>

            <!-- Notifications List -->
            <div class="max-h-96 overflow-y-auto">
                @forelse($notifications as $notification)
                    <div 
                        wire:key="notification-{{ $notification->id }}"
                        class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100 cursor-pointer {{ $notification->is_read ? 'opacity-60' : '' }}"
                        wire:click="markAsRead({{ $notification->id }})"
                    >
                        <div class="flex items-start gap-3">
                            <!-- Icon -->
                            <span class="text-2xl flex-shrink-0">
                                {!! $this->getNotificationIcon($notification->type) !!}
                            </span>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $notification->title }}
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $notification->message }}
                                </p>
                                
                                <!-- Time -->
                                <p class="text-xs text-gray-500 mt-2">
                                    {{ $this->getTimeAgo($notification->created_at) }}
                                </p>

                                <!-- Action Button -->
                                @if($notification->action_url)
                                    <a 
                                        href="{{ $notification->action_url }}"
                                        class="inline-block mt-2 text-sm text-blue-600 hover:text-blue-800 font-medium"
                                    >
                                        {{ $notification->action_text }}
                                        <span aria-hidden="true"> →</span>
                                    </a>
                                @endif
                            </div>

                            <!-- Priority Badge -->
                            @if($notification->priority === 'urgent' || $notification->priority === 'high')
                                <span class="flex-shrink-0 w-2 h-2 bg-red-500 rounded-full"></span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-8 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="mt-4 text-gray-500">Không có thông báo mới</p>
                    </div>
                @endforelse
            </div>

            <!-- Footer -->
            @if($notifications->isNotEmpty())
                <div class="px-4 py-3 border-t border-gray-200 text-center">
                    <a href="{{ route('learning.notifications') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Xem tất cả thông báo
                    </a>
                </div>
            @endif
        </div>
    @endif
</div>
