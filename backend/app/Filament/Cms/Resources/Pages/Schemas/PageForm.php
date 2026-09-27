<?php

namespace App\Filament\Cms\Resources\Pages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(3)->schema([
                    // Left 2 cols: Main Content & Blocks
                    Grid::make(1)->columnSpan(2)->schema([
                        Section::make('Informasi Dasar Halaman')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Judul Halaman (ID)')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set, $record) {
                                        if (!$record) {
                                            $set('slug', Str::slug($state));
                                        }
                                    }),

                                Grid::make(2)->schema([
                                    TextInput::make('slug')
                                        ->label('URL Slug')
                                        ->required()
                                        ->maxLength(255)
                                        ->prefix('/pages/')
                                        ->helperText('Alamat URL unik untuk halaman ini'),

                                    TextInput::make('title_translations.en')
                                        ->label('Judul Halaman (Inggris - EN)')
                                        ->placeholder('About Us, FAQ, etc.'),
                                ]),
                            ]),

                        Section::make('Blok Konten Dinamis')
                            ->description('Susun konten halaman menggunakan blok-blok modular (Heading, Teks, FAQ, Gambar, dll).')
                            ->schema([
                                Repeater::make('blocks')
                                    ->relationship('blocks')
                                    ->label('Blok Konten')
                                    ->orderColumn('sort_order')
                                    ->reorderable()
                                    ->schema([
                                        Grid::make(3)->schema([
                                            Select::make('type')
                                                ->label('Tipe Blok')
                                                ->required()
                                                ->options([
                                                    'heading' => '📌 Heading / Judul',
                                                    'text' => '📝 Paragraf / Teks Bebas',
                                                    'accordion' => '📂 Accordion / FAQ (Dropdown)',
                                                    'image' => '🖼️ Gambar Tunggal',
                                                    'cta' => '📢 Banner Call to Action',
                                                ])
                                                ->live()
                                                ->default('text'),

                                            Toggle::make('is_visible')
                                                ->label('Tampilkan Blok')
                                                ->default(true),
                                        ]),

                                        // Heading Block Fields
                                        Grid::make(3)
                                            ->visible(fn ($get) => $get('type') === 'heading')
                                            ->schema([
                                                Select::make('content.level')
                                                    ->label('Ukuran Level')
                                                    ->options([
                                                        1 => 'H1 (Sangat Besar)',
                                                        2 => 'H2 (Besar)',
                                                        3 => 'H3 (Sedang)',
                                                    ])
                                                    ->default(2),

                                                TextInput::make('content.text')
                                                    ->label('Teks Heading (ID)')
                                                    ->columnSpan(2),

                                                TextInput::make('content_translations.en.text')
                                                    ->label('Teks Heading (EN)')
                                                    ->columnSpan(3),
                                            ]),

                                        // Text Block Fields
                                        Grid::make(1)
                                            ->visible(fn ($get) => $get('type') === 'text')
                                            ->schema([
                                                Textarea::make('content.body')
                                                    ->label('Isi Konten Teks (ID)')
                                                    ->rows(5),

                                                Textarea::make('content_translations.en.body')
                                                    ->label('Isi Konten Teks (EN)')
                                                    ->rows(4),
                                            ]),

                                        // Accordion Block Fields (FAQ Items)
                                        Grid::make(1)
                                            ->visible(fn ($get) => $get('type') === 'accordion')
                                            ->schema([
                                                Repeater::make('content.items')
                                                    ->label('Daftar Item Accordion / FAQ')
                                                    ->schema([
                                                        TextInput::make('title')
                                                            ->label('Pertanyaan / Judul')
                                                            ->required(),
                                                        Textarea::make('body')
                                                            ->label('Jawaban / Penjelasan')
                                                            ->rows(3)
                                                            ->required(),
                                                    ])
                                                    ->collapsible()
                                                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                                                    ->defaultItems(1),
                                            ]),

                                        // Image Block Fields
                                        Grid::make(2)
                                            ->visible(fn ($get) => $get('type') === 'image')
                                            ->schema([
                                                TextInput::make('content.src')
                                                    ->label('URL Gambar')
                                                    ->placeholder('https://images.unsplash.com/...'),
                                                TextInput::make('content.alt')
                                                    ->label('Teks Alternatif (Alt Text)'),
                                                TextInput::make('content.caption')
                                                    ->label('Keterangan Gambar (Caption)')
                                                    ->columnSpan(2),
                                            ]),

                                        // CTA Block Fields
                                        Grid::make(2)
                                            ->visible(fn ($get) => $get('type') === 'cta')
                                            ->schema([
                                                TextInput::make('content.title')
                                                    ->label('Judul CTA'),
                                                TextInput::make('content.button_text')
                                                    ->label('Teks Tombol'),
                                                Textarea::make('content.description')
                                                    ->label('Deskripsi Singkat')
                                                    ->columnSpan(2)
                                                    ->rows(2),
                                                TextInput::make('content.button_link')
                                                    ->label('Link Tombol')
                                                    ->placeholder('/products')
                                                    ->columnSpan(2),
                                            ]),
                                    ])
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => match ($state['type'] ?? '') {
                                        'heading' => 'Heading: ' . ($state['content']['text'] ?? 'Untitled'),
                                        'text' => 'Teks Paragraf',
                                        'accordion' => 'Accordion / FAQ (' . count($state['content']['items'] ?? []) . ' item)',
                                        'image' => 'Gambar',
                                        'cta' => 'CTA: ' . ($state['content']['title'] ?? ''),
                                        default => 'Blok Konten',
                                    }),
                            ]),
                    ]),

                    // Right 1 col: Meta & Publishing Settings
                    Grid::make(1)->columnSpan(1)->schema([
                        Section::make('Publikasi & Tampilan')
                            ->schema([
                                Select::make('status')
                                    ->label('Status Halaman')
                                    ->options([
                                        'published' => '✅ Tayang (Published)',
                                        'draft' => '📝 Draf (Draft)',
                                    ])
                                    ->default('published')
                                    ->required(),

                                Select::make('template')
                                    ->label('Template Layout')
                                    ->options([
                                        'default' => 'Standar (Dengan Padding)',
                                        'full-width' => 'Lebar Penuh (Full Width)',
                                        'with-sidebar' => 'Dengan Sidebar Bantuan',
                                    ])
                                    ->default('default'),

                                TextInput::make('sort_order')
                                    ->label('Nomor Urut')
                                    ->numeric()
                                    ->default(0),

                                Toggle::make('show_in_nav')
                                    ->label('Tautkan di Menu Navbar')
                                    ->helperText('Tampilkan otomatis di header utama')
                                    ->default(false),

                                Toggle::make('show_in_footer')
                                    ->label('Tautkan di Menu Footer')
                                    ->helperText('Tampilkan otomatis di bagian footer')
                                    ->default(true),
                            ]),
                    ]),
                ]),
            ]);
    }
}
