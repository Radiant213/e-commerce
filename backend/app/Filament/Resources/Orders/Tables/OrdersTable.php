<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['user', 'payment', 'items']))
            ->columns([
                TextColumn::make('order_number')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->copyMessage('Nomor pesanan disalin!')
                    ->description(fn (Order $record): string => $record->items->count() . ' produk dipesan')
                    ->label('No. Pesanan'),

                TextColumn::make('shipping_name')
                    ->searchable()
                    ->description(fn (Order $record): string => $record->shipping_phone ?? '-')
                    ->label('Penerima & Kontak'),

                TextColumn::make('total')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold')
                    ->label('Total Tagihan'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'processing' => 'info',
                        'shipped' => 'primary',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->label('Status Pesanan'),

                TextColumn::make('payment.payment_type')
                    ->badge()
                    ->color('gray')
                    ->placeholder('Belum bayar')
                    ->label('Metode Bayar'),

                TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->label('Waktu Masuk'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->recordActions([
                Action::make('print_invoice')
                    ->label('Invoice')
                    ->icon('heroicon-m-printer')
                    ->color('info')
                    ->url(fn (Order $record): string => route('admin.orders.invoice', $record))
                    ->openUrlInNewTab(),

                Action::make('mark_shipped')
                    ->label('Kirim')
                    ->icon('heroicon-m-truck')
                    ->color('primary')
                    ->visible(fn (Order $record): bool => in_array($record->status, ['paid', 'processing']))
                    ->requiresConfirmation()
                    ->modalIcon('heroicon-o-truck')
                    ->modalIconColor('primary')
                    ->modalHeading('Kirim Pesanan Ini?')
                    ->modalDescription('Status pesanan akan diubah menjadi Shipped (Sedang Dikirim).')
                    ->modalSubmitActionLabel('Konfirmasi')
                    ->modalCancelActionLabel('Batal')
                    ->action(function (Order $record) {
                        $record->update(['status' => 'shipped']);
                        Notification::make()
                            ->title('Pesanan Telah Dikirim')
                            ->body("Status pesanan #{$record->order_number} berhasil diubah ke Shipped.")
                            ->success()
                            ->send();
                    }),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
