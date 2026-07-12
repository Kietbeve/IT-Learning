<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>ITLearning</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('Image/Logo.png') }}" type="image/png">

    <meta name="description" content="{{ $description ?? '' }}">
    <meta name="keywords" content="{{ $keywords ?? '' }}">
    <meta name="author" content="{{ $author ?? '' }}">

    {{-- Vite CSS & JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>[x-cloak] { display: none !important; }</style>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body, .font-sans {
            font-family: 'Inter', sans-serif !important;
        }

        /* === CARD MỚI === */
        .doc-card {
            background: white;
            border-radius: 1.5rem;
            border: 1px solid #f0f2f5;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            position: relative;
        }
        .doc-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 50px -20px rgba(0, 20, 40, 0.25), 0 8px 20px -8px rgba(0, 0, 0, 0.06);
            border-color: #dce4ed;
        }

        /* Ảnh */
        .doc-card .card-img-wrap {
            position: relative;
            overflow: hidden;
            background: #f1f5f9;
            aspect-ratio: 16/10;
        }
        .doc-card .card-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.7s ease;
        }
        .doc-card:hover .card-img-wrap img {
            transform: scale(1.08);
        }

        /* Overlay gradient */
        .doc-card .card-img-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.05) 0%, transparent 40%);
            pointer-events: none;
        }

        /* Badge định dạng + loại */
        .doc-card .card-badge {
            position: absolute;
            top: 14px;
            left: 14px;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(6px);
            padding: 5px 14px;
            border-radius: 40px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #1e293b;
            border: 1px solid rgba(255,255,255,0.3);
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .doc-card .card-badge i {
            font-size: 0.7rem;
            color: #2563eb;
        }

        /* Badge hot/new */
        .doc-card .card-badge-hot {
            position: absolute;
            top: 14px;
            left: 14px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 4px 14px;
            border-radius: 40px;
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
            z-index: 10;
        }
        .doc-card .card-badge-new {
            background: linear-gradient(135deg, #10b981, #059669);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        /* Nút yêu thích */
        .doc-card .card-wishlist {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(6px);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            border: 1px solid rgba(255,255,255,0.3);
            cursor: pointer;
            transition: all 0.25s;
            box-shadow: 0 2px 10px rgba(0,0,0,0.04);
            z-index: 10;
        }
        .doc-card .card-wishlist:hover {
            color: #ef4444;
            background: white;
            transform: scale(1.12);
        }
        .doc-card .card-wishlist.active {
            color: #ef4444;
        }
        .doc-card .card-wishlist i {
            font-size: 0.9rem;
        }

        /* Body card */
        .doc-card .card-body {
            padding: 1.25rem 1.25rem 1.25rem;
        }

        /* Tiêu đề */
        .doc-card .card-title {
            font-weight: 700;
            font-size: 1.05rem;
            color: #0f172a;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 6px;
        }
        .doc-card .card-title a {
            color: inherit;
            text-decoration: none;
            transition: color 0.2s;
        }
        .doc-card .card-title a:hover {
            color: #2563eb;
        }

        /* Mô tả ngắn */
        .doc-card .card-desc {
            font-size: 0.8rem;
            color: #64748b;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-top: 4px;
            margin-bottom: 10px;
        }

        /* Meta (danh mục, môn học, lượt tải, đánh giá) */
        .doc-card .card-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 4px 12px;
            font-size: 0.75rem;
            color: #64748b;
            border-top: 1px solid #f1f4f9;
            padding-top: 12px;
            margin-top: 4px;
        }
        .doc-card .card-meta .meta-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .doc-card .card-meta .meta-item i {
            color: #94a3b8;
            font-size: 0.7rem;
            width: 14px;
        }
        .doc-card .card-meta .meta-item .rating {
            color: #f59e0b;
        }

        /* Footer card - giá + nút */
        .doc-card .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px solid #f1f4f9;
        }
        .doc-card .card-price {
            font-size: 1.3rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.3px;
        }
        .doc-card .card-price small {
            font-size: 0.7rem;
            font-weight: 500;
            color: #94a3b8;
            margin-left: 2px;
        }
        .doc-card .card-price .original {
            font-size: 0.75rem;
            font-weight: 400;
            color: #94a3b8;
            text-decoration: line-through;
            margin-left: 6px;
        }

        .doc-card .card-btn {
            background: #0f172a;
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 60px;
            font-weight: 600;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.25s;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
        }
        .doc-card .card-btn:hover {
            background: #2563eb;
            transform: scale(0.96);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25);
        }

        /* === FILTER === */
        .filter-group {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px 16px;
        }
        .filter-item {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 60px;
            padding: 6px 16px 6px 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            cursor: pointer;
        }
        .filter-item:hover {
            border-color: #94a3b8;
        }
        .filter-item i {
            color: #64748b;
            font-size: 0.8rem;
        }
        .filter-item select {
            border: none;
            background: transparent;
            padding: 6px 0;
            font-size: 0.85rem;
            font-weight: 500;
            color: #0f172a;
            outline: none;
            cursor: pointer;
        }
        .filter-item select option {
            font-weight: 400;
        }
        .filter-item.price-input {
            padding: 6px 12px;
        }
        .filter-item.price-input input {
            width: 60px;
            border: none;
            background: transparent;
            outline: none;
            font-size: 0.85rem;
            color: #0f172a;
            padding: 4px 0;
        }
        .filter-item.price-input input::placeholder {
            color: #94a3b8;
        }
        .filter-item.price-input span {
            color: #94a3b8;
        }

        .filter-sort {
            background: #eef2ff;
            border: 1px solid #c7d2fe;
            border-radius: 60px;
            padding: 6px 16px 6px 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .filter-sort i {
            color: #2563eb;
        }
        .filter-sort select {
            border: none;
            background: transparent;
            padding: 6px 0;
            font-size: 0.85rem;
            font-weight: 500;
            color: #1e293b;
            outline: none;
            cursor: pointer;
        }

        .btn-reset {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 60px;
            padding: 6px 18px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-reset:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 20px;
        }

        /* Responsive tweak */
        @media (max-width: 640px) {
            .filter-group {
                gap: 8px;
            }
            .filter-item {
                padding: 4px 12px;
            }
            .filter-item select {
                font-size: 0.75rem;
            }
            .filter-item.price-input input {
                width: 50px;
            }
        }
    </style>
</head>

<body>
    <div id="toast-wrapper">
        <x-notifications z-index="z-50" position="top-right" />
    </div>
    <style>
        /* Ép Toast của WireUI hiển thị ở góc trên bên phải trên MỌI kích thước màn hình (kể cả mobile) 
           và đẩy xuống dưới Header (~72px) */
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
    
    <div class="flex flex-col min-h-screen font-sans text-gray-800">
        <!-- User Header -->
        @include('layouts.patials.user_header')

        {{-- VIP Promotion Banner (for non-VIP logged-in users) --}}
        @auth
            @php
                $isVip = Auth::user()->vip_expires_at && Auth::user()->vip_expires_at->isFuture();
            @endphp
            
            @if(!$isVip && !request()->routeIs('user.subscription'))
                <div x-data="{ 
                    show: !localStorage.getItem('vip_banner_dismissed'),
                    dismiss() {
                        this.show = false;
                        localStorage.setItem('vip_banner_dismissed', 'true');
                        setTimeout(() => localStorage.removeItem('vip_banner_dismissed'), 86400000); // 24h
                    }
                }" 
                     x-cloak
                     x-show="show"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-4"
                     class="bg-gradient-to-r from-amber-500 via-yellow-500 to-amber-500 shadow-lg">
                    <div class="max-w-7xl mx-auto px-4 md:px-8 py-3">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                <svg class="w-6 h-6 text-white shrink-0 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-white font-bold text-sm md:text-base">
                                        @php
                                            $vipBannerPrice = (int) \App\Services\SettingService::get('vip_sale_price', 99000);
                                        @endphp
                                        🎁 <span class="hidden sm:inline">Ưu đãi đặc biệt!</span> Nâng cấp VIP - Tải tài liệu Premium giá hời - Chỉ từ {{ number_format($vipBannerPrice) }}đ/tháng
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <a href="{{ route('user.subscription') }}" 
                                   class="px-4 py-2 bg-white hover:bg-gray-100 text-amber-600 rounded-lg text-sm font-bold shadow-md hover:shadow-lg transition-all duration-300">
                                    Xem ngay
                                </a>
                                <button @click="dismiss" 
                                        class="p-2 hover:bg-white/20 rounded-lg transition-colors duration-200" 
                                        title="Đóng">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endauth
        
        <main class="flex-1 p-8-4 bg-gray-50">
            @yield('content')
            {{ $slot ?? '' }}
        </main>

        <!-- User Footer -->
        @include('layouts.patials.user_footer')
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
