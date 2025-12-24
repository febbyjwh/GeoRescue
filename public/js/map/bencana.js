document.addEventListener("DOMContentLoaded", () => {
    if (typeof initDistrictVillageSelect === "function") {
        initDistrictVillageSelect('#district_id', '#village_id');
    }

    if (!window.MapState || !MapState.map) {
        console.error("MapState belum tersedia");
        return;
    }

    if (!MapState.layers.bencana) {
        MapState.layers.bencana = L.layerGroup().addTo(MapState.map);
    }
    const layerBencana = MapState.layers.bencana;

    if (!MapState.layers.inputPoint) {
        MapState.layers.inputPoint = L.layerGroup().addTo(MapState.map);
    }
    const inputLayer = MapState.layers.inputPoint;
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

            json.data.forEach(item => {
                const lat = parseFloat(item.lat);
                const lng = parseFloat(item.lang);
                if (isNaN(lat) || isNaN(lng)) return;

                const warna = warnaBencana[item.nama_bencana] ?? "#2563eb";

                const marker = L.circleMarker([lat, lng], {
                    radius: 9,
                    fillColor: warna,
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

                marker.on("click", () => fillForm(item));
                layerBencana.addLayer(marker);
            });

        } catch (err) {
            console.error("Gagal load bencana:", err);
        }
    }

    /* ===============================
     * FILL FORM (EDIT MODE)
     * =============================== */
    function fillForm(item) {
        document.getElementById("bencana_id").value = item.id;
        document.getElementById("nama_bencana").value = item.nama_bencana;
        document.getElementById("tingkat_kerawanan").value = item.tingkat_kerawanan;
        document.getElementById("lat").value = item.lat;
        document.getElementById("lang").value = item.lang;

        // Kecamatan
        const districtOption = new Option(
            item.nama_kecamatan,
            item.kecamatan_id,
            true,
            true
        );
        $('#district_id').append(districtOption).trigger('change');

        // Desa
        setTimeout(() => {
            const villageOption = new Option(
                item.nama_desa,
                item.desa_id,
                true,
                true
            );
            $('#village_id').append(villageOption).trigger('change');
        }, 300);

        // Marker edit
        inputLayer.clearLayers();
        inputMarker = L.marker([item.lat, item.lang], {
            draggable: true
        }).addTo(inputLayer);

        inputMarker.on("dragend", (ev) => {
            const pos = ev.target.getLatLng();
            document.getElementById("lat").value = pos.lat.toFixed(6);
            document.getElementById("lang").value = pos.lng.toFixed(6);
        });

        MapState.map.setView([item.lat, item.lang], 15);
    }

    function getFormData() {
        return {
            id: document.getElementById("bencana_id").value || null,
            nama_bencana: document.getElementById("nama_bencana").value,
            kecamatan_id: document.getElementById("district_id").value,
            desa_id: document.getElementById("village_id").value,
            tingkat_kerawanan: document.getElementById("tingkat_kerawanan").value,
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

            alert(isEdit ? "Data berhasil diupdate" : "Data berhasil ditambahkan");
            resetForm();
            loadBencana();

        } catch (err) {
            console.error("Gagal simpan:", err);
            alert("Gagal menyimpan data");
        }
    };

    function resetForm() {
        document.getElementById("bencana_id").value = "";
        document.querySelector("form")?.reset();

        $('#district_id').val(null).trigger('change');
        $('#village_id').empty().trigger('change');

        inputLayer.clearLayers();
    }

    MapState.map.on("click", (e) => {
        const lat = e.latlng.lat.toFixed(6);
        const lng = e.latlng.lng.toFixed(6);

        document.getElementById("lat").value = lat;
        document.getElementById("lang").value = lng;

        inputLayer.clearLayers();

        inputMarker = L.marker([lat, lng], {
            draggable: true
        }).addTo(inputLayer);

        inputMarker.on("dragend", (ev) => {
            const pos = ev.target.getLatLng();
            document.getElementById("lat").value = pos.lat.toFixed(6);
            document.getElementById("lang").value = pos.lng.toFixed(6);
        });
    });

    loadBencana();
});
