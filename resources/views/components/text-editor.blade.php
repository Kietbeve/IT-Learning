@props([
    'label' => null,
    'placeholder' => '',
])

@php
    $model = $attributes->wire('model')->value();
@endphp

<div
    wire:ignore
    x-data="{
        editor: null,

        init() {
            this.editor = new Quill(this.$refs.editor, {
                theme: 'snow',
                placeholder: '{{ $placeholder }}',
                modules: {
                   toolbar: [
                        [{ font: [] }],
                        [{ header: [1, 2, false] }],

                        ['bold', 'italic', 'underline'],
                        ['blockquote', 'code-block'],

                        [{ list: 'ordered' }, { list: 'bullet' }],

                        ['link'],

                        ['clean']
                    ]
                }
            });

            this.editor.root.innerHTML = $wire.get('{{ $model }}') ?? '';

            this.editor.on('text-change', () => {
                $wire.set('{{ $model }}', this.editor.root.innerHTML);
            });
        }
    }"
>
    @if($label)
        <label class="mb-2 block text-sm font-medium text-gray-700">
            {{ $label }}
        </label>
    @endif

    <div
        x-ref="editor"
        class="min-h-[180px] rounded-md bg-white"
    ></div>
</div>