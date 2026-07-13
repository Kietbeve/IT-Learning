<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'IT Learning · Contributor' }}</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('Image/logo.png') }}" type="image/png">
    
    <!-- Tailwind + Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet" />
    
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
    @livewireStyles

    <style>
        body, .font-sans { font-family: 'Inter', system-ui, -apple-system, sans-serif !important; }
        body { background: #f0f4f9; }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: #eef2f6; border-radius: 10px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .card-hover { transition: transform 0.25s ease, box-shadow 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 16px 36px -12px rgba(0,0,0,0.12), 0 4px 16px rgba(0,0,0,0.04); }
        .nav-link { transition: all 0.15s ease; position: relative; }
        .nav-link:hover { background: #f1f5f9; }
        .nav-link.active::before { content: ''; position: absolute; left: 0; top: 20%; height: 60%; width: 3px; background: #4f46e5; border-radius: 0 4px 4px 0; }
        .glass-header { background: rgba(255,255,255,0.75); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
        .gradient-border { background: linear-gradient(135deg, #eef2ff, #e0e7ff, #f3e8ff); }

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
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="antialiased text-slate-700">
    <div id="toast-wrapper">
        <x-notifications position="top-right" />
    </div>

    <!-- layout grid -->
    <div x-data="{ sidebarHidden: (window.innerWidth < 1024) }" 
         @resize.window="sidebarHidden = (window.innerWidth < 1024)"
         class="min-h-screen flex">

        <!-- Backdrop Overlay (Mobile) -->
        <div x-show="!sidebarHidden" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarHidden = true"
             class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 lg:hidden"
             x-cloak>
        </div>

        <!-- ===== SIDEBAR ===== -->
        @include('layouts.patials.contributor_sidebar')

        <!-- ===== MAIN ===== -->
        <div class="flex-1 flex flex-col min-h-screen min-w-0">

            <!-- ===== HEADER ===== -->
            @include('layouts.patials.contributor_header')

            <!-- ===== CONTENT ===== -->
            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                <div class="mx-auto w-full max-w-7xl">
                    <!-- Removed Flash Messages -->

                    @yield('content')
                    {{ $slot ?? '' }}
                </div>
            </main>

            <!-- ===== FOOTER ===== -->
            @include('layouts.patials.contributor_footer')
        </div>
    </div>

    @wireUiScripts
    @livewireScripts
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('notify', (event) => {
                let data = Array.isArray(event) ? event[0] : event;
                if (window.$wireui) {
                    window.$wireui.notify({
                        title: data.title || (data.type === 'success' ? 'Thành công' : (data.type === 'error' ? 'Lỗi' : 'Thông báo')),
                        description: data.message,
                        icon: data.type === 'error' ? 'error' : (data.type === 'success' ? 'success' : (data.type === 'warning' ? 'warning' : 'info')),
                        timeout: 5000
                    });
                } else {
                    alert(data.message);
                }
            });
        });
    </script>
    <script>
        document.addEventListener('livewire:navigated', () => {
            @if(session()->has('success'))
                if (window.$wireui) {
                    window.$wireui.notify({
                        title: 'Thành công',
                        description: '{!! session('success') !!}',
                        icon: 'success',
                        position: 'top-right',
                        timeout: 5000
                    });
                }
            @endif
            @if(session()->has('error'))
                if (window.$wireui) {
                    window.$wireui.notify({
                        title: 'Lỗi',
                        description: '{!! session('error') !!}',
                        icon: 'error',
                        position: 'top-right',
                        timeout: 5000
                    });
                }
            @endif
        });
    </script>
</body>
</html>
