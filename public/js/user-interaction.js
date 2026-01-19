let userLocation = null;
let isAddCustomActive = false;
let customMarker = null;
let gpsMarker = null;
let isUsingCustomLocation = false;

function hitungJarak(lat1, lng1, lat2, lng2) {
    const R = 6371;
    const dLat = ((lat2 - lat1) * Math.PI) / 180;
    const dLng = ((lng2 - lng1) * Math.PI) / 180;

    const a =
        Math.sin(dLat / 2) ** 2 +
        Math.cos((lat1 * Math.PI) / 180) *
            Math.cos((lat2 * Math.PI) / 180) *
            Math.sin(dLng / 2) ** 2;

    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}

function getFasilitasIcon(jenis) {
    const icons = {
        "Rumah Sakit": "🏥",
        Puskesmas: "🩺",
        Sekolah: "🏫",
        "Kantor Polisi": "🚔",
        "Pemadam Kebakaran": "🚒",
        "Kantor Pemerintahan": "🏛️",
    };
    return icons[jenis] || "📍";
}

// ================= CUSTOM LOCATION =================
function saveCustomLocation(lat, lng) {
    userLocation = { lat, lng };
    isUsingCustomLocation = true;

    // hapus marker GPS
    if (gpsMarker && window.mapInstance) {
        window.mapInstance.removeLayer(gpsMarker);
        gpsMarker = null;
    }
}

function initCustomLocation(map) {
    const addCustomBtn = document.getElementById("addCustomLocation");
    if (!addCustomBtn) return;

    map.on("click", (e) => {
        if (!isAddCustomActive) return;

        if (customMarker) {
            map.removeLayer(customMarker);
        }

        saveCustomLocation(e.latlng.lat, e.latlng.lng);

        customMarker = L.marker(e.latlng)
            .addTo(map)
            .bindPopup("Lokasi Saya")
            .openPopup();

        isAddCustomActive = false;

        addCustomBtn.classList.remove("bg-amber-500");
        addCustomBtn.textContent = "📍 Tambah Lokasi Saya";

        console.log("📍 Custom location set:", userLocation);

        if (window.updateAllPopups) window.updateAllPopups();
        renderNearby();
    });

    addCustomBtn.addEventListener("click", () => {
        isAddCustomActive = !isAddCustomActive;

        if (isAddCustomActive) {
            addCustomBtn.classList.add("bg-amber-500");
            addCustomBtn.textContent = "Klik Peta untuk Menentukan Lokasi";
        } else {
            addCustomBtn.classList.remove("bg-amber-500");
            addCustomBtn.textContent = "📍 Tambah Lokasi Saya";
        }
    });
}

function initGPSLocation(map) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                if (!isUsingCustomLocation) {
                    userLocation = {
                        lat: pos.coords.latitude,
                        lng: pos.coords.longitude,
                    };

                    console.log("🗺️ GPS Location obtained:", userLocation);

                    if (window.updateAllPopups) window.updateAllPopups();
                    renderNearby();
                }

                gpsMarker = L.circleMarker(
                    [userLocation.lat, userLocation.lng],
                    {
                        radius: 8,
                        color: "#2563eb",
                        fillColor: "#3b82f6",
                        fillOpacity: 0.9,
                    }
                )
                    .addTo(map)
                    .bindPopup("Lokasi Anda");
            },
            () => console.warn("Izin lokasi ditolak")
        );
    }
}

function getStatusClass(type, status) {
    if (type === "logistik") {
        if (status === "Tersedia") {
            return "bg-green-100 text-green-800";
        }
        if (status === "Menipis") {
            return "bg-orange-100 text-orange-800";
        }
        if (status === "Habis") {
            return "bg-red-100 text-red-800";
        }
        return "bg-gray-100 text-gray-800";
    }

    if (status === "Aktif" || status === "Beroperasi") {
        return "bg-green-100 text-green-800";
    }

    return "bg-red-100 text-red-800";
}

// ================= NEARBY LIST =================
function renderNearby() {
    const list = document.getElementById("nearby-list");
    const emptyMsg = document.getElementById("nearby-empty");

    if (!list) return;

    if (!userLocation) {
        list.innerHTML = "";
        if (emptyMsg) emptyMsg.style.display = "block";
        return;
    }

    if (emptyMsg) emptyMsg.style.display = "none";
    list.innerHTML = "";

    if (!window.layerPosko || !window.layerFasilitas) {
        console.warn("Layers belum siap");
        return;
    }

    const poskoLocations = window.layerPosko.getLayers().map((m) => {
        const data = m.options.markerData || {};
        return {
            nama: data.nama_posko || "Posko Evakuasi",
            status: data.status_posko || "-",
            lat: m.getLatLng().lat,
            lng: m.getLatLng().lng,
            type: "posko",
            icon: "🏕️",
            data: data,
        };
    });

    const fasilitasLocations = window.layerFasilitas.getLayers().map((m) => {
        const data = m.options.markerData || {};
        return {
            nama: data.nama_fasilitas || "Fasilitas",
            status: data.status || "-",
            lat: m.getLatLng().lat,
            lng: m.getLatLng().lng,
            type: "fasilitas",
            icon: getFasilitasIcon(data.jenis_fasilitas),
            data: data,
        };
    });

    const logistikLocations = window.layerLogistik
        ? window.layerLogistik.getLayers().map((m) => {
              const data = m.options.markerData || {};
              return {
                  nama: data.nama_lokasi || "Logistik",
                  status: data.status || "-",
                  lat: m.getLatLng().lat,
                  lng: m.getLatLng().lng,
                  type: "logistik",
                  icon: "📦",
                  data: data,
              };
          })
        : [];

    const allLocations = [
        ...poskoLocations,
        ...fasilitasLocations,
        ...logistikLocations,
    ];
    const MAX_DISTANCE_KM = 10;

    const nearbyItems = allLocations
        .map((l) => ({
            ...l,
            jarak: hitungJarak(
                userLocation.lat,
                userLocation.lng,
                l.lat,
                l.lng
            ),
        }))
        .filter((l) => l.jarak <= MAX_DISTANCE_KM)
        .sort((a, b) => a.jarak - b.jarak)
        .slice(0, 10);

    if (nearbyItems.length === 0) {
        list.innerHTML = `
            <li class="text-sm text-gray-500 text-center py-4">
                Tidak ada lokasi dalam radius 10 km
            </li>
        `;
        return;
    }

    nearbyItems.forEach((l) => {
        const li = document.createElement("li");
        li.className =
            "p-3 hover:bg-gray-50 rounded-lg cursor-pointer border border-gray-200 transition";

        const statusClass = getStatusClass(l.type, l.status);

        li.innerHTML = `
            <div class="flex items-start gap-2">
                <span class="text-lg">${l.icon}</span>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2 mb-1">
                        <strong class="text-sm font-semibold text-gray-800 truncate">
                            ${l.nama}
                        </strong>
                        <span class="inline-block px-1.5 py-0.5 text-xs rounded whitespace-nowrap ${statusClass}">
                            ${l.status}
                        </span>
                    </div>
                    <div class="text-xs text-gray-600 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="font-medium text-amber-500">${l.jarak.toFixed(
                            2
                        )} km</span>
                    </div>
                </div>
            </div>
        `;

        li.onclick = () => {
            if (!window.mapInstance) return;

            window.mapInstance.setView([l.lat, l.lng], 16);

            if (l.type === "posko") {
                window.layerPosko.eachLayer((marker) => {
                    const mLatLng = marker.getLatLng();
                    if (mLatLng.lat === l.lat && mLatLng.lng === l.lng) {
                        marker.openPopup();
                    }
                });
            } else if (l.type === "fasilitas") {
                window.layerFasilitas.eachLayer((marker) => {
                    const mLatLng = marker.getLatLng();
                    if (mLatLng.lat === l.lat && mLatLng.lng === l.lng) {
                        marker.openPopup();
                    }
                });
            } else if (l.type === "logistik") {
                window.layerLogistik.eachLayer((marker) => {
                    const mLatLng = marker.getLatLng();
                    if (mLatLng.lat === l.lat && mLatLng.lng === l.lng) {
                        marker.openPopup();
                    }
                });
            }
        };

        list.appendChild(li);
    });
}

function setupSearch(map) {
    const searchInput = document.getElementById("searchLocation");
    const searchResults = document.getElementById("searchResults");

    if (!searchInput) {
        console.warn("Search input tidak ditemukan");
        return;
    }

    let searchTimeout;

    searchInput.addEventListener("input", (e) => {
        clearTimeout(searchTimeout);

        const query = e.target.value.trim().toLowerCase();

        if (query.length < 2) {
            searchResults.classList.add("hidden");
            searchResults.innerHTML = "";
            return;
        }

        searchTimeout = setTimeout(() => {
            performSearch(query, map);
        }, 300);
    });

    document.addEventListener("click", (e) => {
        if (
            !searchInput.contains(e.target) &&
            !searchResults.contains(e.target)
        ) {
            searchResults.classList.add("hidden");
        }
    });
}

function performSearch(query, map) {
    const searchResults = document.getElementById("searchResults");

    if (!window.layerPosko || !window.layerFasilitas || !window.layerLogistik) {
        console.warn("Layers belum siap untuk search");
        return;
    }

    const poskoResults = window.layerPosko
        .getLayers()
        .map((m) => {
            const data = m.options.markerData || {};
            return {
                nama: data.nama_posko || "Posko Evakuasi",
                kecamatan: data.nama_kecamatan || "",
                desa: data.nama_desa || "",
                status: data.status_posko || "-",
                lat: m.getLatLng().lat,
                lng: m.getLatLng().lng,
                type: "posko",
                icon: "🏕️",
                marker: m,
            };
        })
        .filter(
            (item) =>
                item.nama.toLowerCase().includes(query) ||
                item.kecamatan.toLowerCase().includes(query) ||
                item.desa.toLowerCase().includes(query)
        );

    const fasilitasResults = window.layerFasilitas
        .getLayers()
        .map((m) => {
            const data = m.options.markerData || {};
            return {
                nama: data.nama_fasilitas || "Fasilitas",
                jenis: data.jenis_fasilitas || "",
                kecamatan: data.nama_kecamatan || "",
                desa: data.nama_desa || "",
                status: data.status || "-",
                lat: m.getLatLng().lat,
                lng: m.getLatLng().lng,
                type: "fasilitas",
                icon: getFasilitasIcon(data.jenis_fasilitas),
                marker: m,
            };
        })
        .filter(
            (item) =>
                item.nama.toLowerCase().includes(query) ||
                item.jenis.toLowerCase().includes(query) ||
                item.kecamatan.toLowerCase().includes(query) ||
                item.desa.toLowerCase().includes(query)
        );

    const logistikResults = window.layerLogistik
        .getLayers()
        .map((m) => {
            const data = m.options.markerData || {};
            return {
                nama: data.nama_lokasi || "Logistik",
                jenis: data.jenis_logistik || "",
                status: data.status || "-",
                kecamatan: data.nama_kecamatan || "",
                desa: data.nama_desa || "",
                lat: m.getLatLng().lat,
                lng: m.getLatLng().lng,
                type: "logistik",
                icon: "📦",
                marker: m,
            };
        })
        .filter(
            (item) =>
                item.nama.toLowerCase().includes(query) ||
                item.jenis.toLowerCase().includes(query) ||
                item.kecamatan.toLowerCase().includes(query) ||
                item.desa.toLowerCase().includes(query)
        );

    const allResults = [
        ...poskoResults,
        ...fasilitasResults,
        ...logistikResults,
    ];

    if (allResults.length === 0) {
        searchResults.innerHTML = `
            <div class="p-3 text-sm text-gray-500 text-center">
                Tidak ditemukan hasil untuk "${query}"
            </div>
        `;
        searchResults.classList.remove("hidden");
        return;
    }

    searchResults.innerHTML = allResults
        .slice(0, 8)
        .map((item) => {
            const statusClass = getStatusClass(item.type, item.status);

            const jarak = userLocation
                ? hitungJarak(
                      userLocation.lat,
                      userLocation.lng,
                      item.lat,
                      item.lng
                  ).toFixed(2)
                : null;

            return `
            <div class="search-result-item p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0" 
                 data-lat="${item.lat}" 
                 data-lng="${item.lng}" 
                 data-type="${item.type}">
                <div class="flex items-start gap-2">
                    <span class="text-lg">${item.icon}</span>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <strong class="text-sm font-semibold text-gray-800">
                                ${item.nama}
                            </strong>
                            <span class="inline-block px-1.5 py-0.5 text-xs rounded ${statusClass}">
                                ${item.status}
                            </span>
                        </div>
                        ${
                            item.kecamatan
                                ? `<div class="text-xs text-gray-600">${
                                      item.kecamatan
                                  }${item.desa ? ", " + item.desa : ""}</div>`
                                : ""
                        }
                        ${
                            jarak
                                ? `<div class="text-xs text-blue-600 font-medium mt-1">📍 ${jarak} km</div>`
                                : ""
                        }
                    </div>
                </div>
            </div>
        `;
        })
        .join("");

    searchResults.classList.remove("hidden");

    searchResults.querySelectorAll(".search-result-item").forEach((item) => {
        item.addEventListener("click", () => {
            const lat = parseFloat(item.dataset.lat);
            const lng = parseFloat(item.dataset.lng);
            const type = item.dataset.type;

            map.setView([lat, lng], 16);

            if (type === "posko") {
                window.layerPosko.eachLayer((marker) => {
                    const mLatLng = marker.getLatLng();
                    if (mLatLng.lat === lat && mLatLng.lng === lng) {
                        marker.openPopup();
                    }
                });
            } else if (type === "fasilitas") {
                window.layerFasilitas.eachLayer((marker) => {
                    const mLatLng = marker.getLatLng();
                    if (mLatLng.lat === lat && mLatLng.lng === lng) {
                        marker.openPopup();
                    }
                });
            } else if (type === "logistik") {
                window.layerLogistik.eachLayer((marker) => {
                    const mLatLng = marker.getLatLng();
                    if (mLatLng.lat === lat && mLatLng.lng === lng) {
                        marker.openPopup();
                    }
                });
            }

            searchResults.classList.add("hidden");
            document.getElementById("searchLocation").value = "";
        });
    });
}

function initUserInteraction(map) {
    initCustomLocation(map);
    initGPSLocation(map);
    setupSearch(map);
}

window.getUserLocation = () => userLocation;
window.renderNearby = renderNearby;
window.initUserInteraction = initUserInteraction;
