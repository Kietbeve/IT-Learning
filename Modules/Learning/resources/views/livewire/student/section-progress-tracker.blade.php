<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <!-- Overall Progress Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-lg font-semibold text-gray-900">Tiến độ học tập</h3>
            <span class="text-2xl font-bold text-blue-600">{{ number_format($overallCompletion, 1) }}%</span>
        </div>
        
        <!-- Overall Progress Bar -->
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div 
                class="h-3 rounded-full transition-all duration-300 {{ $this->getProgressColorClass($overallCompletion) }}"
                style="width: {{ $overallCompletion }}%"
            ></div>
        </div>

        <!-- Stats -->
        <div class="flex items-center gap-4 mt-4 text-sm text-gray-600">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ number_format($totalHours, 1) }} giờ học
            </div>
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                {{ count($sectionsProgress) }} chương
            </div>
        </div>
    </div>

    <!-- Sections List -->
    <div class="space-y-4">
        @foreach($sectionsProgress as $index => $sectionProgress)
            @php
                $section = $sectionProgress['section'];
                $progress = $sectionProgress['progress'];
                $isLocked = $sectionProgress['is_locked'];
            @endphp

            <div class="border border-gray-200 rounded-lg p-4 {{ $isLocked ? 'bg-gray-50 opacity-60' : 'bg-white' }}">
                <!-- Section Header -->
                <div class="flex items-start gap-3">
                    <!-- Status Icon -->
                    <div class="flex-shrink-0 mt-1">
                        <span class="text-2xl {{ $this->getSectionStatusClass($sectionProgress) }}">
                            {{ $this->getSectionStatusIcon($sectionProgress) }}
                        </span>
                    </div>

                    <!-- Section Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-semibold text-gray-500">CHƯƠNG {{ $index + 1 }}</span>
                            @if($section->is_required)
                                <span class="px-2 py-0.5 text-xs font-medium bg-red-100 text-red-800 rounded">Bắt buộc</span>
                            @endif
                        </div>

                        <h4 class="text-base font-semibold text-gray-900 mb-1">
                            {{ $section->title }}
                        </h4>

                        @if($section->description)
                            <p class="text-sm text-gray-600 mb-2">{{ $section->description }}</p>
                        @endif

                        <!-- Progress Info -->
                        <div class="flex items-center gap-4 text-sm text-gray-600 mb-2">
                            <span>{{ $progress->completed_lessons }}/{{ $progress->total_lessons }} bài học</span>
                            
                            @if($section->estimated_hours)
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    ~{{ $section->estimated_hours }} giờ
                                </span>
                            @endif
                        </div>

                        <!-- Progress Bar -->
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                            <div 
                                class="h-2 rounded-full transition-all duration-300 {{ $this->getProgressColorClass($progress->completion_percent) }}"
                                style="width: {{ $progress->completion_percent }}%"
                            ></div>
                        </div>

                        <!-- Status Text -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm {{ $this->getSectionStatusClass($sectionProgress) }}">
                                {{ $this->getSectionStatusText($sectionProgress) }}
                            </span>
                            <span class="text-sm font-semibold text-gray-700">
                                {{ number_format($progress->completion_percent, 0) }}%
                            </span>
                        </div>

                        <!-- Prerequisite Info -->
                        @if($isLocked && $section->prerequisite_section_id)
                            <div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded text-sm text-yellow-800">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                Cần hoàn thành chương trước để mở khóa
                            </div>
                        @endif

                        <!-- Action Button -->
                        @if(!$isLocked)
                            <div class="mt-3">
                                @if($progress->status === 'not_started')
                                    <button 
                                        wire:click="startSection({{ $section->id }})"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors"
                                    >
                                        Bắt đầu học
                                    </button>
                                @elseif($progress->status === 'in_progress')
                                    <a 
                                        href="{{ route('learning.section.show', [$roadmap->id, $section->id]) }}"
                                        class="inline-block px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors"
                                    >
                                        Tiếp tục học
                                    </a>
                                @elseif($progress->status === 'completed')
                                    <a 
                                        href="{{ route('learning.section.show', [$roadmap->id, $section->id]) }}"
                                        class="inline-block px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors"
                                    >
                                        Ôn tập lại
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
