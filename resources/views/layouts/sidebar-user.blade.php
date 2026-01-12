<!-- Sidebar default hidden -->
<div id="sidebar" class="hidden fixed left-0 top-0 h-full w-[380px] bg-white shadow-lg z-[1000] flex flex-col">
    <!-- Header -->
    <div class="p-4 flex items-center gap-3">
        <img src="{{ asset('logo-bencana.png') }}" alt="Logo" class="h-10 w-10 object-contain">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Navigasi Mitigasi</h2>
            <p class="text-sm text-gray-500">Pilih layer untuk ditampilkan</p>
        </div>
    </div>

    {{-- <div class="px-4 pb-3">
        <div class="relative">
            <input 
                type="text" 
                id="searchLocation" 
                placeholder="Cari posko, fasilitas, atau lokasi..."
                class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-transparent"
            >
            <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <!-- Search Results -->
        <div id="searchResults" class="hidden mt-2 max-h-60 overflow-y-auto bg-white border border-gray-200 rounded-lg shadow-lg">
            <!-- Results will be populated here -->
        </div>
    </div> --}}

    <!-- Filter Layer -->
    <div class="p-4 flex-1 overflow-y-auto">
        <h3 class="font-semibold text-gray-700 mb-2">Filter Layer</h3>
        <div class="flex flex-col gap-3">
            <button id="posko"
                class="filter-btn text-left px-4 py-2 rounded-[12px] bg-white font-semibold text-sm tracking-wide shadow-sm hover:bg-amber-200/40 hover:scale-105 transition-all duration-200">
                Posko Evakuasi
            </button>

            <button id="fasilitas"
                class="filter-btn text-left px-4 py-2 rounded-[12px] bg-white font-semibold text-sm tracking-wide shadow-sm hover:bg-amber-200/40 hover:scale-105 transition-all duration-200">
                Fasilitas Vital
            </button>

            <button id="logistik"
                class="filter-btn text-left px-4 py-2 rounded-[12px] bg-white font-semibold text-sm tracking-wide shadow-sm hover:bg-amber-200/40 hover:scale-105 transition-all duration-200">
                Distribusi Logistik
            </button>

            <button id="bencana"
                class="filter-btn text-left px-4 py-2 rounded-[12px] bg-white font-semibold text-sm tracking-wide shadow-sm hover:bg-amber-200/40 hover:scale-105 transition-all duration-200">
                Bencana
            </button>
        </div>

        <h3 class="font-semibold text-gray-700 mt-6 mb-2">Lokasi Terdekat</h3>
        <ul id="nearby-list" class="space-y-2">
            <div id="nearby-empty" class="text-sm text-gray-500 text-center py-4">
                Klik "Tambah Lokasi Saya" untuk melihat lokasi terdekat
            </div>
        </ul>

        <button id="addCustomLocation"
            class="mt-4 w-full px-3 py-2 rounded bg-amber-300 text-black hover:bg-amber-500 transition">
            📍 Tambah Lokasi Saya
        </button>

    </div>
</div>

<!-- Toggle button di tengah kanan -->
<button id="toggleSidebar"
    class="fixed right-0 top-1/2 transform -translate-y-1/2 z-[1001] shadow-md hover:brightness-90 transition font-semibold"
    style="background-color: #FFCA28; color: #000; padding: 0.5rem 1.5rem; border-top-left-radius: 50px; border-bottom-left-radius: 50px;">
    ☰
</button>

<script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleSidebar');

    // Toggle sidebar muncul / hilang
    toggleBtn.addEventListener('click', () => sidebar.classList.toggle('hidden'));
</script>
