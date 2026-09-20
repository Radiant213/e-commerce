<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan ({{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}) - RadiantCode</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #f8fafc; color: #0f172a; padding: 24px; font-size: 12px; }
        .report-card { max-width: 980px; margin: 0 auto; background: #fff; border-radius: 14px; padding: 36px; border: 1px solid #e2e8f0; }
        .header-actions { max-width: 980px; margin: 0 auto 16px auto; display: flex; justify-content: space-between; }
        .btn { padding: 9px 16px; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; border: none; text-decoration: none; }
        .btn-print { background: #4f46e5; color: #fff; }
        .btn-close { background: #e2e8f0; color: #334155; }
        .report-header { display: flex; justify-content: space-between; border-bottom: 2px solid #0f172a; padding-bottom: 18px; margin-bottom: 24px; }
        .title { font-size: 20px; font-weight: 800; color: #0f172a; }
        .sub { font-size: 12px; color: #64748b; margin-top: 4px; }
        .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 24px; }
        .kpi-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; }
        .kpi-label { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; }
        .kpi-val { font-size: 17px; font-weight: 800; color: #0f172a; margin-top: 4px; white-space: nowrap; }
        .kpi-val.highlight { color: #10b981; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th { background: #f1f5f9; color: #334155; font-weight: 700; text-transform: uppercase; font-size: 11px; padding: 10px; border: 1px solid #cbd5e1; text-align: left; }
        td { padding: 10px; border: 1px solid #e2e8f0; font-size: 12px; text-align: left; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 9999px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
        .badge-paid { background: #dcfce7; color: #15803d; }
        .badge-pending { background: #fef3c7; color: #b45309; }
        .badge-other { background: #e0f2fe; color: #0369a1; }
        .sign-grid { display: grid; grid-template-columns: 1fr 1fr; margin-top: 48px; page-break-inside: avoid; }
        .sign-box { text-align: center; }
        .sign-space { height: 70px; }
        @media print {
            body { background: #fff; padding: 0; }
            .header-actions { display: none; }
            .report-card { border: none; padding: 0; max-width: 100%; }
        }
    </style>
</head>
<body>
    <div class="header-actions">
        <a href="javascript:window.close()" class="btn btn-close">✕ Tutup</a>
        <button onclick="window.print()" class="btn btn-print">🖨️ Cetak / Simpan PDF (Ctrl+P)</button>
    </div>

    <div class="report-card">
        <div class="report-header">
            <div>
                <div class="title">LAPORAN PENJUALAN & PENDAPATAN</div>
                <div class="sub">RadiantCode E-Commerce • Periode: {{ $startDate->format('d M Y') }} s/d {{ $endDate->format('d M Y') }}</div>
            </div>
            <div style="text-align: right;">
                <div style="font-weight: 700; color: #0f172a;">RADIANTCODE STORE</div>
                <div class="sub">Filter Status: {{ strtoupper($status) }}</div>
                <div class="sub">Dicetak: {{ now()->translatedFormat('d M Y, H:i') }} WIB</div>
            </div>
        </div>

        <div class="kpi-grid">
            <div class="kpi-box">
                <div class="kpi-label">Total Omset (Lunas)</div>
                <div class="kpi-val highlight">Rp&nbsp;{{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
            </div>
            <div class="kpi-box">
                <div class="kpi-label">Total Transaksi</div>
                <div class="kpi-val">{{ $summary['total_orders'] }} Pesanan</div>
            </div>
            <div class="kpi-box">
                <div class="kpi-label">Pesanan Lunas</div>
                <div class="kpi-val" style="color: #4f46e5;">{{ $summary['paid_orders_count'] }} Pesanan</div>
            </div>
            <div class="kpi-box">
                <div class="kpi-label">Total Barang Terjual</div>
                <div class="kpi-val">{{ $summary['total_items_sold'] }} Unit</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th style="width: 130px;">No. Pesanan</th>
                    <th style="width: 120px;">Waktu Masuk</th>
                    <th>Pembeli & Kontak</th>
                    <th style="width: 120px;">Metode Bayar</th>
                    <th style="width: 100px;">Status</th>
                    <th style="width: 150px; white-space: nowrap;">Total Tagihan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $idx => $order)
                    <tr>
                        <td style="color: #94a3b8;">{{ $idx + 1 }}</td>
                        <td><strong style="font-family: monospace;">{{ $order->order_number }}</strong></td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <strong>{{ $order->shipping_name }}</strong>
                            <div style="font-size: 11px; color: #64748b;">{{ $order->shipping_phone }}</div>
                        </td>
                        <td>{{ strtoupper($order->payment?->payment_type ?? 'Online') }}</td>
                        <td>
                            @php
                                $cls = match($order->status) {
                                    'paid', 'delivered' => 'badge-paid',
                                    'pending' => 'badge-pending',
                                    default => 'badge-other'
                                };
                            @endphp
                            <span class="badge {{ $cls }}">{{ strtoupper($order->status) }}</span>
                        </td>
                        <td style="white-space: nowrap; font-weight: 700;">Rp&nbsp;{{ number_format($order->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 24px; color: #94a3b8;">Tidak ada data pesanan pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="sign-grid">
            <div class="sign-box">
                <div>Dibuat Oleh,</div>
                <div class="sign-space"></div>
                <div style="font-weight: 700; text-decoration: underline;">Staf Administrasi & Keuangan</div>
            </div>
            <div class="sign-box">
                <div>Mengetahui,</div>
                <div class="sign-space"></div>
                <div style="font-weight: 700; text-decoration: underline;">Pimpinan Toko / Manager</div>
            </div>
        </div>
    </div>
</body>
</html>
