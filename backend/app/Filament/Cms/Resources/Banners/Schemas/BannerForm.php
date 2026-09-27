<?php

namespace App\Filament\Cms\Resources\Banners\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(3)->schema([
                    // Left 2 cols: Banner Info & Media
                    Grid::make(1)->columnSpan(2)->schema([
                        Section::make('Informasi Banner Promo')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Judul Banner')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Contoh: Diskon Spesial Akhir Pekan 50%'),

                                Textarea::make('subtitle')
                                    ->label('Keterangan Singkat / Subjudul')
                                    ->rows(2)
                                    ->placeholder('Contoh: Dapatkan penawaran terbatas untuk produk elektronik pilihan.'),

                                TextInput::make('image')
                                    ->label('URL Gambar Banner')
                                    ->required()
                                    ->placeholder('https://... atau tautan gambar')
                                    ->helperText('Gunakan tautan gambar banner beresolusi tinggi'),

                                Grid::make(2)->schema([
                                    TextInput::make('link')
                                        ->label('Link Tujuan Saat Diklik')
                                        ->placeholder('/products')
                                        ->default('/products')
                                        ->helperText('Contoh: /products atau link halaman promo'),

                                    TextInput::make('cta_text')
                                        ->label('Teks Tombol')
                                        ->placeholder('Belanja Sekarang')
                                        ->default('Belanja Sekarang'),
                                ]),
                            ]),
                    ]),

                    // Right 1 col: Settings
                    Grid::make(1)->columnSpan(1)->schema([
                        Section::make('Status Penayangan')
                            ->schema([
                                Toggle::make('is_active')
                                    ->label('Tayangkan Banner Ini')
                                    ->helperText('Aktifkan agar langsung tampil di beranda')
                                    ->default(true),

                                TextInput::make('sort_order')
                                    ->label('Urutan Tampil')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Angka lebih kecil tampil lebih dulu'),
                            ]),
                    ]),
                ]),
            ]);
    }
}
