<?php

namespace App\Filament\Cms\Resources\Popups\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
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
                        Section::make('Konten Pop-up')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Judul Pop-up (ID)')
                                    ->required()
                                    ->placeholder('Selamat Datang di Toko Kami! 🎉'),

                                TextInput::make('title_translations.en')
                                    ->label('Judul Pop-up (EN)')
                                    ->placeholder('Welcome to Our Store! 🎉'),

                                Textarea::make('description')
                                    ->label('Deskripsi / Pesan Promo (ID)')
                                    ->rows(3)
                                    ->placeholder('Gunakan kode promo FIRSTBUY untuk diskon 10%...'),

                                Textarea::make('description_translations.en')
                                    ->label('Deskripsi / Pesan Promo (EN)')
                                    ->rows(3),

                                TextInput::make('image')
                                    ->label('URL Gambar Banner Modal (Opsional)')
                                    ->placeholder('https://...'),

                                Grid::make(2)->schema([
                                    TextInput::make('cta_text')
                                        ->label('Teks Tombol Aksi (ID)')
                                        ->placeholder('Gunakan Sekarang'),

                                    TextInput::make('cta_link')
                                        ->label('Link Tombol Aksi')
                                        ->placeholder('/products'),
                                ]),
                            ]),
                    ]),

                    // Right 1 col: Settings & Triggers
                    Grid::make(1)->columnSpan(1)->schema([
                        Section::make('Pemicu & Penayangan')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Identifikasi Internal')
                                    ->required()
                                    ->helperText('Hanya terlihat di CMS'),

                                Select::make('type')
                                    ->label('Pemicu Tampil (Trigger)')
                                    ->options([
                                        'welcome' => 'Saat Pengunjung Masuk (Welcome)',
                                        'exit_intent' => 'Niat Keluar Website (Exit Intent)',
                                        'timed' => 'Setelah X Detik (Timed)',
                                        'scroll' => 'Setelah Scroll Halaman',
                                    ])
                                    ->default('welcome')
                                    ->required(),

                                TextInput::make('delay_seconds')
                                    ->label('Jeda Waktu (Detik)')
                                    ->numeric()
                                    ->default(3)
                                    ->helperText('Berapa detik setelah halaman dimuat pop-up muncul'),

                                Toggle::make('show_once_per_session')
                                    ->label('Tampilkan 1x Saja Per Sesi')
                                    ->helperText('Pengunjung tidak akan terganggu terus menerus')
                                    ->default(true),

                                Toggle::make('is_active')
                                    ->label('Pop-up Aktif')
                                    ->default(false),

                                DateTimePicker::make('starts_at')
                                    ->label('Mulai Berlaku (Opsional)'),

                                DateTimePicker::make('ends_at')
                                    ->label('Berakhir Pada (Opsional)'),
                            ]),
                    ]),
                ]),
            ]);
    }
}
