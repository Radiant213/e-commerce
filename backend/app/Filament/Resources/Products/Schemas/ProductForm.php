<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\KeyValue;
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

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                // Top 2-Column Grid (Information 2/3 and Pricing/Stock 1/3)
                Grid::make(3)
                    ->schema([
                        // Left 2 Columns: Main Info
                        Section::make('Informasi Produk')
                            ->description('Informasi utama dan deskripsi barang dagangan')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Nama Produk')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('slug')
                                            ->label('URL Slug')
                                            ->helperText('Otomatis dari nama atau sesuaikan')
                                            ->maxLength(255),

                                        TextInput::make('sku')
                                            ->label('Kode SKU / Barcode')
                                            ->placeholder('Contoh: PRD-ELK-001')
                                            ->helperText('Otomatis diisi sistem jika kosong'),
                                    ]),

                                Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->label('Kategori Produk'),

                                Textarea::make('short_description')
                                    ->maxLength(500)
                                    ->rows(2)
                                    ->label('Deskripsi Singkat (Ringkasan)'),

                                RichEditor::make('description')
                                    ->label('Deskripsi Lengkap Produk (Mendukung Teks & Gambar)')
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('products/descriptions')
                                    ->fileAttachmentsVisibility('public')
                                    ->helperText('Anda dapat menyisipkan foto/banner di antara paragraf teks dengan tombol upload gambar di editor.')
                                    ->columnSpanFull(),

                                KeyValue::make('specifications')
                                    ->label('Spesifikasi Teknis Produk (Tabel Atribut Kustom)')
                                    ->keyLabel('Parameter / Atribut')
                                    ->valueLabel('Nilai / Keterangan')
                                    ->keyPlaceholder('Nama Parameter (misal: Bahan, Ukuran, Garansi)')
                                    ->valuePlaceholder('Keterangan / Nilai')
                                    ->helperText('Spesifikasi yang Anda buat akan tampil murni dalam bentuk tabel 2-kolom pada halaman detail produk.')
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(2),

                        // Right 1 Column: Price & Stock & Visibility
                        Grid::make(1)
                            ->schema([
                                Section::make('Harga & Inventaris')
                                    ->schema([
                                        TextInput::make('price')
                                            ->required()
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->label('Harga Normal'),

                                        TextInput::make('sale_price')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->label('Harga Coret / Promo')
                                            ->helperText('Kosongkan jika tidak ada promo'),

                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('stock')
                                                    ->required()
                                                    ->numeric()
                                                    ->default(0)
                                                    ->label('Jumlah Stok'),

                                                TextInput::make('weight')
                                                    ->numeric()
                                                    ->suffix('g')
                                                    ->default(100)
                                                    ->label('Berat'),
                                            ]),
                                    ]),

                                Section::make('Status Visibilitas')
                                    ->schema([
                                        Toggle::make('is_active')
                                            ->default(true)
                                            ->onColor('success')
                                            ->label('Aktifkan di Toko'),

                                        Toggle::make('is_featured')
                                            ->default(false)
                                            ->onColor('success')
                                            ->label('Produk Unggulan'),
                                    ]),
                            ])
                            ->columnSpan(1),
                    ]),

                // Full-Width Section 1: Main Product Showcase Video
                Section::make('Video Showcase Produk (Utama)')
                    ->description('Sertakan 1 video showcase utama yang akan disorot pertama kali oleh pembeli saat membuka produk (Mendukung MP4, MOV, WEBM hingga 100 MB).')
                    ->collapsible()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('video_source_type')
                                    ->label('Metode Video Showcase')
                                    ->options([
                                        'upload' => 'Upload File Video',
                                        'url' => 'URL Video Online',
                                    ])
                                    ->default('upload')
                                    ->selectablePlaceholder(false)
                                    ->dehydrateStateUsing(fn ($state) => $state ?: 'upload')
                                    ->live()
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                        if ($record && $record->video_path) {
                                            $raw = $record->video_path;
                                            if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')) {
                                                $component->state('url');
                                            } else {
                                                $component->state('upload');
                                            }
                                        } else {
                                            $component->state($record->video_source_type ?? 'upload');
                                        }
                                    })
                                    ->columnSpan(1),

                                FileUpload::make('video_upload')
                                    ->label('Pilih File Video Showcase')
                                    ->helperText('Format didukung: MP4, MOV, WEBM. Ukuran maksimal: 100 MB. Kosongkan untuk menghapus video.')
                                    ->acceptedFileTypes(['video/mp4', 'video/quicktime', 'video/webm', 'video/ogg'])
                                    ->maxSize(102400) // 100 MB
                                    ->disk('public')
                                    ->directory('products/videos')
                                    ->visibility('public')
                                    ->openable()
                                    ->downloadable()
                                    ->dehydrated(false)
                                    ->columnSpan(2)
                                    ->visible(fn ($get) => $get('video_source_type') !== 'url')
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                        if ($record && $record->video_path) {
                                            $raw = $record->video_path;
                                            if (!str_starts_with($raw, 'http://') && !str_starts_with($raw, 'https://')) {
                                                $clean = preg_replace('#^/?storage/#', '', $raw);
                                                $component->state($clean);
                                            }
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $path = is_array($state) ? reset($state) : $state;
                                            $set('video_path', $path);
                                        } else {
                                            $set('video_path', null);
                                        }
                                    }),

                                TextInput::make('video_url_input')
                                    ->label('URL Video Showcase (YouTube / Link Video Langsung)')
                                    ->placeholder('https://www.youtube.com/watch?v=... atau link .mp4')
                                    ->helperText('Mendukung link YouTube (watch/shorts/youtu.be) atau file video online (mp4/webm).')
                                    ->dehydrated(false)
                                    ->live(onBlur: true)
                                    ->columnSpan(2)
                                    ->visible(fn ($get) => $get('video_source_type') === 'url')
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                        if ($record && $record->video_path) {
                                            $raw = $record->video_path;
                                            if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')) {
                                                $component->state($raw);
                                            }
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $clean = filled($state) ? \App\Services\MediaUrlService::processVideoUrl($state) : null;
                                        $set('video_path', $clean);
                                    }),

                                Hidden::make('video_path')
                                    ->default(null)
                                    ->nullable()
                                    ->dehydrateStateUsing(function ($state, $get) {
                                        if ($get('video_source_type') === 'url') {
                                            $url = $get('video_url_input');
                                            return filled($url) ? \App\Services\MediaUrlService::processVideoUrl($url) : null;
                                        }
                                        $uploaded = $get('video_upload');
                                        if (is_array($uploaded)) {
                                            $uploaded = reset($uploaded);
                                        }
                                        return $uploaded ?: null;
                                    }),
                            ]),
                    ])
                    ->columnSpanFull(),

                // Full-Width Section 2: Product Gallery & Additional Media (Photos & Videos)
                Section::make('Galeri Media Produk (Foto & Video Tambahan)')
                    ->description('Kelola foto dan video tambahan yang akan ditampilkan pada galeri. Anda dapat menyisipkan video pada urutan tampilan (slot) mana pun.')
                    ->schema([
                        Repeater::make('images')
                            ->relationship('images')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        Select::make('source_type')
                                            ->label('Tipe Media')
                                            ->options([
                                                'upload' => 'Upload File Foto (JPG/PNG/WEBP)',
                                                'url' => 'URL Foto Online',
                                                'video_upload' => 'Upload File Video (MP4/MOV/WEBM)',
                                                'video_url' => 'URL Video Online',
                                            ])
                                            ->default('upload')
                                            ->selectablePlaceholder(false)
                                            ->dehydrated(false)
                                            ->live()
                                            ->afterStateHydrated(function ($component, $state, $record) {
                                                if ($record && $record->image_path) {
                                                    $raw = $record->getRawOriginal('image_path') ?? $record->image_path;
                                                    $isUrl = str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://');
                                                    $ext = strtolower(pathinfo(parse_url($raw, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
                                                    $isVideo = ($record->media_type === 'video') || in_array($ext, ['mp4', 'mov', 'webm', 'ogg']);

                                                    if ($isVideo) {
                                                        $component->state($isUrl ? 'video_url' : 'video_upload');
                                                    } else {
                                                        $component->state($isUrl ? 'url' : 'upload');
                                                    }
                                                }
                                            })
                                            ->columnSpan(1),

                                        Toggle::make('is_primary')
                                            ->label('Gambar Utama (Thumbnail)')
                                            ->helperText('Foto cover pada etalase toko')
                                            ->default(false)
                                            ->onColor('success')
                                            ->visible(fn ($get) => in_array($get('source_type'), ['upload', 'url']))
                                            ->columnSpan(1),

                                        TextInput::make('sort_order')
                                            ->label('Urutan Tampilan')
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(1)
                                            ->helperText('Urutan ke-1, 2, 3, dst.')
                                            ->columnSpan(1),
                                    ]),

                                // Image Upload
                                FileUpload::make('image_upload')
                                    ->label('Pilih File Foto Produk')
                                    ->helperText('Format didukung: JPG, PNG, WEBP, GIF, SVG. Ukuran maksimal: 40 MB. Kosongkan untuk menghapus.')
                                    ->image()
                                    ->maxSize(40960) // 40 MB
                                    ->disk('public')
                                    ->directory('products')
                                    ->visibility('public')
                                    ->imageEditor()
                                    ->openable()
                                    ->downloadable()
                                    ->previewable(true)
                                    ->dehydrated(false)
                                    ->visible(fn ($get) => $get('source_type') === 'upload')
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                        if ($record && $record->image_path && $record->media_type !== 'video') {
                                            $raw = $record->getRawOriginal('image_path') ?? $record->image_path;
                                            if (!str_starts_with($raw, 'http://') && !str_starts_with($raw, 'https://')) {
                                                $clean = preg_replace('#^/?storage/#', '', $raw);
                                                $component->state($clean);
                                            }
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $path = is_array($state) ? reset($state) : $state;
                                            $set('image_path', $path);
                                        } else {
                                            $set('image_path', null);
                                        }
                                    }),

                                // Image URL
                                TextInput::make('image_url')
                                    ->label('URL Foto Online (Pinterest / Link Gambar Web)')
                                    ->placeholder('https://id.pinterest.com/pin/... atau link foto publik')
                                    ->helperText('Mendukung link Pinterest (pin page / pin.it) & gambar web. Otomatis diunduh ke server.')
                                    ->dehydrated(false)
                                    ->live(onBlur: true)
                                    ->visible(fn ($get) => $get('source_type') === 'url')
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                        if ($record && $record->image_path && $record->media_type !== 'video') {
                                            $raw = $record->getRawOriginal('image_path') ?? $record->image_path;
                                            if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')) {
                                                $component->state($raw);
                                            }
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $resolved = filled($state) ? \App\Services\MediaUrlService::processImageUrl($state) : null;
                                        $set('image_path', $resolved);
                                    }),

                                // Video Upload
                                FileUpload::make('video_item_upload')
                                    ->label('Pilih File Video Produk')
                                    ->helperText('Format didukung: MP4, MOV, WEBM. Ukuran maksimal: 100 MB. Kosongkan untuk menghapus.')
                                    ->acceptedFileTypes(['video/mp4', 'video/quicktime', 'video/webm', 'video/ogg'])
                                    ->maxSize(102400) // 100 MB
                                    ->disk('public')
                                    ->directory('products/videos')
                                    ->visibility('public')
                                    ->openable()
                                    ->downloadable()
                                    ->dehydrated(false)
                                    ->visible(fn ($get) => $get('source_type') === 'video_upload')
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                        if ($record && $record->image_path) {
                                            $raw = $record->getRawOriginal('image_path') ?? $record->image_path;
                                            if (!str_starts_with($raw, 'http://') && !str_starts_with($raw, 'https://')) {
                                                $clean = preg_replace('#^/?storage/#', '', $raw);
                                                $component->state($clean);
                                            }
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $path = is_array($state) ? reset($state) : $state;
                                            $set('image_path', $path);
                                        } else {
                                            $set('image_path', null);
                                        }
                                    }),

                                // Video URL
                                TextInput::make('video_item_url')
                                    ->label('URL Video Online (YouTube / Link Video Langsung)')
                                    ->placeholder('https://www.youtube.com/watch?v=... atau https://.../video.mp4')
                                    ->helperText('Mendukung link YouTube atau file video langsung.')
                                    ->dehydrated(false)
                                    ->live(onBlur: true)
                                    ->visible(fn ($get) => $get('source_type') === 'video_url')
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                        if ($record && $record->image_path) {
                                            $raw = $record->getRawOriginal('image_path') ?? $record->image_path;
                                            if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')) {
                                                $component->state($raw);
                                            }
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $clean = filled($state) ? \App\Services\MediaUrlService::processVideoUrl($state) : null;
                                        $set('image_path', $clean);
                                    }),

                                Hidden::make('media_type')
                                    ->default('image')
                                    ->dehydrateStateUsing(function ($state, $get) {
                                        $src = $get('source_type');
                                        if (in_array($src, ['video_upload', 'video_url'])) {
                                            return 'video';
                                        }
                                        $vUrl = $get('video_item_url') ?: $get('image_url');
                                        if (\App\Services\MediaUrlService::isYouTube($vUrl)) {
                                            return 'video';
                                        }
                                        return 'image';
                                    }),

                                Hidden::make('image_path')
                                    ->default(null)
                                    ->nullable()
                                    ->dehydrateStateUsing(function ($state, $get) {
                                        $src = $get('source_type');
                                        if ($src === 'url') {
                                            $u = $get('image_url');
                                            return filled($u) ? \App\Services\MediaUrlService::processImageUrl($u) : null;
                                        }
                                        if ($src === 'video_url') {
                                            $u = $get('video_item_url');
                                            return filled($u) ? \App\Services\MediaUrlService::processVideoUrl($u) : null;
                                        }
                                        if ($src === 'video_upload') {
                                            $v = $get('video_item_upload');
                                            if (is_array($v)) $v = reset($v);
                                            return $v ?: null;
                                        }
                                        $uploaded = $get('image_upload');
                                        if (is_array($uploaded)) {
                                            $uploaded = reset($uploaded);
                                        }
                                        return $uploaded ?: null;
                                    }),
                            ])
                            ->defaultItems(1)
                            ->reorderable(true)
                            ->orderColumn('sort_order')
                            ->collapsible()
                            ->itemLabel(function (array $state): ?string {
                                $src = $state['source_type'] ?? 'upload';
                                $isVideo = in_array($src, ['video_upload', 'video_url']);
                                $prefix = $isVideo ? '🎬 Video' : '🖼️ Foto';
                                $path = $state['image_upload'] ?? $state['image_url'] ?? $state['video_item_upload'] ?? $state['video_item_url'] ?? $state['image_path'] ?? null;
                                if (is_array($path)) $path = reset($path);
                                if ($path) {
                                    $short = strlen($path) > 35 ? substr($path, 0, 32) . '...' : $path;
                                    return "{$prefix} ({$short})";
                                }
                                return "{$prefix} Produk";
                            }),
                    ])
                    ->columnSpanFull(),

                // Full-Width Section 3: Product Variants
                Section::make('Variasi Produk (Pilihan Warna / Ukuran / Model)')
                    ->description('Kelola variasi produk seperti pilihan warna, kapasitas, atau model lengkap dengan thumbnail foto dan stok masing-masing (opsional, jika produk memiliki variasi).')
                    ->schema([
                        Repeater::make('variants')
                            ->relationship('variants')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Nama Variasi')
                                            ->placeholder('Contoh: R60iNC Blue atau Hitam - Size XL')
                                            ->required()
                                            ->columnSpan(1),

                                        TextInput::make('stock')
                                            ->label('Stok Variasi')
                                            ->numeric()
                                            ->default(10)
                                            ->required()
                                            ->columnSpan(1),

                                        TextInput::make('sku')
                                            ->label('SKU Variasi (Opsional)')
                                            ->placeholder('Contoh: ANK-R60-BLU')
                                            ->columnSpan(1),
                                    ]),

                                Grid::make(3)
                                    ->schema([
                                        TextInput::make('price')
                                            ->label('Harga Khusus Variasi (Opsional)')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->helperText('Kosongkan jika sama dengan harga normal produk')
                                            ->columnSpan(1),

                                        FileUpload::make('image_path')
                                            ->label('Foto Thumbnail Mini Variasi')
                                            ->image()
                                            ->disk('public')
                                            ->directory('products/variants')
                                            ->visibility('public')
                                            ->columnSpan(1),

                                        Toggle::make('is_active')
                                            ->label('Variasi Aktif')
                                            ->default(true)
                                            ->onColor('success')
                                            ->inline(false)
                                            ->columnSpan(1),
                                    ]),
                            ])
                            ->defaultItems(0)
                            ->reorderable(true)
                            ->orderColumn('sort_order')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => 
                                isset($state['name']) && filled($state['name'])
                                    ? "✨ Variasi: {$state['name']} (Stok: " . ($state['stock'] ?? 0) . ")"
                                    : "Pilihan Variasi"
                            ),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
