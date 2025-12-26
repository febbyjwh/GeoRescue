function iconFasilitas(jenis) {

    let color = '#64748b';

    switch (jenis) {
        case 'Rumah Sakit':
            color = '#dc2626';
            break;
        case 'Puskesmas':
            color = '#16a34a';
            break;
        case 'Sekolah':
            color = '#2563eb';
            break;
        case 'Kantor Polisi':
            color = '#213448';
            break;
        case 'Pemadam Kebakaran':
            color = '#ea580c';
            break;
        case 'Kantor Pemerintahan':
            color = '#6b7280';
            break;
    }

    return L.divIcon({
        html: `
            <div style="color:${color}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/>
                </svg>
            </div>
        `,
        className: '',
        iconSize: [20, 20],
        iconAnchor: [10, 20],
        popupAnchor: [0, -20]
    });
}

document.addEventListener('DOMContentLoaded', () => {

    MapState.layers.fasilitas.clearLayers();

    fetch('/data/fasilitas-vital')
        .then(res => res.json())
        .then(data => {

            console.log('Data fasilitas:', data);

            data.forEach(f => {

                const lat = parseFloat(f.latitude);
                const lng = parseFloat(f.longitude);

                if (isNaN(lat) || isNaN(lng)) return;

                const marker = L.marker([lat, lng], {
                    icon: iconFasilitas(f.jenis_fasilitas)
                }).bindPopup(`
                    <b>${f.nama_fasilitas}</b><br>
                    Jenis: ${f.jenis_fasilitas}<br>
                    Status: ${f.status}
                `);

                MapState.layers.fasilitas.addLayer(marker);
            });

        })
        .catch(err => {
            console.error('Gagal load fasilitas:', err);
        });
});
