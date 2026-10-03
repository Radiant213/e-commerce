<?php

namespace App\Filament\Cms\Resources\Menus\Pages;

use App\Filament\Cms\Resources\Menus\MenuResource;
use Filament\Resources\Pages\EditRecord;

class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index');
    }
}
