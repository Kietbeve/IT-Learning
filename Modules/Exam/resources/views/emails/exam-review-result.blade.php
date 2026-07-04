<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
</head>

<body style="font-family: Arial, Helvetica, sans-serif; color:#333; line-height:1.6">

    <h2>
        @if($exam->status === 'approved')
            🟢 Đề thi đã được duyệt
        @else
            🔴 Đề thi chưa được duyệt
        @endif
    </h2>

    <p>
        Xin chào <strong>{{ $exam->author->name }}</strong>,
    </p>

    <p>
        Đề thi
        <strong>{{ $exam->title }}</strong>

        @if($exam->status === 'approved')
            đã được quản trị viên duyệt.
        @else
            chưa được duyệt.
        @endif
    </p>

    @if($exam->status === 'approved')

        <p style="color:#16a34a">
            Chúc mừng! Đề thi của bạn đã được duyệt và sẵn sàng sử dụng.
        </p>

    @else

        <h3>Lý do từ chối</h3>

        <div
            style="background:#fff7ed;padding:12px;border-left:4px solid #f97316;border-radius:4px;margin-bottom:20px;">

            {{ $exam->rejected_reason }}

        </div>

        <p>
            Vui lòng chỉnh sửa đề thi và gửi lại để được xét duyệt.
        </p>

    @endif

    <p>

        <a href="{{ route('contributor.exams.detail', $exam->id) }}"
            style="
            background:#2563eb;
            color:white;
            padding:10px 18px;
            text-decoration:none;
            border-radius:6px;
            display:inline-block;
            font-weight:bold;
        ">

            @if($exam->status === 'approved')
                📄 Xem đề thi
            @else
                ✏️ Chỉnh sửa đề thi
            @endif

        </a>

    </p>

    <hr style="margin:30px 0;">

    <p style="font-size:13px;color:#666;">
        Email này được gửi tự động từ hệ thống <strong>{{ config('app.name') }}</strong>.
        <br>
        Vui lòng không trả lời email này.
    </p>

</body>

</html>