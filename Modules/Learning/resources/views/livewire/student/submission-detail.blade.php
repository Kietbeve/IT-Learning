<div class="p-6 max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="javascript:history.back()" class="text-sm text-cyan-600 hover:text-cyan-700 mb-2 inline-block">← Quay lại</a>
        <h2 class="text-2xl font-bold text-gray-900">Chi tiết bài nộp của bạn</h2>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
    @endif

    {{-- Submission Status Card --}}
    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-900">
                    @if($submissionType === 'project')
                        Project: {{ $submission->project->title }}
                    @else
                        Assignment: {{ $submission->assignment->title }}
                    @endif
                </h3>
                <p class="text-sm text-gray-600 mt-1">
                    Nộp lúc: {{ $submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i') : 'Chưa nộp' }}
                </p>
            </div>
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold
                @if($submissionType === 'project')
                    {{ $submission->status === 'passed' ? 'bg-green-100 text-green-700 border border-green-300' : '' }}
                    {{ $submission->status === 'failed' ? 'bg-red-100 text-red-700 border border-red-300' : '' }}
                    {{ $submission->status === 'in_review' ? 'bg-blue-100 text-blue-700 border border-blue-300' : '' }}
                    {{ in_array($submission->status, ['submitted', 'resubmitted']) ? 'bg-yellow-100 text-yellow-700 border border-yellow-300' : '' }}
                @else
                    {{ $submission->status === 'graded' ? 'bg-green-100 text-green-700 border border-green-300' : '' }}
                    {{ $submission->status === 'needs_revision' ? 'bg-orange-100 text-orange-700 border border-orange-300' : '' }}
                    {{ $submission->status === 'in_review' ? 'bg-blue-100 text-blue-700 border border-blue-300' : '' }}
                    {{ $submission->status === 'submitted' ? 'bg-yellow-100 text-yellow-700 border border-yellow-300' : '' }}
                @endif">
                @if($submissionType === 'project')
                    @if($submission->status === 'passed') ✓ Đã đạt
                    @elseif($submission->status === 'failed') ✗ Cần làm lại
                    @elseif($submission->status === 'in_review') 👁 Đang review
                    @elseif($submission->status === 'resubmitted') 🔄 Đã nộp lại
                    @else ⏳ Chờ duyệt
                    @endif
                @else
                    @if($submission->status === 'graded') ✓ Đã chấm
                    @elseif($submission->status === 'needs_revision') ↻ Cần sửa
                    @elseif($submission->status === 'in_review') 👁 Đang chấm
                    @else ⏳ Chờ chấm
                    @endif
                @endif
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            {{-- Submitted Content --}}
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Nội dung bài nộp</h3>
                @if($submission->github_url)
                    <div class="mb-3">
                        <span class="text-sm font-semibold text-gray-700">GitHub Repository:</span>
                        <a href="{{ $submission->github_url }}" target="_blank" class="block text-sm text-cyan-600 hover:underline mt-1">{{ $submission->github_url }}</a>
                    </div>
                @endif
                @if($submission->live_demo_url)
                    <div class="mb-3">
                        <span class="text-sm font-semibold text-gray-700">Live Demo:</span>
                        <a href="{{ $submission->live_demo_url }}" target="_blank" class="block text-sm text-cyan-600 hover:underline mt-1">{{ $submission->live_demo_url }}</a>
                    </div>
                @endif
                @if($submissionType === 'project' && $submission->attachment_path)
                    <div class="mb-3">
                        <span class="text-sm font-semibold text-gray-700">File đính kèm:</span>
                        <a href="{{ asset('storage/' . $submission->attachment_path) }}" target="_blank" class="inline-flex items-center gap-2 mt-2 text-sm text-cyan-600 hover:underline">
                            📎 Tải xuống
                        </a>
                    </div>
                @endif
                @if($submissionType === 'assignment' && $submission->attachments && $submission->attachments->isNotEmpty())
                    <div class="mb-3">
                        <span class="text-sm font-semibold text-gray-700 block mb-2">Files đính kèm:</span>
                        <div class="space-y-2">
                            @foreach($submission->attachments as $attachment)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span class="text-sm">📎 {{ $attachment->file_name }}</span>
                                    <button wire:click="downloadAttachment({{ $attachment->id }})" class="text-xs bg-cyan-600 text-white px-3 py-1 rounded hover:bg-cyan-700">Tải xuống</button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if($submissionType === 'project' && $submission->note)
                    <div class="mt-4">
                        <span class="text-sm font-semibold text-gray-700 block mb-2">Ghi chú của bạn:</span>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm">{{ $submission->note }}</div>
                    </div>
                @endif
                @if($submissionType === 'assignment' && $submission->notes)
                    <div class="mt-4">
                        <span class="text-sm font-semibold text-gray-700 block mb-2">Ghi chú của bạn:</span>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm">{{ $submission->notes }}</div>
                    </div>
                @endif
            </div>

            {{-- Feedback Section --}}
            @if($submission->feedback || ($submissionType === 'project' && $submission->status !== 'submitted'))
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Nhận xét từ giáo viên</h3>
                    @if($submission->feedback)
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $submission->feedback }}</p>
                        </div>
                        @if($submissionType === 'project' && $submission->reviewer)
                            <p class="text-xs text-gray-500 mt-3">
                                Reviewer: {{ $submission->reviewer->name }} • {{ $submission->reviewed_at->format('d/m/Y H:i') }}
                            </p>
                        @endif
                        @if($submissionType === 'assignment' && $submission->grader)
                            <p class="text-xs text-gray-500 mt-3">
                                Người chấm: {{ $submission->grader->name }} • {{ $submission->graded_at->format('d/m/Y H:i') }}
                            </p>
                        @endif
                    @else
                        <p class="text-sm text-gray-500 italic">Chưa có nhận xét</p>
                    @endif
                </div>
            @endif

            {{-- Rubric Scores --}}
            @if($submissionType === 'project' && $submission->rubricScores && $submission->rubricScores->isNotEmpty())
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Chi tiết điểm theo tiêu chí</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-2 text-left font-bold text-gray-700">Tiêu chí</th>
                                    <th class="px-4 py-2 text-left font-bold text-gray-700">Điểm</th>
                                    <th class="px-4 py-2 text-left font-bold text-gray-700">Nhận xét</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($submission->rubricScores as $rubricScore)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="font-semibold text-gray-900">{{ $rubricScore->criteria->title }}</div>
                                            @if($rubricScore->criteria->description)
                                                <div class="text-xs text-gray-500 mt-1">{{ $rubricScore->criteria->description }}</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="font-bold {{ $rubricScore->score >= $rubricScore->criteria->max_points * 0.8 ? 'text-green-600' : 'text-gray-700' }}">
                                                {{ $rubricScore->score }}/{{ $rubricScore->criteria->max_points }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">{{ $rubricScore->notes ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if($submissionType === 'assignment' && $submission->rubricItems && $submission->rubricItems->isNotEmpty())
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Chi tiết điểm theo tiêu chí</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-2 text-left font-bold text-gray-700">Tiêu chí</th>
                                    <th class="px-4 py-2 text-left font-bold text-gray-700">Điểm</th>
                                    <th class="px-4 py-2 text-left font-bold text-gray-700">Nhận xét</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($submission->rubricItems as $item)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="font-semibold text-gray-900">{{ $item->criterion }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="font-bold {{ $item->points_earned >= $item->max_points * 0.8 ? 'text-green-600' : 'text-gray-700' }}">
                                                {{ $item->points_earned }}/{{ $item->max_points }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">{{ $item->feedback ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Score Card --}}
            @if($submission->score !== null)
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-3">Điểm số</h3>
                    <div class="text-center">
                        <div class="text-5xl font-black mb-2
                            @if($submissionType === 'project')
                                {{ $submission->isPassed() ? 'text-green-600' : 'text-red-600' }}
                            @else
                                {{ $submission->score >= 80 ? 'text-green-600' : '' }}
                                {{ $submission->score >= 60 && $submission->score < 80 ? 'text-blue-600' : '' }}
                                {{ $submission->score >= 40 && $submission->score < 60 ? 'text-orange-600' : '' }}
                                {{ $submission->score < 40 ? 'text-red-600' : '' }}
                            @endif">
                            {{ $submission->score }}
                        </div>
                        <div class="text-sm text-gray-600">
                            @if($submissionType === 'project')
                                / {{ $submission->project->max_score ?? 100 }} điểm
                            @else
                                / {{ $submission->assignment->max_score }} điểm
                            @endif
                        </div>
                        @if($submissionType === 'project' && $submission->getScorePercentage())
                            <div class="mt-3 text-lg font-bold {{ $submission->isPassed() ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($submission->getScorePercentage(), 1) }}%
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-3">Trạng thái</h3>
                    <div class="text-center text-gray-500">
                        <div class="text-3xl mb-2">⏳</div>
                        <p class="text-sm">Đang chờ chấm điểm</p>
                    </div>
                </div>
            @endif

            {{-- Info Card --}}
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-3">Thông tin</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-600">Lần nộp:</span>
                        <span class="font-bold text-gray-900 ml-2">
                            @if($submissionType === 'project')
                                {{ $submission->submission_no }}
                            @else
                                {{ $submission->attempt_number }}
                            @endif
                        </span>
                    </div>
                    @if($submissionType === 'assignment' && $submission->deadline)
                        <div>
                            <span class="text-gray-600">Deadline:</span>
                            <span class="font-bold text-gray-900 ml-2">{{ $submission->deadline->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                    @if($submissionType === 'assignment' && $submission->is_late)
                        <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                            <span class="text-xs font-bold text-red-700">⚠️ Nộp muộn {{ $submission->days_late }} ngày</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
