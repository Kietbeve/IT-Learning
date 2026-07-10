<?php 
namespace Modules\Exam\Livewire\Contributor;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class QuestionTable2 extends Component
{
    use WithPagination;
    public bool $isAdmin = false;
    
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

    #[Url(except: '')]
    public string $sortOrder = '';

    // Kiểm tra quyền của user
    public function mount()
    {
        $this->isAdmin = !auth()->user()->hasRole('contributor');
    }


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
    public function updatedSortOrder() { $this->resetPage(); }

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
        $this->sortOrder = '';
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
        $query = \Modules\Exam\Models\Question::query()
            ->when(!$this->isAdmin, fn ($q) => $q->where('author_id', auth()->id()))
            ->when($this->search, fn ($q) => $q->where('content', 'like', '%' . $this->search . '%'))
            ->when($this->filterType, fn ($q) => $q->where('type', $this->filterType))
            ->when($this->filterDifficulty, fn ($q) => $q->where('difficulty', $this->filterDifficulty))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterShared !== '', fn ($q) => $q->where('is_shared', $this->filterShared));

        if ($this->sortOrder === 'asc') {
            return $query->oldest()->paginate(10);
        }

        return $query->latest()->paginate(10);
    }

    /**
     * Render Component ra giao diện.
     */
    public function render()
    {
        // Gọi hàm lấy dữ liệu
        $questions = $this->getQuestionsData();

        return view('exam::contributor.question-table2', [
            'questions' => $questions,
            'isAdmin'   => $this->isAdmin
        ])
        ->layout(
            request()->routeIs('admin.*')
                ? 'layouts.admin'
                : 'layouts.contributor'
        );
    }
}
?>