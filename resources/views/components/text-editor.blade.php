@props([
    'label' => null,
    'placeholder' => '',
])

@php
    $model = $attributes->wire('model')->value();
@endphp

<div>
    @if($label)
        <label class="mb-2 block text-sm font-medium text-gray-700">
            {{ $label }}
        </label>
    @endif
    
    <div  class="rounded-md overflow-hidden border transition-colors
        {{ $errors->has($model)
            ? 'border-red-500 ring-1 ring-red-500'
            : 'border-gray-300' }}">
        <div
            wire:ignore
            x-data="{
                editor: null,
                currentValue: '',

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

                    // Hàm cập nhật editor với giá trị mới
                    const updateEditor = (value) => {
                        const htmlValue = value || '';
                        if (this.editor.root.innerHTML !== htmlValue) {
                            this.editor.root.innerHTML = htmlValue;
                            this.currentValue = htmlValue;
                        }
                    };

                    // Load giá trị ban đầu
                    updateEditor($wire.get('{{ $model }}'));

                    // Watch thay đổi từ Livewire (khi mở modal edit)
                    $wire.$watch('{{ $model }}', (value) => {
                        updateEditor(value);
                    });

                    // Đồng bộ về Livewire khi người dùng nhập
                    this.editor.on('text-change', () => {
                        const newValue = this.editor.root.innerHTML;
                        if (this.currentValue !== newValue) {
                            this.currentValue = newValue;
                            $wire.set('{{ $model }}', newValue);
                        }
                    });
                }
            }"
        >
            <div
                x-ref="editor"
                class="min-h-[180px] rounded-md bg-white"
            ></div>
        </div>
    </div>
    
    {{-- Thong bao loi --}}
    @error($model)
        <p class="mt-2 text-sm text-red-600">
            {{ $message }}
        </p>
    @enderror
</div>