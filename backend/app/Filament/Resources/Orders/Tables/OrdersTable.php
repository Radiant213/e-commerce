<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
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
            ->actionsColumnLabel('Aksi')
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

                TextColumn::make('courier_name')
                    ->label('Kurir & Resi')
                    ->placeholder('Belum dikirim')
                    ->description(fn (Order $record): string => $record->tracking_number ? 'No. Resi: ' . $record->tracking_number : '-')
                    ->icon(fn (Order $record): ?string => $record->receipt_image ? 'heroicon-m-photo' : null)
                    ->iconColor('success')
                    ->tooltip(fn (Order $record): ?string => $record->receipt_image ? 'Foto bukti resi tersedia' : null)
                    ->searchable(['courier_name', 'tracking_number']),

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
                ActionGroup::make([
                    Action::make('print_invoice')
                        ->label('Cetak Invoice')
                        ->icon('heroicon-m-printer')
                        ->color('info')
                        ->url(fn (Order $record): string => route('admin.orders.invoice', $record))
                        ->openUrlInNewTab(),

                    Action::make('mark_shipped')
                        ->label('Kirim Pesanan')
                        ->icon('heroicon-m-truck')
                        ->color('primary')
                        ->visible(fn (Order $record): bool => in_array($record->status, ['paid', 'processing']))
                        ->modalIcon('heroicon-o-truck')
                        ->modalIconColor('primary')
                        ->modalHeading('Kirim Pesanan Ini?')
                        ->modalDescription('Masukkan nama kurir, nomor resi, dan upload foto bukti resi (opsional).')
                        ->modalSubmitActionLabel('Kirim Pesanan')
                        ->modalCancelActionLabel('Batal')
                        ->form([
                            \Filament\Forms\Components\TextInput::make('courier_name')
                                ->label('Nama Kurir')
                                ->placeholder('JNE / J&T / GoSend')
                                ->required(),
                            \Filament\Forms\Components\TextInput::make('tracking_number')
                                ->label('Nomor Resi')
                                ->placeholder('Masukkan nomor resi pengiriman')
                                ->required(),
                            \Filament\Forms\Components\FileUpload::make('receipt_image')
                                ->label('Foto Bukti Resi (Opsional)')
                                ->image()
                                ->maxSize(10240)
                                ->disk('public')
                                ->directory('receipts')
                                ->visibility('public')
                                ->helperText('Format: JPG, PNG, WEBP. Maksimal 10 MB.'),
                        ])
                        ->action(function (array $data, Order $record) {
                            $updateData = [
                                'status' => 'shipped',
                                'courier_name' => $data['courier_name'],
                                'tracking_number' => $data['tracking_number'],
                            ];

                            if (!empty($data['receipt_image'])) {
                                $updateData['receipt_image'] = $data['receipt_image'];
                            }

                            $record->update($updateData);
                            
                            \Illuminate\Support\Facades\Mail::to($record->user->email)->send(new \App\Mail\OrderShippedMail($record));

                            Notification::make()
                                ->title('Pesanan Telah Dikirim')
                                ->body("Status pesanan #{$record->order_number} berhasil diubah ke Shipped.")
                                ->success()
                                ->send();
                        }),

                    EditAction::make()
                        ->label('Lihat & Ubah Detail'),
                ])
                ->tooltip('Menu Aksi')
                ->icon('heroicon-m-ellipsis-vertical')
                ->color('gray'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
