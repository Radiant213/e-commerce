<?php

namespace App\Observers;

use App\Mail\OrderShippedMail;
use App\Mail\PaymentSuccessMail;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderObserver
{
    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        if ($order->wasChanged('status')) {
            $previousStatus = $order->getOriginal('status');
            $newStatus = $order->status;

            // 1. Status berubah menjadi 'paid' (pesanan berhasil dibayar / kebeli)
            if ($previousStatus !== 'paid' && $newStatus === 'paid') {
                if ($order->user && !empty($order->user->email)) {
                    try {
                        Mail::to($order->user->email)->send(new PaymentSuccessMail($order));
                    } catch (\Throwable $e) {
                        Log::warning("Gagal mengirim email pembayaran berhasil untuk order #{$order->order_number}: " . $e->getMessage());
                    }
                }
            }

            // 2. Status berubah menjadi 'shipped' (pesanan sedang dikirim)
            if ($previousStatus !== 'shipped' && $newStatus === 'shipped') {
                if ($order->user && !empty($order->user->email)) {
                    try {
                        Mail::to($order->user->email)->send(new OrderShippedMail($order));
                    } catch (\Throwable $e) {
                        Log::warning("Gagal mengirim email pesanan dikirim untuk order #{$order->order_number}: " . $e->getMessage());
                    }
                }
            }
        }
    }
}
