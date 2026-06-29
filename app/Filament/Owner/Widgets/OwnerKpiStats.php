<?php

namespace App\Filament\Owner\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use NumberFormatter;

class OwnerKpiStats extends BaseWidget
{
    protected function getStats(): array
    {
        $fmt = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);
        
        // Total Pendapatan (Status Bayar: Paid)
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        
        // Jumlah Pesanan Lunas
        $orderCount = Order::where('payment_status', 'paid')->count();
        
        // Rata-rata Nilai Order (AOV)
        $averageOrderValue = $orderCount > 0 ? ($totalRevenue / $orderCount) : 0;
        
        // Pesanan Progres (Sedang dikerjakan admin/produksi)
        $onProgress = Order::whereIn('status', ['paid', 'printing', 'sewing', 'qc'])->count();

        return [
            Stat::make('Total Omzet (Lunas)', $fmt->formatCurrency($totalRevenue, 'IDR'))
                ->description('Total pendapatan dari pesanan terbayar')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Rata-rata Nilai Order', $fmt->formatCurrency($averageOrderValue, 'IDR'))
                ->description('Nilai rata-rata per transaksi')
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('info'),

            Stat::make('Pesanan Dalam Proses', $onProgress)
                ->description('Masih dalam tahap produksi/admin')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning'),
        ];
    }
}
