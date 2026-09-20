<x-mail::message>
# Pesanan Anda Sedang Dikirim! 🚚

Halo {{ $order->user->name }},

Kabar gembira! Pesanan Anda dengan nomor resi **{{ $order->tracking_number ?? 'Menunggu Update' }}** via **{{ $order->courier_name ?? 'Kurir' }}** sudah dalam perjalanan.

**Detail Pesanan:**
- Nomor Pesanan: **{{ $order->order_number }}**
- Total: **Rp {{ number_format($order->total, 0, ',', '.') }}**

**Alamat Pengiriman:**
{{ $order->shipping_name }} ({{ $order->shipping_phone }})
{{ $order->shipping_address }}, {{ $order->shipping_city }}
{{ $order->shipping_postal_code }}

@if(!empty($order->receipt_image_url))
**Foto Bukti Resi Pengiriman:**
Penjual telah melampirkan foto resi fisik pengiriman untuk pesanan Anda:
<x-mail::button :url="$order->receipt_image_url">
Lihat Foto Resi Pengiriman
</x-mail::button>
@endif

Silakan pantau status pengiriman melalui website resmi kurir atau cek langsung di dashboard akun Anda.

<x-mail::button :url="config('app.frontend_url') . '/orders'">
Lacak Pesanan Saya
</x-mail::button>

Terima kasih telah berbelanja di {{ config('app.name') }}!
</x-mail::message>
