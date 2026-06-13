@extends('layouts.user')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="max-w-md w-full text-center space-y-6">
        @if(isset($orderType) && $orderType === 'subscription')
            {{-- VIP Success Icon --}}
            <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-br from-amber-100 to-yellow-100 animate-pulse">
                <svg class="w-12 h-12 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
            </div>

            {{-- VIP Title --}}
            <div class="space-y-2">
                <h1 class="text-3xl font-bold bg-gradient-to-r from-amber-600 to-yellow-600 bg-clip-text text-transparent">
                    🎉 Chúc mừng! Bạn đã là VIP!
                </h1>
                <p class="text-base text-slate-600">
                    Tài khoản của bạn đã được nâng cấp thành công. Bắt đầu tải tài liệu Premium ngay!
                </p>
            </div>
        @else
            {{-- Regular Success Icon --}}
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-emerald-100">
                <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            {{-- Regular Title --}}
            <div class="space-y-2">
                <h1 class="text-2xl font-bold text-slate-900">Thanh toán thành công!</h1>
                <p class="text-sm text-slate-500">
                    Cảm ơn bạn đã mua tài liệu. Bạn có thể tải xuống ngay bây giờ.
                </p>
            </div>
        @endif

        {{-- Order Info --}}
        @if(isset($orderType) && $orderType === 'subscription')
            <div class="rounded-2xl border-2 border-amber-200 bg-gradient-to-br from-amber-50 to-yellow-50 p-6 text-left space-y-3">
                <div class="flex justify-between items-center pb-2 border-b border-amber-200">
                    <span class="text-amber-700 font-semibold">Gói VIP đã kích hoạt</span>
                    <span class="px-2 py-1 bg-amber-500 text-white text-xs font-bold rounded-lg">VIP</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">Mã giao dịch:</span>
                    <span class="font-semibold text-slate-900">{{ $orderCode }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">Gói đã mua:</span>
                    <span class="font-semibold text-amber-700">{{ $documentTitle }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">Số tiền:</span>
                    <span class="font-bold text-amber-600">{{ number_format($amount) }}đ</span>
                </div>
                @if(isset($packageKey))
                    @php
                        $package = config("subscription.packages.{$packageKey}");
                    @endphp
                    @if($package)
                        <div class="mt-4 pt-3 border-t border-amber-200">
                            <p class="text-xs text-amber-700 font-semibold mb-2">✨ Quyền lợi của bạn:</p>
                            <ul class="space-y-1 text-xs text-slate-700">
                                <li>• Tải <strong>{{ $package['download_quota'] }} tài liệu Premium</strong></li>
                                <li>• Hiệu lực <strong>{{ $package['duration_days'] }} ngày</strong></li>
                                <li>• Tiết kiệm hơn so với mua lẻ</li>
                            </ul>
                        </div>
                    @endif
                @endif
            </div>
        @else
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-left space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Mã giao dịch:</span>
                    <span class="font-semibold text-slate-900">{{ $orderCode }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Tài liệu:</span>
                    <span class="font-semibold text-slate-900 text-right">{{ $documentTitle }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Số tiền:</span>
                    <span class="font-semibold text-emerald-600">{{ number_format($amount) }}đ</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Thời gian:</span>
                    <span class="font-semibold text-slate-900">{{ now()->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        @endif

        {{-- Actions --}}
        @if(isset($orderType) && $orderType === 'subscription')
            <div class="space-y-3">
                <a href="{{ route('student.subscription') }}" 
                   class="block w-full rounded-xl bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-white py-3.5 px-6 text-sm font-bold shadow-lg hover:shadow-xl transition-all duration-300 text-center">
                    ⚡ Xem trạng thái VIP của bạn
                </a>
                <a href="{{ route('documents.index') }}"
                   class="block w-full rounded-xl border-2 border-amber-200 bg-white hover:bg-amber-50 text-amber-700 py-3 px-6 text-sm font-semibold transition-all duration-300 text-center">
                    Khám phá tài liệu Premium →
                </a>
            </div>
        @else
            <div class="space-y-3">
                <x-button
                    primary
                    :href="route('student.purchases')"
                    label="Tải tài nguyên ngay"
                    icon="arrow-down-tray"
                    class="w-full"
                />
                <x-button
                    flat
                    :href="route('documents.index')"
                    label="Tiếp tục khám phá"
                    icon="arrow-right"
                    class="w-full"
                />
            </div>
        @endif
    </div>
</div>
@endsection
