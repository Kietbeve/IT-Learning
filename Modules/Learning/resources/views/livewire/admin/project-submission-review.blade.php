<div class="p-6 max-w-6xl mx-auto">
    {{-- Success/Error Messages --}}
    @if (session()->has('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-4 text-sm font-semibold">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-4 text-sm font-semibold">
            ✗ {{ session('error') }}
        </div>
    @endif

    {{-- Back Button --}}
    <div class="mb-4">
        <a href="{{ route('admin.learning.submissions.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-cyan-600 transition-colors">
            ← Quay lại danh sách
        </a>
    </div>

    {{-- Header --}}
    <div class="bg-white border border-gray-200 rounded-2xl p-6 mb-6 shadow-sm">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h1 class="text-2xl font-black text-gray-900 mb-2">Chi tiết Project Submission #{{ $submission->id }}</h1>
                <p class="text-sm text-gray-600">Xem và đánh giá bài nộp project từ học viên</p>
            </div>
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold
                {{ $submission->status === 'passed' ? 'bg-green-100 text-green-700 border border-green-300' : '' }}
                {{ $submission->status === 'failed' ? 'bg-red-100 text-red-700 border border-red-300' : '' }}
                {{ $submission->status === 'in_review' ? 'bg-blue-100 text-blue-700 border border-blue-300' : '' }}
                {{ in_array($submission->status, ['submitted', 'resubmitted']) ? 'bg-yellow-100 text-yellow-700 border border-yellow-300' : '' }}">
                @if($submission->status === 'passed') ✓ Đã đạt
                @elseif($submission->status === 'failed') ✗ Cần làm lại
                @elseif($submission->status === 'in_review') 👁 Đang review
                @elseif($submission->status === 'resubmitted') 🔄 Đã nộp lại
                @else ⏳ Chờ duyệt
                @endif
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <span class="text-gray-500 font-semibold">Ngày nộp:</span>
                <span class="text-gray-900 font-bold ml-2">{{ $submission->submitted_at->format('d/m/Y H:i') }}</span>
            </div>
            <div>
                <span class="text-gray-500 font-semibold">Lần nộp:</span>
                <span class="text-gray-900 font-bold ml-2">{{ $submission->submission_no }}/{{ $submission->project->max_resubmissions }}</span>
            </div>
            @if($submission->reviewed_at)
                <div>
                    <span class="text-gray-500 font-semibold">Đã review:</span>
                    <span class="text-gray-900 font-bold ml-2">{{ $submission->reviewed_at->format('d/m/Y H:i') }}</span>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Project Information --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-lg font-black text-gray-900 mb-4 flex items-center gap-2">
                    📋 Thông tin Project
                </h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-bold text-gray-700">Tên project:</span>
                        <p class="text-sm text-gray-900 mt-1">{{ $submission->project->title }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-gray-700">Mô tả:</span>
                        <p class="text-sm text-gray-600 mt-1 leading-relaxed">{{ $submission->project->description }}</p>
                    </div>
                    @if($submission->project->starter_code_url)
                        <div>
                            <span class="text-sm font-bold text-gray-700">Starter Code:</span>
                            <a href="{{ $submission->project->starter_code_url }}" target="_blank" class="text-sm text-cyan-600 hover:text-cyan-800 underline ml-2">
                                {{ $submission->project->starter_code_url }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Submission Details --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-lg font-black text-gray-900 mb-4 flex items-center gap-2">
                    🚀 Bài nộp của học viên
                </h3>
                <div class="space-y-4">
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <span class="text-sm font-bold text-gray-700">GitHub Repository:</span>
                        <a href="{{ $submission->github_url }}" target="_blank" class="block text-sm text-cyan-600 hover:text-cyan-800 underline mt-1 break-all">
                            {{ $submission->github_url }}
                        </a>
                    </div>

                    @if($submission->live_demo_url)
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <span class="text-sm font-bold text-gray-700">Live Demo:</span>
                            <a href="{{ $submission->live_demo_url }}" target="_blank" class="block text-sm text-cyan-600 hover:text-cyan-800 underline mt-1 break-all">
                                {{ $submission->live_demo_url }}
                            </a>
                        </div>
                    @endif

                    @if($submission->attachment_path)
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <span class="text-sm font-bold text-gray-700">File đính kèm:</span>
                            <a href="{{ asset('storage/' . $submission->attachment_path) }}" target="_blank" class="inline-flex items-center gap-2 mt-2 bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition-colors">
                                📎 Tải xuống file
                            </a>
                        </div>
                    @endif

                    @if($submission->note)
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <span class="text-sm font-bold text-gray-700">Ghi chú từ học viên:</span>
                            <p class="text-sm text-gray-600 mt-2 leading-relaxed">{{ $submission->note }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Review Section --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-lg font-black text-gray-900 mb-4 flex items-center gap-2">
                    ✍️ Đánh giá
                </h3>

                @if($submission->feedback)
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4">
                        <span class="text-sm font-bold text-blue-900">Feedback hiện tại:</span>
                        <p class="text-sm text-gray-700 mt-2 leading-relaxed">{{ $submission->feedback }}</p>
                        @if($submission->reviewer)
                            <p class="text-xs text-gray-500 mt-2">Reviewer: {{ $submission->reviewer->name }}</p>
                        @endif
                    </div>
                @endif

                @if(!$showReviewForm)
                    <div class="flex gap-3">
                        @if(in_array($submission->status, ['submitted', 'resubmitted']))
                            <button wire:click="markAsInReview" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-4 py-2 rounded-lg transition-colors">
                                👁 Đánh dấu đang review
                            </button>
                        @endif
                        <button wire:click="toggleReviewForm" class="bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold px-4 py-2 rounded-lg transition-colors">
                            ✍️ {{ $submission->reviewed_at ? 'Cập nhật đánh giá' : 'Đánh giá project' }}
                        </button>
                    </div>
                @else
                    <form wire:submit.prevent="submitReview" class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Kết quả đánh giá <span class="text-red-500">*</span></label>
                            <select wire:model="status" class="w-full border border-gray-300 rounded-xl p-3 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500" required>
                                <option value="">-- Chọn kết quả --</option>
                                <option value="in_review">Đang review</option>
                                <option value="passed">Đạt - Chấp nhận</option>
                                <option value="failed">Không đạt - Cần làm lại</option>
                            </select>
                            @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Nhận xét và feedback</label>
                            <textarea wire:model="feedback" rows="6" placeholder="Nhập feedback chi tiết cho học viên..." class="w-full border border-gray-300 rounded-xl p-3 text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500"></textarea>
                            @error('feedback') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold px-6 py-2 rounded-lg transition-colors">
                                💾 Lưu đánh giá
                            </button>
                            <button type="button" wire:click="toggleReviewForm" class="bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-bold px-6 py-2 rounded-lg transition-colors">
                                Hủy
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Student Information --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-lg font-black text-gray-900 mb-4">👤 Học viên</h3>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white text-lg font-bold">
                        {{ strtoupper(substr($submission->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-sm font-bold text-gray-900">{{ $submission->user->name }}</div>
                        <div class="text-xs text-gray-500">{{ $submission->user->email }}</div>
                    </div>
                </div>
                <div class="text-xs text-gray-600">
                    <div class="mb-2">
                        <span class="font-semibold">Lộ trình:</span>
                        <span class="block mt-1">{{ $submission->enrollment->roadmap->title ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-lg font-black text-gray-900 mb-4">⚡ Thao tác nhanh</h3>
                <div class="space-y-2">
                    <a href="{{ $submission->github_url }}" target="_blank" class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-semibold px-4 py-2 rounded-lg text-center transition-colors">
                        🔗 Mở GitHub
                    </a>
                    @if($submission->live_demo_url)
                        <a href="{{ $submission->live_demo_url }}" target="_blank" class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-semibold px-4 py-2 rounded-lg text-center transition-colors">
                            🌐 Mở Live Demo
                        </a>
                    @endif
                    @if($submission->attachment_path)
                        <a href="{{ asset('storage/' . $submission->attachment_path) }}" target="_blank" class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-semibold px-4 py-2 rounded-lg text-center transition-colors">
                            📎 Tải file đính kèm
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
