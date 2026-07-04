<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in">
    
    <div class="bg-white border border-slate-200 rounded-3xl p-4 sm:p-5 shadow-xl shadow-slate-200/40 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-3">
            <div class="p-2.5 rounded-2xl bg-blue-50 text-blue-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/></svg>
            </div>
            <div>
                <h1 class="text-base font-black text-slate-900 tracking-tight">Quản Lý Không Gian Bài Giảng</h1>
                <p class="text-xs text-slate-500 font-bold mt-0.5">Chương ID: <span class="text-blue-600 font-black">#{{ $section_id ?? 'N/A' }}</span> • Lộ trình DevAcademy Pro</p>
            </div>
        </div>
        <button onclick="history.back()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-50 border border-slate-200 text-slate-700 text-xs font-black rounded-xl hover:bg-slate-100 hover:text-blue-600 transition-all active:scale-95 shadow-sm">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Quay lại
        </button>
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl mb-6 text-xs font-black flex items-center gap-2 shadow-sm">
            ✓ {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <div class="lg:col-span-1 bg-white border border-slate-200 rounded-3xl p-5 shadow-xl shadow-slate-200/40 sticky top-6">
            <div class="flex items-center gap-2 pb-4 mb-4 border-b border-slate-100">
                <span class="w-2.5 h-2.5 rounded-full {{ $lesson_id ? 'bg-amber-500 animate-pulse' : 'bg-blue-600' }}"></span>
                <h2 class="text-xs font-black text-slate-900 uppercase tracking-wider">
                    {{ $lesson_id ? '🔄 Cập Nhật Bài Giảng' : '➕ Tạo Bài Giảng Mới' }}
                </h2>
            </div>
            
            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1.5 uppercase tracking-wide">Tiêu đề bài học *</label>
                    <input type="text" wire:model="title" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all shadow-inner" placeholder="VD: Cấu hình môi trường...">
                    @error('title') <p class="text-[11px] font-bold text-red-600 mt-1">⚠️ {{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1.5 uppercase tracking-wide">Loại tư liệu cấu hình</label>
                    <select wire:model.live="lesson_type" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all bg-white shadow-inner cursor-pointer">
                        <option value="video">🎥 Video bài giảng (YouTube Link)</option>
                        <option value="text">📂 Tài liệu nghiên cứu (PDF File)</option>
                    </select>
                </div>

                @if($lesson_type === 'video')
                    <div class="animate-fade-in">
                        <label class="block text-xs font-black text-slate-700 mb-1.5 uppercase tracking-wide">Đường dẫn YouTube Embed *</label>
                        <input type="text" wire:model="video_url" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all shadow-inner" placeholder="https://www.youtube.com/watch?v=...">
                        @error('video_url') <p class="text-[11px] font-bold text-red-600 mt-1">⚠️ {{ $message }}</p> @enderror
                    </div>
                @else
                    <div class="animate-fade-in">
                        <label class="block text-xs font-black text-slate-700 mb-1.5 uppercase tracking-wide">Chọn tệp giáo trình PDF (Dưới 50MB) *</label>
                        <div class="relative border-2 border-dashed border-slate-200 hover:border-blue-400 rounded-xl p-4 text-center transition-colors bg-slate-50/50">
                            <input type="file" wire:model="pdf_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".pdf">
                            <div class="space-y-1">
                                <svg class="mx-auto h-7 w-7 text-blue-500 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <p class="text-[11px] font-black text-slate-800">Kéo thả hoặc Click để chọn tệp PDF</p>
                                <p class="text-[10px] text-amber-600 font-bold">Yêu cầu: File .pdf & Dung lượng < 50MB</p>
                            </div>
                        </div>
                        
                        <div wire:loading wire:target="pdf_file" class="mt-2 text-[11px] font-bold text-blue-600 animate-pulse">
                            ⏳ Đang tải tệp lên hệ thống dữ liệu tạm...
                        </div>

                        @error('pdf_file') <p class="text-[11px] font-bold text-red-600 mt-1">⚠️ {{ $message }}</p> @enderror
                        
                        {{-- ✨ SỬA LỖI 1: Tự động hiển thị tên tệp vừa chọn hoặc tệp hiện tại --}}
                        @if($pdf_file)
                            <div class="mt-2 p-2 rounded-lg bg-emerald-50 border border-emerald-100 text-[11px] font-bold text-emerald-700 flex items-center gap-1.5 animate-fade-in">
                                <span>📎 Tệp vừa chọn:</span>
                                <span class="truncate max-w-[180px] font-mono font-black text-emerald-900">
                                    {{ method_exists($pdf_file, 'getClientOriginalName') ? $pdf_file->getClientOriginalName() : 'Đang tải lên...' }}
                                </span>
                            </div>
                        @elseif($current_pdf_path || $this->content)
                            <div class="mt-2 p-2 rounded-lg bg-blue-50/60 border border-blue-100 text-[11px] font-bold text-blue-700 flex items-center gap-1.5">
                                <span>📄 File trên hệ thống:</span>
                                <span class="truncate max-w-[180px] font-mono font-medium text-slate-600">{{ $current_pdf_path ?: $this->content }}</span>
                            </div>
                        @endif
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1.5 uppercase tracking-wide">Đính kèm dự án Lab (Tùy chọn)</label>
                    <select wire:model="project_id" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all bg-white shadow-inner cursor-pointer">
                        <option value="">-- Không triển khai Project --</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1.5 uppercase tracking-wide">Thứ tự hiển thị bài</label>
                    <input type="number" wire:model="sort_order" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all shadow-inner" min="0">
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                    @if($lesson_id)
                        <button type="button" wire:click="resetForm" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-black rounded-xl hover:bg-slate-200 transition-all uppercase tracking-wider">Hủy</button>
                        <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-black rounded-xl shadow-md shadow-amber-500/20 transition-all uppercase tracking-wider">Lưu lại</button>
                    @else
                        <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-black rounded-xl shadow-md shadow-blue-500/20 transition-all uppercase tracking-wider">Khởi tạo bài học</button>
                    @endif
                </div>
            </form>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-slate-200 rounded-3xl shadow-xl shadow-slate-200/40 p-5 overflow-hidden">
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider pb-4 mb-4 border-b border-slate-100 flex items-center gap-2">
                    <span class="p-1 rounded-lg bg-slate-100 text-slate-700">📋</span> Giáo trình bài giảng hiện có
                </h3>
                
                <div class="overflow-x-auto -mx-5">
                    <table class="w-full min-w-[500px] border-collapse text-left">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200/80">
                                <th class="py-3 px-4 text-center text-xs font-black text-slate-500 uppercase tracking-wider w-16">STT</th>
                                <th class="py-3 px-4 text-xs font-black text-slate-500 uppercase tracking-wider">Chi tiết bài học</th>
                                <th class="py-3 px-4 text-center text-xs font-black text-slate-500 uppercase tracking-wider w-24">Định dạng</th>
                                <th class="py-3 px-4 text-center text-xs font-black text-slate-500 uppercase tracking-wider w-40">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($lessons as $lesson)
                                <tr class="hover:bg-slate-50/80 transition-colors {{ $lesson_id == $lesson->id ? 'bg-amber-50/60 hover:bg-amber-50/80' : '' }}">
                                    <td class="py-3.5 px-4 text-center font-black text-slate-900 text-xs">{{ $lesson->sort_order }}</td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-black text-slate-900 text-xs leading-snug">{{ $lesson->title }}</div>
                                        <div class="text-[11px] font-bold text-slate-500 mt-0.5 max-w-[280px] sm:max-w-md truncate font-mono">
                                            {{ $lesson->lesson_type === 'video' ? $lesson->video_url : ($lesson->content ?: 'Tập tin tài liệu PDF') }}
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <span class="inline-block text-[9px] font-black px-2 py-0.5 rounded-md tracking-wider shadow-sm {{ $lesson->lesson_type === 'video' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-purple-50 text-purple-600 border border-purple-100' }}">
                                            {{ $lesson->lesson_type === 'video' ? 'VIDEO' : 'PDF' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center space-x-1.5 whitespace-nowrap">
                                        <a href="{{ url('/roadmaps/' . ($roadmap_id ?? 1) . '/learn/' . $lesson->id) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-slate-50 border border-slate-200 text-slate-700 text-[11px] font-black rounded-lg hover:bg-blue-600 hover:text-white transition-all shadow-sm">Xem</a>
                                        <button type="button" wire:click="edit({{ $lesson->id }})" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-blue-50 border border-blue-100 text-blue-600 text-[11px] font-black rounded-lg hover:bg-blue-600 hover:text-white transition-all shadow-sm">Sửa</button>
                                        <button type="button" wire:click="deleteLesson({{ $lesson->id }})" onclick="confirm('Xác nhận xóa bài học này?') || event.stopImmediatePropagation()" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-50 border border-red-100 text-red-600 text-[11px] font-black rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm">Xóa</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-xs font-bold text-slate-400">
                                        🚀 Chưa có cấu hình bài học nào trong chương mục này.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>