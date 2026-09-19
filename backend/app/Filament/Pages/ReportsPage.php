<?php

namespace App\Filament\Pages;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use BackedEnum;
use UnitEnum;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportsPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationLabel = 'Pusat Laporan';

    protected static ?string $title = 'Pusat Laporan & Ekspor Data';

    protected static ?string $slug = 'reports';

    protected static string|UnitEnum|null $navigationGroup = 'Laporan & Analisis';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.reports-page';

    // Active category tab: 'sales', 'inventory', 'bestseller', 'customers'
    public string $activeTab = 'sales';

    // Sales Filters
    public ?string $salesStartDate = null;
    public ?string $salesEndDate = null;
    public string $salesStatus = 'all';

    // Inventory Filters
    public string $inventoryCategoryId = 'all';
    public string $inventoryStockFilter = 'all';

    // Best Seller Filters
    public int $bestsellerLimit = 10;
    public int $bestsellerDays = 30;

    // Top Customer Filters
    public int $customerMinOrders = 1;

    public function mount(): void
    {
        $this->salesStartDate = now()->subDays(30)->format('Y-m-d');
        $this->salesEndDate = now()->format('Y-m-d');
    }

    public function setActiveTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    /**
     * Hitung metrik & ambil daftar transaksi untuk Laporan Penjualan
     */
    public function getSalesData(): array
    {
        $startDate = $this->salesStartDate ? Carbon::parse($this->salesStartDate)->startOfDay() : now()->subDays(30)->startOfDay();
        $endDate = $this->salesEndDate ? Carbon::parse($this->salesEndDate)->endOfDay() : now()->endOfDay();

        $query = Order::with(['items', 'payment', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($this->salesStatus !== 'all' && filled($this->salesStatus)) {
            $query->where('status', $this->salesStatus);
        }

        $allOrders = $query->latest()->get();

        $totalRevenue = $allOrders->whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])->sum('total');
        $totalOrders = $allOrders->count();
        $paidOrdersCount = $allOrders->whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])->count();
        $totalItemsSold = $allOrders->whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])->sum(fn ($o) => $o->items->sum('quantity'));

        return [
            'summary' => [
                'total_revenue' => $totalRevenue,
                'total_orders' => $totalOrders,
                'paid_orders_count' => $paidOrdersCount,
                'total_items_sold' => $totalItemsSold,
            ],
            'orders' => $allOrders->take(50), // Preview 50 data teratas di UI
            'total_count' => $totalOrders,
        ];
    }

    /**
     * Hitung metrik & ambil daftar produk untuk Laporan Stok
     */
    public function getInventoryData(): array
    {
        $query = Product::with(['category', 'variants']);

        if ($this->inventoryCategoryId !== 'all' && filled($this->inventoryCategoryId)) {
            $query->where('category_id', $this->inventoryCategoryId);
        }

        if ($this->inventoryStockFilter === 'out_of_stock') {
            $query->where('stock', '<=', 0);
        } elseif ($this->inventoryStockFilter === 'low_stock') {
            $query->where('stock', '>', 0)->where('stock', '<=', 10);
        } elseif ($this->inventoryStockFilter === 'in_stock') {
            $query->where('stock', '>', 10);
        }

        $allProducts = $query->orderBy('stock', 'asc')->get();

        $totalStockUnits = $allProducts->sum('stock');
        $totalValuation = $allProducts->sum(fn ($p) => $p->stock * ($p->price ?? 0));
        $outOfStockCount = $allProducts->where('stock', '<=', 0)->count();
        $lowStockCount = $allProducts->where('stock', '>', 0)->where('stock', '<=', 10)->count();

        return [
            'summary' => [
                'total_units' => $totalStockUnits,
                'total_valuation' => $totalValuation,
                'out_of_stock' => $outOfStockCount,
                'low_stock' => $lowStockCount,
                'total_products' => $allProducts->count(),
            ],
            'products' => $allProducts->take(50),
            'total_count' => $allProducts->count(),
            'categories' => Category::orderBy('name')->get(),
        ];
    }

    /**
     * Ambil data Produk Terlaris (Best Sellers)
     */
    public function getBestSellerData(): array
    {
        $startDate = now()->subDays($this->bestsellerDays)->startOfDay();

        $items = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['paid', 'processing', 'shipped', 'delivered'])
            ->where('orders.created_at', '>=', $startDate)
            ->selectRaw('order_items.product_name, order_items.product_id, SUM(order_items.quantity) as total_sold, SUM(order_items.subtotal) as total_revenue')
            ->groupBy('order_items.product_name', 'order_items.product_id')
            ->orderByDesc('total_sold')
            ->limit($this->bestsellerLimit)
            ->get();

        return [
            'items' => $items,
            'period_days' => $this->bestsellerDays,
        ];
    }

    /**
     * Ambil data Pelanggan Terloyal (Top Customers)
     */
    public function getCustomerData(): array
    {
        $customers = Order::query()
            ->whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])
            ->selectRaw('shipping_name, shipping_phone, shipping_email, COUNT(*) as order_count, SUM(total) as total_spent, MAX(created_at) as last_order_at')
            ->groupBy('shipping_name', 'shipping_phone', 'shipping_email')
            ->havingRaw('COUNT(*) >= ?', [$this->customerMinOrders])
            ->orderByDesc('total_spent')
            ->limit(30)
            ->get();

        return [
            'customers' => $customers,
        ];
    }

    /**
     * Ekspor CSV Laporan Penjualan (Excel Friendly via UTF-8 BOM)
     */
    public function exportSalesCsv(): StreamedResponse
    {
        $startDate = $this->salesStartDate ? Carbon::parse($this->salesStartDate)->startOfDay() : now()->subDays(30)->startOfDay();
        $endDate = $this->salesEndDate ? Carbon::parse($this->salesEndDate)->endOfDay() : now()->endOfDay();

        $query = Order::with(['items', 'payment'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($this->salesStatus !== 'all' && filled($this->salesStatus)) {
            $query->where('status', $this->salesStatus);
        }

        $orders = $query->latest()->get();
        $filename = 'Laporan_Penjualan_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM for automatic Excel delimiter & character recognition
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No',
                'No Pesanan',
                'Tanggal',
                'Nama Pembeli',
                'No Telepon',
                'Metode Pembayaran',
                'Status Pesanan',
                'Jumlah Item',
                'Total Tagihan (IDR)',
            ]);

            foreach ($orders as $index => $order) {
                fputcsv($handle, [
                    $index + 1,
                    $order->order_number,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->shipping_name,
                    $order->shipping_phone,
                    strtoupper($order->payment?->payment_type ?? 'ONLINE'),
                    strtoupper($order->status),
                    $order->items->sum('quantity'),
                    $order->total,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Ekspor CSV Laporan Stok & Inventaris (Excel Friendly)
     */
    public function exportInventoryCsv(): StreamedResponse
    {
        $query = Product::with(['category']);

        if ($this->inventoryCategoryId !== 'all' && filled($this->inventoryCategoryId)) {
            $query->where('category_id', $this->inventoryCategoryId);
        }

        if ($this->inventoryStockFilter === 'out_of_stock') {
            $query->where('stock', '<=', 0);
        } elseif ($this->inventoryStockFilter === 'low_stock') {
            $query->where('stock', '>', 0)->where('stock', '<=', 10);
        } elseif ($this->inventoryStockFilter === 'in_stock') {
            $query->where('stock', '>', 10);
        }

        $products = $query->orderBy('stock', 'asc')->get();
        $filename = 'Laporan_Stok_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($products) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No',
                'SKU',
                'Nama Produk',
                'Kategori',
                'Harga Satuan (IDR)',
                'Sisa Stok',
                'Total Valuasi Stok (IDR)',
                'Status Stok',
            ]);

            foreach ($products as $index => $prod) {
                $status = $prod->stock <= 0 ? 'HABIS' : ($prod->stock <= 10 ? 'MENIPIS' : 'TERSEDIA');
                fputcsv($handle, [
                    $index + 1,
                    $prod->sku ?: '-',
                    $prod->name,
                    $prod->category?->name ?? 'Tanpa Kategori',
                    $prod->price,
                    $prod->stock,
                    $prod->stock * ($prod->price ?? 0),
                    $status,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Ekspor CSV Produk Terlaris
     */
    public function exportBestSellerCsv(): StreamedResponse
    {
        $startDate = now()->subDays($this->bestsellerDays)->startOfDay();

        $items = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['paid', 'processing', 'shipped', 'delivered'])
            ->where('orders.created_at', '>=', $startDate)
            ->selectRaw('order_items.product_name, SUM(order_items.quantity) as total_sold, SUM(order_items.subtotal) as total_revenue')
            ->groupBy('order_items.product_name')
            ->orderByDesc('total_sold')
            ->limit($this->bestsellerLimit)
            ->get();

        $filename = 'Laporan_Produk_Terlaris_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($items) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'Peringkat',
                'Nama Produk',
                'Total Terjual (Unit)',
                'Estimasi Omset Penjualan (IDR)',
            ]);

            foreach ($items as $index => $item) {
                fputcsv($handle, [
                    $index + 1,
                    $item->product_name,
                    $item->total_sold,
                    $item->total_revenue,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Ekspor CSV Pelanggan Terloyal
     */
    public function exportCustomerCsv(): StreamedResponse
    {
        $customers = Order::query()
            ->whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])
            ->selectRaw('shipping_name, shipping_phone, shipping_email, COUNT(*) as order_count, SUM(total) as total_spent, MAX(created_at) as last_order_at')
            ->groupBy('shipping_name', 'shipping_phone', 'shipping_email')
            ->havingRaw('COUNT(*) >= ?', [$this->customerMinOrders])
            ->orderByDesc('total_spent')
            ->limit(100)
            ->get();

        $filename = 'Laporan_Pelanggan_Loyal_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($customers) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No',
                'Nama Pelanggan',
                'No Telepon',
                'Email',
                'Jumlah Pesanan Selesai',
                'Total Akumulasi Belanja (IDR)',
                'Transaksi Terakhir',
            ]);

            foreach ($customers as $index => $cust) {
                fputcsv($handle, [
                    $index + 1,
                    $cust->shipping_name,
                    $cust->shipping_phone,
                    $cust->shipping_email ?: '-',
                    $cust->order_count,
                    $cust->total_spent,
                    $cust->last_order_at,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
