<?php

namespace App\Filament\Cms\Resources\Banners\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BannersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order', 'asc')
            ->columns([
                ImageColumn::make('image')
                    ->label('Pratinjau')
                    ->square()
                    ->size(70),

                TextColumn::make('title')
                    ->label('Judul Banner')
                    ->weight('bold')
                    ->searchable()
                    ->description(fn ($record) => $record->subtitle),

                TextColumn::make('placement')
                    ->label('Penempatan')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'homepage' => 'Beranda Utama',
                        'category' => 'Halaman Kategori',
                        'product' => 'Detail Produk',
                        'sidebar' => 'Sidebar',
                        default => ucfirst($state),
                    }),

                TextColumn::make('link')
                    ->label('Link Tujuan')
                    ->color('gray')
                    ->limit(25),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),

                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('placement')
                    ->label('Penempatan')
                    ->options([
                        'homepage' => 'Beranda Utama',
                        'category' => 'Halaman Kategori',
                        'product' => 'Detail Produk',
                        'sidebar' => 'Sidebar',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
