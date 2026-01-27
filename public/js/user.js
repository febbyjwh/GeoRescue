document.addEventListener("DOMContentLoaded", async () => {
    const map = L.map("map").setView([-6.9, 107.6], 11);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors",
    }).addTo(map);

    if (!window.MapState) window.MapState = {};
    if (!MapState.layers) MapState.layers = {};
    if (!MapState.layers.kabBandung)
        MapState.layers.kabBandung = L.layerGroup();

    let routingControl = null;

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

    function showRouteTo(destLat, destLng) {
        const userLoc = window.getUserLocation
            ? window.getUserLocation()
            : null;

        if (!userLoc) {
            alert("Lokasi anda belum ditentukan");
            return;
        }

        if (routingControl) {
            map.removeControl(routingControl);
        }
        routingControl = L.Routing.control({
            waypoints: [
                L.latLng(userLoc.lat, userLoc.lng),
                L.latLng(destLat, destLng),
            ],
            router: L.Routing.osrmv1({
                serviceUrl: "https://router.project-osrm.org/route/v1",
                profile: "foot",
            }),
            routeWhileDragging: false,
            addWaypoints: false,
            draggableWaypoints: false,
            show: false,
            lineOptions: {
                styles: [{ weight: 5 }],
            },
            createMarker: () => null,
        }).addTo(map);
    }

    function createPopupContent(type, data, lat, lng) {
        const userLoc = window.getUserLocation
            ? window.getUserLocation()
            : null;
        const jarak = userLoc
            ? hitungJarak(userLoc.lat, userLoc.lng, lat, lng).toFixed(2)
            : null;

        if (type === "posko") {
            return `
            <div class="p-2 w-52">
                <div class="flex items-center justify-between">
                    <strong>${data.nama_posko}</strong>
                    <span class="inline-block px-2 py-0.5 text-xs rounded ${
                        data.status_posko === "Aktif"
                            ? "bg-green-100 text-green-800"
                            : "bg-red-100 text-red-800"
                    }">
                        ${data.status_posko}
                    </span>
                </div>
                
                <span class="text-sm text-gray-600">
                    ${data.jenis_posko}
                </span>

                    <hr class="my-1">

                    <div class="space-y-1 mt-2 text-sm bg-gray-50 p-2 rounded-lg">
                        <div class="flex items-start">
                            <span class="text-gray-500 w-20 flex-shrink-0 font-medium">
                                Kecamatan:
                            </span>
                            <span class="font-semibold text-gray-800">
                                ${data.nama_kecamatan ?? "-"}
                            </span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-gray-500 w-20 flex-shrink-0 font-medium">
                                Desa:
                            </span>
                            <span class="font-semibold text-gray-800">
                                ${data.nama_desa ?? "-"}
                            </span>
                        </div>
                    </div>

                    <button
                        class="mt-2 w-full bg-amber-300 text-black text-sm py-1 rounded"
                        onclick="window.showRoute(${lat}, ${lng})"
                    >
                        Tampilkan Rute
                    </button>

                    <div class="mt-2.5 pt-2.5 border-t border-gray-200 text-xs text-gray-400 text-center">
                        Jarak: ${jarak ? jarak + " km" : "Belum diketahui"}
                    </div>
                </div>
            `;
        } else if (type === "fasilitas") {
            return `
                <div class="p-2 w-52">
                <div class="flex items-center justify-between">
                    <strong>${data.nama_fasilitas}</strong>
                    <span class="inline-block px-2 py-0.5 text-xs rounded ${
                        data.status === "Beroperasi"
                            ? "bg-green-100 text-green-800"
                            : "bg-red-100 text-red-800"
                    }">
                        ${data.status}
                    </span>
                </div>
                
                <span class="text-sm text-gray-600">
                    ${data.jenis_fasilitas}
                </span>

                    <hr class="my-1">

                    <div class="space-y-1 mt-2 text-sm bg-gray-50 p-2 rounded-lg">
                        <div class="flex items-start">
                            <span class="text-gray-500 w-20 flex-shrink-0 font-medium">
                                Kecamatan:
                            </span>
                            <span class="font-semibold text-gray-800">
                                ${data.nama_kecamatan ?? "-"}
                            </span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-gray-500 w-20 flex-shrink-0 font-medium">
                                Desa:
                            </span>
                            <span class="font-semibold text-gray-800">
                                ${data.nama_desa ?? "-"}
                            </span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-gray-500 w-20 flex-shrink-0 font-medium">
                                Alamat:
                            </span>
                            <span class="font-semibold text-gray-800">
                                ${data.alamat ?? "-"}
                            </span>
                        </div>
                    </div>

                    <button
                        class="mt-2 w-full bg-amber-300 text-black text-sm py-1 rounded"
                        onclick="window.showRoute(${lat}, ${lng})"
                    >
                        Tampilkan Rute
                    </button>

                    <div class="mt-2.5 pt-2.5 border-t border-gray-200 text-xs text-gray-400 text-center">
                        Jarak: ${jarak ? jarak + " km" : "Belum diketahui"}
                    </div>
                </div>
            `;
        } else if (type === "logistik") {
            const userLoc = window.getUserLocation
                ? window.getUserLocation()
                : null;
            const jarak = userLoc
                ? hitungJarak(userLoc.lat, userLoc.lng, lat, lng).toFixed(2) +
                  " km"
                : "Belum diketahui";

            return `
        <div class="p-2 w-52">
            <strong>${data.nama_lokasi}</strong><br>
            <span class="text-sm text-gray-600">${data.jenis_logistik}</span>

            <hr class="my-1">

            <div class="space-y-1 text-sm bg-gray-50 p-2 rounded-lg">
                <div class="flex items-start">
                    <span class="text-gray-500 w-20 flex-shrink-0 font-medium">Kecamatan:</span>
                    <span class="font-semibold text-gray-800">${data.nama_kecamatan}</span>
                </div>
                <div class="flex items-start">
                    <span class="text-gray-500 w-20 flex-shrink-0 font-medium">Desa:</span>
                    <span class="font-semibold text-gray-800">${data.nama_desa}</span>
                </div>
                <div class="flex items-start">
                    <span class="text-gray-500 w-20 flex-shrink-0 font-medium">Jumlah:</span>
                    <span class="font-semibold text-gray-800">${data.jumlah} ${data.satuan}</span>
                </div>
                <div class="flex items-start">
                    <span class="text-gray-500 w-20 flex-shrink-0 font-medium">Status:</span>
                    <span class="font-semibold text-gray-800">${data.status}</span>
                </div>
            </div>

            <button
                class="mt-2 w-full bg-amber-300 text-black text-sm py-1 rounded"
                onclick="window.showRoute(${lat}, ${lng})"
            >
                Tampilkan Rute
            </button>

            <div class="mt-2.5 pt-2.5 border-t border-gray-200 text-xs text-gray-400 text-center">
                Jarak: ${jarak}
            </div>
        </div>
    `;
        }
    }

    function updateAllPopups() {
        console.log(" Updating all popups...");

        layerPosko.eachLayer((marker) => {
            const latLng = marker.getLatLng();
            const markerData = marker.options.markerData;
            if (markerData) {
                const newContent = createPopupContent(
                    "posko",
                    markerData,
                    latLng.lat,
                    latLng.lng
                );
                marker.getPopup().setContent(newContent);
            }
        });

        layerFasilitas.eachLayer((marker) => {
            const latLng = marker.getLatLng();
            const markerData = marker.options.markerData;
            if (markerData) {
                const newContent = createPopupContent(
                    "fasilitas",
                    markerData,
                    latLng.lat,
                    latLng.lng
                );
                marker.getPopup().setContent(newContent);
            }
        });

        layerLogistik.eachLayer((marker) => {
            const latLng = marker.getLatLng();
            const markerData = marker.options.markerData;
            if (markerData) {
                const newContent = createPopupContent(
                    "logistik",
                    markerData,
                    latLng.lat,
                    latLng.lng
                );
                marker.getPopup().setContent(newContent);
            }
        });

        console.log(" All popups updated!");
    }

    // ================= GLOBAL HANDLERS =================
    window.showRoute = function (lat, lng) {
        showRouteTo(lat, lng);
    };
    window.updateAllPopups = updateAllPopups;

    // ================= LOAD GEOJSON =================
    try {
        const res = await fetch("/js/geojson/kab-bandung.geojson");
        const geojsonData = await res.json();

        const kabPolygon = L.geoJSON(geojsonData, {
            style: {
                color: "#947519",
                weight: 2,
                fillColor: "#FFCA28",
                fillOpacity: 0.2,
            },
        });

        MapState.layers.kabBandung.clearLayers();
        MapState.layers.kabBandung.addLayer(kabPolygon).addTo(map);

        map.fitBounds(kabPolygon.getBounds());
    } catch (err) {
        console.error("Gagal load geojson Bandung:", err);
    }

    const layerBencana = L.layerGroup().addTo(map);
    const layerPosko = L.layerGroup().addTo(map);
    const layerFasilitas = L.layerGroup().addTo(map);
    const layerLogistik = L.layerGroup().addTo(map);

    const layers = {
        bencana: layerBencana,
        posko: layerPosko,
        fasilitas: layerFasilitas,
        logistik: layerLogistik,
    };

    window.mapInstance = map;
    window.layerBencana = layerBencana;
    window.layerPosko = layerPosko;
    window.layerFasilitas = layerFasilitas;
    window.layerLogistik = layerLogistik;

    function getBencanaInfo(jenisBencana, tingkatKerawanan) {
        const info = {
            banjir: {
                icon: "🌊",
                Tinggi: {
                    peringatan: "BAHAYA! Wilayah rawan banjir tinggi",
                    tips: "Hindari area ini saat hujan deras. Siapkan jalur evakuasi ke tempat tinggi.",
                    warna: "#1E40AF",
                },
                Sedang: {
                    peringatan: "Hati-hati! Potensi banjir cukup tinggi",
                    tips: "Waspadai genangan air saat musim hujan. Pantau informasi cuaca.",
                    warna: "#1E40AF",
                },
                Rendah: {
                    peringatan: "Wilayah ini jarang terkena banjir",
                    tips: "Tetap waspada saat hujan lebat dalam waktu lama.",
                    warna: "#1E40AF",
                },
            },
            longsor: {
                icon: "⛰️",
                Tinggi: {
                    peringatan: "BAHAYA! Tanah sangat rawan longsor",
                    tips: "Hindari area lereng saat hujan. Segera evakuasi jika ada retakan tanah.",
                    warna: "#F59E0B",
                },
                Sedang: {
                    peringatan: "Hati-hati! Area berpotensi longsor",
                    tips: "Waspadai perubahan kondisi tanah. Jauhi lereng curam saat hujan.",
                    warna: "#F59E0B",
                },
                Rendah: {
                    peringatan: "Wilayah ini cukup stabil",
                    tips: "Tetap perhatikan kondisi tanah di sekitar area.",
                    warna: "#F59E0B",
                },
            },
            gempa: {
                icon: "🏚️",
                Tinggi: {
                    peringatan: "ZONA MERAH! Aktivitas seismik tinggi",
                    tips: "Pastikan bangunan tahan gempa. Siapkan tas darurat dan titik kumpul.",
                    warna: "#10B981",
                },
                Sedang: {
                    peringatan: "Zona rawan gempa sedang",
                    tips: "Ikuti prosedur keselamatan gempa. Hindari bangunan tua.",
                    warna: "#10B981",
                },
                Rendah: {
                    peringatan: "Aktivitas gempa rendah",
                    tips: "Tetap kenali jalur evakuasi dan titik aman.",
                    warna: "#10B981",
                },
            },
        };

        const defaultInfo = {
            icon: "⚠️",
            peringatan: "Area rawan bencana",
            tips: "Selalu waspada dan ikuti arahan petugas.",
            warna: "#6b7280",
        };

        const jenis = info[jenisBencana];
        if (!jenis) return { icon: defaultInfo.icon, ...defaultInfo };

        const tingkat = jenis[tingkatKerawanan];
        if (!tingkat) return { icon: jenis.icon, ...defaultInfo };

        return { icon: jenis.icon, ...tingkat };
    }
    const warnaPosko = {
        kesehatan: {
            utama: "#22c55e", // hijau
            secondary: "#86efac",
        },
        evakuasi: {
            utama: "#ef4444", // merah
            secondary: "#fecaca",
        },
    };

    const warnaFasilitas = {
        "Rumah Sakit": "#dc2626",
        Puskesmas: "#16a34a",
        Sekolah: "#2563eb",
        "Kantor Polisi": "#213448",
        "Pemadam Kebakaran": "#ea580c",
        "Kantor Pemerintahan": "#6b7280",
    };

    const warnaBencana = {
        banjir: "#1E40AF",
        longsor: "#F59E0B",
        gempa: "#10B981",
    };

    try {
        const resB = await fetch("/user/bencana-data");
        const dataB = await resB.json();

        dataB.data.forEach((item) => {
            const lat = parseFloat(item.lat);
            const lng = parseFloat(item.lang);
            if (isNaN(lat) || isNaN(lng)) return;

            const bencanaInfo = getBencanaInfo(
                item.jenis_bencana,
                item.tingkat_kerawanan
            );

            const circle = L.circle([lat, lng], {
                radius: 2000,
                color: bencanaInfo.warna,
                fillColor: bencanaInfo.warna,
                fillOpacity: 0.2,
                weight: 2,
            });

            const marker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: "",
                    html: `
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="40"
                height="40"
                viewBox="0 0 297 297"
            >
                <!-- Outline putih -->
                <path
                    d="M148.5,0C85.646,0,34.511,51.136,34.511,113.989c0,25.11,8.008,48.926,23.157,68.873
                    c13.604,17.914,32.512,31.588,53.658,38.904l27.464,68.659c1.589,3.971,5.434,6.574,9.71,6.574
                    c4.276,0,8.121-2.603,9.71-6.574l27.464-68.659c21.146-7.316,40.054-20.99,53.658-38.904
                    c15.149-19.947,23.157-43.763,23.157-68.873C262.489,51.136,211.354,0,148.5,0z
                    M148.5,72.682c22.777,0,41.308,18.53,41.308,41.308c0,22.777-18.53,41.309-41.308,41.309
                    c-22.777,0-41.308-18.531-41.308-41.309C107.192,91.212,125.723,72.682,148.5,72.682z"
                    fill="none"
                    stroke="#ffffff"
                    stroke-width="30"
                    stroke-linejoin="round"
                />

                <!-- Isi warna -->
                <path
                    d="M148.5,0C85.646,0,34.511,51.136,34.511,113.989c0,25.11,8.008,48.926,23.157,68.873
                    c13.604,17.914,32.512,31.588,53.658,38.904l27.464,68.659c1.589,3.971,5.434,6.574,9.71,6.574
                    c4.276,0,8.121-2.603,9.71-6.574l27.464-68.659c21.146-7.316,40.054-20.99,53.658-38.904
                    c15.149-19.947,23.157-43.763,23.157-68.873C262.489,51.136,211.354,0,148.5,0z
                    M148.5,72.682c22.777,0,41.308,18.53,41.308,41.308c0,22.777-18.53,41.309-41.308,41.309
                    c-22.777,0-41.308-18.531-41.308-41.309C107.192,91.212,125.723,72.682,148.5,72.682z"
                    fill="${
                        warnaBencana[item.jenis_bencana?.toLowerCase()] ||
                        "#000000"
                    }"
                />
            </svg>
        `,
                    iconSize: [40, 40],
                    iconAnchor: [20, 40],
                }),
                markerData: item,
            }).bindPopup(`
            <div class="p-3 w-72 bg-white rounded-lg shadow-xl">
                <!-- HEADER: Icon + Nama Bencana + Badge Kerawanan -->
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">${bencanaInfo.icon}</span>
                        <h3 class="font-bold text-lg text-gray-800 capitalize">
                            ${item.jenis_bencana}
                        </h3>
                    </div>
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full ${
                        item.tingkat_kerawanan === "Tinggi"
                            ? "bg-red-100 text-red-800 ring-1 ring-red-200"
                            : item.tingkat_kerawanan === "Sedang"
                            ? "bg-yellow-100 text-yellow-800 ring-1 ring-yellow-200"
                            : "bg-green-100 text-green-800 ring-1 ring-green-200"
                    }">
                        ${item.tingkat_kerawanan || "Rendah"}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-2 mb-3">
                    <!-- PERINGATAN KERAWANAN -->
                    <div class="p-2 rounded-lg ${
                        item.tingkat_kerawanan === "Tinggi"
                            ? "bg-red-50 border border-red-200"
                            : item.tingkat_kerawanan === "Sedang"
                            ? "bg-yellow-50 border border-yellow-200"
                            : "bg-blue-50 border border-blue-200"
                    }">
                        <p class="text-xs font-semibold ${
                            item.tingkat_kerawanan === "Tinggi"
                                ? "text-red-800"
                                : item.tingkat_kerawanan === "Sedang"
                                ? "text-yellow-800"
                                : "text-blue-800"
                        } leading-tight">
                            ⚠️ ${bencanaInfo.peringatan}
                        </p>
                    </div>

                    <!-- RADIUS DAMPAK -->
                    <div class="p-2 bg-blue-50 border border-blue-200 rounded-lg">
                         <p class="text-xs font-semibold text-blue-800 leading-tight">
                            📍 Radius Dampak : ± 2 km </p> 
                        </div>
                    </div>
                </div>

                <!-- INFORMASI LOKASI -->
                <div class="space-y-1.5 mb-3 text-xs bg-gray-50 p-2.5 rounded-lg">
                    <div class="flex items-start">
                            <span class="text-gray-500 w-20 flex-shrink-0 font-medium">Status:</span>
                            <span class="font-semibold text-gray-800">${
                                item.status ?? "-"
                            }</span>
                        </div>
                    <div class="flex items-start">
                        <span class="text-gray-500 w-20 flex-shrink-0 font-medium">Kecamatan:</span>
                        <span class="font-semibold text-gray-800">${
                            item.nama_kecamatan ?? "-"
                        }</span>
                    </div>
                    <div class="flex items-start">
                        <span class="text-gray-500 w-20 flex-shrink-0 font-medium">Desa:</span>
                        <span class="font-semibold text-gray-800">${
                            item.nama_desa ?? "-"
                        }</span>
                    </div>
                </div>

                <!-- TIPS KESELAMATAN -->
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-2.5 mb-3">
                    <div class="flex items-start gap-2">
                        <span class="text-amber-600 flex-shrink-0 text-sm">💡</span>
                        <div>
                            <p class="text-xs font-semibold text-amber-900 mb-1">Tips Keselamatan:</p>
                            <p class="text-xs text-amber-800 leading-relaxed">${
                                bencanaInfo.tips
                            }</p>
                        </div>
                    </div>
                </div>

                <!-- KOORDINAT -->
                <div class="mt-2.5 pt-2.5 border-t border-gray-200 text-xs text-gray-400 text-center">
                    Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}
                </div>
            </div>
        `);

            layerBencana.addLayer(circle);
            layerBencana.addLayer(marker);
        });

        console.log("✅ Loaded", dataB.data.length, "bencana markers");
    } catch (err) {
        console.error("Gagal load bencana:", err);
    }

    try {
        const resP = await fetch("/user/posko-data");
        const dataP = await resP.json();

        dataP.data.forEach((item) => {
            const lat = parseFloat(item.latitude);
            const lng = parseFloat(item.longitude);
            if (isNaN(lat) || isNaN(lng)) return;

            const jenis = item.jenis_posko?.toLowerCase();
            const warnaUtama = warnaPosko[jenis]?.utama || "#F4900C";

            const marker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: "",
                    html: `
<svg

    viewBox="0 0 36 36"
    width="28"
    height="28"
    xmlns="http://www.w3.org/2000/svg"
    preserveAspectRatio="xMidYMid meet"
>
    <!-- OUTLINE PUTIH -->
    <g fill="none" stroke="#ffffff" stroke-width="1.5" stroke-linejoin="round">
        <path d="M16.812 33c-.588 0-1.112-.37-1.31-.924L8.549 12.608a1.339 1.339 0 0 1 .173-1.242c.261-.369.685-.563 1.137-.563h16.313c.557 0 1.059.305 1.279.817l8.343 19.455c.184.43.14.917-.116 1.307a1.39 1.39 0 0 1-1.163.618H16.812z"/>
        <path d="M1.515 33c-.467 0-.904-.236-1.162-.625a1.398 1.398 0 0 1-.116-1.315l8.348-19.479a1.392 1.392 0 0 1 2.557 0L19.49 31.06A1.391 1.391 0 0 1 18.212 33H1.515z"/>
        <path d="M9.859 14.182L7.077 33h5.563z"/>
    </g>

    <!-- ISI ASLI (TIDAK DIUBAH) -->
    <g>
        <!-- WARNA UTAMA (DIGANTI DINAMIS) -->
        <path
            fill="${warnaUtama}"
            d="M16.812 33c-.588 0-1.112-.37-1.31-.924L8.549 12.608a1.339 1.339 0 0 1 .173-1.242c.261-.369.685-.563 1.137-.563h16.313c.557 0 1.059.305 1.279.817l8.343 19.455c.184.43.14.917-.116 1.307a1.39 1.39 0 0 1-1.163.618H16.812z"
        />

        <!-- WARNA ASLI TETAP -->
        <path fill="#FFCC4D" d="M1.515 33c-.467 0-.904-.236-1.162-.625a1.398 1.398 0 0 1-.116-1.315l8.348-19.479a1.392 1.392 0 0 1 2.557 0L19.49 31.06A1.391 1.391 0 0 1 18.212 33H1.515z"/>
        <path fill="#292F33" d="M9.859 14.182L7.077 33h5.563z"/>
        <path fill="#FFAC33" d="M15.46 31.456L16.081 33H12.64zm-11.203 0L3.636 33h3.441z"/>
        <path fill="#FFE8B6" d="M12.64 33s2.529-.645 3.766-1.786L9.859 14.182L12.64 33zm-5.563 0s-2.529-.645-3.766-1.786l6.546-17.031L7.077 33z"/>
    </g>
</svg>
        `,
                    iconSize: [18, 18],
                    iconAnchor: [9, 18],
                }),
                markerData: item,
            });

            marker.bindPopup(createPopupContent("posko", item, lat, lng));
            layerPosko.addLayer(marker);
        });

        console.log("Loaded posko markers");
    } catch (err) {
        console.error("Gagal load posko:", err);
    }

    try {
        const resF = await fetch("/user/fasilitas-data");
        const dataF = await resF.json();

        dataF.data.forEach((item) => {
            const lat = parseFloat(item.latitude);
            const lng = parseFloat(item.longitude);
            if (isNaN(lat) || isNaN(lng)) return;

            const color = warnaFasilitas[item.jenis_fasilitas] ?? "#64748b";

            const marker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: "",
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
                    iconSize: [20, 20],
                    iconAnchor: [10, 20],
                }),
                markerData: item,
            });

            marker.bindPopup(createPopupContent("fasilitas", item, lat, lng));
            layerFasilitas.addLayer(marker);
        });

        console.log("Loaded fasilitas markers");
    } catch (err) {
        console.error("Gagal load fasilitas:", err);
    }

    try {
        const resL = await fetch("/user/logistik-data");
        const dataL = await resL.json();

        dataL.data.forEach((item) => {
            const lat = parseFloat(item.lat);
            const lng = parseFloat(item.lng);

            if (isNaN(lat) || isNaN(lng)) {
                console.warn(
                    "Skipping logistik tanpa koordinat:",
                    item.nama_lokasi
                );
                return;
            }

            const marker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: "",
                    html: `
                <svg width="20" height="20" viewBox="0 0 24 24" fill="#f59e0b">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"
                          stroke="#ffffff"
                          stroke-width="2"
                          stroke-linejoin="round"/>
                </svg>
                `,
                    iconSize: [20, 20],
                    iconAnchor: [10, 20],
                }),
                markerData: item,
            });

            marker.bindPopup(createPopupContent("logistik", item, lat, lng));
            layerLogistik.addLayer(marker);
        });

        console.log("✅ Loaded logistik markers");
    } catch (err) {
        console.error("Gagal load logistik:", err);
    }

    document.querySelectorAll(".filter-btn").forEach((btn) => {
        btn.addEventListener("click", () => {
            const id = btn.id;
            const layer = layers[id];
            if (!layer) return;

            if (map.hasLayer(layer)) {
                map.removeLayer(layer);
                btn.classList.remove("bg-yellow-200", "font-semibold");
            } else {
                map.addLayer(layer);
                btn.classList.add("bg-yellow-200", "font-semibold");
            }
        });
    });

    // set kondisi awal tombol aktif
    ["bencana", "posko", "fasilitas", "logistik"].forEach((id) => {
        document
            .getElementById(id)
            ?.classList.add("bg-yellow-200", "font-semibold");
    });

    if (window.initUserInteraction) {
        window.initUserInteraction(map);
        console.log("✅ User interaction module initialized");
    } else {
        console.warn("⚠️ user-interaction.js belum dimuat");
    }
});
