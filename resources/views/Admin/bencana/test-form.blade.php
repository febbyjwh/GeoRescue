@extends('layouts.app')

@section('content')

<x-common.page-breadcrumb pageTitle="Form Elements Preview" />

<div class="max-w-lg bg-white p-6 rounded-lg shadow">

    <form>

        <x-form.form-elements.select-inputs
            name="jenis_bencana"
            label="Jenis Bencana"
            required
        >
            <option value="">-- Pilih --</option>
            <option value="Banjir">Banjir</option>
            <option value="Longsor">Longsor</option>
            <option value="Gempa">Gempa</option>
        </x-form.form-elements.select-inputs>

    </form>

</div>

@endsection
