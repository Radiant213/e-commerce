<?php

namespace App\Filament\Cms\Resources\EmailTemplates\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class EmailTemplatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Template')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('slug')
                    ->label('Kode Slug')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('subject')
                    ->label('Subjek Email')
                    ->searchable()
                    ->limit(40),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),

                TextColumn::make('updated_at')
                    ->label('Terakhir Diedit')
                    ->dateTime('d M Y, H:i')
                    ->color('gray'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
