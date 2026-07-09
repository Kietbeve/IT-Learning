@component('mail::message')
# ⏰ VIP của bạn sắp hết hạn!

Xin chào **{{ $user->name }}**,

Đây là thông báo nhắc nhở: Tài khoản VIP của bạn tại IT-Learning sẽ hết hạn trong <strong style="color: #ef4444;">{{ $daysRemaining }} ngày nữa</strong>.

<div style="background: #fffbeb; border-left: 4px solid #f59e0b; padding: 16px; margin: 20px 0; border-radius: 4px;">
<div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
<span style="color: #4b5563;">Ngày hết hạn:</span>
<strong style="color: #ef4444;">{{ $expiresAt?->format('d/m/Y H:i') }}</strong>
</div>
<div style="display: flex; justify-content: space-between;">
<span style="color: #4b5563;">Lượt tải còn lại:</span>
<strong style="color: #0f172a;">{{ $quota }} lượt</strong>
</div>
</div>

<div style="background: #fef2f2; border-left: 4px solid #ef4444; padding: 16px; margin: 20px 0; border-radius: 4px;">
<p style="color: #991b1b; margin: 0; font-size: 14px; line-height: 1.6;">
<strong>⚠️ Sau khi VIP hết hạn:</strong><br>
• Bạn sẽ không thể tải thêm tài liệu Premium<br>
• Lượt tải còn lại sẽ bị hủy<br>
• Bạn cần gia hạn VIP để tiếp tục
</p>
</div>

@component('mail::button', ['url' => route('user.subscription'), 'color' => 'primary'])
🔄 Gia hạn VIP ngay
@endcomponent

Cảm ơn bạn đã đồng hành cùng IT-Learning!

Trân trọng,<br>
**Đội ngũ IT-Learning**
@endcomponent
