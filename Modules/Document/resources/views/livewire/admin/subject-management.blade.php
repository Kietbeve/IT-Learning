<div class="space-y-6 py-4">

    {{-- Header - Thanh tìm kiếm và nút thêm mới --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex-1 max-w-md relative">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                </svg>
            </div>
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search"
                placeholder="Tìm kiếm môn học, danh mục..."
                class="block w-full rounded-xl border-0 py-2.5 pl-10 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all"
            >
            <div wire:loading.flex wire:target="search" class="absolute inset-y-0 right-3 items-center">
                <svg class="h-4 w-4 animate-spin text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
            </div>
        </div>
        <button 
            wire:click="openCreateModal"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all shrink-0"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Thêm Môn Học Mới
        </button>
    </div>

    {{-- Bảng danh sách môn học --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow ring-1 ring-gray-200">
        <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity duration-200">
            <div class="overflow-x-auto">
                <table class="min-w-[900px] w-full divide-y divide-gray-200">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-gray-900">ID</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tên Môn Học</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Danh Mục</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Slug</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Trạng Thái</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Ngày Tạo</th>
                            <th scope="col" class="py-3.5 pl-3 pr-6 text-center text-sm font-semibold text-gray-900">Thao Tác</th>
                        </tr>
                </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($subjects as $subject)
                            <tr class="transition hover:bg-gray-50/50">
                                <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-mono text-gray-900">{{ $subject->id }}</td>
                                <td class="px-3 py-4 text-sm font-medium text-gray-900">
                                    <span class="inline-block whitespace-normal break-words rounded-md bg-blue-50 px-2.5 py-1 text-sm font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">
                                        {{ $subject->name }}
                                    </span>
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-600">
                                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-600/20">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        {{ $subject->category->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-600">
                                    <div class="whitespace-normal break-all">
                                        {{ $subject->slug }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    <button 
                                        wire:click="toggleActive({{ $subject->id }})"
                                        class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1 text-xs font-medium transition ring-1 ring-inset {{ $subject->is_active ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 hover:bg-emerald-100' : 'bg-gray-50 text-gray-600 ring-gray-500/20 hover:bg-gray-100' }}"
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
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <div class="text-gray-900">{{ $subject->created_at->format('d/m/Y') }}</div>
                                    <div class="text-xs">{{ $subject->created_at->format('H:i') }}</div>
                                </td>
                                <td class="whitespace-nowrap py-4 pl-3 pr-6 text-center text-sm font-medium">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button 
                                            wire:click="openEditModal({{ $subject->id }})"
                                            class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-amber-700 shadow-sm ring-1 ring-inset ring-amber-600/20 hover:bg-amber-50 transition-colors"
                                        >
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Sửa
                                        </button>
                                        <button 
                                            wire:click="confirmDelete({{ $subject->id }})"
                                            class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-rose-600 shadow-sm ring-1 ring-inset ring-rose-300 hover:bg-rose-50 transition-colors"
                                        >
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Xóa
                                        </button>
                                    </div>
                                </td>
                            </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 px-4 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-semibold text-gray-900">Không có dữ liệu</h3>
                                <p class="mt-1 text-sm text-gray-500">Không tìm thấy môn học nào khớp với tìm kiếm.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($subjects->hasPages())
            <div class="border-t border-gray-200 px-4 py-3">
                {{ $subjects->links() }}
            </div>
        @endif
    </div>

    {{-- Phân trang --}}
    {{-- Phân trang (Đã được chuyển vào trong bảng) --}}

    {{-- Modal tạo mới và chỉnh sửa môn học --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:p-0">
                {{-- Overlay nền tối với hiệu ứng blur --}}
                <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>

                <div class="relative inline-block transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                    <div class="bg-white px-6 pt-6 pb-4">
                        {{-- Header modal --}}
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-semibold text-gray-900">
                                {{ $modalMode === 'create' ? 'Thêm Môn Học Mới' : 'Chỉnh Sửa Môn Học' }}
                            </h3>
                            <button wire:click="closeModal" class="rounded-lg p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Form tạo/sửa môn học --}}
                        <form wire:submit="save" class="space-y-4">
                            {{-- Trường nhập tên môn học --}}
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tên Môn Học <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="name"
                                    wire:model.live="name"
                                    class="w-full rounded-lg border-gray-200 px-4 py-2.5 text-sm shadow-sm transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 @error('name') border-rose-500 @enderror"
                                    placeholder="Ví dụ: Lập trình Web, Cấu trúc dữ liệu..."
                                >
                                @error('name')
                                    <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Dropdown chọn danh mục --}}
                            <div x-data="{ open: false }">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Danh Mục <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative w-full" @click.away="open = false">
                                    <div @click="open = !open" 
                                         class="flex items-center justify-between w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm bg-white text-gray-900 cursor-pointer shadow-sm hover:border-blue-400 transition-all focus:ring-2 focus:ring-blue-500/20 @error('category_id') border-rose-500 @enderror">
                                        <span class="truncate">
                                            @if($category_id)
                                                {{ collect($categories)->firstWhere('id', $category_id)?->name ?? '-- Chọn danh mục --' }}
                                            @else
                                                <span class="text-gray-400">-- Chọn danh mục --</span>
                                            @endif
                                        </span>
                                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                    <div x-show="open" x-cloak
                                         class="absolute z-50 w-full mt-1 bg-white rounded-lg shadow-lg border border-gray-100 overflow-hidden"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95">
                                        <div class="max-h-48 overflow-y-auto py-1">
                                            <div wire:click="$set('category_id', ''); open = false"
                                                 class="px-4 py-2 text-sm cursor-pointer hover:bg-gray-50 transition-colors text-gray-400">
                                                -- Chọn danh mục --
                                            </div>
                                            @foreach($categories as $category)
                                                <div wire:click="$set('category_id', '{{ $category->id }}'); open = false"
                                                     class="px-4 py-2 text-sm cursor-pointer hover:bg-blue-50 transition-colors {{ $category_id == $category->id ? 'text-blue-600 font-medium bg-blue-50' : 'text-gray-700' }}">
                                                    {{ $category->name }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @error('category_id')
                                    <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Toggle trạng thái hoạt động --}}
                            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50 px-4 py-3">
                                <div class="flex-1">
                                    <label for="is_active" class="block text-sm font-medium text-gray-700">
                                        Trạng Thái Hoạt Động
                                    </label>
                                    <p class="text-xs text-gray-500 mt-0.5">Bật để môn học hiển thị trên hệ thống</p>
                                </div>
                                <button
                                    type="button"
                                    wire:click="$toggle('is_active')"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 {{ $is_active ? 'bg-blue-600' : 'bg-gray-300' }}"
                                    role="switch"
                                    aria-checked="{{ $is_active ? 'true' : 'false' }}"
                                >
                                    <span class="translate-x-0 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                            </div>

                            {{-- Footer với các nút hành động --}}
                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                                <button 
                                    type="button"
                                    wire:click="closeModal"
                                    class="rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50"
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
                <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" wire:click="$set('confirmDeleteId', null)"></div>

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
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Xác Nhận Xóa</h3>
                                <p class="text-sm text-gray-600">
                                    Bạn có chắc chắn muốn xóa môn học <span class="font-semibold text-rose-600">{{ $name }}</span>? 
                                    Hành động này không thể hoàn tác và sẽ ảnh hưởng đến các tài liệu và đề thi liên quan.
                                </p>
                            </div>
                        </div>

                        {{-- Footer với các nút hành động --}}
                        <div class="mt-6 flex items-center justify-end gap-3">
                            <button 
                                wire:click="$set('confirmDeleteId', null)"
                                class="rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50"
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

    {{-- Modal Chi tiết Môn học --}}
    @if($showDetailModal && $detailSubject)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:p-0">
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

                        </div>

                        {{-- Chi tiết liên kết --}}
                        <div class="max-h-96 overflow-y-auto space-y-6">
                            {{-- Thông báo nếu không có liên kết --}}
                            @if($detailSubject->documents_count === 0)
                                <div class="py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p class="mt-4 text-sm text-slate-500">Môn học này chưa có tài liệu nào</p>
                                </div>
                            @else
                                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-sm text-slate-600">
                                        Môn học này đang có <span class="font-semibold text-blue-700">{{ $detailSubject->documents_count }} tài liệu</span> liên kết.
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

