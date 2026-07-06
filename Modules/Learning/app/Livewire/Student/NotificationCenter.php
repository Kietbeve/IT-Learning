<?php

namespace Modules\Learning\Livewire\Student;

use Livewire\Component;
use Modules\Learning\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class NotificationCenter extends Component
{
    public $notifications = [];
    public $unreadCount = 0;
    public $showDropdown = false;
    public $limit = 10;

    protected $listeners = [
        'notification-received' => 'refreshNotifications',
        'echo:notifications.{userId},NotificationReceived' => 'handleNewNotification',
    ];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $notificationService = app(NotificationService::class);
        $userId = Auth::id();

        $this->notifications = $notificationService->getUnreadNotifications($userId, $this->limit);
        $this->unreadCount = $notificationService->getUnreadCount($userId);
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
        
        if ($this->showDropdown) {
            $this->loadNotifications();
        }
    }

    public function markAsRead($notificationId)
    {
        $notificationService = app(NotificationService::class);
        $notificationService->markAsRead($notificationId);
        
        $this->loadNotifications();
        $this->dispatch('notification-read', notificationId: $notificationId);
    }

    public function markAllAsRead()
    {
        $notificationService = app(NotificationService::class);
        $notificationService->markAllAsRead(Auth::id());
        
        $this->loadNotifications();
        $this->dispatch('all-notifications-read');
        
        session()->flash('success', 'Đã đánh dấu tất cả thông báo là đã đọc');
    }

    public function handleNewNotification($notification)
    {
        $this->unreadCount++;
        $this->dispatch('show-toast', [
            'message' => $notification['title'],
            'type' => 'info'
        ]);
    }

    public function refreshNotifications()
    {
        $this->loadNotifications();
    }

    public function getNotificationIcon($type)
    {
        return match($type) {
            'assignment_due' => '📝',
            'assignment_graded' => '✅',
            'quiz_available' => '❓',
            'section_unlocked' => '🔓',
            'certificate_earned' => '🎓',
            'deadline_reminder' => '⏰',
            'course_update' => '📢',
            default => '🔔'
        };
    }

    public function getNotificationColor($priority)
    {
        return match($priority) {
            'urgent' => 'bg-red-50 border-red-200',
            'high' => 'bg-orange-50 border-orange-200',
            'normal' => 'bg-blue-50 border-blue-200',
            'low' => 'bg-gray-50 border-gray-200',
            default => 'bg-gray-50 border-gray-200'
        };
    }

    public function getTimeAgo($date)
    {
        $diff = now()->diffInMinutes($date);
        
        if ($diff < 1) {
            return 'Vừa xong';
        } elseif ($diff < 60) {
            return $diff . ' phút trước';
        } elseif ($diff < 1440) {
            return floor($diff / 60) . ' giờ trước';
        } else {
            return floor($diff / 1440) . ' ngày trước';
        }
    }

    public function render()
    {
        return view('learning::livewire.student.notification-center');
    }
}
