<?php

namespace App\Filament\Owner\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StaffPerformanceWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Total Pesanan Lunas
        $totalPaid = Order::where('payment_status', 'paid')->count();
        
        // Pesanan Selesai Produksi (Ready)
        $totalReady = Order::where('status', 'ready')->count();
        
        // Persentase Penyelesaian
        $completionRate = $totalPaid > 0 ? round(($totalReady / $totalPaid) * 100) : 0;
        
        // Antrean di Admin (Belum masuk produksi/masih Paid awal)
        $adminQueue = Order::where('status', 'paid')->count();

        return [
            Stat::make('Efisiensi Produksi', $completionRate . '%')
                ->description('Rasio pesanan selesai vs antrean')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color($completionRate > 70 ? 'success' : 'warning'),

            Stat::make('Antrean di Admin', $adminQueue)
                ->description('Pesanan menunggu penjadwalan')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),

            Stat::make('Total Pesanan Lunas', $totalPaid)
                ->description('Total beban kerja terjadwal')
                ->descriptionIcon('heroicon-m-briefcase'),
        ];
    }
}
