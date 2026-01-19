@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12 space-y-6 xl:col-span-7">
            <x-ecommerce.ecommerce-metrics :totalBencana="$totalBencana" :bencanaTinggi="$bencanaTinggi" :totalPosko="$totalPosko" :poskoAktif="$poskoAktif"
                :poskoPenuh="$poskoPenuh" :poskoTutup="$poskoTutup" :poskoStatus="$poskoStatus" :totalFasilitas="$totalFasilitas" :fasilitasBeroperasi="$fasilitasBeroperasi" :fasilitasTidakTersedia="$fasilitasTidakTersedia"
                :totalLogistik="$totalLogistik" :logistikTersedia="$logistikTersedia" :logistikMenipis="$logistikMenipis" :logistikHabis="$logistikHabis" :logistokStatus="$logistikStatus" />
            {{-- <x-ecommerce.monthly-sale :bencanaPerBulan="$bencanaPerBulan" /> --}}
        </div>
        {{-- <div class="col-span-12 xl:col-span-5">
            <x-ecommerce.monthly-target :jenisBencana="$jenisBencana" />
        </div> --}}
        <div class="col-span-12 xl:col-span-5">
            <x-ecommerce.statistics-chart :bencanaPerBulan="$bencanaPerBulan" :start="$start" :end="$end" />
        </div>

        <div class="col-span-12 xl:col-span-4">
            <x-ecommerce.tabel-logistik :topLogistikBermasalah="$topLogistikBermasalah" />
        </div>

        <div class="col-span-12 xl:col-span-4">
            <x-ecommerce.pie-logistik-status :logistikStatus="$logistikStatus" />
        </div>

        <div class="col-span-12 xl:col-span-4">
            <x-ecommerce.pie-posko-status :poskoStatus="$poskoStatus" />
        </div>

        
    </div>
@endsection
