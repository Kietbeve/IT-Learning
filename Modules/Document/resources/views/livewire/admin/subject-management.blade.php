<div>
    <x-notifications z-index="z-50" />
    {{-- Header - Thanh tìm kiếm và nút thêm mới --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex-1 max-w-md relative">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search"
                placeholder="Tìm kiếm môn học, danh mục..."
                class="w-full rounded-xl border-slate-200 bg-white px-4 py-2.5 text-sm shadow-sm transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
            >
            {{-- Loading indicator khi đang tìm kiếm --}}
            <div
                wire:loading.flex
                wire:target="search"
                class="absolute inset-y-0 right-3 items-center"
            >
                <svg
                    class="h-4 w-4 animate-spin text-slate-500"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    ></circle>
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                    ></path>
                </svg>
            </div>
        </div>
        <button 
            wire:click="openCreateModal"
            class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/50"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Thêm Môn Học Mới
        </button>
    </div>

    {{-- Bảng danh sách môn học --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">ID</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Tên Môn Học</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Danh Mục</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Slug</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Trạng Thái</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-700">Ngày Tạo</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-slate-700">Thao Tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($subjects as $subject)
                        <tr class="transition hover:bg-slate-50">
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900">{{ $subject->id }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $subject->name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                <span class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    {{ $subject->category->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $subject->slug }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <button 
                                    wire:click="toggleActive({{ $subject->id }})"
                                    class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1 text-xs font-medium transition {{ $subject->is_active ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                                >
                                    @if($subject->is_active)
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Hoạt động
                                    @else
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Tắt
                                    @endif
                                </button>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">{{ $subject->created_at->format('d/m/Y') }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                <button 
                                    wire:click="openDetailModal({{ $subject->id }})"
                                    class="inline-flex items-center gap-1 rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 transition hover:bg-blue-100"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Chi tiết
                                </button>
                                <button 
                                    wire:click="openEditModal({{ $subject->id }})"
                                    class="ml-2 inline-flex items-center gap-1 rounded-lg bg-amber-50 px-3 py-1.5 text-xs font-medium text-amber-700 transition hover:bg-amber-100"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Sửa
                                </button>
                                <button 
                                    wire:click="confirmDelete({{ $subject->id }})"
                                    class="ml-2 inline-flex items-center gap-1 rounded-lg bg-rose-50 px-3 py-1.5 text-xs font-medium text-rose-700 transition hover:bg-rose-100"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Xóa
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-500">
                                Không tìm thấy môn học nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Phân trang --}}
    <div class="mt-6">
        {{ $subjects->links() }}
    </div>

    {{-- Modal tạo mới và chỉnh sửa môn học --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:p-0">
                {{-- Overlay nền tối với hiệu ứng blur --}}
                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>

                <div class="relative inline-block transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                    <div class="bg-white px-6 pt-6 pb-4">
                        {{-- Header modal --}}
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-semibold text-slate-900">
                                {{ $modalMode === 'create' ? 'Thêm Môn Học Mới' : 'Chỉnh Sửa Môn Học' }}
                            </h3>
                            <button wire:click="closeModal" class="rounded-lg p-1 text-slate-400 transition hover:bg-slate-100 hover:text-slate-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Form tạo/sửa môn học --}}
                        <form wire:submit="save" class="space-y-4">
                            {{-- Trường nhập tên môn học --}}
                            <div>
                                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">
                                    Tên Môn Học <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="name"
                                    wire:model.live="name"
                                    class="w-full rounded-lg border-slate-200 px-4 py-2.5 text-sm shadow-sm transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 @error('name') border-rose-500 @enderror"
                                    placeholder="Ví dụ: Lập trình Web, Cấu trúc dữ liệu..."
                                >
                                @error('name')
                                    <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Dropdown chọn danh mục --}}
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-slate-700 mb-1">
                                    Danh Mục <span class="text-rose-500">*</span>
                                </label>
                                <select 
                                    id="category_id"
                                    wire:model="category_id"
                                    class="w-full rounded-lg border-slate-200 px-4 py-2.5 text-sm shadow-sm transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 @error('category_id') border-rose-500 @enderror"
                                >
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Toggle trạng thái hoạt động --}}
                            <div class="flex items-center justify-between rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                                <div class="flex-1">
                                    <label for="is_active" class="block text-sm font-medium text-slate-700">
                                        Trạng Thái Hoạt Động
                                    </label>
                                    <p class="text-xs text-slate-500 mt-0.5">Bật để môn học hiển thị trên hệ thống</p>
                                </div>
                                <button
                                    type="button"
                                    wire:click="$toggle('is_active')"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 {{ $is_active ? 'bg-blue-600' : 'bg-slate-300' }}"
                                    role="switch"
                                    aria-checked="{{ $is_active ? 'true' : 'false' }}"
                                >
                                    <span class="translate-x-0 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                            </div>

                            {{-- Footer với các nút hành động --}}
                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                                <button 
                                    type="button"
                                    wire:click="closeModal"
                                    class="rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50"
                                >
                                    Hủy
                                </button>
                                <button
                                    type="submit"
                                    wire:loading.attr="disabled"
                                    class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-70 focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                                >
                                    {{-- Text hiển thị khi không loading --}}
                                    <span wire:loading.remove>
                                        {{ $modalMode === 'create' ? 'Tạo Môn Học' : 'Cập Nhật' }}
                                    </span>

                                    {{-- Loading spinner --}}
                                    <span wire:loading class="flex items-center gap-2">
                                        <svg
                                            class="h-4 w-4 animate-spin"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <circle
                                                class="opacity-25"
                                                cx="12"
                                                cy="12"
                                                r="10"
                                                stroke="currentColor"
                                                stroke-width="4"
                                            ></circle>
                                            <path
                                                class="opacity-75"
                                                fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                                            ></path>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal xác nhận xóa môn học --}}
    @if($confirmDeleteId)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:p-0">
                {{-- Overlay nền tối với hiệu ứng blur --}}
                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" wire:click="$set('confirmDeleteId', null)"></div>

                <div class="relative inline-block transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md sm:align-middle">
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="flex items-start gap-4">
                            {{-- Icon cảnh báo --}}
                            <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-rose-100">
                                <svg class="h-6 w-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 mb-2">Xác Nhận Xóa</h3>
                                <p class="text-sm text-slate-600">
                                    Bạn có chắc chắn muốn xóa môn học <span class="font-semibold text-rose-600">{{ $name }}</span>? 
                                    Hành động này không thể hoàn tác và sẽ ảnh hưởng đến các tài liệu và đề thi liên quan.
                                </p>
                            </div>
                        </div>

                        {{-- Footer với các nút hành động --}}
                        <div class="mt-6 flex items-center justify-end gap-3">
                            <button 
                                wire:click="$set('confirmDeleteId', null)"
                                class="rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50"
                            >
                                Hủy
                            </button>
                            <button
                                wire:click="delete"
                                wire:loading.attr="disabled"
                                wire:target="delete"
                                class="rounded-lg bg-rose-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-rose-700 disabled:cursor-not-allowed disabled:opacity-70 focus:outline-none focus:ring-2 focus:ring-rose-500/50"
                            >
                                {{-- Text hiển thị khi không loading --}}
                                <span wire:loading.remove wire:target="delete">
                                    Xóa Môn Học
                                </span>

                                {{-- Loading spinner --}}
                                <span wire:loading wire:target="delete" class="flex items-center gap-2">
                                    <svg
                                        class="h-4 w-4 animate-spin"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <circle
                                            class="opacity-25"
                                            cx="12"
                                            cy="12"
                                            r="10"
                                            stroke="currentColor"
                                            stroke-width="4"
                                        ></circle>
                                        <path
                                            class="opacity-75"
                                            fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                                        ></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal xem chi tiết liên kết môn học --}}
    @if($showDetailModal && $detailSubject)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:p-0">
                {{-- Overlay nền tối với hiệu ứng blur --}}
                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" wire:click="closeDetailModal"></div>

                <div class="relative inline-block transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl sm:align-middle">
                    <div class="bg-white px-6 pt-6 pb-4">
                        {{-- Header modal --}}
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-semibold text-slate-900">Chi Tiết Môn Học: {{ $detailSubject->name }}</h3>
                                <div class="flex items-center gap-3 mt-2">
                                    <p class="text-sm text-slate-500">Slug: {{ $detailSubject->slug }}</p>
                                    <span class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1 text-xs font-medium {{ $detailSubject->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $detailSubject->is_active ? 'Đang hoạt động' : 'Đã tắt' }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700">
                                        {{ $detailSubject->category->name ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                            <button wire:click="closeDetailModal" class="rounded-lg p-1 text-slate-400 transition hover:bg-slate-100 hover:text-slate-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Thống kê tổng quan --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            {{-- Thống kê tài liệu --}}
                            <div class="rounded-xl border border-blue-200 bg-blue-50 p-4">
                                <div class="flex items-center gap-3">
                                    <div class="rounded-lg bg-blue-100 p-2">
                                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold text-blue-900">{{ $detailSubject->documents_count }}</p>
                                        <p class="text-sm text-blue-700">Tài liệu</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Thống kê đề thi --}}
                            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                                <div class="flex items-center gap-3">
                                    <div class="rounded-lg bg-emerald-100 p-2">
                                        <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold text-emerald-900">{{ $detailSubject->exams_count }}</p>
                                        <p class="text-sm text-emerald-700">Đề thi</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Chi tiết liên kết --}}
                        <div class="max-h-96 overflow-y-auto space-y-6">
                            {{-- Thông báo nếu không có liên kết --}}
                            @if($detailSubject->documents_count === 0 && $detailSubject->exams_count === 0)
                                <div class="py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p class="mt-4 text-sm text-slate-500">Môn học này chưa có tài liệu hoặc đề thi nào</p>
                                </div>
                            @else
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-sm text-slate-600">
                                        Môn học này đang có <span class="font-semibold text-blue-700">{{ $detailSubject->documents_count }} tài liệu</span> 
                                        và <span class="font-semibold text-emerald-700">{{ $detailSubject->exams_count }} đề thi</span> liên kết.
                                    </p>
                                </div>
                            @endif
                        </div>

                        {{-- Footer --}}
                        <div class="mt-6 flex justify-end border-t border-slate-200 pt-4">
                            <button 
                                wire:click="closeDetailModal"
                                class="rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50"
                            >
                                Đóng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
