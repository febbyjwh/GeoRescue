@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
<style>
    #map {
        width: 100%;
        height: 100vh;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    /* ================= MAP ================= */
    const map = L.map('map').setView([-6.9756, 107.615], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    /* ================= LAYERS ================= */
    const layers = {
        bencana: L.featureGroup().addTo(map),
        jalur: L.featureGroup().addTo(map),
        posko: L.featureGroup(),
        fasilitas: L.featureGroup(),
        logistik: L.featureGroup()
    };

    /* ================= DRAW ================= */
    const drawnItems = new L.FeatureGroup().addTo(map);

    const drawControl = new L.Control.Draw({
        edit: { featureGroup: drawnItems },
        draw: {
            polyline: true,
            polygon: false,
            rectangle: false,
            circle: false,
            marker: false,
            circlemarker: false
        }
    });
    map.addControl(drawControl);

    function updateGeojsonInput() {
        const input = document.getElementById('geojsonInput');
        if (input) input.value = JSON.stringify(drawnItems.toGeoJSON());
    }

    map.on(L.Draw.Event.CREATED, e => {
        drawnItems.clearLayers();
        drawnItems.addLayer(e.layer);
        updateGeojsonInput();
    });

    map.on(L.Draw.Event.EDITED, updateGeojsonInput);
    map.on(L.Draw.Event.DELETED, updateGeojsonInput);

    /* ================= BENCANA ================= */
    fetch('/data/bencana.geojson')
        .then(r => r.json())
        .then(data => {
            L.geoJSON(data, {
                pointToLayer: (_, latlng) =>
                    L.circleMarker(latlng, {
                        radius: 8,
                        fillColor: '#2563eb',
                        color: '#000',
                        weight: 1,
                        fillOpacity: 0.8
                    }),
                onEachFeature: (f, layer) => {
                    layer.bindPopup(`<b>${f.properties.Jenis_Bencana}</b>`);
                    layers.bencana.addLayer(layer);
                }
            });
        });

    /* ================= JALUR ================= */
    fetch('/jalur_evakuasi/geojson/jalur-evakuasi')
        .then(r => r.json())
        .then(data => {
            L.geoJSON(data, {
                style: { color: '#dc2626', weight: 5 },
                onEachFeature: (f, layer) => {

                    layer.bindPopup(`
                        <b>${f.properties.Nama}</b><br>
                        ${f.properties.Deskripsi ?? ''}
                    `);

                    layer.on('click', () => openEditMode(f));
                    layers.jalur.addLayer(layer);
                }
            });
        });

    /* ================= EDIT MODE ================= */
    function openEditMode(feature) {

        // tampilkan form jalur
        document.querySelector('[data-form="jalur"]').classList.remove('hidden');

        // isi form
        document.getElementById('nama_jalur').value = feature.properties.Nama ?? '';
        document.getElementById('deskripsi').value = feature.properties.Deskripsi ?? '';

        const form = document.getElementById('jalurForm');
        form.action = `/jalur_evakuasi/${feature.properties.id}`;

        let method = document.getElementById('_method');
        if (!method) {
            method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.id = '_method';
            method.value = 'PUT';
            form.appendChild(method);
        }

        // pindahkan geometry ke drawnItems
        drawnItems.clearLayers();

        const editable = L.geoJSON(feature, {
            style: { color: '#eab308', weight: 6 }
        });

        editable.eachLayer(l => drawnItems.addLayer(l));

        updateGeojsonInput();
    }

    /* ================= FILTER ================= */
    const selector = document.querySelector('select[name="jenis_data"]');

    if (selector) {
        selector.addEventListener('change', () => {

            // hapus semua layer
            Object.values(layers).forEach(layer => {
                if (map.hasLayer(layer)) map.removeLayer(layer);
            });

            // drawnItems selalu aktif
            map.addLayer(drawnItems);

            switch (selector.value) {
                case 'jalur':
                    map.addLayer(layers.bencana);
                    map.addLayer(layers.jalur);
                    break;

                case 'bencana':
                    map.addLayer(layers.bencana);
                    break;

                case 'posko':
                    map.addLayer(layers.bencana);
                    map.addLayer(layers.posko);
                    break;

                case 'fasilitas':
                    map.addLayer(layers.bencana);
                    map.addLayer(layers.fasilitas);
                    break;

                case 'logistik':
                    map.addLayer(layers.bencana);
                    map.addLayer(layers.logistik);
                    break;

                default:
                    map.addLayer(layers.bencana);
                    map.addLayer(layers.jalur);
            }
        });
    }

    /* ================= AUTO EDIT DARI INDEX ================= */
    if (window.EDIT_JALUR_ID) {
        fetch(`/jalur_evakuasi/${window.EDIT_JALUR_ID}/geojson`)
            .then(r => r.json())
            .then(feature => {
                openEditMode(feature);

                const temp = L.geoJSON(feature);
                map.fitBounds(temp.getBounds(), { padding: [50, 50] });
            });
    }

});
</script>

@if(isset($editJalurId))
<script>
    window.EDIT_JALUR_ID = {{ $editJalurId }};
</script>
@endif
@endpush
