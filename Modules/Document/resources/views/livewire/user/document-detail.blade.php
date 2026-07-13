<div class="max-w-7xl mx-auto pb-8 pt-4 px-4 sm:px-6 lg:px-8 space-y-8" x-data x-on:start-download.window="window.location.href = Array.isArray($event.detail) ? $event.detail[0].url : $event.detail.url">

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('documents.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-gray-900 transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Quay lại kho tài liệu
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content (Left - 70%) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Document Info Card -->
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm hover:shadow-md transition-shadow p-6 md:p-8 shadow-sm space-y-6">
                <div class="flex flex-wrap gap-2">
                    <span class="rounded-xl px-2.5 py-1 text-xs font-semibold bg-gray-100 text-gray-800">
                        {{ $doc->category?->name ?? 'Tài liệu' }}
                    </span>
                    @if($doc->subject)
                        <span class="rounded-xl px-2.5 py-1 text-xs font-semibold bg-blue-50 text-blue-700">
                            {{ $doc->subject->name }}
                        </span>
                    @endif
                </div>
                <div>
                    <h1 class="mt-4 text-3xl lg:text-4xl font-extrabold text-blue-900 leading-tight mb-5">
                        {{ strip_tags($doc->title) }}
                    </h1>

                    <!-- Tags -->
                    @if(isset($displayTags) && $displayTags->isNotEmpty())
                        <div class="mt-3 flex flex-wrap gap-1.5">
                            @foreach($displayTags as $tag)
                                <span class="inline-flex items-center rounded-lg bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                                    #{{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                    
                    <div class="mt-4 flex flex-wrap items-center gap-6 text-sm text-gray-500 font-medium">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>{{ number_format($doc->view_count) }} lượt xem</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>{{ number_format($doc->download_count) }} lượt tải</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-rose-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            <span>{{ number_format($doc->favorite_count) }} yêu thích</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-amber-500 fill-amber-500" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            <span class="text-gray-900 font-bold">{{ $avgRating }}</span>
                            <span class="text-gray-400">({{ $totalReviews }} đánh giá)</span>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <h2 class="text-xl font-bold text-blue-900 mb-3">Mô tả tài liệu</h2>
                    <div class="text-gray-600 text-sm leading-relaxed prose prose-sm prose-slate max-w-none prose-headings:text-gray-900 prose-a:text-blue-600 prose-strong:text-gray-800 prose-code:text-rose-600 prose-code:bg-gray-100 prose-code:px-1 prose-code:py-0.5 prose-code:rounded">
                        {!! $doc->description !!}
                    </div>
                    </div>
                </div>

                <!-- Gallery Images -->
                @if($doc->gallery_images && is_array($doc->gallery_images) && count($doc->gallery_images) > 0)
                    <div class="rounded-2xl border border-gray-100 bg-white shadow-sm hover:shadow-md transition-shadow p-6 md:p-8 shadow-sm">
                        <h2 class="text-xl font-bold text-blue-900 mb-4">Hình ảnh gallery</h2>
                        <div x-data="{ activeIndex: null }" class="space-y-4">
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($doc->gallery_images as $index => $image)
                                    <div class="relative rounded-xl overflow-hidden border border-gray-100 aspect-video group cursor-pointer" 
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
                                <div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 backdrop-blur-md"
                                     @click="activeIndex = null"
                                     @keydown.escape.window="activeIndex = null"
                                     @keydown.right.window="activeIndex = Math.min({{ count($doc->gallery_images) - 1 }}, activeIndex + 1)"
                                     @keydown.left.window="activeIndex = Math.max(0, activeIndex - 1)"
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition ease-in duration-200"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0">
                                     
                                    <button @click="activeIndex = null" class="fixed top-4 right-4 z-[110] rounded-full bg-white/10 hover:bg-white/20 p-2 text-white backdrop-blur-sm transition-all shadow-lg">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                    
                                    <button @click.stop="activeIndex = Math.max(0, activeIndex - 1)" 
                                            x-show="activeIndex > 0"
                                            class="fixed left-4 top-1/2 -translate-y-1/2 z-[110] rounded-full bg-white/10 hover:bg-white/20 p-3 text-white backdrop-blur-sm transition-all shadow-lg">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    </button>
                                    
                                    <button @click.stop="activeIndex = Math.min({{ count($doc->gallery_images) - 1 }}, activeIndex + 1)" 
                                            x-show="activeIndex < {{ count($doc->gallery_images) - 1 }}"
                                            class="fixed right-4 top-1/2 -translate-y-1/2 z-[110] rounded-full bg-white/10 hover:bg-white/20 p-3 text-white backdrop-blur-sm transition-all shadow-lg">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </button>

                                    <div class="relative w-full h-full flex flex-col items-center justify-center p-4 sm:p-12" @click.stop>
                                        <img :src="'{{ Storage::disk('r2')->url('') }}' + {{ Js::from($doc->gallery_images) }}[activeIndex].path" 
                                             class="max-h-full max-w-full rounded-lg shadow-2xl object-contain select-none" 
                                             alt="Gallery image" />
                                        <div class="absolute bottom-6 inset-x-0 flex flex-col items-center gap-2">
                                            <template x-if="{{ Js::from($doc->gallery_images) }}[activeIndex].caption">
                                                <span class="rounded-lg bg-black/60 px-4 py-2 text-sm text-white backdrop-blur-sm max-w-[80vw] text-center" x-text="{{ Js::from($doc->gallery_images) }}[activeIndex].caption"></span>
                                            </template>
                                            <p class="rounded-full bg-black/50 px-4 py-1.5 text-sm font-medium text-white backdrop-blur-sm" x-text="`${activeIndex + 1} / {{ count($doc->gallery_images) }}`"></p>
                                        </div>
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
                    <div class="rounded-2xl border border-gray-100 bg-white shadow-sm hover:shadow-md transition-shadow p-5 shadow-sm flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-blue-900 text-sm">Xem trước cấu trúc (ZIP)</h3>
                                <p class="text-xs text-gray-500 mt-0.5 truncate">Duyệt cây thư mục code của tệp ZIP</p>
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
                         class="fixed inset-4 md:inset-8 z-50 rounded-3xl bg-white border border-gray-100 p-6 md:p-8 flex flex-col h-[calc(100vh-64px)] shadow-2xl space-y-4"
                         style="display: none;"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 scale-95">
                         
                        <div class="flex items-center justify-between shrink-0">
                            <div class="flex items-center gap-3">
                                <h2 class="text-xl font-bold text-blue-900">Xem cấu trúc code</h2>
                                <span class="rounded-xl bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-600">Định dạng ZIP</span>
                            </div>
                            <button @click="isFullscreen = false" class="p-2 rounded-xl hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition-colors flex items-center gap-1.5 text-xs font-bold border border-gray-100 bg-gray-50 shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                Đóng
                            </button>
                        </div>

                        <!-- Content Area -->
                        <div class="flex-1 min-h-0 w-full">
                            <!-- Prism.js CSS - LIGHT THEME -->
                            <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism.min.css" rel="stylesheet" />
                            
                            <div class="rounded-2xl border border-gray-100 bg-gradient-to-br from-gray-50 to-white shadow-md overflow-hidden flex h-full w-full">
                                <!-- File Tree (Left Panel) -->
                                <div x-show="showTree" class="w-64 border-r border-gray-100 bg-gradient-to-b from-white to-gray-50 overflow-y-auto shrink-0 h-full">
                                    <div class="p-4 border-b border-gray-100 bg-white sticky top-0 z-10">
                                        <div class="flex items-center justify-between">
                                            <h3 class="font-bold text-blue-900 flex items-center gap-2 text-sm">
                                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                                Cấu trúc Project
                                            </h3>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">{{ count($zipFiles) }} files</p>
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
                                                                'md' => 'text-gray-600',
                                                                default => 'text-gray-500'
                                                            };
                                                            echo '<div @click="selectFile('.htmlspecialchars(json_encode($item['path'])).')" 
                                                                  class="flex items-center gap-2 px-3 py-1.5 hover:bg-blue-50 cursor-pointer rounded-lg text-xs transition-all group"
                                                                  :class="selectedFile === '.htmlspecialchars(json_encode($item['path'])).' ? \'bg-blue-100 text-blue-800 font-semibold shadow-sm\' : \'text-gray-700 hover:text-blue-700\'"
                                                                  style="margin-left: '.($depth * 12).'px">
                                                                  <svg class="w-3.5 h-3.5 '.$iconClass.'" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                                                                  <span class="truncate flex-1">'.htmlspecialchars($name).'</span>
                                                            </div>';
                                                        } else {
                                                            echo '<div class="mt-1">';
                                                            echo '<div class="flex items-center gap-1.5 px-2 py-1 text-xs font-semibold text-gray-700" style="margin-left: '.($depth * 12).'px">
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
                                    <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white sticky top-0 z-10 flex items-center justify-between shrink-0">
                                        <div class="flex items-center gap-2">
                                            <button @click="showTree = !showTree" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors shrink-0">
                                                <svg x-show="showTree" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
                                                <svg x-show="!showTree" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                                            </button>
                                            <div x-show="!selectedFile" class="text-gray-500 text-xs">Chọn file để xem thử code</div>
                                            <div x-show="selectedFile" class="flex items-center truncate max-w-xs md:max-w-md">
                                                <span class="font-mono text-xs text-gray-800 font-semibold truncate" x-text="selectedFile"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-1 overflow-y-auto overflow-x-auto bg-white min-h-0">
                                        <div x-show="!selectedFile" class="h-full flex items-center justify-center text-gray-400 bg-gradient-to-br from-gray-50 to-gray-100">
                                            <div class="text-center p-6">
                                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                                <p class="text-xs font-semibold text-gray-600">Chưa chọn file</p>
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
                <div class="rounded-2xl border border-gray-100 bg-white shadow-sm hover:shadow-md transition-shadow p-3 sm:p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 sm:p-3 bg-blue-50 text-blue-600 rounded-2xl shrink-0">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-blue-900 text-sm">Đọc thử tài nguyên</h3>
                                <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5">Hỗ trợ đọc thử một phần tài liệu trước khi tải xuống</p>
                            </div>
                        </div>
                        <span class="hidden sm:inline-block rounded-xl bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-600 uppercase">Định dạng {{ $doc->file_type }}</span>
                    </div>

                    <div class="h-[500px] md:h-[750px] w-full mt-4">
                        @if($doc->file_type === 'pdf')
                            @php
                                // Preview section luôn hiện preview file (giới hạn trang), không hiện full document
                                $pdfUrl = $doc->preview_file_url ?? $doc->file_original_url;
                            @endphp

                            @if($pdfUrl && $previewFileExists)
                                <div class="rounded-2xl overflow-auto border border-gray-100 shadow-inner h-full w-full bg-gray-100" style="-webkit-overflow-scrolling: touch; touch-action: pan-y;">
                                    <iframe src="{{ $pdfUrl }}#toolbar=0" class="w-full h-full border-0" style="width:100%; height:100%; min-height: 100%;"></iframe>
                                </div>
                            @else
                                <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4 sm:p-8 text-center text-gray-500 h-full w-full flex items-center justify-center">
                                    <p>Không thể hiển thị tài liệu.</p>
                                </div>
                            @endif

                        @elseif($doc->file_type === 'docx')
                            @php
                                // DOCX được convert sang PDF để preview
                                // Logic access control giống PDF documents
                                $pdfUrl = $doc->preview_file_url ?? null;
                            @endphp

                            @if($pdfUrl && $previewFileExists)
                                {{-- Preview PDF từ DOCX đã convert --}}
                                <div class="rounded-2xl overflow-auto border border-gray-100 shadow-inner h-full w-full bg-gray-100" style="-webkit-overflow-scrolling: touch; touch-action: pan-y;">
                                    <iframe src="{{ $pdfUrl }}#toolbar=0" class="w-full h-full border-0" style="width:100%; height:100%; min-height: 100%;"></iframe>
                                </div>
                                
                            @else
                                {{-- Fallback nếu chưa có preview (đang xử lý conversion) --}}
                                <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4 sm:p-8 text-center text-gray-500 h-full w-full flex items-center justify-center">
                                    <div class="space-y-2">
                                        <p class="text-sm font-medium">Đang xử lý preview tài liệu Word...</p>
                                        <p class="text-xs text-gray-400">Vui lòng tải xuống để xem đầy đủ ngay.</p>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @endif

            <!-- Reviews and Comments -->
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm hover:shadow-md transition-shadow p-6 md:p-8 shadow-sm space-y-6">
                <h2 class="text-xl font-bold text-blue-900">Nhận xét từ người học</h2>

                @auth
                    @if($hasDownloaded && !$hasReviewed)
                        <form wire:submit.prevent="submitReview" class="p-4 rounded-2xl bg-gray-50 border border-gray-100 space-y-4">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-semibold text-gray-700">Đánh giá của bạn:</span>
                                <div class="flex items-center gap-1" x-data="{ r: @entangle('rating').live }">
                                    <template x-for="i in 5">
                                        <button type="button" @click="r = i" class="text-2xl focus:outline-none transition-transform active:scale-95">
                                            <span :class="i <= r ? 'text-amber-500' : 'text-gray-300'">★</span>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <div>
                                <textarea wire:model="reviewContent" 
                                          rows="3" 
                                          placeholder="Nhận xét của bạn về chất lượng tài liệu này (tối thiểu 20 ký tự, tối đa 500 ký tự)..."
                                          class="w-full rounded-xl border border-gray-100 bg-white px-4 py-3 text-sm text-gray-900 focus:border-slate-400 focus:outline-none focus:ring-1 focus:ring-gray-200"></textarea>
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
                        <div class="p-4 rounded-2xl border border-gray-100 bg-white flex gap-3">
                            <div class="h-9 w-9 shrink-0 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center font-bold text-xs uppercase">
                                {{ substr($rev->user?->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="flex-1 space-y-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-base font-bold text-blue-900">{{ $rev->user?->name ?? 'Người dùng' }}</h4>
                                    <span class="text-xs text-gray-400">{{ $rev->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex items-center gap-0.5 text-xs text-amber-500">
                                    @for($i=1; $i<=5; $i++)
                                        <span>{{ $i <= $rev->rating ? '★' : '☆' }}</span>
                                    @endfor
                                </div>
                                
                                @if($editReviewId === $rev->id)
                                    <form wire:submit.prevent="updateReview" class="mt-2 space-y-3 bg-gray-50 p-3 rounded-xl border border-gray-100">
                                        <div class="flex items-center gap-1" x-data="{ r: @entangle('editRating').live }">
                                            <template x-for="i in 5">
                                                <button type="button" @click="r = i" class="text-lg focus:outline-none transition-transform active:scale-95">
                                                    <span :class="i <= r ? 'text-amber-500' : 'text-gray-300'">★</span>
                                                </button>
                                            </template>
                                        </div>
                                        <textarea wire:model="editReviewContent" rows="2" class="w-full rounded-lg border border-gray-100 px-3 py-2 text-sm focus:outline-none focus:border-slate-400"></textarea>
                                        <div class="flex gap-2 justify-end">
                                            <button type="button" wire:click="cancelEdit" class="text-xs text-gray-500 hover:underline font-medium px-2 py-1">Hủy</button>
                                            <button type="submit" class="text-xs text-white bg-slate-800 hover:bg-slate-900 rounded-lg px-3 py-1.5 font-bold shadow-sm">Lưu</button>
                                        </div>
                                    </form>
                                @else
                                    <p class="text-sm text-gray-600 leading-relaxed break-words">{{ $rev->review }}</p>
                                    @auth
                                        @if(Auth::id() == $rev->user_id || $isAdmin)
                                            <div class="flex items-center gap-3 mt-2 font-medium">
                                                @if(Auth::id() == $rev->user_id)
                                                    <button wire:click="startEdit({{ $rev->id }})" class="text-xs text-gray-500 hover:text-amber-600 transition-colors">Sửa</button>
                                                @endif
                                                <button wire:click="deleteReview({{ $rev->id }})" onclick="confirm('Bạn có chắc chắn muốn xóa đánh giá này?') || event.stopImmediatePropagation()" class="text-xs text-gray-500 hover:text-red-600 transition-colors">Xóa</button>
                                            </div>
                                        @endif
                                    @endauth
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-gray-400 text-sm">
                            Chưa có nhận xét nào cho tài liệu này.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Comments Section -->
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm hover:shadow-md transition-shadow p-6 md:p-8 shadow-sm space-y-6">
                @php
                    $visibleComments = collect($comments)->where('status', 'visible');
                    $totalComments = $visibleComments->count() + $visibleComments->sum(function($c) { 
                        return $c->replies ? $c->replies->where('status', 'visible')->count() : 0; 
                    });
                @endphp
                <h2 class="text-xl font-bold text-blue-900">Thảo luận ({{ $totalComments }})</h2>

                @auth
                    <form wire:submit.prevent="addComment" class="flex gap-3">
                        <div class="h-10 w-10 shrink-0 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm uppercase shadow-sm">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="flex-1 space-y-3">
                            <textarea wire:model="newComment" rows="2" placeholder="Thêm bình luận của bạn để thảo luận về tài liệu này..." class="w-full rounded-xl border border-gray-100 bg-white px-4 py-3 text-sm text-gray-900 focus:border-blue-400 focus:outline-none focus:ring-1 focus:ring-blue-300 transition-colors shadow-sm"></textarea>
                            @error('newComment')
                                <span class="text-xs text-red-500 font-medium block">{{ $message }}</span>
                            @enderror
                            <div class="flex justify-end">
                                <button type="submit" class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 text-xs font-semibold shadow-md shadow-blue-600/20 transition-all duration-200 active:scale-[0.98]">
                                    Bình luận
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 text-center text-sm text-gray-500">
                        Vui lòng <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Đăng nhập</a> để tham gia thảo luận.
                    </div>
                @endauth

                <!-- Comments List -->
                <div class="space-y-4">
                    @if($comments)
                        @forelse(collect($comments)->where('status', 'visible')->where('parent_id', null) as $comment)
                            <div id="comment-{{ $comment->id }}" class="p-4 rounded-2xl border border-gray-100 bg-gray-50 flex gap-3">
                                <div class="h-9 w-9 shrink-0 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-xs uppercase">
                                    {{ substr($comment->user?->name ?? 'U', 0, 1) }}
                                </div>
                                <div class="flex-1 space-y-2 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-base font-bold text-blue-900 flex items-center gap-2">
                                            {{ $comment->user?->name ?? 'Người dùng' }}
                                            @if($comment->user?->hasRole('admin'))
                                                <span class="inline-flex items-center rounded-md bg-rose-100 px-2 py-0.5 text-[10px] font-bold text-rose-700 ring-1 ring-inset ring-rose-600/20 whitespace-nowrap uppercase">Admin</span>
                                            @endif
                                        </h4>
                                        <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    
                                    @if($editingCommentId === $comment->id)
                                        <form wire:submit.prevent="updateComment" class="space-y-2">
                                            <textarea wire:model="editingContent" rows="2" class="w-full rounded-xl border border-gray-100 px-3 py-2 text-sm focus:outline-none focus:border-blue-400 shadow-sm"></textarea>
                                            <div class="flex gap-2 justify-end">
                                                <button type="button" wire:click="cancelEditComment" class="text-xs text-gray-500 hover:underline font-medium px-2 py-1">Hủy</button>
                                                <button type="submit" class="text-xs text-white bg-blue-600 hover:bg-blue-700 rounded-lg px-3 py-1.5 font-bold shadow-sm">Lưu</button>
                                            </div>
                                        </form>
                                    @else
                                        <p class="text-sm text-gray-700 leading-relaxed break-words">{{ $comment->content }}</p>
                                        
                                        <div class="flex items-center gap-4 text-xs font-medium">
                                            @auth
                                                <button wire:click="startReply({{ $comment->id }}, '{{ addslashes($comment->user?->name ?? 'Người dùng') }}')" class="text-gray-500 hover:text-blue-600 transition-colors">Phản hồi</button>
                                                @if(Auth::id() == $comment->user_id)
                                                    <button wire:click="startEditComment({{ $comment->id }})" class="text-gray-500 hover:text-amber-600 transition-colors">Sửa</button>
                                                @endif
                                                @if(Auth::id() == $comment->user_id || $isAdmin)
                                                    <button wire:click="deleteComment({{ $comment->id }})" onclick="confirm('Bạn có chắc chắn muốn xóa bình luận này?') || event.stopImmediatePropagation()" class="text-gray-500 hover:text-red-600 transition-colors">Xóa</button>
                                                @endif
                                            @endauth
                                        </div>
                                    @endif

                                    <!-- Replies -->
                                    @if($comment->replies && $comment->replies->where('status', 'visible')->count() > 0)
                                        <div class="mt-4 space-y-4 pl-4 border-l-2 border-gray-100">
                                            @foreach($comment->replies->where('status', 'visible') as $reply)
                                                <div id="comment-{{ $reply->id }}" class="flex gap-3">
                                                    <div class="h-7 w-7 shrink-0 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-[10px] uppercase">
                                                        {{ substr($reply->user?->name ?? 'U', 0, 1) }}
                                                    </div>
                                                    <div class="flex-1 space-y-1 min-w-0">
                                                        <div class="flex items-center justify-between">
                                                            <h4 class="text-base font-bold text-blue-900 flex items-center gap-2">
                                                                {{ $reply->user?->name ?? 'Người dùng' }}
                                                                @if($reply->user?->hasRole('admin'))
                                                                    <span class="inline-flex items-center rounded-md bg-rose-100 px-2 py-0.5 text-[10px] font-bold text-rose-700 ring-1 ring-inset ring-rose-600/20 whitespace-nowrap uppercase">Admin</span>
                                                                @endif
                                                            </h4>
                                                            <span class="text-[11px] text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        
                                                        @if($editingCommentId === $reply->id)
                                                            <form wire:submit.prevent="updateComment" class="space-y-2">
                                                                <textarea wire:model="editingContent" rows="2" class="w-full rounded-xl border border-gray-100 px-3 py-2 text-sm focus:outline-none focus:border-blue-400 shadow-sm"></textarea>
                                                                <div class="flex gap-2 justify-end">
                                                                    <button type="button" wire:click="cancelEditComment" class="text-xs text-gray-500 hover:underline font-medium px-2 py-1">Hủy</button>
                                                                    <button type="submit" class="text-xs text-white bg-blue-600 hover:bg-blue-700 rounded-lg px-3 py-1.5 font-bold shadow-sm">Lưu</button>
                                                                </div>
                                                            </form>
                                                        @else
                                        <p class="text-sm text-gray-700 leading-relaxed break-words">{!! preg_replace('/^(@.+?):\s/u', '<strong class="font-bold text-blue-600">$1</strong>: ', e($reply->content)) !!}</p>
                                                            @auth
                                                                <div class="flex items-center gap-3 text-xs font-medium mt-1">
                                                                    <button wire:click="startReply({{ $comment->id }}, '{{ addslashes($reply->user?->name ?? 'Người dùng') }}', {{ $reply->id }})" class="text-gray-500 hover:text-blue-600 transition-colors">Phản hồi</button>
                                                                    @if(Auth::id() == $reply->user_id)
                                                                        <button wire:click="startEditComment({{ $reply->id }})" class="text-gray-500 hover:text-amber-600 transition-colors">Sửa</button>
                                                                    @endif
                                                                    @if(Auth::id() == $reply->user_id || $isAdmin)
                                                                        <button wire:click="deleteComment({{ $reply->id }})" onclick="confirm('Bạn có chắc chắn muốn xóa phản hồi này?') || event.stopImmediatePropagation()" class="text-gray-500 hover:text-red-600 transition-colors">Xóa</button>
                                                                    @endif
                                                                </div>
                                                            @endauth
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Reply Form -->
                                    @if($replyTo === $comment->id)
                                        <form wire:submit.prevent="addReply" class="mt-3 flex gap-2 @if($comment->replies && $comment->replies->where('status', 'visible')->count() > 0) pl-4 border-l-2 border-gray-100 @endif">
                                            <textarea wire:model="replyContent" rows="1" placeholder="Nhập phản hồi..." class="flex-1 rounded-xl border border-gray-100 px-3 py-2 text-sm focus:outline-none focus:border-blue-400 shadow-sm"></textarea>
                                            <div class="flex flex-col gap-1">
                                                <button type="submit" class="rounded-lg bg-blue-600 text-white px-3 py-1 text-xs font-bold hover:bg-blue-700 shadow-sm">Gửi</button>
                                                <button type="button" wire:click="cancelReply" class="rounded-lg bg-gray-200 text-gray-600 px-3 py-1 text-xs font-bold hover:bg-slate-300">Hủy</button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 text-gray-400 text-sm">
                                Chưa có bình luận nào. Hãy là người đầu tiên thảo luận!
                            </div>
                        @endforelse
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Column (Right - 30%) -->
        <div class="space-y-6 lg:col-span-1">
            <!-- Download / Price Card -->
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm hover:shadow-md transition-shadow p-6 shadow-sm space-y-6">
                <div>
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest block">Giá tài nguyên</span>
                    <div class="mt-2 flex items-baseline gap-2">
                        @if($doc->product && $doc->product->price > 0)
                            @if($doc->product->sale_price)
                                <span class="text-3xl font-bold text-blue-600">{{ number_format($doc->product->sale_price) }}đ</span>
                                <span class="text-lg text-gray-400 line-through font-medium ml-2">{{ number_format($doc->product->price) }}đ</span>
                            @else
                                <span class="text-3xl font-bold text-blue-600">{{ number_format($doc->product->price) }}đ</span>
                            @endif
                        @else
                            <span class="text-3xl font-bold text-emerald-600">Miễn phí</span>
                        @endif
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6 space-y-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500 font-medium">Định dạng file:</span>
                        <span class="text-gray-900 font-semibold uppercase">{{ $doc->file_type }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500 font-medium">Dung lượng:</span>
                        <span class="text-gray-900 font-semibold">{{ number_format($doc->file_size / 1024 / 1024, 2) }} MB</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500 font-medium">Ngày đăng:</span>
                        <span class="text-gray-900 font-semibold">{{ $doc->published_at ? $doc->published_at->format('d/m/Y') : ($doc->created_at ? $doc->created_at->format('d/m/Y') : 'N/A') }}</span>
                    </div>

                </div>

                <div class="space-y-3">
                    @if($hasAccess || (!$doc->product || $doc->product->price == 0))
                        @guest
                            @if(!$doc->product || $doc->product->price == 0)
                                <button wire:click="download" class="w-full rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white py-4 text-sm font-semibold shadow-lg shadow-emerald-600/20 hover:shadow-emerald-700/30 flex items-center justify-center gap-2 transition-all duration-200 active:scale-[0.98]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Đăng nhập để tải xuống
                                </button>
                            @else
                                <button wire:click="download" class="w-full rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white py-4 text-sm font-semibold shadow-lg shadow-emerald-600/20 hover:shadow-emerald-700/30 flex items-center justify-center gap-2 transition-all duration-200 active:scale-[0.98]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Tải xuống (Khách)
                                </button>
                            @endif
                        @else
                            <button wire:click="download" class="w-full rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white py-4 text-sm font-semibold shadow-lg shadow-emerald-600/20 hover:shadow-emerald-700/30 flex items-center justify-center gap-2 transition-all duration-200 active:scale-[0.98]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                @if(Auth::id() === $doc->author_id)
                                    Tải xuống tài liệu của bạn
                                @else
                                    Tải xuống
                                @endif
                            </button>
                        @endguest
                    @else
                        @guest
                            <button wire:click="buyDocument" class="w-full rounded-2xl bg-blue-600 hover:bg-blue-700 text-white py-4 text-sm font-semibold shadow-lg shadow-blue-600/20 hover:shadow-blue-700/30 flex items-center justify-center gap-2 transition-all duration-200 active:scale-[0.98]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 7M7 13l-2 5m5-5v5m4-5v5m4-5l2 5"/></svg>
                                Mua tài nguyên ngay
                            </button>
                        @else
                            @if($isVip)
                                <div class="space-y-3">
                                    <button wire:click="promptVipDownload" class="w-full rounded-2xl bg-amber-600 hover:bg-amber-700 text-white py-4 text-sm font-semibold shadow-lg shadow-amber-600/20 hover:shadow-amber-700/30 flex items-center justify-center gap-2 transition-all duration-200 active:scale-[0.98]">
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
                    @endif

                    <button x-data="{ copied: false }" 
                            @click="if (navigator.share) { navigator.share({ title: '{{ addslashes($doc->title) }}', url: window.location.href }) } else { navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2000); }"
                            class="w-full rounded-2xl border-2 border-gray-100 bg-white hover:border-blue-200 hover:bg-blue-50 text-gray-700 hover:text-blue-600 py-3.5 text-sm font-semibold flex items-center justify-center gap-2 transition-all duration-200 active:scale-[0.98]">
                        <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        <svg x-show="copied" style="display: none;" class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span x-text="copied ? 'Đã sao chép link!' : 'Chia sẻ tài liệu'"></span>
                    </button>

                    <button wire:click="toggleFavorite" class="w-full rounded-2xl border-2 border-gray-100 bg-white hover:border-rose-200 hover:bg-rose-50 text-gray-700 hover:text-rose-600 py-3.5 text-sm font-semibold flex items-center justify-center gap-2 transition-all duration-200 active:scale-[0.98]">
                        @if($isBookmarked)
                            <svg class="w-4 h-4 fill-rose-500 text-rose-500" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            <span>Đã lưu tài liệu</span>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            <span>Lưu vào yêu thích</span>
                        @endif
                    </button>

                    <button wire:click="openReportModal" class="w-full rounded-2xl border-2 border-dashed border-gray-100 bg-white hover:border-red-200 hover:bg-red-50 text-gray-500 hover:text-red-600 py-3.5 text-sm font-semibold flex items-center justify-center gap-2 transition-all duration-200 active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                        Báo cáo vi phạm
                    </button>
                </div>
            </div>

            <!-- Author Card -->
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm hover:shadow-md transition-shadow p-5 shadow-sm">
                <div class="flex items-center gap-4">
                    <img src="{{ $doc->author?->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($doc->author?->name ?? 'A') . '&background=1e293b&color=fff&bold=true' }}" 
                         alt="{{ $doc->author?->name ?? 'Author' }}" 
                         class="h-14 w-14 rounded-2xl object-cover shrink-0 shadow-md border border-gray-100">
                    <div class="flex-1 min-w-0">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest block">Tác giả đăng tải</span>
                        <h3 class="text-lg font-bold text-blue-900 truncate">{{ $doc->author?->name ?? 'Giảng viên/CTV' }}</h3>
                    </div>
                </div>
            </div>

            <!-- Related Documents Card -->
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm hover:shadow-md transition-shadow p-5 shadow-sm space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400">Tài liệu liên quan</h3>
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
                        <a href="{{ route('documents.show', [$rel->id, Str::slug($rel->title)]) }}" class="flex gap-4 p-3 rounded-2xl hover:bg-gray-50 transition-all duration-200 group border border-transparent hover:border-gray-100">
                            <!-- Thumbnail -->
                            <div class="h-20 w-28 shrink-0 rounded-xl overflow-hidden bg-gray-100 shadow-sm border border-gray-100/50">
                                <img src="{{ $relThumb }}" alt="{{ $rel->title }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy" />
                            </div>
                            <!-- Info -->
                            <div class="flex-1 min-w-0 flex flex-col justify-center">
                                <h4 class="text-sm font-bold text-blue-900 group-hover:text-blue-600 transition-colors line-clamp-2 leading-snug">{{ strip_tags($rel->title) }}</h4>
                                <div class="flex items-center gap-4 mt-2">
                                    <span class="inline-flex items-center gap-1.5 text-xs text-gray-400 font-medium">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        {{ number_format($rel->download_count) }}
                                    </span>
                                    <span class="inline-block rounded px-2 py-0.5 text-[10px] font-bold uppercase bg-gray-100 text-gray-500">{{ $rel->file_type }}</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-xs text-gray-400 py-4 text-center">Không có tài liệu liên quan nào khác.</div>
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
            <div class="relative w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl border border-gray-100 transition-all transform scale-100 space-y-6 z-10">
                <!-- Header -->
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-blue-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Báo cáo tài liệu vi phạm
                    </h3>
                    <button @click="show = false" class="rounded-full p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="submitReport" class="space-y-4">
                    <!-- Reason Select -->
                    <div class="space-y-1.5">
                        <label for="report-reason" class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Lý do báo cáo <span class="text-red-500">*</span></label>
                        <select id="report-reason" wire:model="reportReason" class="w-full rounded-2xl border border-gray-100 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:border-slate-400 focus:outline-none">
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
                        <label for="report-details" class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Chi tiết bổ sung (tùy chọn)</label>
                        <textarea id="report-details"
                                  wire:model="reportDetails" 
                                  rows="4" 
                                  placeholder="Mô tả cụ thể lý do hoặc bằng chứng vi phạm..."
                                  class="w-full rounded-2xl border border-gray-100 bg-gray-50 px-4 py-3 text-sm text-gray-900 focus:border-slate-400 focus:outline-none"></textarea>
                        @error('reportDetails') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Footer buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" @click="show = false" class="rounded-xl border border-gray-100 hover:bg-gray-50 text-gray-700 px-5 py-2.5 text-xs font-semibold transition">
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

    <!-- VIP Confirm Modal -->
    <div x-show="$wire.showVipConfirmModal" 
         @vip-download-success.window="$wire.set('showVipConfirmModal', false)"
         style="display: none;" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Background backdrop -->
            <div x-show="$wire.showVipConfirmModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" 
                 aria-hidden="true" 
                 wire:click="$set('showVipConfirmModal', false)"></div>

            <!-- Modal panel -->
            <div x-show="$wire.showVipConfirmModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:w-full sm:max-w-md sm:align-middle p-6 border border-gray-100">
                
                <div class="flex items-center justify-center w-16 h-16 mx-auto bg-amber-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                
                <h3 class="text-xl font-bold text-center text-blue-900 mb-2">Xác nhận dùng lượt tải VIP</h3>
                
                <p class="text-center text-gray-600 mb-6 leading-relaxed">
                    Bạn đang có <strong class="text-amber-600 text-lg">{{ Auth::check() ? Auth::user()->vip_download_quota : 0 }}</strong> lượt tải VIP. <br>
                    Bạn có chắc chắn muốn sử dụng 1 lượt để tải xuống tài liệu này không?
                </p>

                <div class="flex gap-3">
                    <button wire:click="$set('showVipConfirmModal', false)" class="flex-1 rounded-2xl border-2 border-gray-100 hover:bg-gray-50 text-gray-700 py-3 text-sm font-semibold transition">
                        Đóng
                    </button>
                    <button wire:click="executeVipDownload" class="flex-1 rounded-2xl bg-amber-600 hover:bg-amber-700 text-white py-3 text-sm font-semibold shadow-lg shadow-amber-600/20 hover:shadow-amber-700/30 transition">
                        Đồng ý tải
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Guest Email Modal -->
    <div x-show="$wire.showGuestEmailModal" 
         style="display: none;" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="$wire.showGuestEmailModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" 
                 aria-hidden="true" 
                 wire:click="$set('showGuestEmailModal', false)"></div>

            <div x-show="$wire.showGuestEmailModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:w-full sm:max-w-md sm:align-middle p-6 border border-gray-100">
                
                <div class="flex items-center justify-center w-16 h-16 mx-auto bg-blue-100 rounded-full mb-4 text-blue-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                
                <h3 class="text-xl font-bold text-center text-blue-900 mb-2">Nhập Email để nhận Biên lai</h3>
                
                <p class="text-center text-gray-500 mb-6 text-sm">
                    Vui lòng cung cấp email. Hệ thống sẽ gửi hóa đơn và thông tin số lượt tải cho bạn.
                </p>

                <form wire:submit.prevent="continueGuestPurchase" class="space-y-4">
                    <div>
                        <label for="guestEmail" class="block text-sm font-bold text-gray-700 mb-1">Địa chỉ Email <span class="text-red-500">*</span></label>
                        <input type="email" id="guestEmail" wire:model="guestEmail" placeholder="ví dụ: khachhang@gmail.com" class="w-full rounded-2xl border border-gray-100 px-4 py-3 text-sm focus:border-blue-400 focus:outline-none focus:ring-4 focus:ring-blue-100 transition" required>
                        @error('guestEmail') <span class="text-xs text-red-500 font-medium mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" wire:click="$set('showGuestEmailModal', false)" class="flex-1 rounded-2xl border-2 border-gray-100 hover:bg-gray-50 text-gray-700 py-3 text-sm font-semibold transition">
                            Đóng
                        </button>
                        <button type="submit" class="flex-1 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white py-3 text-sm font-semibold shadow-lg shadow-blue-600/20 hover:shadow-blue-700/30 transition">
                            Tiếp tục thanh toán
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



