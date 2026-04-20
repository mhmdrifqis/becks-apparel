<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class OrderStatusChart extends Widget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected static string $view = 'filament.widgets.order-status-chart';

    public function getViewData(): array
    {
        // Distribusi status pesanan
        $statuses = [
            'pending'   => ['label' => 'Pending', 'color' => '#94a3b8'],
            'paid'      => ['label' => 'Lunas', 'color' => '#22c55e'],
            'printing'  => ['label' => 'Cetak', 'color' => '#3b82f6'],
            'sewing'    => ['label' => 'Jahit', 'color' => '#8b5cf6'],
            'qc'        => ['label' => 'Quality Control', 'color' => '#f59e0b'],
            'ready'     => ['label' => 'Siap Kirim', 'color' => '#06b6d4'],
            'shipped'   => ['label' => 'Dikirim', 'color' => '#10b981'],
            'completed' => ['label' => 'Selesai', 'color' => '#059669'],
            'cancelled' => ['label' => 'Dibatalkan', 'color' => '#ef4444'],
        ];

        $statusData = [];
        foreach ($statuses as $key => $info) {
            $count = Order::where('status', $key)->count();
            if ($count > 0) {
                $statusData[] = [
                    'label' => $info['label'],
                    'value' => $count,
                    'color' => $info['color'],
                ];
            }
        }

        // Revenue 7 hari terakhir
        $revenueLabels = [];
        $revenueData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $revenueLabels[] = $date->format('d M');
            $revenueData[] = (float) Order::whereDate('created_at', $date)
                ->where('payment_status', 'paid')
                ->sum('total_amount');
        }

        // Pesanan per hari 7 hari terakhir
        $orderCountData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $orderCountData[] = Order::whereDate('created_at', $date)->count();
        }

        return [
            'statusData'     => $statusData,
            'revenueLabels'  => $revenueLabels,
            'revenueData'    => $revenueData,
            'orderCountData' => $orderCountData,
            'totalOrders'    => Order::count(),
            'hasData'        => Order::count() > 0,
        ];
    }
}
