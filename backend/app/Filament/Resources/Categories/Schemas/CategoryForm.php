<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kategori')
                    ->description('Detail nama kategori, ikon, dan gambar banner')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Nama Kategori')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Slug URL')
                                    ->helperText('Otomatis di-generate dari nama'),
                            ]),

                        Textarea::make('description')
                            ->rows(3)
                            ->label('Deskripsi Kategori'),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('image')
                                    ->label('URL Gambar Banner')
                                    ->placeholder('https://images.unsplash.com/...')
                                    ->columnSpan(2),

                                TextInput::make('icon')
                                    ->label('Nama Ikon Lucide')
                                    ->placeholder('Contoh: Smartphone, Shirt, Watch'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->label('Urutan Tampilan'),

                                Toggle::make('is_active')
                                    ->default(true)
                                    ->label('Status Aktif')
                                    ->helperText('Kategori muncul di navigasi pembeli'),
                            ]),
                    ]),
            ]);
    }
}
