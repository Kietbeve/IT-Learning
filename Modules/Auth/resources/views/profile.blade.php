@extends('layouts.user')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="h-32 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
        <div class="px-6 py-8 relative">
            <div class="absolute -top-16 left-6">
                <img class="h-28 w-28 rounded-full ring-4 ring-white object-cover bg-white" 
                     src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=2563eb&color=fff' }}" 
                     alt="Avatar">
            </div>
            <div class="pt-12 sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900">{{ auth()->user()->name }}</h1>
                    <p class="text-sm font-medium text-slate-500 mt-1">{{ auth()->user()->email }}</p>
                </div>
                <div class="mt-5 sm:mt-0">
                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-300 rounded-xl shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                            Đăng xuất
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-8 border-t border-slate-100 pt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Thông tin cá nhân</h3>
                    <div class="space-y-4">
                        <div>
                            <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Số điện thoại</span>
                            <span class="text-slate-800 font-medium">{{ auth()->user()->phone ?? 'Chưa cập nhật' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Tiểu sử (Bio)</span>
                            <span class="text-slate-600 text-sm block mt-1 leading-relaxed">{{ auth()->user()->bio ?? 'Chưa có tiểu sử.' }}</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Hoạt động & Tài khoản</h3>
                    <div class="space-y-4">
                        <div>
                            <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Số dư cộng tác viên</span>
                            <span class="text-slate-800 font-bold text-lg text-emerald-600">{{ number_format(auth()->user()->contributor_balance ?? 0) }}đ</span>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Trạng thái</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 mt-1">
                                Hoạt động
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
