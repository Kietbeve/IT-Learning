<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Exam\Services\ExamService;

class HomeController extends Controller
{
    //Hàm khởi tạo HomeController
    public function __construct(
      private ExamService $examService
    )
    {
      // throw new \Exception('Not implemented');
    }
    public function dashboard (){
      //lay danh sach de thi
      $exams = $this->examService->getExamListLatest(5);

      //truyen du lieu vao trang
      return view('dashboard_user',compact('exams'));
    }
}
