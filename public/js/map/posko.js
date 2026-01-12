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

    // ===== ICON =====
    function getPoskoStyle(jenis, status) {
        let fillColor = "#2563eb";
        let fillOpacity = 1;

        switch ((jenis ?? "").toLowerCase()) {
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

        if ((status ?? "").toLowerCase().includes("tidak")) {
            fillColor = "#9ca3af";
            fillOpacity = 0.5;
        }

        return { fillColor, fillOpacity };
    }

    function getPoskoSVG(color, opacity = 1) {
        return `
        <svg xmlns="http://www.w3.org/2000/svg"
            width="28" height="28" viewBox="0 0 36 36" style="opacity:${opacity}">
            <path fill="${color}" d="M16.812 33c-.588 0-1.112-.37-1.31-.924L8.549 12.608a1.339 1.339 0 0 1 .173-1.242c.261-.369.685-.563 1.137-.563h16.313c.557 0 1.059.305 1.279.817l8.343 19.455c.184.43.14.917-.116 1.307a1.39 1.39 0 0 1-1.163.618H16.812z"/>
            <path fill="#FFCC4D" d="M1.515 33c-.467 0-.904-.236-1.162-.625a1.398 1.398 0 0 1-.116-1.315l8.348-19.479a1.392 1.392 0 0 1 2.557 0L19.49 31.06A1.391 1.391 0 0 1 18.212 33H1.515z"/>
            <path fill="#292F33" d="M9.859 14.182L7.077 33h5.563z"/>
            <path fill="#FFAC33" d="M15.46 31.456L16.081 33H12.64zm-11.203 0L3.636 33h3.441z"/>
            <path fill="#FFE8B6" d="M12.64 33s2.529-.645 3.766-1.786L9.859 14.182L12.64 33zm-5.563 0s-2.529-.645-3.766-1.786l6.546-17.031L7.077 33z"/>
        </svg>
        `;
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

    function createInputMarker(lat, lng) {
        inputLayer.clearLayers();
        inputMarker = L.marker([lat, lng], {
            draggable: true,
            icon: getPoskoIcon(
                formElements.jenis.value,
                formElements.status.value
            ),
        }).addTo(inputLayer);
        inputMarker.on("dragend", (e) => {
            const pos = e.target.getLatLng();
            formElements.latitude.value = pos.lat.toFixed(7);
            formElements.longitude.value = pos.lng.toFixed(7);
        });
    }

    function refreshInputMarkerIcon() {
        if (inputMarker) {
            inputMarker.setIcon(
                getPoskoIcon(
                    formElements.jenis.value,
                    formElements.status.value
                )
            );
        }
    }

    function showPoskoDetail(posko) {
        const box = document.getElementById("selectedPosko");
        if (!box) return;

        box.classList.remove("hidden");
        box.style.display = "block"; // paksa tampil

        document.getElementById("detailNamaPosko").innerText =
            posko.nama_posko ?? "-";
        document.getElementById("detailJenisPosko").innerText =
            posko.jenis_posko ?? "-";
        document.getElementById("detailKecamatanPosko").innerText =
            posko.nama_kecamatan ?? "-";
        document.getElementById("detailDesaPosko").innerText =
            posko.nama_desa ?? "-";
        document.getElementById("detailStatusPosko").innerText =
            posko.status_posko ?? "-";
        document.getElementById(
            "detailKoordinatPosko"
        ).innerText = `${posko.latitude}, ${posko.longitude}`;
    }

    function fillForm(item) {
        formMode = "edit";
        formElements.id.value = item.id;
        formElements.nama.value = item.nama_posko;
        formElements.jenis.value = item.jenis_posko;
        formElements.status.value = item.status_posko;
        formElements.latitude.value = item.latitude;
        formElements.longitude.value = item.longitude;

        formElements.kecamatan.innerHTML = `<option value="${item.kecamatan_id}" selected>${item.nama_kecamatan}</option>`;
        formElements.desa.innerHTML = `<option value="${item.desa_id}" selected>${item.nama_desa}</option>`;

        createInputMarker(item.latitude, item.longitude);
        refreshInputMarkerIcon();
        map.setView([item.latitude, item.longitude], 15);

        showPoskoDetail(item);
    }

    function switchToCreatePosko(lat = null, lng = null) {
        formMode = "create";
        formElements.form.reset();
        inputLayer.clearLayers();
        if (lat && lng) createInputMarker(lat, lng);
        // sembunyikan container detail
        const box = document.getElementById("selectedPosko");
        if (box) {
            box.classList.add("hidden");
            box.style.display = "none";
        }
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

                // Hanya trigger fillForm saat marker diklik
                marker.on("click", () => fillForm(item));

                layerPosko.addLayer(marker);
            });

            console.log("Posko loaded:", json.data.length);
        } catch (err) {
            console.error("Gagal load posko:", err);
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

        switchToCreatePosko(lat, lng);
        formElements.latitude.value = lat;
        formElements.longitude.value = lng;
    });

    formElements.jenis.addEventListener("change", refreshInputMarkerIcon);
    formElements.status.addEventListener("change", refreshInputMarkerIcon);

    loadPosko();
});
