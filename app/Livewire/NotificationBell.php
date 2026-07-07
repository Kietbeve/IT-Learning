<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class NotificationBell extends Component
{
    use WireUiActions;

    public $unreadCount = 0;
    public $userId;
    public $lastLoadedTime;

    #[On('new-notification')]
    public function handleNewNotification($event = null)
    {
        $this->updateUnreadCount();
        $this->lastLoadedTime = now()->timestamp;
        $this->dispatch('refresh'); // trigger UI update
    }



    #[On('notificationRead')]
    public function handleNotificationRead()
    {
        $this->updateUnreadCount();
    }

    public function mount()
    {
        if (Auth::check()) {
            $this->userId = Auth::id();
            $this->unreadCount = Auth::user()->unreadNotifications()->count();
        }
    }

    public function loadNotifications()
    {
        // Thay đổi 1 biến state để Livewire BẮT BUỘC phải re-render lại HTML
        // Nhờ đó, nó sẽ gọi lại getNotificationsProperty() và cập nhật thời gian (time_ago)
        $this->lastLoadedTime = now()->timestamp;
    }

    public function updateUnreadCount()
    {
        if (Auth::check()) {
            $newCount = Auth::user()->unreadNotifications()->count();
            if ($newCount > $this->unreadCount) {
                $latestNotification = Auth::user()->unreadNotifications()->latest()->first();
                
                if ($latestNotification && isset($latestNotification->data['title']) && isset($latestNotification->data['message'])) {
                    // Prevent duplicate toasts across tabs/polls using Cache lock for 5 seconds
                    $cacheKey = 'notified_' . Auth::id() . '_' . $latestNotification->id;
                    if (!\Illuminate\Support\Facades\Cache::has($cacheKey)) {
                        \Illuminate\Support\Facades\Cache::put($cacheKey, true, 5);
                        
                        $notificationType = $latestNotification->data['type'] ?? '';
                        
                        $this->dispatch('notify', [
                            'type' => 'success',
                            'title' => $latestNotification->data['title'],
                            'message' => \Illuminate\Support\Str::limit($latestNotification->data['message'], 100)
                        ]);
                    }
                } else {
                    $this->dispatch('notify', [
                        'type' => 'info',
                        'title' => 'Bạn có thông báo mới',
                        'message' => 'Vui lòng kiểm tra hộp thư thông báo.'
                    ]);
                }
            }
            
            $this->unreadCount = $newCount;
        }
    }

    public function getNotificationsProperty()
    {
        if (!Auth::check()) {
            return collect([]);
        }

        return Auth::user()
            ->notifications()
            ->latest()
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'data' => $notification->data,
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at,
                    'is_unread' => is_null($notification->read_at),
                    'time_ago' => $this->getTimeAgo($notification->created_at),
                ];
            });
    }

    public function markAsRead($notificationId)
    {
        if (!Auth::check()) {
            return;
        }

        $notification = Auth::user()
            ->notifications()
            ->where('id', $notificationId)
            ->first();

        if ($notification) {
            $notification->markAsRead();
            $this->updateUnreadCount();
            $this->dispatch('notificationRead');

            if (!empty($notification->data['url'])) {
                return redirect($notification->data['url']);
            }
        }
    }

    public function markAllAsRead()
    {
        if (!Auth::check()) {
            return;
        }

        Auth::user()->unreadNotifications->markAsRead();
        $this->updateUnreadCount();
        $this->dispatch('notificationRead');
    }

    protected function getTimeAgo($datetime)
    {
        $now = now();
        $diff = $datetime->diff($now);

        if ($diff->y > 0) {
            return $diff->y . ' năm trước';
        }
        if ($diff->m > 0) {
            return $diff->m . ' tháng trước';
        }
        if ($diff->d > 0) {
            return $diff->d . ' ngày trước';
        }
        if ($diff->h > 0) {
            return $diff->h . ' giờ trước';
        }
        if ($diff->i > 0) {
            return $diff->i . ' phút trước';
        }
        return 'Vừa xong';
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
