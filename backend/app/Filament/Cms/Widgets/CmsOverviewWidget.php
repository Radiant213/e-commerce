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
        $totalDraftPages = Page::where('status', 'draft')->count();

        $activeSections = HomepageSection::where('is_active', true)->count();
        $totalSections = HomepageSection::count();

        $activeBanners = Banner::where('is_active', true)->count();

        $totalMenus = Menu::count();
        $totalLinks = MenuItem::where('is_visible', true)->count();

        return [
            Stat::make('Halaman Statis', "{$publishedPages} Tayang")
                ->description($totalDraftPages > 0 ? "{$totalDraftPages} draf menunggu tayang" : 'Semua halaman aktif')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),

            Stat::make('Section Homepage', "{$activeSections} / {$totalSections} Aktif")
                ->description('Urutan dan visibilitas diatur dari CMS')
                ->descriptionIcon('heroicon-m-view-columns')
                ->color('primary'),

            Stat::make('Banner Promo', "{$activeBanners} Aktif")
                ->description('Tampil di slider & homepage')
                ->descriptionIcon('heroicon-m-photo')
                ->color('info'),

            Stat::make('Menu & Tautan', "{$totalLinks} Link")
                ->description("Tersebar di {$totalMenus} lokasi navigasi")
                ->descriptionIcon('heroicon-m-bars-3')
                ->color('warning'),
        ];
    }
}
