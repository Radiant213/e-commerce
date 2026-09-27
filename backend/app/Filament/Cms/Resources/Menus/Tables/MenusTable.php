<?php

namespace App\Filament\Cms\Resources\Menus\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class MenusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Menu')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('location')
                    ->label('Penempatan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'header' => 'primary',
                        'mobile' => 'info',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'header' => 'Header (Navbar Atas)',
                        'mobile' => 'Mobile Drawer',
                        'footer_col_1' => 'Footer Kolom 1 (Belanja)',
                        'footer_col_2' => 'Footer Kolom 2 (Bantuan)',
                        'footer_col_3' => 'Footer Kolom 3 (Legal/Info)',
                        default => ucfirst($state),
                    }),

                TextColumn::make('items_count')
                    ->counts('allItems')
                    ->badge()
                    ->color('gray')
                    ->label('Jumlah Link'),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),

                TextColumn::make('updated_at')
                    ->label('Terakhir Diedit')
                    ->dateTime('d M Y, H:i')
                    ->color('gray'),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
