<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok & Inventaris - RadiantCode</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #f8fafc; color: #0f172a; padding: 24px; font-size: 12px; }
        .report-card { max-width: 960px; margin: 0 auto; background: #fff; border-radius: 14px; padding: 36px; border: 1px solid #e2e8f0; }
        .header-actions { max-width: 960px; margin: 0 auto 16px auto; display: flex; justify-content: space-between; }
        .btn { padding: 9px 16px; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; border: none; text-decoration: none; }
        .btn-print { background: #4f46e5; color: #fff; }
        .btn-close { background: #e2e8f0; color: #334155; }
        .report-header { display: flex; justify-content: space-between; border-bottom: 2px solid #0f172a; padding-bottom: 18px; margin-bottom: 24px; }
        .title { font-size: 20px; font-weight: 800; color: #0f172a; }
        .sub { font-size: 12px; color: #64748b; margin-top: 4px; }
        .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 24px; }
        .kpi-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; }
        .kpi-label { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; }
        .kpi-val { font-size: 18px; font-weight: 800; color: #0f172a; margin-top: 4px; }
        .kpi-val.highlight { color: #4f46e5; }
        .kpi-val.danger { color: #ef4444; }
        .kpi-val.warning { color: #f59e0b; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th { background: #f1f5f9; color: #334155; font-weight: 700; text-transform: uppercase; font-size: 11px; padding: 10px; border: 1px solid #cbd5e1; text-align: left; }
        td { padding: 10px; border: 1px solid #e2e8f0; font-size: 12px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge { padding: 3px 8px; border-radius: 9999px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
        .badge-safe { background: #dcfce7; color: #15803d; }
        .badge-warning { background: #fef3c7; color: #b45309; }
        .badge-danger { background: #fee2e2; color: #b91c1c; }
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
                <div class="title">LAPORAN STOK & INVENTARIS GUDANG</div>
                <div class="sub">RadiantCode E-Commerce • Kategori: {{ $categoryName }}</div>
            </div>
            <div style="text-align: right;">
                <div style="font-weight: 700; color: #0f172a;">RADIANTCODE WAREHOUSE</div>
                <div class="sub">Filter Status: {{ strtoupper(str_replace('_', ' ', $stockFilter)) }}</div>
                <div class="sub">Dicetak: {{ now()->translatedFormat('d M Y, H:i') }} WIB</div>
            </div>
        </div>

        <div class="kpi-grid">
            <div class="kpi-box">
                <div class="kpi-label">Total Unit Fisik</div>
                <div class="kpi-val highlight">{{ number_format($summary['total_units'], 0, ',', '.') }} Unit</div>
            </div>
            <div class="kpi-box">
                <div class="kpi-label">Estimasi Valuasi Aset</div>
                <div class="kpi-val highlight">Rp {{ number_format($summary['total_valuation'], 0, ',', '.') }}</div>
            </div>
            <div class="kpi-box">
                <div class="kpi-label">Stok Habis (Kosong)</div>
                <div class="kpi-val danger">{{ $summary['out_of_stock'] }} Produk</div>
            </div>
            <div class="kpi-box">
                <div class="kpi-label">Stok Kritis (≤ 10)</div>
                <div class="kpi-val warning">{{ $summary['low_stock'] }} Produk</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 35px;">No</th>
                    <th>SKU</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th class="text-right">Harga Satuan</th>
                    <th class="text-center">Sisa Stok</th>
                    <th class="text-right">Total Valuasi</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $idx => $prod)
                    @php
                        $itemValuation = $prod->stock * ($prod->price ?? 0);
                    @endphp
                    <tr>
                        <td class="text-center" style="color: #94a3b8;">{{ $idx + 1 }}</td>
                        <td><strong style="font-family: monospace; font-size: 11px;">{{ $prod->sku ?: '-' }}</strong></td>
                        <td>
                            <strong>{{ $prod->name }}</strong>
                            @if($prod->variants_count ?? $prod->variants->count())
                                <span style="font-size: 10px; color: #6366f1;">({{ $prod->variants->count() }} varian)</span>
                            @endif
                        </td>
                        <td>{{ $prod->category?->name ?? 'Tanpa Kategori' }}</td>
                        <td class="text-right">Rp {{ number_format($prod->price, 0, ',', '.') }}</td>
                        <td class="text-center"><strong>{{ $prod->stock }}</strong></td>
                        <td class="text-right" style="font-weight: 700;">Rp {{ number_format($itemValuation, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($prod->stock <= 0)
                                <span class="badge badge-danger">HABIS</span>
                            @elseif($prod->stock <= 10)
                                <span class="badge badge-warning">MENIPIS</span>
                            @else
                                <span class="badge badge-safe">TERSEDIA</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center" style="padding: 24px; color: #94a3b8;">Tidak ada produk yang cocok dengan filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="sign-grid">
            <div class="sign-box">
                <div>Penanggung Jawab Gudang,</div>
                <div class="sign-space"></div>
                <div style="font-weight: 700; text-decoration: underline;">Kepala Logistik & Gudang</div>
            </div>
            <div class="sign-box">
                <div>Mengetahui,</div>
                <div class="sign-space"></div>
                <div style="font-weight: 700; text-decoration: underline;">Manajemen Operasional</div>
            </div>
        </div>
    </div>
</body>
</html>
