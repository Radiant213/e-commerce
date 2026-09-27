<?php

namespace App\Filament\Cms\Resources\EmailTemplates\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmailTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(3)->schema([
                    // Left 2 cols: Content
                    Grid::make(1)->columnSpan(2)->schema([
                        Section::make('Konten Template Email')
                            ->description('Tersedia placeholder dinamis seperti: {customer_name}, {order_number}, {total_amount}')
                            ->schema([
                                TextInput::make('subject')
                                    ->label('Subjek Email (ID)')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Pesanan #{order_number} Telah Diterima'),

                                TextInput::make('subject_translations.en')
                                    ->label('Subjek Email (EN)')
                                    ->placeholder('Order #{order_number} Received'),

                                Textarea::make('header_text')
                                    ->label('Teks Header / Sambutan (ID)')
                                    ->rows(2)
                                    ->placeholder('Terima kasih telah berbelanja di Radiant Studio!'),

                                Textarea::make('body_content')
                                    ->label('Isi Pesan Utama Email (ID)')
                                    ->rows(6)
                                    ->placeholder('Halo {customer_name}, pesanan Anda sedang kami siapkan...'),

                                Textarea::make('body_content_translations.en')
                                    ->label('Isi Pesan Utama Email (EN)')
                                    ->rows(5),

                                Textarea::make('footer_text')
                                    ->label('Teks Footer / Penutup (ID)')
                                    ->rows(2)
                                    ->placeholder('Ada pertanyaan? Hubungi customer service kami.'),
                            ]),
                    ]),

                    // Right 1 col: Settings & Identifiers
                    Grid::make(1)->columnSpan(1)->schema([
                        Section::make('Identifikasi')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Template')
                                    ->required()
                                    ->placeholder('Konfirmasi Pesanan'),

                                TextInput::make('slug')
                                    ->label('Kode Slug (System ID)')
                                    ->required()
                                    ->disabled(fn ($record) => $record !== null)
                                    ->helperText('Digunakan oleh sistem backend untuk memanggil template'),

                                Toggle::make('is_active')
                                    ->label('Template Aktif')
                                    ->default(true),
                            ]),
                    ]),
                ]),
            ]);
    }
}
