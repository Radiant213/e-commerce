<?php

namespace App\Filament\Cms\Resources\Banners\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
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
                        Section::make('Informasi Banner')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Judul Banner (ID)')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Mega Promo Diskon 50%'),

                                TextInput::make('title_translations.en')
                                    ->label('Judul Banner (EN)')
                                    ->maxLength(255)
                                    ->placeholder('Mega Sale 50% Off'),

                                Textarea::make('subtitle')
                                    ->label('Subjudul / Keterangan Singkat (ID)')
                                    ->rows(2),

                                Textarea::make('subtitle_translations.en')
                                    ->label('Subjudul (EN)')
                                    ->rows(2),

                                Grid::make(2)->schema([
                                    TextInput::make('image')
                                        ->label('URL Gambar Banner Desktop')
                                        ->required()
                                        ->placeholder('https://... atau /storage/...'),

                                    TextInput::make('image_mobile')
                                        ->label('URL Gambar Banner Mobile (Opsional)')
                                        ->placeholder('https://...'),
                                ]),

                                Grid::make(2)->schema([
                                    TextInput::make('link')
                                        ->label('Link Tujuan (URL)')
                                        ->placeholder('/products?promo=1'),

                                    TextInput::make('alt_text')
                                        ->label('Teks Alternatif (Alt Text)')
                                        ->placeholder('Promo diskon akhir pekan'),
                                ]),

                                Grid::make(2)->schema([
                                    TextInput::make('cta_text')
                                        ->label('Teks Tombol (ID)')
                                        ->placeholder('Belanja Sekarang'),

                                    TextInput::make('cta_text_translations.en')
                                        ->label('Teks Tombol (EN)')
                                        ->placeholder('Shop Now'),
                                ]),
                            ]),
                    ]),

                    // Right 1 col: Settings & Scheduling
                    Grid::make(1)->columnSpan(1)->schema([
                        Section::make('Pengaturan & Penayangan')
                            ->schema([
                                Select::make('placement')
                                    ->label('Penempatan Banner')
                                    ->options([
                                        'homepage' => 'Beranda Utama (Hero/Promo)',
                                        'category' => 'Halaman Kategori',
                                        'product' => 'Detail Produk',
                                        'sidebar' => 'Sidebar',
                                    ])
                                    ->default('homepage')
                                    ->required(),

                                TextInput::make('sort_order')
                                    ->label('Nomor Urut')
                                    ->numeric()
                                    ->default(0),

                                Toggle::make('is_active')
                                    ->label('Banner Aktif')
                                    ->default(true),

                                DateTimePicker::make('starts_at')
                                    ->label('Mulai Tayang (Opsional)')
                                    ->helperText('Kosongkan untuk langsung tayang'),

                                DateTimePicker::make('ends_at')
                                    ->label('Berakhir Tayang (Opsional)')
                                    ->helperText('Kosongkan jika tanpa batas waktu'),
                            ]),
                    ]),
                ]),
            ]);
    }
}
