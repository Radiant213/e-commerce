<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(
        protected MidtransService $midtransService
    ) {}

    /**
     * Get or regenerate Snap Token for an existing pending order.
     */
    public function getSnapToken(Request $request, int $orderId): JsonResponse
    {
        $order = Order::where('user_id', $request->user()->id)
            ->where('id', $orderId)
            ->with(['items', 'user', 'payment'])
            ->firstOrFail();

        // First, check if already settled in Midtrans
        if ($order->status === 'pending') {
            $order = $this->midtransService->syncOrderStatus($order);
        }

        if ($order->status !== 'pending') {
            return response()->json([
                'message' => 'Pesanan ini sudah ' . ($order->status === 'paid' ? 'LUNAS (dibayar)' : $order->status) . '.',
                'order' => $order,
            ], 400);
        }

        try {
            $token = $this->midtransService->createSnapToken($order);

            return response()->json([
                'snap_token' => $token,
                'order' => $order->fresh('payment'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal membuat token pembayaran: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * MidTrans Webhook callback notification handler.
     */
    public function notification(): JsonResponse
    {
        try {
            $payment = $this->midtransService->handleNotification();

            return response()->json([
                'status' => 'success',
                'message' => 'Notification handled successfully',
                'payment' => $payment,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'ok',
                'message' => 'Ping acknowledged',
            ], 200);
        }
    }

    /**
     * Check payment status by order ID.
     */
    public function status(Request $request, int $orderId): JsonResponse
    {
        $order = Order::where('user_id', $request->user()->id)
            ->where('id', $orderId)
            ->with('payment')
            ->firstOrFail();

        if ($order->status === 'pending') {
            $order = $this->midtransService->syncOrderStatus($order);
        }

        return response()->json([
            'order_status' => $order->status,
            'payment' => $order->payment,
        ]);
    }
}
