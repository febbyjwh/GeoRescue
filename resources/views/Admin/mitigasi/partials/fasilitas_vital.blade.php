<div class="container-fluid">
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold">Tambah Fasilitas Vital</h1>

        {{-- <a href="{{ route('fasilitasvital.index') }}"
           class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
            Kembali
        </a> --}}
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-100 px-4 py-3 text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-xl border border-gray-200 bg-white p-6">
        <form action="{{ route('fasilitasvital.store') }}" method="POST" class="space-y-6">
            @csrf

            <x-form.form-elements.default-inputs
                label="Nama Fasilitas"
                name="nama_fasilitas"
                placeholder="Masukkan nama fasilitas"
                value="{{ old('nama_fasilitas') }}"
                required
            />

            <x-form.form-elements.select-inputs
                label="Jenis Fasilitas"
                name="jenis_fasilitas"
                required
            >
                <option value="">-- Pilih Jenis --</option>
                @foreach([
                    'Rumah Sakit',
                    'Puskesmas',
                    'Sekolah',
                    'SPBU',
                    'Kantor Polisi',
                    'Pemadam Kebakaran',
                    'Kantor Pemerintahan'
                ] as $jenis)
                    <option value="{{ $jenis }}" {{ old('jenis_fasilitas') == $jenis ? 'selected' : '' }}>
                        {{ $jenis }}
                    </option>
                @endforeach
            </x-form.form-elements.select-inputs>

            <x-form.form-elements.text-area-inputs
                label="Alamat"
                name="alamat"
                placeholder="Masukkan alamat lengkap"
                rows="3"
            >
                {{ old('alamat') }}
            </x-form.form-elements.text-area-inputs>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <x-form.form-elements.default-inputs
                    label="Desa"
                    name="desa"
                    placeholder="Masukkan nama desa"
                    value="{{ old('desa') }}"
                    required
                />

                <x-form.form-elements.default-inputs
                    label="Kecamatan"
                    name="kecamatan"
                    placeholder="Masukkan nama kecamatan"
                    value="{{ old('kecamatan') }}"
                    required
                />
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <x-form.form-elements.default-inputs
                    label="Latitude"
                    name="latitude"
                    placeholder="-6.200000"
                    value="{{ old('latitude') }}"
                />

                <x-form.form-elements.default-inputs
                    label="Longitude"
                    name="longitude"
                    placeholder="106.816666"
                    value="{{ old('longitude') }}"
                />
            </div>

            <x-form.form-elements.select-inputs
                label="Status"
                name="status"
                required
            >
                <option value="">-- Pilih Status --</option>
                <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Rusak" {{ old('status') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
            </x-form.form-elements.select-inputs>

            <div class="flex justify-end gap-3 pt-4">
                <button type="submit"
                        class="inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition">
                    Simpan
                </button>

                <a href="{{ route('fasilitasvital.index') }}"
                   class="inline-flex items-center rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>