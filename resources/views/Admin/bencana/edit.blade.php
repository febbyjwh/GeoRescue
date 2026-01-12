<!-- Tombol Edit (contoh di table/index) -->
<button type="button"
    onclick="showEditBencanaModal({{ $bencana->id }}, '{{ $bencana->jenis_bencana }}', '{{ $bencana->district_id }}', '{{ $bencana->village_id }}', '{{ $bencana->tingkat_kerawanan }}', '{{ $bencana->status }}', '{{ $bencana->lang }}', '{{ $bencana->lat }}')"
    class="text-blue-600 hover:underline ml-2 cursor-pointer">
    Edit
</button>

<!-- Modal Edit Bencana -->
<div id="editBencanaModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/30 backdrop-blur-sm transition-opacity duration-300"></div>

    <!-- Konten Modal -->
    <div class="relative bg-white w-full max-w-lg mx-4 rounded-2xl shadow-xl transform transition-all duration-300 scale-95 overflow-hidden">
        <div class="flex flex-col p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Edit Data Bencana</h2>
            <p class="text-sm text-gray-500 text-center mb-6">
                Ubah data bencana sesuai kebutuhan.
            </p>

            <form id="formEditBencana" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="bencana_id" id="edit_bencana_id">

                <!-- Jenis Bencana -->
                <x-form.form-elements.select-inputs label="Jenis bencana" name="jenis_bencana" id="edit_jenis_bencana" required>
                    <option value="">Pilih Jenis Bencana</option>
                    <option value="banjir">Banjir</option>
                    <option value="gempa">Gempa</option>
                    <option value="longsor">Longsor</option>
                </x-form.form-elements.select-inputs>

                <!-- Kecamatan & Desa -->
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Kecamatan</label>
                    <select id="edit_bencana_district" name="district_id" class="w-full" style="width: 100%"></select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Desa</label>
                    <select id="edit_bencana_village" name="village_id" class="w-full" style="width: 100%"></select>
                </div>

                <!-- Tingkat Kerawanan -->
                <x-form.form-elements.select-inputs label="Tingkat Kerawanan" name="tingkat_kerawanan" id="edit_tingkat_kerawanan" required>
                    <option value="">Pilih Tingkat Kerawanan</option>
                    <option value="Tinggi">Tinggi</option>
                    <option value="Sedang">Sedang</option>
                    <option value="Rendah">Rendah</option>
                </x-form.form-elements.select-inputs>

                <!-- Status -->
                <x-form.form-elements.select-inputs label="Status" name="status" id="edit_status" required>
                    <option value="">Status</option>
                    <option value="Aktif">Aktif</option>
                    <option value="Penanganan">Penanganan</option>
                    <option value="Selesai">Selesai</option>
                </x-form.form-elements.select-inputs>

                <!-- Longitude & Latitude -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <x-form.form-elements.default-inputs label="Longitude" name="lang" id="edit_lang" placeholder="106.816666" />
                    <x-form.form-elements.default-inputs label="Latitude" name="lat" id="edit_lat" placeholder="-6.200000" />
                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-end mt-6 space-x-3">
                    <button type="button" onclick="closeEditBencanaModal()"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-gray-700 text-sm font-medium cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white text-sm font-semibold cursor-pointer">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script Modal Edit -->
<script>
function showEditBencanaModal(id, jenis, districtId, villageId, kerawanan, status, lang, lat) {
    // Isi form
    document.getElementById('formEditBencana').action = `/bencana/${id}`; // endpoint update
    document.getElementById('edit_bencana_id').value = id;
    document.getElementById('edit_jenis_bencana').value = jenis;
    document.getElementById('edit_bencana_district').value = districtId;
    document.getElementById('edit_bencana_village').value = villageId;
    document.getElementById('edit_tingkat_kerawanan').value = kerawanan;
    document.getElementById('edit_status').value = status;
    document.getElementById('edit_lang').value = lang;
    document.getElementById('edit_lat').value = lat;

    // Tampilkan modal
    document.getElementById('editBencanaModal').classList.remove('hidden');
}

function closeEditBencanaModal() {
    document.getElementById('editBencanaModal').classList.add('hidden');
}
</script>
