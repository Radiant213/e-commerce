<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }} - RadiantCode Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        body {
            background-color: #f1f5f9;
            color: #0f172a;
            padding: 24px;
            font-size: 13px;
            line-height: 1.5;
        }
        .invoice-card {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
            padding: 36px;
            border: 1px solid #e2e8f0;
        }
        .header-actions {
            max-width: 800px;
            margin: 0 auto 16px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .btn-print {
            background: #4f46e5;
            color: #ffffff;
        }
        .btn-print:hover {
            background: #4338ca;
        }
        .btn-close {
            background: #e2e8f0;
            color: #334155;
        }
        .btn-close:hover {
            background: #cbd5e1;
        }
        .top-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 24px;
            margin-bottom: 24px;
        }
        .brand-logo {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .brand-logo span {
            color: #4f46e5;
        }
        .brand-sub {
            font-size: 11px;
            color: #64748b;
            margin-top: 4px;
        }
        .invoice-badge {
            text-align: right;
        }
        .invoice-title {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .order-id {
            font-size: 14px;
            font-weight: 700;
            color: #4f46e5;
            font-family: monospace;
            margin-top: 4px;
        }
        .order-date {
            font-size: 12px;
            color: #64748b;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 28px;
        }
        .info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
        }
        .info-box h4 {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 6px;
        }
        .info-box p {
            font-size: 13px;
            color: #1e293b;
            margin-bottom: 4px;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-warning { background: #fef3c7; color: #b45309; }
        .badge-info { background: #e0f2fe; color: #0369a1; }
        .badge-primary { background: #e0e7ff; color: #4338ca; }
        .badge-danger { background: #fee2e2; color: #b91c1c; }

        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        table.items-table th {
            background: #f8fafc;
            color: #475569;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 12px 14px;
            text-align: left;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }
        table.items-table td {
            padding: 14px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
            vertical-align: middle;
        }
        table.items-table .text-right {
            text-align: right;
        }
        table.items-table .text-center {
            text-align: center;
        }

        .summary-wrap {
            display: flex;
            justify-content: flex-end;
            margin-top: 8px;
        }
        .summary-box {
            width: 320px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 13px;
            color: #64748b;
        }
        .summary-total {
            border-top: 2px solid #e2e8f0;
            margin-top: 8px;
            padding-top: 10px;
            display: flex;
            justify-content: space-between;
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
        }
        .summary-total span:last-child {
            color: #4f46e5;
        }

        .footer-note {
            margin-top: 36px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: #94a3b8;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .header-actions {
                display: none;
            }
            .invoice-card {
                box-shadow: none;
                border: none;
                padding: 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

    <div class="header-actions">
        <a href="javascript:window.close()" class="btn btn-close">✕ Tutup</a>
        <button onclick="window.print()" class="btn btn-print">🖨️ Cetak / Simpan PDF (Ctrl+P)</button>
    </div>

    <div class="invoice-card">
        <!-- Top Header -->
        <div class="top-row">
            <div>
                <div class="brand-logo">RADIANT<span>CODE</span></div>
                <div class="brand-sub">Platform E-Commerce Modern • demo1-ecommerce.radiantcode.web.id</div>
            </div>
            <div class="invoice-badge">
                <div class="invoice-title">INVOICE & SURAT JALAN</div>
                <div class="order-id">#{{ $order->order_number }}</div>
                <div class="order-date">{{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB</div>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="info-grid">
            <div class="info-box">
                <h4>Penerima & Alamat Pengiriman</h4>
                <p><strong>{{ $order->shipping_name }}</strong></p>
                <p>📞 {{ $order->shipping_phone }}</p>
                <p>{{ $order->shipping_address }}</p>
                <p>{{ $order->shipping_city }} {{ $order->shipping_postal_code ? '('.$order->shipping_postal_code.')' : '' }}</p>
                @if($order->notes)
                    <div style="margin-top: 8px; padding: 6px 10px; background: #fff; border: 1px dashed #cbd5e1; border-radius: 6px; font-size: 11px; color: #475569;">
                        <strong>Catatan Pembeli:</strong> {{ $order->notes }}
                    </div>
                @endif
            </div>

            <div class="info-box">
                <h4>Informasi Status & Pembayaran</h4>
                <p>Status Pesanan: 
                    @php
                        $statusClass = match($order->status) {
                            'paid' => 'badge-success',
                            'delivered' => 'badge-success',
                            'shipped' => 'badge-primary',
                            'processing' => 'badge-info',
                            'pending' => 'badge-warning',
                            'cancelled' => 'badge-danger',
                            default => 'badge-info'
                        };
                    @endphp
                    <span class="badge {{ $statusClass }}">{{ strtoupper($order->status) }}</span>
                </p>
                <p>Metode Bayar: <strong>{{ strtoupper($order->payment?->payment_type ?? 'Online (Midtrans)') }}</strong></p>
                <p>Status Payment: <strong style="color: #10b981;">{{ strtoupper($order->payment?->status ?? ($order->status === 'paid' ? 'SETTLEMENT' : 'PENDING')) }}</strong></p>
                @if($order->payment?->midtrans_transaction_id)
                    <p style="font-size: 11px; color: #64748b;">Trx ID: <code>{{ $order->payment->midtrans_transaction_id }}</code></p>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 40px;" class="text-center">No</th>
                    <th>Nama Produk</th>
                    <th class="text-right">Harga Satuan</th>
                    <th class="text-center" style="width: 70px;">Jumlah</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->items as $idx => $item)
                    <tr>
                        <td class="text-center" style="color: #94a3b8;">{{ $idx + 1 }}</td>
                        <td>
                            <strong style="color: #0f172a;">{{ $item->product_name }}</strong>
                            @if($item->variant_name)
                                <span style="display: block; font-size: 11px; color: #4f46e5; font-weight: 600;">Varian: {{ $item->variant_name }}</span>
                            @endif
                        </td>
                        <td class="text-right">Rp {{ number_format($item->product_price, 0, ',', '.') }}</td>
                        <td class="text-center" style="font-weight: 700;">{{ $item->quantity }}</td>
                        <td class="text-right" style="font-weight: 700; color: #0f172a;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center" style="padding: 24px; color: #94a3b8;">Tidak ada data barang belanjaan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Summary Calculation -->
        <div class="summary-wrap">
            <div class="summary-box">
                <div class="summary-row">
                    <span>Subtotal Produk</span>
                    <span>Rp {{ number_format($order->items->sum('subtotal'), 0, ',', '.') }}</span>
                </div>
                <div class="summary-row">
                    <span>Ongkos Kirim</span>
                    <span>Gratis / Termasuk</span>
                </div>
                <div class="summary-total">
                    <span>Total Pembayaran</span>
                    <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="footer-note">
            <div>Dokumen ini dicetak otomatis oleh sistem RadiantCode E-Commerce.</div>
            <div>Waktu Cetak: {{ now()->translatedFormat('d M Y, H:i') }} WIB</div>
        </div>
    </div>

</body>
</html>
