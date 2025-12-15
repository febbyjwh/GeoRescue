<!DOCTYPE html>
<html>
<head>
    <title>Data Posko Darurat</title>
</head>
<body>
    <h1>Data Posko Darurat</h1>

    <a href="{{ route('posko.create') }}">Tambah Posko</a>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Nama Posko</th>
            <th>Jenis Posko</th>
            <th>Alamat</th>
            <th>Desa</th>
            <th>Kecamatan</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        @if(!empty($poskos) && count($poskos) > 0)
            @foreach($poskos as $posko)
            <tr>
                <td>{{ $posko->nama_posko }}</td>
                <td>{{ $posko->jenis_posko }}</td>
                <td>{{ $posko->alamat_posko }}</td>
                <td>{{ $posko->nama_desa }}</td>
                <td>{{ $posko->kecamatan }}</td>
                <td>{{ $posko->latitude }}</td>
                <td>{{ $posko->longitude }}</td>
                <td>{{ $posko->status_posko }}</td>
                <td>
                    <a href="{{ route('posko.edit', $posko->id) }}">Edit</a>
                    <form action="{{ route('posko.destroy', $posko->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Yakin ingin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        @else
            <tr>
                <td colspan="9" style="text-align:center;">Belum ada data posko</td>
            </tr>
        @endif
    </table>
</body>
</html>
