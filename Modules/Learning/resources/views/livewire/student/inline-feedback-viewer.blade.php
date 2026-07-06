<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">
            Feedback chi tiết ({{ $this->getTotalFeedbackCount() }})
        </h3>
        
        <div class="flex items-center gap-3">
            @if($this->getUnresolvedFeedbackCount() > 0)
                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-full">
                    {{ $this->getUnresolvedFeedbackCount() }} chưa xử lý
                </span>
            @endif
            
            <button 
                wire:click="toggleResolved"
                class="px-4 py-2 text-sm font-medium {{ $showResolved ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }} rounded-lg hover:opacity-80 transition-opacity"
            >
                {{ $showResolved ? 'Ẩn đã xử lý' : 'Hiện đã xử lý' }}
            </button>
        </div>
    </div>

    <!-- General Feedback -->
    @if(count($groupedFeedbacks['general']) > 0)
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <h4 class="text-base font-semibold text-gray-900 mb-3 flex items-center">
                <span class="mr-2">💬</span>
                Nhận xét chung
            </h4>
            
            <div class="space-y-3">
                @foreach($groupedFeedbacks['general'] as $feedback)
                    <div class="p-3 bg-gray-50 rounded-lg {{ $feedback->is_resolved ? 'opacity-60' : '' }}">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center text-sm text-gray-600">
                                <img src="{{ $feedback->grader->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($feedback->grader->name) }}" 
                                     class="w-6 h-6 rounded-full mr-2" 
                                     alt="{{ $feedback->grader->name }}">
                                <span class="font-medium">{{ $feedback->grader->name }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $feedback->created_at->diffForHumans() }}</span>
                            </div>
                            
                            @if(!$feedback->is_resolved)
                                <button 
                                    wire:click="markFeedbackResolved({{ $feedback->id }})"
                                    class="text-xs text-blue-600 hover:text-blue-800"
                                >
                                    Đánh dấu đã xử lý
                                </button>
                            @else
                                <span class="text-xs text-green-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Đã xử lý
                                </span>
                            @endif
                        </div>
                        
                        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $feedback->comment }}</p>
                        
                        @if($feedback->score !== null)
                            <div class="mt-2 text-sm font-medium text-blue-600">
                                Điểm: {{ $feedback->score }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Criterion Feedback -->
    @if(count($groupedFeedbacks['criterion']) > 0)
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <h4 class="text-base font-semibold text-gray-900 mb-3 flex items-center">
                <span class="mr-2">📋</span>
                Đánh giá theo tiêu chí
            </h4>
            
            <div class="space-y-3">
                @foreach($groupedFeedbacks['criterion'] as $feedback)
                    <div class="p-3 border-l-4 {{ $feedback->score >= 80 ? 'border-green-500 bg-green-50' : ($feedback->score >= 60 ? 'border-yellow-500 bg-yellow-50' : 'border-red-500 bg-red-50') }} {{ $feedback->is_resolved ? 'opacity-60' : '' }}">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                @if($feedback->rubricItem)
                                    <h5 class="text-sm font-semibold text-gray-900">{{ $feedback->rubricItem->name }}</h5>
                                @endif
                                <div class="flex items-center text-xs text-gray-600 mt-1">
                                    <span>{{ $feedback->grader->name }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $feedback->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            
                            @if($feedback->score !== null)
                                <span class="px-2 py-1 text-sm font-bold rounded {{ $feedback->score >= 80 ? 'bg-green-200 text-green-800' : ($feedback->score >= 60 ? 'bg-yellow-200 text-yellow-800' : 'bg-red-200 text-red-800') }}">
                                    {{ $feedback->score }}
                                </span>
                            @endif
                        </div>
                        
                        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $feedback->comment }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Inline Feedback by File -->
    @if(count($groupedFeedbacks['inline_by_file']) > 0)
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <h4 class="text-base font-semibold text-gray-900 mb-3 flex items-center">
                <span class="mr-2">📍</span>
                Nhận xét inline trên code
            </h4>
            
            <div class="space-y-4">
                @foreach($groupedFeedbacks['inline_by_file'] as $fileName => $fileFeedbacks)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-2 border-b border-gray-200 flex items-center justify-between">
                            <h5 class="text-sm font-semibold text-gray-900 font-mono">{{ $fileName }}</h5>
                            <span class="text-xs text-gray-600">{{ count($fileFeedbacks) }} comments</span>
                        </div>
                        
                        <div class="divide-y divide-gray-100">
                            @foreach($fileFeedbacks as $feedback)
                                <div class="p-3 hover:bg-gray-50 {{ $feedback->is_resolved ? 'opacity-60' : '' }}">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 w-12 text-right">
                                            <span class="inline-block px-2 py-1 text-xs font-mono font-semibold bg-blue-100 text-blue-700 rounded">
                                                L{{ $feedback->reference_line }}
                                            </span>
                                        </div>
                                        
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center text-xs text-gray-600 mb-1">
                                                <span class="font-medium">{{ $feedback->grader->name }}</span>
                                                <span class="mx-2">•</span>
                                                <span>{{ $feedback->created_at->diffForHumans() }}</span>
                                                
                                                @if(!$feedback->is_resolved)
                                                    <button 
                                                        wire:click="markFeedbackResolved({{ $feedback->id }})"
                                                        class="ml-auto text-blue-600 hover:text-blue-800"
                                                    >
                                                        Đã xử lý
                                                    </button>
                                                @else
                                                    <span class="ml-auto text-green-600 flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                        </svg>
                                                        Resolved
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <p class="text-sm text-gray-700 whitespace-pre-wrap bg-white p-2 rounded border border-gray-200">
                                                {{ $feedback->comment }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Empty State -->
    @if($feedbacks->isEmpty())
        <div class="bg-white border border-gray-200 rounded-lg p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Chưa có feedback</h3>
            <p class="mt-2 text-gray-600">Giảng viên sẽ chấm bài và gửi feedback cho bạn sớm</p>
        </div>
    @endif
</div>
