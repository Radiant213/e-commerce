<?php

namespace App\Filament\Resources\Settings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('group')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('label')
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('key')
                    ->sortable()
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('value')
                    ->limit(50),
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
