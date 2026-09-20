import React, { useState, useEffect } from 'react';
import { useSearchParams, Link } from 'react-router-dom';
import { Package, Clock, CheckCircle2, AlertCircle, XCircle, ArrowRight, ExternalLink, Image as ImageIcon } from 'lucide-react';
import ordersApi from '@shared/api/orders';
import paymentsApi from '@shared/api/payments';
import { formatCurrency } from '@shared/utils/formatCurrency';
import { formatDate } from '@shared/utils/formatDate';
import { payWithSnap } from '@shared/utils/midtransSnap';
import { useAuth } from '@shared/context/AuthContext';

const OrderHistoryPage = () => {
  const [searchParams] = useSearchParams();
  const { isAuthenticated } = useAuth();
  const [orders, setOrders] = useState([]);
  const [pagination, setPagination] = useState({ current_page: 1, last_page: 1 });
  const [isLoading, setIsLoading] = useState(true);

  const isSuccessRedirect = searchParams.get('success');
  const isPendingRedirect = searchParams.get('pending');
  const orderNumberParam = searchParams.get('order_number');

  const fetchOrders = async (page = 1) => {
    setIsLoading(true);
    try {
      const res = await ordersApi.getOrders(page);
      setOrders(res.data || []);
      setPagination({ current_page: res.current_page, last_page: res.last_page });
    } catch (err) {
      console.error('Error fetching orders:', err);
    } finally {
      setIsLoading(false);
    }
  };

  useEffect(() => {
    if (isAuthenticated) {
      fetchOrders();
    }
  }, [isAuthenticated]);

  const handlePayPendingOrder = async (orderId) => {
    try {
      const res = await paymentsApi.getSnapToken(orderId);
      await payWithSnap(res.snap_token, {
        onSuccess: () => fetchOrders(),
        onPending: () => fetchOrders(),
        onError: () => fetchOrders(),
        onClose: () => fetchOrders(),
      });
    } catch (err) {
      alert(err.message || 'Gagal memproses pembayaran.');
    }
  };

  const handleConfirmDelivery = async (orderId) => {
    if (!window.confirm('Apakah Anda yakin pesanan sudah diterima dengan baik?')) return;
    try {
      await ordersApi.confirmDelivery(orderId);
      alert('Pesanan berhasil dikonfirmasi diterima!');
      fetchOrders(pagination.current_page);
    } catch (err) {
      alert(err.message || 'Gagal mengkonfirmasi pesanan.');
    }
  };

  if (!isAuthenticated) {
    return (
      <div className="max-w-md mx-auto px-4 py-20 text-center">
        <h2 className="text-xl font-bold text-slate-900 mb-2">Silakan Masuk Terlebih Dahulu</h2>
        <p className="text-xs text-slate-500 mb-6">
          Masuk ke akun Anda untuk melihat riwayat pesanan dan status transaksi.
        </p>
        <Link
          to="/login?redirect=/orders"
          className="px-6 py-3 bg-slate-900 text-white rounded-lg text-xs font-semibold hover:bg-slate-800 transition-colors"
        >
          Masuk ke Akun
        </Link>
      </div>
    );
  }

  const getStatusBadge = (status) => {
    switch (status) {
      case 'paid':
      case 'delivered':
        return (
          <span className="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-full border border-emerald-200">
            <CheckCircle2 size={12} />
            LUNAS / BERHASIL
          </span>
        );
      case 'processing':
      case 'shipped':
        return (
          <span className="inline-flex items-center gap-1 px-2.5 py-1 bg-sky-50 text-sky-700 text-xs font-semibold rounded-full border border-sky-200">
            <Clock size={12} />
            DIPROSES / DIKIRIM
          </span>
        );
      case 'pending':
        return (
          <span className="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-semibold rounded-full border border-amber-200">
            <AlertCircle size={12} />
            MENUNGGU PEMBAYARAN
          </span>
        );
      case 'cancelled':
        return (
          <span className="inline-flex items-center gap-1 px-2.5 py-1 bg-red-50 text-red-700 text-xs font-semibold rounded-full border border-red-200">
            <XCircle size={12} />
            DIBATALKAN
          </span>
        );
      default:
        return <span className="text-xs text-slate-500">{status}</span>;
    }
  };

  return (
    <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 text-left space-y-8">
      {/* Header */}
      <div className="border-b border-slate-200 pb-6">
        <h1 className="text-2xl sm:text-3xl font-bold text-slate-900">Riwayat Pesanan Saya</h1>
        <p className="text-xs sm:text-sm text-slate-500 mt-1">
          Pantau status pengiriman dan riwayat pembayaran transaksi Anda.
        </p>
      </div>

      {/* Success Notification Alert */}
      {isSuccessRedirect && (
        <div className="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-start gap-3">
          <CheckCircle2 size={20} className="text-emerald-700 mt-0.5 flex-shrink-0" />
          <div className="text-xs text-emerald-800">
            <p className="font-bold text-sm">Pembayaran Berhasil Dikonfirmasi!</p>
            <p className="mt-0.5">
              Pesanan #{orderNumberParam} telah lunas dan sedang kami siapkan untuk segera dikirim.
            </p>
          </div>
        </div>
      )}

      {/* Orders List */}
      {isLoading ? (
        <div className="space-y-4">
          {[1, 2, 3].map((n) => (
            <div key={n} className="animate-pulse bg-white border border-slate-200 rounded-xl p-6 h-48" />
          ))}
        </div>
      ) : orders.length === 0 ? (
        <div className="bg-white border border-slate-200 rounded-xl p-12 text-center max-w-md mx-auto">
          <div className="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mx-auto mb-3">
            <Package size={24} />
          </div>
          <h3 className="text-base font-semibold text-slate-900">Belum Ada Pesanan</h3>
          <p className="text-xs text-slate-500 mt-1 mb-6">
            Anda belum pernah membuat transaksi pesanan di toko kami.
          </p>
          <Link
            to="/products"
            className="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 text-white rounded-lg text-xs font-semibold hover:bg-slate-800 transition-colors"
          >
            <span>Mulai Belanja</span>
            <ArrowRight size={14} />
          </Link>
        </div>
      ) : (
        <div className="space-y-6">
          {orders.map((order) => (
            <div key={order.id} className="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-xs">
              {/* Order Card Header */}
              <div className="bg-slate-50/80 px-6 py-4 border-b border-slate-200/80 flex flex-wrap items-center justify-between gap-4 text-xs">
                <div className="flex flex-wrap items-center gap-4">
                  <div>
                    <span className="text-slate-400 block text-[10px] uppercase font-bold">No. Pesanan</span>
                    <span className="font-mono font-bold text-slate-900">{order.order_number}</span>
                  </div>
                  <div>
                    <span className="text-slate-400 block text-[10px] uppercase font-bold">Tanggal</span>
                    <span className="text-slate-700">{formatDate(order.created_at, true)}</span>
                  </div>
                  <div>
                    <span className="text-slate-400 block text-[10px] uppercase font-bold">Total Tagihan</span>
                    <span className="font-bold text-slate-900">{formatCurrency(order.total)}</span>
                  </div>
                </div>

                <div className="flex items-center gap-3">
                  {getStatusBadge(order.status)}
                </div>
              </div>

              {/* Order Items List */}
              <div className="p-6 divide-y divide-slate-100">
                {order.items?.map((item) => (
                  <div key={item.id} className="py-3 first:pt-0 last:pb-0 flex items-center justify-between text-xs">
                    <div className="flex items-center gap-3">
                      <img
                        src={item.product?.primary_image?.image_path || 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=100&q=80'}
                        alt={item.product_name}
                        className="w-12 h-12 rounded object-cover border border-slate-100 bg-slate-50"
                      />
                      <div>
                        <h4 className="font-semibold text-slate-900 line-clamp-1">{item.product_name}</h4>
                        <p className="text-slate-400 text-[11px]">
                          {item.quantity} x {formatCurrency(item.product_price)}
                        </p>
                      </div>
                    </div>
                    <span className="font-bold text-slate-900">
                      {formatCurrency(item.subtotal)}
                    </span>
                  </div>
                ))}
              </div>

              {/* Order Footer & Actions */}
              <div className="bg-slate-50/50 px-6 py-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3 text-xs">
                <div className="text-slate-500 flex flex-col gap-1">
                  <span>Penerima: <strong>{order.shipping_name}</strong> ({order.shipping_phone}) - {order.shipping_address}, {order.shipping_city}</span>
                  {(order.tracking_number || order.courier_name) && (
                    <div className="flex flex-wrap items-center gap-2">
                      <span className="text-slate-800">Resi Pengiriman: <strong>{order.courier_name || '-'}</strong> - <span className="font-mono text-emerald-700">{order.tracking_number || '-'}</span></span>
                      {order.receipt_image_url && (
                        <a
                          href={order.receipt_image_url}
                          target="_blank"
                          rel="noopener noreferrer"
                          className="inline-flex items-center gap-1 text-emerald-700 hover:text-emerald-800 font-semibold underline bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 text-[11px] transition-colors"
                          title="Buka foto resi pengiriman"
                        >
                          <ImageIcon size={12} />
                          <span>Foto Resi</span>
                        </a>
                      )}
                    </div>
                  )}
                </div>

                {order.status === 'pending' && (
                  <button
                    onClick={() => handlePayPendingOrder(order.id)}
                    className="px-4 py-2 bg-emerald-800 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition-colors flex items-center gap-1.5 shadow-sm"
                  >
                    <span>Bayar Sekarang (MidTrans)</span>
                    <ExternalLink size={13} />
                  </button>
                )}
                {order.status === 'shipped' && (
                  <button
                    onClick={() => handleConfirmDelivery(order.id)}
                    className="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs font-semibold transition-colors flex items-center gap-1.5 shadow-sm"
                  >
                    <CheckCircle2 size={13} />
                    <span>Pesanan Diterima</span>
                  </button>
                )}
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default OrderHistoryPage;
