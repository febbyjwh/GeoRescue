document.addEventListener("DOMContentLoaded", () => {
  const district = document.getElementById("district");
  const village = document.getElementById("village");

  if (!district || !village) return;

  district.addEventListener("change", async function () {
    village.innerHTML = '<option value="">-- Pilih Desa --</option>';

    if (!this.value) return;

    const res = await fetch(`/jalur_distribusi_logistik/villages/${this.value}`);
    const json = await res.json();

    (json.data || []).forEach((v) => {
      const opt = document.createElement("option");
      opt.value = v.id;
      opt.textContent = v.name;
      village.appendChild(opt);
    });
  });

  document.addEventListener("DOMContentLoaded", () => {
  // kalau kamu punya helper select2 dependent:
  if (typeof initDistrictVillageSelect === "function") {
    // sesuaikan selector id kamu (di component id biasanya = name)
    initDistrictVillageSelect("#district_id", "#village_id");
  }

  if (!window.MapState || !MapState.map) {
    console.error("MapState belum tersedia");
    return;
  }

  // Layer marker logistik
  if (!MapState.layers.logistik) {
    MapState.layers.logistik = L.layerGroup().addTo(MapState.map);
  }
  const layerLogistik = MapState.layers.logistik;

  // Layer marker input (klik map / edit posisi)
  if (!MapState.layers.inputPoint) {
    MapState.layers.inputPoint = L.layerGroup().addTo(MapState.map);
  }
  const inputLayer = MapState.layers.inputPoint;

  let formMode = "create";
  let inputMarker = null;

  // warna marker berdasarkan jenis logistik (bebas kamu sesuaikan)
  const warnaLogistik = {
    Makanan: "#10B981",
    "Obat-obatan": "#EF4444",
    "Air Bersih": "#3B82F6",
    Selimut: "#F59E0B",
    Tenda: "#8B5CF6",
  };

  function getCsrf() {
    return document
      .querySelector('meta[name="csrf-token"]')
      ?.getAttribute("content");
  }

  /* ===============================
   * LOAD LOGISTIK KE MAP
   * =============================== */
  async function loadLogistik() {
    layerLogistik.clearLayers();

    try {
      // ⚠️ ganti URL ini sesuai route getLogistik kamu
      const res = await fetch(`/jalur_distribusi_logistik/get-logistik`);
      const json = await res.json();

      (json.data || []).forEach((item) => {
        const lat = parseFloat(item.lat);
        const lng = parseFloat(item.lng); // pastikan getLogistik return "lng"
        if (isNaN(lat) || isNaN(lng)) return;

        const warna = warnaLogistik[item.jenis_logistik] ?? "#2563eb";

        const marker = L.circleMarker([lat, lng], {
          radius: 9,
          fillColor: warna,
          fillOpacity: 0.85,
          color: "#ffffff",
          weight: 1,
        });

        marker.bindPopup(`
          <strong>${item.nama_lokasi ?? "-"}</strong><br>
          Kecamatan: ${item.nama_kecamatan ?? "-"}<br>
          Desa: ${item.nama_desa ?? "-"}<br>
          Jenis: ${item.jenis_logistik ?? "-"}<br>
          Jumlah: ${item.jumlah ?? "-"} ${item.satuan ?? ""}<br>
          Status: ${item.status ?? "-"}
        `);

        marker.on("click", () => fillForm(item));
        layerLogistik.addLayer(marker);
      });
    } catch (err) {
      console.error("Gagal load logistik:", err);
    }
  }

  /* ===============================
   * FILL FORM (EDIT MODE)
   * =============================== */
  function fillForm(item) {
    formMode = "edit";

    // id hidden input (kamu perlu input hidden id di form)
    const idEl = document.getElementById("logistik_id");
    if (idEl) idEl.value = item.id;

    document.getElementById("nama_lokasi").value = item.nama_lokasi ?? "";
    document.getElementById("jenis_logistik").value = item.jenis_logistik ?? "";
    document.getElementById("jumlah").value = item.jumlah ?? "";
    document.getElementById("satuan").value = item.satuan ?? "";
    document.getElementById("status").value = item.status ?? "";

    // posisi map (pakai item.lat/lng)
    const lat = parseFloat(item.lat);
    const lng = parseFloat(item.lng);

    // isi input latitude/longitude form
    const latEl = document.getElementById("latitude");
    const lngEl = document.getElementById("longitude");
    if (latEl) latEl.value = isNaN(lat) ? "" : lat.toFixed(6);
    if (lngEl) lngEl.value = isNaN(lng) ? "" : lng.toFixed(6);

    // Kecamatan (select2 friendly)
    const districtSelect = document.getElementById("district_id");
    const villageSelect = document.getElementById("village_id");

    if (districtSelect) {
      const districtOption = new Option(
        item.nama_kecamatan ?? "Kecamatan",
        item.district_id,
        true,
        true
      );
      $("#district_id").append(districtOption).trigger("change");
    }

    // Desa (tunggu dependent dropdown selesai)
    setTimeout(() => {
      if (villageSelect) {
        const villageOption = new Option(
          item.nama_desa ?? "Desa",
          item.village_id,
          true,
          true
        );
        $("#village_id").append(villageOption).trigger("change");
      }
    }, 300);

    // marker input draggable
    inputLayer.clearLayers();
    if (!isNaN(lat) && !isNaN(lng)) {
      inputMarker = L.marker([lat, lng], { draggable: true }).addTo(inputLayer);

      inputMarker.on("dragend", (ev) => {
        const pos = ev.target.getLatLng();
        if (latEl) latEl.value = pos.lat.toFixed(6);
        if (lngEl) lngEl.value = pos.lng.toFixed(6);
      });

      MapState.map.setView([lat, lng], 15);
    }
  }

  function getFormData() {
    return {
      id: document.getElementById("logistik_id")?.value || null,
      nama_lokasi: document.getElementById("nama_lokasi").value,
      district_id: document.getElementById("district_id").value,
      village_id: document.getElementById("village_id").value,
      jenis_logistik: document.getElementById("jenis_logistik").value,
      jumlah: document.getElementById("jumlah").value,
      satuan: document.getElementById("satuan").value,
      status: document.getElementById("status").value,
      // optional: kalau kamu mau simpan koordinat sendiri
      latitude: document.getElementById("latitude")?.value,
      longitude: document.getElementById("longitude")?.value,
    };
  }

  /* ===============================
   * SUBMIT LOGISTIK (CREATE/EDIT)
   * =============================== */
  window.submitLogistik = async function () {
    const data = getFormData();
    const isEdit = !!data.id;

    // ⚠️ sesuaikan base url CRUD logistik kamu
    const url = isEdit
      ? `/jalur_distribusi_logistik/${data.id}`
      : `/jalur_distribusi_logistik`;
    const method = isEdit ? "PUT" : "POST";

    try {
      const res = await fetch(url, {
        method,
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": getCsrf(),
          Accept: "application/json",
        },
        body: JSON.stringify(data),
      });

      if (!res.ok) throw await res.json();

      alert(isEdit ? "Data berhasil diupdate" : "Data berhasil ditambahkan");
      resetForm();
      loadLogistik();
    } catch (err) {
      console.error("Gagal simpan:", err);
      alert("Gagal menyimpan data");
    }
  };

  /* ===============================
   * DELETE (optional)
   * =============================== */
  window.deleteLogistik = async function (id) {
    if (!confirm("Yakin hapus data logistik ini?")) return;

    try {
      const res = await fetch(`/jalur_distribusi_logistik/${id}`, {
        method: "DELETE",
        headers: {
          "X-CSRF-TOKEN": getCsrf(),
          Accept: "application/json",
        },
      });

      if (!res.ok) throw await res.json();

      alert("Data berhasil dihapus");
      resetForm();
      loadLogistik();
    } catch (err) {
      console.error("Gagal hapus:", err);
      alert("Gagal menghapus data");
    }
  };

  function switchToCreateLogistik(lat = null, lng = null) {
    formMode = "create";

    const idEl = document.getElementById("logistik_id");
    if (idEl) idEl.value = "";

    document.querySelector("form")?.reset();

    $("#district_id").val(null).trigger("change");
    $("#village_id").empty().trigger("change");

    inputLayer.clearLayers();

    const latEl = document.getElementById("latitude");
    const lngEl = document.getElementById("longitude");

    if (lat != null && lng != null) {
      if (latEl) latEl.value = lat;
      if (lngEl) lngEl.value = lng;

      inputMarker = L.marker([lat, lng], { draggable: true }).addTo(inputLayer);
      inputMarker.on("dragend", (ev) => {
        const pos = ev.target.getLatLng();
        if (latEl) latEl.value = pos.lat.toFixed(6);
        if (lngEl) lngEl.value = pos.lng.toFixed(6);
      });
    }
  }

  function resetForm() {
    const idEl = document.getElementById("logistik_id");
    if (idEl) idEl.value = "";

    document.querySelector("form")?.reset();

    $("#district_id").val(null).trigger("change");
    $("#village_id").empty().trigger("change");

    inputLayer.clearLayers();
  }

  /* ===============================
   * Klik map -> isi latitude/longitude + marker input
   * =============================== */
  MapState.map.on("click", (e) => {
    if (formMode === "edit") {
      switchToCreateLogistik(
        e.latlng.lat.toFixed(6),
        e.latlng.lng.toFixed(6)
      );
      return;
    }

    const lat = e.latlng.lat.toFixed(6);
    const lng = e.latlng.lng.toFixed(6);

    const latEl = document.getElementById("latitude");
    const lngEl = document.getElementById("longitude");
    if (latEl) latEl.value = lat;
    if (lngEl) lngEl.value = lng;

    inputLayer.clearLayers();

    inputMarker = L.marker([lat, lng], { draggable: true }).addTo(inputLayer);
    inputMarker.on("dragend", (ev) => {
      const pos = ev.target.getLatLng();
      if (latEl) latEl.value = pos.lat.toFixed(6);
      if (lngEl) lngEl.value = pos.lng.toFixed(6);
    });
  });

  // initial load
  loadLogistik();
});

});
