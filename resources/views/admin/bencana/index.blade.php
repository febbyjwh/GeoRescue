@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <x-common.page-breadcrumb pageTitle="Data Titik Bencana" class="z-10 relative" />

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            <x-tables.basic-tables.basic-tables-one>
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Nama Bencana</th>
                        <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Tingkat Kerawanan</th>
                        <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Desa</th>
                        <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Kecamatan</th>
                        <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Latitude</th>
                        <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Longitude</th>
                        <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($bencanas as $bencana)
                        <tr class="border-b border-gray-100">
                            <td class="px-5 py-4 text-sm">
                                {{ $bencana->nama_bencana }}
                            </td>

                            <td class="px-5 py-4 text-sm">
                                <span class="inline-block rounded-full px-2 py-0.5 text-xs
                                    {{ $bencana->tingkat_kerawanan == 'Tinggi' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ $bencana->tingkat_kerawanan == 'Sedang' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $bencana->tingkat_kerawanan == 'Rendah' ? 'bg-green-100 text-green-700' : '' }}">
                                    {{ $bencana->tingkat_kerawanan }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-sm">
                                {{ $bencana->desa->nama_desa ?? '-' }}
                            </td>

                            <td class="px-5 py-4 text-sm">
                                {{ $bencana->kecamatan->nama_kecamatan ?? '-' }}
                            </td>

                            <td class="px-5 py-4 text-sm">
                                {{ $bencana->lat }}
                            </td>

                            <td class="px-5 py-4 text-sm">
                                {{ $bencana->lang }}
                            </td>
{{-- 
                            <td class="px-5 py-4 text-sm whitespace-nowrap">
                                <a href="{{ route('bencana.edit', $bencana->id) }}"
                                   class="text-yellow-600 hover:underline">
                                    Edit
                                </a>

                                <form action="{{ route('bencana.destroy', $bencana->id) }}"
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline ml-2"
                                        onclick="return confirm('Yakin hapus data bencana?')">
                                        Hapus
                                    </button>
                                </form>
                            </td> --}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-4 text-center text-gray-500">
                                Belum ada data bencana
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </x-tables.basic-tables.basic-tables-one>

        </div>
    </div>
</div>
@endsection