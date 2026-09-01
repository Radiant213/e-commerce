<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Kategori'),
                \Filament\Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Produk'),
                \Filament\Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Harga Normal'),
                \Filament\Forms\Components\TextInput::make('sale_price')
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Harga Diskon (Opsional)'),
                \Filament\Forms\Components\TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->label('Stok'),
                \Filament\Forms\Components\TextInput::make('weight')
                    ->numeric()
                    ->suffix('gram')
                    ->label('Berat'),
                \Filament\Forms\Components\Textarea::make('short_description')
                    ->maxLength(500)
                    ->rows(2)
                    ->label('Deskripsi Singkat'),
                \Filament\Forms\Components\Textarea::make('description')
                    ->rows(5)
                    ->label('Deskripsi Lengkap'),
                \Filament\Forms\Components\Toggle::make('is_active')
                    ->default(true)
                    ->label('Aktifkan Produk'),
                \Filament\Forms\Components\Toggle::make('is_featured')
                    ->default(false)
                    ->label('Produk Unggulan'),
            ]);
    }
}
