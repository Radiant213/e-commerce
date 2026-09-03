<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(3)
                    ->schema([
                        Section::make('Ulasan & Penilaian Pelanggan')
                            ->description('Moderasi komentar dan ulasan dari pembeli')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Select::make('product_id')
                                            ->relationship('product', 'name')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->label('Produk yang Diulas'),

                                        Select::make('user_id')
                                            ->relationship('user', 'name')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->label('Nama Pengulas (Customer)'),
                                    ]),

                                Select::make('rating')
                                    ->options([
                                        5 => '⭐⭐⭐⭐⭐ (5/5) Sangat Puas',
                                        4 => '⭐⭐⭐⭐ (4/5) Puas',
                                        3 => '⭐⭐⭐ (3/5) Cukup',
                                        2 => '⭐⭐ (2/5) Kurang Puas',
                                        1 => '⭐ (1/5) Kecewa',
                                    ])
                                    ->required()
                                    ->default(5)
                                    ->label('Rating Bintang'),

                                Textarea::make('comment')
                                    ->rows(4)
                                    ->required()
                                    ->label('Komentar Ulasan'),
                            ])
                            ->columnSpan(2),
                    ]),
            ]);
    }
}
