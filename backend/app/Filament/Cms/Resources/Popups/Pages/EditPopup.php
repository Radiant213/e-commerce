<?php

namespace App\Filament\Cms\Resources\Popups\Pages;

use App\Filament\Cms\Resources\Popups\PopupResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPopup extends EditRecord
{
    protected static string $resource = PopupResource::class;

    /** Tracks whether the record had an external image link when the form opened. */
    protected bool $hadExternalImage = false;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * Older pop-ups store an external image link. The upload field can't
     * preview those, so start it empty and show the current image separately.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (str_starts_with((string) ($data['image'] ?? ''), 'http')) {
            $data['image'] = null;
        }

        return $data;
    }

    /**
     * Keep the existing external image when no new file was uploaded.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $current = (string) $this->getRecord()->image;

        if (blank($data['image'] ?? null) && str_starts_with($current, 'http')) {
            unset($data['image']);
        }

        return $data;
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index');
    }
}
