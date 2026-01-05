document.addEventListener("DOMContentLoaded", () => {

    if (typeof initDistrictVillageSelect === "function") {
        console.log(typeof initDistrictVillageSelect);
        initDistrictVillageSelect("#district_id", "#village_id");
    }

    if (!window.MapState || !MapState.map) {
        console.error("MapState belum tersedia");
        return;
    }

    if (!MapState.layers.posko) {
        MapState.layers.posko = L.layerGroup().addTo(MapState.map);
    }
    const layerPosko = MapState.layers.posko;

    if (!MapState.layers.inputPoint) {
        MapState.layers.inputPoint = L.layerGroup().addTo(MapState.map);
    }
    const inputLayer = MapState.layers.inputPoint;

    let formMode = "create";
    let inputMarker = null;

    async function loadPosko() {
        layerPosko.clearLayers();

        try {
            const res = await fetch("/posko/get-posko");
            const json = await res.json();

            json.data.forEach((item) => {
                const lat = parseFloat(item.latitude);
                const lng = parseFloat(item.longitude);
                if (isNaN(lat) || isNaN(lng)) return;

                const marker = L.circleMarker([lat, lng], {
                    radius: 8,
                    fillColor: "#2563eb",
                    fillOpacity: 0.85,
                    color: "#ffffff",
                    weight: 1,
                });

                marker.bindPopup(`
                    <strong>${item.nama_posko}</strong><br>
                    Kecamatan: ${item.nama_kecamatan}<br>
                    Desa: ${item.nama_desa}<br>
                    Status: ${item.status_posko}
                `);

                marker.on("click", () => fillForm(item));
                layerPosko.addLayer(marker);
            });
        } catch (err) {
            console.error("Gagal load posko:", err);
        }
    }

    function fillForm(item) {
        formMode = "edit";

        document.getElementById("posko_id").value = item.id;
        document.getElementById("nama_posko").value = item.nama_posko;
        document.getElementById("jenis_posko").value = item.jenis_posko;
        document.getElementById("status_posko").value = item.status_posko;
        document.getElementById("latitude").value = item.latitude;
        document.getElementById("longitude").value = item.longitude;

        // Kecamatan
        const districtOption = new Option(
            item.nama_kecamatan,
            item.kecamatan_id,
            true,
            true
        );
        $("#district_id").append(districtOption).trigger("change");

        // Desa
        setTimeout(() => {
            const villageOption = new Option(
                item.nama_desa,
                item.desa_id,
                true,
                true
            );
            $("#village_id").append(villageOption).trigger("change");
        }, 300);

        inputLayer.clearLayers();
        inputMarker = L.marker([item.latitude, item.longitude], {
            draggable: true,
        }).addTo(inputLayer);

        inputMarker.on("dragend", (ev) => {
            const pos = ev.target.getLatLng();
            document.getElementById("latitude").value =
                pos.lat.toFixed(7);
            document.getElementById("longitude").value =
                pos.lng.toFixed(7);
        });

        MapState.map.setView([item.latitude, item.longitude], 15);
    }

    function getFormData() {
        return {
            id: document.getElementById("posko_id").value || null,
            nama_posko: document.getElementById("nama_posko").value,
            jenis_posko: document.getElementById("jenis_posko").value,
            kecamatan_id: document.getElementById("district_id").value,
            desa_id: document.getElementById("village_id").value,
            status_posko: document.getElementById("status_posko").value,
            latitude: document.getElementById("latitude").value,
            longitude: document.getElementById("longitude").value,
        };
    }

    window.submitPosko = async function () {
        const data = getFormData();
        const isEdit = !!data.id;

        const url = isEdit ? `/posko/${data.id}` : `/posko`;
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
                    ? "Data posko berhasil diupdate"
                    : "Data posko berhasil ditambahkan"
            );
            resetForm();
            loadPosko();
        } catch (err) {
            console.error("Gagal simpan posko:", err);
            alert("Gagal menyimpan data posko");
        }
    };

    function switchToCreatePosko(lat = null, lng = null) {
        formMode = "create";

        document.getElementById("posko_id").value = "";
        document.querySelector("form")?.reset();

        $("#district_id").val(null).trigger("change");
        $("#village_id").empty().trigger("change");

        inputLayer.clearLayers();

        if (lat && lng) {
            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lng;

            inputMarker = L.marker([lat, lng], {
                draggable: true,
            }).addTo(inputLayer);

            inputMarker.on("dragend", (ev) => {
                const pos = ev.target.getLatLng();
                document.getElementById("latitude").value =
                    pos.lat.toFixed(7);
                document.getElementById("longitude").value =
                    pos.lng.toFixed(7);
            });
        }
    }

    function resetForm() {
        document.getElementById("posko_id").value = "";
        document.querySelector("form")?.reset();

        $("#district_id").val(null).trigger("change");
        $("#village_id").empty().trigger("change");

        inputLayer.clearLayers();
    }

    MapState.map.on("click", (e) => {
        if (formMode === "edit") {
            switchToCreatePosko(
                e.latlng.lat.toFixed(7),
                e.latlng.lng.toFixed(7)
            );
            return;
        }

        const lat = e.latlng.lat.toFixed(7);
        const lng = e.latlng.lng.toFixed(7);

        document.getElementById("latitude").value = lat;
        document.getElementById("longitude").value = lng;

        inputLayer.clearLayers();

        inputMarker = L.marker([lat, lng], {
            draggable: true,
        }).addTo(inputLayer);

        inputMarker.on("dragend", (ev) => {
            const pos = ev.target.getLatLng();
            document.getElementById("latitude").value =
                pos.lat.toFixed(7);
            document.getElementById("longitude").value =
                pos.lng.toFixed(7);
        });
    });

    loadPosko();
});
