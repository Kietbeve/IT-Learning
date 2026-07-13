<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Panel' }}</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('Image/logo.png') }}" type="image/png">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
    @livewireStyles
    
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
        }
        
        [x-cloak] { display: none !important; }
        
        /* Custom scrollbar for sidebar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 20px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Smooth transitions */
        .sidebar-transition {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>

<body class="bg-slate-50 font-sans antialiased text-slate-800">
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
        <!-- Sidebar -->
        @if(request()->is('contributor*') || request()->is('contributor'))
            @include('layouts.patials.contributor_sidebar')
        @else
            @include('layouts.patials.sidebar')
        @endif

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Header -->
            @include('layouts.patials.header')

            <!-- Page Content -->
            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                <div class="mx-auto w-full max-w-7xl">
                    <!-- Page Header with breadcrumb (Removed per user request) -->
                    @if(isset($pageActions))
                        <div class="mb-6 flex justify-end">
                            <div class="flex items-center gap-3">
                                {{ $pageActions }}
                            </div>
                        </div>
                    @endif

                    <!-- Session Messages -->
                    @if(session('success'))
                        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50/80 backdrop-blur-sm px-4 py-4 text-emerald-800 shadow-sm">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50/80 backdrop-blur-sm px-4 py-4 text-rose-800 shadow-sm">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif

                    <!-- Main Content -->
                    @yield('content')
                    {{ $slot ?? '' }}
                </div>
            </main>

            <!-- Footer -->
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

        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.querySelector('[aria-label="Open sidebar"]');
            const sidebar = document.querySelector('aside');
            
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('hidden');
                    sidebar.classList.toggle('fixed');
                    sidebar.classList.toggle('inset-y-0');
                    sidebar.classList.toggle('left-0');
                    sidebar.classList.toggle('z-50');
                    sidebar.classList.toggle('w-72');
                    sidebar.classList.toggle('shadow-2xl');
                    sidebar.classList.toggle('animate-slide-in');
                });
            }
        });
    </script>
    
    <style>
        @keyframes slideIn {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }
    </style>
</body>

</html>