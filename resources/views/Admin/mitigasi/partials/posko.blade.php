<form id="formPosko">
    <input type="hidden" id="posko_id">
    <input type="text" id="nama_posko">
    <select id="jenis_posko">
        <option value="">--Pilih Jenis--</option>
        <option value="Kesehatan">Kesehatan</option>
        <option value="Evakuasi">Evakuasi</option>
    </select>
    <select id="status_posko">
        <option value="">--Pilih Status--</option>
        <option value="Aktif">Aktif</option>
        <option value="Penuh">Penuh</option>
        <option value="Tutup">Tutup</option>
    </select>
    <select id="kecamatan_id"></select>
    <select id="desa_id"></select>
    <input type="text" id="latitude">
    <input type="text" id="longitude">
    <button type="button" onclick="submitPosko()">Simpan</button>
</form>