document.addEventListener("DOMContentLoaded", () => {
    if (typeof initDistrictVillageSelect === "function") {
        initDistrictVillageSelect("#bencana_district", "#bencana_village");
    }

    if (!window.MapState || !MapState.map) {
        console.error("MapState belum tersedia");
        return;
    }

    const map = MapState.map;

    // MapState.activeModule = "bencana";

    if (!MapState.layers.bencana) {
        MapState.layers.bencana = L.layerGroup().addTo(map);
    }

    if (!MapState.layers.bencanaInput) {
        MapState.layers.bencanaInput = L.layerGroup().addTo(map);
    }

    const layerBencana = MapState.layers.bencana;
    const inputLayer = MapState.layers.bencanaInput;

    let formMode = "create";
    let inputMarker = null;

    const warnaBencana = {
        banjir: "#1E40AF",
        longsor: "#F59E0B",
        gempa: "#10B981",
    };

    function getSvgIcon(color = "#008eb5") {
        const svg = `
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 512 512">
            <path fill="${color}" d="M256 0C149.3 0 64 85.3 64 192c0 36.9 11 65.4 30.1 94.3l141.7 215c4.3 6.5 11.7 10.7 20.2 10.7s16-4.3 20.2-10.7l141.7-215C437 257.4 448 228.9 448 192C448 85.3 362.7 0 256 0z"/>
        </svg>
        `;

        return L.icon({
            iconUrl:
                "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(svg),
            iconSize: [40, 40],
            iconAnchor: [20, 40],
        });
    }

    function showBencanaDetail(item) {
        const box = document.getElementById("selectedBencana");
        if (!box) return;

        box.classList.remove("hidden");

        document.getElementById("detailJenis").innerText = item.jenis_bencana;
        document.getElementById("detailKecamatan").innerText =
            item.nama_kecamatan;
        document.getElementById("detailDesa").innerText = item.nama_desa;
        document.getElementById("detailKerawanan").innerText =
            item.tingkat_kerawanan;
        document.getElementById("detailStatus").innerText = item.status;

        const jenisEl = document.getElementById("detailJenis");
        jenisEl.className = "font-semibold";

        if (item.jenis_bencana === "banjir")
            jenisEl.classList.add("text-blue-600");
        if (item.jenis_bencana === "longsor")
            jenisEl.classList.add("text-yellow-600");
        if (item.jenis_bencana === "gempa")
            jenisEl.classList.add("text-green-600");
    }

    async function loadBencana() {
        layerBencana.clearLayers();

        try {
            const res = await fetch("/bencana/get-bencana");
            const json = await res.json();

            json.data.forEach((item) => {
                const lat = parseFloat(item.lat);
                const lng = parseFloat(item.lang);
                if (isNaN(lat) || isNaN(lng)) return;

                const marker = L.marker([lat, lng], {
                    icon: getSvgIcon(warnaBencana[item.jenis_bencana]),
                });

                const circle = L.circle([lat, lng], {
                    radius: 2000,
                    color: warnaBencana[item.jenis_bencana],
                    fillOpacity: 0.2,
                });

                marker.on("click", (e) => {
                    L.DomEvent.stopPropagation(e);
                    fillForm(item);
                    showBencanaDetail(item);
                });

                layerBencana.addLayer(marker);
                console.log("Jumlah marker bencana:", layerBencana.getLayers().length);
                layerBencana.addLayer(circle);
            });
        } catch (err) {
            console.error("Gagal load bencana:", err);
        }
    }

    function fillForm(item) {
        formMode = "edit";

        document.getElementById("bencana_id").value = item.id;
        document.getElementById("jenis_bencana").value = item.jenis_bencana;
        document.getElementById("tingkat_kerawanan").value =
            item.tingkat_kerawanan;
        document.getElementById("status").value = item.status;
        document.getElementById("lat").value = item.lat;
        document.getElementById("lang").value = item.lang;

        $("#bencana_district")
            .append(
                new Option(item.nama_kecamatan, item.kecamatan_id, true, true)
            )
            .trigger("change");

        setTimeout(() => {
            $("#bencana_village")
                .append(new Option(item.nama_desa, item.desa_id, true, true))
                .trigger("change");
        }, 300);

        inputLayer.clearLayers();
        inputMarker = L.marker([item.lat, item.lang], { draggable: true })
            .addTo(inputLayer)
            .on("dragend", updateLatLngFromMarker);

        map.setView([item.lat, item.lang], 15);
    }

    function updateLatLngFromMarker(e) {
        const pos = e.target.getLatLng();
        document.getElementById("lat").value = pos.lat.toFixed(6);
        document.getElementById("lang").value = pos.lng.toFixed(6);
    }

    map.on("click", (e) => {
        console.log("Map clicked!", e.latlng);
        console.log("Active module:", MapState.activeModule);
        if (MapState.activeModule !== "bencana") return;

        if (layerBencana.getLayers().length > 0) {
            layerBencana.clearLayers();
        }

        const lat = e.latlng.lat.toFixed(6);
        const lng = e.latlng.lng.toFixed(6);

        document.getElementById("lat").value = lat;
        document.getElementById("lang").value = lng;

        inputMarker = L.marker([lat, lng], { draggable: true })
            .addTo(inputLayer)
            .on("dragend", updateLatLngFromMarker);

        formMode = "create";
    });

    if (!MapState.bencanaLoaded) {
        loadBencana();
        MapState.bencanaLoaded = true;
    }
});
