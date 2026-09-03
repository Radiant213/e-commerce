<?php

namespace App\Filament\Resources\Users\Schemas;

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
                                    ->label('Peran Akun'),

                                TextInput::make('avatar')
                                    ->label('URL Foto Profil (Avatar)')
                                    ->placeholder('https://images.unsplash.com/... atau storage/...')
                                    ->helperText('Bisa berupa URL gambar online atau path'),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }
}
