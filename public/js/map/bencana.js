document.addEventListener("DOMContentLoaded", () => {

    if (typeof initDistrictVillageSelect === "function") {
        initDistrictVillageSelect("#district_id", "#village_id");
    }

    if (!window.MapState || !MapState.map) {
        console.error("MapState belum tersedia");
        return;
    }

    const map = MapState.map;

    if (!MapState.layers.bencana) {
        MapState.layers.bencana = L.layerGroup().addTo(map);
    }
    if (!MapState.layers.inputPoint) {
        MapState.layers.inputPoint = L.layerGroup().addTo(map);
    }

    const layerBencana = MapState.layers.bencana;
    const inputLayer = MapState.layers.inputPoint;

    let formMode = "create"; // create | edit
    let inputMarker = null;

    const warnaBencana = {
        Banjir: "#1E40AF",
        Longsor: "#F59E0B",
        Gempa: "#10B981",
    };

    async function loadBencana() {
        layerBencana.clearLayers();

        try {
            const res = await fetch("/bencana/get-bencana");
            const json = await res.json();

            json.data.forEach((item) => {
                const lat = parseFloat(item.lat);
                const lng = parseFloat(item.lang);
                if (isNaN(lat) || isNaN(lng)) return;

                const marker = L.circleMarker([lat, lng], {
                    radius: 9,
                    fillColor:
                        warnaBencana[item.nama_bencana] ?? "#2563eb",
                    fillOpacity: 0.85,
                    color: "#ffffff",
                    weight: 1,
                });

                marker.bindPopup(`
                    <strong>${item.nama_bencana}</strong><br>
                    Kecamatan: ${item.nama_kecamatan}<br>
                    Desa: ${item.nama_desa}<br>
                    Kerawanan: ${item.tingkat_kerawanan}
                `);

                // 👉 KLIK MARKER = EDIT
                marker.on("click", (e) => {
                    L.DomEvent.stopPropagation(e);
                    fillForm(item);
                });

                layerBencana.addLayer(marker);
            });
        } catch (err) {
            console.error("Gagal load bencana:", err);
        }
    }

    function setMode(mode) {
        formMode = mode;
    }

    function fillForm(item) {
        setMode("edit");

        document.getElementById("bencana_id").value = item.id;
        document.getElementById("nama_bencana").value = item.nama_bencana;
        document.getElementById("tingkat_kerawanan").value =
            item.tingkat_kerawanan;
        document.getElementById("lat").value = item.lat;
        document.getElementById("lang").value = item.lang;

        // Kecamatan
        const districtOption = new Option(
            item.nama_kecamatan,
            item.kecamatan_id,
            true,
            true
        );
        $("#district_id").append(districtOption).trigger("change");

        // Desa (delay karena async select2)
        setTimeout(() => {
            const villageOption = new Option(
                item.nama_desa,
                item.desa_id,
                true,
                true
            );
            $("#village_id").append(villageOption).trigger("change");
        }, 300);

        // Marker input
        inputLayer.clearLayers();
        inputMarker = L.marker([item.lat, item.lang], {
            draggable: true,
        }).addTo(inputLayer);

        inputMarker.on("dragend", updateLatLngFromMarker);

        map.setView([item.lat, item.lang], 15);
    }

    function switchToCreateBencana(lat, lng) {
        setMode("create");

        document.getElementById("bencana_id").value = "";
        document.querySelector("form")?.reset();

        $("#district_id").val(null).trigger("change");
        $("#village_id").empty().trigger("change");

        inputLayer.clearLayers();

        if (lat && lng) {
            document.getElementById("lat").value = lat;
            document.getElementById("lang").value = lng;

            inputMarker = L.marker([lat, lng], {
                draggable: true,
            }).addTo(inputLayer);

            inputMarker.on("dragend", updateLatLngFromMarker);
        }
    }

    function updateLatLngFromMarker(e) {
        const pos = e.target.getLatLng();
        document.getElementById("lat").value = pos.lat.toFixed(6);
        document.getElementById("lang").value = pos.lng.toFixed(6);
    }

    function getFormData() {
        return {
            id: document.getElementById("bencana_id").value || null,
            nama_bencana: document.getElementById("nama_bencana").value,
            kecamatan_id: document.getElementById("district_id").value,
            desa_id: document.getElementById("village_id").value,
            tingkat_kerawanan:
                document.getElementById("tingkat_kerawanan").value,
            lat: document.getElementById("lat").value,
            lang: document.getElementById("lang").value,
        };
    }

    window.submitBencana = async function () {
        const data = getFormData();
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
                isEdit
                    ? "Data berhasil diupdate"
                    : "Data berhasil ditambahkan"
            );

            switchToCreateBencana();
            loadBencana();
        } catch (err) {
            console.error("Gagal simpan:", err);
            alert("Gagal menyimpan data");
        }
    };

    map.on("click", (e) => {
        switchToCreateBencana(
            e.latlng.lat.toFixed(6),
            e.latlng.lng.toFixed(6)
        );
    });

    loadBencana();
});
