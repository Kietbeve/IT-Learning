<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Thông báo đơn đăng ký CTV</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7fa;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f4f7fa; padding: 40px 0;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, {{ $isApproved ? '#10b981' : '#ef4444' }} 0%, {{ $isApproved ? '#059669' : '#dc2626' }} 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: -0.5px;">
                                {{ $isApproved ? '🎉 Chúc Mừng!' : '📋 Thông Báo' }}
                            </h1>
                            <p style="margin: 10px 0 0 0; color: rgba(255,255,255,0.95); font-size: 16px; font-weight: 400;">
                                IT-Learning Platform
                            </p>
                        </td>
                    </tr>

                    <!-- Status Badge -->
                    <tr>
                        <td style="padding: 0; text-align: center; transform: translateY(-20px);">
                            <div style="display: inline-block; background-color: #ffffff; padding: 8px 24px; border-radius: 50px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                                @if($isApproved)
                                    <span style="display: inline-flex; align-items: center; color: #10b981; font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                                        <span style="display: inline-block; width: 8px; height: 8px; background-color: #10b981; border-radius: 50%; margin-right: 8px;"></span>
                                        Đã Duyệt
                                    </span>
                                @else
                                    <span style="display: inline-flex; align-items: center; color: #ef4444; font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                                        <span style="display: inline-block; width: 8px; height: 8px; background-color: #ef4444; border-radius: 50%; margin-right: 8px;"></span>
                                        Từ Chối
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 0 40px 40px 40px;">
                            
                            <!-- Greeting -->
                            <p style="margin: 0 0 20px 0; color: #1f2937; font-size: 16px; line-height: 1.6;">
                                Xin chào <strong style="color: #111827;">{{ $userName }}</strong>,
                            </p>

                            @if($isApproved)
                                <!-- Approved Content -->
                                <p style="margin: 0 0 20px 0; color: #374151; font-size: 15px; line-height: 1.7;">
                                    Chúng tôi vui mừng thông báo rằng đơn đăng ký trở thành <strong style="color: #10b981;">Cộng Tác Viên</strong> của bạn đã được <strong>chấp thuận</strong>!
                                </p>

                                <!-- Benefits Box -->
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border-left: 4px solid #10b981; border-radius: 8px; padding: 20px;">
                                    <tr>
                                        <td>
                                            <p style="margin: 0 0 12px 0; color: #065f46; font-size: 15px; font-weight: 700;">
                                                🎯 Quyền lợi của bạn:
                                            </p>
                                            <ul style="margin: 0; padding-left: 20px; color: #047857; font-size: 14px; line-height: 1.8;">
                                                <li style="margin-bottom: 8px;">Tạo và đóng góp nội dung học tập</li>
                                                <li style="margin-bottom: 8px;">Nhận hoa hồng từ nội dung của bạn</li>
                                                <li style="margin-bottom: 8px;">Truy cập công cụ quản lý nội dung</li>
                                                <li style="margin-bottom: 0;">Tham gia cộng đồng CTV độc quyền</li>
                                            </ul>
                                        </td>
                                    </tr>
                                </table>

                                <p style="margin: 20px 0; color: #374151; font-size: 15px; line-height: 1.7;">
                                    Bạn có thể bắt đầu đóng góp nội dung ngay bây giờ bằng cách đăng nhập vào tài khoản và truy cập phần dành cho Cộng Tác Viên.
                                </p>

                                <!-- CTA Button -->
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 30px 0;">
                                    <tr>
                                        <td align="center">
                                            <a href="{{ config('app.url') }}" target="_blank" style="display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; text-decoration: none; font-size: 15px; font-weight: 600; border-radius: 8px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); transition: all 0.3s;">
                                                Đăng nhập ngay →
                                            </a>
                                        </td>
                                    </tr>
                                </table>

                                <p style="margin: 20px 0 0 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                    Nếu bạn có bất kỳ câu hỏi nào, đừng ngần ngại liên hệ với chúng tôi qua email hoặc trang hỗ trợ.
                                </p>

                            @else
                                <!-- Rejected Content -->
                                <p style="margin: 0 0 20px 0; color: #374151; font-size: 15px; line-height: 1.7;">
                                    Cảm ơn bạn đã quan tâm và gửi đơn đăng ký trở thành Cộng Tác Viên của IT-Learning Platform.
                                </p>

                                <p style="margin: 0 0 20px 0; color: #374151; font-size: 15px; line-height: 1.7;">
                                    Sau khi xem xét kỹ lưỡng, chúng tôi rất tiếc phải thông báo rằng đơn đăng ký của bạn <strong style="color: #ef4444;">chưa được chấp thuận</strong> vào thời điểm này.
                                </p>

                                @if($reason)
                                    <!-- Rejection Reason Box -->
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background: #fef2f2; border-left: 4px solid #ef4444; border-radius: 8px; padding: 20px;">
                                        <tr>
                                            <td>
                                                <p style="margin: 0 0 10px 0; color: #991b1b; font-size: 14px; font-weight: 700;">
                                                    📝 Lý do:
                                                </p>
                                                <p style="margin: 0; color: #7f1d1d; font-size: 14px; line-height: 1.7; white-space: pre-wrap;">{{ $reason }}</p>
                                            </td>
                                        </tr>
                                    </table>
                                @endif

                                <p style="margin: 20px 0; color: #374151; font-size: 15px; line-height: 1.7;">
                                    Chúng tôi khuyến khích bạn tiếp tục phát triển kỹ năng và kinh nghiệm của mình. Bạn có thể nộp đơn đăng ký lại sau <strong>30 ngày</strong> kể từ hôm nay.
                                </p>

                                <!-- Tips Box -->
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0; background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 8px; padding: 20px;">
                                    <tr>
                                        <td>
                                            <p style="margin: 0 0 12px 0; color: #92400e; font-size: 15px; font-weight: 700;">
                                                💡 Gợi ý để cải thiện:
                                            </p>
                                            <ul style="margin: 0; padding-left: 20px; color: #78350f; font-size: 14px; line-height: 1.8;">
                                                <li style="margin-bottom: 8px;">Bổ sung kinh nghiệm và portfolio</li>
                                                <li style="margin-bottom: 8px;">Tham gia nhiều hơn vào cộng đồng</li>
                                                <li style="margin-bottom: 8px;">Cập nhật CV và thông tin cá nhân</li>
                                                <li style="margin-bottom: 0;">Nâng cao kỹ năng chuyên môn</li>
                                            </ul>
                                        </td>
                                    </tr>
                                </table>

                                <p style="margin: 20px 0 0 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                    Nếu bạn có thắc mắc hoặc cần hỗ trợ thêm, vui lòng liên hệ với đội ngũ hỗ trợ của chúng tôi.
                                </p>
                            @endif

                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="height: 1px; background: linear-gradient(to right, transparent, #e5e7eb, transparent);"></div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 30px 40px; text-align: center; background-color: #f9fafb;">
                            <p style="margin: 0 0 12px 0; color: #111827; font-size: 16px; font-weight: 600;">
                                IT-Learning Platform
                            </p>
                            <p style="margin: 0 0 16px 0; color: #6b7280; font-size: 13px; line-height: 1.6;">
                                Nền tảng học tập công nghệ hàng đầu Việt Nam
                            </p>
                            
                            <!-- Social Links -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 16px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="#" target="_blank" style="display: inline-block; margin: 0 8px; width: 32px; height: 32px; background-color: #e5e7eb; border-radius: 50%; text-align: center; line-height: 32px; text-decoration: none; color: #6b7280; font-size: 14px;">📧</a>
                                        <a href="#" target="_blank" style="display: inline-block; margin: 0 8px; width: 32px; height: 32px; background-color: #e5e7eb; border-radius: 50%; text-align: center; line-height: 32px; text-decoration: none; color: #6b7280; font-size: 14px;">🌐</a>
                                        <a href="#" target="_blank" style="display: inline-block; margin: 0 8px; width: 32px; height: 32px; background-color: #e5e7eb; border-radius: 50%; text-align: center; line-height: 32px; text-decoration: none; color: #6b7280; font-size: 14px;">📱</a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 16px 0 0 0; color: #9ca3af; font-size: 12px; line-height: 1.6;">
                                © {{ date('Y') }} IT-Learning Platform. All rights reserved.<br>
                                Email này được gửi tự động, vui lòng không trả lời.
                            </p>
                        </td>
                    </tr>

                </table>
                <!-- End Main Container -->
            </td>
        </tr>
    </table>
</body>
</html>
