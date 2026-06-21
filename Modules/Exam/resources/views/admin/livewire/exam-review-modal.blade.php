<div>
    <x-notifications z-index="z-50" />

    <x-modal-card title="Từ chối đề thi" blur wire:model="showModal">
        <div class="p-6">

            @if($action === 'approve')

                <h2 class="text-lg font-semibold">
                    Duyệt đề thi
                </h2>

                <p class="mt-2 text-sm text-gray-500">
                    Bạn có chắc muốn duyệt đề thi
                    <strong>{{ $exam?->title }}</strong>?
                </p>

                <div class="mt-6 flex justify-end gap-2">

                    <x-button flat wire:click="close">
                        Huỷ
                    </x-button>

                    <x-button positive wire:click="approve">
                        Duyệt
                    </x-button>

                </div>

            @endif


            @if($action === 'reject')

                <h2 class="text-lg font-semibold">
                    Từ chối đề thi
                </h2>

                <p class="mt-2 text-sm text-gray-500">
                    {{ $exam?->title }}
                </p>

                <div class="mt-4">

                    <textarea wire:model="rejectedReason" rows="5" class="w-full rounded-lg border"
                        placeholder="Nhập lý do từ chối..."></textarea>

                    @error('rejectedReason')
                        <span class="text-sm text-red-500">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

                <div class="mt-6 flex justify-end gap-2">

                    <x-button flat wire:click="close">
                        Huỷ
                    </x-button>

                    <x-button negative wire:click="reject">
                        Từ chối
                    </x-button>

                </div>

            @endif

        </div>
    </x-modal-card>
</div>