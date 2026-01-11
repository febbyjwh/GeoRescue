document.addEventListener('DOMContentLoaded', async () => {

    if (!window.MapState) window.MapState = {};
    if (!MapState.layers) MapState.layers = {};
    // if (!MapState.drawnItems) MapState.drawnItems = L.featureGroup();

    MapState.map = L.map('map').setView([-6.9756, 107.615], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(MapState.map);

    // geojson kab bandung
    if (!MapState.layers.kabBandung) MapState.layers.kabBandung = L.layerGroup();
    try {
        const res = await fetch('/js/geojson/kab-bandung.geojson');
        const geojsonData = await res.json();

        const kabPolygon = L.geoJSON(geojsonData, {
            style: {
                color: '#947519ff',       // border
                weight: 2,
                fillColor: '#FFCA28',     // fill
                fillOpacity: 0.2
            }
        }).addTo(MapState.layers.kabBandung);

        MapState.map.fitBounds(kabPolygon.getBounds());

    } catch (err) {
        console.error('Gagal load geojson Bandung:', err);
    }

    // layer data 
    if (!MapState.layers.bencana) MapState.layers.bencana = L.layerGroup();
    if (!MapState.layers.fasilitas) MapState.layers.fasilitas = L.layerGroup();
    if (!MapState.layers.posko) MapState.layers.posko = L.layerGroup();
    if (!MapState.layers.logistik) MapState.layers.logistik = L.layerGroup();

    // Tambahkan semua layer ke map (polygon sudah duluan di bawah)
    MapState.layers.kabBandung.addTo(MapState.map);
    MapState.layers.bencana.addTo(MapState.map);
    MapState.layers.fasilitas.addTo(MapState.map);
    MapState.layers.posko.addTo(MapState.map);
    MapState.layers.logistik.addTo(MapState.map);
    // MapState.drawnItems.addTo(MapState.map)
});