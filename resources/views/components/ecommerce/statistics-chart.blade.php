@props(['bencanaPerBulan', 'jenisBencana', 'start', 'end'])

<div
    class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 h-153 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6">
    <div class="flex flex-col gap-5 mb-6 sm:flex-row sm:justify-between">
        <div class="w-full">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Kejadian Bencana
            </h3>
            <p class="mt-1 text-gray-500 text-theme-sm dark:text-gray-400">
                Jumlah kejadian bencana per bulan
            </p>
        </div>

        <div class="flex items-start w-full gap-3 sm:justify-end">
            <div x-data="{ selected: 'overview' }"
                class="inline-flex w-fit items-center gap-0.5 rounded-lg bg-gray-100 p-0.5 dark:bg-gray-900">

                @php
                    $options = [
                        ['value' => 'overview', 'label' => 'Banjir'],
                        ['value' => 'sales', 'label' => 'Gempa'],
                        ['value' => 'revenue', 'label' => 'Longsor'],
                    ];
                @endphp

                @foreach ($options as $option)
                    <button @click="selected = '{{ $option['value'] }}'"
                        :class="selected === '{{ $option['value'] }}' ?
                            'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800' :
                            'text-gray-500 dark:text-gray-400'"
                        class="px-3 py-2 font-medium rounded-md text-theme-sm hover:text-gray-900 dark:hover:text-white">
                        {{ $option['label'] }}
                    </button>
                @endforeach

            </div>
        </div>

        <div class="relative max-w-40">
            <input type="text"
                class="datepicker text-theme-sm shadow-theme-xs h-10 w-full max-w-40 rounded-lg border border-gray-200 bg-white py-2.5 pr-4 pl-10 font-medium text-gray-700 focus:ring-0 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400"
                readonly>
            <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                <!-- SVG Calendar Icon -->
                <svg class="fill-gray-700 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20"
                    fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M6.66683 1.54199C7.08104 1.54199 7.41683 1.87778 7.41683 2.29199V3.00033H12.5835V2.29199C12.5835 1.87778 12.9193 1.54199 13.3335 1.54199C13.7477 1.54199 14.0835 1.87778 14.0835 2.29199V3.00033L15.4168 3.00033C16.5214 3.00033 17.4168 3.89576 17.4168 5.00033V7.50033V15.8337C17.4168 16.9382 16.5214 17.8337 15.4168 17.8337H4.5835C3.47893 17.8337 2.5835 16.9382 2.5835 15.8337V7.50033V5.00033C2.5835 3.89576 3.47893 3.00033 4.5835 3.00033L5.91683 3.00033V2.29199C5.91683 1.87778 6.25262 1.54199 6.66683 1.54199ZM6.66683 4.50033H4.5835C4.30735 4.50033 4.0835 4.72418 4.0835 5.00033V6.75033H15.9168V5.00033C15.9168 4.72418 15.693 4.50033 15.4168 4.50033H13.3335H6.66683ZM15.9168 8.25033H4.0835V15.8337C4.0835 16.1098 4.30735 16.3337 4.5835 16.3337H15.4168C15.693 16.3337 15.9168 16.1098 15.9168 15.8337V8.25033Z"
                        fill=""></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="max-w-full overflow-x-auto custom-scrollbar">
        <div id="chartThree" class="-ml-4 min-w-[500px] pl-2 xl:min-w-full h-[500px]"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const labels = {!! json_encode(array_keys($bencanaPerBulan)) !!}; // Jan, Feb, ...
        const seriesData = {!! json_encode(array_values($bencanaPerBulan)) !!}; // 5, 3, ...

        const options = {
            chart: {
                type: 'area',
                height: 455,
                width: 800, 
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
            },
            series: [{
                name: 'Kejadian Bencana',
                data: seriesData
            }],
            xaxis: {
                categories: labels,
                labels: {
                    style: {
                        colors: '#000000',
                        fontSize: '12px'
                    }
                },
                tooltip: {
                    enabled: false
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#000000',
                        fontSize: '12px'
                    }
                }
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    gradientToColors: ['#EAB308'],
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                }
            },
            colors: ['#EAB308'],
            tooltip: {
                theme: 'dark',
                x: {
                    show: true
                },
                y: {
                    formatter: val => `${val} kejadian`
                }
            },
            grid: {
                borderColor: '#E5E7EB',
                strokeDashArray: 3,
                row: {
                    colors: ['transparent'],
                    opacity: 0.5
                },
            },
        };

        const chart = new ApexCharts(document.querySelector("#chartThree"), options);
        chart.render();
    });
</script>
<!-- Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr(".datepicker", {
        enableTime: false,
        dateFormat: "Y-m-d",
        appendTo: document.body, // kalender di luar semua container
        onReady: function(selectedDates, dateStr, instance) {
            instance.calendarContainer.style.zIndex = 9999;
        }
    });
</script>
