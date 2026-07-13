@php
    $hasError = $row->questions_count === 0
        || is_null($row->publish_at)
        || $row->status === 'rejected';

    $isPending = $row->status === 'pending' && ! $hasError;

    $isApproved = $row->status === 'approved' && ! $hasError;

    $bgClass = match (true) {
        $hasError => 'bg-red-100',
        $isPending => 'bg-yellow-100',
        $isApproved => 'bg-green-100',
        default => 'bg-gray-50',
    };
@endphp

<div class="p-4 {{ $bgClass }} rounded">
    <ul class="list-disc ml-5 text-sm text-red-600 space-y-1">
        @if ($row->questions_count === 0)
            <li>Chưa có câu hỏi.</li>
        @endif

        @if (is_null($row->publish_at))
            <li>Chưa thiết lập ngày xuất bản.</li>
        @endif

        @if ($row->status === 'rejected')
            <li>Đề thi đã bị từ chối.</li>
        @endif

        @if ($isPending)
            <li class="text-yellow-600">Đang chờ duyệt.</li>
        @endif

        @if (
            $row->questions_count > 0 &&
            $row->publish_at &&
            $row->status === 'approved'
        )
            <li class="text-green-600">Bài kiểm tra đã sẵn sàng.</li>
        @endif
    </ul>
</div>