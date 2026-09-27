<?php

namespace App\Filament\Cms\Resources\EmailTemplates;

use App\Filament\Cms\Resources\EmailTemplates\Pages\CreateEmailTemplate;
use App\Filament\Cms\Resources\EmailTemplates\Pages\EditEmailTemplate;
use App\Filament\Cms\Resources\EmailTemplates\Pages\ListEmailTemplates;
use App\Filament\Cms\Resources\EmailTemplates\Schemas\EmailTemplateForm;
use App\Filament\Cms\Resources\EmailTemplates\Tables\EmailTemplatesTable;
use App\Models\Cms\EmailTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class EmailTemplateResource extends Resource
{
    protected static ?string $model = EmailTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-envelope';

    protected static string|UnitEnum|null $navigationGroup = 'Email';

    protected static ?string $navigationLabel = 'Template Email';

    protected static ?string $modelLabel = 'Template Email';

    protected static ?string $pluralModelLabel = 'Template Email';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return EmailTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmailTemplatesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmailTemplates::route('/'),
            'create' => CreateEmailTemplate::route('/create'),
            'edit' => EditEmailTemplate::route('/{record}/edit'),
        ];
    }
}
