<div class="container-fluid">
    <x-common.page-breadcrumb pageTitle="Data Titik Bencana" class="z-10 relative" />    <div class="rounded-xl border border-gray-200 bg-white p-6">

        <div class="mb-3">
            <x-form.form-elements.default-inputs id="nama_bencana" label="Nama Bencana" name="nama_bencana"
                placeholder="Masukkan nama Bencana" value="{{ old('nama_bencana') }}" required />
        </div>

        <input type="hidden" id="bencana_id">

        <div class="mb-3">
            <label class="block text-sm font-medium mb-1">Kecamatan</label>
            <select id="district_id" class="w-full" style="width: 100%"></select>
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium mb-1">Desa</label>
            <select id="village_id" class="w-full" style="width: 100%"></select>
        </div>

        <x-form.form-elements.select-inputs label="Tingkat Kerawanan" name="tingkat_kerawanan" id="tingkat_kerawanan"
            required>
            <option value="">-- Pilih Tingkat Kerawanan --</option>
            <option value="Tinggi" {{ old('tingkat_kerawanan') == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
            <option value="Sedang" {{ old('tingkat_kerawanan') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
            <option value="Rendah" {{ old('tingkat_kerawanan') == 'Rendah' ? 'selected' : '' }}>Rendah</option>
        </x-form.form-elements.select-inputs>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <x-form.form-elements.default-inputs label="Longitude" name="lang" id="lang"
                placeholder="106.816666" value="{{ old('lang') }}" />
            <x-form.form-elements.default-inputs label="Latitude" name="lat" id="lat" placeholder="-6.200000"
                value="{{ old('lat') }}" />
        </div>
        <div class="mb-3">
            <button type="button" onclick="submitBencana()"
                class="inline-flex items-center rounded-lg bg-amber-300 px-5 py-2.5 text-sm mt-6 font-medium text-black hover:bg-amber-500 transition">
                Simpan
            </button>
        </div>
    </div>
  
</div>
