<?php

namespace App\Filament\Cms\Resources\Popups\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
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

                                TextInput::make('image')
                                    ->label('URL Gambar Pop-up (Opsional)')
                                    ->placeholder('https://... atau tautan foto promo'),

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

                                TextInput::make('delay_seconds')
                                    ->label('Waktu Tunggu Muncul (Detik)')
                                    ->numeric()
                                    ->default(2)
                                    ->helperText('Berapa detik setelah halaman terbuka pop-up akan muncul'),

                                TextInput::make('name')
                                    ->label('Nama Pengenal')
                                    ->default('Promo Toko')
                                    ->helperText('Hanya terlihat di dalam dashboard CMS'),
                            ]),
                    ]),
                ]),
            ]);
    }
}
