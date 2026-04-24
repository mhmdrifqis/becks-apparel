<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Pendapatan (Revenue) 7 Hari Terakhir';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '275px';

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('d M');
            $data[] = (float) Order::whereDate('created_at', $date)
                ->whereIn('payment_status', ['paid', 'partial'])
                ->sum('deposit_amount');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Uang Masuk (Rp)',
                    'data' => $data,
                    'fill' => 'start',
                    'borderColor' => '#22c55e',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
