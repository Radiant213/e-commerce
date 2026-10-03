<?php

namespace App\Filament\Cms\Resources\Banners\Schemas;

use App\Models\Cms\Banner;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class BannerForm
{
    /** Size guide shown under the upload field, per placement. */
    public static function sizeGuide(?string $placement): string
    {
        return match ($placement) {
            Banner::PLACEMENT_HERO_SLIDER => 'Ukuran ideal 1000 × 1250 px (potret / berdiri, rasio 4:5). Format JPG/PNG/WebP, maks 3 MB.',
            default => 'Ukuran ideal 1200 × 600 px (melebar, rasio 2:1). Format JPG/PNG/WebP, maks 3 MB.',
        };
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(3)->schema([
                    // Left 2 cols: Banner Info & Media
                    Grid::make(1)->columnSpan(2)->schema([
                        Section::make('Gambar Banner')
                            ->description('Pilih dulu mau tampil di mana, lalu upload gambarnya.')
                            ->schema([
                                Select::make('placement')
                                    ->label('Tampil di Mana?')
                                    ->options(Banner::placementOptions())
                                    ->default(Banner::PLACEMENT_HERO_SLIDER)
                                    ->required()
                                    ->native(false)
                                    ->live()
                                    ->helperText('Slider Hero = gambar besar di kanan atas beranda. Strip Promo = banner lebar di tengah beranda.'),

                                // Preview for older banners that still use an external image link.
                                Image::make(fn ($record) => (string) $record?->image, 'Gambar saat ini')
                                    ->imageHeight('10rem')
                                    ->visible(fn ($record) => str_starts_with((string) $record?->image, 'http')),

                                FileUpload::make('image')
                                    ->label('Upload Gambar')
                                    ->image()
                                    ->disk('public')
                                    ->directory('cms/banners')
                                    ->visibility('public')
                                    ->maxSize(3072)
                                    ->imageEditor()
                                    ->required(fn ($record) => blank($record?->image))
                                    ->helperText(fn (Get $get, $record) => self::sizeGuide($get('placement'))
                                        . (str_starts_with((string) $record?->image, 'http')
                                            ? ' Kosongkan kalau mau tetap pakai gambar saat ini.'
                                            : '')),
                            ]),

                        Section::make('Tulisan di Banner (opsional)')
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

                                Grid::make(2)->schema([
                                    TextInput::make('link')
                                        ->label('Link Tujuan Saat Diklik')
                                        ->placeholder('/products')
                                        ->default('/products')
                                        ->helperText('Contoh: /products atau /products?category_id=2'),

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
                                    ->helperText('Matikan untuk menyembunyikan tanpa menghapus')
                                    ->default(true),

                                TextInput::make('sort_order')
                                    ->label('Urutan Tampil')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Angka lebih kecil tampil lebih dulu'),
                            ]),

                        Section::make('Jadwal Tayang (opsional)')
                            ->description('Kosongkan kalau mau tayang terus.')
                            ->schema([
                                DateTimePicker::make('starts_at')
                                    ->label('Mulai Tayang')
                                    ->native(false)
                                    ->seconds(false),

                                DateTimePicker::make('ends_at')
                                    ->label('Selesai Tayang')
                                    ->native(false)
                                    ->seconds(false)
                                    ->helperText('Setelah waktu ini banner otomatis hilang.'),
                            ]),
                    ]),
                ]),
            ]);
    }
}
