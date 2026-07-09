<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-4 flex items-center gap-2 text-xs font-semibold text-slate-400">
        <a href="/manage" class="hover:text-blue-500 transition-colors">Bảng điều khiển</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        <a href="/manage/roadmap" class="hover:text-blue-500 transition-colors">Quản lý lộ trình</a>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        <span class="text-slate-300">Danh sách bài học</span>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold rounded-2xl flex items-center gap-2 animate-fade-in">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-xl shadow-blue-500/5 border border-blue-50/80 overflow-hidden">
        
        <div class="p-6 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-gradient-to-r from-slate-50/50 to-white">
            <div class="flex items-start gap-3">
                <a href="/manage/roadmap" class="inline-flex items-center justify-center p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition-all shrink-0" title="Quay lại danh sách">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                </a>
                <div>
                    <h2 class="text-lg font-black text-slate-900 tracking-tight">
                        Bài Học: {{ $roadmap->title ?? 'Chưa xác định lộ trình' }}
                    </h2>
                    <p class="text-xs text-slate-500 font-medium">Cấu hình chi tiết thứ tự chương mục và nội dung bài học.</p>
                </div>
            </div>

            <div class="flex items-center gap-3 self-end lg:self-auto w-full lg:w-auto justify-end">
                <div class="relative w-full sm:w-60">
                    <input type="text" wire:model.live="search" placeholder="Tìm tiêu đề bài học..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.604 10.604z"/></svg>
                </div>

                <button wire:click="openCreateForm" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 transition-all active:scale-95 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Thêm Bài Học Mới
                </button>
            </div>
        </div>

        @if($isOpenForm)
        <div wire:key="lesson-form-{{ $formKey ?? 'new' }}" class="p-6 bg-blue-50/40 border-b border-blue-100/60 animate-fade-in">
            <h3 class="text-xs font-black text-blue-600 uppercase tracking-wider mb-4">
                {{ $isEditMode ? '🛠️ Cập nhật thông tin bài học' : '✨ Tạo dữ liệu bài học mới' }}
            </h3>
            
            <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Tiêu đề bài học *</label>
                    <input type="text" wire:model.live="title" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-blue-500">
                    @error('title') <span class="text-[10px] text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">
                        Chương
                    </label>

                    <select
                        wire:model.live="section_id"
                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-blue-500"
                    >
                        <option value="">-- Không thuộc chương nào --</option>

                        {{-- ✅ Duyệt qua danh sách chương và in ra bằng thẻ <option> hợp lệ --}}
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" wire:key="section-opt-{{ $section->id }}">
                                Phần {{ $section->sort_order }} - {{ $section->title }}
                            </option>
                        @endforeach
                    </select>

                    @error('section_id')
                        <span class="text-[10px] text-rose-500 font-bold mt-1 block">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                                @if(empty($section_id))
                            <div class="mt-3 animate-fade-in" wire:key="new-section-input">
                                <label class="block text-[11px] font-bold text-blue-600 uppercase mb-1">
                                    Tên chương mới (Nếu muốn tạo tự động)
                                </label>
                            <input 
                                type="text" 
                                wire:model="new_section_title" 
                                placeholder="Nhập tên chương mới... (Bỏ trống nếu muốn để mặc định)" 
                                class="w-full px-4 py-2.5 bg-white border border-blue-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-blue-500 placeholder-slate-400"
                            >
                            @error('new_section_title') 
                                <span class="text-[10px] text-rose-500 font-bold mt-1 block">{{ $message }}</span> 
                            @enderror
                        </div>
                    @endif
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Trạng thái hiển thị</label>
                    <select wire:model="is_published" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-blue-500">
                        <option value="1">Hiển thị công khai</option>
                        <option value="0">Tạm ẩn bài học</option>
                    </select>
                </div>

                <div class="md:col-span-3">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Loại Bài Học *</label>
                    <select wire:model.live="lesson_type" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-blue-500">
                        <option value="text">📝 Nội dung văn bản (Text)</option>
                        <option value="video">🎥 Video bài giảng (Video)</option>
                        <option value="document">📄 Tài liệu đính kèm (Document)</option>
                        <option value="exam">✍️ Bài kiểm tra (Exam)</option>
                        <option value="project">🚀 Bài tập thực hành (Project)</option>
                    </select>
                    @error('lesson_type') <span class="text-[10px] text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Content Configuration Banner - Hiển thị theo loại bài học --}}
                <div wire:key="content-config-{{ $lesson_type }}" class="md:col-span-3 mt-4 p-5 bg-gradient-to-br from-slate-50 to-blue-50/30 rounded-2xl border-2 border-blue-100/60">
                    <h4 class="text-xs font-black text-slate-700 mb-4 uppercase tracking-wide flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Cấu Hình Nội Dung Bài Học
                    </h4>
                    
                    @if($lesson_type === 'text')
                        {{-- Text Content - Quill Editor --}}
                        <div class="bg-white p-4 rounded-xl border border-slate-200">
                            <x-text-editor 
                                label="Nội dung bài học" 
                                wire:model="content"
                                placeholder="Nhập nội dung bài học tại đây..."
                            />
                            @error('content') <span class="text-[10px] text-rose-500 font-bold mt-2 block">{{ $message }}</span> @enderror
                        </div>

                    @elseif($lesson_type === 'video')
                        {{-- Video Content - YouTube URL + Preview --}}
                        <div class="bg-white p-4 rounded-xl border border-slate-200 space-y-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-2">Link Video YouTube *</label>
                                <input 
                                    type="url" 
                                    wire:model.live="video_url" 
                                    placeholder="https://www.youtube.com/watch?v=..." 
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:bg-white transition-all"
                                >
                                @error('video_url') <span class="text-[10px] text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            
                            @if($video_url)
                            <div class="relative">
                                <p class="text-[10px] font-bold text-slate-500 uppercase mb-2">Preview Video:</p>
                                <div class="aspect-video bg-slate-900 rounded-lg overflow-hidden shadow-lg">
                                    @php
                                        // Extract YouTube video ID from various URL formats
                                        $videoId = '';
                                        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $video_url, $matches)) {
                                            $videoId = $matches[1];
                                        }
                                    @endphp
                                    @if($videoId)
                                        <iframe 
                                            src="https://www.youtube-nocookie.com/embed/{{ $videoId }}" 
                                            class="w-full h-full" 
                                            frameborder="0" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                            allowfullscreen>
                                        </iframe>
                                    @else
                                        <div class="flex items-center justify-center h-full text-white text-xs">
                                            URL không hợp lệ
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>

                    @elseif($lesson_type === 'document')
                        {{-- Document Content - Select --}}
                        <div class="bg-white p-4 rounded-xl border border-slate-200">
                            <label class="block text-[11px] font-bold text-slate-600 uppercase mb-2">Chọn Tài Liệu *</label>
                            <select wire:model="document_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-800 focus:outline-none focus:border-blue-500">
                                <option value="">-- Chọn tài liệu --</option>
                                @foreach($documents as $doc)
                                    <option value="{{ $doc->id }}">{{ $doc->title }}</option>
                                @endforeach
                            </select>
                            @error('document_id') <span class="text-[10px] text-rose-500 font-bold mt-2 block">{{ $message }}</span> @enderror
                        </div>

                    @elseif($lesson_type === 'exam')
                        {{-- Exam Content - Select --}}
                        <div class="bg-white p-4 rounded-xl border border-slate-200">
                            <label class="block text-[11px] font-bold text-slate-600 uppercase mb-2">Chọn Bài Kiểm Tra *</label>
                            <select wire:model="exam_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-800 focus:outline-none focus:border-blue-500">
                                <option value="">-- Chọn bài kiểm tra --</option>
                                @foreach($exams as $exam)
                                    <option value="{{ $exam->id }}">{{ $exam->title }}</option>
                                @endforeach
                            </select>
                            @error('exam_id') <span class="text-[10px] text-rose-500 font-bold mt-2 block">{{ $message }}</span> @enderror
                        </div>

                    @elseif($lesson_type === 'project')
                        {{-- Project Content - Select --}}
                        <div class="bg-white p-4 rounded-xl border border-slate-200">
                            <label class="block text-[11px] font-bold text-slate-600 uppercase mb-2">Chọn Bài Tập *</label>
                            <select wire:model="project_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-800 focus:outline-none focus:border-blue-500">
                                <option value="">-- Chọn bài tập --</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->title }}</option>
                                @endforeach
                            </select>
                            @error('project_id') <span class="text-[10px] text-rose-500 font-bold mt-2 block">{{ $message }}</span> @enderror
                        </div>
                    @endif
                </div>

                <div class="md:col-span-3 flex items-center justify-end gap-2 mt-2">
    <button type="button" wire:click="closeForm" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition-colors">
        Hủy
    </button>
    
    <button type="button" wire:click="save" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-500/10 transition-colors">
        Lưu Dữ Liệu
    </button>
</div>
            </form>
        </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider w-20 text-center">Thứ tự</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tiêu Đề Bài Học</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider w-32 text-center">Chương / Mục</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider w-32 text-center">Trạng Thái</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider w-28 text-center">Hành Động</th>
                    </tr>
                </thead>
              <tbody class="divide-y divide-slate-100 text-slate-700">
    @php $lessonIndex = 1; @endphp
    
    {{-- Duyệt trực tiếp qua TẤT CẢ bài học --}}
    @forelse($lessons as $lesson)
        <tr wire:key="lesson-row-{{ $lesson->id }}" class="hover:bg-slate-50/60 transition-colors group">
            
            <td class="px-6 py-4 text-xs font-mono font-bold text-slate-400 text-center">
                {{ $lessonIndex++ }}
            </td>
            
            <td class="px-6 py-4">
                <span class="block text-xs font-black text-slate-800 group-hover:text-blue-600 transition-colors">
                    {{ $lesson->title }}
                </span>
            </td>

            <td class="px-6 py-4 text-center">
                @if($lesson->section)
                    <span class="inline-flex px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold whitespace-nowrap group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                        Phần {{ $lesson->section->sort_order }} - {{ $lesson->section->title }}
                    </span>
                @else
                    <span class="inline-flex px-2.5 py-1 rounded-lg bg-rose-50 text-rose-500 text-[10px] font-bold whitespace-nowrap">
                        Chưa có chương
                    </span>
                @endif
            </td>
            
            <td class="px-6 py-4 text-center">
                @if($lesson->is_published)
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold">
                        <span class="w-1 h-1 rounded-full bg-emerald-500"></span> Hiển thị
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold">
                        <span class="w-1 h-1 rounded-full bg-slate-400"></span> Đang ẩn
                    </span>
                @endif
            </td>
            
            <td class="px-6 py-4">
                <div class="flex items-center justify-center gap-1.5">
                   

                    {{-- Nút sửa --}}
                    <button wire:click="openEditForm({{ $lesson->id }})" title="Chỉnh sửa bài học" class="p-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                    </button>
                    
                    {{-- Nút xóa --}}
                    <button wire:click="deleteLesson({{ $lesson->id }})" onclick="confirm('Bạn có chắc chắn muốn xóa vĩnh viễn bài học này?') || event.stopImmediatePropagation()" title="Xóa bài học" class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                    </button>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="px-6 py-12 text-center text-xs font-semibold text-slate-400">
                📭 Lộ trình này hiện chưa có bài học nào. Hãy bấm "Thêm Bài Học Mới"!
            </td>
        </tr>
    @endforelse
</tbody>
            </table>
        </div>

    </div>
</div>