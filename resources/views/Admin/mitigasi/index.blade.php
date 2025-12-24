@extends('layouts.app')

@section('content')
    <div class="flex gap-4 px-4">

        <!-- MAP -->
        <div class="w-1/2 min-h-screen">
            <div id="map" class="w-full h-screen rounded border"></div>
        </div>

        <!-- FORM -->
        <div class="w-1/2">

            <x-form.form-elements.select-inputs name="jenis_data" id="formSelector" label="Jenis Data" class="mb-4">
                <option value="" disabled selected>
                    -- Pilih Jenis Data --
                </option>
                <option value="bencana">Bencana</option>
                <option value="posko">Posko</option>
                <option value="fasilitas">Fasilitas Vital</option>
                <option value="jalur">Jalur Evakuasi</option>
                <option value="logistik">Distribusi Logistik</option>
            </x-form.form-elements.select-inputs>

            <div id="formPlaceholder" class="text-gray-500 italic">
                Silakan pilih jenis data pada dropdown di atas untuk mulai mengelola peta mitigasi.
            </div>

            <div id="formContainer">
                <div class="form-item hidden" data-form="bencana">
                    @include('admin.mitigasi.partials.bencana')
                </div>

                <div class="form-item hidden" data-form="posko">
                    @include('admin.mitigasi.partials.posko')
                </div>

                <div class="form-item hidden" data-form="fasilitas">
                    @include('admin.mitigasi.partials.fasilitas_vital')
                </div>

                <div class="form-item hidden" data-form="jalur">
                    @include('admin.mitigasi.partials.jalur_evakuasi')
                </div>

                <div class="form-item hidden" data-form="logistik">
                    @include('admin.mitigasi.partials.distribusi_logistik')
                </div>
            </div>
        </div>

    </div>
    @push('scripts')
        @include('maps')
    @endpush
@endsection
