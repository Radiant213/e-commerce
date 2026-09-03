<?php

namespace App\Filament\Resources\Reviews\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['product', 'user']))
            ->columns([
                TextColumn::make('product.name')
                    ->searchable()
                    ->sortable()
                    ->label('Produk')
                    ->weight('bold'),
                TextColumn::make('user.name')
                    ->searchable()
                    ->label('Pelanggan'),
                TextColumn::make('rating')
                    ->badge()
                    ->icon('heroicon-m-star')
                    ->color(fn (int $state): string => match ($state) {
                        5 => 'success',
                        4 => 'info',
                        3 => 'warning',
                        default => 'danger',
                    })
                    ->label('Rating Bintang')
                    ->sortable(),
                TextColumn::make('comment')
                    ->limit(60)
                    ->tooltip(fn ($record) => $record->comment)
                    ->label('Komentar Pembeli'),
                TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->label('Waktu'),
            ])
            ->filters([
                SelectFilter::make('rating')
                    ->label('Filter Rating')
                    ->options([
                        5 => '5 Bintang',
                        4 => '4 Bintang',
                        3 => '3 Bintang',
                        2 => '2 Bintang',
                        1 => '1 Bintang',
                    ]),
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
