<?php

namespace Modules\Learning\Livewire\Student;

use Livewire\Component;
use Modules\Learning\Models\Roadmap;
use Modules\Learning\Services\SectionProgressService;
use Illuminate\Support\Facades\Auth;

class SectionProgressTracker extends Component
{
    public $roadmapId;
    public $roadmap;
    public $sectionsProgress = [];
    public $overallCompletion = 0;
    public $totalHours = 0;

    public function mount($roadmapId)
    {
        $this->roadmapId = $roadmapId;
        $this->loadProgress();
    }

    public function loadProgress()
    {
        $this->roadmap = Roadmap::with(['sections' => function($query) {
            $query->orderBy('sort_order');
        }])->findOrFail($this->roadmapId);

        $userId = Auth::id();
        $sectionProgressService = app(SectionProgressService::class);

        // Get progress for all sections
        $this->sectionsProgress = $sectionProgressService->getRoadmapProgress($userId, $this->roadmapId);
        
        // Calculate overall completion
        $this->overallCompletion = $sectionProgressService->calculateRoadmapCompletionPercent($userId, $this->roadmapId);
        
        // Calculate total time spent
        $this->totalHours = \Modules\Learning\Models\SectionProgress::where('user_id', $userId)
            ->where('roadmap_id', $this->roadmapId)
            ->sum('time_spent_minutes') / 60;
    }

    public function getSectionStatusClass($sectionProgress)
    {
        return match($sectionProgress['progress']->status) {
            'not_started' => 'text-gray-500',
            'in_progress' => 'text-blue-600',
            'completed' => 'text-green-600',
            'locked' => 'text-gray-400',
            default => 'text-gray-500'
        };
    }

    public function getSectionStatusIcon($sectionProgress)
    {
        return match($sectionProgress['progress']->status) {
            'not_started' => '○',
            'in_progress' => '◐',
            'completed' => '✓',
            'locked' => '🔒',
            default => '○'
        };
    }

    public function getSectionStatusText($sectionProgress)
    {
        return match($sectionProgress['progress']->status) {
            'not_started' => 'Chưa bắt đầu',
            'in_progress' => 'Đang học',
            'completed' => 'Đã hoàn thành',
            'locked' => 'Đã khóa',
            default => 'Chưa bắt đầu'
        };
    }

    public function getProgressColorClass($percent)
    {
        if ($percent >= 80) {
            return 'bg-green-500';
        } elseif ($percent >= 50) {
            return 'bg-blue-500';
        } elseif ($percent >= 25) {
            return 'bg-yellow-500';
        } else {
            return 'bg-gray-300';
        }
    }

    public function startSection($sectionId)
    {
        $sectionProgressService = app(SectionProgressService::class);
        $sectionProgressService->unlockSection(Auth::id(), $sectionId);
        
        $this->loadProgress();
        $this->dispatch('section-started', sectionId: $sectionId);
        
        session()->flash('success', 'Đã bắt đầu học chương mới!');
    }

    public function render()
    {
        return view('learning::livewire.student.section-progress-tracker');
    }
}
