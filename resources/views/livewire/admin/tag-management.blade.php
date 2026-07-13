<div class="space-y-6 py-4">

    {{-- Thanh tìm kiếm và nút thêm --}}
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
                placeholder="Tìm kiếm tags..."
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
            Thêm Tag Mới
        </button>
    </div>

    {{-- Bảng --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow ring-1 ring-gray-200">
        <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity duration-200">
            <div class="overflow-x-auto">
                <table class="w-full table-fixed divide-y divide-gray-200">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-gray-900 w-[8%]">ID</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-[25%]">Tên Tag</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-[25%]">Slug</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-[15%]">Ngày Tạo</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-6 text-right text-sm font-semibold text-gray-900 w-[27%]">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($tags as $tag)
                            <tr class="transition hover:bg-gray-50/50">
                                <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-mono text-gray-900">{{ $tag->id }}</td>
                                <td class="px-3 py-4 text-sm font-medium text-gray-900">
                                    <span class="inline-block whitespace-normal break-words rounded-md bg-blue-50 px-2.5 py-1 text-sm font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">
                                        {{ $tag->name }}
                                    </span>
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-600">
                                    <div class="whitespace-normal break-all">
                                        {{ $tag->slug }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <div class="text-gray-900">{{ $tag->created_at->format('d/m/Y') }}</div>
                                    <div class="text-xs">{{ $tag->created_at->format('H:i') }}</div>
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button 
                                            wire:click="openEditModal({{ $tag->id }})"
                                            class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-amber-700 shadow-sm ring-1 ring-inset ring-amber-600/20 hover:bg-amber-50 transition-colors"
                                        >
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Sửa
                                        </button>
                                        <button 
                                            wire:click="confirmDelete({{ $tag->id }})"
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
                                <td colspan="5" class="py-12 px-4 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-semibold text-gray-900">Không có dữ liệu</h3>
                                    <p class="mt-1 text-sm text-gray-500">Không tìm thấy tag nào khớp với tìm kiếm.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($tags->hasPages())
            <div class="border-t border-gray-200 px-4 py-3">
                {{ $tags->links() }}
            </div>
        @endif
    </div>

    {{-- Modal tạo/chỉnh sửa --}}
    <div x-data="{ showModal: @entangle('showModal') }" x-effect="document.body.style.overflow = showModal ? 'hidden' : ''">
        <template x-teleport="body">
            <div x-show="showModal" class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4 sm:p-0" style="display: none;">
                <div x-show="showModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="$wire.closeModal()"></div>

                <div x-show="showModal"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-2xl bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">

                    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                        <h3 class="text-lg font-bold leading-6 text-gray-900">
                            {{ $modalMode === 'create' ? 'Thêm Tag Mới' : 'Chỉnh Sửa Tag' }}
                        </h3>
                        <button @click="$wire.closeModal()" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-500 transition-colors">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </button>
                    </div>

                    <form wire:submit="save" class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Tên Tag <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="name"
                                wire:model.live="name"
                                class="block w-full rounded-xl border-0 py-2.5 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all @error('name') ring-rose-500 @enderror"
                                placeholder="Nhập tên tag..."
                            >
                            @error('name')
                                <p class="mt-1.5 text-sm text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Slug tự động tạo --}}
                        <div class="hidden">
                            <input type="text" wire:model="slug">
                        </div>

                        <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 border-t border-gray-100 pt-4">
                            <button 
                                type="button"
                                @click="$wire.closeModal()"
                                class="inline-flex w-full sm:w-auto justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors"
                            >
                                Hủy bỏ
                            </button>
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="inline-flex w-full sm:w-auto justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                <span wire:loading.remove>
                                    {{ $modalMode === 'create' ? 'Tạo Tag' : 'Cập Nhật' }}
                                </span>
                                <span wire:loading class="flex items-center gap-2">
                                    <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>

    {{-- Modal xác nhận xóa --}}
    <div x-data="{ confirmDeleteId: @entangle('confirmDeleteId') }" x-effect="document.body.style.overflow = confirmDeleteId ? 'hidden' : ''">
        <template x-teleport="body">
            <div x-show="confirmDeleteId" class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4 sm:p-0" style="display: none;">
                <div x-show="confirmDeleteId" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="$wire.$set('confirmDeleteId', null)"></div>

                <div x-show="confirmDeleteId"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-2xl bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md sm:p-6">

                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-rose-100">
                            <svg class="h-6 w-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold leading-6 text-gray-900">Xác Nhận Xóa</h3>
                            <p class="mt-2 text-sm text-gray-600">
                                Bạn có chắc chắn muốn xóa tag <span class="font-semibold text-rose-600">{{ $name }}</span>? Hành động này không thể hoàn tác.
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <button 
                            @click="$wire.$set('confirmDeleteId', null)"
                            class="inline-flex w-full sm:w-auto justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors"
                        >
                            Hủy bỏ
                        </button>
                        <button
                            wire:click="delete"
                            wire:loading.attr="disabled"
                            wire:target="delete"
                            class="inline-flex w-full sm:w-auto justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-rose-600 shadow-sm ring-1 ring-inset ring-rose-300 hover:bg-rose-50 transition-colors disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <span wire:loading.remove wire:target="delete">Xóa Tag</span>
                            <span wire:loading wire:target="delete" class="flex items-center gap-2">
                                <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>