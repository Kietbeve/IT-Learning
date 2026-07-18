<?php

namespace Modules\Exam\Livewire\Contributor;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Modules\Exam\Models\Question;
use Modules\Exam\Models\Exam;
use App\Models\Category;
use WireUi\Traits\WireUiActions;

class ExamQuestionPageV2 extends Component
{
    use WireUiActions;

    // Exam info
    public int $examId;
    public ?Exam $exam = null;

    // Selected questions
    public array $selectedQuestionIds = [];
    public array $existingQuestionIds = []; // Questions already in exam
    
    // Score tracking
    public array $questionScores = []; // Scores for newly selected questions
    public array $existingQuestionScores = []; // Scores for existing questions
    public array $questionsToDelete = []; // Questions marked for deletion (only deleted on save) Ids

    // Filters
    public string $searchTerm = '';
    public array $filterDifficulty = [];
    public array $filterType = [];
    public array $filterCategoryId = [];

    // Pagination
    public int $currentPage = 1;
    public int $perPage = 10;

    // Categories for filter dropdown
    public array $categories = [];

    // Random selection feature - Chức năng chọn ngẫu nhiên câu hỏi
    public bool $showRandomBanner = true; // Hiển thị/ẩn banner random
    public string $randomMode = 'total'; // Chế độ random: 'total' hoặc 'by_difficulty'
    
    // Random theo tổng số lượng
    public int $randomTotalCount = 0; // Tổng số câu cần random
    
    // Random theo từng độ khó
    public int $randomEasyCount = 0; // Số câu dễ cần random
    public int $randomMediumCount = 0; // Số câu trung bình cần random
    public int $randomHardCount = 0; // Số câu khó cần random

    // Question source tabs - Tabs chọn nguồn câu hỏi (cá nhân / dùng chung)
    public string $questionSourceTab = 'personal'; // 'personal' (của tôi) hoặc 'shared' (dùng chung)

    public function mount(int $examId): void
    {
        $this->examId = $examId;
        
        // Load exam info for breadcrumb
        $this->exam = Exam::findOrFail($examId);
        
        // Load categories cho dropdown filter
        $this->categories = Category::query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
        
        // Load existing questions with scores from exam
        $this->loadExistingQuestionsData();
    }

    /**
     * Reload existing questions data from database
     * Used in mount() and after save to refresh state
     */
    private function loadExistingQuestionsData(): void
    {
        $existingData = DB::table('exam_questions')
            ->where('exam_id', $this->examId)
            ->get(['question_id', 'score']);
        
        $this->existingQuestionIds = $existingData->pluck('question_id')->toArray();
        $this->existingQuestionScores = $existingData->pluck('score', 'question_id')->toArray();
    }
    //tính tổng điểm những câu sẽ bị xóa khỏi đề thi this->totalScoreToDelete()
    #[Computed]
    public function totalScoreToDelete(): float
    {
        $totalScore = 0;

        foreach ($this->questionsToDelete as $questionId) {
            $totalScore += $this->existingQuestionScores[$questionId] ?? 0;
        }

        return $totalScore;
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

        //chỉ lấy đúng loại câu hỏi của đề
        if($this->exam->type=='essay'){
                $query->where('type', 'essay');
        }
        elseif($this->exam->type=='multiple_choice'){
                $query->where('type', '!=', 'essay');
        }

        // Filter theo tab nguồn câu hỏi (cá nhân / dùng chung)
        if ($this->questionSourceTab === 'personal') {
            // Tab "Câu hỏi cá nhân": chỉ lấy câu hỏi do chính user hiện tại tạo
            $query->where('author_id', auth()->id());
        } elseif ($this->questionSourceTab === 'shared') {
            // Tab "Câu hỏi dùng chung": lấy tất cả câu hỏi được đánh dấu là shared
            $query->where('is_shared', true);
        }

        // Apply search filter
        if (!empty($this->searchTerm)) {
            $query->where('content', 'like', '%' . $this->searchTerm . '%');
        }

        // Apply difficulty filter
        if (!empty($this->filterDifficulty)) {
            $query->whereIn('difficulty', $this->filterDifficulty);
        }

        // Apply type filter
        if (!empty($this->filterType)) {
            $query->whereIn('type', $this->filterType);
        }

        // Apply category filter
        if (!empty($this->filterCategoryId)) {
            $query->whereIn('category_id', $this->filterCategoryId);
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
    public function availableCountsByDifficulty(): array
    {
        // Đếm số câu hỏi available theo từng độ khó (dùng cho random selection)
        // Loại trừ: câu hỏi đã có trong đề + câu hỏi đã chọn
        $excludeIds = array_merge($this->existingQuestionIds, $this->selectedQuestionIds);
        
        $query = Question::query()
            ->when(!empty($excludeIds), fn($q) => $q->whereNotIn('id', $excludeIds));

        //loc cau hoi theo loai de
        if($this->exam->type=='essay'){
                $query->where('type', 'essay');
        }
        elseif($this->exam->type=='multiple_choice'){
                $query->where('type', '!=', 'essay');
        }
        
        // Filter theo tab nguồn câu hỏi (cá nhân / dùng chung)
        if ($this->questionSourceTab === 'personal') {
            // Tab "Câu hỏi cá nhân": chỉ đếm câu hỏi của chính user hiện tại
            $query->where('author_id', auth()->id());
        } elseif ($this->questionSourceTab === 'shared') {
            // Tab "Câu hỏi dùng chung": đếm tất cả câu hỏi được shared
            $query->where('is_shared', true);
        }
        
        // Áp dụng các filter hiện tại (search, type, category)
        if (!empty($this->searchTerm)) {
            $query->where('content', 'like', '%' . $this->searchTerm . '%');
        }
        
        if (!empty($this->filterType)) {
            $query->whereIn('type', $this->filterType);
        }
        
        if (!empty($this->filterCategoryId)) {
            $query->whereIn('category_id', $this->filterCategoryId);
        }
        
        // Đếm theo từng độ khó
        if (!empty($this->filterDifficulty)) {
            // Có filter độ khó -> chỉ đếm các độ khó đang được chọn
            $counts = [
                'easy' => in_array('easy', $this->filterDifficulty)
                    ? (clone $query)->where('difficulty', 'easy')->count()
                    : 0,

                'medium' => in_array('medium', $this->filterDifficulty)
                    ? (clone $query)->where('difficulty', 'medium')->count()
                    : 0,

                'hard' => in_array('hard', $this->filterDifficulty)
                    ? (clone $query)->where('difficulty', 'hard')->count()
                    : 0,
            ];
        } else {
            // Không filter độ khó -> đếm cả 3
            $counts = [
                'easy' => (clone $query)->where('difficulty', 'easy')->count(),
                'medium' => (clone $query)->where('difficulty', 'medium')->count(),
                'hard' => (clone $query)->where('difficulty', 'hard')->count(),
            ];
        }
        
        // Tổng số câu available
        $counts['total'] = $counts['easy'] + $counts['medium'] + $counts['hard'];
        
        return $counts;
    }

    #[Computed]
    public function totalScore(): float
    {
        $existingTotal = array_sum(array_map('floatval', $this->existingQuestionScores));
        $newTotal = array_sum(array_map('floatval', $this->questionScores));
        
        
        return round($existingTotal + $newTotal - $this->totalScoreToDelete, 2);
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
        // Xóa NGAY khỏi danh sách mới thêm (trả về available questions bên trái)
        $index = array_search($questionId, $this->selectedQuestionIds);
        
        if ($index !== false) {
            unset($this->selectedQuestionIds[$index]);
            unset($this->questionScores[$questionId]);
            $this->selectedQuestionIds = array_values($this->selectedQuestionIds);
        }
        
        $this->notification()->success('Đã xóa câu hỏi khỏi danh sách mới thêm.');
    }

    public function randomSelectQuestions(): void
    {
        // Lấy số lượng câu hỏi available
        $availableCounts = $this->availableCountsByDifficulty();
        
        // Xử lý theo chế độ random được chọn
        if ($this->randomMode === 'total') {
            // Chế độ 1: Random theo tổng số lượng (không phân biệt độ khó)
            $totalCount = (int) $this->randomTotalCount;
            
            // Validate: Số lượng phải > 0
            if ($totalCount <= 0) {
                $this->notification()->error(
                    title: 'Lỗi!',
                    description: 'Vui lòng nhập số lượng câu hỏi cần random.'
                );
                return;
            }
            
            // Validate: Không vượt quá số câu available
            if ($totalCount > $availableCounts['total']) {
                $this->notification()->error(
                    title: 'Lỗi!',
                    description: "Chỉ có {$availableCounts['total']} câu hỏi khả dụng. Vui lòng nhập số nhỏ hơn."
                );
                return;
            }
            
            // Query random questions (không phân biệt độ khó)
            $excludeIds = array_merge($this->existingQuestionIds, $this->selectedQuestionIds);
            
            $query = Question::query()
                ->whereNotIn('id', $excludeIds);
            
            //loc cau hoi theo loai de
            if($this->exam->type=='essay'){
                    $query->where('type', 'essay');
            }
            elseif($this->exam->type=='multiple_choice'){
                    $query->where('type', '!=', 'essay');
            }
            
            // Filter theo tab nguồn câu hỏi (QUAN TRỌNG: phải tôn trọng tab hiện tại)
            if ($this->questionSourceTab === 'personal') {
                // Chỉ random từ câu hỏi của chính user
                $query->where('author_id', auth()->id());
            } elseif ($this->questionSourceTab === 'shared') {
                // Chỉ random từ câu hỏi được shared
                $query->where('is_shared', true);
            }
            
            // Áp dụng các filter hiện tại
            if (!empty($this->searchTerm)) {
                $query->where('content', 'like', '%' . $this->searchTerm . '%');
            }
            if (!empty($this->filterType)) {
                $query->whereIn('type', $this->filterType);
            }
            if (!empty($this->filterCategoryId)) {
                $query->whereIn('category_id', $this->filterCategoryId);
            }
            
            // Random select
            $randomQuestionIds = $query->inRandomOrder()
                ->limit($totalCount)
                ->pluck('id')
                ->toArray();
            
            // Thêm vào danh sách selected
            foreach ($randomQuestionIds as $questionId) {
                $this->addQuestion($questionId);
            }
            
            // Thông báo thành công
            $this->notification()->success(
                title: 'Thành công!',
                description: "Đã thêm {$totalCount} câu hỏi ngẫu nhiên."
            );
            
            // Reset input
            $this->randomTotalCount = 0;
            
        } else {
            // Chế độ 2: Random theo từng độ khó (easy/medium/hard)
            $easyCount = (int) $this->randomEasyCount;
            $mediumCount = (int) $this->randomMediumCount;
            $hardCount = (int) $this->randomHardCount;
            
            // Validate: Ít nhất 1 độ khó phải > 0
            if ($easyCount <= 0 && $mediumCount <= 0 && $hardCount <= 0) {
                $this->notification()->error(
                    title: 'Lỗi!',
                    description: 'Vui lòng nhập số lượng câu hỏi cần random cho ít nhất 1 độ khó.'
                );
                return;
            }
            
            // Validate từng độ khó
            if ($easyCount > $availableCounts['easy']) {
                $this->notification()->error(
                    title: 'Lỗi!',
                    description: "Chỉ có {$availableCounts['easy']} câu dễ khả dụng."
                );
                return;
            }
            
            if ($mediumCount > $availableCounts['medium']) {
                $this->notification()->error(
                    title: 'Lỗi!',
                    description: "Chỉ có {$availableCounts['medium']} câu trung bình khả dụng."
                );
                return;
            }
            
            if ($hardCount > $availableCounts['hard']) {
                $this->notification()->error(
                    title: 'Lỗi!',
                    description: "Chỉ có {$availableCounts['hard']} câu khó khả dụng."
                );
                return;
            }
            
            // Query base (loại trừ câu đã có và đã chọn)
            $excludeIds = array_merge($this->existingQuestionIds, $this->selectedQuestionIds);
            $baseQuery = Question::query()->whereNotIn('id', $excludeIds);
            
            //loc cau hoi theo loai de
            if($this->exam->type=='essay'){
                    $baseQuery->where('type', 'essay');
            }
            elseif($this->exam->type=='multiple_choice'){
                    $baseQuery->where('type', '!=', 'essay');
            }
            
            // Filter theo tab nguồn câu hỏi (QUAN TRỌNG: phải tôn trọng tab hiện tại)
            if ($this->questionSourceTab === 'personal') {
                // Chỉ random từ câu hỏi của chính user
                $baseQuery->where('author_id', auth()->id());
            } elseif ($this->questionSourceTab === 'shared') {
                // Chỉ random từ câu hỏi được shared
                $baseQuery->where('is_shared', true);
            }
            
            // Áp dụng filters
            if (!empty($this->searchTerm)) {
                $baseQuery->where('content', 'like', '%' . $this->searchTerm . '%');
            }
            if (!empty($this->filterType)) {
                $baseQuery->whereIn('type', $this->filterType);
            }
            if (!empty($this->filterCategoryId)) {
                $baseQuery->whereIn('category_id', $this->filterCategoryId);
            }
            
            $totalAdded = 0;
            
            // Random select câu dễ
            if ($easyCount > 0) {
                $easyQuestions = (clone $baseQuery)
                    ->where('difficulty', 'easy')
                    ->inRandomOrder()
                    ->limit($easyCount)
                    ->pluck('id')
                    ->toArray();
                
                foreach ($easyQuestions as $questionId) {
                    $this->addQuestion($questionId);
                }
                $totalAdded += count($easyQuestions);
            }
            
            // Random select câu trung bình
            if ($mediumCount > 0) {
                $mediumQuestions = (clone $baseQuery)
                    ->where('difficulty', 'medium')
                    ->inRandomOrder()
                    ->limit($mediumCount)
                    ->pluck('id')
                    ->toArray();
                
                foreach ($mediumQuestions as $questionId) {
                    $this->addQuestion($questionId);
                }
                $totalAdded += count($mediumQuestions);
            }
            
            // Random select câu khó
            if ($hardCount > 0) {
                $hardQuestions = (clone $baseQuery)
                    ->where('difficulty', 'hard')
                    ->inRandomOrder()
                    ->limit($hardCount)
                    ->pluck('id')
                    ->toArray();
                
                foreach ($hardQuestions as $questionId) {
                    $this->addQuestion($questionId);
                }
                $totalAdded += count($hardQuestions);
            }
            
            // Thông báo thành công chi tiết
            $message = "Đã thêm {$totalAdded} câu hỏi ngẫu nhiên";
            $details = [];
            if ($easyCount > 0) $details[] = "{$easyCount} câu dễ";
            if ($mediumCount > 0) $details[] = "{$mediumCount} câu trung bình";
            if ($hardCount > 0) $details[] = "{$hardCount} câu khó";
            
            if (!empty($details)) {
                $message .= " (" . implode(', ', $details) . ")";
            }
            
            $this->notification()->success(
                title: 'Thành công!',
                description: $message
            );
            
            // Reset inputs
            $this->randomEasyCount = 0;
            $this->randomMediumCount = 0;
            $this->randomHardCount = 0;
        }
    }

    public function toggleRandomBanner(): void
    {
        // Toggle hiển thị/ẩn banner random
        $this->showRandomBanner = !$this->showRandomBanner;
    }

    public function removeExistingQuestion(int $questionId): void
    {
        // Đánh dấu câu hỏi để xóa khi lưu (xóa tạm thời - chưa xóa DB)
        if (!in_array($questionId, $this->questionsToDelete)) {
            $this->questionsToDelete[] = $questionId;
        }
        
        $this->notification()->success('Đã đánh dấu xóa. Click "Lưu" để áp dụng.');
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

    public function saveQuestionsToExam()
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
                // Xóa các câu hỏi đã đánh dấu để xóa (pending delete)
                if (!empty($this->questionsToDelete)) {
                    DB::table('exam_questions')
                        ->where('exam_id', $this->examId)
                        ->whereIn('question_id', $this->questionsToDelete)
                        ->delete();
                    
                    // Loại bỏ khỏi selectedQuestionIds nếu có
                    $this->selectedQuestionIds = array_values(
                        array_diff($this->selectedQuestionIds, $this->questionsToDelete)
                    );
                    
                    // Loại bỏ scores của câu đã xóa
                    foreach ($this->questionsToDelete as $qId) {
                        unset($this->questionScores[$qId]);
                        unset($this->existingQuestionScores[$qId]);
                    }
                }
                
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
                
                // Nếu đề đang là nháp thì chuyển sang chờ duyệt, tránh duyệt lại khi update
                Exam::whereKey($this->examId)
                    ->where('status', 'draft')
                    ->update([
                        'status' => 'pending',
                    ]);
                
                // Recalculate sort_order nếu có xóa câu hỏi
                if (!empty($this->questionsToDelete)) {
                    $this->recalculateSortOrder();
                }
            });

            $count = count($this->selectedQuestionIds);
            $this->notification()->success(
                title: 'Thành công!',
                description: "Đã thêm {$count} câu hỏi và cập nhật điểm số."
            );
            
            // Reset questionsToDelete sau khi lưu thành công
            $this->questionsToDelete = [];
            
            // Reset selected questions và scores
            $this->selectedQuestionIds = [];
            $this->questionScores = [];
            
            // Reload existing questions data from database (giống như reload trang)
            $this->loadExistingQuestionsData();
            
            // Reset về trang đầu tiên để hiển thị lại danh sách available questions
            $this->currentPage = 1;
            
        } catch (\Exception $e) {
            $this->notification()->error(
                title: 'Lỗi!',
                description: 'Có lỗi xảy ra khi thêm câu hỏi: ' . $e->getMessage()
            );
        }
    }

    // Helper: Renumber sort_order
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

    // public function redirectToDetail()
    // {
    //     return redirect()->route('contributor.exams.detail', ['examId' => $this->examId]);
    // }

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

    // Xóa tag filter cho cả 3 loại filter
    public function removeFilter(string $property, $value): void
    {
        if (!property_exists($this, $property)) {
            return;
        }

        $this->{$property} = array_values(
            array_filter(
                $this->{$property},
                fn ($item) => (string) $item !== (string) $value
            )
        );
    }

    public function render()
    {
        return view('exam::contributor.livewire.exam-question-page-v2')
            ->layout('layouts.contributor');
    }
}
