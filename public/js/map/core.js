window.MapState = {
    map: null,
    layers: {
        bencana: L.featureGroup(),
        jalur: L.featureGroup(),
        posko: L.featureGroup(),
        fasilitas: L.featureGroup(),
        logistik: L.featureGroup()
    },
    drawnItems: new L.FeatureGroup()
};