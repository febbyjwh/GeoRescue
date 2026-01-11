<div class="container-fluid">
    <x-common.page-breadcrumb pageTitle="Data Titik Bencana" class="z-10 relative" />
    <div class="rounded-xl border border-gray-200 bg-white p-6">
        <form id="formBencana" onsubmit="event.preventDefault()" onclick="setActiveModule('bencana')">
            <x-form.form-elements.select-inputs label="Jenis bencana" name="jenis_bencana" id="jenis_bencana" required>
                <option value="">Pilih Jenis Bencana</option>
                <option value="banjir" {{ old('jenis_bencana') == 'banjir' ? 'selected' : '' }}>Banjir</option>
                <option value="gempa" {{ old('jenis_bencana') == 'gempa' ? 'selected' : '' }}>Gempa</option>
                <option value="longsor" {{ old('jenis_bencana') == 'longsor' ? 'selected' : '' }}>Longsor</option>
            </x-form.form-elements.select-inputs>

            <input type="hidden" id="bencana_id">

            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Kecamatan</label>
                <select id="bencana_district" class="w-full" style="width: 100%"></select>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Desa</label>
                <select id="bencana_village" class="w-full" style="width: 100%"></select>
            </div>

            <x-form.form-elements.select-inputs label="Tingkat Kerawanan" name="tingkat_kerawanan"
                id="tingkat_kerawanan" required>
                <option value="">Pilih Tingkat Kerawanan</option>
                <option value="Tinggi" {{ old('tingkat_kerawanan') == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                <option value="Sedang" {{ old('tingkat_kerawanan') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                <option value="Rendah" {{ old('tingkat_kerawanan') == 'Rendah' ? 'selected' : '' }}>Rendah</option>
            </x-form.form-elements.select-inputs>

            <x-form.form-elements.select-inputs label="Status" name="status" id="status" required>
                <option value="">Status</option>
                <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Penanganan" {{ old('status') == 'Penanganan' ? 'selected' : '' }}>Penanganan</option>
                <option value="Selesai" {{ old('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </x-form.form-elements.select-inputs>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <x-form.form-elements.default-inputs label="Longitude" name="lang" id="lang"
                    placeholder="106.816666" value="{{ old('lang') }}" />
                <x-form.form-elements.default-inputs label="Latitude" name="lat" id="lat"
                    placeholder="-6.200000" value="{{ old('lat') }}" />
            </div>

            <div class="mb-3">
                <button type="button" onclick="submitBencana()"
                    class="inline-flex items-center rounded-lg bg-amber-300 px-5 py-2.5 text-sm mt-6 font-medium text-black hover:bg-amber-500 transition">
                    Simpan
                </button>
            </div>
        </form>

        <!-- DETAIL BENCANA TERPILIH -->
        <div id="selectedBencana" class="hidden mt-4 rounded-xl border border-amber-200 bg-amber-50 p-5 text-sm">

            <h3 class="text-md font-semibold mb-3 text-amber-800">
                📍 Detail Bencana Terpilih
            </h3>

            <ul class="space-y-2 text-gray-800">
                <li>
                    <span class="font-medium">Jenis Bencana:</span>
                    <span id="detailJenis">-</span>
                </li>
                <li>
                    <span class="font-medium">Kecamatan:</span>
                    <span id="detailKecamatan">-</span>
                </li>
                <li>
                    <span class="font-medium">Desa:</span>
                    <span id="detailDesa">-</span>
                </li>
                <li>
                    <span class="font-medium">Tingkat Kerawanan:</span>
                    <span id="detailKerawanan">-</span>
                </li>
                <li>
                    <span class="font-medium">Status:</span>
                    <span id="detailStatus">-</span>
                </li>
            </ul>

            <p class="text-xs text-gray-500 mt-3">
                Informasi ini ditampilkan berdasarkan titik bencana yang dipilih pada peta.
            </p>
        </div>

        <div class="mt-4 rounded-xl border border-blue-200 bg-blue-50 p-5 text-sm">
            <h3 class="text-md font-semibold mb-4 text-blue-800">
                📍 Informasi Umum Bencana
            </h3>

            <!-- RINGKASAN (4 CARD) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">

                <!-- TOTAL -->
                <div class="rounded-lg border border-gray-200 bg-blue-100 p-4">
                    <p class="text-xs text-black font-bold">Total Titik Bencana</p>
                    <p class="text-gray-900">
                        {{ $bencanaSummary['total'] }} Titik
                    </p>
                </div>

                <!-- WILAYAH -->
                <div class="rounded-lg border border-gray-200 bg-blue-100 p-4">
                    <p class="text-xs text-black font-bold">Wilayah Terdampak</p>
                    <p class="text-gray-900">
                        {{ $bencanaSummary['kecamatan'] }} Kecamatan<br>
                        {{ $bencanaSummary['desa'] }} Desa
                    </p>
                </div>

                <!-- KERAWANAN -->
                <div class="rounded-lg border border-gray-200 bg-blue-100 p-4">
                    <p class="text-xs text-black font-bold">Tingkat Kerawanan</p>
                    <p class="text-gray-900">
                        Tinggi: {{ $bencanaSummary['kerawanan']['tinggi'] ?? 0 }}<br>
                        Sedang: {{ $bencanaSummary['kerawanan']['sedang'] ?? 0 }}<br>
                        Rendah: {{ $bencanaSummary['kerawanan']['rendah'] ?? 0 }}
                    </p>
                </div>

                <!-- JENIS -->
                {{-- <div class="rounded-lg border border-gray-200 bg-blue-100 p-4">
                    <p class="text-xs text-black font-bold">Jenis Bencana</p>
                    <p class="text-gray-900">
                        {{ collect($bencanaSummary['jenis'])->sum() }} Titik
                    </p>
                </div> --}}

            </div>

            <!-- DETAIL INFORMASI -->
            <div class="mt-4 text-sm">

                <p class="font-bold mb-3 text-gray-700">
                    Detail Informasi Bencana
                </p>

                <!-- JENIS BENCANA -->
                <div class="mb-3">
                    <p class="font-medium text-gray-800 mb-2">
                        Jenis Bencana *
                    </p>

                    <ul class="space-y-2">
                        <li class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                            <span>Banjir – {{ $bencanaSummary['jenis']['banjir'] ?? 0 }} Titik</span>
                        </li>

                        <li class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                            <span>Longsor – {{ $bencanaSummary['jenis']['longsor'] ?? 0 }} Titik</span>
                        </li>

                        <li class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-green-500"></span>
                            <span>Gempa – {{ $bencanaSummary['jenis']['gempa'] ?? 0 }} Titik</span>
                        </li>
                    </ul>
                </div>

                <hr class="my-3">

                <!-- WILAYAH PALING TERDAMPAK -->
                <div class="mb-3">
                    <p class="font-medium text-gray-800 mb-2">
                        Wilayah Paling Terdampak *
                    </p>
                    <p class="text-gray-800">
                        {{ $bencanaSummary['wilayah_terdampak']['nama'] ?? '-' }}
                        <span class="text-gray-600 font-normal">
                            ({{ $bencanaSummary['wilayah_terdampak']['total'] ?? 0 }} Titik)
                        </span>
                    </p>
                </div>

                <hr class="my-3">

                <!-- STATUS KEJADIAN -->
                <div class="mb-3">
                    <p class="font-medium text-gray-800 mb-2">
                        Status Kejadian *
                    </p>

                    <ul class="list-disc ml-5 space-y-1">
                        <li>Aktif: {{ $bencanaSummary['status']['aktif'] ?? 0 }}</li>
                        <li>Penanganan: {{ $bencanaSummary['status']['penanganan'] ?? 0 }}</li>
                        <li>Selesai: {{ $bencanaSummary['status']['selesai'] ?? 0 }}</li>
                    </ul>
                </div>

                <hr class="my-3">

                <!-- RADIUS DAMPAK -->
                <div class="mb-3">
                    <p>
                        <span class="font-medium">Radius Dampak:</span>
                        ± {{ $bencanaSummary['radius'] }}
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        Catatan : Radius dampak menunjukkan area perkiraan terdampak bencana dari titik kejadian
                        yang ditampilkan pada peta.
                    </p>
                </div>

                <hr class="my-3">

                <!-- UPDATE (RATA KANAN) -->
                <div class="text-right text-gray-600">
                    <p class="text-xs text-gray-500">
                        Terakhir Udate : {{ optional($bencanaSummary['last_update'])->format('d M Y H:i') }}
                    </p>
                </div>

            </div>

        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('js/select-region.js') }}"></script>
    @endpush
</div>
