@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css">
<style>
    #map {
        width: 100%;
        height: 100vh;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>

{{-- ENTRY POINT --}}
<script src="{{ asset('js/map/core.js') }}"></script>
<script src="{{ asset('js/map/index.js') }}"></script>

{{-- FEATURES --}}
<script src="{{ asset('js/map/draw.js') }}"></script>
<script src="{{ asset('js/map/filter.js') }}"></script>
<script src="{{ asset('js/map/form_switcher.js') }}"></script>

{{-- DATA --}}
<script src="{{ asset('js/map/bencana.js') }}"></script>
<script src="{{ asset('js/map/jalur.js') }}"></script>
<script src="{{ asset('js/map/posko.js') }}"></script>
<script src="{{ asset('js/map/fasilitas.js') }}"></script>
<script src="{{ asset('js/map/logistik.js') }}"></script>
@endpush
