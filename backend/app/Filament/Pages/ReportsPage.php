<?php

namespace App\Filament\Pages;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\SimpleXlsxExporter;
use BackedEnum;
use UnitEnum;
use Filament\Pages\Page;
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
            'orders' => $allOrders->take(50),
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
     * Menggunakan leftJoin ke tabel users agar aman dari error missing column.
     */
    public function getCustomerData(): array
    {
        try {
            $minOrders = max(1, (int) $this->customerMinOrders);

            $customers = Order::query()
                ->leftJoin('users', 'orders.user_id', '=', 'users.id')
                ->whereIn('orders.status', ['paid', 'processing', 'shipped', 'delivered'])
                ->selectRaw('orders.user_id, orders.shipping_name, orders.shipping_phone, users.email as customer_email, COUNT(orders.id) as order_count, SUM(orders.total) as total_spent, MAX(orders.created_at) as last_order_at')
                ->groupBy('orders.user_id', 'orders.shipping_name', 'orders.shipping_phone', 'users.email')
                ->havingRaw('COUNT(orders.id) >= ?', [$minOrders])
                ->orderByDesc('total_spent')
                ->limit(50)
                ->get();

            return [
                'customers' => $customers,
            ];
        } catch (\Throwable $e) {
            report($e);
            return [
                'customers' => collect([]),
            ];
        }
    }

    /**
     * Ekspor Native Excel (.xlsx) Laporan Penjualan (Rupiah Formatted & Left-Aligned)
     */
    public function exportSalesExcel(): StreamedResponse
    {
        $startDate = $this->salesStartDate ? Carbon::parse($this->salesStartDate)->startOfDay() : now()->subDays(30)->startOfDay();
        $endDate = $this->salesEndDate ? Carbon::parse($this->salesEndDate)->endOfDay() : now()->endOfDay();

        $query = Order::with(['items', 'payment'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($this->salesStatus !== 'all' && filled($this->salesStatus)) {
            $query->where('status', $this->salesStatus);
        }

        $orders = $query->latest()->get();
        $filename = 'Laporan_Penjualan_' . now()->format('Ymd_His') . '.xlsx';

        $headers = [
            'No',
            'No Pesanan',
            'Tanggal',
            'Nama Pembeli',
            'No Telepon',
            'Metode Pembayaran',
            'Status Pesanan',
            'Jumlah Item',
            'Total Tagihan',
        ];

        $rows = [];
        foreach ($orders as $index => $order) {
            $rows[] = [
                $index + 1,
                $order->order_number,
                $order->created_at->format('d/m/Y H:i'),
                $order->shipping_name,
                $order->shipping_phone,
                strtoupper($order->payment?->payment_type ?? 'ONLINE'),
                strtoupper($order->status),
                $order->items->sum('quantity') . ' Unit',
                'Rp ' . number_format($order->total, 0, ',', '.'),
            ];
        }

        return SimpleXlsxExporter::download($filename, $headers, $rows, 'Penjualan');
    }

    public function exportSalesCsv(): StreamedResponse
    {
        return $this->exportSalesExcel();
    }

    /**
     * Ekspor Native Excel (.xlsx) Laporan Stok (Rupiah Formatted & Left-Aligned)
     */
    public function exportInventoryExcel(): StreamedResponse
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
        $filename = 'Laporan_Stok_' . now()->format('Ymd_His') . '.xlsx';

        $headers = [
            'No',
            'SKU',
            'Nama Produk',
            'Kategori',
            'Harga Satuan',
            'Sisa Stok',
            'Total Valuasi Stok',
            'Status Stok',
        ];

        $rows = [];
        foreach ($products as $index => $prod) {
            $status = $prod->stock <= 0 ? 'HABIS' : ($prod->stock <= 10 ? 'MENIPIS' : 'TERSEDIA');
            $rows[] = [
                $index + 1,
                $prod->sku ?: '-',
                $prod->name,
                $prod->category?->name ?? 'Tanpa Kategori',
                'Rp ' . number_format($prod->price, 0, ',', '.'),
                $prod->stock . ' Unit',
                'Rp ' . number_format($prod->stock * ($prod->price ?? 0), 0, ',', '.'),
                $status,
            ];
        }

        return SimpleXlsxExporter::download($filename, $headers, $rows, 'Stok');
    }

    public function exportInventoryCsv(): StreamedResponse
    {
        return $this->exportInventoryExcel();
    }

    /**
     * Ekspor Native Excel (.xlsx) Produk Terlaris
     */
    public function exportBestSellerExcel(): StreamedResponse
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

        $filename = 'Laporan_Produk_Terlaris_' . now()->format('Ymd_His') . '.xlsx';

        $headers = [
            'Peringkat',
            'Nama Produk',
            'Total Terjual',
            'Estimasi Omset Penjualan',
        ];

        $rows = [];
        foreach ($items as $index => $item) {
            $rows[] = [
                $index + 1,
                $item->product_name,
                $item->total_sold . ' Unit',
                'Rp ' . number_format($item->total_revenue, 0, ',', '.'),
            ];
        }

        return SimpleXlsxExporter::download($filename, $headers, $rows, 'Produk Terlaris');
    }

    public function exportBestSellerCsv(): StreamedResponse
    {
        return $this->exportBestSellerExcel();
    }

    /**
     * Ekspor Native Excel (.xlsx) Pelanggan Terloyal
     */
    public function exportCustomerExcel(): StreamedResponse
    {
        $minOrders = max(1, (int) $this->customerMinOrders);

        $customers = Order::query()
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->whereIn('orders.status', ['paid', 'processing', 'shipped', 'delivered'])
            ->selectRaw('orders.user_id, orders.shipping_name, orders.shipping_phone, users.email as customer_email, COUNT(orders.id) as order_count, SUM(orders.total) as total_spent, MAX(orders.created_at) as last_order_at')
            ->groupBy('orders.user_id', 'orders.shipping_name', 'orders.shipping_phone', 'users.email')
            ->havingRaw('COUNT(orders.id) >= ?', [$minOrders])
            ->orderByDesc('total_spent')
            ->limit(100)
            ->get();

        $filename = 'Laporan_Pelanggan_Loyal_' . now()->format('Ymd_His') . '.xlsx';

        $headers = [
            'No',
            'Nama Pelanggan',
            'No Telepon',
            'Email',
            'Jumlah Pesanan Selesai',
            'Total Akumulasi Belanja',
            'Transaksi Terakhir',
        ];

        $rows = [];
        foreach ($customers as $index => $cust) {
            $rows[] = [
                $index + 1,
                $cust->shipping_name,
                $cust->shipping_phone,
                $cust->customer_email ?: '-',
                $cust->order_count . ' Pesanan',
                'Rp ' . number_format($cust->total_spent, 0, ',', '.'),
                $cust->last_order_at ? Carbon::parse($cust->last_order_at)->format('d/m/Y H:i') : '-',
            ];
        }

        return SimpleXlsxExporter::download($filename, $headers, $rows, 'Pelanggan Loyal');
    }

    public function exportCustomerCsv(): StreamedResponse
    {
        return $this->exportCustomerExcel();
    }
}
