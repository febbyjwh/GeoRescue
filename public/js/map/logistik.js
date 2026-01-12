document.addEventListener("DOMContentLoaded", () => {
    /* ===============================
     * INIT SELECT
     * =============================== */
    if (typeof initDistrictVillageSelect === "function") {
        initDistrictVillageSelect("#logistik_district", "#logistik_village");
    }

    /* ===============================
     * MAP STATE
     * =============================== */
    if (!window.MapState || !MapState.map) {
        console.error("MapState belum tersedia");
        return;
    }

    const map = MapState.map;

    if (!MapState.layers.logistik) {
        MapState.layers.logistik = L.layerGroup().addTo(map);
    }

    if (!MapState.layers.logistikInput) {
        MapState.layers.logistikInput = L.layerGroup().addTo(map);
    }

    const layerLogistik = MapState.layers.logistik;
    const inputLayer = MapState.layers.logistikInput;

    let inputMarker = null;

    /* ===============================
     * ICON
     * =============================== */
    function getColor(jenis) {
        return (
            {
                pangan: "#10B981",
                sandang: "#F59E0B",
                kesehatan: "#EF4444",
                hunian: "#8B5CF6",
            }[jenis] || "#2563EB"
        );
    }

    function getLogistikIcon(jenis) {
        const color = getColor(jenis);

        const svg = `
<svg xmlns="http://www.w3.org/2000/svg"
     width="36" height="36"
     viewBox="0 0 20 20">
  <path
    d="M19.367 18.102L18 14h-1.5l.833 4H2.667l.833-4H2L.632 18.102C.285 19.146.9 20 2 20h16c1.1 0 1.715-.854 1.367-1.898zM15 5A5 5 0 1 0 5 5c0 4.775 5 10 5 10s5-5.225 5-10zm-7.7.06A2.699 2.699 0 0 1 10 2.361a2.699 2.699 0 1 1 0 5.399a2.7 2.7 0 0 1-2.7-2.7z"
    fill="${color}"
    stroke="white"
    stroke-width="1.5"
    paint-order="stroke fill"
  />
</svg>`;

        return L.icon({
            iconUrl:
                "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(svg),
            iconSize: [40, 40],
            iconAnchor: [20, 40],
            popupAnchor: [0, -36],
        });
    }

    function getLogistikPopup(item) {
        return `
        <div style="min-width:220px">
            <strong>${item.nama_lokasi}</strong>
            <hr style="margin:6px 0">
            <div><b>Jenis:</b> ${item.jenis_logistik}</div>
            <div><b>Jumlah:</b> ${item.jumlah} ${item.satuan}</div>
            <div><b>Status:</b> ${item.status}</div>
            <div><b>Kecamatan:</b> ${item.nama_kecamatan ?? "-"}</div>
            <div><b>Desa:</b> ${item.nama_desa ?? "-"}</div>
        </div>
    `;
    }

    function showLogistikDetail(item) {
        const box = document.getElementById("selectedLogistik");
        if (!box) return;
        box.classList.remove("hidden");

        document.getElementById("detailNamaLogistik").textContent =
            item.nama_lokasi ?? "-";
        document.getElementById("detailJenisLogistik").textContent =
            item.jenis_logistik ?? "-";
        document.getElementById("detailJumlahSatuan").textContent =
            (item.jumlah ?? "-") + " " + (item.satuan ?? "-");
        document.getElementById("detailStatusLogistik").textContent =
            item.status ?? "-";

        // gunakan nama_kecamatan / nama_desa dari JSON
        document.getElementById("detailKecamatanLogistik").textContent =
            item.nama_kecamatan ?? "-";
        document.getElementById("detailDesaLogistik").textContent =
            item.nama_desa ?? "-";

        document.getElementById("detailKoordinatLogistik").textContent = `${
            item.lat ?? "-"
        }, ${item.lng ?? "-"}`;
    }

    /* ===============================
     * LOAD LOGISTIK
     * =============================== */
    async function loadLogistik() {
        layerLogistik.clearLayers();

        try {
            const res = await fetch("/jalur_distribusi_logistik/get-logistik");
            const json = await res.json();

            json.data.forEach((item) => {
                const lat = parseFloat(item.lat);
                const lng = parseFloat(item.lng);
                if (isNaN(lat) || isNaN(lng)) return;

                const marker = L.marker([lat, lng], {
                    icon: getLogistikIcon(item.jenis_logistik),
                });

                marker.bindPopup(getLogistikPopup(item), {
                    closeButton: true,
                    offset: [0, -30],
                });

                marker.on("click", (e) => {
                    L.DomEvent.stopPropagation(e);
                    fillForm(item);
                    showLogistikDetail(item);
                });

                layerLogistik.addLayer(marker);
            });

            console.log(
                "Jumlah marker logistik:",
                layerLogistik.getLayers().length
            );
        } catch (err) {
            console.error("Gagal load logistik:", err);
        }
    }

    /* ===============================
     * FILL FORM (EDIT)
     * =============================== */
    function fillForm(item) {
        // isi form
        document.getElementById("logistik_id").value = item.id;
        document.getElementById("nama_lokasi").value = item.nama_lokasi;
        document.getElementById("jenis_logistik").value = item.jenis_logistik;
        document.getElementById("jumlah").value = item.jumlah;
        document.getElementById("satuan").value = item.satuan;
        document.getElementById("status").value = item.status;
        document.getElementById("detailKecamatanLogistik").textContent =
            item.nama_kecamatan ?? "-";
        document.getElementById("detailDesaLogistik").textContent =
            item.nama_desa ?? "-";
        document.getElementById("lat").value = item.lat;
        document.getElementById("lng").value = item.lng;

        // select kecamatan & desa
        $("#logistik_district")
            .append(new Option(item.district_id, item.district_id, true, true))
            .trigger("change");

        setTimeout(() => {
            $("#logistik_village")
                .append(
                    new Option(item.village_id, item.village_id, true, true)
                )
                .trigger("change");
        }, 300);

        // marker input
        inputLayer.clearLayers();
        inputMarker = L.marker([item.lat, item.lng], { draggable: true })
            .addTo(inputLayer)
            .on("dragend", updateLatLng);

        map.setView([item.lat, item.lng], 15);

        // ==== UPDATE DETAIL LOGISTIK TERPILIH ====
        document.getElementById("detailNamaLogistik").textContent =
            item.nama_lokasi;
        document.getElementById("detailJenisLogistik").textContent =
            item.jenis_logistik;
        document.getElementById("detailJumlahSatuan").textContent =
            item.jumlah + " " + item.satuan;
        document.getElementById("detailStatusLogistik").textContent =
            item.status;

        // Karena JSON hanya ada district_id & village_id
        document.getElementById("detailKecamatanLogistik").textContent =
            item.district_id || "-";
        document.getElementById("detailDesaLogistik").textContent =
            item.village_id || "-";

        document.getElementById(
            "detailKoordinatLogistik"
        ).textContent = `${item.lat}, ${item.lng}`;

        // tampilkan container
        document.getElementById("selectedLogistik").classList.remove("hidden");
    }

    function updateLatLng(e) {
        const pos = e.target.getLatLng();
        document.getElementById("lat").value = pos.lat.toFixed(6);
        document.getElementById("lng").value = pos.lng.toFixed(6);
    }

    /* ===============================
     * MAP CLICK → CREATE
     * =============================== */
    map.on("click", (e) => {
        if (MapState.activeModule !== "logistik") return;

        inputLayer.clearLayers();

        const lat = e.latlng.lat.toFixed(6);
        const lng = e.latlng.lng.toFixed(6);

        document.getElementById("lat").value = lat;
        document.getElementById("lng").value = lng;

        inputMarker = L.marker([lat, lng], { draggable: true })
            .addTo(inputLayer)
            .on("dragend", updateLatLng);
    });

    /* ===============================
     * INIT
     * =============================== */
    if (!MapState.logistikLoaded) {
        loadLogistik();
        MapState.logistikLoaded = true;
    }
});
