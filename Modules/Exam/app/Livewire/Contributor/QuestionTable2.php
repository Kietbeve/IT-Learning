<?php 
namespace Modules\Exam\Livewire\Contributor;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class QuestionTable2 extends Component
{
    use WithPagination;

    // ─── THUỘC TÍNH BỘ LỌC TÌM KIẾM ────────────────────────────
    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $filterType = '';

    #[Url]
    public string $filterDifficulty = '';

    #[Url]
    public string $filterStatus = '';

    #[Url]
    public string $filterShared = '';

    // Biến kiểm soát hiển thị panel bộ lọc
    public bool $showFilters = false;

    // ─── THUỘC TÍNH CHỌN NHIỀU HÀNG (BULK SELECTION) ───────────
    public array $selectedRows = [];
    public bool $selectAll = false;
    public bool $showCheckboxes = false;

    // ─── THUỘC TÍNH ẨN/HIỆN CỘT (COLUMN VISIBILITY) ────────────
    public array $visibleColumns = [
        'id'         => true,
        'content'    => true,
        'type'       => true,
        'difficulty' => true,
        'status'     => true,
        'shared'     => true,
        'actions'    => true,
    ];

    /**
     * Bật/Tắt chế độ "Chọn nhiều". 
     * Khi tắt sẽ tự động bỏ chọn toàn bộ checkbox.
     */
    public function toggleCheckboxes()
    {
        $this->showCheckboxes = !$this->showCheckboxes;
        if (!$this->showCheckboxes) {
            $this->selectedRows = [];
            $this->selectAll = false;
        }
    }

    /**
     * Xử lý sự kiện khi người dùng click vào nút "Chọn tất cả" trên Header.
     */
    public function updatedSelectAll($value)
    {
        if ($value) {
            // Lấy ID của tất cả các câu hỏi đang được hiển thị trên trang hiện tại
            $this->selectedRows = collect($this->getQuestionsData()->items())
                                    ->pluck('id')
                                    ->map(fn($id) => (string) $id)
                                    ->toArray();
        } else {
            // Xóa mảng nếu bỏ chọn
            $this->selectedRows = [];
        }
    }

    /**
     * Xác nhận xóa nhiều bản ghi cùng lúc.
     * Kích hoạt sự kiện gửi danh sách ID tới Component Modal xác nhận.
     */
    public function confirmBulkDelete()
    {
        if (count($this->selectedRows) > 0) {
            $this->dispatch('question-bulk-delete-confirm', ids: $this->selectedRows)
                 ->to(\Modules\Exam\Livewire\Contributor\QuestionModal::class);
        }
    }

    // Reset về trang 1 khi người dùng thay đổi bất kỳ bộ lọc nào
    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilterType() { $this->resetPage(); }
    public function updatedFilterDifficulty() { $this->resetPage(); }
    public function updatedFilterStatus() { $this->resetPage(); }
    public function updatedFilterShared() { $this->resetPage(); }

    // ─── TÙY CHỌN BỘ LỌC (DROPDOWN OPTIONS) ────────────────────
    public array $typeOptions = [
        'single_choice'   => 'Trắc nghiệm một đáp án',
        'multiple_choice' => 'Trắc nghiệm nhiều đáp án',
        'essay'           => 'Tự luận',
    ];

    public array $difficultyOptions = [
        'easy'   => 'Dễ',
        'medium' => 'Trung bình',
        'hard'   => 'Khó',
    ];

    public array $statusOptions = [
        'pending'  => 'Chờ duyệt',
        'approved' => 'Đã duyệt',
        'rejected' => 'Từ chối',
    ];

    public array $sharedOptions = [
        1 => 'Đã chia sẻ',
        0 => 'Riêng tư',
    ];

    // ─── CÁC THAO TÁC XỬ LÝ GIAO DIỆN VÀ DATA ──────────────────

    /**
     * Xóa trắng mọi bộ lọc đang có và đưa về trang đầu tiên.
     */
    public function resetFilters(): void
    {
        $this->reset(['search', 'filterType', 'filterDifficulty', 'filterStatus', 'filterShared']);
        $this->resetPage();
    }

    /**
     * Đếm số lượng bộ lọc đang được kích hoạt.
     */
    public function getActiveFilterCountProperty(): int
    {
        return collect([
            $this->filterType,
            $this->filterDifficulty,
            $this->filterStatus,
            $this->filterShared,
        ])->filter(fn ($v) => $v !== '')->count();
    }

    /**
     * Ẩn/Hiện khu vực chọn bộ lọc tìm kiếm.
     */
    public function toggleFilters(): void
    {
        $this->showFilters = !$this->showFilters;
    }

    /**
     * Cập nhật trạng thái "Chia sẻ" của một câu hỏi.
     * Hàm này dành riêng cho Admin (hoặc người có quyền).
     */
    public function share($id)
    {
        $question = \Modules\Exam\Models\Question::findOrFail($id);

        $question->update([
            'is_shared' => !$question->is_shared,
        ]);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $question->is_shared ? 'Đã chia sẻ câu hỏi.' : 'Đã hủy chia sẻ câu hỏi.'
        ]);
    }

    /**
     * Lấy dữ liệu câu hỏi (Phân trang và xử lý bộ lọc).
     * Bao gồm cả logic DB thật (đang comment) và dữ liệu mô phỏng (Mock Data).
     */
    private function getQuestionsData()
    {
        $isAdmin = auth()->check() ? !auth()->user()->hasRole('contributor') : false;
        
        // --- TRUY VẤN DB THẬT (Comment lại để dùng dữ liệu giả) ---
        // $query = \Modules\Exam\Models\Question::query()
        //     ->when(!$isAdmin, fn ($q) => $q->where('author_id', auth()->id()))
        //     ->when($this->search, fn ($q) => $q->where('content', 'like', '%' . $this->search . '%'))
        //     ->when($this->filterType, fn ($q) => $q->where('type', $this->filterType))
        //     ->when($this->filterDifficulty, fn ($q) => $q->where('difficulty', $this->filterDifficulty))
        //     ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
        //     ->when($this->filterShared !== '', fn ($q) => $q->where('is_shared', $this->filterShared));
        //
        // return $query->latest()->paginate(10);
        // ----------------------------------------------------------

        // --- DỮ LIỆU MÔ PHỎNG (MOCK DATA) CHO MỤC ĐÍCH KIỂM THỬ ---
        $mockData = collect([
            (object)[ 'id' => 1, 'content' => '<p>Ngôn ngữ lập trình <strong>PHP</strong> ra đời năm nào?</p>', 'type' => 'single_choice', 'difficulty' => 'easy', 'status' => 'approved', 'is_shared' => true, ],
            (object)[ 'id' => 2, 'content' => 'Trình bày các ưu nhược điểm của <em>mô hình MVC</em> trong phát triển phần mềm và cho ví dụ minh họa cụ thể.', 'type' => 'essay', 'difficulty' => 'hard', 'status' => 'pending', 'is_shared' => false, ],
            (object)[ 'id' => 3, 'content' => 'Đâu là các framework của JavaScript? (Chọn nhiều đáp án)', 'type' => 'multiple_choice', 'difficulty' => 'medium', 'status' => 'rejected', 'is_shared' => false, ],
            (object)[ 'id' => 4, 'content' => 'Định nghĩa của <b>API</b> là gì? Phân biệt REST và GraphQL.', 'type' => 'essay', 'difficulty' => 'medium', 'status' => 'approved', 'is_shared' => true, ],
            (object)[ 'id' => 5, 'content' => 'Câu lệnh SQL nào dùng để <i>lấy dữ liệu</i> từ database?', 'type' => 'single_choice', 'difficulty' => 'easy', 'status' => 'approved', 'is_shared' => false, ],
            (object)[ 'id' => 6, 'content' => 'Khái niệm về Interface trong lập trình hướng đối tượng (OOP).', 'type' => 'essay', 'difficulty' => 'hard', 'status' => 'pending', 'is_shared' => true, ],
            (object)[ 'id' => 7, 'content' => 'Thuật toán sắp xếp nào có độ phức tạp trung bình là O(n log n)?', 'type' => 'multiple_choice', 'difficulty' => 'hard', 'status' => 'approved', 'is_shared' => true, ],
            (object)[ 'id' => 8, 'content' => 'Trong HTML, thẻ nào dùng để tạo một danh sách không có thứ tự?', 'type' => 'single_choice', 'difficulty' => 'easy', 'status' => 'rejected', 'is_shared' => false, ],
            (object)[ 'id' => 9, 'content' => 'Câu hỏi mẫu để kiểm tra trạng thái lạ và loại lạ hiển thị như thế nào.', 'type' => 'unknown_type', 'difficulty' => 'unknown_diff', 'status' => 'unknown_status', 'is_shared' => true, ],
            (object)[ 'id' => 10, 'content' => 'Hãy viết một hàm đệ quy để tính giai thừa của một số nguyên dương n bằng Python.', 'type' => 'essay', 'difficulty' => 'medium', 'status' => 'approved', 'is_shared' => false, ],
            (object)[ 'id' => 11, 'content' => 'CSS Flexbox dùng thuộc tính nào để căn giữa các phần tử theo trục chính?', 'type' => 'single_choice', 'difficulty' => 'easy', 'status' => 'pending', 'is_shared' => true, ],
            (object)[ 'id' => 12, 'content' => 'Docker Compose là công cụ để làm gì? Nêu cấu trúc cơ bản của docker-compose.yml.', 'type' => 'essay', 'difficulty' => 'hard', 'status' => 'approved', 'is_shared' => true, ],
        ]);

        // Xử lý bộ lọc trên Dữ liệu mô phỏng
        $filteredData = $mockData
            ->when($this->search, fn ($q) => $q->filter(fn($item) => stripos(strip_tags($item->content), $this->search) !== false))
            ->when($this->filterType, fn ($q) => $q->filter(fn($item) => $item->type === $this->filterType))
            ->when($this->filterDifficulty, fn ($q) => $q->filter(fn($item) => $item->difficulty === $this->filterDifficulty))
            ->when($this->filterStatus, fn ($q) => $q->filter(fn($item) => $item->status === $this->filterStatus))
            ->when($this->filterShared !== '', fn ($q) => $q->filter(fn($item) => $item->is_shared == $this->filterShared));

        // Phân trang nội bộ
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = $filteredData->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $filteredData->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
    }

    /**
     * Render Component ra giao diện.
     */
    public function render()
    {
        $isAdmin = auth()->check() ? !auth()->user()->hasRole('contributor') : false;
        
        // Gọi hàm lấy dữ liệu
        $questions = $this->getQuestionsData();

        return view('exam::contributor.question-table2', [
            'questions' => $questions,
            'isAdmin'   => $isAdmin
        ])
        ->layout(
            request()->routeIs('admin.*')
                ? 'layouts.admin'
                : 'layouts.contributor'
        );
    }
}
?>