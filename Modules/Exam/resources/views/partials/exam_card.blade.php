<div class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden h-full flex flex-col">
    {{-- Header --}}
    <div class="bg-linear-to-r from-indigo-500 to-indigo-900 p-5">

        <div class="flex items-center justify-between">

            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-sm">
                <span class="text-xl font-bold text-white">
                    {{ mb_strtoupper(mb_substr($exam->category->name, 0, 1)) }}
                </span>
            </div>

            <x-badge
                flat
                white
                label="{{ $exam->category->name }}"
            />

        </div>

    </div>

    {{-- Content --}}
    <div class="p-5 flex-1 flex flex-col">

        <h3 class="text-lg font-bold text-gray-900 line-clamp-2 min-h-14">
            {{ $exam->title }}
        </h3>

        <p class="mt-3 text-sm text-gray-500 line-clamp-3 flex-1">
            {{ $exam->short_description }}
        </p>

        {{-- Meta --}}
        <div class="mt-4 flex items-center justify-between text-sm">

            <div class="flex items-center gap-1 text-gray-600">
                <x-icon
                    name="clock"
                    class="w-4 h-4 text-gray-400"
                />
                <span>{{ $exam->duration_minutes }} phút</span>
            </div>

            <div class="flex items-center gap-1 text-gray-600">
                <x-icon
                    name="question-mark-circle"
                    class="w-4 h-4 text-gray-400"
                />
                <span>{{ $exam->questions_count }} câu</span>
            </div>

        </div>

        {{-- Author --}}
        <div class="mt-5 flex items-center gap-3">

            <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center text-sm font-semibold text-indigo-700">
                {{ mb_strtoupper(mb_substr($exam->author->name, 0, 1)) }}
            </div>

            <div class="min-w-0">
                <p class="text-xs text-gray-400">
                    Tác giả
                </p>

                <p class="text-sm font-medium text-gray-700 truncate">
                    {{ $exam->author->name }}
                </p>
            </div>

        </div>

    </div>

    {{-- Footer --}}
    <div class="px-5 pb-5">

        <x-button
            indigo
            class="w-full"
            :href="route('exam.examDetail', ['examSlug' => $exam->slug])"
            label="Xem chi tiết"
            right-icon="arrow-right"
        />

    </div>


</div>
