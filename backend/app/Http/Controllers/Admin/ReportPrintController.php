<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportPrintController extends Controller
{
    protected function authorizeAdmin(): void
    {
        if (!auth()->check()) {
            redirect()->guest('/admin/login')->send();
            exit;
        }

        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses khusus Administrator.');
        }
    }

    /**
     * Cetak Invoice & Packing Slip Pesanan
     */
    public function invoice(Order $order)
    {
        $this->authorizeAdmin();

        $order->load(['items.product.primaryImage', 'items.variant', 'payment', 'user']);

        return view('print.invoice', [
            'order' => $order,
        ]);
    }

    /**
     * Cetak Laporan Penjualan & Keuangan
     */
    public function salesReport(Request $request)
    {
        $this->authorizeAdmin();

        $startDate = $request->query('start_date') ? Carbon::parse($request->query('start_date'))->startOfDay() : now()->subDays(30)->startOfDay();
        $endDate = $request->query('end_date') ? Carbon::parse($request->query('end_date'))->endOfDay() : now()->endOfDay();
        $status = $request->query('status', 'all');

        $query = Order::with(['items', 'payment', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($status !== 'all' && filled($status)) {
            $query->where('status', $status);
        }

        $orders = $query->latest()->get();

        $totalRevenue = $orders->whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])->sum('total');
        $totalOrders = $orders->count();
        $paidOrdersCount = $orders->whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])->count();
        $totalItemsSold = $orders->whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])->sum(fn ($o) => $o->items->sum('quantity'));

        return view('print.sales-report-print', [
            'orders' => $orders,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'status' => $status,
            'summary' => [
                'total_revenue' => $totalRevenue,
                'total_orders' => $totalOrders,
                'paid_orders_count' => $paidOrdersCount,
                'total_items_sold' => $totalItemsSold,
            ],
        ]);
    }

    /**
     * Cetak Laporan Stok & Inventaris Gudang
     */
    public function inventoryReport(Request $request)
    {
        $this->authorizeAdmin();

        $categoryId = $request->query('category_id');
        $stockFilter = $request->query('stock_filter', 'all');

        $query = Product::with(['category', 'variants']);

        if ($categoryId && $categoryId !== 'all') {
            $query->where('category_id', $categoryId);
        }

        if ($stockFilter === 'out_of_stock') {
            $query->where('stock', '<=', 0);
        } elseif ($stockFilter === 'low_stock') {
            $query->where('stock', '>', 0)->where('stock', '<=', 10);
        } elseif ($stockFilter === 'in_stock') {
            $query->where('stock', '>', 10);
        }

        $products = $query->orderBy('stock', 'asc')->get();

        $totalStockUnits = $products->sum('stock');
        $totalValuation = $products->sum(fn ($p) => $p->stock * ($p->price ?? 0));
        $outOfStockCount = $products->where('stock', '<=', 0)->count();
        $lowStockCount = $products->where('stock', '>', 0)->where('stock', '<=', 10)->count();

        $categoryName = 'Semua Kategori';
        if ($categoryId && $categoryId !== 'all') {
            $cat = Category::find($categoryId);
            if ($cat) $categoryName = $cat->name;
        }

        return view('print.inventory-report-print', [
            'products' => $products,
            'categoryName' => $categoryName,
            'stockFilter' => $stockFilter,
            'summary' => [
                'total_units' => $totalStockUnits,
                'total_valuation' => $totalValuation,
                'out_of_stock' => $outOfStockCount,
                'low_stock' => $lowStockCount,
                'total_products' => $products->count(),
            ],
        ]);
    }
}
