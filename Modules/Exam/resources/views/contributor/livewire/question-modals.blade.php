{{-- Modal: Chi tiết câu hỏi --}}

<div>
    <x-notifications z-index="z-50" />
    {{-- Modal: Xem chi tiết câu hỏi --}}
    <x-modal-card title="Chi tiết câu hỏi" blur wire:model="showViewModal" max-width="2xl">
        @if ($question)
            <div class="space-y-4">

                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                    {!! $question->content !!}
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

                {{-- Explanation --}}
                @if (!empty($question->explanation))
                    <div class="rounded-lg border border-blue-200 bg-blue-50 p-4">
                        <h3 class="mb-2 font-semibold text-blue-700">
                            Giải thích
                        </h3>

                        <div class="text-sm text-slate-700">
                            {!! $question->explanation !!}
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

            <x-textarea label="Nội dung" wire:model="content" />

            <x-textarea
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

                <x-native-select label="Loại câu hỏi" wire:model="type">
                    <option value="single_choice">Trắc nghiệm một đáp án</option>
                    <option value="multiple_choice">Trắc nghiệm nhiều đáp án</option>
                    <option value="essay">Tự luận</option>
                </x-native-select>
            </div>

            @if (in_array($type, ['single_choice', 'multiple_choice'], true))
                <div class="space-y-3 rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="text-sm font-semibold text-slate-700">Đáp án</div>

                    @foreach ($options as $index => $option)
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-[1fr_auto] sm:items-end">
                            <x-input :label="'Đáp án ' . chr(65 + $index)" wire:model="options.{{ $index }}.content"
                                :placeholder="'Nhập đáp án ' . chr(65 + $index)" />

                            <label
                                class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700">
                                <input type="checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                    wire:model="options.{{ $index }}.is_correct">
                                Đúng
                            </label>
                        </div>
                    @endforeach

                    @error('options')
                        <p class="text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
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

                <x-textarea label="Nội dung" wire:model="content" />

                <x-textarea
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

                    <x-native-select label="Loại câu hỏi" wire:model="type">
                        <option value="single_choice">Trắc nghiệm một đáp án</option>
                        <option value="multiple_choice">Trắc nghiệm nhiều đáp án</option>
                        <option value="essay">Tự luận</option>
                    </x-native-select>
                </div>

                @if (in_array($type, ['single_choice', 'multiple_choice'], true))
                    <div class="space-y-3 rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <div class="text-sm font-semibold text-slate-700">Đáp án</div>

                        @foreach ($options as $index => $option)
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-[1fr_auto] sm:items-end">
                                <x-input label="Đáp án {{ chr(65 + $index) }}" wire:model="options.{{ $index }}.content"
                                    placeholder="Nhập đáp án {{ chr(65 + $index) }}" />

                                <label
                                    class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700">
                                    <input type="checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                        wire:model="options.{{ $index }}.is_correct">
                                    Đúng
                                </label>
                            </div>
                        @endforeach

                        @error('options')
                            <p class="text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
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
</div>