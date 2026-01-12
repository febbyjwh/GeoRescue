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
    let inputMarker = [];

    const warnaBencana = {
        banjir: "#1E40AF",
        longsor: "#F59E0B",
        gempa: "#10B981",
    };

    function getBencanaIcon(jenis) {
        let fillColor = "#1E40AF"; // default biru
        if (jenis === "longsor") fillColor = "#F59E0B";
        if (jenis === "gempa") fillColor = "#10B981";

        const svg = `
    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 512 512">
      <circle cx="256" cy="192" r="32" fill="${fillColor}" stroke="#fff" stroke-width="8"/>
      <path fill="${fillColor}" stroke="#fff" stroke-width="18" d="M256 32c-88.22 0-160 68.65-160 153c0 40.17 18.31 93.59 54.42 158.78c29 52.34 62.55 99.67 80 123.22a31.75 31.75 0 0 0 51.22 0c17.42-23.55 51-70.88 80-123.22C397.69 278.61 416 225.19 416 185c0-84.35-71.78-153-160-153Zm0 224a64 64 0 1 1 64-64a64.07 64.07 0 0 1-64 64Z"/>
    </svg>
    `;

        return L.icon({
            iconUrl:
                "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(svg),
            iconSize: [40, 40],
            iconAnchor: [20, 40],
        });
        // let url = "";

        // if (jenis === "banjir") {
        //     url = "/image/banjir.png"; // path ke file image
        // } else if (jenis === "longsor") {
        //     url = "/image/longsor.png";
        // } else if (jenis === "gempa") {
        //     url = "/image/gempa.png";
        // }

        // return L.icon({
        //     iconUrl: url,
        //     iconSize: [40, 40],
        //     iconAnchor: [20, 40],
        // });
    }

    window.submitBencana = async function () {
        const formElements = document.getElementById("formBencana").elements;

        const data = {
            id: formElements.bencana_id.value || null,
            jenis_bencana: formElements.jenis_bencana.value,
            tingkat_kerawanan: formElements.tingkat_kerawanan.value,
            status: formElements.status.value,
            kecamatan_id: formElements.bencana_district.value,
            desa_id: formElements.bencana_village.value,
            lat: formElements.lat.value,
            lang: formElements.lang.value,
        };

        const isEdit = !!data.id;
        const url = isEdit ? `/bencana/${data.id}` : `/bencana`;
        const method = isEdit ? "PUT" : "POST";

        try {
            const res = await fetch(url, {
                method,
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify(data),
            });

            if (!res.ok) throw await res.json();

            alert(
                isEdit ? "Titik bencana diupdate" : "Titik bencana ditambahkan"
            );
            formElements.reset();
            inputLayer.clearLayers(); // hapus marker input
            loadBencana(); // reload semua marker bencana
        } catch (err) {
            console.error(err);
            alert("Gagal menyimpan titik bencana");
        }
    };

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
        document.getElementById(
            "detailKoordinatBencana"
        ).innerText = `${item.lat}, ${item.lang}`;

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
        // layerBencana.clearLayers();

        try {
            const res = await fetch("/bencana/get-bencana");
            const json = await res.json();

            json.data.forEach((item) => {
                const lat = parseFloat(item.lat);
                const lng = parseFloat(item.lang);
                if (isNaN(lat) || isNaN(lng)) return;

                const marker = L.marker([lat, lng], {
                    icon: getBencanaIcon(item.jenis_bencana),
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
                console.log(
                    "Jumlah marker bencana:",
                    layerBencana.getLayers().length
                );
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

    // const inputMarkers = []

    map.on("click", (e) => {
        console.log("Map clicked!", e.latlng);
        console.log("Active module:", MapState.activeModule);
        if (MapState.activeModule !== "bencana") return;

        if (layerBencana.getLayers().length > 0) {
            // layerBencana.clearLayers();
        }

        const lat = e.latlng.lat.toFixed(7);
        const lng = e.latlng.lng.toFixed(7);

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
