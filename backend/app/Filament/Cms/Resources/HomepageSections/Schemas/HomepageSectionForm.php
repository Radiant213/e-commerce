<?php

namespace App\Filament\Cms\Resources\HomepageSections\Schemas;

use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Callout;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HomepageSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(3)->schema([
                    // Left 2 cols: Content
                    Grid::make(1)->columnSpan(2)->schema([
                        Section::make('Konten Section')
                            ->description('Kustomisasi teks, tombol, dan isi section ini.')
                            ->schema([
                                Callout::make('info_promo_strip')
                                    ->info()
                                    ->title('Tentang Strip Promo')
                                    ->description('Gambar dan link banner pada section ini diatur di menu "Banner & Slider" dengan memilih penempatan "Strip Promo (tengah beranda)".')
                                    ->visible(fn ($record) => $record?->type === 'promo_strip'),

                                TextInput::make('config.badge')
                                    ->label('Badge / Label Atas')
                                    ->placeholder('Contoh: ✨ New Season 2026')
                                    ->visible(fn ($record) => in_array($record?->type, ['hero', 'categories', 'product_showcase', 'brand_story'])),

                                TextInput::make('config.title')
                                    ->label('Judul Utama Section')
                                    ->placeholder('Masukkan judul yang menarik...'),

                                Textarea::make('config.subtitle')
                                    ->label('Subjudul / Deskripsi')
                                    ->rows(3)
                                    ->placeholder('Deskripsi ringkas yang mendukung judul...'),

                                // CTA buttons for Hero
                                Grid::make(2)
                                    ->visible(fn ($record) => $record?->type === 'hero')
                                    ->schema([
                                        TextInput::make('config.cta_primary_text')
                                            ->label('Tombol Utama (Teks)')
                                            ->placeholder('Jelajahi Koleksi'),
                                        TextInput::make('config.cta_primary_link')
                                            ->label('Tombol Utama (Link URL)')
                                            ->placeholder('/products'),
                                        TextInput::make('config.cta_secondary_text')
                                            ->label('Tombol Kedua (Teks)')
                                            ->placeholder('Produk Unggulan'),
                                        TextInput::make('config.cta_secondary_link')
                                            ->label('Tombol Kedua (Link URL)')
                                            ->placeholder('/products?is_featured=1'),
                                    ]),

                                // Brand Story specific: Image and Points
                                FileUpload::make('config.image')
                                    ->label('Gambar Cerita Brand')
                                    ->image()
                                    ->disk('public')
                                    ->directory('cms/brand')
                                    ->visibility('public')
                                    ->maxSize(3072)
                                    ->imageEditor()
                                    ->helperText('Ukuran ideal 1000 × 625 px (rasio 16:10). Format JPG/PNG/WebP, maks 3 MB.')
                                    ->visible(fn ($record) => $record?->type === 'brand_story'),

                                Repeater::make('config.points')
                                    ->label('Poin Keunggulan Brand')
                                    ->visible(fn ($record) => $record?->type === 'brand_story')
                                    ->schema([
                                        TextInput::make('text')
                                            ->label('Poin')
                                            ->required()
                                            ->placeholder('Contoh: 100% Produk Original & Bergaransi Resmi'),
                                    ])
                                    ->defaultItems(3)
                                    ->collapsible(),

                                // CTA for Brand Story
                                Grid::make(2)
                                    ->visible(fn ($record) => $record?->type === 'brand_story')
                                    ->schema([
                                        TextInput::make('config.cta_text')
                                            ->label('Teks Tombol CTA')
                                            ->placeholder('Pelajari Lebih Lanjut'),
                                        TextInput::make('config.cta_link')
                                            ->label('Link Tombol CTA')
                                            ->placeholder('/pages/about'),
                                    ]),

                                // Newsletter Button
                                TextInput::make('config.button_text')
                                    ->label('Teks Tombol Submit')
                                    ->visible(fn ($record) => $record?->type === 'newsletter'),

                                // Product Showcase specific: Mode selection, pinned products, and tabs
                                Radio::make('config.selection_mode')
                                    ->label('Mode Penentuan Produk')
                                    ->options([
                                        'auto' => 'Otomatis (Berdasarkan Produk Paling Populer / Featured)',
                                        'manual' => 'Pilih Manual (Pin Produk Pilihan Sendiri)',
                                    ])
                                    ->default('auto')
                                    ->inline()
                                    ->live()
                                    ->helperText('Pilih "Pilih Manual" jika ingin menentukan produk spesifik yang ditampilkan di etalase beranda.')
                                    ->visible(fn ($record) => $record?->type === 'product_showcase'),

                                Select::make('config.pinned_product_ids')
                                    ->label('Pilih & Urutkan Produk Unggulan')
                                    ->multiple()
                                    ->reorderable()
                                    ->searchable()
                                    ->preload()
                                    ->options(fn () => Product::where('is_active', true)->pluck('name', 'id')->toArray())
                                    ->helperText('Pilih produk yang ingin dipin. Anda dapat mengubah urutan tampilan produk dengan menggeser (drag & drop) item.')
                                    ->visible(fn ($record, $get) => $record?->type === 'product_showcase' && $get('config.selection_mode') === 'manual'),

                                TextInput::make('config.manual_tab_label')
                                    ->label('Label Tab Produk Pilihan')
                                    ->placeholder('Contoh: Pilihan Kami / Spotlight')
                                    ->default('Pilihan Kami')
                                    ->visible(fn ($record, $get) => $record?->type === 'product_showcase' && $get('config.selection_mode') === 'manual'),

                                Toggle::make('config.show_tabs')
                                    ->label('Tampilkan Tab Filter (Pilihan, Terlaris, Terbaru)')
                                    ->helperText('Jika dimatikan, hanya produk unggulan yang ditampilkan tanpa tombol pemilih tab.')
                                    ->default(true)
                                    ->visible(fn ($record) => $record?->type === 'product_showcase'),

                                // Limit for Products / Categories
                                TextInput::make('config.limit')
                                    ->label('Jumlah Item Ditampilkan')
                                    ->numeric()
                                    ->default(8)
                                    ->visible(fn ($record) => in_array($record?->type, ['product_showcase', 'categories'])),

                                // Stats repeater for Hero
                                Repeater::make('config.stats')
                                    ->label('Statistik Highlight')
                                    ->visible(fn ($record) => $record?->type === 'hero')
                                    ->schema([
                                        TextInput::make('value')
                                            ->label('Angka / Nilai')
                                            ->placeholder('500+'),
                                        TextInput::make('label')
                                            ->label('Keterangan')
                                            ->placeholder('Produk Terkurasi'),
                                    ])
                                    ->columns(2),
                            ]),

                        // Translations
                        Section::make('Terjemahan Bahasa Inggris (Opsional)')
                            ->collapsed()
                            ->schema([
                                TextInput::make('config_translations.en.badge')
                                    ->label('Badge (EN)')
                                    ->visible(fn ($record) => in_array($record?->type, ['hero', 'categories', 'product_showcase', 'brand_story'])),
                                TextInput::make('config_translations.en.title')
                                    ->label('Judul Utama (EN)'),
                                Textarea::make('config_translations.en.subtitle')
                                    ->label('Subjudul (EN)')
                                    ->rows(3),
                                TextInput::make('config_translations.en.manual_tab_label')
                                    ->label('Manual Tab Label (EN)')
                                    ->visible(fn ($record) => $record?->type === 'product_showcase'),
                                TextInput::make('config_translations.en.cta_primary_text')
                                    ->label('Primary CTA Text (EN)')
                                    ->visible(fn ($record) => $record?->type === 'hero'),
                            ]),
                    ]),

                    // Right 1 col: Settings
                    Grid::make(1)->columnSpan(1)->schema([
                        Section::make('Status Penayangan')
                            ->schema([
                                Toggle::make('is_active')
                                    ->label('Aktif & Tampil di Web')
                                    ->helperText('Matikan untuk menyembunyikan section ini dari beranda')
                                    ->default(true),

                                TextInput::make('title')
                                    ->label('Nama Bagian')
                                    ->required()
                                    ->helperText('Nama pengenal di CMS'),
                            ]),
                    ]),
                ]),
            ]);
    }
}
