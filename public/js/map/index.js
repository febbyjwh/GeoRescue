document.addEventListener("DOMContentLoaded", async () => {
    // ===============================
    // MAP STATE (GLOBAL)
    // ===============================
    if (!window.MapState) window.MapState = {};

    MapState.activeModule = null;
    MapState.layers = {};

    window.setActiveModule = function (module) {
        MapState.activeModule = module;
        console.log("Active module set to:", module);
    };

    // ===============================
    // INIT MAP
    // ===============================
    MapState.map = L.map("map").setView([-6.9756, 107.615], 12);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap",
    }).addTo(MapState.map);

    // ===============================
    // LAYERS (SEMUA DISINI, 1x SAJA)
    // ===============================
    MapState.layers.kabBandung = L.layerGroup().addTo(MapState.map);

    MapState.layers.bencana = L.layerGroup().addTo(MapState.map);
    MapState.layers.bencanaInput = L.layerGroup().addTo(MapState.map);

    MapState.layers.posko = L.layerGroup().addTo(MapState.map);
    MapState.layers.poskoInput = L.layerGroup().addTo(MapState.map);

    MapState.layers.fasilitas = L.layerGroup().addTo(MapState.map);
    MapState.layers.fasilitasInput = L.layerGroup().addTo(MapState.map);

    MapState.layers.logistik = L.layerGroup().addTo(MapState.map);
    MapState.layers.logistikInput = L.layerGroup().addTo(MapState.map);

    // ===============================
    // GEOJSON KAB BANDUNG
    // ===============================
    try {
        const res = await fetch("/js/geojson/kab-bandung.geojson");
        const geojsonData = await res.json();

        const kabPolygon = L.geoJSON(geojsonData, {
            style: {
                color: "#947519ff",
                weight: 2,
                fillColor: "#FFCA28",
                fillOpacity: 0.2,
            },
        }).addTo(MapState.layers.kabBandung);

        MapState.map.fitBounds(kabPolygon.getBounds());
    } catch (err) {
        console.error("Gagal load geojson Kab Bandung:", err);
    }

    // ===============================
    // SATU PINTU MAP CLICK
    // ===============================
    MapState.map.on("click", (e) => {
        console.log("Map clicked:", e.latlng);
        console.log("Active module:", MapState.activeModule);

        switch (MapState.activeModule) {
            case "bencana":
                window.handleBencanaClick?.(e);
                break;

            case "posko":
                window.handlePoskoClick?.(e);
                break;

            case "fasilitas":
                window.handleFasilitasClick?.(e);
                break;

            case "logistik":
                window.handleLogistikClick?.(e);
                break;

            default:
                console.warn("Tidak ada module aktif");
        }
    });
});
