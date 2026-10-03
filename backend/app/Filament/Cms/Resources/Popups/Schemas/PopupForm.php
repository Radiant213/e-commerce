<?php

namespace App\Filament\Cms\Resources\Popups\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PopupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(3)->schema([
                    // Left 2 cols: Content
                    Grid::make(1)->columnSpan(2)->schema([
                        Section::make('Konten Pop-up Penawaran')
                            ->description('Pesan promosi atau voucher yang akan muncul kepada pengunjung toko.')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Judul Pop-up')
                                    ->required()
                                    ->placeholder('Contoh: Selamat Datang di Toko Kami! 🎉'),

                                Textarea::make('description')
                                    ->label('Pesan / Keterangan Promo')
                                    ->rows(3)
                                    ->required()
                                    ->placeholder('Contoh: Gunakan kode voucher RADIANT10 untuk potongan Rp 25.000 belanja pertama Anda.'),

                                // Preview for older pop-ups that still use an external image link.
                                Image::make(fn ($record) => (string) $record?->image, 'Gambar saat ini')
                                    ->imageHeight('8rem')
                                    ->visible(fn ($record) => str_starts_with((string) $record?->image, 'http')),

                                FileUpload::make('image')
                                    ->label('Gambar Pop-up (opsional)')
                                    ->image()
                                    ->disk('public')
                                    ->directory('cms/popups')
                                    ->visibility('public')
                                    ->maxSize(2048)
                                    ->imageEditor()
                                    ->helperText('Ukuran ideal 800 × 600 px. Format JPG/PNG/WebP, maks 2 MB.'),

                                Grid::make(2)->schema([
                                    TextInput::make('cta_text')
                                        ->label('Teks Tombol')
                                        ->placeholder('Klaim Voucher Sekarang')
                                        ->default('Belanja Sekarang'),

                                    TextInput::make('cta_link')
                                        ->label('Link Tujuan Tombol')
                                        ->placeholder('/products')
                                        ->default('/products'),
                                ]),
                            ]),
                    ]),

                    // Right 1 col: Settings
                    Grid::make(1)->columnSpan(1)->schema([
                        Section::make('Pengaturan Tampil')
                            ->schema([
                                Toggle::make('is_active')
                                    ->label('Aktifkan Pop-up')
                                    ->helperText('Jika aktif, pop-up akan otomatis tampil saat pengunjung membuka web')
                                    ->default(true),

                                Radio::make('show_on')
                                    ->label('Tampil di Halaman')
                                    ->options([
                                        'home' => 'Beranda saja',
                                        'all' => 'Semua halaman',
                                    ])
                                    ->default('home')
                                    ->required(),

                                TextInput::make('delay_seconds')
                                    ->label('Waktu Tunggu Muncul (Detik)')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(2)
                                    ->helperText('Berapa detik setelah halaman terbuka pop-up akan muncul'),

                                Toggle::make('show_once_per_session')
                                    ->label('Cukup Sekali per Kunjungan')
                                    ->helperText('Biar pengunjung nggak terganggu pop-up berulang')
                                    ->default(true),

                                TextInput::make('name')
                                    ->label('Nama Pengenal')
                                    ->default('Promo Toko')
                                    ->helperText('Hanya terlihat di dalam dashboard CMS'),
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
                                    ->helperText('Setelah waktu ini pop-up otomatis berhenti tampil.'),
                            ]),
                    ]),
                ]),
            ]);
    }
}
