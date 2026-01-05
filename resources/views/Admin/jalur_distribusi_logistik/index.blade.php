@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-3">Data Titik Distribusi Logistik</h1>

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
                </div>
                <div class="card-body">

                    <x-tables.basic-tables.basic-tables-one>
                        <thead>
                        <tr class="border-b border-gray-200">
                            <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Nama Jalur</th>
                            <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Asal Logistik</th>
                            <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Asal Latitude</th>
                            <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Asal Longitude</th>
                            <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Tujuan Distribusi</th>
                            <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Tujuan Latitude</th>
                            <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Tujuan Longitude</th>
                            <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Status</th>
                            <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Aksi</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($jalur as $item)
                            <tr class="border-b border-gray-100">
                                <td class="px-5 py-4 text-sm">{{ $item->nama_jalur }}</td>
                                <td class="px-5 py-4 text-sm">{{ $item->asal_logistik }}</td>
                                <td class="px-5 py-4 text-sm">{{ $item->asal_latitude }}</td>
                                <td class="px-5 py-4 text-sm">{{ $item->asal_longitude }}</td>
                                <td class="px-5 py-4 text-sm">{{ $item->tujuan_distribusi }}</td>
                                <td class="px-5 py-4 text-sm">{{ $item->tujuan_latitude }}</td>
                                <td class="px-5 py-4 text-sm">{{ $item->tujuan_longitude }}</td>
                                <td class="px-5 py-4 text-sm">
                                    <span class="inline-block rounded-full px-2 py-0.5 text-xs
                                        {{ $item->status_jalur == 'aktif'
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-gray-200 text-gray-700' }}">
                                        {{ $item->status_jalur }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-sm whitespace-nowrap">
                                    <a href="{{ route('jalur_distribusi_logistik.edit', $item->id) }}" class="text-yellow-600 hover:underline">Edit</a>
                                    <form action="{{ route('jalur_distribusi_logistik.destroy', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline ml-2" onclick="return confirm('Yakin hapus?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-5 py-4 text-center text-gray-500">
                                    Belum ada data jalur distribusi logistik
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
