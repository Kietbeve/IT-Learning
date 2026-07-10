<div id="admin-category-list"
     x-data="{ notification: null, showFormModal: @entangle('isFormOpen') }" 
     x-on:notify.window="notification = $event.detail; setTimeout(() => notification = null, 3000)"
     class="space-y-6">



    <div class="grid gap-4 md:gap-6 grid-cols-1 md:grid-cols-3">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-600">Tổng danh mục</p>
                <span class="rounded-xl bg-gray-50 p-2">
                    <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($totalCount) }}</p>
            <p class="mt-1 text-sm text-gray-500">Danh mục tài nguyên</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-600">Đang hoạt động</p>
                <span class="rounded-xl bg-green-50 p-2">
                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($activeCount) }}</p>
            <p class="mt-1 text-sm text-gray-500">Khả dụng đăng tải</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-600">Tạm khóa</p>
                <span class="rounded-xl bg-amber-50 p-2">
                    <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-bold text-gray-900">{{ number_format($inactiveCount) }}</p>
            <p class="mt-1 text-sm text-gray-500">Ẩn trên bộ lọc</p>
        </div>
    </div>

    {{-- Thanh tìm kiếm và bộ lọc --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex-1 max-w-2xl flex flex-col sm:flex-row items-center gap-3">
            <select wire:model.live="statusFilter" aria-label="Bộ lọc trạng thái" class="w-full sm:w-auto rounded-xl border-0 py-2.5 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all">
                <option value="all">Tất cả trạng thái</option>
                <option value="active">Đang hoạt động</option>
                <option value="inactive">Tạm dừng</option>
            </select>

            <div class="relative w-full sm:max-w-md">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" 
                       id="search-admin-categories"
                       aria-label="Tìm kiếm danh mục"
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Tìm kiếm danh mục..." 
                       class="block w-full rounded-xl border-0 py-2.5 pl-10 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all" />
                <div wire:loading.flex wire:target="search" class="absolute inset-y-0 right-3 items-center">
                    <svg class="h-4 w-4 animate-spin text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <button type="button" 
                wire:click="openCreateModal"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Thêm Danh Mục Mới
        </button>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow ring-1 ring-gray-200">

        <div wire:loading.class="opacity-60 transition-opacity duration-200" class="transition-opacity duration-200">
            <div class="block md:hidden divide-y divide-slate-100">
            @forelse($categories as $cat)
                <div class="p-4 space-y-3">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1 flex-1 min-w-0">
                            <span class="font-bold text-gray-950 block leading-tight text-base truncate">{{ $cat->name }}</span>
                            <div class="text-[10px] font-mono text-gray-400 truncate">Đường dẫn: /documents?category={{ $cat->slug }}</div>
                        </div>
                        <span class="inline-flex items-center justify-center rounded-xl bg-gray-100 px-2.5 py-1 text-xs font-bold text-gray-700 shrink-0">
                            Thứ tự: {{ $cat->sort_order }}
                        </span>
                    </div>
                    @if($cat->description)
                        <p class="text-xs text-gray-500 line-clamp-2">{{ $cat->description }}</p>
                    @endif
                    <div class="flex items-center justify-between gap-4 pt-2">
                        <button wire:click="toggleStatus({{ $cat->id }})" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors {{ $cat->is_active ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $cat->is_active ? 'bg-emerald-600' : 'bg-rose-600' }}"></span>
                            {{ $cat->is_active ? 'Hoạt động' : 'Tạm khóa' }}
                        </button>
                        <div class="flex items-center gap-2">
                            <button wire:click="editCategory({{ $cat->id }})" 
                                    class="inline-flex rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 text-xs font-bold transition-all shadow-sm">
                                Sửa
                            </button>
                            <button wire:click="confirmDelete({{ $cat->id }})" 
                                    class="inline-flex rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 px-3 py-1.5 text-xs font-bold transition-all shadow-sm">
                                Xóa
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-400">
                    Không tìm thấy danh mục nào.
                </div>
            @endforelse
            </div>

            <div class="hidden md:block overflow-x-auto">
                <table class="w-full table-fixed text-left divide-y divide-gray-200">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-gray-900 w-[40%] cursor-pointer hover:bg-gray-100 transition-colors" wire:click="sortBy('name')">
                                Danh mục
                                @if($sortField === 'name')
                                    <span class="ml-1 text-[10px]">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                @endif
                            </th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-[20%] cursor-pointer hover:bg-gray-100 transition-colors" wire:click="sortBy('sort_order')">
                                Thứ tự ưu tiên
                                @if($sortField === 'sort_order')
                                    <span class="ml-1 text-[10px]">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                @endif
                            </th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-[20%]">Trạng thái</th>
                            <th class="relative py-3.5 pl-3 pr-6 text-right text-sm font-semibold text-gray-900 w-[20%]">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($categories as $cat)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-4 pl-6 pr-3">
                                    <div class="space-y-1">
                                        <span class="font-bold text-gray-950 block leading-tight text-base whitespace-normal break-words">{{ $cat->name }}</span>
                                        <div class="text-[10px] font-mono text-gray-400 whitespace-normal break-all">Đường dẫn: /documents?category={{ $cat->slug }}</div>
                                        @if($cat->description)
                                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $cat->description }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center rounded-xl bg-gray-100 px-3 py-1.5 font-bold text-gray-700">
                                        {{ $cat->sort_order }}
                                    </span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <button wire:click="toggleStatus({{ $cat->id }})" 
                                            class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1 text-xs font-medium transition ring-1 ring-inset {{ $cat->is_active ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 hover:bg-emerald-100' : 'bg-gray-50 text-gray-600 ring-gray-500/20 hover:bg-gray-100' }}">
                                        @if($cat->is_active)
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
                                <td class="relative py-4 pl-3 pr-6 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button wire:click="editCategory({{ $cat->id }})" 
                                                class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-amber-700 shadow-sm ring-1 ring-inset ring-amber-600/20 hover:bg-amber-50 transition-colors">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Sửa
                                        </button>
                                        <button wire:click="confirmDelete({{ $cat->id }})" 
                                                class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-rose-600 shadow-sm ring-1 ring-inset ring-rose-300 hover:bg-rose-50 transition-colors">
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
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    Không tìm thấy danh mục nào.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($categories->hasPages())
            <div class="border-t border-gray-200 px-4 py-3">
                {{ $categories->links(data: ['scrollTo' => '#admin-category-list']) }}
            </div>
        @endif
    </div>

    <div x-show="showFormModal" 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" 
         style="display: none;"
         x-transition>
        <div class="bg-white rounded-3xl max-w-lg w-full border border-gray-200 shadow-2xl p-6 space-y-6" @click.away="showFormModal = false">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-lg font-bold text-gray-900">
                    {{ $categoryId ? 'Cập nhật danh mục' : 'Thêm danh mục tài liệu mới' }}
                </h3>
                <button @click="showFormModal = false" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            
            <form wire:submit.prevent="saveCategory" class="space-y-4">
                <div class="space-y-1">
                    <label for="cat-name" class="block text-sm font-semibold text-gray-700">Tên danh mục <span class="text-red-500">*</span></label>
                    <input id="cat-name"
                           type="text" 
                           wire:model.live="name"
                           placeholder="Ví dụ: Lập trình PHP, Thiết kế đồ họa..." 
                           class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:border-gray-400 focus:outline-none" />
                    @error('name')
                        <span class="text-xs text-red-500 font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label for="cat-sort-order" class="block text-sm font-semibold text-gray-700">Thứ tự sắp xếp ưu tiên</label>
                    <input id="cat-sort-order"
                           type="number" 
                           wire:model="sort_order"
                           min="0"
                           class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:border-gray-400 focus:outline-none" />
                    @error('sort_order')
                        <span class="text-xs text-red-500 font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <div class="space-y-1">
                    <label for="cat-description" class="block text-sm font-semibold text-gray-700">Mô tả danh mục</label>
                    <textarea id="cat-description"
                              wire:model="description" 
                              rows="3" 
                              placeholder="Mô tả tóm tắt về các tài liệu thuộc danh mục này..."
                              class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:border-gray-400 focus:outline-none"></textarea>
                    @error('description')
                        <span class="text-xs text-red-500 font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center gap-3 py-2">
                    <input id="cat-is-active"
                           type="checkbox" 
                           wire:model="is_active" 
                           class="h-4.5 w-4.5 rounded border-gray-300 text-gray-900 focus:ring-gray-500" />
                    <label for="cat-is-active" class="text-sm font-semibold text-gray-700 cursor-pointer">Kích hoạt hoạt động ngay</label>
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-gray-100">
                    <button type="button" @click="showFormModal = false" class="rounded-xl border border-gray-200 hover:bg-gray-50 px-4 py-2.5 text-xs font-semibold text-gray-700 transition-colors">
                        Hủy bỏ
                    </button>
                    <button type="submit" class="rounded-xl bg-gray-900 hover:bg-gray-800 text-white px-5 py-2.5 text-xs font-semibold shadow-sm transition-colors">
                        Xác nhận lưu
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <template x-teleport="body">
        <div x-data="{ show: @entangle('confirmDeleteId').live }" x-show="show" class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4 sm:p-0" style="display: none;">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="$set('confirmDeleteId', null)"></div>

            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-rose-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-rose-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Xóa danh mục</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Bạn có chắc chắn muốn xóa danh mục này? Tất cả dữ liệu liên quan sẽ bị ảnh hưởng. Thao tác này không thể hoàn tác.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                    <button type="button" wire:click="delete" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2.5 bg-rose-600 text-base font-semibold text-white hover:bg-rose-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 sm:w-auto sm:text-sm">Xóa danh mục</button>
                    <button type="button" wire:click="$set('confirmDeleteId', null)" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2.5 bg-white text-base font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Hủy bỏ</button>
                </div>
            </div>
        </div>
    </template>
</div>
