<div class="container-fluid">
    <x-common.page-breadcrumb pageTitle="Data Fasilitas Vital" class="z-10 relative" />
    <form id="formBencana" onsubmit="event.preventDefault()" onclick="setActiveModule('fasilitas')">
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
</div>
