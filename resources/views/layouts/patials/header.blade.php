<header class="border-b border-slate-200 bg-white px-4 py-4 shadow-sm sm:px-6 lg:px-8 font-sans">
    @php
        $isContributor = request()->is('contributor*') || request()->is('contributor');
        $activeUser = auth()->user() ?? \Modules\Auth\Models\User::whereHas('roles', function($q) {
            $q->where('name', 'contributor');
        })->first() ?? \Modules\Auth\Models\User::first();
    @endphp
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex items-center gap-4">
            <button class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm lg:hidden" aria-label="Open sidebar">
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>
            <div>
                <p class="text-sm font-medium uppercase tracking-[0.24em] text-slate-500">{{ $isContributor ? 'Contributor' : 'Admin' }}</p>
                <h2 class="text-xl font-semibold text-slate-900">{{ $title ?? 'Control Panel' }}</h2>
            </div>
        </div>

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
            <label class="sr-only" for="admin-search">Search</label>
            <div class="relative w-full sm:w-72">
                <input id="admin-search" type="search" placeholder="Search..." class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
                <span class="pointer-events-none absolute inset-y-0 right-4 inline-flex items-center text-slate-400">
                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8" /><path d="M21 21l-4.35-4.35" /></svg>
                </span>
            </div>

            <div class="flex items-center gap-3 rounded-3xl border border-slate-200 bg-slate-50 px-3 py-2">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900 text-white font-bold">{{ $isContributor ? 'C' : 'A' }}</div>
                <div>
                    <p class="text-sm font-semibold text-slate-900">{{ $activeUser?->name ?? ($isContributor ? 'Contributor' : 'Admin') }}</p>
                    <span class="text-xs text-slate-500">View profile</span>
                </div>
            </div>
        </div>
    </div>
</header>
