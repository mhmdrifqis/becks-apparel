<?php

namespace App\Filament\Owner\Widgets;

use App\Models\OrderItem;
use App\Models\Package;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopPackagesChart extends ChartWidget
{
    protected static ?string $heading = 'Produk & Paket Terlaris';
    protected static ?int $sort = 11;

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = OrderItem::query()
            ->join('packages', 'order_items.package_id', '=', 'packages.id')
            ->select('packages.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('packages.id', 'packages.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Terjual (Pcs)',
                    'data' => $data->pluck('total_sold')->toArray(),
                    'backgroundColor' => [
                        '#06402B',
                        '#059669',
                        '#10B981',
                        '#34D399',
                        '#6EE7B7'
                    ],
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
