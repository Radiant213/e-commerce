<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('order_number')
                    ->disabled()
                    ->label('No. Pesanan'),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->label('Status Pesanan'),
                \Filament\Forms\Components\TextInput::make('shipping_name')
                    ->required()
                    ->label('Nama Penerima'),
                \Filament\Forms\Components\TextInput::make('shipping_phone')
                    ->required()
                    ->label('No. Telepon'),
                \Filament\Forms\Components\Textarea::make('shipping_address')
                    ->required()
                    ->rows(3)
                    ->label('Alamat Pengiriman'),
                \Filament\Forms\Components\TextInput::make('shipping_city')
                    ->label('Kota'),
                \Filament\Forms\Components\TextInput::make('shipping_postal_code')
                    ->label('Kode Pos'),
                \Filament\Forms\Components\Textarea::make('notes')
                    ->rows(2)
                    ->label('Catatan'),
            ]);
    }
}
