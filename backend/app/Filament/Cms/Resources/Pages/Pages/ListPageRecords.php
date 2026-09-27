<?php

namespace App\Filament\Cms\Resources\Pages\Pages;

use App\Filament\Cms\Resources\Pages\PageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPageRecords extends ListRecords
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Buat Halaman Baru'),
        ];
    }
}
