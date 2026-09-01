<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Konfirmasi Pesanan</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f8fafc; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #4f46e5, #06b6d4); color: white; padding: 28px 24px; text-align: center; }
        .content { padding: 24px; }
        .order-info { background: #f1f5f9; border-radius: 8px; padding: 16px; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        .table th { text-align: left; padding: 8px; border-bottom: 2px solid #e2e8f0; font-size: 13px; color: #64748b; }
        .table td { padding: 12px 8px; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        .total-row { font-weight: bold; font-size: 16px; color: #1e293b; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0; font-size:24px;">Terima Kasih atas Pesanan Anda!</h1>
            <p style="margin:8px 0 0 0; opacity:0.9;">Pesanan #{{ $order->order_number }}</p>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $order->shipping_name }}</strong>,</p>
            <p>Pesanan Anda telah kami terima dan sedang menunggu pembayaran. Berikut adalah rincian pesanan Anda:</p>

            <div class="order-info">
                <strong>Alamat Pengiriman:</strong><br>
                {{ $order->shipping_address }}, {{ $order->shipping_city }} {{ $order->shipping_postal_code }}<br>
                Telp: {{ $order->shipping_phone }}
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th style="text-align:center;">Qty</th>
                        <th style="text-align:right;">Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td style="text-align:center;">{{ $item->quantity }}</td>
                        <td style="text-align:right;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" style="text-align:right;">Subtotal:</td>
                        <td style="text-align:right;">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @if($order->shipping_cost > 0)
                    <tr>
                        <td colspan="2" style="text-align:right;">Ongkos Kirim:</td>
                        <td style="text-align:right;">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td colspan="2" style="text-align:right;">Total Pembayaran:</td>
                        <td style="text-align:right; color:#4f46e5;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
