<?php

namespace Modules\Document\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;

class DocumentReport extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'pending'; // Show pending reports by default
    public $reasonFilter = 'all';
    
    // Mock reports stored in session or component state
    public $mockReports = [];

    // Properties for handling admin dismiss notes
    public $selectedReportId = null;
    public $reportNote = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'pending'],
        'reasonFilter' => ['except' => 'all'],
    ];

    public function mount()
    {
        // Populate rich mock reports if empty
        if (empty($this->mockReports)) {
            $this->mockReports = [
                [
                    'id' => 1,
                    'document_id' => 1,
                    'document_title' => 'Giáo trình Laravel 11 từ căn bản đến nâng cao',
                    'document_author' => 'Nguyễn Văn Cộng Tác Viên',
                    'user_name' => 'Trần Học Viên',
                    'user_email' => 'student@example.com',
                    'reason' => 'copyright',
                    'description' => 'Tài liệu này sao chép nguyên bản giáo trình của trường Đại học Công nghệ mà không có sự đồng ý của tác giả. Yêu cầu gỡ bỏ.',
                    'status' => 'pending',
                    'created_at' => now()->subHours(2)->format('Y-m-d H:i:s'),
                    'resolved_by' => null,
                    'resolved_at' => null,
                    'review_note' => null,
                ],
                [
                    'id' => 2,
                    'document_id' => 2,
                    'document_title' => 'Source code Website bán hàng PHP thuần cực đẹp',
                    'document_author' => 'Nguyễn Văn Cộng Tác Viên',
                    'user_name' => 'Lê Học Sinh',
                    'user_email' => 'student2@example.com',
                    'reason' => 'spam',
                    'description' => 'Link tải source code này bị lỗi và chứa nhiều liên kết quảng cáo độc hại, không đúng như mô tả.',
                    'status' => 'pending',
                    'created_at' => now()->subDays(1)->format('Y-m-d H:i:s'),
                    'resolved_by' => null,
                    'resolved_at' => null,
                    'review_note' => null,
                ],
                [
                    'id' => 3,
                    'document_id' => 2,
                    'document_title' => 'Source code Website bán hàng PHP thuần cực đẹp',
                    'document_author' => 'Nguyễn Văn Cộng Tác Viên',
                    'user_name' => 'Hoàng Minh',
                    'user_email' => 'student3@example.com',
                    'reason' => 'inappropriate',
                    'description' => 'Tài liệu chứa từ ngữ không phù hợp trong phần hướng dẫn cài đặt ở trang 4.',
                    'status' => 'resolved',
                    'created_at' => now()->subDays(3)->format('Y-m-d H:i:s'),
                    'resolved_by' => 'Admin IT-Learning',
                    'resolved_at' => now()->subDays(2)->format('Y-m-d H:i:s'),
                    'review_note' => 'Đã yêu cầu cộng tác viên chỉnh sửa lại nội dung trang 4.',
                ],
                [
                    'id' => 4,
                    'document_id' => 3,
                    'document_title' => 'Đồ án tốt nghiệp: Hệ thống quản lý thư viện trường học',
                    'document_author' => 'Trần Học Viên',
                    'user_name' => 'Phạm Văn Nam',
                    'user_email' => 'nam@example.com',
                    'reason' => 'other',
                    'description' => 'Tài liệu đăng tải bị trùng lặp với đồ án của tôi đã đăng tuần trước.',
                    'status' => 'dismissed',
                    'created_at' => now()->subDays(5)->format('Y-m-d H:i:s'),
                    'resolved_by' => 'Admin IT-Learning',
                    'resolved_at' => now()->subDays(4)->format('Y-m-d H:i:s'),
                    'review_note' => 'Đồ án tốt nghiệp của bạn Phạm Văn Nam đăng sau, nội dung không trùng lặp hoàn toàn mà có cải tiến công nghệ mới.',
                ]
            ];
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingReasonFilter()
    {
        $this->resetPage();
    }

    public function resolveReport($id)
    {
        $reports = $this->mockReports;
        foreach ($reports as &$report) {
            if ($report['id'] == $id) {
                $report['status'] = 'resolved';
                $report['resolved_by'] = 'Admin IT-Learning';
                $report['resolved_at'] = now()->format('Y-m-d H:i:s');
                $report['review_note'] = null; // Duyệt thì không cần ghi chú lý do
                break;
            }
        }
        $this->mockReports = $reports;
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã phê duyệt báo cáo và gỡ bỏ tài liệu vi phạm thành công.']);
    }

    public function openDismissModal($id)
    {
        $this->selectedReportId = $id;
        $this->reportNote = '';
        $this->resetValidation();
        $this->dispatch('open-modal', 'report-dismiss-modal');
    }

    public function confirmDismiss()
    {
        $this->validate([
            'reportNote' => 'required|string|min:5|max:500'
        ], [
            'reportNote.required' => 'Vui lòng nhập lý do/ghi chú bác bỏ.',
            'reportNote.min' => 'Ghi chú phải có tối thiểu 5 ký tự.',
            'reportNote.max' => 'Ghi chú không được vượt quá 500 ký tự.',
        ]);

        $reports = $this->mockReports;
        foreach ($reports as &$report) {
            if ($report['id'] == $this->selectedReportId) {
                $report['status'] = 'dismissed';
                $report['resolved_by'] = 'Admin IT-Learning';
                $report['resolved_at'] = now()->format('Y-m-d H:i:s');
                $report['review_note'] = $this->reportNote;
                break;
            }
        }
        $this->mockReports = $reports;

        $this->dispatch('close-modal', 'report-dismiss-modal');
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã bác bỏ báo cáo vi phạm thành công.']);

        $this->selectedReportId = null;
        $this->reportNote = '';
    }

    public function getReasonLabel($reason)
    {
        $reasons = [
            'copyright' => 'Bản quyền / Trùng lặp',
            'spam' => 'Spam / Lừa đảo',
            'inappropriate' => 'Nội dung không phù hợp',
            'other' => 'Khác',
        ];

        return $reasons[$reason] ?? 'Khác';
    }

    public function render()
    {
        // Count reports for stats cards from mock list
        $pendingCount = collect($this->mockReports)->where('status', 'pending')->count();
        $resolvedCount = collect($this->mockReports)->where('status', 'resolved')->count();
        $dismissedCount = collect($this->mockReports)->where('status', 'dismissed')->count();
        $totalCount = count($this->mockReports);

        // Filter mock reports
        $filtered = collect($this->mockReports);

        if ($this->statusFilter !== 'all') {
            $filtered = $filtered->where('status', $this->statusFilter);
        }

        if ($this->reasonFilter !== 'all') {
            $filtered = $filtered->where('reason', $this->reasonFilter);
        }

        if (!empty($this->search)) {
            $searchLower = mb_strtolower($this->search, 'UTF-8');
            $filtered = $filtered->filter(function($item) use ($searchLower) {
                return str_contains(mb_strtolower($item['document_title'], 'UTF-8'), $searchLower)
                    || str_contains(mb_strtolower($item['description'], 'UTF-8'), $searchLower)
                    || str_contains(mb_strtolower($item['user_name'], 'UTF-8'), $searchLower);
            });
        }

        // Map to objects so blade file can use arrow syntax seamlessly
        $objectReports = $filtered->map(function($item) {
            return (object)[
                'id' => $item['id'],
                'document_id' => $item['document_id'],
                'document' => (object)[
                    'id' => $item['document_id'],
                    'title' => $item['document_title'],
                    'author' => (object)['name' => $item['document_author']],
                ],
                'user' => (object)[
                    'name' => $item['user_name'],
                    'email' => $item['user_email'],
                ],
                'reason' => $item['reason'],
                'description' => $item['description'],
                'status' => $item['status'],
                'resolver' => $item['resolved_by'] ? (object)['name' => $item['resolved_by']] : null,
                'resolved_at' => $item['resolved_at'] ? \Carbon\Carbon::parse($item['resolved_at']) : null,
                'created_at' => \Carbon\Carbon::parse($item['created_at']),
                'review_note' => $item['review_note'] ?? null,
            ];
        });

        // Paginate manually using Laravel collection helper
        $perPage = 8;
        $page = (int) request()->query('page', 1);
        if ($page < 1) $page = 1;
        $paginatedItems = $objectReports->slice(($page - 1) * $perPage, $perPage)->all();
        $reports = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems,
            $objectReports->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()]
        );

        return view('document::livewire.admin.document-report', [
            'reports' => $reports,
            'pendingCount' => $pendingCount,
            'resolvedCount' => $resolvedCount,
            'dismissedCount' => $dismissedCount,
            'totalCount' => $totalCount,
            'dbError' => false,
        ])->layout('layouts.admin', [
            'pageTitle' => 'Quản lý báo cáo vi phạm tài liệu',
            'breadcrumb' => new \Illuminate\Support\HtmlString('<span class="mx-2">/</span> Admin <span class="mx-2">/</span> Báo cáo vi phạm')
        ]);
    }
}
