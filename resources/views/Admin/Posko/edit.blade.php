@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-semibold">Edit Posko</h1>

        <a href="{{ route('posko.index') }}"
           class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
            Kembali
        </a>
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
        <form action="{{ route('posko.update', $posko->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <x-form.form-elements.default-inputs
                label="Nama Posko"
                name="nama_posko"
                value="{{ $posko->nama_posko }}"
                placeholder="Masukkan nama posko"
                required
            />

            <x-form.form-elements.default-inputs
                label="Jenis Posko"
                name="jenis_posko"
                value="{{ $posko->jenis_posko }}"
                placeholder="Contoh: Kesehatan, Logistik"
                required
            />

            <x-form.form-elements.text-area-inputs
                label="Alamat Posko"
                name="alamat_posko"
                rows="3"
                required
            >
                {{ $posko->alamat_posko }}
            </x-form.form-elements.text-area-inputs>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <x-form.form-elements.default-inputs
                    label="Nama Desa"
                    name="nama_desa"
                    value="{{ $posko->nama_desa }}"
                    placeholder="Masukkan nama desa"
                    required
                />

                <x-form.form-elements.default-inputs
                    label="Kecamatan"
                    name="kecamatan"
                    value="{{ $posko->kecamatan }}"
                    placeholder="Masukkan nama kecamatan"
                    required
                />
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <x-form.form-elements.default-inputs
                    label="Latitude"
                    name="latitude"
                    value="{{ $posko->latitude }}"
                    placeholder="-6.200000"
                />

                <x-form.form-elements.default-inputs
                    label="Longitude"
                    name="longitude"
                    value="{{ $posko->longitude }}"
                    placeholder="106.816666"
                />
            </div>

            <x-form.form-elements.select-inputs
                label="Status Posko"
                name="status_posko"
                required
            >
                <option value="">-- Pilih Status --</option>
                <option value="Aktif" {{ old('status_posko', $posko->status_posko) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Penuh" {{ old('status_posko', $posko->status_posko) == 'Penuh' ? 'selected' : '' }}>Penuh</option>
                <option value="Tutup" {{ old('status_posko', $posko->status_posko) == 'Tutup' ? 'selected' : '' }}>Tutup</option>
            </x-form.form-elements.select-inputs>

            <div class="flex justify-end gap-3 pt-4">
                <button type="submit"
                        class="inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition">
                    Update
                </button>

                <a href="{{ route('posko.index') }}"
                   class="inline-flex items-center rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
