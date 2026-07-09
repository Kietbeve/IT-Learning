@component('mail::message')
# Xin chào {{ $user->name ?? 'bạn' }},

Chúng tôi xin thông báo về kết quả kiểm duyệt tài liệu của bạn trên hệ thống **IT-Learning**. Rất tiếc, tài liệu này hiện chưa đủ điều kiện để được phê duyệt.

<div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 20px; margin: 20px 0;">
<h3 style="margin-top: 0; color: #991b1b; font-size: 16px; margin-bottom: 16px; text-transform: uppercase;">Chi Tiết Từ Chối</h3>
<div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 15px;">
<span style="color: #b91c1c;">Tên tài liệu:</span>
<strong style="color: #7f1d1d; text-align: right; max-width: 70%;">{{ $document->title }}</strong>
</div>
<div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 15px;">
<span style="color: #b91c1c;">Lý do từ chối:</span>
<strong style="color: #7f1d1d; text-align: right; max-width: 70%;">{{ $reason }}</strong>
</div>
<hr style="border: 0; border-top: 1px solid #fecaca; margin: 16px 0;">
<p style="margin: 0; color: #dc2626; font-weight: bold; text-align: right;">
Trạng thái: TỪ CHỐI
</p>
</div>

Bạn vui lòng kiểm tra lại lý do từ chối, cập nhật lại nội dung tài liệu cho phù hợp và gửi yêu cầu duyệt lại nhé.

@component('mail::button', ['url' => $url, 'color' => 'error'])
Vào bảng điều khiển
@endcomponent

Trân trọng,<br>
**Đội ngũ IT-Learning**
@endcomponent
