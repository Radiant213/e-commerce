<?php

namespace App\Filament\Cms\Pages;

use App\Filament\Cms\Widgets\CmsOverviewWidget;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Pages\Dashboard as BaseDashboard;

class CmsDashboard extends BaseDashboard
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $title = 'Studio Konten & Tampilan';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?int $navigationSort = -2;

    public function getSubheading(): ?string
    {
        return 'Kelola banner promosi, pop-up, halaman informasi, dan kontak toko dengan mudah tanpa koding.';
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 4;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('visit_store')
                ->label('Buka Toko Online')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('primary')
                ->url(url('/'))
                ->openUrlInNewTab(),

            Action::make('go_to_admin')
                ->label('Admin Toko (Pesanan & Produk)')
                ->icon('heroicon-o-shopping-bag')
                ->color('gray')
                ->url(url('/admin'))
                ->openUrlInNewTab()
                ->visible(fn () => auth()->user()?->isAdmin() ?? false),
        ];
    }

    public function getWidgets(): array
    {
        return [
            CmsOverviewWidget::class,
        ];
    }
}
