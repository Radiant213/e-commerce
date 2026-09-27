<?php

namespace App\Filament\Cms\Resources\Pages\Pages;

use App\Filament\Cms\Resources\Pages\PageResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePageRecord extends CreateRecord
{
    protected static string $resource = PageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        if (($data['status'] ?? 'draft') === 'published') {
            $data['published_at'] = now();
        }

        return $data;
    }
}
