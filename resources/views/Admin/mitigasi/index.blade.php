@extends('layouts.app')

@section('content')
    <div class="flex gap-4 h-sreen">

        <!-- MAP -->
        <div class="w-1/2 h-full">
            <div id="map" class="w-full h-screen rounded border"></div>
        </div>

        <!-- FORM -->
        <div class="w-1/2 h-full overflow-y-auto pr-2">

            <x-form.form-elements.select-inputs name="jenis_data" id="formSelector" label="Jenis Data" class="mb-4">
                <option value="" disabled selected>
                    Pilih Jenis Data
                </option>
                <option value="bencana">Bencana</option>
                <option value="posko">Posko</option>
                <option value="fasilitas">Fasilitas Vital</option>
                {{-- <option value="jalur">Jalur Evakuasi</option> --}}
                <option value="logistik">Distribusi Logistik</option>
            </x-form.form-elements.select-inputs>

            <div id="formPlaceholder" class="flex flex-col items-center justify-center mt-35 text-gray-500 italic space-y-4">
                <!-- SVG Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 20 20"
                    fill="currentColor" class="text-gray-400">
                    <path
                        d="m13 13.14l1.17-5.94c.79-.43 1.33-1.25 1.33-2.2a2.5 2.5 0 0 0-5 0c0 .95.54 1.77 1.33 2.2zm0-9.64c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5s-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5zm1.72 4.8L18 6.97v9L13.12 18L7 15.97l-5 2v-9l5-2l4.27 1.41l1.73 7.3z" />
                </svg>

                <!-- Placeholder Text -->
                <div class="text-gray-500 italic text-center">
                    Silakan pilih jenis data pada dropdown di atas untuk mulai mengelola peta mitigasi.
                </div>
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

                {{-- <div class="form-item hidden" data-form="jalur">
                    @include('admin.mitigasi.partials.jalur_evakuasi')
                </div> --}}

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
