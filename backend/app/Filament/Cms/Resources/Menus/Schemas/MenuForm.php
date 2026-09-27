<?php

namespace App\Filament\Cms\Resources\Menus\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(3)->schema([
                    // Left 2 cols: Menu Items
                    Grid::make(1)->columnSpan(2)->schema([
                        Section::make('Daftar Tautan Menu')
                            ->description('Tambah, ubah, atau hapus link tautan dalam menu ini.')
                            ->schema([
                                Repeater::make('allItems')
                                    ->relationship('allItems')
                                    ->label('Item Tautan')
                                    ->orderColumn('sort_order')
                                    ->reorderable()
                                    ->schema([
                                        Grid::make(3)->schema([
                                            TextInput::make('label')
                                                ->label('Label Teks')
                                                ->required()
                                                ->placeholder('Contoh: Beranda'),

                                            TextInput::make('url')
                                                ->label('URL Tautan')
                                                ->required()
                                                ->placeholder('/products atau https://...'),

                                            Select::make('target')
                                                ->label('Buka di')
                                                ->options([
                                                    '_self' => 'Tab Saat Ini (_self)',
                                                    '_blank' => 'Tab Baru (_blank)',
                                                ])
                                                ->default('_self'),
                                        ]),

                                        Grid::make(3)->schema([
                                            TextInput::make('label_translations.en')
                                                ->label('Label (Inggris - EN)')
                                                ->placeholder('Contoh: Home'),

                                            Select::make('type')
                                                ->label('Tipe Link')
                                                ->options([
                                                    'custom' => 'Kustom URL',
                                                    'page' => 'Halaman Statis',
                                                    'category' => 'Kategori Produk',
                                                    'product' => 'Detail Produk',
                                                ])
                                                ->default('custom'),

                                            Toggle::make('is_visible')
                                                ->label('Tampilkan Link')
                                                ->default(true),
                                        ]),
                                    ])
                                    ->defaultItems(1)
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                            ]),
                    ]),

                    // Right 1 col: Menu Settings
                    Grid::make(1)->columnSpan(1)->schema([
                        Section::make('Informasi Menu')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Menu')
                                    ->required()
                                    ->placeholder('Navigasi Utama'),

                                TextInput::make('location')
                                    ->label('Kode Penempatan')
                                    ->disabled()
                                    ->helperText('Lokasi penempatan menu di sistem frontend'),

                                Toggle::make('is_active')
                                    ->label('Menu Aktif')
                                    ->default(true),
                            ]),
                    ]),
                ]),
            ]);
    }
}
