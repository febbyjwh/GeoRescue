@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-3">Data Posko Darurat</h1>

                @if (session('success'))
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
                                    <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Nama Posko</th>
                                    <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Jenis Posko</th>
                                    <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Alamat</th>
                                    <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Desa</th>
                                    <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Kecamatan</th>
                                    <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Latitude</th>
                                    <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Longitude</th>
                                    <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Status</th>
                                    <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($poskos as $posko)
                                    <tr class="border-b border-gray-100">
                                        <td class="px-5 py-4 text-sm">{{ $posko->nama_posko }}</td>
                                        <td class="px-5 py-4 text-sm">{{ $posko->jenis_posko }}</td>
                                        <td class="px-5 py-4 text-sm">{{ $posko->alamat_posko }}</td>
                                        <td class="px-5 py-4 text-sm">{{ $posko->nama_desa }}</td>
                                        <td class="px-5 py-4 text-sm">{{ $posko->kecamatan }}</td>
                                        <td class="px-5 py-4 text-sm">{{ $posko->latitude }}</td>
                                        <td class="px-5 py-4 text-sm">{{ $posko->longitude }}</td>
                                        <td class="px-5 py-4 text-sm">
                                            <span
                                                class="inline-block rounded-full px-2 py-0.5 text-xs
                                    {{ $posko->status_posko == 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }}">
                                                {{ $posko->status_posko }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-sm whitespace-nowrap">
                                            <a href="{{ route('posko.edit', $posko->id) }}"
                                                class="text-yellow-600 hover:underline">
                                                Edit
                                            </a>

                                            <form action="{{ route('posko.destroy', $posko->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-red-600 hover:underline ml-2"
                                                    onclick="return confirm('Yakin hapus data posko?')">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-5 py-4 text-center text-gray-500">
                                            Belum ada data posko
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
