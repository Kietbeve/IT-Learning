@extends('layouts.user')

@section('content')
    <div class="max-w-7xl mx-auto pb-8 pt-4">
        <!-- Search Bar -->
        <form action="{{ route('exam.index') }}" method="get">
          <div class="mb-6 px-4 sm:px-6 lg:px-8">
              <div class="bg-white p-3 rounded-2xl shadow-sm border border-gray-200 flex items-center gap-3">
                  <div class="flex-1">
                      <x-input name="keyword" icon="magnifying-glass" placeholder="Nhập tên bài thi, danh mục để tìm kiếm..." value="{{ request('keyword') }}"
            />
                  </div>
                  <x-button indigo type="submit" label="Tìm kiếm" icon="magnifying-glass" class="hidden sm:flex" />
              </div>
          </div>
        </form>


        {{-- <h1 class="text-3xl font-bold text-gray-900 mb-6 px-4 sm:px-6 lg:px-8">Danh sách bài thi</h1> --}}

        <div class="flex flex-col gap-4 px-4 sm:px-6 lg:px-8">
          @forelse ( $exams as $exam )
            @include('exam::partials.exam_card',
            [
              'exam'=>$exam
            ])
          @empty
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                        <x-icon
                            name="magnifying-glass"
                            class="w-8 h-8 text-gray-400"
                        />
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-800">
                        Không tìm thấy bài kiểm tra
                    </h3>
                    <p class="mt-2 text-sm text-gray-500 max-w-md">
                        Hãy thử thay đổi từ khóa hoặc tìm kiếm bằng tên danh mục khác.
                    </p>
                </div> 
          @endforelse 
        </div>
    </div>
@endsection