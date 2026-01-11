<script src="https://cdn.tailwindcss.com"></script>

<div id="map" style="width:100%; height:100vh;"></div>

{{-- Include Sidebar & Modal --}}
@include('layouts.sidebar-user')
@include('layouts/modal-user')

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
<script src="{{ asset('js/user-interaction.js') }}"></script>
<script src="{{ asset('js/user.js') }}"></script>
