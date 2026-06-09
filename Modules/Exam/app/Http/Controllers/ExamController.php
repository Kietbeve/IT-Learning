<?php

namespace Modules\Exam\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Exam\Models\Exam;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('exam::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('exam::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('exam::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('exam::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
    //view contributor/questions
    public function questionManager()
    {
        return view("exam::livewire.question-table");
    }
    public function examManager()
    {
        return view("exam::livewire.exam-table");
    }
    public function examDetail($examId)
    {
        $exam = Exam::findOrFail($examId);
        $questionCount = $exam->questions()->count();
        $attemptCount = $exam->attempts()->count();
        //return view ExamDetail with data
        return view('exam::livewire.exam-detail', 
            compact('exam','questionCount','attemptCount'));
    }
    
}
