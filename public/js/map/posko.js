document.addEventListener("DOMContentLoaded", () => {
    console.log("POSKO JS LOADED");

    if (typeof initDistrictVillageSelect === "function") {
        initDistrictVillageSelect("#kecamatan_id", "#desa_id");
    }

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

    for (const [key, el] of Object.entries(formElements)) {
        if (!el) console.warn(`Form element "${key}" belum ada di DOM`);
    }

    if (!window.MapState || !MapState.map) {
        console.error("MapState belum tersedia");
        return;
    }

    const map = MapState.map;
    MapState.activeModule = "posko";

    if (!MapState.layers.posko)
        MapState.layers.posko = L.layerGroup().addTo(map);
    if (!MapState.layers.poskoInput)
        MapState.layers.poskoInput = L.layerGroup().addTo(map);

    const layerPosko = MapState.layers.posko;
    const inputLayer = MapState.layers.poskoInput;

    let inputMarker = null;
    let formMode = "create";

    function getPoskoStyle(jenis, status) {
        let fillColor = "#2563eb";
        let fillOpacity = 1;

        switch (jenis?.toLowerCase()) {
            case "kesehatan":
            case "posko kesehatan":
                fillColor = "#dc2626";
                break;
            case "evakuasi":
            case "posko evakuasi":
                fillColor = "#16a34a";
                break;
            case "logistik":
                fillColor = "#ca8a04";
                break;
        }

        switch (status?.toLowerCase()) {
            case "tidak aktif":
            case "nonaktif":
                fillColor = "#9ca3af";
                fillOpacity = 0.5;
                break;
        }

        return { fillColor, fillOpacity };
    }

    function getPoskoSVG(color, opacity = 1) {
        return `
        <svg xmlns="http://www.w3.org/2000/svg"
            width="28"
            height="28"
            viewBox="0 0 24 24"
            fill="${color}"
            style="opacity:${opacity}">
            <path d="M8.575 12.25ZM2 21V9l8-6l5.375 4.05q-.625.075-1.175.288t-1.05.562L10 5.5L4 10v9h4v2H2Zm8 0v-1.9q0-.525.263-.988t.712-.737q1.15-.675 2.413-1.025T16 16q1.35 0 2.613.35t2.412 1.025q.45.275.713.738T22 19.1V21H10Zm2.15-2h7.7q-.875-.5-1.85-.75T16 18q-1.025 0-2 .25t-1.85.75ZM16 15q-1.25 0-2.125-.875T13 12q0-1.25.875-2.125T16 9q1.25 0 2.125.875T19 12q0 1.25-.875 2.125T16 15Zm0-2q.425 0 .713-.288T17 12q0-.425-.288-.713T16 11q-.425 0-.713.288T15 12q0 .425.288.713T16 13Z"/>
        </svg>`;
    }

    function getPoskoIcon(jenis, status) {
        const style = getPoskoStyle(jenis, status);
        return L.divIcon({
            html: getPoskoSVG(style.fillColor, style.fillOpacity),
            className: "posko-svg-icon",
            iconSize: [40, 40],
            iconAnchor: [14, 28],
            popupAnchor: [0, -28],
        });
    }

    async function loadPosko() {
        layerPosko.clearLayers();
        try {
            const res = await fetch("/posko/get-posko");
            const json = await res.json();

            json.data.forEach((item) => {
                const lat = parseFloat(item.latitude);
                const lng = parseFloat(item.longitude);
                if (isNaN(lat) || isNaN(lng)) return;

                const marker = L.marker([lat, lng], {
                    icon: getPoskoIcon(item.jenis_posko, item.status_posko),
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
            });

            console.log("Posko loaded:", json.data.length);
        } catch (err) {
            console.error("Gagal load posko:", err);
        }
    }

    function createInputMarker(lat, lng) {
        inputLayer.clearLayers();
        inputMarker = L.marker([lat, lng], {
            draggable: true,
            icon: getPoskoIcon(
                formElements.jenis.value,
                formElements.status.value
            ),
        }).addTo(inputLayer);

        inputMarker.on("dragend", updateLatLngFromMarker);
    }

    function refreshInputMarkerIcon() {
        if (!inputMarker) return;
        inputMarker.setIcon(
            getPoskoIcon(
                formElements.jenis.value,
                formElements.status.value
            )
        );
    }

    function updateLatLngFromMarker(e) {
        const pos = e.target.getLatLng();
        formElements.latitude.value = pos.lat.toFixed(7);
        formElements.longitude.value = pos.lng.toFixed(7);
    }

    function fillForm(item) {
        formMode = "edit";

        formElements.id.value = item.id;
        formElements.nama.value = item.nama_posko;
        formElements.jenis.value = item.jenis_posko;
        formElements.status.value = item.status_posko;
        formElements.latitude.value = item.latitude;
        formElements.longitude.value = item.longitude;

        formElements.kecamatan.innerHTML =
            `<option value="${item.kecamatan_id}" selected>${item.nama_kecamatan}</option>`;
        formElements.desa.innerHTML =
            `<option value="${item.desa_id}" selected>${item.nama_desa}</option>`;

        createInputMarker(item.latitude, item.longitude);
        map.setView([item.latitude, item.longitude], 15);
    }

    function switchToCreatePosko(lat = null, lng = null) {
        formMode = "create";
        formElements.form.reset();
        inputLayer.clearLayers();

        if (lat && lng) {
            formElements.latitude.value = lat;
            formElements.longitude.value = lng;
            createInputMarker(lat, lng);
        }
    }

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
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
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

    map.on("click", (e) => {
        if (MapState.activeModule !== "posko") return;

        const lat = e.latlng.lat.toFixed(7);
        const lng = e.latlng.lng.toFixed(7);

        formElements.latitude.value = lat;
        formElements.longitude.value = lng;

        createInputMarker(lat, lng);
    });

    formElements.jenis.addEventListener("change", refreshInputMarkerIcon);
    formElements.status.addEventListener("change", refreshInputMarkerIcon);

    loadPosko();
});
