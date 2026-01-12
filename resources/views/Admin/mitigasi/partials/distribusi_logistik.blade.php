<div class="container-fluid">

    <x-common.page-breadcrumb pageTitle="Data Logistik" class="z-10 relative" />

    <!-- FORM LOGISTIK -->
    <div class="rounded-xl border border-gray-200 bg-white p-6">
        <form id="formLogistik" onclick="setActiveModule('logistik')" onfocusin="setActiveModule('logistik')">
            @csrf
            <input type="hidden" id="logistik_id">

            {{-- NAMA LOKASI --}}
            <x-form.form-elements.default-inputs label="Nama Distribusi Logistik" name="nama_lokasi" id="nama_lokasi"
                placeholder="Contoh: Gudang Logistik Cicalengka" required />

            {{-- KECAMATAN & DESA --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 mt-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Kecamatan</label>
                    <select id="logistik_district" class="w-full" style="width:100%"></select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Desa</label>
                    <select id="logistik_village" class="w-full" style="width:100%"></select>
                </div>
            </div>

            {{-- JENIS LOGISTIK --}}
            <x-form.form-elements.select-inputs label="Jenis Logistik" name="jenis_logistik" id="jenis_logistik"
                required class="mt-4">
                <option value="">-- Pilih Jenis Logistik --</option>
                <option value="all">Semua Logistik</option>
                <option value="pangan">Pangan</option>
                <option value="sandang">Sandang</option>
                <option value="kesehatan">Kesehatan</option>
                <option value="hunian">Hunian</option>
            </x-form.form-elements.select-inputs>

            {{-- JUMLAH & SATUAN --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 mt-4">
                <x-form.form-elements.default-inputs label="Jumlah" name="jumlah" id="jumlah" type="number"
                    placeholder="Contoh: 100" required />
                <x-form.form-elements.default-inputs label="Satuan" name="satuan" id="satuan"
                    placeholder="Contoh: Paket / Dus / Liter" required />
            </div>

            {{-- LAT & LNG --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 mt-4">
                <x-form.form-elements.default-inputs label="Latitude" name="lat" id="lat"
                    placeholder="-6.914744" />
                <x-form.form-elements.default-inputs label="Longitude" name="lng" id="lng"
                    placeholder="107.609810" />
            </div>

            {{-- STATUS --}}
            <x-form.form-elements.select-inputs label="Status Logistik" name="status" id="status" required
                class="mt-4">
                <option value="">-- Pilih Status --</option>
                <option value="tersedia">Tersedia</option>
                <option value="menipis">Menipis</option>
                <option value="habis">Habis</option>
            </x-form.form-elements.select-inputs>

            {{-- BUTTON --}}
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="submitLogistik()"
                    class="inline-flex items-center rounded-lg
                           bg-amber-300 px-5 py-2 text-sm font-medium
                           text-black hover:bg-amber-500 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    <!-- DETAIL LOGISTIK TERPILIH -->
    <div id="selectedLogistik" class="hidden mt-4 rounded-xl border border-amber-200 bg-amber-50 p-5 text-sm">
        <h3 class="text-md font-semibold mb-3 text-amber-800">
            📍 Detail Logistik Terpilih
        </h3>

        <ul class="space-y-2 text-gray-800">
            <li>
                <span class="font-medium">Nama Lokasi:</span>
                <span id="detailNamaLogistik">-</span>
            </li>
            <li>
                <span class="font-medium">Jenis Logistik:</span>
                <span id="detailJenisLogistik">-</span>
            </li>
            <li>
                <span class="font-medium">Jumlah & Satuan:</span>
                <span id="detailJumlahSatuan">-</span>
            </li>
            <li>
                <span class="font-medium">Status:</span>
                <span id="detailStatusLogistik">-</span>
            </li>
            <li>
                <span class="font-medium">Kecamatan:</span>
                <span id="detailKecamatanLogistik">-</span>
            </li>
            <li>
                <span class="font-medium">Desa:</span>
                <span id="detailDesaLogistik">-</span>
            </li>
            <li>
                <span class="font-medium">Koordinat:</span>
                <span id="detailKoordinatLogistik">-</span>
            </li>
        </ul>

        <p class="text-xs text-gray-500 mt-3">
            Informasi ini ditampilkan berdasarkan logistik yang dipilih pada peta.
        </p>
    </div>

    <!-- INFORMASI UMUM LOGISTIK -->
    <div class="mt-4 rounded-xl border border-blue-200 bg-blue-50 p-5 text-sm">

        <h3 class="text-md font-semibold mb-4 text-blue-800">
            📦 Informasi Umum Logistik
        </h3>

        <!-- RINGKASAN 4 CARD -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
            <div class="rounded-lg border border-gray-200 bg-blue-100 p-4">
                <p class="text-xs text-black font-bold">Total Lokasi Logistik</p>
                <p class="text-gray-900">{{ $logistikSummary['total_lokasi'] ?? 0 }}</p>
            </div>

            <div class="rounded-lg border border-gray-200 bg-blue-100 p-4">
                <p class="text-xs text-black font-bold">Total Stok Logistik</p>
                <p class="text-gray-900">{{ number_format($logistikSummary['total_stok'] ?? 0) }} Paket</p>
            </div>

            <div class="rounded-lg border border-gray-200 bg-blue-100 p-4">
                <p class="text-xs text-black font-bold">Kecamatan Terlayani</p>
                <p class="text-gray-900">{{ $logistikSummary['kecamatan'] ?? 0 }} Kecamatan</p>
            </div>

            <div class="rounded-lg border border-gray-200 bg-blue-100 p-4">
                <p class="text-xs text-black font-bold">Desa Terlayani</p>
                <p class="text-gray-900">{{ $logistikSummary['desa'] ?? 0 }} Desa</p>
            </div>
        </div>

        <!-- DETAIL INFORMASI LOGISTIK -->
        <div class="mt-4">
            <p class="font-bold mb-3 text-gray-700">Detail Informasi Logistik</p>

            <!-- DISTRIBUSI JENIS LOGISTIK -->
            <div class="mb-3">
                <p class="font-medium text-gray-800 mb-2">Distribusi Jenis Logistik *</p>
                <ul class="space-y-2">
                    <li class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-green-500"></span>
                        <span>Pangan – {{ number_format($logistikSummary['jenis']['pangan'] ?? 0) }} Paket</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                        <span>Sandang – {{ number_format($logistikSummary['jenis']['sandang'] ?? 0) }} Paket</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-500"></span>
                        <span>Kesehatan – {{ number_format($logistikSummary['jenis']['kesehatan'] ?? 0) }} Paket</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-purple-500"></span>
                        <span>Hunian – {{ number_format($logistikSummary['jenis']['hunian'] ?? 0) }} Paket</span>
                    </li>
                </ul>
            </div>

            <hr class="my-3">

            <!-- ANALISIS SPASIAL -->
            <div class="mb-3">
                <p class="font-medium text-gray-800 mb-2">Analisis Spasial Logistik *</p>
                <p class="text-gray-800 leading-relaxed">
                    Persebaran gudang logistik berada di
                    <strong>{{ $logistikSummary['kecamatan'] ?? '-' }}</strong> kecamatan dengan total
                    <strong>{{ number_format($logistikSummary['total_stok'] ?? 0) }}</strong> paket logistik.
                    Logistik jenis
                    <strong class="capitalize">{{ $logistikSummary['jenis_terbanyak'] ?? '-' }}</strong>
                    menjadi stok terbanyak, membantu fokus distribusi ke wilayah terdampak.
                </p>
            </div>

            {{-- <hr class="my-3"> --}}

            <!-- LAST UPDATE -->
            <div class="text-right text-gray-600">
                <p class="text-xs text-gray-500">
                    Terakhir Update: {{ optional($logistikSummary['last_update'])->format('d M Y H:i') }}
                </p>
            </div>
        </div>
    </div>

</div>
