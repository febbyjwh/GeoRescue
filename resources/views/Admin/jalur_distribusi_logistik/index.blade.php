@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <x-common.page-breadcrumb pageTitle="Data Logistik" class="z-10 relative" />

        @if (session('success'))
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
                            <th class="px-5 py-3 text-sm font-medium text-gray-500">ID</th>
                            <th class="px-5 py-3 text-sm font-medium text-gray-500">Nama Lokasi</th>
                            <th class="px-5 py-3 text-sm font-medium text-gray-500">Kecamatan</th>
                            <th class="px-5 py-3 text-sm font-medium text-gray-500">Desa</th>
                            <th class="px-5 py-3 text-sm font-medium text-gray-500">Jenis Logistik</th>
                            <th class="px-5 py-3 text-sm font-medium text-gray-500">Jumlah</th>
                            <th class="px-5 py-3 text-sm font-medium text-gray-500">Satuan</th>
                            <th class="px-5 py-3 text-sm font-medium text-gray-500">Latitude</th>
                            <th class="px-5 py-3 text-sm font-medium text-gray-500">Longitude</th>
                            <th class="px-5 py-3 text-sm font-medium text-gray-500">Status</th>
                            <th class="px-5 py-3 text-sm font-medium text-gray-500">Dibuat</th>
                            <th class="px-5 py-3 text-sm font-medium text-gray-500">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($logistiks as $item)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-5 py-4 text-sm">{{ $item->id }}</td>
                                <td class="px-5 py-4 text-sm">{{ $item->nama_lokasi }}</td>
                                <td class="px-5 py-4 text-sm">{{ $item->district->name ?? $item->district_id }}</td>
                                <td class="px-5 py-4 text-sm">{{ $item->village->name ?? $item->village_id }}</td>
                                <td class="px-5 py-4 text-sm">{{ $item->jenis_logistik }}</td>
                                <td class="px-5 py-4 text-sm">{{ $item->jumlah }}</td>
                                <td class="px-5 py-4 text-sm">{{ $item->satuan }}</td>
                                <td class="px-5 py-4 text-sm">{{ $item->lat }}</td>
                                <td class="px-5 py-4 text-sm">{{ $item->lng }}</td>

                                <td class="px-5 py-4 text-sm">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ strtolower($item->status) === 'tersedia'
                                        ? 'bg-green-100 text-green-700'
                                        : (strtolower($item->status) === 'menipis'
                                            ? 'bg-yellow-100 text-yellow-700'
                                            : 'bg-red-100 text-red-700') }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-sm">
                                    {{ optional($item->created_at)->format('Y-m-d H:i') }}
                                </td>

                                <td class="px-5 py-4 text-sm whitespace-nowrap">
                                    <a href="{{ route('jalur_distribusi_logistik.edit', $item->id) }}"
                                        class="text-yellow-600 hover:underline">
                                        Edit
                                    </a>

                                    <form action="{{ route('jalur_distribusi_logistik.destroy', $item->id) }}"
                                        method="POST" class="inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ml-2 text-red-600 hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="px-5 py-4 text-center text-gray-500">
                                    Belum ada data logistik
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </x-tables.basic-tables.basic-tables-one>
                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $logistiks->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
