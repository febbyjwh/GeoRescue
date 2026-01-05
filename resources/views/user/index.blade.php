<div id="map" style="width: 100%; height: 100vh;"></div>

{{-- Leaflet CSS/JS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
document.addEventListener("DOMContentLoaded", async () => {
    // Inisialisasi map
    const map = L.map('map').setView([-6.9, 107.6], 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Layer groups
    const layerBencana = L.layerGroup().addTo(map);
    const layerPosko = L.layerGroup().addTo(map);

    // Warna per jenis bencana
    const warnaBencana = {
        Banjir: "#1E40AF",
        Longsor: "#F59E0B",
        Gempa: "#10B981"
    };

    // ==============================
    // Fetch Bencana
    // ==============================
    try {
        const resB = await fetch('/api/bencana');
        const dataB = await resB.json();
        dataB.data.forEach(item => {
            const lat = parseFloat(item.lat);
            const lng = parseFloat(item.lang);
            if (isNaN(lat) || isNaN(lng)) return;

            const warna = warnaBencana[item.nama_bencana] ?? "#2563eb";

            const marker = L.circleMarker([lat, lng], {
                radius: 8,
                fillColor: warna,
                fillOpacity: 0.8,
                color: "#fff",
                weight: 1
            }).bindPopup(`
                <strong>${item.nama_bencana}</strong><br>
                Kecamatan: ${item.nama_kecamatan}<br>
                Desa: ${item.nama_desa}<br>
                Kerawanan: ${item.tingkat_kerawanan}
            `);

            layerBencana.addLayer(marker);
        });
    } catch (err) {
        console.error("Gagal load bencana:", err);
    }

    // ==============================
    // Fetch Posko
    // ==============================
    try {
        const resP = await fetch('/api/posko');
        const dataP = await resP.json();
        dataP.data.forEach(item => {
            const lat = parseFloat(item.latitude);
            const lng = parseFloat(item.longitude);
            if (isNaN(lat) || isNaN(lng)) return;

            const marker = L.marker([lat, lng], {
                icon: L.icon({
                    iconUrl: '/images/posko-icon.png',
                    iconSize: [30, 30],
                    iconAnchor: [15, 30]
                })
            }).bindPopup(`
                <strong>${item.nama_posko}</strong><br>
                Jenis: ${item.jenis_posko}<br>
                Status: ${item.status_posko}
            `);

            layerPosko.addLayer(marker);
        });
    } catch (err) {
        console.error("Gagal load posko:", err);
    }

    // ==============================
    // Layer control
    // ==============================
    L.control.layers(null, {
        "Bencana": layerBencana,
        "Posko": layerPosko
    }).addTo(map);
});
</script>