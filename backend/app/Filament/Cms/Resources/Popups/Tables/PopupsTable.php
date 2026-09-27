<?php

namespace App\Filament\Cms\Resources\Popups\Tables;

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
                    ->label('Nama Internal')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('title')
                    ->label('Judul Pop-up')
                    ->searchable()
                    ->limit(35),

                TextColumn::make('type')
                    ->label('Pemicu (Trigger)')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'welcome' => 'success',
                        'exit_intent' => 'danger',
                        'timed' => 'warning',
                        'scroll' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'welcome' => 'Saat Buka Halaman (Welcome)',
                        'exit_intent' => 'Niat Keluar (Exit Intent)',
                        'timed' => 'Berdasarkan Detik (Timed)',
                        'scroll' => 'Berdasarkan Scroll',
                        default => ucfirst($state),
                    }),

                TextColumn::make('delay_seconds')
                    ->label('Jeda Waktu')
                    ->suffix(' detik')
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
