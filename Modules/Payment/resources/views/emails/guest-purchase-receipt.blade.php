<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2563eb; color: #fff; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { padding: 20px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 8px 8px; }
        .btn { display: inline-block; padding: 10px 20px; background: #2563eb; color: #ffffff !important; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 15px; }
        .footer { margin-top: 20px; text-align: center; font-size: 12px; color: #6b7280; }
        .highlight { font-weight: bold; color: #2563eb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Cảm ơn bạn đã mua tài liệu!</h2>
        </div>
        <div class="content">
            <p>Chào bạn,</p>
            <p>Đơn hàng <strong>#{{ $order->order_code }}</strong> của bạn đã được thanh toán thành công.</p>
            
            <p>Bạn đã mua tài liệu: <strong>{{ $order->items->first()->document_title_snapshot }}</strong></p>
            
            <p>Với tư cách là khách vãng lai, bạn có <strong>5 lượt tải</strong> cho tài liệu này trên thiết bị hiện tại.</p>
            <p>Số lượt tải còn lại: <span class="highlight">{{ $order->guest_download_limit - $order->guest_download_count }}</span>/{{ $order->guest_download_limit }}</p>
            
            <div style="background: #fef2f2; border-left: 4px solid #ef4444; padding: 15px; margin: 20px 0;">
                <strong>⚠️ Lưu ý quan trọng:</strong>
                <p style="margin-bottom: 0;">Số lượt tải có giới hạn đối với khách. Để bảo vệ tài sản và tải lại <strong>không giới hạn</strong> sau này, chúng tôi khuyên bạn nên <strong>đăng ký/đăng nhập</strong> bằng địa chỉ email này. Tài liệu sẽ được tự động liên kết với tài khoản của bạn!</p>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ route('login') }}" class="btn">Đăng nhập / Đăng ký ngay</a>
            </div>
            
            <p style="margin-top: 20px;">Hoặc quay lại xem tài liệu:</p>
            <p><a href="{{ config('app.url') }}/documents/{{ $order->items->first()->document_id }}">{{ config('app.url') }}/documents/{{ $order->items->first()->document_id }}</a></p>
        </div>
        <div class="footer">
            Đây là email tự động từ IT-Learning. Vui lòng không trả lời email này.
        </div>
    </div>
</body>
</html>
