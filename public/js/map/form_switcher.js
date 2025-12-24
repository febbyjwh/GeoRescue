document.addEventListener('DOMContentLoaded', () => {

    const selector = document.querySelector('select[name="jenis_data"]');
    const forms = document.querySelectorAll('.form-item');
    const placeholder = document.getElementById('formPlaceholder');

    if (!selector) return;

    selector.addEventListener('change', () => {

        if (placeholder) placeholder.style.display = 'none';

        forms.forEach(f => f.classList.add('hidden'));

        const active = document.querySelector(
            `.form-item[data-form="${selector.value}"]`
        );

        if (active) active.classList.remove('hidden');
    });
});
