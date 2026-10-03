<?php

namespace App\Filament\Cms\Resources\Banners\Pages;

use App\Filament\Cms\Resources\Banners\BannerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBanner extends EditRecord
{
    protected static string $resource = BannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * Older banners store an external image link (https://...). The upload
     * field can't preview those, so start it empty and show the current
     * image separately in the form.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (str_starts_with((string) ($data['image'] ?? ''), 'http')) {
            $data['image'] = null;
        }

        return $data;
    }

    /**
     * Keep the current image when no new file was uploaded.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (array_key_exists('image', $data) && blank($data['image'])) {
            unset($data['image']);
        }

        return $data;
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index');
    }
}
