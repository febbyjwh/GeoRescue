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

    <!-- Filter Layer -->
    <div class="p-4 flex-1 overflow-y-auto">
        <h3 class="font-semibold text-gray-700 mb-2">Filter Layer</h3>
        <div class="flex flex-col gap-2">
            <button id="posko"
                class="filter-btn text-left px-3 py-2 rounded hover:bg-gray-200 transition bg-transparent">
                Posko Evakuasi
            </button>

            <button id="fasilitas"
                class="filter-btn text-left px-3 py-2 rounded hover:bg-gray-200 transition bg-transparent">
                Fasilitas Vital
            </button>

            <button id="logistik"
                class="filter-btn text-left px-3 py-2 rounded hover:bg-gray-200 transition bg-transparent">
                Distribusi Logistik
            </button>

            <button id="bencana"
                class="filter-btn text-left px-3 py-2 rounded hover:bg-gray-200 transition bg-transparent">
                Bencana
            </button>
        </div>

        <h3 class="font-semibold text-gray-700 mt-6 mb-2">Lokasi Terdekat</h3>
        {{-- <ul id="nearby-list" class="space-y-2">
            <li class="p-2 hover:bg-gray-100 rounded cursor-pointer">Posko Evakuasi 1</li>
            <li class="p-2 hover:bg-gray-100 rounded cursor-pointer">Fasilitas Vital 2</li>
            <li class="p-2 hover:bg-gray-100 rounded cursor-pointer">Distribusi Logistik 3</li>
        </ul> --}}

        <button id="addCustomLocation"
            class="mt-4 w-full px-3 py-2 rounded bg-blue-500 text-white hover:bg-blue-600 transition">
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
