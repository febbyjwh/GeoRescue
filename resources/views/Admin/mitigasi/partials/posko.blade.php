<div class="container-fluid">
    <x-common.page-breadcrumb pageTitle="Data Posko" class="z-10 relative" />

    <div class="rounded-xl border border-gray-200 bg-white p-6">

        {{-- Nama Posko --}}
        <div class="mb-3">
            <x-form.form-elements.default-inputs
                id="nama_posko"
                label="Nama Posko"
                name="nama_posko"
                placeholder="Masukkan nama posko"
                value="{{ old('nama_posko') }}"
                required
            />
        </div>

        {{-- Hidden ID (EDIT MODE) --}}
        <input type="hidden" id="posko_id">

        {{-- Kecamatan --}}
         <div class="mb-3">
            <label class="block text-sm font-medium mb-1">Kecamatan</label>
            <select id="district_id" class="w-full" style="width: 100%"></select>
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium mb-1">Desa</label>
            <select id="village_id" class="w-full" style="width: 100%"></select>
        </div>

        {{-- Jenis Posko --}}
        <div class="mb-3">
            <x-form.form-elements.default-inputs
                id="jenis_posko"
                label="Jenis Posko"
                name="jenis_posko"
                placeholder="Contoh: Kesehatan, Logistik"
                value="{{ old('jenis_posko') }}"
                required
            />
        </div>

        {{-- Status Posko --}}
        <x-form.form-elements.select-inputs
            label="Status Posko"
            name="status_posko"
            id="status_posko"
            required
        >
            <option value="">-- Pilih Status --</option>
            <option value="Aktif">Aktif</option>
            <option value="Penuh">Penuh</option>
            <option value="Tutup">Tutup</option>
        </x-form.form-elements.select-inputs>

        {{-- Koordinat --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <x-form.form-elements.default-inputs
                label="Longitude"
                name="longitude"
                id="longitude"
                placeholder="106.816666"
                value="{{ old('longitude') }}"
            />
            <x-form.form-elements.default-inputs
                label="Latitude"
                name="latitude"
                id="latitude"
                placeholder="-6.200000"
                value="{{ old('latitude') }}"
            />
        </div>

        {{-- Submit --}}
        <div class="mb-3">
            <button
                type="button"
                onclick="submitPosko()"
                class="inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm mt-6 font-medium text-white hover:bg-blue-700 transition"
            >
                Simpan
            </button>
        </div>
    </div>

    <div id="map" style="height:400px" class="mt-6 rounded-lg"></div>
</div>
@push('scripts')
    <script src="{{ asset('js/select-region.js') }}"></script>
@endpush
