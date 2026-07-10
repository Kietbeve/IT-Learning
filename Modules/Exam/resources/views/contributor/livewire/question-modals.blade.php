{{-- Modal: Chi tiết câu hỏi --}}

<div>

    {{-- Modal: Xem chi tiết câu hỏi --}}
    <x-modal-card title="Chi tiết câu hỏi" blur wire:model="showViewModal" max-width="2xl">
        @if ($question)
            <div class="space-y-4">
                
                <div class="flex gap-2">
                    <x-icon name="document-text" class="w-5 h-5 text-slate-600 shrink-0 mt-0.5" />
                    <span class="font-semibold text-slate-900 text-sm ">Nội dung câu hỏi:</span>
                </div>
                
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                    <div class="flex gap-3">
                        <div>
                            <div class="text-sm text-slate-700 ql-editor">{!! $question->content !!}</div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium">Loại:</span>
                        {{ $question->type }}
                    </div>

                    <div>
                        <span class="font-medium">Độ khó:</span>
                        {{ $question->difficulty }}
                    </div>

                    <div>
                        <span class="font-medium">Danh mục:</span>
                        {{ $question->category?->name }}
                    </div>

                    <div>
                        <span class="font-medium">Người tạo:</span>
                        {{ $question->author?->name }}
                    </div>
                </div>

                @if ($question->options->isNotEmpty())
                    <div>
                        <h3 class="mb-2 font-semibold">
                            Đáp án
                        </h3>

                        <div class="space-y-2">
                            @foreach ($question->options as $option)
                                    <div class="flex items-center justify-between rounded-lg border p-3
                                                                                                                                {{ $option->is_correct
                                ? 'border-green-300 bg-green-50'
                                : 'border-slate-200 bg-white' }}">
                                        <div>
                                            <span class="font-semibold">
                                                {{ $option->option_key }}.
                                            </span>

                                            {{ $option->content }}
                                        </div>

                                        @if ($option->is_correct)
                                            <span class="text-sm font-medium text-green-600">
                                                Đáp án đúng
                                            </span>
                                        @endif
                                    </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($question->type === 'essay')
                    <div class="rounded-lg border border-green-200 bg-green-50 p-4">
                        <div class="flex gap-3">
                            <x-icon name="check-circle" class="w-5 h-5 text-green-600 shrink-0 mt-0.5" />
                            <div>
                                <h4 class="font-semibold text-green-900 text-sm mb-1">Đáp án mẫu:</h4>
                                <div class="text-sm text-green-800">
                                    {!! $question->answer_text ?: '<em class="text-gray-500">Chưa có đáp án mẫu</em>' !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Explanation --}}
                @if (!empty($question->explanation))
                    <div class="rounded-lg border border-blue-200 bg-blue-50 p-4">
                        <div class="flex gap-3">
                            <x-icon name="information-circle" class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" />
                            <div>
                                <h4 class="font-semibold text-blue-900 text-sm mb-1">Giải thích đáp án:</h4>
                                <div class="text-sm text-blue-800 ql-editor">{!! $question->explanation !!}</div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        @endif

        <x-slot name="footer">
            <div class="flex justify-end">
                <x-button flat label="Đóng" wire:click="$set('showViewModal', false)" />
            </div>
        </x-slot>
    </x-modal-card>

    {{-- Modal: Thêm câu hỏi --}}
    <x-modal-card title="Thêm câu hỏi" blur wire:model="showCreateModal" max-width="3xl">
        <div class="space-y-4">
            <x-native-select label="Danh mục" wire:model="category_id">
                <option value="">-- Chọn danh mục --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                @endforeach
            </x-native-select>

            <x-text-editor label="Nội dung" wire:model="content" />

            <x-text-editor
                label="Giải thích"
                wire:model="explanation"
                placeholder="Giải thích đáp án hoặc kiến thức liên quan (không bắt buộc)"
            />

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-native-select label="Độ khó" wire:model="difficulty">
                    <option value="easy">Dễ</option>
                    <option value="medium">Trung bình</option>
                    <option value="hard">Khó</option>
                </x-native-select>

                    <x-native-select label="Loại câu hỏi" wire:model.live="type">
                    <option value="single_choice">Trắc nghiệm một đáp án</option>
                    <option value="multiple_choice">Trắc nghiệm nhiều đáp án</option>
                    <option value="essay">Tự luận</option>
                </x-native-select>
            </div>

            {{-- Phần đáp án: Động theo loại câu hỏi --}}
            @if ($type === 'single_choice' || $type === 'multiple_choice')
                <div class="space-y-3 rounded-xl border border-slate-200 bg-slate-50 p-4">
                    {{-- Header với nút thêm đáp án --}}
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-semibold text-slate-700">Đáp án</div>
                        <x-button xs positive icon="plus" label="Thêm đáp án" wire:click="addOption" />
                    </div>

                    {{-- Danh sách đáp án --}}
                    @foreach ($options as $index => $option)
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-[1fr_auto_auto] sm:items-end">
                            {{-- Ô nhập nội dung đáp án --}}
                            <x-input 
                                :label="'Đáp án ' . chr(65 + $index)" 
                                wire:model="options.{{ $index }}.content"
                                :placeholder="'Nhập đáp án ' . chr(65 + $index)" 
                            />

                            {{-- Radio button cho single_choice, Checkbox cho multiple_choice --}}
                            @if ($type === 'single_choice')
                                <label class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700">
                                    <input 
                                        type="radio" 
                                        name="correct_answer_create"
                                        class="border-slate-300 text-blue-600 focus:ring-blue-500"
                                        wire:click="selectSingleCorrectAnswer({{ $index }})"
                                        @if($option['is_correct']) checked @endif
                                    >
                                    Đúng
                                </label>
                            @else
                                <label class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700">
                                    <input 
                                        type="checkbox" 
                                        class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                        wire:model="options.{{ $index }}.is_correct"
                                    >
                                    Đúng
                                </label>
                            @endif

                            {{-- Nút xóa đáp án (disable nếu chỉ còn 2 đáp án) --}}
                            <x-button 
                                xs 
                                negative 
                                icon="trash" 
                                wire:click="removeOption({{ $index }})"
                                :disabled="count($options) <= 2"
                            />
                        </div>
                    @endforeach

                    @error('options')
                        <p class="text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            {{-- Phần đáp án tự luận (tạm thời chưa lưu) --}}
            @elseif ($type === 'essay')
                <div class="space-y-3 rounded-xl border border-amber-200 bg-amber-50 p-4">
                    <div class="text-sm font-semibold text-slate-700">Đáp án tự luận</div>
                    <x-text-editor
                        wire:model="answer_text"
                        placeholder="Nhập gợi ý đáp án"
                        rows="5"
                    />
                    {{-- <p class="text-xs text-amber-600">
                        ℹ️ Đáp án tự luận sẽ được cập nhật trong phiên bản sau
                    </p> --}}
                </div>
            @endif
        </div>

        <x-slot name="footer">
            <div class="flex justify-end gap-2">
                <x-button flat label="Hủy" x-on:click="close" />
                <x-button primary label="Lưu" wire:click="createQuestion" />
            </div>
        </x-slot>
    </x-modal-card>

    {{-- Modal: Cập nhật câu hỏi --}}
    <x-modal-card title="Cập nhật câu hỏi" blur wire:model="showEditModal" max-width="3xl">
        @if ($question)
            <div class="space-y-4">

                <x-native-select label="Danh mục" wire:model="category_id">
                    <option value="">-- Chọn danh mục --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                    @endforeach
                </x-native-select>

                <x-text-editor label="Nội dung" wire:model="content" />

                <x-text-editor
                    label="Giải thích"
                    wire:model="explanation"
                    placeholder="Giải thích đáp án hoặc kiến thức liên quan (không bắt buộc)"
                />

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-native-select label="Độ khó" wire:model="difficulty">
                        <option value="easy">Dễ</option>
                        <option value="medium">Trung bình</option>
                        <option value="hard">Khó</option>
                    </x-native-select>

                <x-native-select label="Loại câu hỏi" wire:model.live="type">
                        <option value="single_choice">Trắc nghiệm một đáp án</option>
                        <option value="multiple_choice">Trắc nghiệm nhiều đáp án</option>
                        <option value="essay">Tự luận</option>
                    </x-native-select>
                </div>

                {{-- Phần đáp án: Động theo loại câu hỏi --}}
                @if ($type === 'single_choice' || $type === 'multiple_choice')
                    <div class="space-y-3 rounded-xl border border-slate-200 bg-slate-50 p-4">
                        {{-- Header với nút thêm đáp án --}}
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-semibold text-slate-700">Đáp án</div>
                            <x-button xs positive icon="plus" label="Thêm đáp án" wire:click="addOption" />
                        </div>

                        {{-- Danh sách đáp án --}}
                        @foreach ($options as $index => $option)
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-[1fr_auto_auto] sm:items-end">
                                {{-- Ô nhập nội dung đáp án --}}
                                <x-input 
                                    :label="'Đáp án ' . chr(65 + $index)" 
                                    wire:model="options.{{ $index }}.content"
                                    :placeholder="'Nhập đáp án ' . chr(65 + $index)" 
                                />

                                {{-- Radio button cho single_choice, Checkbox cho multiple_choice --}}
                                @if ($type === 'single_choice')
                                    <label class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700">
                                        <input 
                                            type="radio" 
                                            name="correct_answer_edit"
                                            class="border-slate-300 text-blue-600 focus:ring-blue-500"
                                            wire:click="selectSingleCorrectAnswer({{ $index }})"
                                            @if($option['is_correct']) checked @endif
                                        >
                                        Đúng
                                    </label>
                                @else
                                    <label class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700">
                                        <input 
                                            type="checkbox" 
                                            class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                            wire:model="options.{{ $index }}.is_correct"
                                        >
                                        Đúng
                                    </label>
                                @endif

                                {{-- Nút xóa đáp án (disable nếu chỉ còn 2 đáp án) --}}
                                <x-button 
                                    xs 
                                    negative 
                                    icon="trash" 
                                    wire:click="removeOption({{ $index }})"
                                    :disabled="count($options) <= 2"
                                />
                            </div>
                        @endforeach

                        @error('options')
                            <p class="text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                {{-- Phần đáp án tự luận (tạm thời chưa lưu) --}}
                @elseif ($type === 'essay')
                    <div class="space-y-3 rounded-xl border border-amber-200 bg-amber-50 p-4">
                        <div class="text-sm font-semibold text-slate-700">Đáp án tự luận</div>
                        <x-text-editor 
                            wire:model="answer_text"
                            placeholder="Nhập gợi ý đáp án"
                            rows="5"
                        />
                        {{-- <p class="text-xs text-amber-600">
                            ℹ️ Đáp án tự luận sẽ được cập nhật trong phiên bản sau
                        </p> --}}
                    </div>
                @endif

            </div>

            <x-slot name="footer">
                <div class="flex justify-end gap-2">
                    <x-button flat label="Hủy" x-on:click="close" />

                    <x-button primary label="Lưu" wire:click="update" />
                </div>
            </x-slot>

        @else
            <p class="rounded-lg border border-slate-200 bg-slate-50 p-4 text-slate-500 italic">
                Không có dữ liệu câu hỏi để chỉnh sửa.
            </p>
        @endif
    </x-modal-card>

    {{-- Modal: Xác nhận xóa --}}
    <x-modal-card title="Xác nhận xóa" blur wire:model="showDeleteModal">
        @if ($question)
            <div class="space-y-4">

                <p class="text-slate-600">
                    Bạn có chắc chắn muốn xóa câu hỏi sau?
                </p>

                <div class="rounded-lg border border-red-200 bg-red-50 p-4">
                    {!! $question->content !!}
                </div>

                <p class="text-sm text-red-600">
                    Dữ liệu sẽ được chuyển sang trạng thái đã xóa và có thể khôi phục sau.
                </p>

            </div>
        @endif

        <x-slot name="footer">
            <div class="flex justify-end gap-2">

                <x-button flat label="Hủy" wire:click="$set('showDeleteModal', false)" />

                <x-button negative label="Xác nhận xóa" wire:click="delete_one" />

            </div>
        </x-slot>
    </x-modal-card>
    {{-- Modal: Xác nhận xóa nhiều --}}
    <x-modal-card title="Xác nhận xóa" blur wire:model="showBulkDeleteModal">
        <div class="space-y-4">

            <p>
                Bạn có chắc chắn muốn xóa
                <span class="font-bold text-red-600">
                    {{ count($questionIds) }}
                </span>
                câu hỏi đã chọn?
            </p>

            <div class="rounded-lg border border-red-200 bg-red-50 p-4">
                Hành động này sẽ chuyển các câu hỏi sang trạng thái đã xóa.
            </div>

        </div>

        <x-slot name="footer">
            <div class="flex justify-end gap-2">

                <x-button flat label="Hủy" wire:click="$set('showBulkDeleteModal', false)" />

                <x-button negative label="Xác nhận xóa" wire:click="bulk_delete" />

            </div>
        </x-slot>
    </x-modal-card>

    {{-- Modal Import câu hỏi --}}
    <x-modal-card
        title="Import câu hỏi"
        blur
        wire:model="showImportModal"
        max-width="2xl"
    >
        <div class="space-y-4">

            <div class="p-4 bg-blue-50 rounded-lg">
                <p class="font-semibold">
                    Tải file Excel mẫu
                </p>

                <a
                    href="{{ route('questions.import.template') }}"
                    class="inline-flex items-center mt-2 px-3 py-2 bg-blue-600 text-white rounded-lg"
                >
                    📄 Download mẫu
                </a>
            </div>

            <div>
                <input
                    type="file"
                    wire:model="importFile"
                    accept=".xlsx,.xls,.csv"
                    class="w-full"
                >

                <div wire:loading wire:target="importFile" class="text-blue-600 mt-2 text-sm">
                    ⏳ Đang tải file lên server...
                </div>

                @error('importFile')
                    <span class="text-red-500">
                        {{ $message }}
                    </span>
                @enderror

                                @if ($errors->has('import'))
    <div
        class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4"
    >
        <div class="flex items-start gap-3">
            <svg
                class="h-5 w-5 text-red-500 mt-0.5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 9v2m0 4h.01M5.07 19H18.93c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"
                />
            </svg>

            <div>
                <h3 class="font-semibold text-red-800">
                    Import thất bại
                </h3>

                <p class="mt-1 text-sm text-red-700">
                    {{ $errors->first('import') }}
                </p>
            </div>
        </div>
    </div>
@endif
            </div>

        </div>

        <x-slot name="footer">
            <div class="flex justify-end gap-2">

                <x-button
                    flat
                    label="Hủy"
                    x-on:click="$wire.showImportModal = false"
                />

                <x-button
                    primary
                    label="Import"
                    wire:click="importQuestions"
                    spinner="importQuestions"
                    wire:loading.attr="disabled"
                    wire:target="importFile"
                />

            </div>
        </x-slot>
    </x-modal-card>

    {{-- Modal duyệt --}}
    <x-modal-card title="Xác nhận duyệt câu hỏi" blur wire:model="showApproveModal">
        <div class="space-y-4">

            <p>
                Bạn có chắc chắn muốn duyệt câu hỏi này?
            </p>

            <div class="rounded-lg border border-green-200 bg-green-50 p-4">
                Sau khi duyệt, câu hỏi sẽ được chuyển sang trạng thái
                <span class="font-semibold text-green-700">Đã duyệt</span>.
            </div>

        </div>

        <x-slot name="footer">
            <div class="flex justify-end gap-2">

                <x-button
                    flat
                    label="Hủy"
                    wire:click="$set('showApproveModal', false)"
                />

                <x-button
                    positive
                    label="Xác nhận"
                    wire:click="approve"
                />

            </div>
        </x-slot>
    </x-modal-card>
    {{-- Modal từ chối --}}
    <x-modal-card title="Từ chối câu hỏi" blur wire:model="showRejectModal">
        <div class="space-y-4">

            <x-textarea
                label="Lý do từ chối"
                wire:model.defer="rejectReason"
                placeholder="Nhập lý do từ chối..."
            />

        </div>

        <x-slot name="footer">
            <div class="flex justify-end gap-2">

                <x-button
                    flat
                    label="Hủy"
                    wire:click="$set('showRejectModal', false)"
                />

                <x-button
                    negative
                    label="Xác nhận từ chối"
                    wire:click="reject"
                />

            </div>
        </x-slot>
    </x-modal-card>
</div>