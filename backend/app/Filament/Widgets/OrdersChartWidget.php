<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class OrdersChartWidget extends ChartWidget
{
    protected ?string $heading = 'Grafik Tren Penjualan (7 Hari Terakhir)';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(fn ($day) => Carbon::now()->subDays($day));

        $labels = [];
        $ordersCount = [];
        $revenueData = [];

        foreach ($days as $day) {
            $formattedDay = $day->format('Y-m-d');
            $labels[] = $day->translatedFormat('d M');

            $ordersCount[] = Order::whereDate('created_at', $formattedDay)->count();

            $revenue = Order::whereDate('created_at', $formattedDay)
                ->whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])
                ->sum('total');

            // In thousands (ribu Rp) for clean readable scale
            $revenueData[] = (int) round($revenue / 1000);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan (dlm Ribu Rp)',
                    'data' => $revenueData,
                    'borderColor' => '#6366f1',
                    'backgroundColor' => 'rgba(99, 102, 241, 0.15)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
                [
                    'label' => 'Jumlah Pesanan',
                    'data' => $ordersCount,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.15)',
                    'fill' => false,
                    'tension' => 0.35,
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
