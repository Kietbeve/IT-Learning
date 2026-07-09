@extends('layouts.user')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="max-w-md w-full text-center space-y-6">
        {{-- Failed Icon --}}
        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-red-100">
            <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>

        {{-- Title --}}
        <div class="space-y-2">
            <h1 class="text-2xl font-bold text-blue-900">Thanh toán thất bại</h1>
            <p class="text-sm text-gray-500">
                @if($reason)
                    {{ $reason }}
                @else
                    Giao dịch của bạn không thể hoàn tất. Vui lòng thử lại hoặc chọn phương thức thanh toán khác.
                @endif
            </p>
        </div>

        {{-- Actions --}}
        <div class="space-y-3">
            <x-button
                primary
                wire:click="$dispatch('openCheckoutModal', { documentId: {{ $documentId ?? 'null' }} })"
                label="Thử lại"
                icon="arrow-uturn-left"
                class="w-full"
            />
            <x-button
                flat
                :href="route('documents.index')"
                label="Quay lại kho tài liệu"
                icon="arrow-left"
                class="w-full"
            />
        </div>
    </div>
</div>
@endsection


