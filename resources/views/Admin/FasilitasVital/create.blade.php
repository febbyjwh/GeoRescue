@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-semibold">Tambah Fasilitas Vital</h1>

        {{-- opsional kalau mau tombol kembali --}}
        {{-- 
        <a href="{{ route('fasilitasvital.index') }}"
           class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
            Kembali
        </a>
        --}}
    </div>

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
                <option value="">-- Pilih --</option>
                <option>Rumah Sakit</option>
                <option>Puskesmas</option>
                <option>Sekolah</option>
                <option>Kantor Polisi</option>
                <option>Pemadam Kebakaran</option>
                <option>Kantor Pemerintahan</option>
            </x-form.form-elements.select-inputs>

            <x-form.form-elements.text-area-inputs
                label="Alamat"
                name="alamat"
                rows="3"
            >
                {{ old('alamat') }}
            </x-form.form-elements.text-area-inputs>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <x-form.form-elements.default-inputs
                    label="Desa"
                    name="desa"
                    value="{{ old('desa') }}"
                    required
                />

                <x-form.form-elements.default-inputs
                    label="Kecamatan"
                    name="kecamatan"
                    value="{{ old('kecamatan') }}"
                    required
                />
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <x-form.form-elements.default-inputs
                    label="Latitude"
                    name="latitude"
                    value="{{ old('latitude') }}"
                />

                <x-form.form-elements.default-inputs
                    label="Longitude"
                    name="longitude"
                    value="{{ old('longitude') }}"
                />
            </div>

            <x-form.form-elements.select-inputs
                label="Status"
                name="status"
            >
                <option>Beroperasi</option>
                <option>Tidak Tersedia</option>
            </x-form.form-elements.select-inputs>

            <div class="flex justify-end gap-3">
                <button class="inline-flex rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
