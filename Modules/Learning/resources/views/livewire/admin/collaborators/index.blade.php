<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-xl font-black tracking-wide text-white uppercase">Quản Lý Cộng Tác Viên</h1>
            <p class="text-xs text-slate-400 mt-1">Danh sách tài khoản nhân sự thuộc nhóm phân quyền cộng tác viên.</p>
        </div>
        <a href="/admin/collaborators/create" wire:navigate class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-lg shadow-blue-500/20 transition-all border border-blue-500/30 group">
            <svg class="w-4 h-4 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Thêm Cộng Tác Viên
        </a>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <div class="bg-slate-950/40 border border-slate-800/80 rounded-2xl overflow-hidden backdrop-blur-md shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/60 border-b border-slate-800/80 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-4 text-center w-16">STT</th>
                        <th class="px-6 py-4">Họ và tên</th>
                        <th class="px-6 py-4">Địa chỉ Email</th>
                        <th class="px-6 py-4">Ngày tham gia</th>
                        <th class="px-6 py-4 text-right w-44">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50 text-xs text-slate-300">
                    @forelse($collaborators as $index => $collaborator)
                        <tr class="hover:bg-slate-900/40 transition-colors group">
                            <td class="px-6 py-4 text-center font-medium text-slate-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-bold text-slate-100 group-hover:text-blue-400 transition-colors">{{ $collaborator->name }}</td>
                            <td class="px-6 py-4 text-slate-400">{{ $collaborator->email }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $collaborator->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="/admin/collaborators/{{ $collaborator->id }}/edit" wire:navigate class="inline-flex px-2.5 py-1.5 rounded-lg bg-blue-500/10 border border-blue-500/20 text-blue-400 hover:bg-blue-600 hover:text-white transition-all font-semibold">
                                    Sửa
                                </a>
                                <button wire:click="deleteCollaborator({{ $collaborator->id }})" wire:confirm="Bạn có chắc chắn muốn xóa cộng tác viên này không?" class="inline-flex px-2.5 py-1.5 rounded-lg bg-rose-500/10 border border-rose-500/20 text-rose-400 hover:bg-rose-600 hover:text-white transition-all font-semibold">
                                    Xóa
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                <svg class="w-8 h-8 mx-auto mb-3 text-slate-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A10.012 10.012 0 016.111 15.9c-1.143-1.042-2.447-2.07-4.682-2.72a3 3 0 00-4.68 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94-3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                                <span class="text-xs">Hệ thống chưa ghi nhận cộng tác viên nào.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>