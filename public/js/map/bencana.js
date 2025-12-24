document.addEventListener("DOMContentLoaded", () => {
    fetch("/bencana/get-bencana")
        .then((r) => r.json())
        .then((data) => {
            let res = data.data;

            const warnaBencana = {
                Banjir: "#1E40AF",
                Longsor: "#F59E0B",
                Gempa: "#10B981",
            };

            res.forEach((item) => {
                const lat = parseFloat(item.lat);
                const lng = parseFloat(item.lang);

                if (isNaN(lat) || isNaN(lng)) return;

                const warna = warnaBencana[item.nama_bencana] ?? "#2563eb";

                const marker = L.circleMarker([lat, lng], {
                    radius: 10,
                    fillColor: warna,
                    fillOpacity: 0.8,
                    color: "#ffffff",
                    weight: 1,
                });

                marker.bindPopup(`
                    <strong>${item.nama_bencana}</strong><br>
                    Kecamatan: ${item.kecamatan ?? "-"}<br>
                    Desa: ${item.desa}<br>
                    Kerawanan: ${item.tingkat_kerawanan}
                `);

                MapState.layers.bencana.addLayer(marker);
            });
        });
});
