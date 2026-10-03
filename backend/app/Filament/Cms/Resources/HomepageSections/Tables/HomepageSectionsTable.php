<?php

namespace App\Filament\Cms\Resources\HomepageSections\Tables;

use App\Models\Cms\HomepageSection;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class HomepageSectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('title')
                    ->label('Bagian Beranda')
                    ->weight('bold')
                    ->searchable()
                    ->description(fn (HomepageSection $record) => HomepageSection::TYPES[$record->type] ?? ucfirst($record->type)),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'hero' => 'primary',
                        'categories' => 'warning',
                        'product_showcase' => 'success',
                        'promo_strip' => 'info',
                        'brand_story' => 'violet',
                        'newsletter' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'hero' => 'Hero Banner Atas',
                        'categories' => 'Kategori Pilihan',
                        'product_showcase' => 'Etalase Produk',
                        'promo_strip' => 'Strip Promo',
                        'brand_story' => 'Cerita Brand',
                        'newsletter' => 'Newsletter',
                        default => ucfirst($state),
                    }),

                ToggleColumn::make('is_active')
                    ->label('Tampilkan'),

                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable()
                    ->color('gray'),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
