<?php

namespace App\Filament\Cms\Resources\HomepageSections\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class HomepageSectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order', 'asc')
            ->columns([
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('title')
                    ->label('Nama Section')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'hero' => 'primary',
                        'categories' => 'warning',
                        'product_showcase' => 'success',
                        'brand_story' => 'info',
                        'newsletter' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'hero' => 'Hero Banner Utama',
                        'categories' => 'Kategori Unggulan',
                        'product_showcase' => 'Showcase Produk',
                        'brand_story' => 'Kisah Brand & USP',
                        'newsletter' => 'Formulir Newsletter',
                        'custom_banner' => 'Custom Banner Promo',
                        default => ucfirst($state),
                    }),

                ToggleColumn::make('is_active')
                    ->label('Tampilkan di Web'),

                TextColumn::make('updated_at')
                    ->label('Terakhir Diedit')
                    ->dateTime('d M Y, H:i')
                    ->color('gray')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
