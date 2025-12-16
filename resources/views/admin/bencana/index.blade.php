@extends('layouts.app')

@section('content')

<div class="flex h-screen">
    <!-- Sidebar -->
    @include('layouts.sidebar')

    <div class="flex-1 flex flex-col">
        <!-- Navbar/Header -->
        <x-common.page-breadcrumb pageTitle="Tambah Jalur Evakuasi" class="z-10 relative" />

        <!-- Map Container -->
        <div id="map" style="height:500px; margin-bottom:20px;"></div>
    </div>
</div>
@endsection
@include('maps')
