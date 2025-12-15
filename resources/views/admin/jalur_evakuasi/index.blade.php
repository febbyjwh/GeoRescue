@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">Daftar Jalur Evakuasi</h2>

<div id="map" style="height:500px; margin-bottom:20px;"></div>

<ul class="list-disc ml-5">
    @foreach($jalurs as $jalur)
        <li>{{ $jalur->nama_jalur }}</li>
    @endforeach
</ul>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
var map = L.map('map').setView([-6.9, 107.6], 12);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

@foreach($jalurs as $jalur)
    var geo = {!! $jalur->geojson !!};
    L.geoJSON(geo).addTo(map).bindPopup("{{ $jalur->nama_jalur }}");
@endforeach
</script>
@endsection
