<?php

namespace App\Filament\Cms\Resources\HomepageSections;

use App\Filament\Cms\Resources\HomepageSections\Pages\EditHomepageSection;
use App\Filament\Cms\Resources\HomepageSections\Pages\ListHomepageSections;
use App\Filament\Cms\Resources\HomepageSections\Schemas\HomepageSectionForm;
use App\Filament\Cms\Resources\HomepageSections\Tables\HomepageSectionsTable;
use App\Models\Cms\HomepageSection;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class HomepageSectionResource extends Resource
{
    protected static ?string $model = HomepageSection::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-view-columns';

    protected static string|UnitEnum|null $navigationGroup = 'Konten Homepage';

    protected static ?string $navigationLabel = 'Urutan & Section';

    protected static ?string $modelLabel = 'Section Homepage';

    protected static ?string $pluralModelLabel = 'Section Homepage';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return HomepageSectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HomepageSectionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHomepageSections::route('/'),
            'edit' => EditHomepageSection::route('/{record}/edit'),
        ];
    }
}
