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
<<<<<<< HEAD
    layers.posko.addTo(MapState.map);
=======
    layers.logistik.addTo(MapState.map);
>>>>>>> 5997120986c69162a38bfaf07929ea1da487fc4f
    drawnItems.addTo(MapState.map);
});
