@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-semibold">Edit Jalur Distribusi Logistik</h1>

        <a href="{{ route('jalurdistribusi.index') }}"
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
        <form action="{{ route('jalurdistribusi.update', $jalur->id) }}"
              method="POST"
              class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Nama Jalur --}}
            <x-form.form-elements.default-inputs
                label="Nama Jalur"
                name="nama_jalur"
                value="{{ old('nama_jalur', $jalur->nama_jalur) }}"
                required
            />

            {{-- Asal Logistik --}}
            <x-form.form-elements.default-inputs
                label="Asal Logistik"
                name="asal_logistik"
                value="{{ old('asal_logistik', $jalur->asal_logistik) }}"
                required
            />

            {{-- Koordinat Asal --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <x-form.form-elements.default-inputs
                    label="Latitude Asal"
                    name="asal_latitude"
                    value="{{ old('asal_latitude', $jalur->asal_latitude) }}"
                    required
                />

                <x-form.form-elements.default-inputs
                    label="Longitude Asal"
                    name="asal_longitude"
                    value="{{ old('asal_longitude', $jalur->asal_longitude) }}"
                    required
                />
            </div>

            {{-- Tujuan Distribusi --}}
            <x-form.form-elements.default-inputs
                label="Tujuan Distribusi"
                name="tujuan_distribusi"
                value="{{ old('tujuan_distribusi', $jalur->tujuan_distribusi) }}"
                required
            />

            {{-- Koordinat Tujuan --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <x-form.form-elements.default-inputs
                    label="Latitude Tujuan"
                    name="tujuan_latitude"
                    value="{{ old('tujuan_latitude', $jalur->tujuan_latitude) }}"
                    required
                />

                <x-form.form-elements.default-inputs
                    label="Longitude Tujuan"
                    name="tujuan_longitude"
                    value="{{ old('tujuan_longitude', $jalur->tujuan_longitude) }}"
                    required
                />
            </div>

            {{-- Status Jalur --}}
            <x-form.form-elements.select-inputs
                label="Status Jalur"
                name="status_jalur"
                required
            >
                @foreach (['aktif' => 'Aktif', 'terhambat' => 'Terhambat', 'ditutup' => 'Ditutup'] as $key => $label)
                    <option value="{{ $key }}"
                        {{ old('status_jalur', $jalur->status_jalur) == $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </x-form.form-elements.select-inputs>

            <div class="flex justify-end gap-3 pt-4">
                <button type="submit"
                        class="inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition">
                    Update
                </button>

                <a href="{{ route('jalurdistribusi.index') }}"
                   class="inline-flex items-center rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection