<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Kategori'),
                \Filament\Forms\Components\Textarea::make('description')
                    ->rows(3)
                    ->label('Deskripsi'),
                \Filament\Forms\Components\TextInput::make('image')
                    ->url()
                    ->label('URL Gambar Banner'),
                \Filament\Forms\Components\TextInput::make('icon')
                    ->label('Nama Ikon Lucide'),
                \Filament\Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->label('Urutan Tampilan'),
                \Filament\Forms\Components\Toggle::make('is_active')
                    ->default(true)
                    ->label('Status Aktif'),
            ]);
    }
}
