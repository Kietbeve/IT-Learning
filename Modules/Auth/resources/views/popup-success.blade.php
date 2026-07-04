<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập thành công</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .container {
            text-align: center;
            padding: 3rem 2rem;
            animation: fadeIn 0.5s ease-in;
        }
        
        .icon {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            animation: scaleIn 0.6s ease-out;
        }
        
        .message {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }
        
        .submessage {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }
        
        .linked-info {
            font-size: 0.875rem;
            opacity: 0.8;
            margin-top: 1rem;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes scaleIn {
            from { transform: scale(0); }
            to { transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">✓</div>
        <div class="message">Đăng nhập thành công!</div>
        <div class="submessage">Cửa sổ sẽ tự động đóng...</div>
        
        @if($linkedCount > 0)
            <div class="linked-info">
                Đã liên kết {{ $linkedCount }} bài thi với tài khoản của bạn
            </div>
        @endif
    </div>
    
    <script>
        // Gửi message về parent window (trang làm bài thi)
        if (window.opener && !window.opener.closed) {
            try {
                window.opener.postMessage({
                    type: 'google-login-success',
                    linkedCount: {{ $linkedCount ?? 0 }}
                }, '*');
                
                console.log('✓ Message sent to parent window');
            } catch (error) {
                console.error('Failed to send message:', error);
            }
            
            // Đóng popup sau 1 giây
            setTimeout(function() {
                window.close();
            }, 1000);
        } else {
            // Nếu không có opener hoặc opener đã đóng, redirect về trang chủ
            console.log('No parent window found, redirecting to home...');
            setTimeout(function() {
                window.location.href = '/';
            }, 2000);
        }
    </script>
</body>
</html>
