<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <button onclick="window.history.back()" class="p-2 text-gray-500 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors shadow-sm" title="Quay lại">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </button>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Quản lý các bước nộp Project</h1>
                <p class="mt-1 text-sm text-gray-600">Project: {{ $project->title }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <button wire:click="createDefaultSteps" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
                Tạo 5 bước mặc định
            </button>
            <button wire:click="openCreateModal" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
                Thêm bước mới
            </button>
        </div>
    </div>

    @if($steps->isEmpty())
        <div class="rounded-lg border-2 border-dashed border-gray-300 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có bước nào</h3>
            <p class="mt-1 text-sm text-gray-500">Tạo các bước nộp bài cho project này</p>
        </div>
    @else
        <div class="overflow-hidden rounded-lg bg-white shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Thứ tự</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Tên bước</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Loại nộp</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">File types</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Max size</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Trạng thái</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($steps as $step)
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{{ $step->step_order }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $step->step_name }}</div>
                                @if($step->instructions)
                                    <div class="mt-1 text-xs text-gray-500">{{ Str::limit($step->instructions, 80) }}</div>
                                @endif
                                @if($step->resource_file_path)
                                    <div class="mt-2 flex items-center gap-1 text-xs text-blue-600 bg-blue-50 w-max px-2 py-1 rounded">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        <a href="{{ Storage::disk('public')->url($step->resource_file_path) }}" target="_blank" class="hover:underline font-medium" title="Tải xuống tài liệu đính kèm">
                                            {{ $step->resource_file_name ?? 'Tài liệu đính kèm' }}
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $step->submission_type === 'file' ? 'bg-blue-100 text-blue-800' : ($step->submission_type === 'link' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800') }}">
                                    {{ $step->submission_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $step->getAllowedFileTypesString() }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $step->max_file_size_mb }} MB</td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <button wire:click="toggleActive({{ $step->id }})" type="button" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 {{ $step->is_active ? 'bg-blue-600' : 'bg-gray-200' }}">
                                    <span class="translate-x-0 inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $step->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                <button wire:click="openEditModal({{ $step->id }})" class="inline-flex items-center justify-center rounded-full bg-blue-600 p-1.5 text-white hover:bg-blue-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>
                                <button wire:click="deleteStep({{ $step->id }})" class="ml-2 inline-flex items-center justify-center rounded-full bg-red-600 p-1.5 text-white hover:bg-red-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <x-modal wire:model.defer="showModal" max-width="4xl">
        <x-card title="{{ $editingStepId ? 'Chỉnh sửa bước' : 'Tạo bước mới' }}">
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <x-input label="Tên bước *" wire:model.defer="step_name" placeholder="VD: Phân tích yêu cầu" />
                    <x-input label="Thứ tự *" wire:model.defer="step_order" type="number" min="1" />
                </div>

                <x-select label="Loại nộp bài *" wire:model.defer="submission_type">
                    <x-select.option value="file" label="File upload" />
                    <x-select.option value="link" label="Link (URL)" />
                    <x-select.option value="both" label="Cả hai (File hoặc Link)" />
                </x-select>

                <x-textarea label="Hướng dẫn" wire:model.defer="instructions" rows="3" placeholder="Hướng dẫn chi tiết cho học viên..." />

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">File yêu cầu đính kèm (Tài liệu, đề bài...)</label>
                    @if($existing_resource_file_path)
                        <div class="mb-2 flex items-center gap-2 text-sm text-gray-600 bg-gray-50 p-2 rounded border border-gray-200">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                            <span class="truncate max-w-xs">{{ $existing_resource_file_name }}</span>
                            <a href="{{ Storage::disk('public')->url($existing_resource_file_path) }}" target="_blank" class="ml-auto text-blue-600 hover:underline">Xem file</a>
                        </div>
                    @endif
                    <input type="file" wire:model="resource_file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                    <div wire:loading wire:target="resource_file" class="text-sm text-gray-500 mt-1">Đang tải lên...</div>
                    @error('resource_file') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                @if(in_array($submission_type, ['file', 'both']))
                    <div class="rounded-lg bg-blue-50 p-4">
                        <h4 class="mb-3 text-sm font-medium text-blue-900">Cài đặt File Upload</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <x-input label="Loại file cho phép (cách nhau bằng dấu phẩy)" wire:model.defer="allowed_file_types" placeholder="doc,docx,pdf,txt" />
                            <x-input label="Kích thước tối đa (MB)" wire:model.defer="max_file_size_mb" type="number" min="1" />
                        </div>
                    </div>
                @endif

                @if(in_array($submission_type, ['link', 'both']))
                    <div class="rounded-lg bg-green-50 p-4">
                        <h4 class="mb-3 text-sm font-medium text-green-900">Cài đặt Link URL</h4>
                        <x-input label="Placeholder" wire:model.defer="link_placeholder" placeholder="Nhập link Figma..." />
                    </div>
                @endif

                <div class="grid grid-cols-3 gap-4 items-start">
                    <div class="pt-7">
                        <x-checkbox label="Bước bắt buộc" wire:model.defer="is_required" />
                    </div>
                    <div class="pt-7">
                        <x-checkbox label="Kích hoạt" wire:model.defer="is_active" />
                    </div>
                    <x-input label="Số lần nộp lại tối đa" wire:model.defer="max_resubmissions" type="number" min="1" />
                </div>
            </div>

            <x-slot name="footer">
                <div class="flex justify-end gap-2">
                    <x-button flat label="Hủy" wire:click="closeModal" />
                    <x-button primary label="{{ $editingStepId ? 'Cập nhật' : 'Tạo mới' }}" wire:click="save" />
                </div>
            </x-slot>
        </x-card>
    </x-modal>
</div>
