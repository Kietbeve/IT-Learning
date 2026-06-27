<div class="max-w-2xl mx-auto px-4 sm:px-6 py-8 animate-fade-in">
    <div class="mb-6">
        <a href="/admin/collaborators" wire:navigate class="inline-flex items-center gap-1 text-xs font-bold text-slate-400 hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Quay lại danh sách
        </a>
        <h1 class="text-xl font-black text-white uppercase tracking-wide mt-3">Thêm Cộng Tác Viên Mới</h1>
    </div>

    <div class="bg-slate-950/40 border border-slate-800/80 rounded-2xl p-6 backdrop-blur-md shadow-xl">
        <form wire:submit.prevent="save" class="space-y-5">
            <div>
                <label for="name" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Họ và tên <span class="text-rose-500">*</span></label>
                <input type="text" id="name" wire:model="name" class="w-full bg-slate-900/60 border @error('name') border-rose-500/50 focus:border-rose-500 focus:ring-rose-500 @else border-slate-800 focus:border-blue-500 focus:ring-blue-500 @enderror rounded-xl px-4 py-3 text-xs text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-1 transition-all" placeholder="Nhập tên đầy đủ của CTV...">
                @error('name') <span class="text-[11px] text-rose-500 block mt-1.5">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Địa chỉ Email <span class="text-rose-500">*</span></label>
                <input type="email" id="email" wire:model="email" class="w-full bg-slate-900/60 border @error('email') border-rose-500/50 focus:border-rose-500 focus:ring-rose-500 @else border-slate-800 focus:border-blue-500 focus:ring-blue-500 @enderror rounded-xl px-4 py-3 text-xs text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-1 transition-all" placeholder="username@devacademy.edu.vn">
                @error('email') <span class="text-[11px] text-rose-500 block mt-1.5">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Mật khẩu khởi tạo <span class="text-rose-500">*</span></label>
                <input type="password" id="password" wire:model="password" class="w-full bg-slate-900/60 border @error('password') border-rose-500/50 focus:border-rose-500 focus:ring-rose-500 @else border-slate-800 focus:border-blue-500 focus:ring-blue-500 @enderror rounded-xl px-4 py-3 text-xs text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-1 transition-all" placeholder="Tối thiểu 6 ký tự bảo mật...">
                @error('password') <span class="text-[11px] text-rose-500 block mt-1.5">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4 border-t border-slate-800/60 flex items-center justify-end gap-3">
                <a href="/admin/collaborators" wire:navigate class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-400 hover:text-white hover:bg-slate-900 transition-all border border-transparent hover:border-slate-800">Hủy bỏ</a>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-500/10 transition-all flex items-center justify-center min-w-[100px]">
                    <span wire:loading.remove wire:target="save">Lưu tài khoản</span>
                    <span wire:loading wire:target="save" class="inline-block w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                </button>
            </div>
        </form>
    </div>
</div>