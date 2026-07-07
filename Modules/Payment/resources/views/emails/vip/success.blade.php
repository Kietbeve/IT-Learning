@component('mail::message')
# Xin chào {{ $user->name }},

Cảm ơn bạn đã sử dụng dịch vụ của **IT-Learning**. Chúng tôi xin thông báo giao dịch thanh toán của bạn đã được hệ thống ghi nhận thành công.

<div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin: 20px 0;">
<h3 style="margin-top: 0; color: #334155; font-size: 16px; margin-bottom: 16px; text-transform: uppercase;">Chi Tiết Giao Dịch</h3>
<div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 15px;">
<span style="color: #64748b;">Gói dịch vụ:</span>
<strong style="color: #0f172a;">{{ $package['name'] ?? 'VIP' }}</strong>
</div>
<div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 15px;">
<span style="color: #64748b;">Số tiền thanh toán:</span>
<strong style="color: #0f172a;">{{ number_format($order->total_amount) }}đ</strong>
</div>
<div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 15px;">
<span style="color: #64748b;">Mã đơn hàng:</span>
<strong style="color: #0f172a;">{{ $order->order_code }}</strong>
</div>
<hr style="border: 0; border-top: 1px solid #cbd5e1; margin: 16px 0;">
<p style="margin: 0; color: #059669; font-weight: bold; text-align: right;">
Trạng thái: THÀNH CÔNG
</p>
</div>

Gói dịch vụ của bạn đã được kích hoạt. Bạn có thể kiểm tra trạng thái tài khoản tại trang quản lý.

@component('mail::button', ['url' => route('user.subscription'), 'color' => 'primary'])
Truy cập tài khoản
@endcomponent


Trân trọng,<br>
**Đội ngũ IT-Learning**
@endcomponent
