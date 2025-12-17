@extends('layouts.app')

@section('content')
    <div class="flex gap-4 px-4">

        <!-- MAP -->
        <div class="w-1/2 min-h-screen">
            <div id="map" class="h-full rounded border"></div>
        </div>

        <!-- FORM -->
        <div class="w-1/2">
            {{-- form selector --}}
            {{-- <select id="formSelector" class="w-full mb-4 border rounded p-2">
            <option value="">Pilih Jenis Data</option>
            <option value="bencana">Bencana</option>
            <option value="posko">Posko</option>
            <option value="fasilitas">Fasilitas Vital</option>
            <option value="jalur">Jalur Evakuasi</option>
            <option value="logistik">Distribusi Logistik</option>
        </select> --}}
            <div id="formPlaceholder" class="text-gray-500 italic">
                Silakan pilih jenis data pada dropdown di atas untuk mulai mengelola peta mitigasi.
            </div>


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

            <div id="formContainer">
                <div class="form-item hidden" data-form="bencana">
                    @include('admin.mitigasi.partials.data_bencana')
                </div>

                <div class="form-item hidden" data-form="posko">
                    @include('admin.mitigasi.partials.posko')
                </div>

                <div class="form-item hidden" data-form="fasilitas">
                    @include('Admin.FasilitasVital.create')
                </div>

                <div class="form-item hidden" data-form="jalur">
                    @include('admin.jalur_evakuasi.create')
                </div>

                <div class="form-item hidden" data-form="logistik">
                    @include('admin.mitigasi.partials.distribusi_logistik')
                </div>
            </div>
        </div>

    </div>
@endsection
@include('maps')
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const selector = document.querySelector('select[name="jenis_data"]');
            const forms = document.querySelectorAll('.form-item');

            if (!selector) return;

            selector.addEventListener('change', () => {
                const selected = selector.value;

                forms.forEach(form => form.classList.add('hidden'));

                if (selected) {
                    const activeForm = document.querySelector(
                        `.form-item[data-form="${selected}"]`
                    );
                    if (activeForm) {
                        activeForm.classList.remove('hidden');
                    }
                }
            });
        });
    </script>
@endpush
