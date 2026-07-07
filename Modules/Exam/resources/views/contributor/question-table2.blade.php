

<div>
    <div class="border-b border-gray-200 bg-white px-6 py-5">
        {{-- Hàng trên cùng: Tiêu đề --}}
        <div class="mb-4">
            <h2 class="text-lg font-semibold tracking-tight text-gray-800">
                Danh sách câu hỏi
            </h2>
        </div>

        {{-- Hàng điều khiển: Tìm kiếm, Lọc, và Công cụ --}}
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            
            {{-- Bên Trái: Tìm kiếm + Nút Lọc --}}
            <div class="flex min-w-0 flex-1 items-center gap-3">
                
                {{-- Ô nhập tìm kiếm --}}
                <div class="group relative w-full max-w-sm">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                        <svg class="h-4.5 w-4.5 text-gray-400 transition-colors duration-200 group-focus-within:text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>

                    <input
                        type="text"
                        wire:model.live.debounce.400ms="search"
                        placeholder="Tìm kiếm câu hỏi..."
                        class="w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-10 text-sm text-gray-700 shadow-sm
                               placeholder:text-gray-400
                               transition-all duration-300 ease-in-out
                               hover:border-gray-300 hover:bg-white
                               focus:border-indigo-400 focus:bg-white focus:shadow-md focus:shadow-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                    />

                    {{-- Nút xóa tìm kiếm (chỉ hiện khi có chữ) --}}
                    @if($search)
                    <button type="button" wire:click="$set('search', '')" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 transition-colors duration-200 hover:text-gray-600">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                    @endif
                </div>

                {{-- Nút Bật/Tắt Bộ Lọc --}}
                <button
                    wire:click="toggleFilters"
                    class="relative inline-flex flex-shrink-0 items-center gap-2 rounded-xl border px-4 py-2.5 text-sm font-medium transition-all duration-200
                           {{ $showFilters
                               ? 'border-indigo-300 bg-indigo-50 text-indigo-700 shadow-sm'
                               : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300 hover:bg-gray-50' }}"
                >
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                    </svg>
                    Bộ lọc

                    {{-- Nhãn (Badge) đếm số bộ lọc đang áp dụng --}}
                    @if($this->activeFilterCount > 0)
                    <span class="flex h-5 min-w-5 items-center justify-center rounded-full bg-indigo-600 px-1.5 text-[10px] font-bold text-white">
                        {{ $this->activeFilterCount }}
                    </span>
                    @endif
                </button>
            </div>

            {{-- Bên Phải: Thanh Công Cụ (Toolbar) --}}
            <div class="flex flex-shrink-0 flex-wrap items-center gap-3">
                <x-button indigo icon="plus" label="Thêm câu hỏi" wire:click="$dispatch('question-create')"/>
                <x-button flat icon="arrow-up-tray" label="Import câu hỏi" class="border-2 border-indigo-300" wire:click="$dispatch('question-import')"/>

                {{-- Chức năng Chọn nhiều / Xóa hàng loạt --}}
                @if($showCheckboxes)
                    @if(count($selectedRows) > 0)
                        <x-button negative icon="trash" label="Xóa ({{ count($selectedRows) }})" wire:click="confirmBulkDelete" class="shadow-sm"/>
                    @endif
                    <x-button flat icon="x-mark" label="Hủy chọn nhiều" wire:click="toggleCheckboxes" class="border-2 border-gray-300"/>
                @else
                    <x-button flat icon="check-circle" label="Chọn nhiều" wire:click="toggleCheckboxes" class="border-2 border-indigo-300"/>
                @endif

                {{-- Thiết lập hiển thị Cột --}}
                <x-dropdown>
                    <x-slot name="trigger">
                        <x-button flat icon="view-columns" label="Cột hiển thị" class="border-2 border-indigo-300"/>
                    </x-slot>

                    <div class="w-64 space-y-3 p-4">
                        <x-checkbox wire:model.live="visibleColumns.id" label="ID"/>
                        <x-checkbox wire:model.live="visibleColumns.content" label="Nội dung câu hỏi"/>
                        <x-checkbox wire:model.live="visibleColumns.type" label="Loại câu hỏi"/>
                        <x-checkbox wire:model.live="visibleColumns.difficulty" label="Độ khó"/>
                        <x-checkbox wire:model.live="visibleColumns.status" label="Trạng thái"/>
                        <x-checkbox wire:model.live="visibleColumns.shared" label="Chia sẻ"/>
                        <x-checkbox wire:model.live="visibleColumns.actions" label="Thao tác"/>
                    </div>
                </x-dropdown>
            </div>
        </div>

        {{-- ===================== Khu vực Bộ Lọc (Filter Panel) ===================== --}}
        @if($showFilters)
        <div class="mt-5 rounded-xl border border-gray-100 bg-gray-50/70 p-5 transition-all duration-300">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                {{-- Bộ lọc: Loại câu hỏi --}}
                <div>
                    <x-native-select
                        label="Loại câu hỏi"
                        wire:model.live="filterType"
                    >
                        <option value="">Tất cả</option>
                        @foreach($typeOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-native-select>
                </div>

                {{-- Bộ lọc: Độ khó --}}
                <div>
                    <x-native-select
                        label="Độ khó"
                        wire:model.live="filterDifficulty"
                    >
                        <option value="">Tất cả</option>
                        @foreach($difficultyOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-native-select>
                </div>

                {{-- Bộ lọc: Trạng thái duyệt --}}
                <div>
                    <x-native-select
                        label="Trạng thái"
                        wire:model.live="filterStatus"
                    >
                        <option value="">Tất cả</option>
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-native-select>
                </div>

                {{-- Bộ lọc: Chế độ Chia sẻ --}}
                <div>
                    <x-native-select
                        label="Chia sẻ"
                        wire:model.live="filterShared"
                    >
                        <option value="">Tất cả</option>
                        @foreach($sharedOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-native-select>
                </div>
            </div>

            {{-- Nút Reset toàn bộ bộ lọc --}}
            @if($this->activeFilterCount > 0)
            <div class="mt-4 flex items-center justify-between border-t border-gray-200 pt-4">
                <span class="text-xs text-gray-500">
                    Đang áp dụng <span class="font-semibold text-indigo-600">{{ $this->activeFilterCount }}</span> bộ lọc
                </span>
                <button
                    wire:click="resetFilters"
                    class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-medium text-red-600 transition-colors duration-200 hover:bg-red-50"
                >
                    <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182" />
                    </svg>
                    Xóa tất cả bộ lọc
                </button>
            </div>
            @endif
        </div>
        @endif
    </div>

    {{-- ===================== Bảng Dữ Liệu ===================== --}}
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="sticky top-0 z-10 border-b border-gray-200 bg-gray-50">
                <tr>
                    {{-- Cột Checkbox trên Tiêu đề (Chọn tất cả) --}}
                    @if($showCheckboxes)
                    <th class="w-14 px-6 py-4">
                        <x-checkbox wire:model.live="selectAll"/>
                    </th>
                    @endif
                    
                    @if($visibleColumns['id'])
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">ID</th>
                    @endif
                    @if($visibleColumns['content'])
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Nội dung câu hỏi</th>
                    @endif
                    @if($visibleColumns['type'])
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Loại câu hỏi</th>
                    @endif
                    @if($visibleColumns['difficulty'])
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Độ khó</th>
                    @endif
                    @if($visibleColumns['status'])
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Trạng thái</th>
                    @endif
                    @if($visibleColumns['shared'])
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Chia sẻ</th>
                    @endif
                    @if($visibleColumns['actions'])
                    <th class="w-44 px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">Thao tác</th>
                    @endif
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($questions as $question)
                <tr class="transition hover:bg-indigo-50/40">
                    
                    {{-- Cột Checkbox từng hàng --}}
                    @if($showCheckboxes)
                    <td class="px-6 py-4">
                        <x-checkbox wire:model.live="selectedRows" value="{{ $question->id }}"/>
                    </td>
                    @endif

                    @if($visibleColumns['id'])
                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $question->id }}
                    </td>
                    @endif

                    @if($visibleColumns['content'])
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-800">
                            {{ Str::words(strip_tags($question->content), 10, '...') }}
                        </div>
                    </td>
                    @endif

                    @if($visibleColumns['type'])
                    <td class="px-6 py-4">
                        @if($question->type === 'single_choice')
                            <x-badge indigo label="Trắc nghiệm một đáp án"/>
                        @elseif($question->type === 'multiple_choice')
                            <x-badge purple label="Trắc nghiệm nhiều đáp án"/>
                        @elseif($question->type === 'essay')
                            <x-badge sky label="Tự luận"/>
                        @else
                            <x-badge slate label="{{ $question->type }}"/>
                        @endif
                    </td>
                    @endif

                    @if($visibleColumns['difficulty'])
                    <td class="px-6 py-4">
                        @if($question->difficulty === 'easy')
                            <x-badge positive label="Dễ"/>
                        @elseif($question->difficulty === 'medium')
                            <x-badge warning label="Trung bình"/>
                        @elseif($question->difficulty === 'hard')
                            <x-badge negative label="Khó"/>
                        @else
                            <x-badge slate label="{{ $question->difficulty }}"/>
                        @endif
                    </td>
                    @endif

                    @if($visibleColumns['status'])
                    <td class="px-6 py-4">
                        @if($question->status === 'pending')
                            <x-badge warning label="Chờ duyệt"/>
                        @elseif($question->status === 'approved')
                            <x-badge positive label="Đã duyệt"/>
                        @elseif($question->status === 'rejected')
                            <x-badge negative label="Từ chối"/>
                        @else
                            <x-badge slate label="Không xác định"/>
                        @endif
                    </td>
                    @endif

                    @if($visibleColumns['shared'])
                    <td class="px-6 py-4">
                        {{-- Logic nút chia sẻ phụ thuộc vào quyền Admin --}}
                        @if(!$isAdmin)
                            @if($question->is_shared)
                                <x-button disabled positive label="Đã chia sẻ" class="text-xs" sm/>
                            @else
                                <x-button disabled secondary label="Chia sẻ" class="text-xs" sm/>
                            @endif
                        @else
                            @if($question->is_shared)
                                <x-button wire:click="share({{ $question->id }})" positive label="Hủy chia sẻ" class="text-xs" sm/>
                            @else
                                <x-button wire:click="share({{ $question->id }})" primary label="Chia sẻ" class="text-xs" sm/>
                            @endif
                        @endif
                    </td>
                    @endif

                    @if($visibleColumns['actions'])
                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            {{-- Admin có quyền Duyệt / Từ chối --}}
                            @if($isAdmin && $question->status === 'pending')
                                <x-button flat icon="check" sm positive wire:click="$dispatch('question-approve', { id: {{ $question->id }} })"/>
                                <x-button flat icon="x-mark" sm negative wire:click="$dispatch('question-reject', { id: {{ $question->id }} })"/>
                            @endif
                            <x-button flat icon="eye" sm info wire:click="$dispatch('question-view', { id: {{ $question->id }} })"/>
                            <x-button flat icon="pencil" sm warning wire:click="$dispatch('question-edit', { id: {{ $question->id }} })"/>
                            <x-button flat icon="trash" sm negative wire:click="$dispatch('question-delete-confirm', { id: {{ $question->id }} })"/>
                        </div>
                    </td>
                    @endif
                </tr>
                
                {{-- Khi bảng không có dữ liệu --}}
                @empty
                <tr>
                    @php
                        // Tự động tính toán số cột để gom (colspan) cho dòng thông báo
                        $activeCols = collect($visibleColumns)->filter()->count();
                        $totalCols = $activeCols + ($showCheckboxes ? 1 : 0);
                    @endphp
                    <td colspan="{{ $totalCols }}" class="px-6 py-10 text-center text-sm text-gray-500">
                        Không tìm thấy câu hỏi nào.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ===================== Chân Bảng (Phân trang) ===================== --}}
    <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
        {{ $questions->links() }}
    </div>

    {{-- Nhúng (Embed) Component Modal --}}
    @livewire(\Modules\Exam\Livewire\Contributor\QuestionModal::class)

</div>


