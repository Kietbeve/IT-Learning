<div
    x-data="{ open: false }"
    class="relative rounded-lg border p-3">

    <button
        type="button"
        @click="open = !open"
        class="w-full flex items-center justify-between text-left font-semibold text-gray-700">

        <span>{{ $title }}</span>

        <svg class="w-4 h-4 transition"
             :class="{ 'rotate-180': open }"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7"/>
        </svg>

    </button>

    {{-- Dropdown --}}
    <div
        x-show="open"
        @click.outside="open = false"
        x-transition
        class="absolute left-0 right-0 z-20 mt-2 rounded-lg border bg-white shadow-lg p-3 max-h-56 overflow-y-auto">

        @foreach($options as $value => $label)

            <label class="flex items-center gap-2 py-1">
                <input
                    type="checkbox"
                    {{ $attributes }}
                    value="{{ $value }}"
                >

                <span>{{ $label }}</span>
            </label>

        @endforeach

    </div>

    {{-- Selected tags --}}
    @if(count($selected))
        <div class="flex flex-wrap gap-2 mt-3">

            @foreach($selected as $value)

                <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 text-blue-700 px-2 py-1 text-xs">

                    {{ $options[$value] ?? $value }}

                    <button
                        type="button"
                        wire:click="removeFilter('{{ $attributes->wire('model')->value() }}','{{ $value }}')"
                        class="font-bold hover:text-red-600">

                        ×

                    </button>

                </span>

            @endforeach

        </div>
    @endif

</div>