<?php

namespace App\Filament\Cms\Resources\Banners\Tables;

use App\Models\Cms\Banner;
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
            ->reorderable('sort_order')
            ->columns([
                ImageColumn::make('image')
                    ->label('Pratinjau')
                    ->disk('public')
                    ->getStateUsing(fn (Banner $record) => $record->getImageUrl())
                    ->square()
                    ->size(70),

                TextColumn::make('title')
                    ->label('Judul Banner')
                    ->weight('bold')
                    ->searchable()
                    ->description(fn ($record) => $record->subtitle),

                TextColumn::make('placement')
                    ->label('Tampil di')
                    ->badge()
                    ->color(fn (?string $state): string => $state === Banner::PLACEMENT_HERO_SLIDER ? 'primary' : 'info')
                    ->formatStateUsing(fn (?string $state): string => Banner::placementOptions()[$state] ?? ucfirst((string) $state)),

                TextColumn::make('schedule')
                    ->label('Jadwal')
                    ->getStateUsing(function (Banner $record): string {
                        if (! $record->starts_at && ! $record->ends_at) {
                            return 'Tayang terus';
                        }

                        $from = $record->starts_at?->translatedFormat('d M Y H:i') ?? 'sekarang';
                        $to = $record->ends_at?->translatedFormat('d M Y H:i') ?? 'seterusnya';

                        return "{$from} → {$to}";
                    })
                    ->color('gray')
                    ->size('sm'),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),
            ])
            ->filters([
                SelectFilter::make('placement')
                    ->label('Tampil di')
                    ->options(Banner::placementOptions()),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
