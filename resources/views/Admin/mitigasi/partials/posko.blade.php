<div class="container-fluid">
    <x-common.page-breadcrumb pageTitle="Data Posko" class="z-10 relative" />

    <div class="rounded-xl border border-gray-200 bg-white p-6">

        {{-- FORM POSKO --}}
        <form id="formPosko" onclick="setActiveModule('posko')" onfocusin="setActiveModule('posko')">
            @csrf
            <input type="hidden" id="posko_id" name="posko_id" value="">

            <x-form.form-elements.default-inputs label="Nama Posko" name="nama_posko" placeholder="Masukkan nama posko"
                value="{{ old('nama_posko') }}" required />

            <x-form.form-elements.select-inputs label="Jenis Posko" name="jenis_posko" id="jenis_posko" required>
                <option value="">Pilih Posko</option>
                <option value="Kesehatan">Kesehatan</option>
                <option value="Evakuasi">Evakuasi</option>
            </x-form.form-elements.select-inputs>

            <div class="mb-3 w-full">
                <label for="kecamatan_id" class="block text-sm font-medium mb-1">Kecamatan</label>
                <select id="kecamatan_id" name="kecamatan_id"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Pilih Kecamatan</option>
                    <!-- options akan diisi dinamis -->
                </select>
            </div>

            <div class="mb-3 w-full">
                <label for="desa_id" class="block text-sm font-medium mb-1">Desa</label>
                <select id="desa_id" name="desa_id"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Pilih Desa</option>
                    <!-- options akan diisi dinamis -->
                </select>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <x-form.form-elements.default-inputs label="Latitude" name="latitude" id="latitude"
                    placeholder="-6.200000" />

                <x-form.form-elements.default-inputs label="Longitude" name="longitude" id="longitude"
                    placeholder="106.816666" />
            </div>

            <x-form.form-elements.select-inputs label="Status Posko" name="status_posko" required>
                <option value="">-- Pilih Status --</option>
                <option value="Aktif">Aktif</option>
                <option value="Penuh">Penuh</option>
                <option value="Tutup">Tutup</option>
            </x-form.form-elements.select-inputs>

            <div class="mb-3">
                <button type="button" onclick="submitPosko()"
                    class="inline-flex items-center rounded-lg bg-amber-300 px-5 py-2.5 text-sm mt-6 font-medium text-black hover:bg-amber-500 transition">
                    Simpan
                </button>
            </div>
        </form>

        <!-- Detail Posko Selected -->
        <div id="selectedPosko" class="hidden mt-4 rounded-xl border border-amber-200 bg-amber-50 p-5 text-sm">
            <h3 class="text-md font-semibold mb-3 text-amber-800">📍 Detail Posko Terpilih</h3>
            <ul class="space-y-2 text-gray-800">
                <li><span class="font-medium">Nama Posko:</span> <span id="detailNamaPosko">-</span></li>
                <li><span class="font-medium">Jenis Posko:</span> <span id="detailJenisPosko">-</span></li>
                <li><span class="font-medium">Kecamatan:</span> <span id="detailKecamatanPosko">-</span></li>
                <li><span class="font-medium">Desa:</span> <span id="detailDesaPosko">-</span></li>
                <li><span class="font-medium">Status:</span> <span id="detailStatusPosko">-</span></li>
                <li><span class="font-medium">Koordinat:</span> <span id="detailKoordinatPosko">-</span></li>
            </ul>
            <p class="text-xs text-gray-500 mt-3">Informasi ini ditampilkan berdasarkan posko yang dipilih pada peta.
            </p>
        </div>

        <div class="mt-4 rounded-xl border border-blue-200 bg-blue-50 p-5 text-sm">
            <h3 class="text-md font-semibold mb-4 text-blue-800">
                📍 Informasi Umum Posko
            </h3>

            <!-- Summary Card (3) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                <div class="rounded-lg border border-gray-200 bg-blue-100 p-4">
                    <p class="text-xs text-black font-bold">Total Posko</p>
                    <p class="text-gray-900">{{ $poskoSummary['total'] }} Posko</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-blue-100 p-4">
                    <p class="text-xs text-black font-bold">Jumlah Kecamatan Terdampak</p>
                    <p class="text-gray-900">{{ $poskoSummary['kecamatan'] }} Kecamatan</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-blue-100 p-4">
                    <p class="text-xs text-black font-bold">Jumlah Desa Terdampak</p>
                    <p class="text-gray-900">{{ $poskoSummary['desa'] }} Desa</p>
                </div>
            </div>

            <!-- Detail List -->
            <div class="mt-2 text-sm">
                <p class="font-bold mb-2 text-gray-700">Status Posko</p>
                <ul class="list-disc ml-5 space-y-1">
                    <li>Aktif: {{ $poskoSummary['status']['Aktif'] }}</li>
                    <li>Penuh: {{ $poskoSummary['status']['Penuh'] }}</li>
                    <li>Tutup: {{ $poskoSummary['status']['Tutup'] }}</li>
                </ul>

                <p class="font-bold mt-3 mb-2 text-gray-700">Jenis Posko</p>
                <ul class="list-disc ml-5 space-y-1">
                    @foreach ($poskoSummary['jenis'] as $jenis => $count)
                        <li>{{ $jenis }}: {{ $count }}</li>
                    @endforeach
                </ul>

                <p class="font-bold mt-3 mb-2 text-gray-700">Wilayah dengan Posko Terbanyak</p>
                <p class="text-gray-800">
                    {{ $poskoSummary['wilayah_terbanyak']['nama'] ?? '-' }}
                    <span class="text-gray-600 font-normal">
                        ({{ $poskoSummary['wilayah_terbanyak']['total'] ?? 0 }} Posko)
                    </span>
                </p>

                <div class="text-right text-gray-600 mt-3">
                    <p class="text-xs text-gray-500">
                        Terakhir Update: {{ optional($poskoSummary['last_update'])->format('d M Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/select-region.js') }}"></script>
    @endpush
</div>
