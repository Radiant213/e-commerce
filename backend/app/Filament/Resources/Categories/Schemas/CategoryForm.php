<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kategori')
                    ->description('Detail nama kategori, ikon, dan gambar banner (upload s/d 40 MB atau link URL)')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Nama Kategori')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Slug URL')
                                    ->helperText('Otomatis di-generate dari nama'),
                            ]),

                        Textarea::make('description')
                            ->rows(3)
                            ->label('Deskripsi Kategori'),

                        Grid::make(3)
                            ->schema([
                                Select::make('image_source')
                                    ->label('Metode Banner')
                                    ->options([
                                        'upload' => 'Upload File Gambar',
                                        'url' => 'URL Gambar Online',
                                    ])
                                    ->default('upload')
                                    ->selectablePlaceholder(false)
                                    ->dehydrated(false)
                                    ->live()
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                         if ($record && $record->image) {
                                             $raw = $record->getRawOriginal('image') ?? $record->image;
                                             if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')) {
                                                 $component->state('url');
                                             } else {
                                                 $component->state('upload');
                                             }
                                         }
                                     })
                                     ->columnSpan(1),

                                TextInput::make('icon')
                                    ->label('Nama Ikon Lucide')
                                    ->placeholder('Contoh: Smartphone, Shirt, Watch')
                                    ->columnSpan(2),
                            ]),

                        FileUpload::make('image_upload')
                            ->label('Pilih File Gambar Banner')
                            ->helperText('Format: JPG, PNG, WEBP, GIF, SVG. Maksimal: 40 MB. Kosongkan untuk menghapus banner.')
                            ->image()
                            ->maxSize(40960) // 40 MB
                            ->disk('public')
                            ->directory('categories')
                            ->visibility('public')
                            ->imageEditor()
                            ->openable()
                            ->downloadable()
                            ->previewable(true)
                            ->dehydrated(false)
                            ->visible(fn ($get) => $get('image_source') !== 'url')
                            ->afterStateHydrated(function ($component, $state, $record) {
                                if ($record && $record->image) {
                                    $raw = $record->getRawOriginal('image') ?? $record->image;
                                    if (!str_starts_with($raw, 'http://') && !str_starts_with($raw, 'https://')) {
                                        $clean = preg_replace('#^/?storage/#', '', $raw);
                                        $component->state($clean);
                                    }
                                }
                            })
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $path = is_array($state) ? reset($state) : $state;
                                    $set('image', $path);
                                } else {
                                    $set('image', null);
                                }
                            }),

                        TextInput::make('image_url')
                            ->label('URL Gambar Banner Online')
                            ->placeholder('https://images.unsplash.com/...')
                            ->helperText('Masukkan tautan URL gambar online berawalan https://. Kosongkan untuk menghapus banner.')
                            ->dehydrated(false)
                            ->visible(fn ($get) => $get('image_source') === 'url')
                            ->afterStateHydrated(function ($component, $state, $record) {
                                if ($record && $record->image) {
                                    $raw = $record->getRawOriginal('image') ?? $record->image;
                                    if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')) {
                                        $component->state($raw);
                                    }
                                }
                            })
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('image', $state ?: null);
                            }),

                        Hidden::make('image')
                            ->default(null)
                            ->nullable()
                            ->dehydrateStateUsing(function ($state, $get) {
                                if ($get('image_source') === 'url') {
                                    return $get('image_url') ?: null;
                                }
                                $uploaded = $get('image_upload');
                                if (is_array($uploaded)) {
                                    $uploaded = reset($uploaded);
                                }
                                return $uploaded ?: null;
                            }),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->label('Urutan Tampilan')
                                    ->helperText('Urutan ke-1, 2, 3, dst.'),

                                Toggle::make('is_active')
                                    ->default(true)
                                    ->label('Status Aktif')
                                    ->helperText('Kategori muncul di navigasi pembeli'),
                            ]),
                    ]),
            ]);
    }
}
