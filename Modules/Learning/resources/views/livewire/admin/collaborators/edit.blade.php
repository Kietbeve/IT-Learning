<div class="max-w-2xl mx-auto px-4 sm:px-6 py-8 animate-fade-in">
    <div class="mb-6">
        <a href="/admin/collaborators" wire:navigate class="inline-flex items-center gap-1 text-xs font-bold text-slate-400 hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Quay lại danh sách
        </a>
        <h1 class="text-xl font-black text-white uppercase tracking-wide mt-3">Cập Nhật Thông Tin CTV</h1>
    </div>

    <div class="bg-slate-950/40 border border-slate-800/80 rounded-2xl p-6 backdrop-blur-md shadow-xl">
        <form wire:submit.prevent="update" class="space-y-5">
            <div>
                <label for="name" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Họ và tên <span class="text-rose-500">*</span></label>
                <input type="text" id="name" wire:model="name" class="w-full bg-slate-900/60 border @error('name') border-rose-500/50 focus:border-rose-500 focus:ring-rose-500 @else border-slate-800 focus:border-blue-500 focus:ring-blue-500 @enderror rounded-xl px-4 py-3 text-xs text-slate-200 focus:outline-none focus:ring-1 transition-all">
                @error('name') <span class="text-[11px] text-rose-500 block mt-1.5">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Địa chỉ Email <span class="text-rose-500">*</span></label>
                <input type="email" id="email" wire:model="email" class="w-full bg-slate-900/60 border @error('email') border-rose-500/50 focus:border-rose-500 focus:ring-rose-500 @else border-slate-800 focus:border-blue-500 focus:ring-blue-500 @enderror rounded-xl px-4 py-3 text-xs text-slate-200 focus:outline-none focus:ring-1 transition-all">
                @error('email') <span class="text-[11px] text-rose-500 block mt-1.5">{{ $message }}</span> @enderror
            </div>

            <div class="p-3.5 rounded-xl bg-amber-500/5 border border-amber-500/20 text-amber-400/90 text-[11px] leading-relaxed flex gap-2">
                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>Để bảo mật thông tin tối đa, tính năng thay đổi mật khẩu sẽ do chính Cộng tác viên tự thực hiện tại trang quản lý tài khoản cá nhân của họ.</span>
            </div>

            <div class="pt-4 border-t border-slate-800/60 flex items-center justify-end gap-3">
                <a href="/admin/collaborators" wire:navigate class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-400 hover:text-white hover:bg-slate-900 transition-all border border-transparent hover:border-slate-800">Hủy bỏ</a>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-500/10 transition-all flex items-center justify-center min-w-[100px]">
                    <span wire:loading.remove wire:target="update">Cập nhật</span>
                    <span wire:loading wire:target="update" class="inline-block w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                </button>
            </div>
        </form>
    </div>
</div>