<div class="container-fluid">
    {{-- Header --}}
    <!-- Navbar/Header -->
    <x-common.page-breadcrumb pageTitle="Data Posko" class="z-10 relative" />

    {{-- Validation Error --}}
    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-100 px-4 py-3 text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="rounded-xl border border-gray-200 bg-white p-6">
        <form action="{{ route('posko.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Nama Posko --}}
            <x-form.form-elements.default-inputs
                label="Nama Posko"
                name="nama_posko"
                placeholder="Masukkan nama posko"
                value="{{ old('nama_posko') }}"
                required
            />

            {{-- Jenis Posko --}}
            <x-form.form-elements.default-inputs
                label="Jenis Posko"
                name="jenis_posko"
                placeholder="Contoh: Kesehatan, Logistik"
                value="{{ old('jenis_posko') }}"
                required
            />

            {{-- Alamat --}}
            <x-form.form-elements.text-area-inputs
                label="Alamat Posko"
                name="alamat_posko"
                rows="3"
                required
            >
                {{ old('alamat_posko') }}
            </x-form.form-elements.text-area-inputs>

            {{-- Desa & Kecamatan --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <x-form.form-elements.default-inputs
                    label="Nama Desa"
                    name="nama_desa"
                    placeholder="Masukkan nama desa"
                    value="{{ old('nama_desa') }}"
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

            {{-- Koordinat --}}
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

            {{-- Status --}}
            <x-form.form-elements.select-inputs
                label="Status Posko"
                name="status_posko"
                required
            >
                <option value="">-- Pilih Status --</option>
                <option value="Aktif" {{ old('status_posko') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Penuh" {{ old('status_posko') == 'Penuh' ? 'selected' : '' }}>Penuh</option>
                <option value="Tutup" {{ old('status_posko') == 'Tutup' ? 'selected' : '' }}>Tutup</option>
            </x-form.form-elements.select-inputs>

            {{-- Action --}}
            <div class="flex justify-end gap-3 pt-4">
                <button type="submit"
                        class="inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition">
                    Simpan
                </button>

                <a href="{{ route('posko.index') }}"
                   class="inline-flex items-center rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>