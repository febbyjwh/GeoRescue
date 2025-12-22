<div class="container-fluid">

    <!-- Navbar/Header -->
    <x-common.page-breadcrumb pageTitle="Data Distribusi Logistik" class="z-10 relative" />

    <div class="rounded-xl border border-gray-200 bg-white p-6">
        <form action="{{ route('jalur_distribusi_logistik.store') }}" method="POST" class="space-y-6">
            @csrf

            <x-form.form-elements.default-inputs
                label="Nama Jalur"
                name="nama_jalur"
                placeholder="Contoh: Gudang A - Posko B"
                value="{{ old('nama_jalur') }}"
                required
            />

            <x-form.form-elements.default-inputs
                label="Asal Logistik"
                name="asal_logistik"
                placeholder="Gudang / Posko Asal"
                value="{{ old('asal_logistik') }}"
                required
            />

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <x-form.form-elements.default-inputs
                    label="Latitude Asal"
                    name="asal_latitude"
                    placeholder="-6.914744"
                    value="{{ old('asal_latitude') }}"
                    required
                />

                <x-form.form-elements.default-inputs
                    label="Longitude Asal"
                    name="asal_longitude"
                    placeholder="107.609810"
                    value="{{ old('asal_longitude') }}"
                    required
                />
            </div>

            <x-form.form-elements.default-inputs
                label="Tujuan Distribusi"
                name="tujuan_distribusi"
                placeholder="Posko / Wilayah Tujuan"
                value="{{ old('tujuan_distribusi') }}"
                required
            />

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <x-form.form-elements.default-inputs
                    label="Latitude Tujuan"
                    name="tujuan_latitude"
                    placeholder="-6.921234"
                    value="{{ old('tujuan_latitude') }}"
                    required
                />

                <x-form.form-elements.default-inputs
                    label="Longitude Tujuan"
                    name="tujuan_longitude"
                    placeholder="107.614321"
                    value="{{ old('tujuan_longitude') }}"
                    required
                />
            </div>

            <x-form.form-elements.select-inputs
                label="Status Jalur"
                name="status_jalur"
                required
            >
                <option value="">-- Pilih Status --</option>
                <option value="aktif">Aktif</option>
                <option value="terhambat">Terhambat</option>
                <option value="ditutup">Ditutup</option>
            </x-form.form-elements.select-inputs>

            <div class="flex justify-end gap-3">
                <button class="inline-flex rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>