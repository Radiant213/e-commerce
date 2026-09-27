<?php

namespace App\Filament\Cms\Resources\Pages\Pages;

use App\Filament\Cms\Resources\Pages\PageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPageRecord extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_by'] = auth()->id();
        if (($data['status'] ?? 'draft') === 'published' && empty($this->record->published_at)) {
            $data['published_at'] = now();
        }

        return $data;
    }
}
