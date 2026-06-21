<div id="learningSidebar" class="fixed top-0 left-0 h-full w-72 bg-white shadow-xl z-50 transform -translate-x-full transition-transform duration-300">
    <div class="p-5 border-b flex justify-between items-center">
        <h2 class="font-bold text-xl text-blue-600">Lộ trình học</h2>
        <button onclick="toggleSidebar()" class="text-gray-500 text-xl">✕</button>
    </div>

    <div class="p-4 overflow-y-auto h-[calc(100%-80px)]">
        <h3 class="font-semibold text-green-600 mb-3">✓ Đã đăng ký</h3>
        @forelse($registeredRoadmaps ?? [] as $roadmap)
            <a href="{{ route('learning.roadmaps.show', $roadmap->id) }}" class="block p-3 rounded-lg hover:bg-blue-50 mb-2">
                {{ $roadmap->title }}
            </a>
        @empty
            <p class="text-gray-400 text-sm mb-2">Chưa có lộ trình</p>
        @endforelse

        <hr class="my-5">

        <h3 class="font-semibold text-gray-700 mb-3">📚 Chưa đăng ký</h3>
        @forelse($unregisteredRoadmaps ?? [] as $roadmap)
            <a href="{{ route('learning.roadmaps.show', $roadmap->id) }}" class="block p-3 rounded-lg hover:bg-gray-100 mb-2">
                {{ $roadmap->title }}
            </a>
        @empty
            <p class="text-gray-400 text-sm">Không còn lộ trình</p>
        @endforelse
    </div>
</div>

<button onclick="toggleSidebar()" class="fixed top-20 left-5 z-40 bg-blue-600 text-white p-3 rounded-lg shadow-lg hover:bg-blue-700 transition">
    ☰
</button>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById("learningSidebar");
    if (sidebar) {
        sidebar.classList.toggle("-translate-x-full");
    }
}
</script>