<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductImportService
{
    /**
     * Download format template CSV untuk import produk
     */
    public function downloadTemplate(): StreamedResponse
    {
        $filename = 'template_import_produk_' . date('Ymd') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM for Microsoft Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header kolom
            fputcsv($handle, [
                'sku',
                'name',
                'category',
                'price',
                'sale_price',
                'stock',
                'weight',
                'description',
                'is_active',
            ]);

            // Sample Baris 1
            fputcsv($handle, [
                'PRD-TSHIRT-01',
                'Kaos Polos Combed 30s Premium',
                'Pakaian Pria',
                '85000',
                '75000',
                '50',
                '200',
                'Kaos bahan 100% cotton combed nyaman dan adem untuk pemakaian sehari-hari.',
                '1',
            ]);

            // Sample Baris 2
            fputcsv($handle, [
                'PRD-SNK-02',
                'Sneakers Urban Run Pro Edition',
                'Sepatu',
                '350000',
                '299000',
                '20',
                '800',
                'Sneakers kasual dengan bantalan empuk dan desain minimalis modern.',
                '1',
            ]);

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Import produk dari file CSV
     */
    public function importFromCsv(string $filePath): array
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            return [
                'success' => false,
                'message' => 'File CSV tidak dapat dibaca atau tidak ditemukan.',
                'created' => 0,
                'updated' => 0,
                'errors' => ['File tidak dapat diakses.'],
            ];
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return [
                'success' => false,
                'message' => 'Gagal membuka stream file CSV.',
                'created' => 0,
                'updated' => 0,
                'errors' => ['Gagal membuka file.'],
            ];
        }

        // Baca baris pertama (Header)
        $rawHeader = fgetcsv($handle);
        if (!$rawHeader) {
            fclose($handle);
            return [
                'success' => false,
                'message' => 'File CSV kosong.',
                'created' => 0,
                'updated' => 0,
                'errors' => ['Header kosong.'],
            ];
        }

        // Hapus UTF-8 BOM pada kolom pertama jika ada
        $rawHeader[0] = preg_replace('/^\xEF\xBB\xBF/', '', $rawHeader[0]);

        // Standarisasi header kolom: lowercase dan trim
        $headers = array_map(function ($h) {
            return strtolower(trim($h));
        }, $rawHeader);

        $created = 0;
        $updated = 0;
        $errors = [];
        $rowNum = 1;

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle)) !== false) {
                $rowNum++;

                // Abaikan jika baris kosong
                if (empty(array_filter($row, fn ($val) => !is_null($val) && trim($val) !== ''))) {
                    continue;
                }

                // Map kolom dengan header
                $data = [];
                foreach ($headers as $index => $header) {
                    $data[$header] = isset($row[$index]) ? trim($row[$index]) : null;
                }

                $name = $data['name'] ?? null;
                if (empty($name)) {
                    $errors[] = "Baris {$rowNum}: Kolom 'name' wajib diisi.";
                    continue;
                }

                $rawPrice = str_replace(['Rp', '.', ' '], '', $data['price'] ?? '0');
                $price = is_numeric($rawPrice) ? (float) $rawPrice : 0;
                if ($price <= 0) {
                    $errors[] = "Baris {$rowNum}: Kolom 'price' harus angka lebih dari 0.";
                    continue;
                }

                $rawSalePrice = str_replace(['Rp', '.', ' '], '', $data['sale_price'] ?? '0');
                $salePrice = is_numeric($rawSalePrice) && (float) $rawSalePrice > 0 ? (float) $rawSalePrice : null;

                $stock = isset($data['stock']) && is_numeric($data['stock']) ? (int) $data['stock'] : 0;
                $weight = isset($data['weight']) && is_numeric($data['weight']) ? (float) $data['weight'] : 200.00;
                $sku = !empty($data['sku']) ? strtoupper(trim($data['sku'])) : null;
                $description = $data['description'] ?? null;
                $isActive = isset($data['is_active']) ? (bool) $data['is_active'] : true;

                // Resolusi Kategori
                $categoryId = null;
                $categoryName = $data['category'] ?? null;
                if (!empty($categoryName)) {
                    $category = Category::where('name', $categoryName)->first();
                    if (!$category) {
                        $category = Category::create([
                            'name' => $categoryName,
                            'slug' => Str::slug($categoryName) . '-' . Str::random(4),
                            'is_active' => true,
                        ]);
                    }
                    $categoryId = $category->id;
                }

                // Cek apakah produk dengan SKU tersebut sudah ada (Upsert)
                $product = null;
                if ($sku) {
                    $product = Product::where('sku', $sku)->first();
                }

                if ($product) {
                    // Update data produk yang ada
                    $updatePayload = [
                        'name' => $name,
                        'price' => $price,
                        'sale_price' => $salePrice,
                        'stock' => $stock,
                        'weight' => $weight,
                        'is_active' => $isActive,
                        'video_source_type' => $product->video_source_type ?: 'upload',
                    ];

                    if ($categoryId) {
                        $updatePayload['category_id'] = $categoryId;
                    }

                    if ($description) {
                        $updatePayload['description'] = $description;
                    }

                    $product->update($updatePayload);
                    $updated++;
                } else {
                    // Buat produk baru
                    $finalSku = $sku ?: ('PRD-' . strtoupper(Str::random(6)));
                    $slug = Str::slug($name) . '-' . Str::random(5);

                    Product::create([
                        'sku' => $finalSku,
                        'name' => $name,
                        'slug' => $slug,
                        'category_id' => $categoryId,
                        'price' => $price,
                        'sale_price' => $salePrice,
                        'stock' => $stock,
                        'weight' => $weight,
                        'description' => $description,
                        'is_active' => $isActive,
                        'video_source_type' => 'upload',
                    ]);
                    $created++;
                }
            }

            DB::commit();
            fclose($handle);

            return [
                'success' => true,
                'created' => $created,
                'updated' => $updated,
                'total' => $created + $updated,
                'errors' => $errors,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            if (is_resource($handle)) {
                fclose($handle);
            }

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage(),
                'created' => $created,
                'updated' => $updated,
                'errors' => array_merge($errors, [$e->getMessage()]),
            ];
        }
    }
}
