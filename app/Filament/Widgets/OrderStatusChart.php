<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrderStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Status Pesanan';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '275px';

    protected function getData(): array
    {
        $statuses = [
            'paid'      => 'Antrean',
            'printing'  => 'Cetak',
            'sewing'    => 'Jahit',
            'qc'        => 'QC',
            'ready'     => 'Siap Kirim',
        ];

        $data = [];
        $labels = [];
        $colors = [
            '#22c55e', // Antrean (Green)
            '#3b82f6', // Cetak (Blue)
            '#8b5cf6', // Jahit (Purple)
            '#f59e0b', // QC (Orange)
            '#06b6d4', // Siap Kirim (Cyan)
        ];

        foreach ($statuses as $key => $label) {
            $labels[] = $label;
            $data[] = Order::where('status', $key)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pesanan',
                    'data' => $data,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
