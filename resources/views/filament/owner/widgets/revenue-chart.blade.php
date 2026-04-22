<div class="p-6 bg-white dark:bg-zinc-900 rounded-3xl border border-gray-100 dark:border-zinc-800 shadow-sm transition-all hover:shadow-md">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400 mb-1">Intelligence Analytics</h3>
            <h2 class="text-2xl font-black text-slate-800 dark:text-white tracking-tighter">PERFORMA PENDAPATAN</h2>
        </div>
        <div class="flex flex-col items-end">
            <div class="p-3 bg-brand-50 dark:bg-brand-950/30 rounded-2xl mb-1">
                <svg class="w-6 h-6 text-brand-900 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
            <span class="text-[10px] font-bold text-brand-600 dark:text-brand-400 uppercase tracking-widest">30 Days Trend</span>
        </div>
    </div>

    <div style="height: 320px;" class="relative">
        <canvas id="revenueLineChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('revenueLineChart').getContext('2d');
        
        // Data passed from widget class
        const chartData = @json($chartData);
        
        // Create Gradient
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(6, 64, 43, 0.4)');
        gradient.addColorStop(0.5, 'rgba(6, 64, 43, 0.1)');
        gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: chartData.values,
                    borderColor: '#06402B',
                    backgroundColor: gradient,
                    borderWidth: 5,
                    fill: true,
                    tension: 0.45,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#06402B',
                    pointBorderWidth: 3,
                    pointRadius: 0, // Hidden on normal
                    pointHoverRadius: 8, // Shown on hover
                    pointHoverBackgroundColor: '#06402B',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 3,
                    shadowBlur: 15,
                    shadowColor: 'rgba(6, 64, 43, 0.4)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: true,
                        backgroundColor: '#06402B',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 16,
                        cornerRadius: 12,
                        displayColors: false,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 16, weight: '900' },
                        callbacks: {
                            label: function(context) {
                                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { 
                            drawBorder: false,
                            color: 'rgba(0, 0, 0, 0.03)',
                            lineWidth: 1
                        },
                        ticks: {
                            font: { size: 11, weight: 'bold' },
                            color: '#94a3b8',
                            padding: 10,
                            callback: function(value) {
                                if (value >= 1000000) return (value / 1000000) + ' Jt';
                                if (value >= 1000) return (value / 1000) + ' Rb';
                                return value;
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 11, weight: 'bold' },
                            color: '#94a3b8',
                            padding: 10,
                            maxRotation: 0,
                            autoSkip: true,
                            maxTicksLimit: 10
                        }
                    }
                }
            }
        });
    });
</script>
