document.addEventListener("DOMContentLoaded", () => {
    console.log("POSKO JS LOADED");

    // ===============================
    // FORM ELEMENTS
    // ===============================
    const formElements = {
        form: document.getElementById("formPosko"),
        id: document.getElementById("posko_id"),
        nama: document.getElementById("nama_posko"),
        jenis: document.getElementById("jenis_posko"),
        status: document.getElementById("status_posko"),
        kecamatan: document.getElementById("kecamatan_id"),
        desa: document.getElementById("desa_id"),
        latitude: document.getElementById("latitude"),
        longitude: document.getElementById("longitude"),
    };

    // Cek semua element ada
    for (const [key, el] of Object.entries(formElements)) {
        if (!el) console.warn(`Form element "${key}" belum ada di DOM!`);
    }

    // ===============================
    // MAP CHECK
    // ===============================
    if (!window.MapState || !MapState.map) {
        console.error("MapState belum tersedia");
        return;
    }

    const map = MapState.map;

    // ===============================
    // ACTIVE MODULE FLAG
    // ===============================
    MapState.activeModule = "posko";
    console.log("Active module set to POSKO");

    // ===============================
    // LAYERS
    // ===============================
    if (!MapState.layers.posko) MapState.layers.posko = L.layerGroup().addTo(map);
    if (!MapState.layers.poskoInput) MapState.layers.poskoInput = L.layerGroup().addTo(map);

    const layerPosko = MapState.layers.posko;
    const inputLayer = MapState.layers.poskoInput;

    let formMode = "create";
    let inputMarker = null;

    // ===============================
    // LOAD POSKO
    // ===============================
    async function loadPosko() {
        layerPosko.clearLayers();
        try {
            const res = await fetch("/posko/get-posko");
            const json = await res.json();

            json.data.forEach(item => {
                const lat = parseFloat(item.latitude);
                const lng = parseFloat(item.longitude);
                if (isNaN(lat) || isNaN(lng)) return;

                const marker = L.circleMarker([lat, lng], {
                    radius: 8,
                    fillColor: "#2563eb",
                    fillOpacity: 0.85,
                    color: "#fff",
                    weight: 1,
                });

                marker.bindPopup(`
                    <strong>${item.nama_posko}</strong><br>
                    Kecamatan: ${item.nama_kecamatan}<br>
                    Desa: ${item.nama_desa}<br>
                    Jenis: ${item.jenis_posko}<br>
                    Status: ${item.status_posko}
                `);

                marker.on("click", () => fillForm(item));

                layerPosko.addLayer(marker);
                console.log("Marker added:", marker.getLatLng());
            });

            console.log("Posko loaded:", json.data.length);
        } catch (err) {
            console.error("Gagal load posko:", err);
        }
    }

    // ===============================
    // FILL FORM
    // ===============================
    function fillForm(item) {
        formMode = "edit";

        formElements.id.value = item.id;
        formElements.nama.value = item.nama_posko;
        formElements.jenis.value = item.jenis_posko;
        formElements.status.value = item.status_posko;
        formElements.latitude.value = item.latitude;
        formElements.longitude.value = item.longitude;

        // Kecamatan & Desa
        formElements.kecamatan.innerHTML = `<option value="${item.kecamatan_id}" selected>${item.nama_kecamatan}</option>`;
        formElements.desa.innerHTML = `<option value="${item.desa_id}" selected>${item.nama_desa}</option>`;

        // Marker edit
        inputLayer.clearLayers();
        inputMarker = L.marker([item.latitude, item.longitude], {
            draggable: true
        }).addTo(inputLayer);
        inputMarker.on("dragend", updateLatLngFromMarker);

        map.setView([item.latitude, item.longitude], 15);
    }

    function switchToCreatePosko(lat = null, lng = null) {
        formMode = "create";
        formElements.form.reset();

        inputLayer.clearLayers();

        if (lat && lng) {
            formElements.latitude.value = lat;
            formElements.longitude.value = lng;

            inputMarker = L.marker([lat, lng], {
                draggable: true
            }).addTo(inputLayer);
            inputMarker.on("dragend", updateLatLngFromMarker);
        }
    }

    function updateLatLngFromMarker(e) {
        const pos = e.target.getLatLng();
        formElements.latitude.value = pos.lat.toFixed(7);
        formElements.longitude.value = pos.lng.toFixed(7);
    }

    // ===============================
    // SUBMIT POSKO
    // ===============================
    window.submitPosko = async function () {
        const data = {
            id: formElements.id.value || null,
            nama_posko: formElements.nama.value,
            jenis_posko: formElements.jenis.value,
            status_posko: formElements.status.value,
            kecamatan_id: formElements.kecamatan.value,
            desa_id: formElements.desa.value,
            latitude: formElements.latitude.value,
            longitude: formElements.longitude.value,
        };

        const isEdit = !!data.id;
        const url = isEdit ? `/posko/${data.id}` : `/posko`;
        const method = isEdit ? "PUT" : "POST";

        try {
            const res = await fetch(url, {
                method,
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                },
                body: JSON.stringify(data),
            });

            if (!res.ok) throw await res.json();

            alert(isEdit ? "Posko diupdate" : "Posko ditambahkan");
            switchToCreatePosko();
            loadPosko();
        } catch (err) {
            console.error(err);
            alert("Gagal menyimpan posko");
        }
    };

    // ===============================
    // MAP CLICK TO ADD MARKER
    // ===============================
    map.on("click", (e) => {
        if (formMode === "edit") return;

        const lat = e.latlng.lat.toFixed(7);
        const lng = e.latlng.lng.toFixed(7);

        formElements.latitude.value = lat;
        formElements.longitude.value = lng;

        inputLayer.clearLayers();
        inputMarker = L.marker([lat, lng], {
            draggable: true
        }).addTo(inputLayer);
        inputMarker.on("dragend", updateLatLngFromMarker);
    });

    // ===============================
    // INITIAL LOAD
    // ===============================
    loadPosko();
});
