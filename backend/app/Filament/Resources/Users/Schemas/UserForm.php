<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(3)
                    ->schema([
                        // Left 2 columns: Identity & Contact
                        Section::make('Informasi Pengguna')
                            ->description('Data profil dan informasi akun pengguna')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(255)
                                            ->label('Nama Lengkap')
                                            ->prefixIcon('heroicon-m-user'),

                                        TextInput::make('email')
                                            ->required()
                                            ->email()
                                            ->maxLength(255)
                                            ->label('Alamat Email')
                                            ->prefixIcon('heroicon-m-envelope'),
                                    ]),

                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('phone')
                                            ->tel()
                                            ->maxLength(20)
                                            ->label('Nomor WhatsApp / HP')
                                            ->prefixIcon('heroicon-m-phone'),

                                        TextInput::make('password')
                                            ->password()
                                            ->dehydrated(fn ($state) => filled($state))
                                            ->required(fn (string $operation): bool => $operation === 'create')
                                            ->label('Kata Sandi (Password)')
                                            ->helperText('Biarkan kosong jika tidak ingin mengubah password'),
                                    ]),

                                Textarea::make('address')
                                    ->rows(3)
                                    ->label('Alamat Utama Pengiriman')
                                    ->placeholder('Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan'),
                            ])
                            ->columnSpan(2),

                        // Right 1 column: Role & Avatar
                        Section::make('Hak Akses & Foto')
                            ->schema([
                                Select::make('role')
                                    ->options([
                                        'admin' => '👑 Administrator (Akses Penuh)',
                                        'customer' => '🛍️ Pelanggan (Customer)',
                                    ])
                                    ->required()
                                    ->default('customer')
                                    ->native(false)
                                    ->label('Peran Akun'),

                                Select::make('avatar_source')
                                    ->label('Metode Foto Profil')
                                    ->options([
                                        'upload' => 'Upload File Foto',
                                        'url' => 'URL Foto Online',
                                    ])
                                    ->default('upload')
                                    ->selectablePlaceholder(false)
                                    ->dehydrated(false)
                                    ->live()
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                         if ($record && $record->avatar) {
                                             $raw = $record->getRawOriginal('avatar') ?? $record->avatar;
                                             if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')) {
                                                 $component->state('url');
                                             } else {
                                                 $component->state('upload');
                                             }
                                         }
                                     }),

                                FileUpload::make('avatar_upload')
                                    ->label('Pilih Foto Profil')
                                    ->helperText('Format: JPG, PNG, WEBP. Maksimal: 40 MB. Kosongkan untuk menghapus foto profil.')
                                    ->image()
                                    ->maxSize(40960) // 40 MB
                                    ->disk('public')
                                    ->directory('avatars')
                                    ->visibility('public')
                                    ->avatar()
                                    ->openable()
                                    ->downloadable()
                                    ->previewable(true)
                                    ->dehydrated(false)
                                    ->visible(fn ($get) => $get('avatar_source') !== 'url')
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                         if ($record && $record->avatar) {
                                             $raw = $record->getRawOriginal('avatar') ?? $record->avatar;
                                             if (!str_starts_with($raw, 'http://') && !str_starts_with($raw, 'https://')) {
                                                 $clean = preg_replace('#^/?storage/#', '', $raw);
                                                 $component->state($clean);
                                             }
                                         }
                                     })
                                     ->afterStateUpdated(function ($state, callable $set) {
                                         if ($state) {
                                             $path = is_array($state) ? reset($state) : $state;
                                             $set('avatar', $path);
                                         } else {
                                             $set('avatar', null);
                                         }
                                     }),

                                TextInput::make('avatar_url')
                                    ->label('URL Foto Profil Online')
                                    ->placeholder('https://images.unsplash.com/...')
                                    ->helperText('Masukkan URL foto profil online berawalan https://. Kosongkan untuk menghapus foto profil.')
                                    ->dehydrated(false)
                                    ->visible(fn ($get) => $get('avatar_source') === 'url')
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                        if ($record && $record->avatar) {
                                            $raw = $record->getRawOriginal('avatar') ?? $record->avatar;
                                            if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')) {
                                                $component->state($raw);
                                            }
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $set('avatar', $state ?: null);
                                    }),

                                Hidden::make('avatar')
                                    ->default(null)
                                    ->nullable()
                                    ->dehydrateStateUsing(function ($state, $get) {
                                        if ($get('avatar_source') === 'url') {
                                            return $get('avatar_url') ?: null;
                                        }
                                        $uploaded = $get('avatar_upload');
                                        if (is_array($uploaded)) {
                                            $uploaded = reset($uploaded);
                                        }
                                        return $uploaded ?: null;
                                    }),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }
}
