document.addEventListener("DOMContentLoaded", () => {
    const selector = document.querySelector('select[name="jenis_data"]');
    if (!selector) return;

    selector.addEventListener("change", () => {
        const map = MapState.map;

        Object.values(MapState.layers).forEach((l) => map.removeLayer(l));
        map.addLayer(MapState.drawnItems);

        switch (selector.value) {
            case "bencana":
                map.addLayer(MapState.layers.kabBandung);
                map.addLayer(MapState.layers.bencana);
                break;

            case "posko":
                map.addLayer(MapState.layers.kabBandung);
                map.addLayer(MapState.layers.bencana);
                map.addLayer(MapState.layers.posko);
                break;

            case "fasilitas":
                map.addLayer(MapState.layers.kabBandung);
                map.addLayer(MapState.layers.bencana);
                map.addLayer(MapState.layers.fasilitas);
                break;

            case "logistik":
                map.addLayer(MapState.layers.kabBandung);
                map.addLayer(MapState.layers.logistik);
                break;

            default:
                map.addLayer(MapState.layers.kabBandung);
                map.addLayer(MapState.layers.bencana);
                map.addLayer(MapState.layers.posko);
                map.addLayer(MapState.layers.logistik);
                map.addLayer(MapState.layers.fasilitas);
                map.addLayer(MapState.layers.logistik);
        }
    });
});
