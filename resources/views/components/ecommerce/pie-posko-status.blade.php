@props(['poskoStatus'])
@php
    $aktif = $poskoStatus['Aktif'] ?? 0;
    $penuh = $poskoStatus['Penuh'] ?? 0;
    $tutup = $poskoStatus['Tutup'] ?? 0;

    $total = $aktif + $penuh + $tutup;

    $persenAktif = $total > 0 ? ($aktif / $total) * 100 : 0;
    $persenPenuh = $total > 0 ? ($penuh / $total) * 100 : 0;
    $persenTutup = $total > 0 ? ($tutup / $total) * 100 : 0;

    $critical = $persenTutup >= 40;
    $siaga = !$critical && $persenPenuh >= 40;
    $warning = !$critical && !$siaga && $penuh > 0;
@endphp

<div class="relative flex flex-col rounded-xl bg-white bg-clip-border text-gray-700 shadow-md">
    <!-- Header -->
    <div class="relative mx-4 mt-4 flex flex-col gap-4 md:flex-row md:items-center">
        <!-- Icon -->
        <div
            class="w-max rounded-lg p-5 text-white
            @if ($critical) bg-red-600
            @elseif($siaga)
                bg-orange-500
            @elseif($warning)
                bg-yellow-500
            @else
                bg-green-600 @endif
        ">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
            </svg>
        </div>

        <!-- Title -->
        <div>
            <h6 class="text-base font-semibold text-blue-gray-900">
                Status Posko
            </h6>

            @if ($critical)
                <span class="mt-1 inline-block rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                    Kritis
                </span>
            @elseif($siaga)
                <span
                    class="mt-1 inline-block rounded-full bg-orange-100 px-3 py-1 text-xs font-medium text-orange-700">
                    Siaga
                </span>
            @elseif($warning)
                <span
                    class="mt-1 inline-block rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">
                    Perlu Perhatian
                </span>
            @else
                <span class="mt-1 inline-block rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                    Operasional Normal
                </span>
            @endif

            <p class="mt-1 text-sm text-gray-700">
                Ketersediaan posko bencana
            </p>
        </div>
    </div>

    <!-- Chart -->
    <div class="py-6 mt-4 grid place-items-center px-2">
        <div id="pie-posko"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const rawData = {!! json_encode($poskoStatus) !!};
        const order = ['Aktif', 'Penuh', 'Tutup'];

        const labels = order.filter(s => rawData[s] !== undefined);
        const series = labels.map(s => rawData[s]);

        if (series.length === 0) return;

        const options = {
            series: series,
            chart: {
                type: "pie",
                width: 280,
                height: 255,
                toolbar: {
                    show: false
                },
            },
            labels: labels,
            dataLabels: {
                enabled: false
            },
            legend: {
                show: true,
                position: "bottom",
                fontSize: "12px",
            },
            colors: [
                "#34D399", // Aktif
                "#FBBF24", // Penuh
                "#F87171", // Tutup
            ],
        };

        new ApexCharts(
            document.querySelector("#pie-posko"),
            options
        ).render();
    });
</script>
