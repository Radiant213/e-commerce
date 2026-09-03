<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl('https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80'),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->label('Nama Lengkap'),

                TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Alamat email disalin!')
                    ->label('Email'),

                TextColumn::make('phone')
                    ->searchable()
                    ->placeholder('-')
                    ->label('No. HP / WhatsApp'),

                TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'primary',
                        'customer' => 'gray',
                        default => 'gray',
                    })
                    ->label('Peran Akun'),

                TextColumn::make('orders_count')
                    ->counts('orders')
                    ->badge()
                    ->color('success')
                    ->sortable()
                    ->label('Total Order'),

                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->label('Terdaftar Sejak'),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Filter Peran')
                    ->options([
                        'admin' => 'Administrator',
                        'customer' => 'Customer',
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
