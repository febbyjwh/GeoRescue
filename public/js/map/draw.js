document.addEventListener('DOMContentLoaded', () => {

    const { map, drawnItems } = MapState;

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

    function updateGeojson() {
        const input = document.getElementById('geojsonInput');
        if (input) input.value = JSON.stringify(drawnItems.toGeoJSON());
    }

    map.on(L.Draw.Event.CREATED, e => {
        drawnItems.clearLayers();
        drawnItems.addLayer(e.layer);
        updateGeojson();
    });

    map.on(L.Draw.Event.EDITED, updateGeojson);
    map.on(L.Draw.Event.DELETED, updateGeojson);
});
