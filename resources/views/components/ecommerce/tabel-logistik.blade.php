<div class="rounded-2xl border border-gray-200 bg-white p-6
            min-h-[340px] md:min-h-[400px]
            dark:border-gray-800 dark:bg-white/[0.03]">

    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
        <h4 class="flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-white/90">
            <span class="text-red-600 dark:text-red-400">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-4 w-4" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke-dasharray="60" stroke-dashoffset="60" d="M12 3L21 20H3L12 3Z">
                        <animate fill="freeze" attributeName="stroke-dashoffset" dur="0.5s" values="60;0" />
                    </path>
                    <path stroke-dasharray="6" stroke-dashoffset="6" d="M12 10V14">
                        <animate fill="freeze" attributeName="stroke-dashoffset" begin="0.6s" dur="0.2s"
                            values="6;0" />
                    </path>
                    <circle cx="12" cy="17" r="1" fill="currentColor" fill-opacity="0">
                        <animate fill="freeze" attributeName="fill-opacity" begin="0.8s" dur="0.4s"
                            values="0;1" />
                    </circle>
                </svg>
            </span>

            Top 5 Lokasi Logistik Bermasalah
        </h4>

        <span class="text-xs text-gray-500">
            Prioritas distribusi
        </span>
    </div>

    <!-- Mini Table -->
    <div class="space-y-3">
        @forelse ($topLogistikBermasalah as $item)
            <div
                class="flex items-center justify-between rounded-xl border border-gray-100 px-3 py-2
                       hover:bg-gray-50 transition dark:border-gray-800 dark:hover:bg-white/[0.05]">

                <!-- Lokasi -->
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-800 dark:text-white/90">
                        {{ $item->nama_lokasi }}
                    </span>

                    <span class="text-xs text-gray-500">
                        {{ $item->village->name ?? '-' }},
                        {{ $item->district->name ?? '-' }}
                    </span>
                </div>

                <!-- Status -->
                @if ($item->status === 'Habis')
                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                        🚨 Habis
                    </span>
                @else
                    <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">
                        ⚠️ Menipis
                    </span>
                @endif
            </div>
        @empty
            <div class="text-center text-sm text-gray-500 py-6">
                Tidak ada logistik bermasalah
            </div>
        @endforelse
    </div>
</div>
