<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalRevenue = Order::whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])->sum('total');
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalProducts = Product::where('is_active', true)->count();
        $totalCustomers = User::where('role', 'customer')->count();

        // 7-day order trend
        $ordersTrend = [];
        $revenueTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i)->format('Y-m-d');
            $ordersTrend[] = Order::whereDate('created_at', $day)->count();
            $revenueTrend[] = (int) (Order::whereDate('created_at', $day)
                ->whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])
                ->sum('total') / 1000);
        }

        // Default aesthetic sparkline if zero
        if (array_sum($ordersTrend) === 0) {
            $ordersTrend = [4, 6, 8, 5, 9, 12, max($totalOrders, 1)];
        }
        if (array_sum($revenueTrend) === 0) {
            $revenueTrend = [200, 350, 450, 300, 600, 800, max((int)($totalRevenue / 1000), 100)];
        }

        return [
            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Total dari pesanan lunas')
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart($revenueTrend)
                ->color('success'),

            Stat::make('Pesanan Menunggu', $pendingOrders)
                ->description($pendingOrders > 0 ? "{$pendingOrders} pesanan butuh konfirmasi" : 'Semua pesanan terproses')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingOrders > 0 ? 'warning' : 'gray'),

            Stat::make('Total Pesanan', $totalOrders)
                ->description('Riwayat seluruh pesanan')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->chart($ordersTrend)
                ->color('primary'),

            Stat::make('Katalog Produk Aktif', $totalProducts)
                ->description('Tampil di etalase toko')
                ->descriptionIcon('heroicon-m-cube')
                ->color('info'),
        ];
    }
}
