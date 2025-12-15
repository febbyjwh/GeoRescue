<!DOCTYPE html>
<html>
<head>
    <title>Edit Posko</title>
</head>
<body>
    <h1>Edit Posko</h1>

    @if ($errors->any())
        <div style="color:red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('posko.update', $posko->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label>Nama Posko:</label>
            <input type="text" name="nama_posko" value="{{ old('nama_posko', $posko->nama_posko) }}">
        </div>
        <div>
            <label>Jenis Posko:</label>
            <input type="text" name="jenis_posko" value="{{ old('jenis_posko', $posko->jenis_posko) }}">
        </div>
        <div>
            <label>Alamat Posko:</label>
            <textarea name="alamat_posko">{{ old('alamat_posko', $posko->alamat_posko) }}</textarea>
        </div>
        <div>
            <label>Nama Desa:</label>
            <input type="text" name="nama_desa" value="{{ old('nama_desa', $posko->nama_desa) }}">
        </div>
        <div>
            <label>Kecamatan:</label>
            <input type="text" name="kecamatan" value="{{ old('kecamatan', $posko->kecamatan) }}">
        </div>
        <div>
            <label>Latitude:</label>
            <input type="text" name="latitude" value="{{ old('latitude', $posko->latitude) }}">
        </div>
        <div>
            <label>Longitude:</label>
            <input type="text" name="longitude" value="{{ old('longitude', $posko->longitude) }}">
        </div>
        <div>
            <label>Status Posko:</label>
            <select name="status_posko">
                <option value="Aktif" {{ old('status_posko', $posko->status_posko)=='Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Penuh" {{ old('status_posko', $posko->status_posko)=='Penuh' ? 'selected' : '' }}>Penuh</option>
                <option value="Tutup" {{ old('status_posko', $posko->status_posko)=='Tutup' ? 'selected' : '' }}>Tutup</option>
            </select>
        </div>

        <button type="submit">Update</button>
    </form>
</body>
</html>
    