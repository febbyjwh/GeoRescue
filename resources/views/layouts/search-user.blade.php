<!-- Floating Modern Search -->
<div id="searchContainer" class="absolute top-10 left-1/2 transform -translate-x-1/2 z-[999] w-auto px-4">
    <div class="relative w-[120vw] max-w-[500px]">
        <input 
            type="text" 
            id="searchLocation" 
            placeholder="Cari posko, fasilitas, atau lokasi..."
            class="w-full pl-12 pr-4 py-3 text-sm border border-amber-500 rounded-[50px] focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 shadow-lg bg-white placeholder-gray-400"
        >
        <svg class="absolute left-4 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
    </div>
    <div id="searchResults" class="hidden mt-2 max-h-60 overflow-y-auto bg-white border border-amber-400 rounded-xl shadow-lg"></div>
</div>