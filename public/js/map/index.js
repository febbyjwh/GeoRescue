// public/js/map/index.js
document.addEventListener('DOMContentLoaded', () => {

    const { map, layers, drawnItems } = window.MapState;

    MapState.map = L.map('map').setView([-6.9756, 107.615], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(MapState.map);

    // default layers
    layers.bencana.addTo(MapState.map);
    layers.jalur.addTo(MapState.map);
    layers.fasilitas.addTo(MapState.map);
    layers.logistik.addTo(MapState.map);
    drawnItems.addTo(MapState.map);
});
