<?php

namespace App\Filament\Cms\Widgets;

use App\Models\Cms\Banner;
use App\Models\Cms\HomepageSection;
use App\Models\Cms\Menu;
use App\Models\Cms\MenuItem;
use App\Models\Cms\Page;
use App\Models\Cms\Popup;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CmsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $publishedPages = Page::where('status', 'published')->count();
        $activeBanners = Banner::where('is_active', true)->count();
        $activePopups = Popup::where('is_active', true)->count();

        return [
            Stat::make('Banner Promo', "{$activeBanners} Aktif")
                ->description('Banner promosi di beranda')
                ->descriptionIcon('heroicon-m-photo')
                ->color('primary')
                ->url(route('filament.cms.resources.banners.index')),

            Stat::make('Pop-up Promo', "{$activePopups} Aktif")
                ->description($activePopups > 0 ? 'Promo otomatis muncul ke pengunjung' : 'Tidak ada pop-up aktif')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('warning')
                ->url(route('filament.cms.resources.popups.index')),

            Stat::make('Halaman Informasi', "{$publishedPages} Halaman")
                ->description('Tentang Kami, FAQ, Kebijakan, dll.')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success')
                ->url(route('filament.cms.resources.pages.index')),

            Stat::make('Status Toko Online', 'Siap Menerima Order')
                ->description('Katalog & transaksi aktif')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('info')
                ->url('/'),
        ];
    }
}
