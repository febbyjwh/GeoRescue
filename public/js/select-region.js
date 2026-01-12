function initDistrictVillageSelect(districtSelector, villageSelector) {

    $(districtSelector).select2({
        width: '100%',
        placeholder: 'Pilih Kecamatan',
        ajax: {
            url: '/api/region/districts',
            dataType: 'json',
            delay: 250,
            data: params => ({
                q: params.term
            }),
            processResults: data => ({
                results: data.map(item => ({
                    id: item.id,
                    text: item.name
                }))
            })
        }
    });

    $(villageSelector).select2({
        width: '100%',
        placeholder: 'Pilih Desa',
        ajax: {
            url: '/api/region/villages',
            dataType: 'json',
            delay: 250,
            data: params => ({
                q: params.term,
                district_id: $(districtSelector).val()
            }),
            processResults: data => ({
                results: data.map(item => ({
                    id: item.id,
                    text: item.name
                }))
            })
        }
    });

    $(districtSelector).on('change', function () {
        $(villageSelector).val(null).trigger('change');
    });
}
