<div class="container-fluid">

    <!-- Navbar/Header -->
    <x-common.page-breadcrumb pageTitle="Data Jalur Evakuasi" class="z-10 relative" />

    <div class="rounded-xl border border-gray-200 bg-white p-6">
        <form id="jalurForm" action="{{ route('jalur_evakuasi.store') }}" method="POST" class="space-y-6">
            @csrf

            <x-form.form-elements.default-inputs
                label="Nama Jalur"
                name="nama_jalur"
                placeholder="Masukkan nama jalur"
                value="{{ old('nama_jalur') }}"
                required
            />

            <x-form.form-elements.text-area-inputs
                label="Deskripsi Jalur"
                name="deskripsi"
                rows="3"
            >
                {{ old('deskripsi') }}
            </x-form.form-elements.text-area-inputs>

            {{-- <div class="mb-4">
                <label class="block font-medium mb-2">Buat Jalur di Map</label>
                <div id="map" class="w-full h-[600px] rounded border"></div>
            </div> --}}

            <input type="hidden" name="geojson" id="geojsonInput">

            <div class="flex justify-end gap-3">
                <button type="submit" class="inline-flex rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>

</div>
