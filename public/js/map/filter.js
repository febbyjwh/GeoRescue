document.addEventListener("DOMContentLoaded", () => {
    const selector = document.querySelector('select[name="jenis_data"]');
    if (!selector) return;

    function showOnlyLayers(layerNames = []) {
        Object.entries(MapState.layers).forEach(([name, layer]) => {
            if (!layer) return;

            if (layerNames.includes(name)) {
                if (!MapState.map.hasLayer(layer)) {
                    MapState.map.addLayer(layer);
                }
            } else {
                if (MapState.map.hasLayer(layer)) {
                    MapState.map.removeLayer(layer);
                }
            }
        });
    }

    selector.addEventListener("change", () => {
        console.log("Filter changed:", selector.value);

        switch (selector.value) {
            case "bencana":
                showOnlyLayers(["kabBandung", "bencana", "bencanaInput"]);
                setActiveModule("bencana");
                break;

            case "posko":
                showOnlyLayers(["kabBandung", "posko", "poskoInput"]);
                setActiveModule("posko");
                break;

            case "fasilitas":
                showOnlyLayers(["kabBandung", "fasilitas", "fasilitasInput"]);
                setActiveModule("fasilitas");
                break;

            case "logistik":
                showOnlyLayers(["kabBandung", "logistik", "logistikInput"]);
                setActiveModule("logistik");
                break;

            default:
                showOnlyLayers([
                    "kabBandung",
                    "bencana",
                    "posko",
                    "fasilitas",
                    "logistik",
                ]);
                setActiveModule(null);
        }
    });
});
