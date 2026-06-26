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
                          class="shrink-0 px-3! lg:px-4!"
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

                      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                          {{-- Category --}}
                          <div x-data="{ searchCategory: '' }">

                              <label class="block text-sm font-medium text-gray-700 mb-2">
                                  Danh mục
                              </label>

                              <input
                                  type="text"
                                  x-model="searchCategory"
                                  placeholder="Tìm danh mục..."
                                  class="mb-2 w-full text-xs py-1.5 px-3 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                              >

                              <div class="space-y-2 max-h-28 overflow-y-auto border border-gray-200 rounded-xl p-3 scroll-smooth [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-track]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-gray-300 hover:[&::-webkit-scrollbar-thumb]:bg-gray-400">

                                  <label class="flex items-center gap-2" x-show="!searchCategory || 'tất cả'.includes(searchCategory.toLowerCase())">
                                      <input
                                          type="radio"
                                          name="category"
                                          value=""
                                          class="border-gray-300"
                                          {{ request('category') ? '' : 'checked' }}
                                      >
                                      <span>Tất cả</span>
                                  </label>

                                  @foreach ($categories as $category)
                                      <label class="flex items-center gap-2" x-show="!searchCategory || '{{ mb_strtolower($category->name) }}'.includes(searchCategory.toLowerCase())">
                                          <input
                                              type="radio"
                                              name="category"
                                              value="{{ $category->id }}"
                                              class="border-gray-300"
                                              {{ request('category') == $category->id ? 'checked' : '' }}
                                          >
                                          <span>{{ $category->name }}</span>
                                      </label>
                                  @endforeach

                              </div>


                          </div>

                          {{-- Tags --}}
                          <div x-data="{ searchTag: '' }">

                              <label class="block text-sm font-medium text-gray-700 mb-2">
                                  Tags
                              </label>

                              <input
                                  type="text"
                                  x-model="searchTag"
                                  placeholder="Tìm tag..."
                                  class="mb-2 w-full text-xs py-1.5 px-3 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                              >

                              <div class="space-y-2 max-h-28 overflow-y-auto border border-gray-200 rounded-xl p-3 scroll-smooth [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-track]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-gray-300 hover:[&::-webkit-scrollbar-thumb]:bg-gray-400">

                                  @foreach ($tags as $id => $name)
                                      <label class="flex items-center gap-2" x-show="!searchTag || '{{ mb_strtolower($name) }}'.includes(searchTag.toLowerCase())">
                                          <input
                                              type="checkbox"
                                              name="tags[]"
                                              value="{{ $id }}"
                                              class="border-gray-300 rounded text-indigo-600 focus:ring-indigo-500"
                                              {{ in_array($id, (array)request('tags', [])) ? 'checked' : '' }}
                                          >
                                          <span class="text-sm text-gray-700">{{ $name }}</span>
                                      </label>
                                  @endforeach

                              </div>

                          </div>

                          {{-- Type --}}
                          <div>

                              <x-native-select
                                  label="Loại bài thi"
                                  name="type"
                              >
                                  <option value="" {{ request('type') ? '' : 'selected' }}>
                                      Tất cả
                                  </option>

                                  <option value="multiple_choice" {{ request('type') == 'multiple_choice' ? 'selected' : '' }}>
                                      Trắc nghiệm
                                  </option>

                                  <option value="essay" {{ request('type') == 'essay' ? 'selected' : '' }}>
                                      Tự luận
                                  </option>

                                  <option value="hybrid" {{ request('type') == 'hybrid' ? 'selected' : '' }}>
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
                                  <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>
                                      Mới nhất
                                  </option>

                                  <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                                      Cũ nhất
                                  </option>

                              </x-native-select>

                          </div>

                          
                          

                          </div>

                      </div>

  
                  </div>

              </div>

          </div>

        </form>