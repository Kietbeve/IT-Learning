<?php

namespace Modules\Learning\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Modules\Learning\Services\RoadmapService; 

class LearningController extends Controller
{
    protected RoadmapService $roadmapService;

    public function __construct(RoadmapService $roadmapService)
    {
        $this->roadmapService = $roadmapService;
    }

    /**
     * TRANG 1: Danh sách lộ trình
     */
    public function index(Request $request): View
    {
        return view('learning::layouts.roadmap-list', [
            'roadmaps' => $this->roadmapService->getFilteredRoadmaps($request->all(), 6)
        ]);
    }

    /**
     * TRANG 2: Chi tiết lộ trình
     */
    public function show(mixed $id): View
    {
        return view('learning::layouts.roadmap-detail', array_merge(
            ['id' => $id], 
            $this->roadmapService->getRoadmapDetail($id)
        ));
    }

    /**
     * TRANG 3: Nội dung chi tiết bài học
     */
    public function showLesson(mixed $roadmapId, mixed $lessonId): View
    {
        return view('learning::layouts.lesson-show', array_merge(
            ['roadmapId' => $roadmapId, 'lessonId' => $lessonId],
            $this->roadmapService->getLessonDetail($roadmapId, $lessonId)
        ));
    }
}