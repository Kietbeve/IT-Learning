<?php

namespace Modules\Exam\Livewire\Contributor;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Modules\Exam\Models\Question;
use App\Models\Category;
use WireUi\Traits\WireUiActions;

class ExamQuestionModal extends Component
{
    use WireUiActions;

    // Modal state
    public bool $showAddQuestionModal = false;

    // Exam info
    public int $examId;

    // Selected questions
    public array $selectedQuestionIds = [];
    public array $existingQuestionIds = []; // Questions already in exam
    
    // Score tracking
    public array $questionScores = []; // Scores for newly selected questions
    public array $existingQuestionScores = []; // Scores for existing questions

    // Filters
    public string $searchTerm = '';
    public ?string $filterDifficulty = null;
    public ?string $filterType = null;
    public ?int $filterCategoryId = null;

    // Pagination
    public int $currentPage = 1;
    public int $perPage = 10;

    // Categories for filter dropdown
    public array $categories = [];

    // Modal states for remove
    public bool $showRemoveModal = false;
    public bool $showBulkRemoveModal = false;

    // Data for confirmation
    public ?array $questionToRemove = null; // ['id', 'content', 'sort_order']
    public array $questionIdsToRemove = [];

    public function mount(): void
    {
        // Load categories cho dropdown filter
        $this->categories = Category::query()
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn($category) => [
                'id' => $category->id,
                'name' => $category->name,
            ])
            ->toArray();
    }

    #[On('open-add-question-modal')]
    public function openAddQuestionModal(int $examId): void
    {
        $this->examId = $examId;
        $this->showAddQuestionModal = true;
        
        // Load existing questions with scores from exam
        $existingData = DB::table('exam_questions')
            ->where('exam_id', $examId)
            ->get(['question_id', 'score']);
        
        $this->existingQuestionIds = $existingData->pluck('question_id')->toArray();
        $this->existingQuestionScores = $existingData->pluck('score', 'question_id')->toArray();
        
        // Reset state khi mở modal
        $this->reset([
            'selectedQuestionIds',
            'questionScores',
            'searchTerm',
            'filterDifficulty',
            'filterType',
            'filterCategoryId',
            'currentPage',
        ]);
    }

    #[Computed]
    public function availableQuestions()
    {
        // Exclude: questions in exam + newly selected questions
        $excludeIds = array_merge($this->existingQuestionIds, $this->selectedQuestionIds);
        
        // Query các câu hỏi available (chưa có trong đề và chưa được chọn)
        $query = Question::query()
            ->with(['category', 'options'])
            ->when(!empty($excludeIds), fn($q) => $q->whereNotIn('id', $excludeIds));
            // ->where('status', 'approved'); // Chỉ lấy câu hỏi đã duyệt

        // Apply search filter
        if (!empty($this->searchTerm)) {
            $query->where('content', 'like', '%' . $this->searchTerm . '%');
        }

        // Apply difficulty filter
        if (!empty($this->filterDifficulty)) {
            $query->where('difficulty', $this->filterDifficulty);
        }

        // Apply type filter
        if (!empty($this->filterType)) {
            $query->where('type', $this->filterType);
        }

        // Apply category filter
        if (!empty($this->filterCategoryId)) {
            $query->where('category_id', $this->filterCategoryId);
        }

        // Get paginated results
        $perPage = $this->perPage;
        $offset = ($this->currentPage - 1) * $perPage;

        $total = $query->count();
        $questions = $query->orderBy('id', 'desc')
            ->skip($offset)
            ->take($perPage)
            ->get();

        return [
            'data' => $questions,
            'total' => $total,
            'totalPages' => ceil($total / $perPage),
            'currentPage' => $this->currentPage,
        ];
    }

    #[Computed]
    public function existingQuestions()
    {
        // Load câu hỏi đã có trong đề thi, sắp xếp theo sort_order
        if (empty($this->existingQuestionIds)) {
            return collect([]);
        }

        return Question::query()
            ->join('exam_questions', 'questions.id', '=', 'exam_questions.question_id')
            ->where('exam_questions.exam_id', $this->examId)
            ->with(['category', 'options'])
            ->orderBy('exam_questions.sort_order', 'asc')
            ->select('questions.*')
            ->get();
    }

    #[Computed]
    public function selectedQuestions()
    {
        if (empty($this->selectedQuestionIds)) {
            return collect([]);
        }

        // Load chi tiết các câu hỏi đã chọn để preview
        return Question::query()
            ->with(['category', 'options'])
            ->whereIn('id', $this->selectedQuestionIds)
            ->get();
    }

    #[Computed]
    public function totalScore(): float
    {
        $existingTotal = array_sum(array_map('floatval', $this->existingQuestionScores));
        $newTotal = array_sum(array_map('floatval', $this->questionScores));
        
        return round($existingTotal + $newTotal, 2);
    }

    public function addQuestion(int $questionId): void
    {
        // Thêm câu hỏi vào danh sách selected (one-way add)
        if (!in_array($questionId, $this->selectedQuestionIds)) {
            $this->selectedQuestionIds[] = $questionId;
            $this->questionScores[$questionId] = 1.0; // Default score
        }
    }

    public function removeNewQuestion(int $questionId): void
    {
        // Xóa câu hỏi khỏi danh sách newly selected
        $index = array_search($questionId, $this->selectedQuestionIds);
        
        if ($index !== false) {
            unset($this->selectedQuestionIds[$index]);
            unset($this->questionScores[$questionId]); // Remove score
            $this->selectedQuestionIds = array_values($this->selectedQuestionIds);
        }
    }

    public function removeExistingQuestion(int $questionId): void
    {
        // Xóa trực tiếp từ DB không cần confirm
        DB::table('exam_questions')
            ->where('exam_id', $this->examId)
            ->where('question_id', $questionId)
            ->delete();
        
        // Tính lại sort_order
        $this->recalculateSortOrder();
        
        // Cập nhật state arrays
        $this->existingQuestionIds = array_values(
            array_diff($this->existingQuestionIds, [$questionId])
        );
        unset($this->existingQuestionScores[$questionId]);
        
        $this->notification()->success('Đã xóa câu hỏi khỏi đề thi');
    }

    protected function validateAllScores(): bool
    {
        // Validate new question scores
        foreach ($this->questionScores as $questionId => $score) {
            $score = (float) $score;
            if ($score < 0.5 || $score > 50) {
                $this->notification()->error(
                    title: 'Điểm không hợp lệ',
                    description: "Câu hỏi có điểm không hợp lệ. Điểm phải từ 0.5 đến 50."
                );
                return false;
            }
        }
        
        // Validate existing question scores
        foreach ($this->existingQuestionScores as $questionId => $score) {
            $score = (float) $score;
            if ($score < 0.5 || $score > 50) {
                $this->notification()->error(
                    title: 'Điểm không hợp lệ',
                    description: "Câu hỏi có điểm không hợp lệ. Điểm phải từ 0.5 đến 50."
                );
                return false;
            }
        }
        
        return true;
    }

    public function saveQuestionsToExam(): void
    {
        // Allow saving if there are existing questions to update OR new questions to add
        if (empty($this->selectedQuestionIds) && empty($this->existingQuestionIds)) {
            $this->notification()->error(
                title: 'Lỗi!',
                description: 'Không có câu hỏi nào để lưu.'
            );
            return;
        }

        // Validate all scores
        if (!$this->validateAllScores()) {
            return;
        }

        try {
            DB::transaction(function () {
                // Update existing question scores (if changed)
                foreach ($this->existingQuestionScores as $questionId => $score) {
                    DB::table('exam_questions')
                        ->where('exam_id', $this->examId)
                        ->where('question_id', $questionId)
                        ->update([
                            'score' => (float) $score,
                            'updated_at' => now(),
                        ]);
                }

                // Insert new questions with scores
                $maxOrder = DB::table('exam_questions')
                    ->where('exam_id', $this->examId)
                    ->max('sort_order') ?? 0;

                foreach ($this->selectedQuestionIds as $index => $questionId) {
                    DB::table('exam_questions')->insert([
                        'exam_id' => $this->examId,
                        'question_id' => $questionId,
                        'score' => (float) ($this->questionScores[$questionId] ?? 1.0),
                        'sort_order' => $maxOrder + $index + 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });

            $count = count($this->selectedQuestionIds);
            $this->notification()->success(
                title: 'Thành công!',
                description: "Đã thêm {$count} câu hỏi và cập nhật điểm số."
            );

            // Đóng modal
            $this->showAddQuestionModal = false;

            // Reset state
            $this->reset(['selectedQuestionIds', 'questionScores', 'existingQuestionScores']);

            // Dispatch event để table refresh
            $this->dispatch('questions-added-to-exam');
            
        } catch (\Exception $e) {
            $this->notification()->error(
                title: 'Lỗi!',
                description: 'Có lỗi xảy ra khi thêm câu hỏi: ' . $e->getMessage()
            );
        }
    }

    // Listen: Remove single question
    #[On('remove-question')]
    public function confirmRemove(int $id): void
    {
        // Load question data from pivot
        $pivotData = DB::table('exam_questions')
            ->join('questions', 'exam_questions.question_id', '=', 'questions.id')
            ->where('exam_questions.exam_id', $this->examId)
            ->where('exam_questions.question_id', $id)
            ->select('questions.id', 'questions.content', 'exam_questions.sort_order')
            ->first();
        
        $this->questionToRemove = [
            'id' => $pivotData->id,
            'content' => $pivotData->content,
            'sort_order' => $pivotData->sort_order,
        ];
        
        $this->showRemoveModal = true;
    }

    // Execute: Remove single
    public function removeQuestion(): void
    {
        DB::table('exam_questions')
            ->where('exam_id', $this->examId)
            ->where('question_id', $this->questionToRemove['id'])
            ->delete();
        
        // Recalculate sort_order
        $this->recalculateSortOrder();
        
        $this->showRemoveModal = false;
        $this->notification()->success('Đã gỡ câu hỏi khỏi đề thi');
        $this->dispatch('questions-added-to-exam');
    }

    // Listen: Bulk remove
    #[On('bulk-remove-questions')]
    public function confirmBulkRemove(array $ids): void
    {
        $this->questionIdsToRemove = $ids;
        $this->showBulkRemoveModal = true;
    }

    // Execute: Bulk remove
    public function bulkRemoveQuestions(): void
    {
        DB::table('exam_questions')
            ->where('exam_id', $this->examId)
            ->whereIn('question_id', $this->questionIdsToRemove)
            ->delete();
        
        $this->recalculateSortOrder();
        
        $count = count($this->questionIdsToRemove);
        $this->showBulkRemoveModal = false;
        $this->notification()->success("Đã gỡ {$count} câu hỏi khỏi đề thi");
        $this->dispatch('questions-added-to-exam');
    }

    // Helper: Renumber sort_order (SIMPLEST APPROACH)
    private function recalculateSortOrder(): void
    {
        $questions = DB::table('exam_questions')
            ->where('exam_id', $this->examId)
            ->orderBy('sort_order')
            ->select('id')
            ->get();
        
        foreach ($questions as $index => $question) {
            DB::table('exam_questions')
                ->where('id', $question->id)
                ->update(['sort_order' => $index + 1]);
        }
    }

    public function closeModal(): void
    {
        $this->showAddQuestionModal = false;
        $this->reset([
            'selectedQuestionIds', 
            'existingQuestionIds', 
            'questionScores',
            'existingQuestionScores',
            'searchTerm', 
            'currentPage'
        ]);
    }

    public function resetFilters(): void
    {
        $this->reset(['searchTerm', 'filterDifficulty', 'filterType', 'filterCategoryId']);
        $this->currentPage = 1;
    }

    public function nextPage(): void
    {
        $availableQuestions = $this->availableQuestions();
        
        if ($this->currentPage < $availableQuestions['totalPages']) {
            $this->currentPage++;
        }
    }

    public function previousPage(): void
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }

    public function goToPage(int $page): void
    {
        $availableQuestions = $this->availableQuestions();
        
        if ($page >= 1 && $page <= $availableQuestions['totalPages']) {
            $this->currentPage = $page;
        }
    }

    public function render()
    {
        return view('exam::contributor.livewire.exam-question-modal');
    }
}
