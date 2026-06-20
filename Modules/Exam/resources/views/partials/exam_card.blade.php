<div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300">
  <div class="p-4">

    {{-- Header --}}
    <div class="flex items-start gap-3">

      <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center shrink-0">
        <x-icon name="document-text" class="w-5 h-5 text-indigo-600" />
      </div>

      <div class="flex-1 min-w-0">

        <div class="flex items-start justify-between gap-3">
          <h3 class="text-base font-semibold text-gray-900 line-clamp-1">
            {{ $exam->title }}
          </h3>

          <x-badge flat indigo label="{{ $exam->category->name }}" />
        </div>

        <p class="text-sm text-gray-500 line-clamp-2 mt-1">
          {{ $exam->short_description }}
        </p>

      </div>

    </div>

    {{-- Meta --}}
    <div class="flex flex-wrap gap-4 mt-4 text-sm text-gray-600">

      <div class="flex items-center gap-1">
        <x-icon name="clock" class="w-4 h-4 text-gray-400" />
        <span>{{ $exam->duration_minutes }} phút</span>
      </div>

      <div class="flex items-center gap-1">
        <x-icon name="question-mark-circle" class="w-4 h-4 text-gray-400" />
        <span>{{ $exam->questions_count }} câu</span>
      </div>

    </div>

    {{-- Footer --}}
    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">

      <div class="flex items-center gap-2">

        <div
          class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-xs font-semibold text-primary-700">
          {{ mb_strtoupper(mb_substr($exam->author->name, 0, 1)) }}
        </div>

        <span class="text-sm text-gray-600">
          {{ $exam->author->name }}
        </span>

      </div>

      <x-button sm indigo :href="route('exam.examDetail', ['examSlug' => $exam->slug])" label="Xem chi tiết" right-icon="arrow-right" />

    </div>

  </div>
</div>