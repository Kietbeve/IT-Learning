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

    <section class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-gray-50/50">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Danh mục tài liệu</h2>
                <p class="text-sm text-gray-500 mt-1">Quản lý các danh mục phân loại tài liệu học tập trong hệ thống.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">
                <select wire:model.live="statusFilter" aria-label="Bộ lọc trạng thái" class="w-full sm:w-auto rounded-2xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-gray-400 focus:outline-none">
                    <option value="all">Tất cả trạng thái</option>
                    <option value="active">Đang hoạt động</option>
                    <option value="inactive">Tạm dừng</option>
                </select>

                <div class="relative flex-1 sm:w-64 sm:flex-initial">
                    <input type="search" 
                           id="search-admin-categories"
                           aria-label="Tìm kiếm danh mục"
                           wire:model.live.debounce.300ms="search" 
                           placeholder="Tìm kiếm danh mục..." 
                           class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-2.5 pl-10 text-sm text-gray-900 focus:border-gray-400 focus:outline-none" />
                    <span class="absolute inset-y-0 left-3.5 inline-flex items-center text-gray-400">
                        <svg viewBox="0 0 24 24" class="h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8" /><path d="M21 21l-4.35-4.35" /></svg>
                    </span>
                </div>

                <button type="button" 
                        wire:click="openCreateModal"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gray-900 hover:bg-gray-800 text-white px-4 py-2.5 text-sm font-semibold transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Thêm danh mục
                </button>
            </div>
        </div>

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
                            <button wire:confirm="Bạn có chắc chắn muốn xóa danh mục này? Thao tác không thể hoàn tác."
                                    wire:click="deleteCategory({{ $cat->id }})" 
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
                <table class="w-full text-left border-collapse min-w-full">
                    <thead>
                        <tr class="border-b border-gray-200 text-xs font-semibold uppercase tracking-wider text-gray-500 bg-gray-50/70">
                            <th class="px-6 py-4 cursor-pointer hover:bg-gray-100 transition-colors" wire:click="sortBy('name')">
                                Danh mục
                                @if($sortField === 'name')
                                    <span class="ml-1 text-[10px]">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                @endif
                            </th>
                            <th class="px-6 py-4 cursor-pointer hover:bg-gray-100 transition-colors" wire:click="sortBy('sort_order')">
                                Thứ tự ưu tiên
                                @if($sortField === 'sort_order')
                                    <span class="ml-1 text-[10px]">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                @endif
                            </th>
                            <th class="px-6 py-4">Trạng thái</th>
                            <th class="px-6 py-4 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-gray-700">
                        @forelse($categories as $cat)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="space-y-1 max-w-sm sm:max-w-md lg:max-w-lg">
                                        <span class="font-bold text-gray-950 block leading-tight text-base">{{ $cat->name }}</span>
                                        <div class="text-[10px] font-mono text-gray-400">Đường dẫn: /documents?category={{ $cat->slug }}</div>
                                        @if($cat->description)
                                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $cat->description }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center rounded-xl bg-gray-100 px-3 py-1.5 font-bold text-gray-700">
                                        {{ $cat->sort_order }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button wire:click="toggleStatus({{ $cat->id }})" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-colors {{ $cat->is_active ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $cat->is_active ? 'bg-emerald-600' : 'bg-rose-600' }}"></span>
                                        {{ $cat->is_active ? 'Hoạt động' : 'Tạm khóa' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <button wire:click="editCategory({{ $cat->id }})" 
                                                class="inline-flex rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 px-3.5 py-2 text-xs font-bold transition-all shadow-sm">
                                            Sửa
                                        </button>
                                        <button wire:confirm="Bạn có chắc chắn muốn xóa danh mục này? Thao tác không thể hoàn tác."
                                                wire:click="deleteCategory({{ $cat->id }})" 
                                                class="inline-flex rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 px-3.5 py-2 text-xs font-bold transition-all shadow-sm">
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
            <div class="p-6 border-t border-gray-200 bg-gray-50/50">
                {{ $categories->links(data: ['scrollTo' => '#admin-category-list']) }}
            </div>
        @endif
    </section>

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
</div>
