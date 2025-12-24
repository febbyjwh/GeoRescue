document.addEventListener("DOMContentLoaded", () => {
    if (!window.MapState) {
        console.error("MapState belum diinisialisasi!");
        return;
    }

    const { map, layers } = MapState;

    const formContainer = document.querySelector('[data-form="jalur"]');
    const form = document.getElementById("jalurForm");
    const namaInput = document.getElementById("nama_jalur");
    const deskripsiInput = document.getElementById("deskripsi");
    const geojsonInput = document.getElementById("geojsonInput");

    if (!form || !geojsonInput) return;

    /* =============================
       GLOBAL STATE
    ============================== */
    let routingControl = null;
    let selectedPoints = [];

    /* =============================
       RESET ROUTE
    ============================== */
    function resetRoute() {
        selectedPoints = [];
        geojsonInput.value = "";

        if (routingControl) {
            map.removeControl(routingControl);
            routingControl = null;
        }
    }

    /* =============================
       CREATE ROUTE FROM POINTS
    ============================== */
    function createRoute(points) {
        if (routingControl) map.removeControl(routingControl);

        routingControl = L.Routing.control({
            waypoints: points,
            addWaypoints: true,
            draggableWaypoints: true,
            routeWhileDragging: true,
            show: false,
            createMarker: () => null,
            lineOptions: {
                styles: [
                    {
                        color: "#facc15",
                        weight: 5,
                        opacity: 0.9,
                    },
                ],
            },
        })
            .on("routesfound", (e) => {
                const route = e.routes[0];

                const geojson = {
                    type: "FeatureCollection",
                    features: [
                        {
                            type: "Feature",
                            properties: {
                                distance: route.summary.totalDistance,
                                duration: route.summary.totalTime,
                            },
                            geometry: {
                                type: "LineString",
                                coordinates: route.coordinates.map((c) => [
                                    c.lng,
                                    c.lat,
                                ]),
                            },
                        },
                    ],
                };

                // 🔥 INI YANG DIKIRIM KE DATABASE
                geojsonInput.value = JSON.stringify(geojson);
            })
            .addTo(map);
    }

    /* =============================
       CLICK MAP → PICK 2 POINTS
    ============================== */
    map.on("click", (e) => {
        console.log("map clicked");

        // Klik ke-3 → reset dan mulai ulang
        if (selectedPoints.length === 2) {
            resetRoute();
        }

        selectedPoints.push(e.latlng);

        // Klik ke-2 → buat route
        if (selectedPoints.length === 2) {
            createRoute(selectedPoints);
        }
    });

    /* =============================
       SWITCH TO CREATE MODE
    ============================== */
    function switchToCreate() {
        resetRoute();

        if (namaInput) namaInput.value = "";
        if (deskripsiInput) deskripsiInput.value = "";

        form.action = "/jalur_evakuasi";

        const methodInput = document.getElementById("_method");
        if (methodInput) methodInput.remove();

        if (formContainer) formContainer.classList.remove("hidden");
    }

    /* =============================
       EDIT MODE (CLICK EXISTING ROUTE)
    ============================== */
    function openEditJalur(feature) {
        switchToCreate();

        if (namaInput) namaInput.value = feature.properties.Nama ?? "";
        if (deskripsiInput)
            deskripsiInput.value = feature.properties.Deskripsi ?? "";

        form.action = `/jalur_evakuasi/${feature.properties.id}`;

        if (!document.getElementById("_method")) {
            const m = document.createElement("input");
            m.type = "hidden";
            m.name = "_method";
            m.id = "_method";
            m.value = "PUT";
            form.appendChild(m);
        }

        const coords = feature.geometry.coordinates.map((c) =>
            L.latLng(c[1], c[0])
        );

        selectedPoints = [coords[0], coords[coords.length - 1]];
        createRoute(coords);
    }

    /* =============================
       LOAD EXISTING ROUTES
    ============================== */
    fetch("/jalur_evakuasi/geojson/jalur-evakuasi")
        .then((res) => res.json())
        .then((data) => {
            L.geoJSON(data, {
                style: {
                    color: "#dc2626",
                    weight: 5,
                },
                onEachFeature: (feature, layer) => {
                    layer.bindPopup(`<b>${feature.properties.Nama}</b>`);
                    layer.on("click", () => openEditJalur(feature));
                    layers.jalur.addLayer(layer);
                },
            });
        })
        .catch((err) => console.error("Gagal load jalur:", err));

    /* =============================
       AUTO EDIT FROM CONTROLLER
    ============================== */
    if (window.EDIT_JALUR_ID) {
        fetch(`/jalur_evakuasi/${window.EDIT_JALUR_ID}/geojson`)
            .then((res) => res.json())
            .then((feature) => {
                openEditJalur(feature);
                map.fitBounds(L.geoJSON(feature).getBounds(), {
                    padding: [50, 50],
                });
            });
    }
});
