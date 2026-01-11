<div class="container-fluid">
    <x-common.page-breadcrumb pageTitle="Data Posko" class="z-10 relative" />

    <div class="rounded-xl border border-gray-200 bg-white p-6">

        {{-- FORM POSKO --}}
        <form id="formPosko" onclick="setActiveModule('posko')" onfocusin="setActiveModule('posko')">
            @csrf

            <x-form.form-elements.default-inputs
                label="Nama Posko"
                name="nama_posko"
                placeholder="Masukkan nama posko"
                value="{{ old('nama_posko') }}"
                required
            />

            <x-form.form-elements.select-inputs
                label="Jenis Posko"
                name="jenis_posko"
                id="jenis_posko"
                required
            >
                <option value="">Pilih Posko</option>
                <option value="Kesehatan">Kesehatan</option>
                <option value="Evakuasi">Evakuasi</option>
            </x-form.form-elements.select-inputs>

            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Kecamatan</label>
                <select id="kecamatan_id" name="kecamatan_id" class="w-full"></select>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Desa</label>
                <select id="desa_id" name="desa_id" class="w-full"></select>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <x-form.form-elements.default-inputs
                    label="Latitude"
                    name="latitude"
                    id="latitude"
                    placeholder="-6.200000"
                />

                <x-form.form-elements.default-inputs
                    label="Longitude"
                    name="longitude"
                    id="longitude"
                    placeholder="106.816666"
                />
            </div>

            <x-form.form-elements.select-inputs
                label="Status Posko"
                name="status_posko"
                required
            >
                <option value="">-- Pilih Status --</option>
                <option value="Aktif">Aktif</option>
                <option value="Penuh">Penuh</option>
                <option value="Tutup">Tutup</option>
            </x-form.form-elements.select-inputs>

            <div class="mb-3">
                <button type="button"
                    onclick="submitPosko()"
                    class="inline-flex items-center rounded-lg bg-amber-300 px-5 py-2.5 text-sm mt-6 font-medium text-black hover:bg-amber-500 transition">
                    Simpan
                </button>
            </div>
        </form>

        {{-- INFORMASI UMUM POSKO (MENYAMAI BENCANA) --}}
        <div class="mt-4 rounded-xl border border-blue-200 bg-blue-50 p-5 text-sm">

            <h3 class="text-md font-semibold mb-4 text-blue-800">
                📍 Informasi Umum Posko
            </h3>

            {{-- RINGKASAN --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">

                <div class="rounded-lg border border-gray-200 bg-blue-100 p-4">
                    <p class="text-xs text-black font-bold">Total Posko</p>
                    <p class="text-gray-900">
                        {{ $poskoSummary['total'] }} Posko
                    </p>
                </div>

                <div class="rounded-lg border border-gray-200 bg-blue-100 p-4">
                    <p class="text-xs text-black font-bold">Wilayah Terjangkau</p>
                    <p class="text-gray-900">
                        {{ $poskoSummary['kecamatan'] }} Kecamatan<br>
                        {{ $poskoSummary['desa'] }} Desa
                    </p>
                </div>

                <div class="rounded-lg border border-gray-200 bg-blue-100 p-4">
                    <p class="text-xs text-black font-bold">Status Posko</p>
                    <p class="text-gray-900">
                        Aktif: {{ $poskoSummary['status']['Aktif'] ?? 0 }}<br>
                        Penuh: {{ $poskoSummary['status']['Penuh'] ?? 0 }}<br>
                        Tutup: {{ $poskoSummary['status']['Tutup'] ?? 0 }}
                    </p>
                </div>

            </div>

            {{-- DETAIL INFORMASI --}}
            <div class="mt-4 text-sm">

                <p class="font-bold mb-3 text-gray-700">
                    Detail Informasi Posko
                </p>

                {{-- JENIS POSKO --}}
                <div class="mb-3">
                    <p class="font-medium text-gray-800 mb-2">
                        Jenis Posko *
                    </p>

                    <ul class="space-y-2">
                        @foreach ($poskoSummary['jenis'] as $jenis => $jumlah)
                            <li class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                                <span>{{ $jenis }} – {{ $jumlah }} Posko</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <hr class="my-3">

                {{-- WILAYAH TERBANYAK --}}
                <div class="mb-3">
                    <p class="font-medium text-gray-800 mb-2">
                        Wilayah dengan Posko Terbanyak *
                    </p>
                    <p class="text-gray-800">
                        {{ $poskoSummary['wilayah_terbanyak']['nama'] ?? '-' }}
                        <span class="text-gray-600 font-normal">
                            ({{ $poskoSummary['wilayah_terbanyak']['total'] ?? 0 }} Posko)
                        </span>
                    </p>
                </div>

                <hr class="my-3">

                {{-- UPDATE --}}
                <div class="text-right text-gray-600">
                    <p class="text-xs text-gray-500">
                        Terakhir Update :
                        {{ optional($poskoSummary['last_update'])->format('d M Y H:i') }}
                    </p>
                </div>

            </div>
        </div>

    </div>

    @push('scripts')
        <script src="{{ asset('js/select-region.js') }}"></script>
    @endpush
</div>
