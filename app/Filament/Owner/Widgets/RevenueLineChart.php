<?php

namespace App\Filament\Owner\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RevenueLineChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pendapatan (30 Hari Terakhir)';
    protected static ?int $sort = 10;

    protected function getData(): array
    {
        // Ambil data pendapatan 30 hari terakhir
        $revenueData = Order::whereIn('payment_status', ['paid', 'partial'])
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(deposit_amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $labels = [];
        $values = [];

        // Pastikan setiap hari dalam 30 hari terakhir ada datanya (isi 0 jika kosong)
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $label = Carbon::now()->subDays($i)->format('d M');
            
            $labels[] = $label;
            
            $dayData = $revenueData->firstWhere('date', $date);
            $values[] = $dayData ? (float) $dayData->total : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Uang Masuk (Rp)',
                    'data' => $values,
                    'fill' => 'start',
                    'borderColor' => '#06402B',
                    'backgroundColor' => 'rgba(6, 64, 43, 0.1)',
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
