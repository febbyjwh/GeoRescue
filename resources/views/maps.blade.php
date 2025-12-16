@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<style>
    #map {
        width: 100%;
        height: 100px;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const mapEl = document.getElementById('map');
    if (!mapEl) return; // ⛔ MAP TIDAK ADA → STOP TOTAL

    const map = L.map(mapEl);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const markers = L.featureGroup().addTo(map);

    const bencanaColors = {
        "Banjir": "#1f77b4",
        "Longsor": "#ff7f0e",
        "Gempa": "#2ca02c"
    };

    fetch('/data/bencana.geojson')
        .then(res => res.json())
        .then(data => {
            L.geoJSON(data, {
                pointToLayer: function (feature, latlng) {
                    const jenis = feature.properties.Jenis_Bencana;
                    const color = bencanaColors[jenis] || "#888888";

                    return L.circleMarker(latlng, {
                        radius: 8,
                        fillColor: color,
                        color: '#000',
                        weight: 1,
                        fillOpacity: 0.8
                    });
                },
                onEachFeature: function (feature, layer) {
                    const props = feature.properties;
                    layer.bindPopup(
                        `<b>${props.Desa}</b><br>
                         ${props.Jenis_Bencana}, ${props.Tingkat_Kerawanan}`
                    );
                    markers.addLayer(layer);
                }
            });

            map.fitBounds(markers.getBounds(), { padding: [50, 50] });

            setTimeout(() => map.invalidateSize(), 200);
        })
        .catch(console.error);
});
</script>
@endpush
