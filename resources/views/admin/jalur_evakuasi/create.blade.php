@extends('layouts.app')

@section('content')

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Leaflet Draw</title>

        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
        <x-common.page-breadcrumb pageTitle="Tambah Jalur Evakuasi" />
        <style>
            #map {
                height: 350px;
                width: 100%;
                margin-top: 20px;
            }
        </style>
    </head>

    <body>
        <!-- Hidden input untuk menyimpan GeoJSON -->
        <input type="hidden" id="geojson_input" name="geojson">

        <!-- Div untuk peta -->
        <div id="map"></div>

        <!-- Leaflet JS -->
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

        <script>
            // Inisialisasi peta
            var map = L.map('map').setView([-6.9, 107.6], 12);

            // Tambahkan layer OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // FeatureGroup untuk menyimpan layer yang digambar
            var drawnItems = new L.FeatureGroup();
            map.addLayer(drawnItems);

            // Tambahkan kontrol Draw
            var drawControl = new L.Control.Draw({
                draw: {
                    polygon: false,
                    marker: false,
                    circle: false,
                    rectangle: false
                },
                edit: {
                    featureGroup: drawnItems
                }
            });
            map.addControl(drawControl);

            // Event ketika jalur selesai digambar
            map.on(L.Draw.Event.CREATED, function(e) {
                var layer = e.layer;
                drawnItems.addLayer(layer);

                // Simpan GeoJSON ke hidden input
                var geojson = layer.toGeoJSON();
                document.getElementById('geojson_input').value = JSON.stringify(geojson.geometry);
                console.log("GeoJSON saved:", geojson.geometry);
            });
        </script>

        <div class="w-full min-h-screen p-6 bg-gray-50">
            <x-common.component-card title="Input Jalur Evakuasi">
                <!-- Nama Jalur -->
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Nama Jalur Evakuasi
                    </label>
                    <input type="text" name="nama_jalur" placeholder="Nama Jalur Evakuasi"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        required />
                </div>

                <!-- Hidden GeoJSON -->
                <input type="hidden" name="geojson" id="geojson_input">

                <!-- Hidden User ID -->
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Simpan Jalur</button>
            </x-common.component-card>
        </div>
    </body>
@endsection
