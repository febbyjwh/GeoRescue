@props(['logistikStatus'])

@php
    $tersedia = $logistikStatus['Tersedia'] ?? 0;
    $menipis = $logistikStatus['Menipis'] ?? 0;
    $habis = $logistikStatus['Habis'] ?? 0;

    $total = $tersedia + $menipis + $habis;

    $persenHabis = $total > 0 ? ($habis / $total) * 100 : 0;
    $persenMenipis = $total > 0 ? ($menipis / $total) * 100 : 0;

    $critical = $persenHabis >= 40;
    $siaga = !$critical && $persenMenipis >= 40;
    $warning = !$critical && !$siaga && $persenMenipis >= 20;
@endphp

<div class="relative flex flex-col rounded-xl bg-white bg-clip-border text-gray-700 shadow-md">
    <!-- Header -->
    <div class="relative mx-4 mt-4 flex flex-col gap-4 md:flex-row md:items-center">
        <!-- Icon with visual effect -->
        <div
            class="w-max rounded-lg p-5 text-white
            @if ($critical) bg-red-600
            @elseif($warning)
                bg-yellow-500
            @elseif($siaga)
                bg-orange-500
            @else
                bg-green-600 @endif
        ">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
            </svg>
        </div>

        <!-- Title -->
        <div>
            <h6 class="text-base font-semibold text-blue-gray-900">
                Status Logistik
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
                    Aman
                </span>
            @endif

            <p class="mt-1 text-sm text-gray-700">
                Ketersediaan logistik saat ini
            </p>
        </div>
    </div>

    <!-- Chart -->
    <div class="py-6 mt-4 grid place-items-center px-2">
        <div id="pie-logistik"></div>
    </div>
</div>

<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const rawData = {!! json_encode($logistikStatus) !!};
        const statusOrder = ['Tersedia', 'Menipis', 'Habis'];

        const labels = statusOrder.filter(s => rawData[s] !== undefined);
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
                "#34D399", // Tersedia (green)
                "#FBBF24", // Menipis (yellow)
                "#F87171", // Habis (red)
            ],
        };

        new ApexCharts(
            document.querySelector("#pie-logistik"),
            options
        ).render();
    });
</script>
