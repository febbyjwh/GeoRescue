document.addEventListener("DOMContentLoaded", () => {
  // ===============================
  // 1) Dependent dropdown: Kecamatan -> Desa
  // ===============================
  const district = document.getElementById("district_id");
  const village = document.getElementById("village_id");

  if (district && village) {
    district.addEventListener("change", async function () {
      village.innerHTML = '<option value="">-- Pilih Desa --</option>';

      if (!this.value) return;

      const res = await fetch(
        `/jalur_distribusi_logistik/villages/${this.value}`
      );
      const json = await res.json();

      (json.data || []).forEach((v) => {
        const opt = document.createElement("option");
        opt.value = v.id;
        opt.textContent = v.name;
        village.appendChild(opt);
      });
    });
  }

  // Optional: kalau kamu pakai helper select2
  if (typeof initDistrictVillageSelect === "function") {


    initDistrictVillageSelect("#district_id", "#village_id");
  }

  // ===============================
  // 2) Leaflet setup
  // ===============================
  if (!window.MapState || !MapState.map) {
    console.error("MapState belum tersedia");
    return;
  }


  if (!MapState.layers.logistik) {
    MapState.layers.logistik = L.layerGroup().addTo(MapState.map);
  }
  const layerLogistik = MapState.layers.logistik;


  if (!MapState.layers.inputPoint) {
    MapState.layers.inputPoint = L.layerGroup().addTo(MapState.map);
  }
  const inputLayer = MapState.layers.inputPoint;

  let formMode = "create";
  let inputMarker = null;


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

  // ===============================
  // 3) Load logistik markers
  // ===============================
  async function loadLogistik() {
    layerLogistik.clearLayers();

    try {

      const res = await fetch(`/jalur_distribusi_logistik/get-logistik`);
      const json = await res.json();

      console.log("LOGISTIK JSON:", json); // debug

      (json.data || []).forEach((item) => {
        const lat = parseFloat(item.lat);
        const lng = parseFloat(item.lang); // ✅ penting: backend kirim "lang", bukan "lng"

        if (isNaN(lat) || isNaN(lng)) {
          console.warn("Koordinat invalid:", item);
          return;
        }

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

      console.log("Jumlah marker:", layerLogistik.getLayers().length);
    } catch (err) {
      console.error("Gagal load logistik:", err);
    }
  }

  // ===============================
  // 4) Fill form edit mode
  // ===============================
  function fillForm(item) {
    formMode = "edit";
  

    const idEl = document.getElementById("logistik_id");
    if (idEl) idEl.value = item.id;

    document.getElementById("nama_lokasi").value = item.nama_lokasi ?? "";
    document.getElementById("jenis_logistik").value = item.jenis_logistik ?? "";
    document.getElementById("jumlah").value = item.jumlah ?? "";
    document.getElementById("satuan").value = item.satuan ?? "";
    document.getElementById("status").value = item.status ?? "";


    const lat = parseFloat(item.lat);
    const lng = parseFloat(item.lang); // ✅ "lang"

    // ✅ input form pakai lat/lang (sesuai backend)
    const latEl = document.getElementById("lat");   // kalau masih pakai #latitude, ganti ke "latitude"
    const lngEl = document.getElementById("lang");  // kalau masih pakai #longitude, ganti ke "longitude"

    if (latEl) latEl.value = isNaN(lat) ? "" : lat.toFixed(6);
    if (lngEl) lngEl.value = isNaN(lng) ? "" : lng.toFixed(6);

    // Kecamatan (select2 friendly)
    const districtOption = new Option(
      item.nama_kecamatan ?? "Kecamatan",
      item.kecamatan_id ?? item.district_id,
      true,
      true
    );
    $("#district_id").append(districtOption).trigger("change");

    // Desa
    setTimeout(() => {
      const villageOption = new Option(
        item.nama_desa ?? "Desa",
        item.desa_id ?? item.village_id,
        true,
        true
      );
      $("#village_id").append(villageOption).trigger("change");
    }, 300);


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

      // ✅ kirim lat/lang ke backend
      lat: document.getElementById("lat")?.value,
      lang: document.getElementById("lang")?.value,
    };
  }

  // ===============================
  // 5) Submit create/edit (AJAX)
  // ===============================
  window.submitLogistik = async function () {
    const data = getFormData();
    const isEdit = !!data.id;


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

  // ===============================
  // 6) Reset / Create mode
  // ===============================
  function resetForm() {
    document.getElementById("logistik_id") && (document.getElementById("logistik_id").value = "");
    document.querySelector("form")?.reset();

    $("#district_id").val(null).trigger("change");
    $("#village_id").empty().trigger("change");

    inputLayer.clearLayers();
    formMode = "create";
  }

  // ===============================
  // 7) Klik map -> isi lat/lang + marker input
  // ===============================
  MapState.map.on("click", (e) => {
    
    const lat = e.latlng.lat.toFixed(6);
    const lng = e.latlng.lng.toFixed(6);

    const latEl = document.getElementById("lat");
    const lngEl = document.getElementById("lang");

    if (latEl) latEl.value = lat;
    if (lngEl) lngEl.value = lng;

    inputLayer.clearLayers();

    inputMarker = L.marker([lat, lng], { draggable: true }).addTo(inputLayer);
    inputMarker.on("dragend", (ev) => {
      const pos = ev.target.getLatLng();
      if (latEl) latEl.value = pos.lat.toFixed(6);
      if (lngEl) lngEl.value = pos.lng.toFixed(6);
    });

    // kalau lagi edit, klik map otomatis balik create mode biar konsisten
    if (formMode === "edit") formMode = "create";
  });

  // initial load
  loadLogistik();
});


