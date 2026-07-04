<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>

<h2>Kết quả bài thi</h2>

<p>Xin chào <strong>{{ $attempt->user->name }}</strong>,</p>

<p>Bài thi <strong>{{ $attempt->exam->title }}</strong> đã được chấm.</p>

@if($stats['percent_score'] >= $attempt->exam->pass_percent)
    <p style="color: #16a34a; font-weight: bold; font-size: 16px;">
        🟢 KẾT QUẢ: ĐẠT
    </p>
@else
    <p style="color: #dc2626; font-weight: bold; font-size: 16px;">
        🔴 KẾT QUẢ: CHƯA ĐẠT
    </p>
@endif

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Nội dung</th>
        <th>Kết quả</th>
    </tr>
    <tr>
        <td>Điểm</td>
        <td>{{ $stats['score'] }}/{{ $stats['max_score'] }}</td>
    </tr>
    <tr>
        <td>Tỷ lệ</td>
        <td>{{ $stats['percent_score'] }}%</td>
    </tr>
    <tr>
        <td>Đúng</td>
        <td>{{ $stats['correct_answers'] }}</td>
    </tr>
    <tr>
        <td>Sai</td>
        <td>{{ $stats['wrong_answers'] }}</td>
    </tr>
    <tr>
        <td>Bỏ qua</td>
        <td>{{ $stats['skipped_answers'] }}</td>
    </tr>
</table>

<p>
    <a href="{{ route('exam.attempt.result', $attempt->session_id) }}"
      style="
        background:#2563eb;
        color:#ffffff;
        padding:10px 18px;
        text-decoration:none;
        border-radius:6px;
        display:inline-block;
        font-weight:bold;
        margin-top: 12px;
        " 
    >
        ➡️Xem chi tiết kết quả
    </a>
</p>

<hr style="margin:30px 0;">

<p style="font-size:13px;color:#6b7280;">
    Email này được gửi tự động từ hệ thống <strong>IT Learning</strong>.<br>
    Vui lòng không trả lời email này.
</p>

</body>
</html>