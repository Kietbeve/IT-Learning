@extends('layouts.user')

@section('content')
    <div class="max-w-7xl mx-auto pb-8 pt-4">
        <!-- Search Bar -->
        {{-- <form action="{{ route('exam.index') }}" method="get">
          <div class="mb-6 px-4 sm:px-6 lg:px-8">
              <div class="bg-white p-3 rounded-2xl shadow-sm border border-gray-200 flex items-center gap-3">
                  <div class="flex-1">
                      <x-input name="keyword" icon="magnifying-glass" placeholder="Nhập tên bài thi, danh mục để tìm kiếm..." value="{{ request('keyword') }}"
            />
                  </div>
                  <x-button indigo type="submit" label="Tìm kiếm" icon="magnifying-glass" class="hidden sm:flex" />
              </div>
          </div>
        </form> --}}

        <form action="{{ route('exam.index') }}" method="GET">

          <div class="mb-6 px-4 sm:px-6 lg:px-8">

              <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">

                  {{-- Search Row --}}
                  <div class="flex flex-row gap-3">

                      <div class="flex-1 min-w-0">
                          <x-input
                              name="keyword"
                              icon="magnifying-glass"
                              placeholder="Nhập tên bài thi, danh mục để tìm kiếm..."
                              value="{{ request('keyword') }}"
                          />
                      </div>

                      {{-- Filter Toggle --}}
                      <button
                          type="button"
                          x-data
                          @click="$dispatch('toggle-filter')"
                          class="inline-flex items-center justify-center gap-2 px-3 lg:px-4 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 transition shrink-0"
                      >
                          <x-icon name="funnel" class="w-5 h-5" />
                          <span class="hidden lg:inline">Bộ lọc</span>
                      </button>

                      {{-- Search Button --}}
                      <x-button
                          indigo
                          type="submit"
                          icon="magnifying-glass"
                          class="shrink-0 !px-3 lg:!px-4"
                      >
                          <span class="hidden lg:inline">Tìm kiếm</span>
                      </x-button>

                  </div>


                  {{-- Advanced Filters --}}
                  <div
                      x-data="{ open: false }"
                      x-on:toggle-filter.window="open = !open"
                      x-show="open"
                      x-transition
                      class="mt-5 pt-5 border-t border-gray-200"
                  >

                      <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                          {{-- Category --}}
                          <div>

                              <label class="block text-sm font-medium text-gray-700 mb-2">
                                  Danh mục
                              </label>

                              <div class="space-y-2 max-h-28 overflow-y-auto border border-gray-200 rounded-xl p-3 scroll-smooth [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-track]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-gray-300 hover:[&::-webkit-scrollbar-thumb]:bg-gray-400">

                                  <label class="flex items-center gap-2">
                                      <input
                                          type="radio"
                                          name="category"
                                          value=""
                                          class="border-gray-300"
                                          checked
                                      >
                                      <span>Tất cả</span>
                                  </label>

                                  <label class="flex items-center gap-2">
                                      <input
                                          type="radio"
                                          name="category"
                                          value="1"
                                          class="border-gray-300"
                                      >
                                      <span>Laravel</span>
                                  </label>

                                  <label class="flex items-center gap-2">
                                      <input
                                          type="radio"
                                          name="category"
                                          value="2"
                                          class="border-gray-300"
                                      >
                                      <span>PHP</span>
                                  </label>

                                  <label class="flex items-center gap-2">
                                      <input
                                          type="radio"
                                          name="category"
                                          value="3"
                                          class="border-gray-300"
                                      >
                                      <span>Java</span>
                                  </label>

                                  <label class="flex items-center gap-2">
                                      <input
                                          type="radio"
                                          name="category"
                                          value="4"
                                          class="border-gray-300"
                                      >
                                      <span>Database</span>
                                  </label>

                              </div>


                          </div>

                          {{-- Type --}}
                          <div>

                              <x-native-select
                                  label="Loại bài thi"
                                  name="type"
                              >
                                  <option value="">
                                      Tất cả
                                  </option>

                                  <option value="multiple_choice">
                                      Trắc nghiệm
                                  </option>

                                  <option value="essay">
                                      Tự luận
                                  </option>

                                  <option value="hybrid">
                                      Hybrid
                                  </option>

                              </x-native-select>

                          </div>

                          {{-- Sort --}}
                          <div>

                              <x-native-select
                                  label="Sắp xếp"
                                  name="sort"
                              >
                                  <option value="latest">
                                      Mới nhất
                                  </option>

                                  <option value="oldest">
                                      Cũ nhất
                                  </option>

                              </x-native-select>

                          </div>

                      </div>

  
                  </div>

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