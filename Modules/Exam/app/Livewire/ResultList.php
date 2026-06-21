<?php

namespace Modules\Exam\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Modules\Exam\Models\ExamAttempt;

#[Layout('layouts.user')]
#[Title('Kết quả của tôi')]
class ResultList extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';

    public string $result = '';

    public string $sort = 'latest';

    protected $queryString = [
        'search' => ['except' => ''],
        'result' => ['except' => ''],
        'sort' => ['except' => 'latest'],
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedResult(): void
    {
        $this->resetPage();
    }

    public function updatedSort(): void
    {
        $this->resetPage();
    }

    public function getStatsProperty(): array
    {
        $query = ExamAttempt::query()
            ->where('user_id', auth()->id());
            // ->where('status', 'completed');

        return [
            'total' => (clone $query)->count(),

            'passed' => (clone $query)
                ->where('is_passed', true)
                ->count(),

            'failed' => (clone $query)
                ->where('is_passed', false)
                ->count(),

            'average_score' => round(
                (clone $query)->avg('percent_score') ?? 0,
                1
            ),
        ];
    }

    public function getAttemptsProperty()
    {
        return ExamAttempt::query()
            ->with([
                'exam',
            ])
            ->where('user_id', auth()->id())
            // ->where('status', 'completed')

            ->when(
                $this->search,
                fn ($query) => $query->whereHas(
                    'exam',
                    fn ($query) => $query->where(
                        'title',
                        'like',
                        "%{$this->search}%"
                    )
                )
            )

            ->when(
                $this->result === 'passed',
                fn ($query) => $query->where(
                    'is_passed',
                    true
                )
            )

            ->when(
                $this->result === 'failed',
                fn ($query) => $query->where(
                    'is_passed',
                    false
                )
            )

            ->when(
                $this->sort === 'latest',
                fn ($query) => $query->latest(
                    'submitted_at'
                )
            )

            ->when(
                $this->sort === 'oldest',
                fn ($query) => $query->oldest(
                    'submitted_at'
                )
            )

            ->when(
                $this->sort === 'score_desc',
                fn ($query) => $query->orderByDesc(
                    'percent_score'
                )
            )

            ->when(
                $this->sort === 'score_asc',
                fn ($query) => $query->orderBy(
                    'percent_score'
                )
            )

            ->paginate(12);
    }

    public function render()
    {
        return view(
            'exam::livewire.result-list',
            [
                'stats' => $this->stats,
                'attempts' => $this->attempts,
            ]
        );
    }
}