<div wire:poll.5s class="inline-flex">
    @if($count > 0)
        <span class="inline-flex items-center justify-center rounded-full bg-rose-500 px-2 py-0.5 text-[10px] font-bold text-white shadow-sm ring-2 ring-white min-w-[20px]">
            {{ $count }}
        </span>
    @endif
</div>
