document.addEventListener('DOMContentLoaded', () => {

    fetch('/data/bencana.geojson')
        .then(r => r.json())
        .then(data => {

            // mapping warna per jenis bencana
            const warnaBencana = {
                'Banjir': '#1E40AF',       // biru tua
                'Longsor': '#F59E0B', // merah
                'Gempa': '#10B981',         // hijau
            };

            L.geoJSON(data, {
                pointToLayer: (feature, latlng) => {
                    const jenis = feature.properties.Jenis_Bencana;
                    const warna = warnaBencana[jenis] ?? '#2563eb'; // fallback biru
                    return L.circleMarker(latlng, {
                        radius: 10,
                        color: 'blue',
                        fillColor: warna,
                        fillOpacity: 0.8,
                        color: '#ffffffff',
                        weight: 1
                    });
                },
                onEachFeature: (f, layer) => {
                    layer.bindPopup(f.properties.Jenis_Bencana);
                    MapState.layers.bencana.addLayer(layer);
                }
            });
        });
});
