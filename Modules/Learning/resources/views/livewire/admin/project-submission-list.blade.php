<div class="p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Quản lý Project Submissions</h2>
        <p class="text-sm text-gray-600">Xem và đánh giá các bài nộp project từ học viên</p>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tổng số</div>
            <div class="text-2xl font-black text-gray-900">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 shadow-sm">
            <div class="text-xs font-semibold text-yellow-700 uppercase tracking-wider mb-1">Chờ duyệt</div>
            <div class="text-2xl font-black text-yellow-700">{{ $stats['pending'] }}</div>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 shadow-sm">
            <div class="text-xs font-semibold text-blue-700 uppercase tracking-wider mb-1">Đang review</div>
            <div class="text-2xl font-black text-blue-700">{{ $stats['in_review'] }}</div>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 shadow-sm">
            <div class="text-xs font-semibold text-green-700 uppercase tracking-wider mb-1">Đã đạt</div>
            <div class="text-2xl font-black text-green-700">{{ $stats['passed'] }}</div>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 shadow-sm">
            <div class="text-xs font-semibold text-red-700 uppercase tracking-wider mb-1">Cần làm lại</div>
            <div class="text-2xl font-black text-red-700">{{ $stats['failed'] }}</div>
        </div>
    </div>

    {{-- Filters and Search --}}
    <div class="bg-white border border-gray-200 rounded-xl p-4 mb-6 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Lọc theo trạng thái</label>
                <select wire:model.live="statusFilter" class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                    <option value="all">Tất cả</option>
                    <option value="submitted">Đã nộp</option>
                    <option value="resubmitted">Đã nộp lại</option>
                    <option value="in_review">Đang review</option>
                    <option value="passed">Đã đạt</option>
                    <option value="failed">Cần làm lại</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Tìm kiếm</label>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Tìm theo tên học viên, email, project..." 
                       class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
            </div>
        </div>
    </div>

    {{-- Submissions Table --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <button wire:click="sortBy('id')" class="text-xs font-bold text-gray-700 uppercase tracking-wider hover:text-cyan-600 flex items-center gap-1">
                                ID
                                @if($sortField === 'id')
                                    <span class="text-cyan-600">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Học viên</span>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Project</span>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <button wire:click="sortBy('submission_no')" class="text-xs font-bold text-gray-700 uppercase tracking-wider hover:text-cyan-600 flex items-center gap-1">
                                Lần nộp
                                @if($sortField === 'submission_no')
                                    <span class="text-cyan-600">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <button wire:click="sortBy('status')" class="text-xs font-bold text-gray-700 uppercase tracking-wider hover:text-cyan-600 flex items-center gap-1">
                                Trạng thái
                                @if($sortField === 'status')
                                    <span class="text-cyan-600">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <button wire:click="sortBy('submitted_at')" class="text-xs font-bold text-gray-700 uppercase tracking-wider hover:text-cyan-600 flex items-center gap-1">
                                Ngày nộp
                                @if($sortField === 'submitted_at')
                                    <span class="text-cyan-600">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-center">
                            <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Thao tác</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($submissions as $submission)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm font-mono text-gray-600">
                                #{{ $submission->id }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr($submission->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $submission->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $submission->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm font-semibold text-gray-900">{{ $submission->project->title }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm font-bold text-gray-700">{{ $submission->submission_no }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold
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
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $submission->submitted_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route($routePrefix . '.submissions.review', $submission->id) }}" 
                                   class="inline-flex items-center gap-1 bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-colors">
                                    👁 Xem chi tiết
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">
                                Không tìm thấy submission nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
            {{ $submissions->links() }}
        </div>
    </div>
</div>
