# Hướng Dẫn Setup Môi Trường Cho Team

Sau khi các bạn `git pull` code mới nhất về, để hệ thống hoạt động đầy đủ (Queue Mail, Thông báo, Scheduler) các bạn **BẮT BUỘC** phải làm các bước sau:

## 1. Khởi động hệ thống (Làm mỗi khi code)

Dự án cần mở **2 cửa sổ Terminal** và để chạy ngầm:

**Terminal 1 - Chạy server Laravel:**
```bash
php artisan serve
```

**Terminal 2 - Chạy Queue Worker (xử lý Email & Thông báo):**
```bash
php artisan queue:work redis --queue=high,default
```
*(Lưu ý: Bắt buộc phải có `--queue=high,default` thì hệ thống mới gửi được Email VIP đúng thứ tự ưu tiên).*

> ✅ **Không cần** chạy `php artisan reverb:start` nữa. Hệ thống thông báo đang dùng Polling tự động mỗi 30 giây, không cần WebSocket.

---

## 2. Setup Task Scheduler (Chỉ cần làm 1 lần duy nhất)

Tính năng **Tự động gửi Email nhắc nhở gói VIP hết hạn lúc 9h sáng** yêu cầu máy tính phải có bộ đếm thời gian. Làm 2 bước sau để cài đặt (chỉ cần làm 1 lần):

1. Bấm nút Windows, gõ **PowerShell**, nhấn chuột phải chọn **Run as Administrator**.
2. Copy và Dán toàn bộ đoạn code sau vào rồi Enter:

```powershell
$action = New-ScheduledTaskAction `
    -Execute "C:\xampp\php\php.exe" `
    -Argument "artisan schedule:run" `
    -WorkingDirectory "D:\doantotnghiep\IT-Learning"

# Chạy mỗi 1 phút
$trigger = New-ScheduledTaskTrigger `
    -Once `
    -At (Get-Date) `
    -RepetitionInterval (New-TimeSpan -Minutes 1)

# Cài đặt
$settings = New-ScheduledTaskSettingsSet `
    -AllowStartIfOnBatteries `
    -DontStopIfGoingOnBatteries `
    -Hidden

# Đăng ký task
Register-ScheduledTask `
    -TaskName "Laravel-Scheduler" `
    -Action $action `
    -Trigger $trigger `
    -Settings $settings `
    -Force

Write-Host "✅ Laravel Scheduler đã được tạo và sẽ chạy mỗi phút." -ForegroundColor Green
```

> **Lưu ý:** Nếu đường dẫn thư mục project của bạn khác với `D:\doantotnghiep\IT-Learning`, hãy sửa lại đường dẫn ở phần `-WorkingDirectory` cho đúng trước khi chạy.
