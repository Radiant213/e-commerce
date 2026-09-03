<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                // 1. Full-width Order Items Section
                Section::make('Daftar Produk yang Dipesan')
                    ->description('Rincian barang belanjaan customer pada pesanan ini')
                    ->schema([
                        ViewField::make('items_table')
                            ->view('filament.order-items-table'),
                    ]),

                // 2. Two-column bottom section
                Grid::make(2)
                    ->schema([
                        // Left: Status & Midtrans payment info
                        Section::make('Status & Pembayaran')
                            ->description('Status pemrosesan dan data gateway Midtrans')
                            ->schema([
                                Select::make('status')
                                    ->options([
                                        'pending' => 'Pending (Menunggu Pembayaran)',
                                        'paid' => 'Paid (Sudah Dibayar)',
                                        'processing' => 'Processing (Sedang Dikemas)',
                                        'shipped' => 'Shipped (Sedang Dikirim)',
                                        'delivered' => 'Delivered (Pesanan Selesai)',
                                        'cancelled' => 'Cancelled (Dibatalkan)',
                                    ])
                                    ->required()
                                    ->label('Status Pesanan'),

                                Placeholder::make('payment_details')
                                    ->label('Info Gateway Midtrans')
                                    ->content(function ($record) {
                                        if (!$record || !$record->payment) {
                                            return new HtmlString('<div style="padding: 12px; background: #fffbeb; color: #92400e; border: 1px solid #fef3c7; border-radius: 8px; font-size: 0.875rem;">⚠️ Belum ada data transaksi Midtrans tercatat.</div>');
                                        }
                                        $p = $record->payment;
                                        $isPaid = in_array(strtolower($p->status ?? ''), ['settlement', 'capture', 'paid']);
                                        $statusBg = $isPaid ? '#ecfdf5' : '#fffbeb';
                                        $statusColor = $isPaid ? '#065f46' : '#92400e';
                                        $statusBorder = $isPaid ? '#a7f3d0' : '#fde68a';
                                        $statusLabel = strtoupper($p->status ?? 'PENDING');
                                        $type = strtoupper($p->payment_type ?? 'ONLINE');
                                        $trx = $p->midtrans_transaction_id ?? '-';
                                        $gross = number_format($p->gross_amount ?? $record->total, 0, ',', '.');

                                        return new HtmlString("
                                            <div style='display: flex; flex-direction: column; gap: 10px; font-size: 0.875rem;'>
                                                <div style='display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; background: {$statusBg}; color: {$statusColor}; border: 1px solid {$statusBorder}; border-radius: 8px;'>
                                                    <span style='font-weight: 600;'>Status Settlement</span>
                                                    <span style='padding: 2px 10px; background: #ffffff; border: 1px solid {$statusBorder}; border-radius: 9999px; font-weight: 700; font-size: 0.75rem; text-transform: uppercase;'>{$statusLabel}</span>
                                                </div>
                                                <div style='display: grid; grid-template-columns: 1fr 1fr; gap: 10px; background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.8rem;'>
                                                    <div><span style='color: #94a3b8; display: block;'>Metode Bayar:</span> <strong style='color: #0f172a; font-size: 0.875rem;'>{$type}</strong></div>
                                                    <div><span style='color: #94a3b8; display: block;'>Nominal Bayar:</span> <strong style='color: #0f172a; font-size: 0.875rem;'>Rp {$gross}</strong></div>
                                                    <div style='grid-column: span 2; border-top: 1px solid #e2e8f0; padding-top: 8px;'><span style='color: #94a3b8; display: block;'>ID Transaksi Midtrans:</span> <code style='font-family: monospace; background: #ffffff; padding: 3px 8px; border-radius: 4px; border: 1px solid #e2e8f0; color: #334155; font-size: 0.75rem; display: inline-block; margin-top: 2px;'>{$trx}</code></div>
                                                </div>
                                            </div>
                                        ");
                                    }),
                            ]),

                        // Right: Shipping and delivery info
                        Section::make('Tujuan & Penerima Pengiriman')
                            ->description('Data customer dan alamat kurir')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('shipping_name')
                                            ->label('Nama Penerima')
                                            ->required(),

                                        TextInput::make('shipping_phone')
                                            ->label('No. Telepon / WhatsApp')
                                            ->required(),
                                    ]),

                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('shipping_city')
                                            ->label('Kota / Kabupaten'),

                                        TextInput::make('shipping_postal_code')
                                            ->label('Kode Pos'),
                                    ]),

                                Textarea::make('shipping_address')
                                    ->label('Alamat Lengkap')
                                    ->rows(2)
                                    ->required(),

                                TextInput::make('notes')
                                    ->label('Catatan Pesanan')
                                    ->placeholder('Tidak ada catatan khusus dari pembeli'),
                            ]),
                    ]),
            ]);
    }
}
