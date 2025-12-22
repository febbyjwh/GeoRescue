document.addEventListener('DOMContentLoaded', () => {

    const selector = document.querySelector('select[name="jenis_data"]');
    if (!selector) return;

    selector.addEventListener('change', () => {

        const map = MapState.map;

        Object.values(MapState.layers).forEach(l => map.removeLayer(l));
        map.addLayer(MapState.drawnItems);

        switch (selector.value) {
            case 'jalur':
                map.addLayer(MapState.layers.bencana);
                map.addLayer(MapState.layers.jalur);
                break;

            case 'bencana':
                map.addLayer(MapState.layers.bencana);
                break;

            default:
                map.addLayer(MapState.layers.bencana);
                map.addLayer(MapState.layers.jalur);
        }
    });
});