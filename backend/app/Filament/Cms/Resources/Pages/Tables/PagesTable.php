<?php

namespace App\Filament\Cms\Resources\Pages\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Halaman')
                    ->weight('bold')
                    ->searchable()
                    ->description(fn ($record) => '/pages/' . $record->slug),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'published' => 'Tayang (Live)',
                        'draft' => 'Draf (Draft)',
                        default => ucfirst($state),
                    }),

                TextColumn::make('blocks_count')
                    ->counts('blocks')
                    ->badge()
                    ->color('info')
                    ->label('Blok Konten'),

                ToggleColumn::make('show_in_nav')
                    ->label('Di Navbar'),

                ToggleColumn::make('show_in_footer')
                    ->label('Di Footer'),

                TextColumn::make('updated_at')
                    ->label('Terakhir Diedit')
                    ->dateTime('d M Y, H:i')
                    ->color('gray')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'published' => 'Tayang (Published)',
                        'draft' => 'Draf (Draft)',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
