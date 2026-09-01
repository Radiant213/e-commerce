<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pembayaran Berhasil</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f8fafc; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 28px 24px; text-align: center; }
        .content { padding: 24px; }
        .badge { display: inline-block; background: #dcfce7; color: #166534; padding: 6px 16px; border-radius: 9999px; font-weight: bold; font-size: 14px; margin-bottom: 16px; }
        .order-info { background: #f1f5f9; border-radius: 8px; padding: 16px; margin: 16px 0; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0; font-size:24px;">Pembayaran Berhasil! 🎉</h1>
            <p style="margin:8px 0 0 0; opacity:0.9;">Pesanan #{{ $order->order_number }}</p>
        </div>
        <div class="content">
            <div style="text-align: center;">
                <span class="badge">LUNAS</span>
            </div>
            <p>Halo <strong>{{ $order->shipping_name }}</strong>,</p>
            <p>Kami telah menerima pembayaran sebesar <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong> untuk pesanan Anda. Pesanan sekarang sedang kami siapkan untuk diproses dan dikirim ke alamat Anda.</p>

            <div class="order-info">
                <strong>Detail Pengiriman:</strong><br>
                {{ $order->shipping_address }}, {{ $order->shipping_city }} {{ $order->shipping_postal_code }}<br>
                Penerima: {{ $order->shipping_name }} ({{ $order->shipping_phone }})
            </div>

            <p style="font-size: 13px; color: #64748b;">Anda dapat melacak status pesanan Anda melalui dashboard akun di website kami.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
