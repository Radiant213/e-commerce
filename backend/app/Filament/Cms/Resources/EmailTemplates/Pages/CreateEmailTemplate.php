<?php

namespace App\Filament\Cms\Resources\EmailTemplates\Pages;

use App\Filament\Cms\Resources\EmailTemplates\EmailTemplateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmailTemplate extends CreateRecord
{
    protected static string $resource = EmailTemplateResource::class;
}
