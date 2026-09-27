<?php

namespace App\Filament\Cms\Resources\Popups;

use App\Filament\Cms\Resources\Popups\Pages\CreatePopup;
use App\Filament\Cms\Resources\Popups\Pages\EditPopup;
use App\Filament\Cms\Resources\Popups\Pages\ListPopups;
use App\Filament\Cms\Resources\Popups\Schemas\PopupForm;
use App\Filament\Cms\Resources\Popups\Tables\PopupsTable;
use App\Models\Cms\Popup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class PopupResource extends Resource
{
    protected static ?string $model = Popup::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';

    protected static string|UnitEnum|null $navigationGroup = 'Marketing';

    protected static ?string $navigationLabel = 'Pop-up Promo';

    protected static ?string $modelLabel = 'Pop-up Promo';

    protected static ?string $pluralModelLabel = 'Pop-up Promo';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return PopupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PopupsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPopups::route('/'),
            'create' => CreatePopup::route('/create'),
            'edit' => EditPopup::route('/{record}/edit'),
        ];
    }
}
