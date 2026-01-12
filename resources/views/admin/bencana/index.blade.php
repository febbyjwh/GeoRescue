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
                            <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">No</th>
                            <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Jenis Bencana</th>
                            <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Tingkat Kerawanan</th>
                            <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Status</th>
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
                                {{-- Nomor urut berdasarkan halaman --}}
                                <td class="px-5 py-4 text-sm">
                                    {{ $bencanas->firstItem() + $loop->index }}
                                </td>

                                <td class="px-5 py-4 text-sm">{{ $bencana->jenis_bencana }}</td>
                                <td class="px-5 py-4 text-sm">{{ $bencana->tingkat_kerawanan }}</td>
                                <td class="px-5 py-4 text-sm">{{ $bencana->status }}</td>
                                <td class="px-5 py-4 text-sm">{{ $bencana->village->name ?? '-' }}</td>
                                <td class="px-5 py-4 text-sm">{{ $bencana->district->name ?? '-' }}</td>
                                <td class="px-5 py-4 text-sm">{{ $bencana->lat }}</td>
                                <td class="px-5 py-4 text-sm">{{ $bencana->lang }}</td>

                                <td class="px-5 py-4 text-sm whitespace-nowrap">
                                    <a href="{{ route('bencana.edit', $bencana->id) }}"
                                        class="text-yellow-600 hover:underline">Edit</a>

                                    <!-- Tombol Hapus -->
                                    <button type="button"
                                        onclick="showDeleteBencanaModal('{{ route('bencana.destroy', $bencana->id) }}')"
                                        class="text-red-600 hover:underline ml-2 cursor-pointer">
                                        Hapus
                                    </button>

                                    <!-- Modal Hapus Bencana -->
                                    <div id="deleteBencanaModal"
                                        class="hidden fixed inset-0 z-50 flex items-center justify-center">
                                        <!-- Overlay -->
                                        <div
                                            class="absolute inset-0 bg-black/30 backdrop-blur-sm transition-opacity duration-300">
                                        </div>

                                        <!-- Konten Modal -->
                                        <div
                                            class="relative bg-white w-full max-w-sm mx-4 rounded-2xl shadow-xl transform transition-all duration-300 scale-95">
                                            <div class="flex flex-col items-center p-6">
                                                <!-- Icon Warning -->
                                                <div
                                                    class="flex items-center justify-center w-16 h-16 rounded-full bg-red-100 mb-4">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.664 1.732-3L13.732 4a2 2 0 00-3.464 0L4.34 16c-.77 1.336.192 3 1.732 3z" />
                                                    </svg>
                                                </div>

                                                <!-- Judul & Pesan -->
                                                <h2 class="text-lg font-semibold text-gray-700 mb-2">Hapus Data Bencana?
                                                </h2>
                                                <p class="text-sm text-gray-500 text-center mb-6">
                                                Apakah Anda yakin ingin menghapus
                                                    data ini?
                                                </p>

                                                <!-- Tombol Aksi -->
                                                <div class="flex space-x-3">
                                                    <button type="button" onclick="closeDeleteBencanaModal()"
                                                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-gray-700 text-sm font-medium cursor-pointer">
                                                        Batal
                                                    </button>
                                                    <form id="deleteBencanaForm" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="px-5 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-white text-sm font-semibold cursor-pointer">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-5 py-4 text-center text-gray-500">
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
