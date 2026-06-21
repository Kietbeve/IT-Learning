<div>
    {{-- Header --}}
    <x-card>
        <div class="grid md:grid-cols-2 gap-4">

            <div>
                <div class="text-sm text-gray-500">
                    Tiêu đề
                </div>

                <div class="font-semibold">
                    {{ $exam->title }}
                </div>
            </div>

            <div>
                <div class="text-sm text-gray-500">
                    Tác giả
                </div>

                <div>
                    {{ $exam->author->name }}
                </div>
            </div>

            <div>
                <div class="text-sm text-gray-500">
                    Danh mục
                </div>

                <div>
                    {{ $exam->category->name }}
                </div>
            </div>

            <div>
                <div class="text-sm text-gray-500">
                    Tổng câu
                </div>

                <div>
                    {{ $this->totalQuestions }}
                </div>
            </div>

            <div>
                <div class="text-sm text-gray-500">
                    Tổng điểm
                </div>

                <div>
                    {{ $this->totalScore }}
                </div>
            </div>

            <div>
                <div class="text-sm text-gray-500">
                    Thời gian
                </div>

                <div>
                    {{ $exam->duration_minutes }} phút
                </div>
            </div>

        </div>
    </x-card>

    {{-- Check list --}}
    <x-card class="mt-6">

        <h3 class="font-semibold mb-4">
            Kiểm tra chất lượng
        </h3>

        <div class="space-y-2">

            <div>
                @if($this->qualityCheck['missing_correct_answer'] === 0)
                    ✅ Tất cả câu hỏi có đáp án đúng
                @else
                    ❌
                    {{ $this->qualityCheck['missing_correct_answer'] }}
                    câu chưa có đáp án đúng
                @endif
            </div>

            <div>
                @if($this->qualityCheck['missing_explanation'] === 0)
                    ✅ Tất cả câu hỏi có giải thích
                @else
                    ⚠
                    {{ $this->qualityCheck['missing_explanation'] }}
                    câu chưa có giải thích
                @endif
            </div>

        </div>

    </x-card>

    {{-- Danh sách câu hỏi --}}
    <x-card class="mt-6">

        <h3 class="font-semibold mb-4">
            Danh sách câu hỏi
        </h3>

        <div class="space-y-3">

            @foreach($exam->questions->sortBy('pivot.sort_order') as $question)

                <div class="p-4 border rounded-lg cursor-pointer hover:bg-gray-50" wire:click="$dispatch('question-view', { id: {{ $question->id }} })">
                    <div class="font-medium">
                        Câu {{ $question->pivot->sort_order }}
                    </div>

                    <div class="text-sm text-gray-500">
                        {{ \Illuminate\Support\Str::limit(strip_tags($question->content), 120) }}
                    </div>

                </div>

            @endforeach

        </div>

    </x-card>

    {{-- Lí do từ chối --}}
    <x-card class="mt-6">

        <x-textarea wire:model="rejectedReason" label="Lý do từ chối" rows="5" />

    </x-card>

    {{-- Action --}}
    <div class="mt-6 flex justify-end gap-3">

        <x-button negative wire:click="reject">
            Từ chối
        </x-button>

        <x-button positive wire:click="approve">
            Duyệt đề thi
        </x-button>

    </div>

    <livewire:modules.exam.livewire.contributor.question-modal />
</div>