@props([
    'jenisBencana',
])

<div class="relative flex flex-col rounded-xl bg-white bg-clip-border text-gray-700 shadow-md">
  <div class="relative mx-4 mt-4 flex flex-col gap-4 overflow-hidden rounded-none bg-transparent bg-clip-border text-gray-700 shadow-none md:flex-row md:items-center">
    <div class="w-max rounded-lg bg-gray-900 p-5 text-white">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.5"
        stroke="currentColor"
        aria-hidden="true"
        class="h-6 w-6"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3"
        ></path>
      </svg>
    </div>
    <div>
      <h6 class="block font-sans text-base font-semibold leading-relaxed tracking-normal text-blue-gray-900 antialiased">
        Bencana per Jenis
      </h6>
      <p class="block max-w-sm font-sans text-sm font-normal leading-normal text-gray-700 antialiased">
        Statistik jumlah bencana berdasarkan jenis
      </p>
    </div>
  </div>
  <div class="py-6 mt-4 grid place-items-center px-2">
    <div id="pie-chart"></div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const labels = {!! json_encode($jenisBencana->keys()) !!};
    const series = {!! json_encode($jenisBencana->values()) !!};

    const chartConfig = {
      series: series,
      chart: {
        type: "pie",
        width: 280,
        height: 255,
        toolbar: { show: false },
      },
      dataLabels: { enabled: false },
      colors: ["#F87171", "#60A5FA", "#34D399", "#FBBF24", "#A78BFA"], // Tailwind colors
      legend: { show: true, position: "bottom", fontSize: '12px' },
      labels: labels,
    };

    const chart = new ApexCharts(document.querySelector("#pie-chart"), chartConfig);
    chart.render();
});
</script>