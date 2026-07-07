<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Panel' }}</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('Image/logo.png') }}" type="image/png">
    
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
    @livewireStyles
</head>

<body class="bg-slate-50 font-sans antialiased text-slate-800">
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <div id="toast-wrapper">
        <x-notifications z-index="z-50" position="top-right" />
    </div>
    <style>
        /* Force WireUI notifications to top-right below header */
        #toast-wrapper > div {
            top: 80px !important;
            right: 20px !important;
            bottom: auto !important;
            left: auto !important;
            width: 380px !important;
            max-width: calc(100vw - 40px) !important;
            z-index: 99999 !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-end !important;
        }
    </style>
    <x-dialog z-index="z-50" blur="md" align="center" />
    
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
    @livewireScripts
    @wireUiScripts
    <script>
        document.addEventListener('livewire:init', () => {
            // Lắng nghe sự kiện 'notify' từ Livewire component
            Livewire.on('notify', (event) => {
                let data = Array.isArray(event) ? event[0] : event;
                if (window.$wireui) {
                    window.$wireui.notify({
                        title: data.title || (data.type === 'success' ? 'Thành công' : (data.type === 'error' ? 'Lỗi' : 'Thông báo')),
                        description: data.message,
                        icon: data.type === 'error' ? 'error' : (data.type === 'success' ? 'success' : (data.type === 'warning' ? 'warning' : 'info')),
                        position: 'top-right',
                        timeout: 5000
                    });
                } else {
                    alert(data.message);
                }
            });
        });
    </script>
</body>

</html>