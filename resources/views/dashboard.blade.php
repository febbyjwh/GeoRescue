@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12 space-y-6 xl:col-span-7">
            <x-ecommerce.ecommerce-metrics 
            :totalBencana="$totalBencana" 
            :bencanaTinggi="$bencanaTinggi" 
            :totalPosko="$totalPosko"
            :poskoAktif="$poskoAktif"
            :poskoPenuh="$poskoPenuh"
            :poskoTutup="$poskoTutup"
            :totalFasilitas="$totalFasilitas"
            :fasilitasBeroperasi="$fasilitasBeroperasi"
            :fasilitasTidakTersedia="$fasilitasTidakTersedia"
            />
            {{-- <x-ecommerce.monthly-sale :bencanaPerBulan="$bencanaPerBulan" /> --}}
        </div>
        <div class="col-span-12 xl:col-span-5">
           <x-ecommerce.monthly-target :jenisBencana="$jenisBencana" />
        </div>

        <div class="col-span-12">
            <x-ecommerce.statistics-chart 
            :bencanaPerBulan="$bencanaPerBulan"
            :start="$start"
            :end="$end"
             />
        </div>

        {{-- <div class="col-span-12 xl:col-span-5">
            <x-ecommerce.customer-demographic />
        </div>

        <div class="col-span-12 xl:col-span-7">
            <x-ecommerce.recent-orders />
        </div> --}}
    </div>
@endsection
