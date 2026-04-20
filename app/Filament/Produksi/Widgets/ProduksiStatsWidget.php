<?php

namespace App\Filament\Produksi\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class ProduksiStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Antrean Baru (Belum disentuh produksi)
        $newQueued = Order::where('status', 'paid')->count();

        // Dalam Proses (Sedang dikerjakan)
        $onProcessing = Order::whereIn('status', ['printing', 'sewing', 'qc'])->count();

        // Selesai Hari Ini (Siap kirim)
        $completedToday = Order::where('status', 'ready')
            ->whereDate('updated_at', Carbon::today())
            ->count();

        return [
            Stat::make('Antrean Baru', $newQueued)
                ->description('Pesanan lunas menunggu cetak')
                ->descriptionIcon('heroicon-m-clock')
                ->color($newQueued > 0 ? 'warning' : 'gray'),

            Stat::make('Dalam Proses', $onProcessing)
                ->description('Cetak / Jahit / QC')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('info'),

            Stat::make('Selesai Hari Ini', $completedToday)
                ->description('Siap dikirim ke pelanggan')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
