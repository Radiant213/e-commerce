<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                // Top 2-Column Grid (Information 2/3 and Pricing/Stock 1/3)
                Grid::make(3)
                    ->schema([
                        // Left 2 Columns: Main Info
                        Section::make('Informasi Produk')
                            ->description('Informasi utama dan deskripsi barang dagangan')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Nama Produk')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('slug')
                                            ->label('URL Slug')
                                            ->helperText('Otomatis dari nama atau sesuaikan')
                                            ->maxLength(255),

                                        TextInput::make('sku')
                                            ->label('Kode SKU / Barcode')
                                            ->placeholder('Contoh: PRD-ELK-001')
                                            ->helperText('Otomatis diisi sistem jika kosong'),
                                    ]),

                                Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->label('Kategori Produk'),

                                Textarea::make('short_description')
                                    ->maxLength(500)
                                    ->rows(2)
                                    ->label('Deskripsi Singkat (Ringkasan)'),

                                Textarea::make('description')
                                    ->rows(5)
                                    ->label('Deskripsi Lengkap & Spesifikasi'),
                            ])
                            ->columnSpan(2),

                        // Right 1 Column: Price & Stock & Visibility
                        Grid::make(1)
                            ->schema([
                                Section::make('Harga & Inventaris')
                                    ->schema([
                                        TextInput::make('price')
                                            ->required()
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->label('Harga Normal'),

                                        TextInput::make('sale_price')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->label('Harga Coret / Promo')
                                            ->helperText('Kosongkan jika tidak ada promo'),

                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('stock')
                                                    ->required()
                                                    ->numeric()
                                                    ->default(0)
                                                    ->label('Jumlah Stok'),

                                                TextInput::make('weight')
                                                    ->numeric()
                                                    ->suffix('g')
                                                    ->default(100)
                                                    ->label('Berat'),
                                            ]),
                                    ]),

                                Section::make('Status Visibilitas')
                                    ->schema([
                                        Toggle::make('is_active')
                                            ->default(true)
                                            ->label('Aktifkan di Toko'),

                                        Toggle::make('is_featured')
                                            ->default(false)
                                            ->label('Produk Unggulan'),
                                    ]),
                            ])
                            ->columnSpan(1),
                    ]),

                // Full-Width Bottom Section: Product Gallery
                Section::make('Galeri & Foto Produk')
                    ->description('Kelola foto produk yang akan ditampilkan kepada pembeli')
                    ->schema([
                        Repeater::make('images')
                            ->relationship('images')
                            ->schema([
                                TextInput::make('image_path')
                                    ->label('URL Gambar atau Path')
                                    ->required()
                                    ->placeholder('https://images.unsplash.com/... atau storage/products/...')
                                    ->columnSpan(3),

                                Toggle::make('is_primary')
                                    ->label('Gambar Utama')
                                    ->default(false)
                                    ->columnSpan(1),

                                TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0)
                                    ->columnSpan(1),
                            ])
                            ->columns(5)
                            ->defaultItems(1)
                            ->reorderable('sort_order')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['image_path'] ?? 'Foto Produk'),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
