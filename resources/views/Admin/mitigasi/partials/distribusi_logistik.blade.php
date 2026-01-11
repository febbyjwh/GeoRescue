<div class="container-fluid">

    <x-common.page-breadcrumb pageTitle="Data Logistik" class="z-10 relative" />

    <div class="rounded-xl border border-gray-200 bg-white p-6">
        <form action="{{ route('jalur_distribusi_logistik.store') }}" method="POST" class="space-y-6">
            @csrf

            <x-form.form-elements.default-inputs label="Nama Lokasi" name="nama_lokasi"
                placeholder="Contoh: Gudang Logistik Cicalengka" value="{{ old('nama_lokasi') }}" required />

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="">
                    <label class="block text-sm font-medium mb-1">Kecamatan</label>
                    <select id="logistik_district" class="w-full" style="width: 100%"></select>
                </div>

                <div class="">
                    <label class="block text-sm font-medium mb-1">Desa</label>
                    <select id="logistik_village" class="w-full" style="width: 100%"></select>
                </div>

            </div>

            <x-form.form-elements.select-inputs label="Jenis Logistik" name="jenis_logistik" required>
                <option value="">-- Pilih Jenis Logistik --</option>
                <option value="Makanan">Makanan</option>
                <option value="Obat-obatan">Obat-obatan</option>
                <option value="Air Bersih">Air Bersih</option>
                <option value="Selimut">Selimut</option>
                <option value="Tenda">Tenda</option>
            </x-form.form-elements.select-inputs>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <x-form.form-elements.default-inputs label="Jumlah" name="jumlah" type="number"
                    placeholder="Contoh: 100" value="{{ old('jumlah') }}" required />

                <x-form.form-elements.default-inputs label="Satuan" name="satuan"
                    placeholder="Contoh: Paket / Dus / Liter" value="{{ old('satuan') }}" required />
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <x-form.form-elements.default-inputs label="Latitude" name="lat" id="lat"
                    placeholder="-6.914744" value="{{ old('lat') }}"  />

                <x-form.form-elements.default-inputs label="Longitude" name="lang" id="lang"
                    placeholder="107.609810" value="{{ old('lang') }}"  />
            </div>


            <x-form.form-elements.select-inputs label="Status Logistik" name="status" required>
                <option value="">-- Pilih Status --</option>
                <option value="tersedia">Tersedia</option>
                <option value="menipis">Menipis</option>
                <option value="habis">Habis</option>
            </x-form.form-elements.select-inputs>

            <div class="flex justify-end gap-3">
                <div>
                    <button type="button" id="btnTambahTitik" class="inline-flex rounded-lg bg-yellow-300 px-5 py-2.5 text-sm font-medium text-black hover:bg-yellow-500">
                        Tambah Titik
                    </button>

                    <button type="submit"
                        class="inline-flex rounded-lg bg-amber-300 px-5 py-2.5 text-sm font-medium text-black hover:bg-amber-500">
                        Simpan
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
