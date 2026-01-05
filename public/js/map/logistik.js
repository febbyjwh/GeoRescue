    function iconLogistik(jenis) {

        let color = '#64748b';

        switch (jenis) {
            case 'Makanan':
                color = '#16a34a';
                break;
            case 'Obat-obatan':
                color = '#dc2626';
                break;
            case 'Air Bersih':
                color = '#2563eb';
                break;
            case 'Selimut':
                color = '#7c3aed';
                break;
            case 'Tenda':
                color = '#ea580c';
                break;
        }

        return L.divIcon({
            html: `
            <div style="
                width:16px;
                height:16px;
                border-radius:50%;
                background:${color};
                border:2px solid #000; 
                box-shadow:0 0 0 3px rgba(255,255,255,.9), 0 0 5px rgba(0,0,0,.4);
            "></div>
            `,
            className: '',
            iconSize: [16, 16],
            iconAnchor: [8, 8]
        });
    }

    document.addEventListener('DOMContentLoaded', () => {

        if (!window.MapState?.layers?.logistik) return;

        MapState.layers.logistik.clearLayers();

        // 🔥 AMBIL DARI CONTROLLER, BUKAN FILE
        fetch('/logistik/map-data')
            .then(res => res.json())
            .then(data => {

                console.log('Data logistik:', data);

                data.forEach(item => {

                    const coords = item.geojson?.coordinates;
                    if (!coords || coords.length < 2) return;

                    // GeoJSON: [lng, lat]
                    const lng = parseFloat(coords[0]);
                    const lat = parseFloat(coords[1]);

                    if (isNaN(lat) || isNaN(lng)) return;

                    const marker = L.marker([lat, lng], {
                        icon: iconLogistik(item.jenis_logistik)
                    }).bindPopup(`
                        <b>${item.nama_lokasi ?? '-'}</b><br>
                        Kecamatan: ${item.district_name ?? '-'}<br>
                        Desa: ${item.village_name ?? '-'}<br>
                        Jenis: ${item.jenis_logistik ?? '-'}<br>
                        Jumlah: ${item.jumlah ?? '-'} ${item.satuan ?? ''}<br>
                        Status: ${item.status ?? '-'}
                    `);

                    MapState.layers.logistik.addLayer(marker);
                });
            })
            .catch(err => console.error('Gagal load logistik:', err));
    });