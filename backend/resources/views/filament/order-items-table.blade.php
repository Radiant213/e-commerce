@php
    $order = $getRecord();
    $items = $order?->items ?? collect();
@endphp

<div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);">
    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">
                <th style="padding: 12px 16px; width: 48%;">Produk</th>
                <th style="padding: 12px 16px; text-align: right; width: 20%;">Harga Satuan</th>
                <th style="padding: 12px 16px; text-align: center; width: 12%;">Qty</th>
                <th style="padding: 12px 16px; text-align: right; width: 20%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                @php
                    $product = $item->product;
                    $thumb = $product?->thumbnail_url ?? 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=120&q=80';
                @endphp
                <tr style="border-bottom: 1px solid #f1f5f9; transition: background-color 0.15s ease;">
                    <td style="padding: 14px 16px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <img src="{{ $thumb }}" alt="{{ $item->product_name }}" style="width: 48px; height: 48px; min-width: 48px; max-width: 48px; min-height: 48px; max-height: 48px; border-radius: 8px; object-fit: cover; border: 1px solid #e2e8f0; flex-shrink: 0;" />
                            <div>
                                <span style="font-weight: 600; color: #0f172a; display: block; line-height: 1.35; font-size: 0.875rem;">{{ $item->product_name }}</span>
                                @if($product?->sku)
                                    <span style="font-size: 0.75rem; color: #94a3b8; font-family: monospace; display: inline-block; margin-top: 2px;">SKU: {{ $product->sku }}</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="padding: 14px 16px; text-align: right; color: #475569; font-weight: 500; white-space: nowrap;">
                        Rp {{ number_format($item->product_price, 0, ',', '.') }}
                    </td>
                    <td style="padding: 14px 16px; text-align: center; white-space: nowrap;">
                        <span style="display: inline-block; padding: 3px 10px; background: #f1f5f9; color: #334155; font-weight: 600; font-size: 0.75rem; border-radius: 9999px; border: 1px solid #e2e8f0;">
                            {{ $item->quantity }}x
                        </span>
                    </td>
                    <td style="padding: 14px 16px; text-align: right; font-weight: 700; color: #0f172a; white-space: nowrap;">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="padding: 24px; text-align: center; color: #94a3b8;">
                        Tidak ada item produk dalam pesanan ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="border-top: 1px solid #e2e8f0; background: #fafafa; color: #64748b; font-size: 0.825rem;">
                <td colspan="3" style="padding: 10px 16px; text-align: right; font-weight: 500;">Subtotal Produk:</td>
                <td style="padding: 10px 16px; text-align: right; font-weight: 600; color: #1e293b; white-space: nowrap;">
                    Rp {{ number_format($order?->subtotal ?? 0, 0, ',', '.') }}
                </td>
            </tr>
            <tr style="background: #fafafa; color: #64748b; font-size: 0.825rem;">
                <td colspan="3" style="padding: 8px 16px; text-align: right; font-weight: 500;">Biaya Pengiriman (Kurir):</td>
                <td style="padding: 8px 16px; text-align: right; font-weight: 600; color: #1e293b; white-space: nowrap;">
                    Rp {{ number_format($order?->shipping_cost ?? 0, 0, ',', '.') }}
                </td>
            </tr>
            <tr style="border-top: 2px solid #e2e8f0; background: #f8fafc;">
                <td colspan="3" style="padding: 12px 16px; text-align: right; font-weight: 700; color: #0f172a; font-size: 0.95rem;">Total Pembayaran:</td>
                <td style="padding: 12px 16px; text-align: right; font-weight: 800; color: #059669; font-size: 1.15rem; white-space: nowrap;">
                    Rp {{ number_format($order?->total ?? 0, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>
</div>
