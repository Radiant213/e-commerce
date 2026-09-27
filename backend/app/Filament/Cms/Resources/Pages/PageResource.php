<?php

namespace App\Filament\Cms\Resources\Pages;

use App\Filament\Cms\Resources\Pages\Pages\CreatePageRecord;
use App\Filament\Cms\Resources\Pages\Pages\EditPageRecord;
use App\Filament\Cms\Resources\Pages\Pages\ListPageRecords;
use App\Filament\Cms\Resources\Pages\Schemas\PageForm;
use App\Filament\Cms\Resources\Pages\Tables\PagesTable;
use App\Models\Cms\Page as CmsPage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class PageResource extends Resource
{
    protected static ?string $model = CmsPage::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|UnitEnum|null $navigationGroup = 'Halaman & Navigasi';

    protected static ?string $navigationLabel = 'Semua Halaman';

    protected static ?string $modelLabel = 'Halaman';

    protected static ?string $pluralModelLabel = 'Halaman Statis';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return PageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PagesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPageRecords::route('/'),
            'create' => CreatePageRecord::route('/create'),
            'edit' => EditPageRecord::route('/{record}/edit'),
        ];
    }
}
