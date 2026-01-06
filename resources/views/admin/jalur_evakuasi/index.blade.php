@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Navbar/Header -->
        <x-common.page-breadcrumb pageTitle="Data Jalur Evakuasi" class="z-10 relative" />
        <div class="row mb-4">
            <div class="col-12">
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
                    <div class="card-body">

                        <x-tables.basic-tables.basic-tables-one>
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Nama Jalur</th>
                                    <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Deskripsi</th>
                                    <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Created By</th>
                                    <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Created At</th>
                                    <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Updated At</th>
                                    <th class="px-5 py-3 text-left text-sm font-medium text-gray-500">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($jalurs as $jalur)
                                    <tr class="border-b border-gray-100">
                                        <td class="px-5 py-4 text-sm">{{ $jalur->nama_jalur }}</td>
                                        <td class="px-5 py-4 text-sm">{{ $jalur->deskripsi }}</td>
                                        <td class="px-5 py-4 text-sm">{{ $jalur->created_by ?? '-' }}</td>
                                        <td class="px-5 py-4 text-sm">{{ $jalur->created_at }}</td>
                                        <td class="px-5 py-4 text-sm">{{ $jalur->updated_at }}</td>
                                        <td class="px-5 py-4 text-sm whitespace-nowrap">
                                        <td>
                                            <a href="{{ route('bencana.edit', $bencana->id) }}"
                                                class="text-yellow-600 hover:underline">
                                                Edit
                                            </a>

                                            <!-- Tombol Hapus -->
                                            <form action="{{ route('bencana.destroy', $bencana->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-red-600 hover:underline ml-2"
                                                    onclick="return confirm('Yakin hapus data bencana?')">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-5 py-4 text-center text-gray-500">
                                            Belum ada data jalur evakuasi
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
