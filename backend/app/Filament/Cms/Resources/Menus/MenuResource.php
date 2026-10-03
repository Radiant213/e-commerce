<?php

namespace App\Filament\Cms\Resources\Menus;

use App\Filament\Cms\Resources\Menus\Pages\EditMenu;
use App\Filament\Cms\Resources\Menus\Pages\ListMenus;
use App\Filament\Cms\Resources\Menus\Schemas\MenuForm;
use App\Filament\Cms\Resources\Menus\Tables\MenusTable;
use App\Models\Cms\Menu;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-bars-3';

    protected static string|UnitEnum|null $navigationGroup = 'Tampilan & Promo';

    protected static ?string $navigationLabel = 'Menu Navigasi';

    protected static ?string $modelLabel = 'Menu';

    protected static ?string $pluralModelLabel = 'Menu Navigasi';

    protected static ?int $navigationSort = 3;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return MenuForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MenusTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenus::route('/'),
            'edit' => EditMenu::route('/{record}/edit'),
        ];
    }
}
