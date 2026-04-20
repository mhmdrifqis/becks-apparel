<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class AdminStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Total pesanan masuk hari ini
        $ordersToday = Order::whereDate('created_at', $today)->count();

        // Pesanan baru bulan ini
        $ordersThisMonth = Order::where('created_at', '>=', $thisMonth)->count();

        // Pendapatan bulan ini (hanya yang sudah paid)
        $revenueThisMonth = Order::where('created_at', '>=', $thisMonth)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        // Pesanan pending pembayaran
        $pendingPayment = Order::where('payment_status', 'unpaid')
            ->whereNotIn('status', ['cancelled'])
            ->count();

        // Pesanan aktif dalam produksi
        $inProduction = Order::whereIn('status', ['paid', 'printing', 'sewing', 'qc'])
            ->count();

        // Pesanan siap kirim
        $readyToShip = Order::where('status', 'ready')->count();

        return [
            Stat::make('Pesanan Hari Ini', $ordersToday)
                ->description('Total pesanan masuk hari ini')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color($ordersToday > 0 ? 'success' : 'gray')
                ->chart($this->getOrderTrendLast7Days()),

            Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format($revenueThisMonth, 0, ',', '.'))
                ->description($ordersThisMonth . ' pesanan bulan ini')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Menunggu Pembayaran', $pendingPayment)
                ->description('Pesanan belum lunas')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingPayment > 0 ? 'warning' : 'success'),

            Stat::make('Dalam Produksi', $inProduction)
                ->description('Paid → Cetak → Jahit → QC')
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color('info'),

            Stat::make('Siap Kirim', $readyToShip)
                ->description('Menunggu input resi')
                ->descriptionIcon('heroicon-m-truck')
                ->color($readyToShip > 0 ? 'warning' : 'gray'),

            Stat::make('Total User Terdaftar', User::count())
                ->description('Akun pelanggan aktif')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }

    protected function getOrderTrendLast7Days(): array
    {
        $trend = [];
        for ($i = 6; $i >= 0; $i--) {
            $trend[] = Order::whereDate('created_at', Carbon::today()->subDays($i))->count();
        }
        return $trend;
    }
}
