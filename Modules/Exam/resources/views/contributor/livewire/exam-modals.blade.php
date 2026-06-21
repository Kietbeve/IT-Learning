<div>
    <x-notifications z-index="z-50" />

    {{-- Modal: Chi tiết đề thi --}}
    <x-modal-card title="Chi tiết đề thi" blur wire:model="showViewModal" max-width="3xl">
        @if ($exam)
                <div class="space-y-5 text-sm">

                    {{-- Tiêu đề & Mô tả ngắn --}}
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                        <h3 class="text-base font-semibold text-slate-900">{{ $exam->title }}</h3>
                        @if ($exam->slug)
                            <p class="mt-0.5 text-xs text-slate-400 font-mono">{{ $exam->slug }}</p>
                        @endif
                        @if ($exam->short_description)
                            <p class="mt-2 text-sm text-slate-600">{{ $exam->short_description }}</p>
                        @endif
                    </div>

                    {{-- Thông tin chính --}}
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Thông tin chính</p>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-3">

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Mã đề (Public ID)</span>
                                <span class="font-mono font-semibold text-slate-800 text-xs">{{ $exam->public_id }}</span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Danh mục</span>
                                <span class="text-slate-800">{{ $exam->category?->name ?? '—' }}</span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Loại đề</span>
                                <span class="text-slate-800">
                                    {{ match ($exam->type) {
                'multiple_choice' => 'Trắc nghiệm',
                'essay' => 'Tự luận',
                'hybrid' => 'Hỗn hợp',
                default => $exam->type,
            } }}
                                </span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Chế độ</span>
                                <span class="text-slate-800">
                                    {{ match ($exam->mode) {
                'practice' => 'Luyện tập',
                'official' => 'Thi chính thức',
                default => $exam->mode,
            } }}
                                </span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Thời lượng</span>
                                <span class="text-slate-800">{{ $exam->duration_minutes }} phút</span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Điểm đạt</span>
                                <span class="text-slate-800">{{ $exam->pass_percent }}%</span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Phạm vi</span>
                                <span class="text-slate-800">
                                    {{ match ($exam->visibility) {
                'public' => 'Công khai',
                'private' => 'Riêng tư',
                default => $exam->visibility,
            } }}
                                </span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Trạng thái</span>
                                @php
                                    $statusMap = [
                                        'draft' => ['label' => 'Bản nháp', 'class' => 'bg-slate-100 text-slate-600'],
                                        'pending' => ['label' => 'Chờ duyệt', 'class' => 'bg-yellow-100 text-yellow-700'],
                                        'approved' => ['label' => 'Đã duyệt', 'class' => 'bg-green-100 text-green-700'],
                                        'rejected' => ['label' => 'Từ chối', 'class' => 'bg-red-100 text-red-700'],
                                    ];
                                    $s = $statusMap[$exam->status] ?? ['label' => $exam->status, 'class' => 'bg-slate-100 text-slate-600'];
                                @endphp
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $s['class'] }}">
                                    {{ $s['label'] }}
                                </span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Số câu hỏi</span>
                                <span class="text-slate-800 font-semibold">{{ $exam->questions_count ?? 0 }}</span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Số lượt làm</span>
                                <span class="text-slate-800">{{ $exam->attempt_count }}</span>
                            </div>

                        </div>
                    </div>

                    {{-- Thông tin quản lý --}}
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Quản lý & Thời gian</p>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-3">

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Người tạo</span>
                                <span class="text-slate-800">{{ $exam->author?->name ?? '—' }}</span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Người duyệt</span>
                                <span class="text-slate-800">{{ $exam->reviewer?->name ?? '—' }}</span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Ngày tạo</span>
                                <span class="text-slate-800">{{ $exam->created_at?->format('d/m/Y H:i') ?? '—' }}</span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Cập nhật lần cuối</span>
                                <span class="text-slate-800">{{ $exam->updated_at?->format('d/m/Y H:i') ?? '—' }}</span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Ngày duyệt</span>
                                <span class="text-slate-800">{{ $exam->reviewed_at?->format('d/m/Y H:i') ?? '—' }}</span>
                            </div>

                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-500">Ngày phát hành</span>
                                <span class="text-slate-800">{{ $exam->publish_at?->format('d/m/Y H:i') ?? '—' }}</span>
                            </div>

                        </div>
                    </div>

                    {{-- Lý do từ chối (nếu có) --}}
                    @if ($exam->status === 'rejected' && $exam->rejected_reason)
                        <div class="rounded-lg border border-red-200 bg-red-50 p-3">
                            <p class="text-xs font-semibold text-red-600 mb-1">Lý do từ chối</p>
                            <p class="text-sm text-red-700">{{ $exam->rejected_reason }}</p>
                        </div>
                    @endif

                    {{-- Mô tả chi tiết --}}
                    @if ($exam->description)
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Mô tả chi tiết</p>
                            <div class="text-sm text-slate-700 bg-slate-50 p-3 rounded border border-slate-200">
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

    {{-- Modal: Chỉnh sửa đề thi --}}
    <x-modal-card title="Chỉnh sửa đề thi" blur wire:model="showEditModal" max-width="xl">

        @if ($showEditModal)
            <div class="space-y-4">

                <x-input label="Tiêu đề" wire:model="title" />

                {{-- <x-input label="Slug" wire:model="slug" /> --}}

                <x-textarea label="Mô tả ngắn" wire:model="short_description" />

                <x-textarea label="Mô tả chi tiết" wire:model="description" />

                <x-native-select label="Danh mục" wire:model="category_id">
                    <option value="">-- Chọn danh mục --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category['id'] }}" @selected($category_id == $category['id'])>
                            {{ $category['name'] }}
                        </option>
                    @endforeach
                </x-native-select>

                <div class="grid grid-cols-2 gap-4">

                    <x-native-select label="Loại đề" wire:model="type">
                        <option value="multiple_choice" @selected($type === 'multiple_choice')>Trắc nghiệm</option>
                        <option value="essay" @selected($type === 'essay')>Tự luận</option>
                        <option value="hybrid" @selected($type === 'hybrid')>Kết hợp</option>
                    </x-native-select>

                    <x-native-select label="Chế độ" wire:model="mode">
                        <option value="practice" @selected($mode === 'practice')>Luyện tập</option>
                        <option value="official" @selected($mode === 'official')>Chính thức</option>
                    </x-native-select>

                </div>

                <div class="grid grid-cols-2 gap-4">

                    <x-input type="number" label="Thời gian (phút)" wire:model="duration_minutes" />

                    <x-input type="number" label="Điểm đạt (%)" wire:model="pass_percent" />

                </div>

                <div class="grid grid-cols-2 gap-4">

                    <x-native-select label="Hiển thị" wire:model="visibility">
                        <option value="public" @selected($visibility === 'public')>Công khai</option>
                        <option value="private" @selected($visibility === 'private')>Riêng tư</option>
                    </x-native-select>

                    {{-- <x-native-select label="Trạng thái" wire:model="status">
                        <option value="draft" @selected($status === 'draft')>Nháp</option>
                        <option value="pending" @selected($status === 'pending')>Chờ duyệt</option>
                        <option value="approved" @selected($status === 'approved')>Đã duyệt</option>
                        <option value="rejected" @selected($status === 'rejected')>Từ chối</option>
                    </x-native-select> --}}

                </div>

            </div>
        @endif

        <x-slot name="footer">
            <div class="flex justify-end gap-2">
                <x-button flat label="Hủy" wire:click="$set('showEditModal', false)" />
                <x-button primary label="Lưu" wire:click="save" />
            </div>
        </x-slot>

    </x-modal-card>

    {{-- Modal: Xác nhận xóa --}}
    <x-modal-card title="Xác nhận xóa" blur wire:model="showDeleteModal">
        @if ($exam)
            <div class="space-y-4">

                <p class="text-slate-600">
                    Bạn có chắc chắn muốn xóa đề thi sau?
                </p>

                <div class="rounded-lg border border-red-200 bg-red-50 p-4">
                    {!! $exam->title !!}
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
</div>