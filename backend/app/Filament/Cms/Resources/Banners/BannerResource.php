<?php

namespace App\Filament\Cms\Resources\Banners;

use App\Filament\Cms\Resources\Banners\Pages\CreateBanner;
use App\Filament\Cms\Resources\Banners\Pages\EditBanner;
use App\Filament\Cms\Resources\Banners\Pages\ListBanners;
use App\Filament\Cms\Resources\Banners\Schemas\BannerForm;
use App\Filament\Cms\Resources\Banners\Tables\BannersTable;
use App\Models\Cms\Banner;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected static string|UnitEnum|null $navigationGroup = 'Marketing';

    protected static ?string $navigationLabel = 'Banner & Promo';

    protected static ?string $modelLabel = 'Banner Promo';

    protected static ?string $pluralModelLabel = 'Banner & Promo';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return BannerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BannersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBanners::route('/'),
            'create' => CreateBanner::route('/create'),
            'edit' => EditBanner::route('/{record}/edit'),
        ];
    }
}
