<header class="border-b border-indigo-50/80 bg-white/80 px-4 py-4 shadow-sm sm:px-6 lg:px-8 backdrop-blur-md font-sans text-slate-850 sticky top-0 z-30">
    @php
        $activeUser = auth()->user() ?? \Modules\Auth\Models\User::whereHas('roles', function($q) {
            $q->where('name', 'contributor');
        })->first() ?? \Modules\Auth\Models\User::first();
    @endphp
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex items-center gap-4">
            <button class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-600 shadow-sm lg:hidden" aria-label="Open sidebar">
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>
            <div>
                <h2 class="text-2xl font-bold text-slate-850 tracking-tight">Contributor</h2>
            </div>
        </div>

        <div class="flex items-center">
            <x-dropdown>
                <x-slot name="trigger">
                    <button class="flex items-center gap-3 rounded-3xl border border-slate-200 bg-slate-50/60 px-3 py-2 text-left focus:outline-none hover:bg-slate-100/50 transition-all">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-600 text-white font-bold shadow-md shadow-indigo-500/10 shrink-0">C</div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ $activeUser?->name ?? 'Contributor' }}</p>
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Creator Account</span>
                        </div>
                        <x-icon name="chevron-down" class="w-4 h-4 text-slate-450 shrink-0" />
                    </button>
                </x-slot>

                <x-dropdown.item href="{{ route('contributor.transactions') }}" label="Lịch sử giao dịch">
                    <x-slot name="prepend">
                        <x-icon name="document-text" class="w-4 h-4 mr-2" />
                    </x-slot>
                </x-dropdown.item>

                <x-dropdown.item href="{{ route('contributor.payout-request') }}" label="Ví">
                    <x-slot name="prepend">
                        <x-icon name="banknotes" class="w-4 h-4 mr-2" />
                    </x-slot>
                </x-dropdown.item>

                <div class="border-t border-slate-100 my-1"></div>

                @if(auth()->user() && auth()->user()->hasRole('admin'))
                    <x-dropdown.item href="{{ route('admin.dashboard') }}">
                        <div class="flex items-center text-amber-600 font-semibold">
                            <x-icon name="shield-check" class="w-4 h-4 mr-2" />
                            <span>Trang quản trị</span>
                        </div>
                    </x-dropdown.item>
                @endif

                <form method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <x-dropdown.item label="Đăng xuất" onclick="event.preventDefault(); this.closest('form').submit();">
                        <x-slot name="prepend">
                            <x-icon name="arrow-right-on-rectangle" class="w-4 h-4 mr-2" />
                        </x-slot>
                    </x-dropdown.item>
                </form>
            </x-dropdown>
        </div>
    </div>
</header>
