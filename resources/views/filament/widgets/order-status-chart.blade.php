<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-m-chart-bar class="h-5 w-5 text-primary-500" />
                <span>Analitik Pesanan</span>
            </div>
        </x-slot>

        @if(!$hasData)
            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <x-heroicon-o-chart-bar class="h-16 w-16 text-gray-300 dark:text-gray-600 mb-4" />
                <h3 class="text-lg font-semibold text-gray-500 dark:text-gray-400">Belum Ada Data Pesanan</h3>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">
                    Grafik akan muncul setelah ada transaksi masuk.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                {{-- Chart 1: Revenue 7 Hari --}}
                <div class="rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
                    <h3 class="mb-4 text-sm font-semibold text-gray-700 dark:text-gray-300">
                        📈 Revenue 7 Hari Terakhir
                    </h3>
                    <canvas id="revenueChart" height="220"></canvas>
                </div>

                {{-- Chart 2: Distribusi Status --}}
                <div class="rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
                    <h3 class="mb-4 text-sm font-semibold text-gray-700 dark:text-gray-300">
                        🥧 Distribusi Status Pesanan
                    </h3>
                    @if(count($statusData) > 0)
                        <canvas id="statusChart" height="220"></canvas>
                        {{-- Legend manual --}}
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach($statusData as $item)
                                <div class="flex items-center gap-1.5">
                                    <span class="inline-block h-3 w-3 rounded-full" style="background-color: {{ $item['color'] }}"></span>
                                    <span class="text-xs text-gray-600 dark:text-gray-400">
                                        {{ $item['label'] }} ({{ $item['value'] }})
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-8 text-center text-sm text-gray-400">Belum ada data</div>
                    @endif
                </div>

            </div>

            {{-- Chart 3: Jumlah Order per Hari --}}
            <div class="mt-6 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
                <h3 class="mb-4 text-sm font-semibold text-gray-700 dark:text-gray-300">
                    📦 Jumlah Pesanan Masuk 7 Hari Terakhir
                </h3>
                <canvas id="orderCountChart" height="120"></canvas>
            </div>

            {{-- Load Chart.js dari CDN --}}
            <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

            <script>
                (function () {
                    const isDark = document.documentElement.classList.contains('dark');
                    const gridColor = isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';
                    const labelColor = isDark ? '#9ca3af' : '#6b7280';

                    // --- Revenue Chart ---
                    const revenueCtx = document.getElementById('revenueChart');
                    if (revenueCtx) {
                        new Chart(revenueCtx, {
                            type: 'line',
                            data: {
                                labels: {!! json_encode($revenueLabels) !!},
                                datasets: [{
                                    label: 'Revenue (Rp)',
                                    data: {!! json_encode($revenueData) !!},
                                    borderColor: '#22c55e',
                                    backgroundColor: 'rgba(34,197,94,0.12)',
                                    borderWidth: 2.5,
                                    pointRadius: 4,
                                    pointBackgroundColor: '#22c55e',
                                    tension: 0.4,
                                    fill: true,
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        callbacks: {
                                            label: ctx => 'Rp ' + ctx.raw.toLocaleString('id-ID')
                                        }
                                    }
                                },
                                scales: {
                                    x: { grid: { color: gridColor }, ticks: { color: labelColor, font: { size: 11 } } },
                                    y: {
                                        grid: { color: gridColor },
                                        ticks: {
                                            color: labelColor,
                                            font: { size: 11 },
                                            callback: val => 'Rp ' + (val / 1000).toFixed(0) + 'k'
                                        }
                                    }
                                }
                            }
                        });
                    }

                    // --- Status Donut Chart ---
                    const statusCtx = document.getElementById('statusChart');
                    const statusData = {!! json_encode($statusData) !!};
                    if (statusCtx && statusData.length > 0) {
                        new Chart(statusCtx, {
                            type: 'doughnut',
                            data: {
                                labels: statusData.map(d => d.label),
                                datasets: [{
                                    data: statusData.map(d => d.value),
                                    backgroundColor: statusData.map(d => d.color),
                                    borderWidth: 2,
                                    borderColor: isDark ? '#1f2937' : '#ffffff',
                                    hoverOffset: 6,
                                }]
                            },
                            options: {
                                responsive: true,
                                cutout: '65%',
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        callbacks: {
                                            label: ctx => ctx.label + ': ' + ctx.raw + ' pesanan'
                                        }
                                    }
                                }
                            }
                        });
                    }

                    // --- Order Count Bar Chart ---
                    const orderCtx = document.getElementById('orderCountChart');
                    if (orderCtx) {
                        new Chart(orderCtx, {
                            type: 'bar',
                            data: {
                                labels: {!! json_encode($revenueLabels) !!},
                                datasets: [{
                                    label: 'Jumlah Pesanan',
                                    data: {!! json_encode($orderCountData) !!},
                                    backgroundColor: 'rgba(99,102,241,0.7)',
                                    borderColor: '#6366f1',
                                    borderWidth: 1.5,
                                    borderRadius: 6,
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        callbacks: {
                                            label: ctx => ctx.raw + ' pesanan'
                                        }
                                    }
                                },
                                scales: {
                                    x: { grid: { color: gridColor }, ticks: { color: labelColor, font: { size: 11 } } },
                                    y: {
                                        beginAtZero: true,
                                        grid: { color: gridColor },
                                        ticks: { color: labelColor, font: { size: 11 }, stepSize: 1 }
                                    }
                                }
                            }
                        });
                    }
                })();
            </script>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
