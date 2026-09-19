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
                                    ->native(false)
                                    ->label('Status Pesanan'),

                                Placeholder::make('payment_details')
                                    ->label('Info Gateway Midtrans')
                                    ->content(function ($record) {
                                        if (!$record || !$record->payment) {
                                            return new HtmlString('<div style="padding: 12px; background: rgba(245, 158, 11, 0.1); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 10px; font-size: 0.875rem;">⚠️ Belum ada data transaksi Midtrans tercatat.</div>');
                                        }
                                        $p = $record->payment;
                                        $isPaid = in_array(strtolower($p->status ?? ''), ['settlement', 'capture', 'paid']);
                                        $statusBg = $isPaid ? 'rgba(16, 185, 129, 0.12)' : 'rgba(245, 158, 11, 0.12)';
                                        $statusColor = $isPaid ? '#10B981' : '#F59E0B';
                                        $statusBorder = $isPaid ? 'rgba(16, 185, 129, 0.3)' : 'rgba(245, 158, 11, 0.3)';
                                        $statusLabel = strtoupper($p->status ?? 'PENDING');
                                        $type = strtoupper($p->payment_type ?? 'ONLINE');
                                        $trx = $p->midtrans_transaction_id ?? '-';
                                        $gross = number_format($p->gross_amount ?? $record->total, 0, ',', '.');

                                        return new HtmlString("
                                            <div style='display: flex; flex-direction: column; gap: 10px; font-size: 0.875rem;'>
                                                <div style='display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; background: {$statusBg}; color: {$statusColor}; border: 1px solid {$statusBorder}; border-radius: 10px;'>
                                                    <span style='font-weight: 600;'>Status Settlement</span>
                                                    <span style='padding: 3px 12px; background: var(--rc-surface, #ffffff); color: {$statusColor}; border: 1px solid {$statusBorder}; border-radius: 9999px; font-weight: 700; font-size: 0.75rem; text-transform: uppercase;'>{$statusLabel}</span>
                                                </div>
                                                <div style='display: grid; grid-template-columns: 1fr 1fr; gap: 10px; background: var(--rc-surface-2, #f8fafc); padding: 14px; border-radius: 10px; border: 1px solid var(--rc-border, #e2e8f0); font-size: 0.8rem;'>
                                                    <div><span style='color: var(--rc-text-muted, #94a3b8); display: block;'>Metode Bayar:</span> <strong style='color: var(--rc-text, #0f172a); font-size: 0.875rem;'>{$type}</strong></div>
                                                    <div><span style='color: var(--rc-text-muted, #94a3b8); display: block;'>Nominal Bayar:</span> <strong style='color: var(--rc-emerald, #10b981); font-size: 0.875rem;'>Rp {$gross}</strong></div>
                                                    <div style='grid-column: span 2; border-top: 1px solid var(--rc-border, #e2e8f0); padding-top: 10px;'><span style='color: var(--rc-text-muted, #94a3b8); display: block;'>ID Transaksi Midtrans:</span> <code style='font-family: monospace; background: var(--rc-surface, #ffffff); padding: 4px 10px; border-radius: 6px; border: 1px solid var(--rc-border, #e2e8f0); color: var(--rc-text, #334155); font-size: 0.75rem; display: inline-block; margin-top: 4px;'>{$trx}</code></div>
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
