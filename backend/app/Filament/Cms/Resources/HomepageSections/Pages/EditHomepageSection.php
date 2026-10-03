<?php

namespace App\Filament\Cms\Resources\HomepageSections\Pages;

use App\Filament\Cms\Resources\HomepageSections\HomepageSectionResource;
use Filament\Resources\Pages\EditRecord;

class EditHomepageSection extends EditRecord
{
    protected static string $resource = HomepageSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index');
    }
}
