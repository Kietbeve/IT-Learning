<div class="max-w-7xl mx-auto py-6" x-data="{ notification: null }" x-on:notify.window="notification = $event.detail; setTimeout(() => notification = null, 3000)">
    <!-- Notification Toast -->
    <div x-show="notification" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-5 right-5 z-50 rounded-2xl border bg-white p-4 shadow-xl border-slate-200"
         style="display: none;">
        <div class="flex items-center gap-3">
            <template x-if="notification && notification.type === 'success'">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </template>
            <template x-if="notification && notification.type === 'info'">
                <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </template>
            <div>
                <p class="text-sm font-semibold text-slate-900" x-text="notification ? notification.message : ''"></p>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('documents.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Quay lại kho tài liệu
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content (Left - 70%) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Document Info Card -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 md:p-8 shadow-sm space-y-6">
                <div>
                    <span class="rounded-xl px-2.5 py-1 text-xs font-semibold bg-slate-100 text-slate-800">
                        {{ $doc->category?->name ?? 'Tài liệu' }}
                    </span>
                    <h1 class="mt-4 text-2xl md:text-3xl font-bold tracking-tight text-slate-900 leading-tight">
                        {{ $doc->title }}
                    </h1>

                    <!-- Tags -->
                    @if($doc->tags->isNotEmpty())
                        <div class="mt-3 flex flex-wrap gap-1.5">
                            @foreach($doc->tags as $tag)
                                <span class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                    #{{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                    
                    <div class="mt-4 flex flex-wrap items-center gap-6 text-sm text-slate-500 font-medium">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>{{ number_format($doc->view_count) }} lượt xem</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>{{ number_format($doc->download_count) }} lượt tải</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-rose-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            <span>{{ number_format($doc->favorite_count) }} yêu thích</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-amber-500 fill-amber-500" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <span class="text-slate-900 font-bold">{{ $avgRating }}</span>
                            <span class="text-slate-400">({{ $totalReviews }} đánh giá)</span>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-6">
                    <h2 class="text-lg font-bold text-slate-900 mb-3">Mô tả tài liệu</h2>
                    <div class="text-slate-600 text-sm leading-relaxed prose prose-sm prose-slate max-w-none prose-headings:text-slate-900 prose-a:text-blue-600 prose-strong:text-slate-800 prose-code:text-rose-600 prose-code:bg-slate-100 prose-code:px-1 prose-code:py-0.5 prose-code:rounded">
                        {!! $doc->description !!}
                    </div>
                    </div>
                </div>

                <!-- Gallery Images -->
                @if($doc->gallery_images && is_array($doc->gallery_images) && count($doc->gallery_images) > 0)
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 md:p-8 shadow-sm">
                        <h2 class="text-lg font-bold text-slate-900 mb-4">Hình ảnh gallery</h2>
                        <div x-data="{ activeIndex: null }" class="space-y-4">
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($doc->gallery_images as $index => $image)
                                    <div class="relative rounded-xl overflow-hidden border border-slate-200 aspect-video group cursor-pointer" 
                                         @click="activeIndex = {{ $index }}">
                                        <img src="{{ Storage::disk('r2')->url($image['path']) }}" 
                                             alt="Gallery {{ $index + 1 }}" 
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                                             loading="lazy" />
                                        @if(!empty($image['caption']))
                                            <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/60 to-transparent p-2">
                                                <span class="text-xs text-white font-medium">{{ $image['caption'] }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <!-- Lightbox -->
                            <template x-if="activeIndex !== null">
                                <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
                                     @click="activeIndex = null"
                                     x-transition>
                                    <div class="relative max-w-[90vw] max-h-[90vh]" @click.stop>
                                        <button @click="activeIndex = null" 
                                                class="absolute -top-3 -right-3 z-10 rounded-full bg-white/90 hover:bg-white text-slate-800 p-1.5 shadow-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                        <div class="flex items-center gap-2">
                                            <button @click="activeIndex = Math.max(0, activeIndex - 1)" 
                                                    x-show="activeIndex > 0"
                                                    class="rounded-full bg-white/90 hover:bg-white text-slate-800 p-2 shadow-lg transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                            </button>
                                            <img :src="'{{ Storage::disk('r2')->url('') }}' + {{ Js::from($doc->gallery_images) }}[activeIndex].path" 
                                                 class="max-h-[85vh] max-w-[75vw] rounded-2xl shadow-2xl object-contain" 
                                                 alt="Gallery image" />
                                            <button @click="activeIndex = Math.min({{ count($doc->gallery_images) - 1 }}, activeIndex + 1)" 
                                                    x-show="activeIndex < {{ count($doc->gallery_images) - 1 }}"
                                                    class="rounded-full bg-white/90 hover:bg-white text-slate-800 p-2 shadow-lg transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            </button>
                                        </div>
                                        <p class="text-center text-white text-sm mt-2" x-text="`${activeIndex + 1} / {{ count($doc->gallery_images) }}`"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                @endif

            <!-- Preview Section (Modal Trigger & Fullscreen Viewer for ZIP, Inline Viewer for PDF/DOCX) -->
            @if($doc->file_type === 'zip')
                <div x-data="{ 
                         isFullscreen: false,
                         selectedFile: null, 
                         copied: false, 
                         showTree: true,
                         selectFile(path) {
                             this.selectedFile = path;
                             this.$nextTick(() => {
                                 let container = document.getElementById('user-code-preview-container');
                                 if (container) {
                                     let el = container.querySelector('[data-path=\'' + path + '\'] code');
                                     if (el && !el.classList.contains('prism-highlighted')) {
                                         Prism.highlightElement(el);
                                         el.classList.add('prism-highlighted');
                                     }
                                 }
                             });
                         }
                     }">
                     
                    <!-- Compact Trigger Card -->
                    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-slate-900 text-sm">Xem trước cấu trúc (ZIP)</h3>
                                <p class="text-xs text-slate-500 mt-0.5 truncate">Duyệt cây thư mục code của tệp ZIP</p>
                            </div>
                        </div>
                        <button @click="isFullscreen = true" class="inline-flex rounded-xl bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 text-xs font-semibold shadow-sm transition-all whitespace-nowrap">
                            Xem cấu trúc code
                        </button>
                    </div>

                    <!-- Backdrop when Fullscreen -->
                    <div x-show="isFullscreen" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 transition-opacity duration-300" style="display: none;" @click="isFullscreen = false"></div>

                    <!-- Fullscreen Modal Container -->
                    <div x-show="isFullscreen" 
                         class="fixed inset-4 md:inset-8 z-50 rounded-3xl bg-white border border-slate-200 p-6 md:p-8 flex flex-col h-[calc(100vh-64px)] shadow-2xl space-y-4"
                         style="display: none;"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 scale-95">
                         
                        <div class="flex items-center justify-between shrink-0">
                            <div class="flex items-center gap-3">
                                <h2 class="text-lg font-bold text-slate-900">Xem cấu trúc code</h2>
                                <span class="rounded-xl bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-600">Định dạng ZIP</span>
                            </div>
                            <button @click="isFullscreen = false" class="p-2 rounded-xl hover:bg-slate-100 text-slate-500 hover:text-slate-700 transition-colors flex items-center gap-1.5 text-xs font-bold border border-slate-200 bg-slate-50 shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                Đóng
                            </button>
                        </div>

                        <!-- Content Area -->
                        <div class="flex-1 min-h-0 w-full">
                            <!-- Prism.js CSS - LIGHT THEME -->
                            <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism.min.css" rel="stylesheet" />
                            
                            <div class="rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-50 to-white shadow-md overflow-hidden flex h-full w-full">
                                <!-- File Tree (Left Panel) -->
                                <div x-show="showTree" class="w-64 border-r border-slate-200 bg-gradient-to-b from-white to-slate-50 overflow-y-auto shrink-0 h-full">
                                    <div class="p-4 border-b border-slate-200 bg-white sticky top-0 z-10">
                                        <div class="flex items-center justify-between">
                                            <h3 class="font-bold text-slate-900 flex items-center gap-2 text-sm">
                                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                                Cấu trúc Project
                                            </h3>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1">{{ count($zipFiles) }} files</p>
                                    </div>
                                    <div class="p-3">
                                        @php
                                            $tree = [];
                                            foreach($zipFiles as $path => $file) {
                                                $parts = explode('/', $path);
                                                $current = &$tree;
                                                foreach($parts as $i => $part) {
                                                    if($i === count($parts) - 1) {
                                                        $current[$part] = ['path' => $path, 'isFile' => true];
                                                    } else {
                                                        if(!isset($current[$part])) $current[$part] = [];
                                                        $current = &$current[$part];
                                                    }
                                                }
                                            }
                                            if (!function_exists('renderUserTree')) {
                                                function renderUserTree($tree, $prefix = '', $depth = 0) {
                                                    foreach($tree as $name => $item) {
                                                        if(isset($item['isFile'])) {
                                                            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                                                            $iconClass = match($ext) {
                                                                'php' => 'text-indigo-600',
                                                                'js', 'jsx' => 'text-yellow-600',
                                                                'ts', 'tsx' => 'text-blue-600',
                                                                'py' => 'text-green-600',
                                                                'java' => 'text-red-600',
                                                                'css', 'scss' => 'text-pink-600',
                                                                'html' => 'text-orange-600',
                                                                'json', 'xml' => 'text-purple-600',
                                                                'md' => 'text-slate-600',
                                                                default => 'text-slate-500'
                                                            };
                                                            echo '<div @click="selectFile('.htmlspecialchars(json_encode($item['path'])).')" 
                                                                  class="flex items-center gap-2 px-3 py-1.5 hover:bg-blue-50 cursor-pointer rounded-lg text-xs transition-all group"
                                                                  :class="selectedFile === '.htmlspecialchars(json_encode($item['path'])).' ? \'bg-blue-100 text-blue-800 font-semibold shadow-sm\' : \'text-slate-700 hover:text-blue-700\'"
                                                                  style="margin-left: '.($depth * 12).'px">
                                                                  <svg class="w-3.5 h-3.5 '.$iconClass.'" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                                                                  <span class="truncate flex-1">'.htmlspecialchars($name).'</span>
                                                            </div>';
                                                        } else {
                                                            echo '<div class="mt-1">';
                                                            echo '<div class="flex items-center gap-1.5 px-2 py-1 text-xs font-semibold text-slate-700" style="margin-left: '.($depth * 12).'px">
                                                                  <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                                                  '.htmlspecialchars($name).'
                                                            </div>';
                                                            renderUserTree($item, $prefix.$name.'/', $depth + 1);
                                                            echo '</div>';
                                                        }
                                                    }
                                                }
                                            }
                                            renderUserTree($tree);
                                        @endphp
                                    </div>
                                </div>

                                 <!-- Code Preview (Right Panel) -->
                                <div class="flex-1 bg-white overflow-hidden flex flex-col h-full" id="user-code-preview-container">
                                    <div class="px-4 py-3 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white sticky top-0 z-10 flex items-center justify-between shrink-0">
                                        <div class="flex items-center gap-2">
                                            <button @click="showTree = !showTree" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors shrink-0">
                                                <svg x-show="showTree" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
                                                <svg x-show="!showTree" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                                            </button>
                                            <div x-show="!selectedFile" class="text-slate-500 text-xs">Chọn file để xem thử code</div>
                                            <div x-show="selectedFile" class="flex items-center truncate max-w-xs md:max-w-md">
                                                <span class="font-mono text-xs text-slate-800 font-semibold truncate" x-text="selectedFile"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-1 overflow-y-auto overflow-x-auto bg-white min-h-0">
                                        <div x-show="!selectedFile" class="h-full flex items-center justify-center text-slate-400 bg-gradient-to-br from-slate-50 to-slate-100">
                                            <div class="text-center p-6">
                                                <svg class="w-12 h-12 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                                <p class="text-xs font-semibold text-slate-600">Chưa chọn file</p>
                                            </div>
                                        </div>
                                        @foreach($zipFiles as $path => $fileData)
                                            @php
                                                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                                $lang = match($ext) {
                                                    'php' => 'php',
                                                    'js', 'jsx' => 'javascript',
                                                    'ts', 'tsx' => 'typescript',
                                                    'py' => 'python',
                                                    'java' => 'java',
                                                    'css' => 'css',
                                                    'scss' => 'scss',
                                                    'html' => 'markup',
                                                    'json' => 'json',
                                                    'xml' => 'xml',
                                                    'md' => 'markdown',
                                                    'sql' => 'sql',
                                                    'yml', 'yaml' => 'yaml',
                                                    default => 'markup'
                                                };
                                            @endphp
                                            <div x-show="selectedFile === '{{ $path }}'" data-path="{{ $path }}" style="display: none;">
                                                <pre class="!m-0 !rounded-none" style="font-size: 14px !important; line-height: 1.8 !important; padding: 1.5rem !important; background: #fafafa !important;"><code class="language-{{ $lang }}" style="font-size: 14px !important; line-height: 1.8 !important;">{{ $fileData['content'] }}</code></pre>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Prism.js Scripts -->
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup-templating.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-clike.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-typescript.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-python.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-java.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-css.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-json.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markdown.min.js"></script>
                        </div>
                    </div>
                </div>
            @else
                <!-- Preview Section for PDF / DOCX (Inline Reader directly on the page layout) -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-sm">Đọc thử tài nguyên</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Hỗ trợ đọc thử một phần tài liệu trước khi tải xuống</p>
                            </div>
                        </div>
                        <span class="rounded-xl bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-600 uppercase">Định dạng {{ $doc->file_type }}</span>
                    </div>

                    <div class="h-[750px] w-full mt-4">
                        @if($doc->file_type === 'pdf')
                            @php
                                $watermarkedUrl = ($doc->watermark_status === 'success' && $doc->file_watermarked_path) ? $doc->file_watermarked_url : null;
                                $pdfUrl = $hasAccess ? ($watermarkedUrl ?? $doc->file_original_url) : ($doc->preview_file_url ?? $doc->file_original_url);
                            @endphp

                            @if($pdfUrl)
                                <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-inner h-full w-full bg-slate-100">
                                    <iframe src="{{ $pdfUrl }}#toolbar=0" class="w-full h-full border-0"></iframe>
                                </div>
                            @else
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-8 text-center text-slate-500 h-full w-full flex items-center justify-center">
                                    <p>Không thể hiển thị tài liệu.</p>
                                </div>
                            @endif

                        @elseif($doc->file_type === 'docx')
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-8 text-center text-slate-500 h-full w-full flex items-center justify-center">
                                <p class="text-sm">Tài liệu DOCX không hỗ trợ xem trực tiếp. Vui lòng tải xuống để xem đầy đủ.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Reviews and Comments -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 md:p-8 shadow-sm space-y-6">
                <h2 class="text-lg font-bold text-slate-900">Nhận xét từ người học</h2>

                @auth
                    @if($hasDownloaded && !$hasReviewed)
                        <form wire:submit.prevent="submitReview" class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-semibold text-slate-700">Đánh giá của bạn:</span>
                                <div class="flex items-center gap-1" x-data="{ r: @entangle('rating').live }">
                                    <template x-for="i in 5">
                                        <button type="button" @click="r = i" class="text-2xl focus:outline-none transition-transform active:scale-95">
                                            <span :class="i <= r ? 'text-amber-500' : 'text-slate-300'">★</span>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <div>
                                <textarea wire:model="reviewContent" 
                                          rows="3" 
                                          placeholder="Nhận xét của bạn về chất lượng tài liệu này (tối thiểu 20 ký tự, tối đa 500 ký tự)..."
                                          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-300"></textarea>
                                @error('reviewContent')
                                    <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="rounded-xl bg-slate-900 hover:bg-slate-800 text-white px-5 py-2 text-xs font-semibold shadow-md transition-colors">
                                    Gửi nhận xét
                                </button>
                            </div>
                        </form>
                    @endif
                @endauth

                <!-- Reviews list -->
                <div class="space-y-4">
                    @forelse($doc->reviews->where('status', 'visible') as $rev)
                        <div class="p-4 rounded-2xl border border-slate-100 bg-white flex gap-3">
                            <div class="h-9 w-9 shrink-0 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs uppercase">
                                {{ substr($rev->user?->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="flex-1 space-y-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-bold text-slate-900">{{ $rev->user?->name ?? 'Người dùng' }}</h4>
                                    <span class="text-xs text-slate-400">{{ $rev->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex items-center gap-0.5 text-xs text-amber-500">
                                    @for($i=1; $i<=5; $i++)
                                        <span>{{ $i <= $rev->rating ? '★' : '☆' }}</span>
                                    @endfor
                                </div>
                                <p class="text-sm text-slate-600 leading-relaxed">{{ $rev->review }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-slate-400 text-sm">
                            Chưa có nhận xét nào cho tài liệu này.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Action Column (Right - 30%) -->
        <div class="space-y-6 lg:col-span-1">
            <!-- Download / Price Card -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest block">Giá tài nguyên</span>
                    <div class="mt-2 flex items-baseline gap-2">
                        @if($doc->product)
                            <span class="text-3xl font-bold text-blue-600">{{ number_format($doc->product->price) }}đ</span>
                        @else
                            <span class="text-3xl font-bold text-emerald-600">Miễn phí</span>
                        @endif
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-6 space-y-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500 font-medium">Định dạng file:</span>
                        <span class="text-slate-900 font-semibold uppercase">{{ $doc->file_type }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500 font-medium">Dung lượng:</span>
                        <span class="text-slate-900 font-semibold">{{ number_format($doc->file_size / 1024 / 1024, 2) }} MB</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500 font-medium">Ngày đăng:</span>
                        <span class="text-slate-900 font-semibold">{{ $doc->published_at ? $doc->published_at->format('d/m/Y') : ($doc->created_at ? $doc->created_at->format('d/m/Y') : 'N/A') }}</span>
                    </div>
                    @if(!$doc->is_downloadable)
                        <div class="flex items-center gap-2 rounded-xl bg-amber-50 border border-amber-200 px-3 py-2 text-xs font-semibold text-amber-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Tài liệu này chỉ hỗ trợ xem online
                        </div>
                    @endif
                </div>

                <div class="space-y-3">
                    @guest
                        @if($doc->product)
                            <button wire:click="buyDocument" class="w-full rounded-2xl bg-blue-600 hover:bg-blue-700 text-white py-4 text-sm font-semibold shadow-lg shadow-blue-600/20 hover:shadow-blue-700/30 flex items-center justify-center gap-2 transition-all duration-200 active:scale-[0.98]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 7M7 13l-2 5m5-5v5m4-5v5m4-5l2 5"/></svg>
                                Mua tài nguyên ngay
                            </button>
                        @else
                            <button wire:click="download" class="w-full rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white py-4 text-sm font-semibold shadow-lg shadow-emerald-600/20 hover:shadow-emerald-700/30 flex items-center justify-center gap-2 transition-all duration-200 active:scale-[0.98]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Đăng nhập để tải xuống
                            </button>
                        @endif
                    @else
                        @if(!$doc->product)
                            <button wire:click="download" class="w-full rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white py-4 text-sm font-semibold shadow-lg shadow-emerald-600/20 hover:shadow-emerald-700/30 flex items-center justify-center gap-2 transition-all duration-200 active:scale-[0.98]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Tải xuống
                            </button>
                        @elseif($isVip)
                            <div class="space-y-3">
                                <button wire:click="download" class="w-full rounded-2xl bg-amber-600 hover:bg-amber-700 text-white py-4 text-sm font-semibold shadow-lg shadow-amber-600/20 hover:shadow-amber-700/30 flex items-center justify-center gap-2 transition-all duration-200 active:scale-[0.98]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                    Tải xuống với gói VIP
                                </button>
                                <button wire:click="buyDocument" class="w-full rounded-2xl bg-blue-600 hover:bg-blue-700 text-white py-4 text-sm font-semibold shadow-lg shadow-blue-600/20 hover:shadow-blue-700/30 flex items-center justify-center gap-2 transition-all duration-200 active:scale-[0.98]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 7M7 13l-2 5m5-5v5m4-5v5m4-5l2 5"/></svg>
                                    Mua tài nguyên ngay
                                </button>
                            </div>
                        @else
                            <button wire:click="buyDocument" class="w-full rounded-2xl bg-blue-600 hover:bg-blue-700 text-white py-4 text-sm font-semibold shadow-lg shadow-blue-600/20 hover:shadow-blue-700/30 flex items-center justify-center gap-2 transition-all duration-200 active:scale-[0.98]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 7M7 13l-2 5m5-5v5m4-5v5m4-5l2 5"/></svg>
                                Mua tài nguyên ngay
                            </button>
                        @endif
                    @endguest

                    <button wire:click="toggleFavorite" class="w-full rounded-2xl border-2 border-slate-200 bg-white hover:border-rose-200 hover:bg-rose-50 text-slate-700 hover:text-rose-600 py-3.5 text-sm font-semibold flex items-center justify-center gap-2 transition-all duration-200 active:scale-[0.98]">
                        @if($isBookmarked)
                            <svg class="w-4 h-4 fill-rose-500 text-rose-500" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            <span>Đã lưu tài liệu</span>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            <span>Lưu vào yêu thích</span>
                        @endif
                    </button>

                    <button wire:click="openReportModal" class="w-full rounded-2xl border-2 border-dashed border-slate-200 bg-white hover:border-red-200 hover:bg-red-50 text-slate-500 hover:text-red-600 py-3.5 text-sm font-semibold flex items-center justify-center gap-2 transition-all duration-200 active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                        Báo cáo vi phạm
                    </button>
                </div>
            </div>

            {{-- VIP Suggestion Card (for Premium documents, non-VIP users) --}}
            @auth
                @php
                    $isVip = Auth::user()->vip_expires_at && Auth::user()->vip_expires_at->isFuture();
                    $isPremium = $doc->product && $doc->product->price > 0;
                @endphp
                
                @if($isPremium && !$isVip && !$hasAccess)
                    <div class="rounded-3xl border-2 border-amber-400 bg-gradient-to-br from-amber-50 to-yellow-50 p-6 shadow-lg">
                        <div class="flex items-start gap-3 mb-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-yellow-500 flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">Nâng cấp VIP Premium</h3>
                                <p class="text-sm text-gray-600">Tiết kiệm hơn với gói VIP</p>
                            </div>
                        </div>
                        
                        <ul class="space-y-2 mb-4">
                            <li class="flex items-start gap-2 text-sm text-gray-700">
                                <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Gói <strong>1 tháng:</strong> Tải <strong>5 tài liệu</strong> Premium</span>
                            </li>
                            <li class="flex items-start gap-2 text-sm text-gray-700">
                                <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Gói <strong>3 tháng:</strong> Tải <strong>20 tài liệu</strong> - Tiết kiệm <strong>40%</strong></span>
                            </li>
                            <li class="flex items-start gap-2 text-sm text-gray-700">
                                <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Gói <strong>6 tháng:</strong> Tải <strong>50 tài liệu</strong> - Tiết kiệm <strong>60%</strong></span>
                            </li>
                        </ul>
                        
                        <a href="{{ route('student.subscription') }}" 
                           class="block w-full text-center rounded-2xl bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-white py-3 text-sm font-bold shadow-lg hover:shadow-xl transition-all duration-300">
                            ⚡ Xem các gói VIP
                        </a>
                    </div>
                @endif
            @endauth

            <!-- Author Card -->
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-slate-800 to-slate-950 text-white flex items-center justify-center font-bold text-xl uppercase shrink-0 shadow-md">
                        {{ substr($doc->author?->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest block">Tác giả đăng tải</span>
                        <h3 class="text-base font-bold text-slate-900 truncate">{{ $doc->author?->name ?? 'Giảng viên/CTV' }}</h3>
                    </div>
                </div>
            </div>

            <!-- Related Documents Card -->
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400">Tài liệu liên quan</h3>
                <div class="space-y-3">
                    @php
                        $relPlaceholders = [
                            'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=200&q=80',
                            'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=200&q=80',
                            'https://images.unsplash.com/photo-1542831371-29b0f74f9713?auto=format&fit=crop&w=200&q=80',
                            'https://images.unsplash.com/photo-1504639725590-34d0984388bd?auto=format&fit=crop&w=200&q=80',
                            'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=200&q=80',
                            'https://images.unsplash.com/photo-1607799279861-4dd421887fb3?auto=format&fit=crop&w=200&q=80',
                        ];
                    @endphp
                    @forelse($relatedDocuments as $rel)
                        @php
                            $relThumb = $rel->thumbnail_url ?? $relPlaceholders[$rel->id % count($relPlaceholders)];
                        @endphp
                        <a href="{{ route('documents.show', [$rel->id, Str::slug($rel->title)]) }}" class="flex gap-3 p-2 rounded-2xl hover:bg-slate-50 transition-all duration-200 group">
                            <!-- Thumbnail -->
                            <div class="h-16 w-20 shrink-0 rounded-xl overflow-hidden bg-slate-100 shadow-sm">
                                <img src="{{ $relThumb }}" alt="{{ $rel->title }}" class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-300" loading="lazy" />
                            </div>
                            <!-- Info -->
                            <div class="flex-1 min-w-0 flex flex-col justify-center">
                                <h4 class="text-xs font-bold text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-2 leading-snug">{{ $rel->title }}</h4>
                                <div class="flex items-center gap-3 mt-1.5">
                                    <span class="inline-flex items-center gap-1 text-[10px] text-slate-400 font-medium">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        {{ number_format($rel->download_count) }}
                                    </span>
                                    <span class="inline-block rounded px-1.5 py-0.5 text-[9px] font-bold uppercase bg-slate-100 text-slate-500">{{ $rel->file_type }}</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-xs text-slate-400 py-4 text-center">Không có tài liệu liên quan nào khác.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Checkout Modal --}}
    @livewire('payment-checkout-modal')

    <!-- Report Abuse Modal -->
    <div x-data="{ show: $wire.entangle('showReportModal') }"
         x-show="show" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="show = false"></div>

        <!-- Modal Content Container -->
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl border border-slate-200 transition-all transform scale-100 space-y-6 z-10">
                <!-- Header -->
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Báo cáo tài liệu vi phạm
                    </h3>
                    <button @click="show = false" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="submitReport" class="space-y-4">
                    <!-- Reason Select -->
                    <div class="space-y-1.5">
                        <label for="report-reason" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Lý do báo cáo <span class="text-red-500">*</span></label>
                        <select id="report-reason" wire:model="reportReason" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-slate-400 focus:outline-none">
                            <option value="Bản quyền">Vi phạm bản quyền / Sở hữu trí tuệ</option>
                            <option value="Nội dung sai">Nội dung sai lệch, không chính xác</option>
                            <option value="File hỏng">Tệp tin lỗi, không tải được hoặc chứa mã độc</option>
                            <option value="Spam">Spam, quảng cáo không phù hợp</option>
                            <option value="Khác">Lý do khác</option>
                        </select>
                        @error('reportReason') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Details Textarea -->
                    <div class="space-y-1.5">
                        <label for="report-details" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Chi tiết bổ sung (tùy chọn)</label>
                        <textarea id="report-details"
                                  wire:model="reportDetails" 
                                  rows="4" 
                                  placeholder="Mô tả cụ thể lý do hoặc bằng chứng vi phạm..."
                                  class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-slate-400 focus:outline-none"></textarea>
                        @error('reportDetails') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Footer buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="show = false" class="rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 px-5 py-2.5 text-xs font-semibold transition">
                            Hủy bỏ
                        </button>
                        <button type="submit" class="rounded-xl bg-red-600 hover:bg-red-500 text-white px-5 py-2.5 text-xs font-semibold shadow-md transition">
                            Gửi báo cáo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
