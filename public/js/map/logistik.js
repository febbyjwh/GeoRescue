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
            <div><b>Jumlah:</b> ${item.jumlah} ${item.logistik_satuan}</div>
            <div><b>Status:</b> ${item.logistik_status}</div>
            <div><b>Kecamatan:</b> ${item.nama_kecamatan ?? "-"}</div>
            <div><b>Desa:</b> ${item.nama_desa ?? "-"}</div>
        </div>
    `;
    }


    function resetLogistikForm() {
        const form = document.getElementById("formLogistik");

        // reset form biasa
        form.reset();

        // reset hidden id & mode
        document.getElementById("logistik_id").value = "";
        formMode = "create";

        // reset select2
        $("#logistik_district").val(null).trigger("change");
        $("#logistik_village").val(null).trigger("change");

        // hapus marker input
        inputLayer.clearLayers();
        inputMarker = [];

        // sembunyikan detail
        const box = document.getElementById("selectedLogistik");
        if (box) box.classList.add("hidden");
    }

    window.submitLogistik = async function () {
        const formElements = document.getElementById("formLogistik").elements;

        const data = {
            id: formElements.logistik_id.value || null,
            nama_lokasi: formElements.nama_lokasi.value,
            jenis_logistik: formElements.jenis_logistik.value,
            jumlah: formElements.jumlah.value,
            logistik_status: formElements.logistik_status.value,
            district_id: formElements.logistik_district.value,
            village_id: formElements.logistik_village.value,
            logistik_satuan: formElements.logistik_satuan.value,
            logistik_lat: formElements.logistik_lat.value,
            logistik_lng: formElements.logistik_lng.value,
        };

        const isEdit = !!data.id;
        const url = isEdit
            ? `/jalur_distribusi_logistik/${data.id}`
            : `/jalur_distribusi_logistik`;

        if (isEdit) data._method = "PUT";

        try {
            const res = await fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify(data),
            });

            if (!res.ok) throw await res.json();

            alert(
                isEdit ? "Titik logistik diupdate" : "Titik logistik ditambahkan"
            );
            resetLogistikForm();
            layerLogistik.clearLayers();
            loadLogistik();
        } catch (err) {
            console.error("Message: error" + err.message + ", " + err);
            alert("Gagal menyimpan titik Logistik");
        }
    };

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

        document.getElementById("detailKoordinatLogistik").textContent = `${item.lat ?? "-"
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

            // console.log(
            //     "Jumlah marker logistik:",
            //     layerLogistik.getLayers().length,
            // );
        } catch (err) {
            console.error("Gagal load logistik:", err);
        }
    }

    /* ===============================
     * FILL FORM (EDIT)
     * =============================== */
    function fillForm(item) {
        console.log(item);

        // ===== MODE EDIT =====
        document.getElementById("logistik_id").value = item.id ?? "";

        // ===== INPUT TEXT =====
        document.getElementById("nama_lokasi").value = item.nama_lokasi ?? "";
        document.getElementById("jenis_logistik").value = item.jenis_logistik ?? "";
        document.getElementById("jumlah").value = item.jumlah ?? "";
        document.getElementById("logistik_satuan").value = item.logistik_satuan ?? "";
        document.getElementById("logistik_status").value = item.logistik_status ?? "";

        // ===== KOORDINAT =====
        document.getElementById("logistik_lat").value = item.lat ?? "";
        document.getElementById("logistik_lng").value = item.lng ?? "";

        // ===== SELECT KECAMATAN =====
        if (item.kecamatan_id) {
            const kecamatanOption = new Option(
                item.nama_kecamatan ?? "Kecamatan Terpilih",
                item.kecamatan_id,
                true,
                true
            );

            $("#logistik_district")
                .empty()
                .append(kecamatanOption)
                .trigger("change");
        }

        // ===== SELECT DESA (WAIT KECAMATAN LOAD) =====
        if (item.desa_id) {
            setTimeout(() => {
                const desaOption = new Option(
                    item.nama_desa ?? "Desa Terpilih",
                    item.desa_id,
                    true,
                    true
                );

                $("#logistik_village")
                    .empty()
                    .append(desaOption)
                    .trigger("change");
            }, 400);
        }

        // ===== MARKER INPUT =====
        inputLayer.clearLayers();

        if (item.lat && item.lng) {
            inputMarker = L.marker([item.lat, item.lng], { draggable: true })
                .addTo(inputLayer)
                .on("dragend", updateLatLng);

            map.setView([item.lat, item.lng], 15);
        }

        // ===== DETAIL PANEL =====
        document.getElementById("detailNamaLogistik").textContent =
            item.nama_lokasi ?? "-";

        document.getElementById("detailJenisLogistik").textContent =
            item.jenis_logistik ?? "-";

        document.getElementById("detailJumlahSatuan").textContent = (item.jumlah ?? "-") + " " + (item.logistik_satuan ?? "-");

        document.getElementById("detailStatusLogistik").textContent = item.logistik_status ?? "-";

        document.getElementById("detailKecamatanLogistik").textContent =
            item.nama_kecamatan ?? "-";

        document.getElementById("detailDesaLogistik").textContent =
            item.nama_desa ?? "-";

        document.getElementById("detailKoordinatLogistik").textContent =
            `${item.lat ?? "-"}, ${item.lng ?? "-"}`;

        document.getElementById("selectedLogistik").classList.remove("hidden");
    }



    function updateLatLng(e) {
        const pos = e.target.getLatLng();
        document.getElementById("logistik_lat").value = pos.lat.toFixed(6);
        document.getElementById("logistik_lng").value = pos.lng.toFixed(6);
    }

    /* ===============================
     * MAP CLICK → CREATE
     * =============================== */
    map.on("click", (e) => {
        if (MapState.activeModule !== "logistik") return;

        inputLayer.clearLayers();

        const lat = e.latlng.lat.toFixed(7);
        const lng = e.latlng.lng.toFixed(7);

        document.getElementById("logistik_lat").value = lat;
        document.getElementById("logistik_lng").value = lng;

        console.log($('#logistik_lat').val());


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