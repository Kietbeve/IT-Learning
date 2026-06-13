<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Panel' }}</title>
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
    @livewireStyles
</head>

<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
    <div class="flex min-h-screen">
        @if(request()->is('contributor*') || request()->is('contributor'))
            @include('layouts.patials.contributor_sidebar')
        @else
            @include('layouts.patials.sidebar')
        @endif

        <div class="flex-1 flex flex-col min-w-0">
            @include('layouts.patials.header')

            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                <div class="mx-auto w-full max-w-7xl">
                    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
                                {{ $pageTitle ?? 'Dashboard' }}
                            </h1>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-4 text-emerald-800">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-4 text-rose-800">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                    {{ $slot ?? '' }}
                </div>
            </main>

            @include('layouts.patials.footer')
        </div>
    </div>
    @wireUiScripts
    @livewireScripts
</body>

</html>