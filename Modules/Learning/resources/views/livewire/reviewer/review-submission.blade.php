<div class="container mx-auto px-4 py-6" x-data x-on:do-submit-review.window="$wire.submitReview($event.detail)">
    <div class="mb-6">
        <div class="mb-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.reviewer.project.submissions' : 'contributor.reviewer.project.submissions', ['projectId' => $project->id]) }}" class="p-2 text-gray-500 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors shadow-sm" title="Quay lại danh sách đồ án">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Chấm bài nộp</h1>
                    <p class="mt-1 text-sm text-gray-600">{{ $project->title }} - {{ $step->step_name }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                <x-button negative label="Từ chối" wire:click="openReviewModal('rejected')" />
                <x-button positive label="Duyệt nhanh" wire:click="quickApprove" />
            </div>
        </div>
        <x-badge :label="$submission->getStatusLabel()" :color="$submission->getStatusColor()" lg />
    </div>

    <div class="grid grid-cols-3 gap-6">
        <div class="col-span-2 space-y-6">
            <x-card title="Thông tin bài nộp">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Học viên</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $student->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Lần nộp</label>
                            <p class="mt-1 text-sm text-gray-900">Lần {{ $submission->submission_number }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Thời gian nộp</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $submission->submitted_at?->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Trạng thái</label>
                            <x-badge :label="$submission->getStatusLabel()" :color="$submission->getStatusColor()" />
                        </div>
                    </div>

                    @if($submission->hasFile())
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <svg class="h-10 w-10 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                                    </svg>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $submission->file_name }}</p>
                                        <p class="text-sm text-gray-500">{{ $submission->getFileSizeFormatted() }}</p>
                                    </div>
                                </div>
                                <x-button primary label="Tải xuống" wire:click="downloadFile" />
                            </div>
                        </div>
                    @endif

                    @if($submission->hasLink())
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                            <label class="text-sm font-medium text-gray-700">Link</label>
                            <a href="{{ $submission->link_url }}" target="_blank" class="mt-1 flex items-center gap-2 text-blue-600 hover:text-blue-800">
                                {{ Str::limit($submission->link_url, 80) }}
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        </div>
                    @endif

                    @if($submission->notes)
                        <div>
                            <label class="text-sm font-medium text-gray-700">Ghi chú từ học viên</label>
                            <p class="mt-1 text-sm text-gray-600">{{ $submission->notes }}</p>
                        </div>
                    @endif

                    @if($submission->feedback)
                        <div class="rounded-lg border-l-4 border-yellow-400 bg-yellow-50 p-4">
                            <label class="text-sm font-medium text-yellow-800">Feedback từ lần review trước</label>
                            <p class="mt-1 text-sm text-yellow-700">{{ $submission->feedback }}</p>
                        </div>
                    @endif
                </div>
            </x-card>

            @if($allSubmissions->count() > 1)
                <x-card title="Lịch sử nộp bài ({{ $allSubmissions->count() }} lần)">
                    <div class="space-y-2">
                        @foreach($allSubmissions as $sub)
                            <div wire:click="loadSubmission({{ $sub->id }})" class="cursor-pointer hover:bg-gray-50 transition flex items-center justify-between rounded-lg border p-3 {{ $sub->id === $submission->id ? 'border-blue-500 bg-blue-50 shadow-sm' : 'border-gray-200' }}">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-900">Lần {{ $sub->submission_number }}</span>
                                        <x-badge :label="$sub->getStatusLabel()" :color="$sub->getStatusColor()" xs />
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ $sub->submitted_at?->format('d/m/Y H:i') }}</p>
                                </div>
                                @if($sub->feedback)
                                    <p class="text-xs text-gray-600 max-w-xs text-right">{{ Str::limit($sub->feedback, 60) }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </x-card>
            @endif

            @if($reviewHistory->isNotEmpty())
                <x-card title="Lịch sử review">
                    <div class="space-y-3">
                        @foreach($reviewHistory as $review)
                            <div class="rounded-lg border p-4">
                                <div class="mb-2 flex items-center justify-between">
                                    <span class="font-medium">{{ $review->reviewer->name }}</span>
                                    <div class="flex items-center gap-2">
                                        <x-badge :label="$review->getDecisionLabel()" :color="$review->getDecisionColor()" xs />
                                        <span class="text-xs text-gray-500">{{ $review->getReviewedAtForHumans() }}</span>
                                    </div>
                                </div>
                                @if($review->feedback)
                                    <p class="text-sm text-gray-600">{{ $review->feedback }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </x-card>
            @endif
        </div>

        <div class="space-y-6">
            <x-card title="Tiến độ học viên">
                <div class="space-y-2">
                    @foreach($studentProgress as $item)
                        <div class="flex items-center gap-3">
                            @if($item['status'] === 'approved')
                                <svg class="h-6 w-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                            @elseif($item['status'] === 'rejected')
                                <svg class="h-6 w-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                                </svg>
                            @elseif(in_array($item['status'], ['submitted', 'under_review']))
                                <svg class="h-6 w-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                                </svg>
                            @else
                                <svg class="h-6 w-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z"/>
                                </svg>
                            @endif
                            <div class="flex-1">
                                <p class="text-sm font-medium">{{ $item['step']->step_name }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst($item['status']) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-card>

            @if($isProjectCompleted)
                <x-card title="Chấm điểm cuối cùng" class="border-2 border-indigo-200">
                    <div class="space-y-4">
                        <div class="rounded-lg bg-indigo-50 p-3">
                            <p class="text-sm text-indigo-800">Học viên đã hoàn thành tất cả các bước. Bạn có thể chấm điểm dự án lúc này.</p>
                        </div>
                        
                        <x-input label="Điểm (0 - 10)" wire:model.defer="projectScore" type="number" step="0.1" min="0" max="10" placeholder="VD: 8.5" />
                        
                        <x-textarea label="Nhận xét tổng quan" wire:model.defer="projectFeedback" rows="3" placeholder="Đánh giá tổng quan về dự án..." />
                        
                        <x-button primary label="Lưu điểm" wire:click="submitFinalGrade" class="w-full" />
                    </div>
                </x-card>
            @endif

            <x-card title="Hướng dẫn bước này">
                <p class="text-sm text-gray-600">{{ $step->instructions }}</p>
            </x-card>
        </div>
    </div>

    <div x-show="$wire.showReviewModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-800/60 p-4">
        
        <!-- Background overlay -->
        <div class="absolute inset-0" wire:click="closeReviewModal"></div>

        <!-- Modal panel -->
        <div x-show="$wire.showReviewModal" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" 
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" 
             class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl flex flex-col max-h-[90vh] overflow-hidden">
            
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex-shrink-0">
                <div class="flex items-center gap-3">
                    @if($decision === 'rejected')
                        <div class="rounded-full bg-red-100 p-3 text-red-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-red-700">Từ chối bài nộp</span>
                    @else
                        <div class="rounded-full bg-green-100 p-3 text-green-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-green-700">Duyệt bài nộp</span>
                    @endif
                </div>
            </div>

            <div class="px-6 py-4 overflow-y-auto flex-1">
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div wire:click="$set('decision', 'approved')" class="relative flex cursor-pointer rounded-xl border-2 p-5 shadow-sm transition {{ $decision === 'approved' ? 'border-green-600 bg-green-50 ring-2 ring-green-600' : 'border-gray-300 hover:border-gray-500 bg-white' }}">
                            <div class="flex w-full items-center justify-between">
                                <div class="flex items-center">
                                    <div class="text-base">
                                        <p class="font-bold {{ $decision === 'approved' ? 'text-green-900' : 'text-gray-900' }}">Duyệt bài</p>
                                        <p class="mt-1 text-sm {{ $decision === 'approved' ? 'text-green-700' : 'text-gray-600' }}">Học viên được tiếp tục</p>
                                    </div>
                                </div>
                                @if($decision === 'approved')
                                    <svg class="h-8 w-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <div class="h-6 w-6 rounded-full border-2 border-gray-400"></div>
                                @endif
                            </div>
                        </div>

                        <div wire:click="$set('decision', 'rejected')" class="relative flex cursor-pointer rounded-xl border-2 p-5 shadow-sm transition {{ $decision === 'rejected' ? 'border-red-600 bg-red-50 ring-2 ring-red-600' : 'border-gray-300 hover:border-gray-500 bg-white' }}">
                            <div class="flex w-full items-center justify-between">
                                <div class="flex items-center">
                                    <div class="text-base">
                                        <p class="font-bold {{ $decision === 'rejected' ? 'text-red-900' : 'text-gray-900' }}">Từ chối</p>
                                        <p class="mt-1 text-sm {{ $decision === 'rejected' ? 'text-red-700' : 'text-gray-600' }}">Yêu cầu làm lại</p>
                                    </div>
                                </div>
                                @if($decision === 'rejected')
                                    <svg class="h-8 w-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <div class="h-6 w-6 rounded-full border-2 border-gray-400"></div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2 mt-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Feedback chi tiết ({{ $decision === 'rejected' ? 'Bắt buộc ít nhất 10 ký tự' : 'Tùy chọn' }})
                        </label>
                        <textarea 
                            wire:model.live.debounce.300ms="feedback" 
                            rows="4" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-base"
                            placeholder="Nhận xét chi tiết cho học viên..."></textarea>
                    </div>

                    @if($decision === 'rejected')
                        <div class="flex items-start rounded-lg bg-red-100 p-4 border-l-4 border-red-500 mt-4">
                            <div class="flex-shrink-0 mt-0.5">
                                <svg class="h-6 w-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-base font-bold text-red-900">Thông báo từ chối</h3>
                                <div class="mt-1 text-sm text-red-800">
                                    <p>Học viên sẽ nhận được thông báo từ chối ngay lập tức kèm theo feedback này. Vui lòng ghi rõ các lỗi để học viên khắc phục.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-start rounded-lg bg-green-100 p-4 border-l-4 border-green-500 mt-4">
                            <div class="flex-shrink-0 mt-0.5">
                                <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-base font-bold text-green-900">Thông báo duyệt</h3>
                                <div class="mt-1 text-sm text-green-800">
                                    <p>Học viên sẽ được thông báo đã hoàn thành và có thể chuyển sang bước tiếp theo ngay lập tức.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3 flex-shrink-0">
                <button type="button" wire:click="closeReviewModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Hủy
                </button>
                
                @if($decision === 'rejected')
                    @if(strlen(trim($feedback)) < 10)
                        <button type="button" disabled class="px-4 py-2 text-sm font-medium text-white bg-gray-400 border border-transparent rounded-md shadow-sm cursor-not-allowed">
                            Từ chối bài nộp
                        </button>
                    @else
                        <button type="button" wire:click="submitReview(false)" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                            <span wire:loading.remove wire:target="submitReview">Từ chối bài nộp</span>
                            <span wire:loading wire:target="submitReview">Đang xử lý...</span>
                        </button>
                    @endif
                @else
                    <button type="button" wire:click="submitReview(false)" class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <span wire:loading.remove wire:target="submitReview">Duyệt bài nộp</span>
                        <span wire:loading wire:target="submitReview">Đang xử lý...</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
