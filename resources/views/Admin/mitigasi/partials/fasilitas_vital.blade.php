<div class="container-fluid">
    <x-common.page-breadcrumb pageTitle="Data Fasilitas Vital" class="z-10 relative" />
    <form id="formFasilitas" onsubmit="event.preventDefault()" onclick="setActiveModule('fasilitas')">
        <div class="rounded-xl border border-gray-200 bg-white p-6">

            <div class="mb-3">
                <x-form.form-elements.default-inputs id="nama_fasilitas" label="Nama Fasilitas" name="nama_fasilitas"
                    placeholder="Masukkan nama fasilitas" value="{{ old('nama_fasilitas') }}" required />
            </div>

            <input type="hidden" id="fasilitas_id">

            <div class="mb-3">
                <x-form.form-elements.select-inputs label="Jenis Fasilitas" name="jenis_fasilitas" id="jenis_fasilitas"
                    required>
                    <option value="">-- Pilih --</option>
                    <option value="Rumah Sakit">Rumah Sakit</option>
                    <option value="Puskesmas">Puskesmas</option>
                    <option value="Sekolah">Sekolah</option>
                    <option value="Kantor Polisi">Kantor Polisi</option>
                    <option value="Pemadam Kebakaran">Pemadam Kebakaran</option>
                    <option value="Kantor Pemerintahan">Kantor Pemerintahan</option>
                </x-form.form-elements.select-inputs>
            </div>

            <div class="mb-3">
                <x-form.form-elements.text-area-inputs label="Alamat" name="alamat" id="alamat" rows="3">
                    {{ old('alamat') }}
                </x-form.form-elements.text-area-inputs>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Kecamatan</label>
                <select id="fasilitas_district_id" class="w-full" style="width: 100%"></select>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Desa</label>
                <select id="fasilitas_village_id" class="w-full" style="width: 100%"></select>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <x-form.form-elements.default-inputs label="Latitude" name="latitude" id="latitude"
                    placeholder="-6.200000" value="{{ old('latitude') }}" />

                <x-form.form-elements.default-inputs label="Longitude" name="longitude" id="longitude"
                    placeholder="106.816666" value="{{ old('longitude') }}" />
            </div>

            <div class="mb-3">
                <x-form.form-elements.select-inputs label="Status" name="status" id="status">
                    <option value="">-- Pilih Status --</option>
                    <option value="Beroperasi">Beroperasi</option>
                    <option value="Tidak Tersedia">Tidak Tersedia</option>
                </x-form.form-elements.select-inputs>
            </div>

            <div class="mb-3">
                <button type="button" onclick="submitFasilitas()"
                    class="inline-flex items-center rounded-lg bg-amber-300 px-5 py-2.5 text-sm mt-6 font-medium text-black hover:bg-amber-500 transition">
                    Simpan
                </button>
            </div>
        </div>
    </form>

    <!-- DETAIL FASILITAS TERPILIH -->
    <div id="selectedFasilitas" class="hidden mt-4 rounded-xl border border-amber-200 bg-amber-50 p-5 text-sm">

        <h3 class="text-md font-semibold mb-3 text-amber-800">
            📍 Detail Fasilitas Terpilih
        </h3>

        <ul class="space-y-2 text-gray-800">
            <li>
                <span class="font-medium">Nama Fasilitas:</span>
                <span id="detailNamaFasilitas">-</span>
            </li>
            <li>
                <span class="font-medium">Jenis Fasilitas:</span>
                <span id="detailJenisFasilitas">-</span>
            </li>
            <li>
                <span class="font-medium">Kecamatan:</span>
                <span id="detailKecamatanFasilitas">-</span>
            </li>
            <li>
                <span class="font-medium">Desa:</span>
                <span id="detailDesaFasilitas">-</span>
            </li>
            <li>
                <span class="font-medium">Status:</span>
                <span id="detailStatusFasilitas">-</span>
            </li>
            <li>
                <span class="font-medium">Alamat:</span>
                <span id="detailAlamatFasilitas">-</span>
            </li>
            <li>
                <span class="font-medium">Koordinat:</span>
                <span id="detailKoordinatFasilitas">-</span>
            </li>
        </ul>

        <p class="text-xs text-gray-500 mt-3">
            Informasi ini ditampilkan berdasarkan fasilitas yang dipilih pada peta.
        </p>
    </div>

    <div class="mt-4 rounded-xl border border-blue-200 bg-blue-50 p-5 text-sm">
        <h3 class="text-md font-semibold mb-4 text-blue-800">
            📍 Informasi Umum Fasilitas Vital
        </h3>

        <!-- RINGKASAN (3 CARD) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">

            <!-- TOTAL -->
            <div class="rounded-lg border border-gray-200 bg-blue-100 p-4">
                <p class="text-xs text-black font-bold">Total Fasilitas</p>
                <p class="text-gray-900">{{ $fasilitasSummary['total'] }} Fasilitas</p>
            </div>

            <!-- KECAMATAN -->
            <div class="rounded-lg border border-gray-200 bg-blue-100 p-4">
                <p class="text-xs text-black font-bold">Jumlah Kecamatan Terdampak</p>
                <p class="text-gray-900">{{ $fasilitasSummary['kecamatan'] }} Kecamatan</p>
            </div>

            <!-- DESA -->
            <div class="rounded-lg border border-gray-200 bg-blue-100 p-4">
                <p class="text-xs text-black font-bold">Jumlah Desa Terdampak</p>
                <p class="text-gray-900">{{ $fasilitasSummary['desa'] }} Desa</p>
            </div>

        </div>

        <!-- DETAIL INFORMASI -->
        <div class="mt-4 text-sm">

            <!-- STATUS FASILITAS -->
            <div class="mb-3">
                <p class="font-bold mb-2 text-gray-700">Status Fasilitas *</p>
                <ul class="list-disc ml-5 space-y-1">
                    @foreach ($fasilitasSummary['status'] as $status => $count)
                        <li>{{ $status }}: {{ $count }}</li>
                    @endforeach
                </ul>
            </div>

            <hr class="my-3">

            <!-- JENIS FASILITAS -->
            <div class="mb-3">
                <p class="font-bold mb-2 text-gray-700">Jenis Fasilitas *</p>
                <ul class="list-disc ml-5 space-y-1">
                    @foreach ($fasilitasSummary['jenis'] as $jenis => $count)
                        <li>{{ $jenis }}: {{ $count }}</li>
                    @endforeach
                </ul>
            </div>

            <hr class="my-3">

            <!-- KECAMATAN TERBANYAK -->
            <div class="mb-3">
                <p class="font-bold mb-2 text-gray-700">Kecamatan dengan Fasilitas Terbanyak *</p>
                <p class="text-gray-900">
                    {{ $fasilitasSummary['wilayah_terbanyak']['nama'] ?? '-' }}
                    <span class="text-gray-600 font-normal">
                        ({{ $fasilitasSummary['wilayah_terbanyak']['total'] ?? 0 }} Fasilitas)
                    </span>
                </p>
            </div>

            <hr class="my-3">

            <!-- LAST UPDATE -->
            <div class="text-right text-gray-600">
                <p class="text-xs text-gray-500">
                    Terakhir Update : {{ optional($fasilitasSummary['last_update'])->format('d M Y H:i') }}
                </p>
            </div>
        </div>
    </div>

</div>
