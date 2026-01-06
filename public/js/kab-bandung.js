document.addEventListener("DOMContentLoaded", async () => {
    if (!window.MapState || !MapState.map) return;

    // Layer untuk polygon Bandung
    if (!MapState.layers.kabBandung) {
        MapState.layers.kabBandung = L.layerGroup().addTo(MapState.map);
    }
    const bandungLayer = MapState.layers.kabBandung;

    try {
        const res = await fetch('/geojson/kab-bandung.geojson');
        const geojson = await res.json();

        L.geoJSON(geojson, {
            style: {
                color: "#1D4ED8",      // garis biru
                weight: 2,
                fillColor: "#3B82F6", // isi biru transparan
                fillOpacity: 0.3
            }
        }).addTo(bandungLayer);

        // Zoom otomatis ke bounds polygon
        MapState.map.fitBounds(bandungLayer.getBounds());
    } catch (err) {
        console.error("Gagal load polygon Bandung:", err);
    }
});
