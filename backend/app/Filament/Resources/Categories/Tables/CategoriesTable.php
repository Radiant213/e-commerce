<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Banner')
                    ->circular()
                    ->defaultImageUrl('https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=100&q=80'),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->label('Nama Kategori'),

                TextColumn::make('slug')
                    ->searchable()
                    ->color('gray')
                    ->label('Slug'),

                TextColumn::make('products_count')
                    ->counts('products')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->label('Jumlah Produk'),

                TextColumn::make('icon')
                    ->badge()
                    ->color('gray')
                    ->label('Ikon'),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),

                TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable()
                    ->label('Urutan'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
