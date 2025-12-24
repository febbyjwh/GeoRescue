document.addEventListener('DOMContentLoaded', () => {

    if (!window.MapState) return console.error('MapState belum diinisialisasi!');

    const { map, layers, drawnItems } = MapState;
    const formContainer = document.querySelector('[data-form="jalur"]');
    const form = document.getElementById('jalurForm');
    const namaInput = document.getElementById('nama_jalur');
    const deskripsiInput = document.getElementById('deskripsi');
    const geojsonInput = document.getElementById('geojsonInput');

    if (!formContainer || !form || !namaInput || !deskripsiInput || !geojsonInput) return;

    // Fungsi switch ke create
    function switchToCreate() {
        namaInput.value = '';
        deskripsiInput.value = '';
        geojsonInput.value = '';
        form.action = '/jalur_evakuasi';
        const methodInput = document.getElementById('_method');
        if (methodInput) methodInput.remove();
        drawnItems.clearLayers();
        formContainer.classList.remove('hidden');
    }

    // Fungsi edit jalur
    function openEditJalur(feature) {
        const allForms = document.querySelectorAll('.form-item');
        allForms.forEach(f => f.classList.add('hidden'));

        // tampilkan form jalur
        formContainer.classList.remove('hidden');

         // Hide placeholder
        const formPlaceholder = document.getElementById('formPlaceholder');
        if (formPlaceholder) formPlaceholder.classList.add('hidden');

        // Switch selector ke jalur
        const selector = document.querySelector('select[name="jenis_data"]');
        if (selector) selector.value = 'jalur';
        formContainer.classList.remove('hidden');
        namaInput.value = feature.properties.Nama ?? '';
        deskripsiInput.value = feature.properties.Deskripsi ?? '';
        form.action = `/jalur_evakuasi/${feature.properties.id}`;

        if (!document.getElementById('_method')) {
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.id = '_method';
            methodInput.value = 'PUT';
            form.appendChild(methodInput);
        }

        drawnItems.clearLayers();
        L.geoJSON(feature).eachLayer(layer => drawnItems.addLayer(layer));
    }

    // Load semua jalur
    let currentlyEditingLayer = null;

    fetch('/jalur_evakuasi/geojson/jalur-evakuasi')
        .then(res => res.json())
        .then(data => {
            L.geoJSON(data, {
                style: { color: '#dc2626', weight: 5 },
                onEachFeature: (feature, layer) => {
                    layer.bindPopup(`<b>${feature.properties.Nama}</b>`);

                    layer.on('click', () => {

                    MapState.layers.jalur.eachLayer(l => l.setStyle({ color: '#dc2626' }));

                    drawnItems.clearLayers();
                    const clickedLayer = L.geoJSON(feature, {
                        style: { color: '#facc15', weight: 5 }
                    });
                    clickedLayer.eachLayer(l => drawnItems.addLayer(l));
                    currentlyEditingLayer = clickedLayer;
                    openEditJalur(feature);
                    });

                    MapState.layers.jalur.addLayer(layer);
                }
            });
        })
        .catch(err => console.error('Gagal load jalur:', err));

    // Klik di map, switch ke create
    map.on('click', (e) => {
        if (['CANVAS','DIV'].includes(e.originalEvent.target.tagName)) {
            switchToCreate();
        }
    });

    // Auto edit dari controller
    if (window.EDIT_JALUR_ID) {
        fetch(`/jalur_evakuasi/${window.EDIT_JALUR_ID}/geojson`)
            .then(res => res.json())
            .then(feature => {
                openEditJalur(feature);
                map.fitBounds(L.geoJSON(feature).getBounds(), { padding: [50, 50] });
            })
            .catch(err => console.error('Gagal load jalur untuk edit:', err));
    }
});