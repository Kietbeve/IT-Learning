@component('mail::message')
# Xin chào {{ $user->name }},

Cảm ơn bạn đã sử dụng dịch vụ của **IT-Learning**. Chúng tôi xin thông báo bạn đã sử dụng thành công 1 lượt tải VIP để mua và tải xuống tài liệu.

<div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin: 20px 0;">
<h3 style="margin-top: 0; color: #334155; font-size: 16px; margin-bottom: 16px; text-transform: uppercase;">Chi Tiết Giao Dịch</h3>
<div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 15px;">
<span style="color: #64748b;">Tên tài liệu:</span>
<strong style="color: #0f172a; text-align: right; max-width: 60%;">{{ $document->title }}</strong>
</div>
<div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 15px;">
<span style="color: #64748b;">Lượt tải VIP còn lại:</span>
<strong style="color: #0f172a;">{{ $user->vip_download_quota }} lượt</strong>
</div>
<hr style="border: 0; border-top: 1px solid #cbd5e1; margin: 16px 0;">
<p style="margin: 0; color: #059669; font-weight: bold; text-align: right;">
Trạng thái: THÀNH CÔNG
</p>
</div>


@component('mail::button', ['url' => route('documents.show', [$document->id, Str::slug($document->title)]), 'color' => 'primary'])
Xem tài liệu
@endcomponent


Trân trọng,<br>
**Đội ngũ IT-Learning**
@endcomponent
