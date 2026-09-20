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
     * Download format template Excel (.xlsx) untuk import produk
     */
    public function downloadTemplate(): StreamedResponse
    {
        $filename = 'Template_Import_Produk_' . date('Ymd') . '.xlsx';

        $headers = [
            'SKU',
            'Nama Produk',
            'Kategori',
            'Harga Normal',
            'Harga Promo',
            'Stok',
            'Berat (gram)',
            'Deskripsi',
            'Status Aktif (1/0)',
        ];

        $sampleRows = [
            [
                'PRD-TSHIRT-01',
                'Kaos Polos Combed 30s Premium',
                'Pakaian Pria',
                '85000',
                '75000',
                '50',
                '200',
                'Kaos bahan 100% cotton combed nyaman dan adem untuk pemakaian sehari-hari.',
                '1',
            ],
            [
                'PRD-SNK-02',
                'Sneakers Urban Run Pro Edition',
                'Sepatu',
                '350000',
                '299000',
                '20',
                '800',
                'Sneakers kasual dengan bantalan empuk dan desain minimalis modern.',
                '1',
            ],
            [
                'PRD-BAG-03',
                'Backpack Laptop Waterproof 20L',
                'Tas & Ransel',
                '225000',
                '195000',
                '35',
                '550',
                'Tas ransel laptop tahan air dengan banyak kompartemen dan port USB charger.',
                '1',
            ],
        ];

        return SimpleXlsxExporter::download($filename, $headers, $sampleRows, 'Template Produk');
    }

    /**
     * Import produk dari file (Mendukung .xlsx dan .csv)
     */
    public function importFromFile(string $filePath): array
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            return [
                'success' => false,
                'message' => 'File tidak dapat dibaca atau tidak ditemukan.',
                'created' => 0,
                'updated' => 0,
                'errors' => ['File tidak dapat diakses.'],
            ];
        }

        // Check if file is XLSX (ZIP container starting with PK)
        $isXlsx = false;
        $fh = @fopen($filePath, 'r');
        if ($fh) {
            $magic = fread($fh, 4);
            fclose($fh);
            if ($magic === "PK\x03\x04") {
                $isXlsx = true;
            }
        }

        $allRows = [];

        if ($isXlsx) {
            $allRows = SimpleXlsxExporter::parse($filePath);
        } else {
            // Read as CSV
            $handle = @fopen($filePath, 'r');
            if ($handle) {
                while (($line = fgetcsv($handle)) !== false) {
                    $allRows[] = $line;
                }
                fclose($handle);
            }
        }

        if (empty($allRows)) {
            return [
                'success' => false,
                'message' => 'File kosong atau format tidak dapat dibaca.',
                'created' => 0,
                'updated' => 0,
                'errors' => ['Data kosong.'],
            ];
        }

        // Header extraction
        $rawHeader = array_shift($allRows);
        if (isset($rawHeader[0])) {
            $rawHeader[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $rawHeader[0]);
        }

        // Standardize header keys
        $headerMap = [];
        foreach ($rawHeader as $idx => $name) {
            $cleanName = strtolower(trim((string) $name));
            $key = match (true) {
                str_contains($cleanName, 'sku') => 'sku',
                str_contains($cleanName, 'nama') || $cleanName === 'name' => 'name',
                str_contains($cleanName, 'kategori') || $cleanName === 'category' => 'category',
                str_contains($cleanName, 'promo') || str_contains($cleanName, 'sale') || str_contains($cleanName, 'coret') => 'sale_price',
                str_contains($cleanName, 'harga') || $cleanName === 'price' => 'price',
                str_contains($cleanName, 'stok') || $cleanName === 'stock' => 'stock',
                str_contains($cleanName, 'berat') || $cleanName === 'weight' => 'weight',
                str_contains($cleanName, 'deskripsi') || str_contains($cleanName, 'keterangan') || $cleanName === 'description' => 'description',
                str_contains($cleanName, 'aktif') || str_contains($cleanName, 'status') || $cleanName === 'is_active' => 'is_active',
                default => $cleanName,
            };
            $headerMap[$idx] = $key;
        }

        $created = 0;
        $updated = 0;
        $errors = [];
        $rowNum = 1;

        DB::beginTransaction();

        try {
            foreach ($allRows as $row) {
                $rowNum++;

                if (empty(array_filter($row, fn ($val) => !is_null($val) && trim((string) $val) !== ''))) {
                    continue;
                }

                $data = [];
                foreach ($headerMap as $cIdx => $fieldKey) {
                    $data[$fieldKey] = isset($row[$cIdx]) ? trim((string) $row[$cIdx]) : null;
                }

                $name = $data['name'] ?? null;
                if (empty($name)) {
                    $errors[] = "Baris {$rowNum}: Kolom 'Nama Produk' wajib diisi.";
                    continue;
                }

                $rawPrice = str_replace(['Rp', '.', ',', ' '], '', $data['price'] ?? '0');
                $price = is_numeric($rawPrice) ? (float) $rawPrice : 0;
                if ($price <= 0) {
                    $errors[] = "Baris {$rowNum}: Kolom 'Harga Normal' harus angka lebih dari 0.";
                    continue;
                }

                $rawSalePrice = str_replace(['Rp', '.', ',', ' '], '', $data['sale_price'] ?? '');
                $salePrice = (is_numeric($rawSalePrice) && (float) $rawSalePrice > 0) ? (float) $rawSalePrice : null;

                $stock = (isset($data['stock']) && is_numeric($data['stock'])) ? (int) $data['stock'] : 0;
                $weight = (isset($data['weight']) && is_numeric($data['weight'])) ? (float) $data['weight'] : 200.00;
                $sku = !empty($data['sku']) ? strtoupper(trim((string) $data['sku'])) : null;
                $description = $data['description'] ?? null;

                $isActiveVal = $data['is_active'] ?? '1';
                $isActive = !in_array(strtolower((string) $isActiveVal), ['0', 'false', 'tidak', 'off', 'nonaktif']);

                // Category resolution
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

                // Check for existing product by SKU
                $product = null;
                if ($sku) {
                    $product = Product::where('sku', $sku)->first();
                }

                if ($product) {
                    $updatePayload = [
                        'name' => $name,
                        'price' => $price,
                        'sale_price' => $salePrice,
                        'stock' => $stock,
                        'weight' => $weight,
                        'is_active' => $isActive,
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

            return [
                'success' => true,
                'created' => $created,
                'updated' => $updated,
                'total' => $created + $updated,
                'errors' => $errors,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage(),
                'created' => $created,
                'updated' => $updated,
                'errors' => array_merge($errors, [$e->getMessage()]),
            ];
        }
    }

    /**
     * Backward-compatibility proxy for importFromCsv
     */
    public function importFromCsv(string $filePath): array
    {
        return $this->importFromFile($filePath);
    }
}
