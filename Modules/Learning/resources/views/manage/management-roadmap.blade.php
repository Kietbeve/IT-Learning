<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <div class="mb-4 flex items-center gap-2 text-xs font-semibold text-slate-400">
        <a href="/manage" class="hover:text-blue-500 transition-colors">Bảng điều khiển</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        <span class="text-slate-300">Quản lý lộ trình</span>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold rounded-2xl flex items-center gap-2 animate-fade-in">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-xl shadow-blue-500/5 border border-blue-50/80 overflow-hidden">
        
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-gradient-to-r from-slate-50/50 to-white">
            <div>
                <h2 class="text-lg font-black text-slate-900 tracking-tight">Cơ Sở Dữ Liệu Lộ Trình</h2>
                <p class="text-xs text-slate-500 font-medium">Hệ thống đang đồng bộ trực tiếp với database thực tế.</p>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative">
                    <input type="text" wire:model.live="search" placeholder="Tìm tiêu đề lộ trình..." class="w-full sm:w-60 pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.604 10.604z"/></svg>
                </div>

                <button wire:click="openCreateForm" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Tạo Lộ Trình Mới
                </button>
            </div>
        </div>

        @if($isOpenForm)
        <div class="p-6 bg-blue-50/40 border-b border-blue-100/60 animate-fade-in">
            <h3 class="text-xs font-black text-blue-600 uppercase tracking-wider mb-4">
                {{ $isEditMode ? '🛠️ Cập nhật thông tin lộ trình' : '✨ Khởi tạo dữ liệu lộ trình mới' }}
            </h3>
            
            <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Tiêu đề lộ trình (Title) *</label>
                    <input type="text" wire:model.live="title" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-blue-500">
                    @error('title') <span class="text-[10px] text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Đường dẫn định danh (Slug)</label>
                    <input type="text" wire:model="slug" class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-medium text-slate-500 focus:outline-none" readonly>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Mô tả ngắn (Short Description)</label>
                    <input type="text" wire:model.live="short_description" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-blue-500">
                    @error('short_description') <span class="text-[10px] text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Nội dung chi tiết (Description)</label>
                    <textarea wire:model="description" rows="3" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-blue-500"></textarea>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Cấp độ (Level)</label>
                    <select wire:model="level" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-blue-500">
                        <option value="beginner">Nhập môn (Beginner)</option>
                        <option value="intermediate">Trung cấp (Intermediate)</option>
                        <option value="advanced">Chuyên sâu (Advanced)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Chế độ hiển thị (Visibility)</label>
                    <select wire:model="visibility" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-blue-500">
                        <option value="public">Công khai công chúng</option>
                        <option value="private">Nội bộ hệ thống</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Trạng thái phê duyệt (Status)</label>
                    <select wire:model="status" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-blue-500">
                        <option value="draft">Bản nháp (Draft)</option>
                        <option value="pending">Chờ xét duyệt (Pending)</option>
                        <option value="published">Đã xuất bản (Published)</option>
                    </select>
                </div>

                <div class="md:col-span-2 flex items-center justify-end gap-2 mt-2">
                    <button type="button" wire:click="closeForm" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition-colors">Hủy</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-500/10 transition-colors">Lưu Dữ Liệu</button>
                </div>
            </form>
        </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider w-24">Public ID</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Lộ Trình Đào Tạo</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider w-28">Cấp Độ</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider w-32">Trạng Thái</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider w-28 text-center">Hành Động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($roadmaps as $roadmap)
                    <tr class="hover:bg-slate-50/60 transition-colors group">
                        <td class="px-6 py-4 text-xs font-mono font-bold text-slate-400">{{ $roadmap->public_id }}</td>
                        <td class="px-6 py-4">
                            <span class="block text-xs font-black text-slate-800 group-hover:text-blue-600 transition-colors">{{ $roadmap->title }}</span>
                            <span class="block text-[11px] text-slate-400 font-medium mt-0.5 max-w-md truncate">{{ $roadmap->short_description ?? 'Chưa cấu hình mô tả ngắn.' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[10px] font-bold capitalize">
                                {{ $roadmap->level }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($roadmap->status === 'published')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold">
                                    <span class="w-1 h-1 rounded-full bg-emerald-500"></span> Published
                                </span>
                            @elseif($roadmap->status === 'pending')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-600 text-[10px] font-bold">
                                    <span class="w-1 h-1 rounded-full bg-amber-500"></span> Pending
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold">
                                    <span class="w-1 h-1 rounded-full bg-slate-400"></span> Draft
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-1.5">
                                <button wire:click="openEditForm({{ $roadmap->id }})" class="p-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                </button>
                                <button wire:click="deleteRoadmap({{ $roadmap->id }})" onclick="confirm('Bạn có muốn đưa lộ trình này vào thùng rác?') || event.stopImmediatePropagation()" class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-xs font-semibold text-slate-400">
                            📭 Không tìm thấy kết quả nào trong hệ thống DB.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>