@component('mail::message')
# Xin chào {{ $user->name ?? 'bạn' }},

Tin vui cho bạn! Tài liệu của bạn đã được quản trị viên phê duyệt thành công và hiện đã được xuất bản công khai trên hệ thống **IT-Learning**.

<div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 20px; margin: 20px 0;">
<h3 style="margin-top: 0; color: #166534; font-size: 16px; margin-bottom: 16px; text-transform: uppercase;">Thông Tin Tài Liệu</h3>
<div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 15px;">
<span style="color: #15803d;">Tên tài liệu:</span>
<strong style="color: #14532d; text-align: right; max-width: 70%;">{{ $document->title }}</strong>
</div>
<hr style="border: 0; border-top: 1px solid #bbf7d0; margin: 16px 0;">
<p style="margin: 0; color: #16a34a; font-weight: bold; text-align: right;">
Trạng thái: ĐÃ DUYỆT
</p>
</div>

@component('mail::button', ['url' => $url, 'color' => 'success'])
Xem tài liệu của bạn
@endcomponent

Cảm ơn bạn đã đóng góp nội dung chất lượng cho cộng đồng!

Trân trọng,<br>
**Đội ngũ IT-Learning**
@endcomponent
