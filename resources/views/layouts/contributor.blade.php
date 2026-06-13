<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Kênh Người Đăng Tải' }}</title>
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
    @livewireStyles
</head>

<body class="min-h-screen bg-gradient-to-br from-[#f3f5fc] via-white to-[#f9f8ff] text-slate-700 antialiased font-sans">
    <div class="flex min-h-screen">
        @include('layouts.patials.contributor_sidebar')

        <div class="flex-1 flex flex-col min-w-0">
            @include('layouts.patials.contributor_header')

            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                <div class="mx-auto w-full max-w-7xl">
                    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-2xl font-black tracking-tight text-slate-800">
                                {{ $pageTitle ?? '' }}
                            </h1>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-6 rounded-2xl border border-emerald-100 bg-emerald-50/80 backdrop-blur-md px-4 py-4 text-emerald-800 shadow-md">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-6 rounded-2xl border border-rose-100 bg-rose-50/80 backdrop-blur-md px-4 py-4 text-rose-800 shadow-md">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                    {{ $slot ?? '' }}
                </div>
            </main>

            @include('layouts.patials.contributor_footer')
        </div>
    </div>
    @wireUiScripts
    @livewireScripts
</body>

</html>
