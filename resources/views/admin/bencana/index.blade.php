@extends('layouts.app')

@section('content')
<div class="flex h-screen">
    <!-- Sidebar -->
    @include('layouts.sidebar')

    <div class="flex-1 flex flex-col">
        <!-- Navbar/Header -->
        <x-common.page-breadcrumb pageTitle="Peta Titik Bencana Kabupaten" class="z-10 relative" />

        <!-- Map Container -->
        <div id="map" class="flex-1"></div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('map');

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var markers = L.featureGroup().addTo(map);

    var bencanaColors = {
        "Banjir": "#1f77b4",
        "Longsor": "#ff7f0e",
        "Gempa": "#2ca02c"
    };

    fetch('/data/bencana.geojson')
        .then(res => res.json())
        .then(data => {
            L.geoJSON(data, {
                pointToLayer: function(feature, latlng) {
                    var jenis = feature.properties.Jenis_Bencana;
                    var color = bencanaColors[jenis] || "#888888";
                    return L.circleMarker([latlng.lat, latlng.lng], {
                        radius: 8,
                        fillColor: color,
                        color: '#000',
                        weight: 1,
                        fillOpacity: 0.8
                    });
                },
                onEachFeature: function(feature, layer) {
                    var props = feature.properties;
                    layer.bindPopup("<b>" + props.Desa + "</b><br>" +
                        props.Jenis_Bencana + ", " + props.Tingkat_Kerawanan);
                    markers.addLayer(layer);
                }
            });
            map.fitBounds(markers.getBounds(), {padding: [50, 50]});
        })
        .catch(err => console.error(err));
});
</script>
@endpush
