<div id="learningSidebar" class="fixed top-0 left-0 h-full w-72 bg-white shadow-xl z-40 transform transition-transform duration-300">
    <div class="p-5 border-b flex justify-between items-center">
        <div class="flex items-center gap-3">
            <button onclick="toggleSidebar()" class="text-blue-600 hover:text-blue-700 text-2xl font-bold hover:bg-blue-50 w-9 h-9 rounded-lg flex items-center justify-center transition-colors">
                ☰
            </button>
            <h2 class="font-bold text-xl text-blue-600">Lộ trình học</h2>
        </div>
        <button onclick="closeSidebar()" class="text-gray-500 hover:text-gray-700 text-2xl font-bold hover:bg-gray-100 w-8 h-8 rounded-full flex items-center justify-center transition-colors">✕</button>
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

<div id="sidebarOverlay" class="fixed top-0 left-0 h-full w-72 bg-white z-50 hidden transition-opacity duration-300" onclick="toggleSidebar()"></div>

<script>
function toggleSidebar() {
    const overlay = document.getElementById("sidebarOverlay");
    
    if (overlay) {
        overlay.classList.toggle("hidden");
    }
}

function closeSidebar() {
    const overlay = document.getElementById("sidebarOverlay");
    
    if (overlay) {
        overlay.classList.add("hidden");
    }
}
</script>