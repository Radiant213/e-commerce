<?php

namespace App\Filament\Resources\Products\Tables;

use App\Services\ProductImportService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['category', 'primaryImage', 'images']))
            ->columns([
                ImageColumn::make('thumbnail_url')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl('https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=150&q=80'),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->sku ? 'SKU: ' . $record->sku : null)
                    ->label('Nama Produk'),

                TextColumn::make('category.name')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->label('Kategori'),

                TextColumn::make('price')
                    ->money('IDR')
                    ->sortable()
                    ->label('Harga Normal'),

                TextColumn::make('sale_price')
                    ->money('IDR')
                    ->placeholder('-')
                    ->sortable()
                    ->label('Harga Promo'),

                TextColumn::make('stock')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state === 0 => 'danger',
                        $state < 10 => 'warning',
                        default => 'success',
                    })
                    ->label('Sisa Stok'),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),

                ToggleColumn::make('is_featured')
                    ->label('Unggulan'),

                TextColumn::make('avg_rating')
                    ->numeric(decimalPlaces: 1)
                    ->label('Rating')
                    ->sortable(),

                TextColumn::make('total_sold')
                    ->numeric()
                    ->label('Terjual')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Dibuat'),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Kategori'),
                TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
                TernaryFilter::make('is_featured')
                    ->label('Produk Unggulan'),
            ])
            ->headerActions([
                Action::make('download_template')
                    ->label('Format CSV')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->color('gray')
                    ->action(fn (ProductImportService $service) => $service->downloadTemplate()),

                Action::make('import_csv')
                    ->label('Import CSV')
                    ->icon('heroicon-m-arrow-up-tray')
                    ->color('success')
                    ->modalHeading('Import Data Produk dari File CSV')
                    ->modalDescription('Unggah file CSV sesuai format template. Produk dengan SKU yang sama akan otomatis diperbarui (Upsert).')
                    ->form([
                        FileUpload::make('csv_file')
                            ->label('Pilih File CSV')
                            ->disk('local')
                            ->directory('temp-imports')
                            ->acceptedFileTypes(['text/csv', 'text/plain', 'application/csv', 'text/comma-separated-values'])
                            ->required()
                            ->preserveFilenames(),
                    ])
                    ->action(function (array $data, ProductImportService $service) {
                        $filePath = storage_path('app/' . $data['csv_file']);
                        $result = $service->importFromCsv($filePath);
                        if (file_exists($filePath)) {
                            @unlink($filePath);
                        }

                        if ($result['success']) {
                            Notification::make()
                                ->title('Import Produk Selesai')
                                ->body("Berhasil mengimpor {$result['created']} produk baru dan memperbarui {$result['updated']} produk.")
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Import Gagal')
                                ->body($result['message'] ?? 'Periksa format file CSV Anda.')
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
