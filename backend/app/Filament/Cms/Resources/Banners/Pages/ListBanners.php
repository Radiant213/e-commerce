<?php

namespace App\Filament\Cms\Resources\Banners\Pages;

use App\Filament\Cms\Resources\Banners\BannerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBanners extends ListRecords
{
    protected static string $resource = BannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Banner Baru'),
        ];
    }
}
