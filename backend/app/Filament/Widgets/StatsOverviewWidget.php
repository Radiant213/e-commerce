<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalRevenue = Order::whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])->sum('total');
        $totalOrders = Order::count();
        $totalProducts = Product::where('is_active', true)->count();
        $totalCustomers = User::where('role', 'customer')->count();

        return [
            Stat::make('Total Pendapatan (Revenue)', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Total penjualan pesanan lunas')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Total Pesanan Masuk', $totalOrders)
                ->description('Semua status pesanan')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make('Produk Aktif', $totalProducts)
                ->description('Tersedia di etalase web')
                ->descriptionIcon('heroicon-m-cube')
                ->color('warning'),

            Stat::make('Pelanggan Terdaftar', $totalCustomers)
                ->description('Total akun customer aktif')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }
}
