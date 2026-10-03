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
                            ->description('Atur daftar link navigasi yang muncul di menu ini.')
                            ->schema([
                                Repeater::make('allItems')
                                    ->relationship('allItems')
                                    ->label('Daftar Link')
                                    ->orderColumn('sort_order')
                                    ->reorderable()
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('label')
                                                ->label('Nama Link (Teks)')
                                                ->required()
                                                ->placeholder('Contoh: Produk'),

                                            TextInput::make('url')
                                                ->label('Tujuan Link (URL)')
                                                ->required()
                                                ->placeholder('/products atau https://...'),
                                        ]),

                                        Grid::make(3)->schema([
                                            Select::make('target')
                                                ->label('Buka Link di')
                                                ->options([
                                                    '_self' => 'Tab Saat Ini',
                                                    '_blank' => 'Tab Baru (Halaman Baru)',
                                                ])
                                                ->default('_self')
                                                ->native(false),

                                            TextInput::make('label_translations.en')
                                                ->label('Nama (Inggris/EN)')
                                                ->placeholder('Contoh: Products'),

                                            Toggle::make('is_visible')
                                                ->label('Tampilkan Link Ini')
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
                                    ->label('Lokasi Menu')
                                    ->disabled()
                                    ->helperText('Lokasi penempatan menu ini di website'),

                                Toggle::make('is_active')
                                    ->label('Aktifkan Menu Ini')
                                    ->default(true),
                            ]),
                    ]),
                ]),
            ]);
    }
}
