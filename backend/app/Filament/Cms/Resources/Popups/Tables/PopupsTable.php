<?php

namespace App\Filament\Cms\Resources\Popups\Tables;

use App\Models\Cms\Popup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class PopupsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->weight('bold')
                    ->searchable()
                    ->description(fn (Popup $record) => $record->title),

                TextColumn::make('show_on')
                    ->label('Tampil di')
                    ->badge()
                    ->color(fn (?string $state): string => $state === 'all' ? 'info' : 'primary')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'all' => 'Semua Halaman',
                        default => 'Beranda Saja',
                    }),

                TextColumn::make('schedule')
                    ->label('Jadwal')
                    ->getStateUsing(function (Popup $record): string {
                        if (! $record->starts_at && ! $record->ends_at) {
                            return 'Tayang terus';
                        }

                        $from = $record->starts_at?->translatedFormat('d M Y H:i') ?? 'sekarang';
                        $to = $record->ends_at?->translatedFormat('d M Y H:i') ?? 'seterusnya';

                        return "{$from} → {$to}";
                    })
                    ->color('gray')
                    ->size('sm'),

                TextColumn::make('delay_seconds')
                    ->label('Jeda')
                    ->suffix(' dtk')
                    ->badge()
                    ->color('gray'),

                ToggleColumn::make('show_once_per_session')
                    ->label('1x Per Sesi'),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
