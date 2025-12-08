hallo
<!DOCTYPE html>
<html>
<head>
    <title>GeoRescue Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        #map { height: 600px; width: 100%; }
    </style>
</head>
<body>
    <div id="map"></div>

    <script>
        var map = L.map('map').setView([-6.9, 107.6], 12); // pusat Bandung
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        fetch('/api/bencana')
        .then(res => res.json())
        .then(data => {
            data.forEach(function(b) {
                L.marker([b.lat, b.lng])
                    .addTo(map)
                    .bindPopup(`<b>${b.nama}</b><br>${b.jenis}`);
            });
        });
    </script>
</body>
</html>
