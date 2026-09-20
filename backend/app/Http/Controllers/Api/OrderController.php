<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected \App\Services\MidtransService $midtransService
    ) {}

    /**
     * List user orders with pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with(['items.product.primaryImage', 'payment'])
            ->latest()
            ->paginate(10);

        // Auto-sync any pending orders with Midtrans status
        foreach ($orders as $order) {
            if ($order->status === 'pending') {
                $this->midtransService->syncOrderStatus($order);
            }
        }

        // Refresh orders collection after potential sync updates
        $orders = Order::where('user_id', $request->user()->id)
            ->with(['items.product.primaryImage', 'payment'])
            ->latest()
            ->paginate(10);

        return response()->json($orders);
    }

    /**
     * Get single order detail.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $order = Order::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->with(['items.product.primaryImage', 'payment'])
            ->firstOrFail();

        if ($order->status === 'pending') {
            $order = $this->midtransService->syncOrderStatus($order);
        }

        return response()->json(['order' => $order]);
    }

    /**
     * Create order from current cart (Checkout).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:1000',
            'shipping_city' => 'nullable|string|max:100',
            'shipping_postal_code' => 'nullable|string|max:10',
            'shipping_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $result = $this->orderService->createOrder(
                $request->user()->id,
                $validated
            );

            return response()->json([
                'message' => 'Pesanan berhasil dibuat!',
                'order' => $result['order'],
                'snap_token' => $result['snap_token'],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Cancel order.
     */
    public function cancel(Request $request, int $id): JsonResponse
    {
        $order = Order::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        try {
            $cancelledOrder = $this->orderService->cancelOrder($order);

            return response()->json([
                'message' => 'Pesanan berhasil dibatalkan.',
                'order' => $cancelledOrder,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }
    /**
     * Customer confirms delivery.
     */
    public function confirmDelivery(Request $request, int $id): JsonResponse
    {
        $order = Order::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        if ($order->status !== 'shipped') {
            return response()->json([
                'message' => 'Hanya pesanan dengan status "shipped" (sedang dikirim) yang bisa dikonfirmasi.'
            ], 422);
        }

        $order->update(['status' => 'delivered']);

        return response()->json([
            'message' => 'Terima kasih, pesanan telah berhasil dikonfirmasi diterima.',
            'order' => $order,
        ]);
    }
}
