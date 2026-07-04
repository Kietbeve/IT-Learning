<div class="max-w-3xl mx-auto">
    <div class="mb-4">
        <button onclick="history.back()"
                class="flex items-center gap-2 text-sm text-gray-600 hover:text-cyan-700 transition-colors">
            ← Quay lại
        </button>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
        <h2 class="text-xl font-bold text-gray-900 mb-6">📝 Tạo thảo luận mới</h2>

        @if(session()->has('error'))
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit="submit" class="space-y-5">
            <div>
                <label class="block text-sm font-bold text-gray-800 mb-1.5">Tiêu đề <span class="text-red-500">*</span></label>
                <input type="text" wire:model="title"
                       class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500"
                       placeholder="Nhập tiêu đề thảo luận...">
                @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-800 mb-1.5">Nội dung <span class="text-red-500">*</span></label>
                <textarea wire:model="content" rows="6"
                          class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500"
                          placeholder="Nhập nội dung chi tiết..."></textarea>
                @error('content') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" wire:click="cancel"
                        class="px-5 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Hủy
                </button>
                <button type="submit"
                        class="px-6 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                    Đăng thảo luận
                </button>
            </div>
        </form>
    </div>
</div>
