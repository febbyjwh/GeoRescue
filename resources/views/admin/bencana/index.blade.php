@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <x-common.page-breadcrumb pageTitle="Data Titik Bencana" class="z-10 relative" />

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
                        <th class="px-5 py-3 text-sm">No</th>
                        <th class="px-5 py-3 text-sm">Jenis Bencana</th>
                        <th class="px-5 py-3 text-sm">Tingkat Kerawanan</th>
                        <th class="px-5 py-3 text-sm">Status</th>
                        <th class="px-5 py-3 text-sm">Radius Dampak</th>
                        <th class="px-5 py-3 text-sm">Desa</th>
                        <th class="px-5 py-3 text-sm">Kecamatan</th>
                        <th class="px-5 py-3 text-sm">Latitude</th>
                        <th class="px-5 py-3 text-sm">Longitude</th>
                        <th class="px-5 py-3 text-sm">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($bencanas as $bencana)
                        <tr class="border-b border-gray-100">

                            {{-- No --}}
                            <td class="px-5 py-4 text-sm">
                                {{ $bencanas->firstItem() + $loop->index }}
                            </td>

                            {{-- Jenis --}}
                            <td class="px-5 py-4 text-sm font-medium">
                                {{ ucfirst($bencana->jenis_bencana) }}
                            </td>

                            {{-- Kerawanan --}}
                            <td class="px-5 py-4 text-sm">
                                {{ ucfirst($bencana->tingkat_kerawanan) }}
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4 text-sm">
                                {{ ucfirst($bencana->status) }}
                            </td>

                            {{-- Radius Dampak --}}
                            <td class="px-5 py-4 text-sm font-semibold">
                                {{ $bencana->nilai ?? '-' }}
                                <span class="text-gray-500 text-xs">
                                    {{ $bencana->satuan }}
                                </span>
                            </td>

                            {{-- Desa --}}
                            <td class="px-5 py-4 text-sm">
                                {{ $bencana->village->name ?? '-' }}
                            </td>

                            {{-- Kecamatan --}}
                            <td class="px-5 py-4 text-sm">
                                {{ $bencana->district->name ?? '-' }}
                            </td>

                            {{-- Latitude --}}
                            <td class="px-5 py-4 text-sm">
                                {{ $bencana->lat }}
                            </td>

                            {{-- Longitude --}}
                            <td class="px-5 py-4 text-sm">
                                {{ $bencana->lang }}
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-4 text-sm whitespace-nowrap">
                                <a href="{{ route('bencana.edit', $bencana->id) }}"
                                    class="text-yellow-600 hover:underline">
                                    Edit
                                </a>

                                <button type="button"
                                    onclick="showDeleteBencanaModal('{{ route('bencana.destroy', $bencana->id) }}')"
                                    class="text-red-600 hover:underline ml-2">
                                    Hapus
                                </button>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-5 py-4 text-center text-gray-500">
                                Belum ada data bencana
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </x-tables.basic-tables.basic-tables-one>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $bencanas->links() }}
            </div>

        </div>
    </div>
</div>

<script>
    function showDeleteBencanaModal(url) {
        document.getElementById('deleteBencanaForm').action = url;
        document.getElementById('deleteBencanaModal').classList.remove('hidden');
    }

    function closeDeleteBencanaModal() {
        document.getElementById('deleteBencanaModal').classList.add('hidden');
    }
</script>
@endsection