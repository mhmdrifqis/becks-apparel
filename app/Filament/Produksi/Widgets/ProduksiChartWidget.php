<?php

namespace App\Filament\Produksi\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ProduksiChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Kinerja Produksi (7 Hari Terakhir)';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('d M');
            
            // Hitung order yang masuk tahap 'ready' atau 'shipped' atau 'completed' pada tanggal tersebut
            $count = Order::whereIn('status', ['ready', 'shipped', 'completed'])
                ->whereDate('updated_at', $date)
                ->count();
                
            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pesanan Selesai Diproduksi',
                    'data' => $data,
                    'backgroundColor' => '#22c55e',
                    'borderColor' => '#16a34a',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
