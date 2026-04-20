<?php

namespace App\Filament\Owner\Widgets;

use App\Models\Order;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RevenueLineChart extends Widget
{
    protected static string $view = 'filament.owner.widgets.revenue-chart';

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        // Ambil data pendapatan 30 hari terakhir
        $revenueData = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
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
            'chartData' => [
                'labels' => $labels,
                'values' => $values,
            ],
        ];
    }
}
