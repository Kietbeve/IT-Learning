@component('mail::message')
# Xin chào {{ $user->name ?? 'bạn' }},

Chúng tôi xin thông báo về một thay đổi liên quan đến tài liệu của bạn trên hệ thống **IT-Learning**.

<div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 20px; margin: 20px 0;">
<h3 style="margin-top: 0; color: #92400e; font-size: 16px; margin-bottom: 16px; text-transform: uppercase;">Thông Tin Tài Liệu</h3>
<div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 15px;">
<span style="color: #b45309;">Tên tài liệu:</span>
<strong style="color: #78350f; text-align: right; max-width: 70%;">{{ $documentTitle }}</strong>
</div>
<hr style="border: 0; border-top: 1px solid #fde68a; margin: 16px 0;">
<p style="margin: 0; color: #d97706; font-weight: bold; text-align: right;">
Trạng thái: BỊ GỠ BỎ
</p>
</div>

Tài liệu này đã bị quản trị viên gỡ bỏ khỏi hệ thống. Nếu bạn có bất kỳ thắc mắc nào về quyết định này, vui lòng liên hệ trực tiếp với ban quản trị để được hỗ trợ và giải đáp.

@component('mail::button', ['url' => $url])
Vào hệ thống
@endcomponent

Trân trọng,<br>
**Đội ngũ IT-Learning**
@endcomponent
