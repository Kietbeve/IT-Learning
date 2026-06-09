<div>
    <x-notifications z-index="z-50" />

    {{-- Modal: Chi tiết đề thi --}}
    <x-modal-card title="Chi tiết đề thi" blur wire:model="showViewModal" max-width="2xl">
        @if ($exam)
            <div class="space-y-4">
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                    <h3 class="text-base font-semibold text-slate-900">{{ $exam->title }}</h3>
                    @if ($exam->short_description)
                        <p class="mt-1 text-sm text-slate-600">{{ $exam->short_description }}</p>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-slate-500">Mã đề (Public ID):</span>
                        <span class="font-semibold text-slate-900">{{ $exam->public_id }}</span>
                    </div>

                    <div>
                        <span class="font-medium text-slate-500">Danh mục:</span>
                        <span class="text-slate-900">{{ $exam->category?->name ?? '—' }}</span>
                    </div>

                    <div>
                        <span class="font-medium text-slate-500">Loại đề:</span>
                        <span class="text-slate-900">
                            {{ match ($exam->type) {
                                'multiple_choice' => 'Trắc nghiệm',
                                'essay' => 'Tự luận',
                                'hybrid' => 'Hỗn hợp',
                                default => $exam->type,
                            } }}
                        </span>
                    </div>

                    <div>
                        <span class="font-medium text-slate-500">Chế độ:</span>
                        <span class="text-slate-900">
                            {{ match ($exam->mode) {
                                'practice' => 'Luyện tập',
                                'official' => 'Thi chính thức',
                                default => $exam->mode,
                            } }}
                        </span>
                    </div>

                    <div>
                        <span class="font-medium text-slate-500">Thời lượng:</span>
                        <span class="text-slate-900">{{ $exam->duration_minutes }} phút</span>
                    </div>

                    <div>
                        <span class="font-medium text-slate-500">Điểm đạt:</span>
                        <span class="text-slate-900">{{ $exam->pass_percent }}%</span>
                    </div>

                    <div>
                        <span class="font-medium text-slate-500">Phạm vi:</span>
                        <span class="text-slate-900">
                            {{ match ($exam->visibility) {
                                'public' => 'Công khai',
                                'private' => 'Riêng tư',
                                default => $exam->visibility,
                            } }}
                        </span>
                    </div>

                    <div>
                        <span class="font-medium text-slate-500">Trạng thái:</span>
                        <span class="text-slate-900">
                            {{ match ($exam->status) {
                                'draft' => 'Bản nháp',
                                'pending' => 'Chờ duyệt',
                                'approved' => 'Đã duyệt',
                                'rejected' => 'Từ chối',
                                default => $exam->status,
                            } }}
                        </span>
                    </div>

                    <div>
                        <span class="font-medium text-slate-500">Số lượt làm:</span>
                        <span class="text-slate-900">{{ $exam->attempt_count }}</span>
                    </div>

                    <div>
                        <span class="font-medium text-slate-500">Người tạo:</span>
                        <span class="text-slate-900">{{ $exam->author?->name ?? '—' }}</span>
                    </div>
                </div>

                @if ($exam->description)
                    <div class="border-t border-slate-100 pt-3">
                        <span class="font-medium text-slate-500 text-sm">Mô tả:</span>
                        <div class="mt-1 text-sm text-slate-700 bg-white p-3 rounded border border-slate-200">
                            {!! $exam->description !!}
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

    {{-- Modal: Chỉnh sửa đề thi (Placeholder) --}}
    <x-modal-card title="Chỉnh sửa đề thi" blur wire:model="showEditModal" max-width="xl">
        @if ($exam)
            <div class="space-y-4">
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-amber-800">
                    <p class="font-semibold text-base mb-1">Màn hình chỉnh sửa đề thi</p>
                    <p class="text-sm">Đây là placeholder cho chức năng chỉnh sửa đề thi <strong>{{ $exam->title }}</strong> (ID: {{ $exam->id }}).</p>
                </div>
            </div>
        @endif

        <x-slot name="footer">
            <div class="flex justify-end gap-2">
                <x-button flat label="Hủy" wire:click="$set('showEditModal', false)" />
                <x-button primary label="Lưu (Placeholder)" wire:click="$set('showEditModal', false)" />
            </div>
        </x-slot>
    </x-modal-card>

    {{-- Modal: Thêm đề thi (Placeholder) --}}
    <x-modal-card title="Thêm đề thi" blur wire:model="showCreateModal" max-width="xl">
        <div class="space-y-4">
            <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 text-blue-800">
                <p class="font-semibold text-base mb-1">Màn hình thêm mới đề thi</p>
                <p class="text-sm">Đây là placeholder cho chức năng thêm mới đề thi.</p>
            </div>
        </div>

        <x-slot name="footer">
            <div class="flex justify-end gap-2">
                <x-button flat label="Hủy" wire:click="$set('showCreateModal', false)" />
                <x-button primary label="Lưu (Placeholder)" wire:click="$set('showCreateModal', false)" />
            </div>
        </x-slot>
    </x-modal-card>
</div>
