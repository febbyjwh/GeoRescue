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
        const userLoc = window.getUserLocation ? window.getUserLocation() : null;
        
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
        const userLoc = window.getUserLocation ? window.getUserLocation() : null;
        const jarak = userLoc
            ? hitungJarak(userLoc.lat, userLoc.lng, lat, lng).toFixed(2)
            : null;

        if (type === 'posko') {
            return `
            <div class="p-2 w-52">
                <div class="flex items-center justify-between">
                    <strong>${data.nama_posko}</strong>
                    <span class="inline-block px-2 py-0.5 text-xs rounded ${data.status_posko === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
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
                                ${data.nama_kecamatan ?? '-'}
                            </span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-gray-500 w-20 flex-shrink-0 font-medium">
                                Desa:
                            </span>
                            <span class="font-semibold text-gray-800">
                                ${data.nama_desa ?? '-'}
                            </span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-gray-500 w-20 flex-shrink-0 font-medium">
                                Alamat:
                            </span>
                            <span class="font-semibold text-gray-800">
                                ${data.alamat_posko ?? '-'}
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
        } else if (type === 'fasilitas') {
            return `
                <div class="p-2 w-52">
                <div class="flex items-center justify-between">
                    <strong>${data.nama_fasilitas}</strong>
                    <span class="inline-block px-2 py-0.5 text-xs rounded ${data.status === 'Beroperasi' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
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
                                ${data.nama_kecamatan ?? '-'}
                            </span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-gray-500 w-20 flex-shrink-0 font-medium">
                                Desa:
                            </span>
                            <span class="font-semibold text-gray-800">
                                ${data.nama_desa ?? '-'}
                            </span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-gray-500 w-20 flex-shrink-0 font-medium">
                                Alamat:
                            </span>
                            <span class="font-semibold text-gray-800">
                                ${data.alamat ?? '-'}
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
        } else if (type === 'logistik') {
            return `
                <div class="p-2 w-52">
                    <strong>${data.nama_logistik}</strong><br>
                    <span class="text-sm text-gray-600">
                        ${data.jenis_logistik}
                    </span>

                    <hr class="my-1">

                    <p class="text-sm">Jumlah: ${data.jumlah}</p>
                    <p class="text-sm">Status: ${data.status}</p>
                    <p class="text-sm">
                         Jarak: ${jarak ? jarak + " km" : "Belum diketahui"}
                    </p>

                    <button
                        class="mt-2 w-full bg-amber-300 text-black text-sm py-1 rounded"
                        onclick="window.showRoute(${lat}, ${lng})"
                    >
                        Tampilkan Rute
                    </button>
                </div>
            `;
        }
    }

    function updateAllPopups() {
        console.log(' Updating all popups...');

        layerPosko.eachLayer((marker) => {
            const latLng = marker.getLatLng();
            const markerData = marker.options.markerData;
            if (markerData) {
                const newContent = createPopupContent('posko', markerData, latLng.lat, latLng.lng);
                marker.getPopup().setContent(newContent);
            }
        });

        layerFasilitas.eachLayer((marker) => {
            const latLng = marker.getLatLng();
            const markerData = marker.options.markerData;
            if (markerData) {
                const newContent = createPopupContent('fasilitas', markerData, latLng.lat, latLng.lng);
                marker.getPopup().setContent(newContent);
            }
        });

        layerLogistik.eachLayer((marker) => {
            const latLng = marker.getLatLng();
            const markerData = marker.options.markerData;
            if (markerData) {
                const newContent = createPopupContent('logistik', markerData, latLng.lat, latLng.lng);
                marker.getPopup().setContent(newContent);
            }
        });

        console.log(' All popups updated!');
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
                icon: '🌊',
                Tinggi: {  
                    peringatan: 'BAHAYA! Wilayah rawan banjir tinggi',
                    tips: 'Hindari area ini saat hujan deras. Siapkan jalur evakuasi ke tempat tinggi.',
                    warna: '#1E40AF' 
                },
                Sedang: {
                    peringatan: 'Hati-hati! Potensi banjir cukup tinggi',
                    tips: 'Waspadai genangan air saat musim hujan. Pantau informasi cuaca.',
                    warna: '#1E40AF'
                },
                Rendah: {
                    peringatan: 'Wilayah ini jarang terkena banjir',
                    tips: 'Tetap waspada saat hujan lebat dalam waktu lama.',
                    warna: '#1E40AF'
                }
            },
            longsor: {  
                icon: '⛰️',
                Tinggi: {
                    peringatan: 'BAHAYA! Tanah sangat rawan longsor',
                    tips: 'Hindari area lereng saat hujan. Segera evakuasi jika ada retakan tanah.',
                    warna: '#F59E0B' 
                },
                Sedang: {
                    peringatan: 'Hati-hati! Area berpotensi longsor',
                    tips: 'Waspadai perubahan kondisi tanah. Jauhi lereng curam saat hujan.',
                    warna: '#F59E0B'
                },
                Rendah: {
                    peringatan: 'Wilayah ini cukup stabil',
                    tips: 'Tetap perhatikan kondisi tanah di sekitar area.',
                    warna: '#F59E0B'
                }
            },
            gempa: {  
                icon: '🏚️',
                Tinggi: {
                    peringatan: 'ZONA MERAH! Aktivitas seismik tinggi',
                    tips: 'Pastikan bangunan tahan gempa. Siapkan tas darurat dan titik kumpul.',
                    warna: '#10B981'  
                },
                Sedang: {
                    peringatan: 'Zona rawan gempa sedang',
                    tips: 'Ikuti prosedur keselamatan gempa. Hindari bangunan tua.',
                    warna: '#10B981'
                },
                Rendah: {
                    peringatan: 'Aktivitas gempa rendah',
                    tips: 'Tetap kenali jalur evakuasi dan titik aman.',
                    warna: '#10B981'
                }
            }
        };

        const defaultInfo = {
            icon: '⚠️',
            peringatan: 'Area rawan bencana',
            tips: 'Selalu waspada dan ikuti arahan petugas.',
            warna: '#6b7280'
        };

        const jenis = info[jenisBencana]; 
        if (!jenis) return { icon: defaultInfo.icon, ...defaultInfo };
        
        const tingkat = jenis[tingkatKerawanan];  
        if (!tingkat) return { icon: jenis.icon, ...defaultInfo };
        
        return { icon: jenis.icon, ...tingkat };
    }
    const warnaFasilitas = {
        'Rumah Sakit': '#dc2626',
        'Puskesmas': '#16a34a',
        'Sekolah': '#2563eb',
        'Kantor Polisi': '#213448',
        'Pemadam Kebakaran': '#ea580c',
        'Kantor Pemerintahan': '#6b7280'
    };

   try {
    const resB = await fetch("/user/bencana-data");
    const dataB = await resB.json();

    dataB.data.forEach((item) => {
        const lat = parseFloat(item.lat);
        const lng = parseFloat(item.lang);
        if (isNaN(lat) || isNaN(lng)) return;
        
        const bencanaInfo = getBencanaInfo(item.jenis_bencana, item.tingkat_kerawanan);

        const circle = L.circle([lat, lng], {
            radius: 2000,
            color: bencanaInfo.warna,
            fillColor: bencanaInfo.warna,
            fillOpacity: 0.2,
            weight: 2
        });

        const marker = L.circleMarker([lat, lng], {
            radius: 9,
            fillColor: bencanaInfo.warna,
            fillOpacity: 0.85,
            color: "#fff",
            weight: 2,
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
                        item.tingkat_kerawanan === 'Tinggi' ? 'bg-red-100 text-red-800 ring-1 ring-red-200' :
                        item.tingkat_kerawanan === 'Sedang' ? 'bg-yellow-100 text-yellow-800 ring-1 ring-yellow-200' :
                        'bg-green-100 text-green-800 ring-1 ring-green-200'
                    }">
                        ${item.tingkat_kerawanan || 'Rendah'}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-2 mb-3">
                    <!-- PERINGATAN KERAWANAN -->
                    <div class="p-2 rounded-lg ${
                        item.tingkat_kerawanan === 'Tinggi' ? 'bg-red-50 border border-red-200' :
                        item.tingkat_kerawanan === 'Sedang' ? 'bg-yellow-50 border border-yellow-200' :
                        'bg-blue-50 border border-blue-200'
                    }">
                        <p class="text-xs font-semibold ${
                            item.tingkat_kerawanan === 'Tinggi' ? 'text-red-800' :
                            item.tingkat_kerawanan === 'Sedang' ? 'text-yellow-800' :
                            'text-blue-800'
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
                            <span class="font-semibold text-gray-800">${item.status ?? '-'}</span>
                        </div>
                    <div class="flex items-start">
                        <span class="text-gray-500 w-20 flex-shrink-0 font-medium">Kecamatan:</span>
                        <span class="font-semibold text-gray-800">${item.nama_kecamatan ?? '-'}</span>
                    </div>
                    <div class="flex items-start">
                        <span class="text-gray-500 w-20 flex-shrink-0 font-medium">Desa:</span>
                        <span class="font-semibold text-gray-800">${item.nama_desa ?? '-'}</span>
                    </div>
                </div>

                <!-- TIPS KESELAMATAN -->
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-2.5 mb-3">
                    <div class="flex items-start gap-2">
                        <span class="text-amber-600 flex-shrink-0 text-sm">💡</span>
                        <div>
                            <p class="text-xs font-semibold text-amber-900 mb-1">Tips Keselamatan:</p>
                            <p class="text-xs text-amber-800 leading-relaxed">${bencanaInfo.tips}</p>
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

        console.log('✅ Loaded', dataB.data.length, 'bencana markers');
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

            const marker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: "",
                    html: `
                        <div style="
                            width: 14px;
                            height: 14px;
                            background: #7c3aed ;
                            border-radius: 50% 50% 65% 65%;
                            border: 2px solid white;
                        "></div>
                    `,
                    iconSize: [14, 14],
                    iconAnchor: [7, 7],
                }),
                markerData: item 
            });

            marker.bindPopup(createPopupContent('posko', item, lat, lng));
            layerPosko.addLayer(marker);
        });

        console.log('Loaded posko markers');
    } catch (err) {
        console.error("Gagal load posko:", err);
    }

    try {
        const resF = await fetch("/user/fasilitas-data");
        const dataF = await resF.json();

        dataF.data.forEach(item => {
            const lat = parseFloat(item.latitude);
            const lng = parseFloat(item.longitude);
            if (isNaN(lat) || isNaN(lng)) return;

            const color = warnaFasilitas[item.jenis_fasilitas] ?? '#64748b';

            const marker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: '',
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
                markerData: item
            });

            marker.bindPopup(createPopupContent('fasilitas', item, lat, lng));
            layerFasilitas.addLayer(marker);
        });

        console.log('Loaded fasilitas markers');
    } catch (err) {
        console.error("Gagal load fasilitas:", err);
    }

    try {
        const resL = await fetch("/user/logistik-data");
        const dataL = await resL.json();

        dataL.data.forEach(item => {
            const lat = parseFloat(item.latitude);
            const lng = parseFloat(item.longitude);
            if (isNaN(lat) || isNaN(lng)) return;

           const marker = L.circleMarker([lat, lng], {
                radius: 10,
                fillColor: "#FF0000",
                color: "#FF0000",
                weight: 1,
                opacity: 1,
                fillOpacity: 0.8,
                markerData: item
            });

            marker.bindPopup(createPopupContent('logistik', item, lat, lng));
            layerLogistik.addLayer(marker);
        });

        console.log('Loaded logistik markers');
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
                btn.classList.remove("bg-yellow-300", "font-semibold");
            } else {
                map.addLayer(layer);
                btn.classList.add("bg-yellow-300", "font-semibold");
            }
        });
    });

    // set kondisi awal tombol aktif
    ["bencana", "posko", "fasilitas", "logistik"].forEach((id) => {
        document.getElementById(id)?.classList.add("bg-yellow-300", "font-semibold");
    });

    if (window.initUserInteraction) {
        window.initUserInteraction(map);
        console.log('✅ User interaction module initialized');
    } else {
        console.warn('⚠️ user-interaction.js belum dimuat');
    }
});