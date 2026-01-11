@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-3">Data Logistik</h1>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('jalur_distribusi_logistik.create') }}" class="btn btn-primary">
                        + Tambah Logistik
                    </a>
                </div>

                <div class="card-body">
                    <x-tables.basic-tables.basic-tables-one>
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th>ID</th>
                                <th>Nama Lokasi</th>
                                <th>Kecamatan</th>
                                <th>Desa</th>
                                <th>Jenis Logistik</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th>Lat</th>
                                <th>Lang</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                        @forelse($logistiks as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->nama_lokasi }}</td>

                                {{-- relasi district & village --}}
                                <td>{{ $item->district->name ?? $item->district_id }}</td>
                                <td>{{ $item->village->name ?? $item->village_id }}</td>

                                <td>{{ $item->jenis_logistik }}</td>
                                <td>{{ $item->jumlah }}</td>
                                <td>{{ $item->satuan }}</td>

                                <td>{{ $item->lat }}</td>
                                <td>{{ $item->lang }}</td>

                                <td>
                                    <span class="badge
                                        {{ strtolower($item->status) === 'tersedia' ? 'bg-success' : (strtolower($item->status) === 'menipis' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>

                                <td>{{ optional($item->created_at)->format('Y-m-d H:i') }}</td>

                                <td class="d-flex gap-2">
                                    <a href="{{ route('jalur_distribusi_logistik.edit', $item->id) }}"
                                       class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <form action="{{ route('jalur_distribusi_logistik.destroy', $item->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin hapus data ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-muted">
                                    Belum ada data logistik
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </x-tables.basic-tables.basic-tables-one>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
