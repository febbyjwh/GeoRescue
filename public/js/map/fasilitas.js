document.addEventListener("DOMContentLoaded", () => {
    if (typeof initDistrictVillageSelect === "function") {
        initDistrictVillageSelect('#fasilitas_district_id', '#fasilitas_village_id');
    }

    if (!window.MapState || !MapState.map) {
        console.error("MapState belum tersedia");
        return;
    }

    if (!MapState.layers.fasilitas) {
        MapState.layers.fasilitas = L.layerGroup().addTo(MapState.map);
    }
    const layerFasilitas = MapState.layers.fasilitas;

    if (!MapState.layers.inputPoint) {
        MapState.layers.inputPoint = L.layerGroup().addTo(MapState.map);
    }
    const inputLayer = MapState.layers.inputPoint;
    let inputMarker = null;

    const warnaFasilitas = {
        'Rumah Sakit': '#dc2626',
        'Puskesmas': '#16a34a',
        'Sekolah': '#2563eb',
        'Kantor Polisi': '#213448',
        'Pemadam Kebakaran': '#ea580c',
        'Kantor Pemerintahan': '#6b7280'
    };

    function iconFasilitas(jenis) {
        const color = warnaFasilitas[jenis] ?? '#64748b';
        
        return L.divIcon({
            html: `
                <div style="color:${color}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"
                        stroke="#ffffff"
                        stroke-width="2"
                        stroke-linejoin="round"/>
                    </svg>
                </div>
            `,
            className: '',
            iconSize: [20, 20],
            iconAnchor: [10, 20],
        });
    }

    async function loadFasilitas() {
        layerFasilitas.clearLayers();

        try {
            const res = await fetch("/fasilitasvital/get-fasilitas");
            const json = await res.json();

            json.data.forEach(item => {
                const lat = parseFloat(item.latitude);
                const lng = parseFloat(item.longitude);
                if (isNaN(lat) || isNaN(lng)) return;

                const marker = L.marker([lat, lng], {
                    icon: iconFasilitas(item.jenis_fasilitas)
                });

                marker.bindPopup(`
                    <strong>${item.nama_fasilitas}</strong><br>
                    Jenis: ${item.jenis_fasilitas}<br>
                    Kecamatan: ${item.nama_kecamatan}<br>
                    Desa: ${item.nama_desa}<br>
                    Status: ${item.status}
                `);

                marker.on("click", () => fillForm(item));
                layerFasilitas.addLayer(marker);
            });

        } catch (err) {
            console.error("Gagal load fasilitas:", err);
        }
    }

    function fillForm(item) {
        document.getElementById("fasilitas_id").value = item.id;
        document.getElementById("nama_fasilitas").value = item.nama_fasilitas;
        document.getElementById("jenis_fasilitas").value = item.jenis_fasilitas;
        document.getElementById("alamat").value = item.alamat || '';
        document.getElementById("status").value = item.status;
        document.getElementById("latitude").value = item.latitude;
        document.getElementById("longitude").value = item.longitude;

        const districtOption = new Option(
            item.nama_kecamatan,
            item.kecamatan_id,
            true,
            true
        );
        $('#fasilitas_district_id').append(districtOption).trigger('change');

        setTimeout(() => {
            const villageOption = new Option(
                item.nama_desa,
                item.desa_id,
                true,
                true
            );
            $('#fasilitas_village_id').append(villageOption).trigger('change');
        }, 300);

        inputLayer.clearLayers();
        inputMarker = L.marker([item.latitude, item.longitude], {
            draggable: true
        }).addTo(inputLayer);

        inputMarker.on("dragend", (ev) => {
            const pos = ev.target.getLatLng();
            document.getElementById("latitude").value = pos.lat.toFixed(6);
            document.getElementById("longitude").value = pos.lng.toFixed(6);
        });

        MapState.map.setView([item.latitude, item.longitude], 15);
    }

    function getFormData() {
        return {
            id: document.getElementById("fasilitas_id").value || null,
            nama_fasilitas: document.getElementById("nama_fasilitas").value,
            jenis_fasilitas: document.getElementById("jenis_fasilitas").value,
            alamat: document.getElementById("alamat").value,
            kecamatan_id: document.getElementById("fasilitas_district_id").value, 
            desa_id: document.getElementById("fasilitas_village_id").value,
            status: document.getElementById("status").value,
            latitude: document.getElementById("latitude").value,
            longitude: document.getElementById("longitude").value,
        };
    }

    window.submitFasilitas = async function () {
        const data = getFormData();
        const isEdit = !!data.id;

        const url = isEdit ? `/fasilitasvital/${data.id}` : `/fasilitasvital`;
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
            loadFasilitas();

        } catch (err) {
            console.error("Gagal simpan:", err);
            alert("Gagal menyimpan data");
        }
    };

    function resetForm() {
        document.getElementById("fasilitas_id").value = "";
        document.getElementById("nama_fasilitas").value = "";
        document.getElementById("jenis_fasilitas").value = "";
        document.getElementById("alamat").value = "";
        document.getElementById("status").value = "";
        document.getElementById("latitude").value = "";
        document.getElementById("longitude").value = "";

        $('#fasilitas_district_id').val(null).trigger('change');
        $('#fasilitas_village_id').empty().trigger('change');

        inputLayer.clearLayers();
    }

    MapState.map.on("click", (e) => {
        const lat = e.latlng.lat.toFixed(6);
        const lng = e.latlng.lng.toFixed(6);

        document.getElementById("latitude").value = lat;
        document.getElementById("longitude").value = lng;

        inputLayer.clearLayers();

        inputMarker = L.marker([lat, lng], {
            draggable: true
        }).addTo(inputLayer);

        inputMarker.on("dragend", (ev) => {
            const pos = ev.target.getLatLng();
            document.getElementById("latitude").value = pos.lat.toFixed(6);
            document.getElementById("longitude").value = pos.lng.toFixed(6);
        });
    });

    loadFasilitas();
});