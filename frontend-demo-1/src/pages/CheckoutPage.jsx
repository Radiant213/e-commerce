import React, { useState, useEffect } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { ShieldCheck, Truck, CreditCard, Lock, CheckCircle2, ArrowRight, MapPin, Plus, Check } from 'lucide-react';
import { useCart } from '@shared/context/CartContext';
import { useAuth } from '@shared/context/AuthContext';
import { useLanguage } from '@shared/context/LanguageContext';
import { formatCurrency } from '@shared/utils/formatCurrency';
import ordersApi from '@shared/api/orders';
import addressesApi from '@shared/api/addresses';
import { payWithSnap } from '@shared/utils/midtransSnap';
import { useToast } from '../components/Toast';

const CheckoutPage = () => {
  const { t } = useLanguage();
  const navigate = useNavigate();
  const { items, total, clearCart } = useCart();
  const { user, isAuthenticated } = useAuth();
  const { addToast } = useToast();

  const [savedAddresses, setSavedAddresses] = useState([]);
  const [selectedAddressId, setSelectedAddressId] = useState(null);
  const [isManualAddress, setIsManualAddress] = useState(false);

  const [formData, setFormData] = useState({
    shipping_name: user?.name || '',
    shipping_phone: user?.phone || '',
    shipping_address: user?.address || '',
    shipping_city: 'Jakarta Selatan',
    shipping_postal_code: '12190',
    notes: '',
  });

  const [shippingOption, setShippingOption] = useState('regular');
  const [isProcessing, setIsProcessing] = useState(false);
  const [errorMessage, setErrorMessage] = useState('');

  // Fetch saved addresses
  useEffect(() => {
    if (isAuthenticated) {
      addressesApi.getAddresses().then((addrs) => {
        setSavedAddresses(addrs || []);
        if (addrs && addrs.length > 0) {
          const primary = addrs.find((a) => a.is_primary) || addrs[0];
          setSelectedAddressId(primary.id);
          setFormData({
            shipping_name: primary.recipient_name,
            shipping_phone: primary.phone,
            shipping_address: primary.address_line,
            shipping_city: primary.city,
            shipping_postal_code: primary.postal_code,
            notes: primary.notes || '',
          });
        }
      });
    }
  }, [isAuthenticated]);

  const handleSelectAddress = (addr) => {
    setSelectedAddressId(addr.id);
    setIsManualAddress(false);
    setFormData({
      shipping_name: addr.recipient_name,
      shipping_phone: addr.phone,
      shipping_address: addr.address_line,
      shipping_city: addr.city,
      shipping_postal_code: addr.postal_code,
      notes: addr.notes || '',
    });
  };

  if (!isAuthenticated) {
    return (
      <div className="max-w-md mx-auto px-4 py-20 text-center animate-fade-in">
        <h2 className="text-xl font-bold text-slate-900 mb-2">Silakan Masuk Terlebih Dahulu</h2>
        <p className="text-xs text-slate-500 mb-6">
          Anda perlu login ke akun Anda untuk menyelesaikan proses pemesanan.
        </p>
        <Link
          to="/login?redirect=/checkout"
          className="px-6 py-3 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-all shadow-md active:scale-95 inline-block"
        >
          Masuk ke Akun
        </Link>
      </div>
    );
  }

  if (items.length === 0) {
    return (
      <div className="max-w-md mx-auto px-4 py-20 text-center animate-fade-in">
        <h2 className="text-xl font-bold text-slate-900 mb-2">Keranjang Belanja Kosong</h2>
        <p className="text-xs text-slate-500 mb-6">
          Tambahkan beberapa produk ke keranjang belanja Anda sebelum checkout.
        </p>
        <Link
          to="/products"
          className="px-6 py-3 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-all shadow-md active:scale-95 inline-block"
        >
          Mulai Belanja
        </Link>
      </div>
    );
  }

  // Shipping calculation
  const isFreeShippingEligible = total >= 500000;
  const shippingCost = isFreeShippingEligible
    ? (shippingOption === 'express' ? 25000 : 0)
    : (shippingOption === 'express' ? 40000 : 20000);

  const grandTotal = total + shippingCost;

  const handleInputChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleCheckoutSubmit = async (e) => {
    e.preventDefault();
    setIsProcessing(true);
    setErrorMessage('');

    try {
      const result = await ordersApi.createOrder({
        ...formData,
        shipping_cost: shippingCost,
      });

      const { snap_token, order } = result;

      await payWithSnap(snap_token, {
        onSuccess: (paymentResult) => {
          clearCart();
          addToast({
            title: 'Pembayaran Sukses!',
            message: `Pesanan #${order.order_number} telah lunas dan sedang disiapkan.`,
            type: 'success',
          });
          navigate(`/dashboard?tab=orders&success=1&order_number=${order.order_number}`);
        },
        onPending: (paymentResult) => {
          clearCart();
          navigate(`/dashboard?tab=orders&pending=1&order_number=${order.order_number}`);
        },
        onError: (err) => {
          alert('Pembayaran dibatalkan.');
          navigate('/dashboard?tab=orders');
        },
        onClose: () => {
          navigate('/dashboard?tab=orders');
        },
      });
    } catch (err) {
      console.error('Checkout error:', err);
      setErrorMessage(err.message || 'Terjadi kesalahan saat memproses pesanan.');
    } finally {
      setIsProcessing(false);
    }
  };

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 text-left animate-fade-in">
      <div className="border-b border-slate-200 pb-6 mb-8">
        <h1 className="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
          {t('chk_title')}
        </h1>
        <p className="text-xs sm:text-sm text-slate-500 mt-1">
          {t('chk_subtitle')}
        </p>
      </div>

      {errorMessage && (
        <div className="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl">
          {errorMessage}
        </div>
      )}

      <form onSubmit={handleCheckoutSubmit} className="grid grid-cols-1 lg:grid-cols-12 gap-10">
        {/* Left Column: Saved Addresses + Form + Couriers */}
        <div className="lg:col-span-7 space-y-8">
          {/* Section 1: Saved Addresses Book Selector */}
          <div className="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-7 shadow-xs space-y-4">
            <div className="flex items-center justify-between">
              <h3 className="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <MapPin size={16} />
                {t('chk_step_address')}
              </h3>
              <Link
                to="/dashboard?tab=addresses"
                className="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs active:scale-95"
              >
                <MapPin size={13} />
                <span>{t('chk_manage_addresses')}</span>
              </Link>
            </div>

            {savedAddresses.length > 0 ? (
              <div className="space-y-3">
                {savedAddresses.map((addr) => {
                  const isSelected = selectedAddressId === addr.id && !isManualAddress;
                  return (
                    <div
                      key={addr.id}
                      onClick={() => handleSelectAddress(addr)}
                      className={`p-4 rounded-2xl border-2 cursor-pointer transition-all ${
                        isSelected
                          ? 'border-emerald-700 bg-emerald-50/20 shadow-xs'
                          : 'border-slate-200 hover:border-slate-300'
                      }`}
                    >
                      <div className="flex items-start justify-between gap-3">
                        <div className="flex items-center gap-2">
                          <span className="px-2 py-0.5 bg-slate-100 text-slate-800 text-[11px] font-bold rounded-md">
                            {addr.label}
                          </span>
                          {addr.is_primary && (
                            <span className="px-2 py-0.5 bg-emerald-700 text-white text-[10px] font-bold rounded-md">
                              {t('dash_addr_primary')}
                            </span>
                          )}
                        </div>
                        <div
                          className={`w-5 h-5 rounded-full border flex items-center justify-center transition-colors ${
                            isSelected ? 'bg-emerald-700 border-emerald-700 text-white' : 'border-slate-300'
                          }`}
                        >
                          {isSelected && <Check size={12} strokeWidth={3} />}
                        </div>
                      </div>

                      <div className="mt-2 text-xs text-slate-700 space-y-0.5">
                        <p className="font-bold text-slate-900">{addr.recipient_name} ({addr.phone})</p>
                        <p className="text-slate-600">{addr.address_line}, {addr.city}, {addr.postal_code}</p>
                      </div>
                    </div>
                  );
                })}

                <button
                  type="button"
                  onClick={() => setIsManualAddress(true)}
                  className={`w-full py-2.5 px-4 rounded-xl border text-xs font-bold transition-all text-center ${
                    isManualAddress
                      ? 'border-slate-900 bg-slate-50 text-slate-900'
                      : 'border-dashed border-slate-300 text-slate-600 hover:border-slate-500'
                  }`}
                >
                  {t('chk_use_manual')}
                </button>
              </div>
            ) : null}

            {/* Manual / Editing Form */}
            {(isManualAddress || savedAddresses.length === 0) && (
              <div className="pt-4 border-t border-slate-100 space-y-4">
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label className="block text-xs font-bold text-slate-700 mb-1">{t('chk_recipient_name')}</label>
                    <input
                      type="text"
                      name="shipping_name"
                      value={formData.shipping_name}
                      onChange={handleInputChange}
                      required
                      className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:border-slate-900 transition-colors"
                    />
                  </div>

                  <div>
                    <label className="block text-xs font-bold text-slate-700 mb-1">{t('chk_phone')}</label>
                    <input
                      type="tel"
                      name="shipping_phone"
                      value={formData.shipping_phone}
                      onChange={handleInputChange}
                      required
                      className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:border-slate-900 transition-colors"
                    />
                  </div>
                </div>

                <div>
                  <label className="block text-xs font-bold text-slate-700 mb-1">{t('chk_address_line')}</label>
                  <textarea
                    name="shipping_address"
                    rows={3}
                    value={formData.shipping_address}
                    onChange={handleInputChange}
                    required
                    placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan..."
                    className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:border-slate-900 transition-colors"
                  />
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label className="block text-xs font-bold text-slate-700 mb-1">{t('chk_city')}</label>
                    <input
                      type="text"
                      name="shipping_city"
                      value={formData.shipping_city}
                      onChange={handleInputChange}
                      required
                      className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:border-slate-900 transition-colors"
                    />
                  </div>

                  <div>
                    <label className="block text-xs font-bold text-slate-700 mb-1">{t('chk_postal')}</label>
                    <input
                      type="text"
                      name="shipping_postal_code"
                      value={formData.shipping_postal_code}
                      onChange={handleInputChange}
                      required
                      className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:border-slate-900 transition-colors"
                    />
                  </div>
                </div>

                <div>
                  <label className="block text-xs font-bold text-slate-700 mb-1">{t('chk_notes')}</label>
                  <input
                    type="text"
                    name="notes"
                    value={formData.notes}
                    onChange={handleInputChange}
                    placeholder="Titipkan di pos satpam..."
                    className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:border-slate-900 transition-colors"
                  />
                </div>
              </div>
            )}
          </div>

          {/* Section 2: Courier Selector */}
          <div className="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-7 shadow-xs space-y-4">
            <h3 className="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
              <Truck size={16} />
              {t('chk_step_courier')}
            </h3>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <label
                className={`p-4 border-2 rounded-2xl cursor-pointer flex flex-col justify-between transition-all ${
                  shippingOption === 'regular' ? 'border-slate-900 bg-slate-50/80 shadow-xs' : 'border-slate-200 hover:border-slate-300'
                }`}
              >
                <div className="flex items-start justify-between">
                  <div>
                    <span className="font-extrabold text-xs text-slate-900 block">{t('chk_courier_reg')}</span>
                    <span className="text-[11px] text-slate-500">JNE / J&T / SiCepat</span>
                  </div>
                  <input
                    type="radio"
                    name="shipping_option"
                    checked={shippingOption === 'regular'}
                    onChange={() => setShippingOption('regular')}
                    className="text-slate-900 focus:ring-slate-900"
                  />
                </div>
                <span className="text-xs font-extrabold text-slate-900 mt-3">
                  {isFreeShippingEligible ? t('chk_courier_free_badge') : 'Rp 20.000'}
                </span>
              </label>

              <label
                className={`p-4 border-2 rounded-2xl cursor-pointer flex flex-col justify-between transition-all ${
                  shippingOption === 'express' ? 'border-slate-900 bg-slate-50/80 shadow-xs' : 'border-slate-200 hover:border-slate-300'
                }`}
              >
                <div className="flex items-start justify-between">
                  <div>
                    <span className="font-extrabold text-xs text-slate-900 block">{t('chk_courier_exp')}</span>
                    <span className="text-[11px] text-slate-500">Prioritas Kilat</span>
                  </div>
                  <input
                    type="radio"
                    name="shipping_option"
                    checked={shippingOption === 'express'}
                    onChange={() => setShippingOption('express')}
                    className="text-slate-900 focus:ring-slate-900"
                  />
                </div>
                <span className="text-xs font-extrabold text-slate-900 mt-3">
                  {isFreeShippingEligible ? 'Rp 25.000' : 'Rp 40.000'}
                </span>
              </label>
            </div>
          </div>
        </div>

        {/* Right Column: Order Summary Card */}
        <div className="lg:col-span-5 space-y-6">
          <div className="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-7 space-y-6 sticky top-24 shadow-xs">
            <h3 className="text-sm font-extrabold text-slate-900 uppercase tracking-wider pb-3 border-b border-slate-100">
              {t('chk_order_summary')} ({items.length} {t('dash_order_items')})
            </h3>

            {/* Items List */}
            <div className="divide-y divide-slate-100 max-h-64 overflow-y-auto pr-1">
              {items.map((item) => (
                <div key={item.id} className="py-3 first:pt-0 last:pb-0 flex items-center justify-between text-xs">
                  <div className="flex items-center gap-3">
                    <img
                      src={item.product?.primary_image?.image_path || 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=100&q=80'}
                      alt={item.product?.name}
                      className="w-11 h-11 rounded-xl object-cover border border-slate-100 bg-slate-50"
                    />
                    <div>
                      <p className="font-bold text-slate-900 line-clamp-1 max-w-[180px]">
                        {item.product?.name}
                      </p>
                      <p className="text-slate-400 text-[11px]">
                        {item.quantity} x {formatCurrency(item.product?.effective_price || item.product?.price)}
                      </p>
                    </div>
                  </div>
                  <span className="font-extrabold text-slate-900">
                    {formatCurrency((item.product?.effective_price || item.product?.price) * item.quantity)}
                  </span>
                </div>
              ))}
            </div>

            {/* Cost Breakdown */}
            <div className="pt-4 border-t border-slate-100 space-y-2.5 text-xs">
              <div className="flex justify-between text-slate-600">
                <span>{t('chk_subtotal')}</span>
                <span className="font-bold text-slate-900">{formatCurrency(total)}</span>
              </div>
              <div className="flex justify-between text-slate-600">
                <span>{t('chk_shipping_fee')}</span>
                <span className="font-bold text-slate-900">
                  {shippingCost === 0 ? t('chk_courier_free_badge') : formatCurrency(shippingCost)}
                </span>
              </div>
              <div className="flex justify-between text-base font-extrabold text-slate-900 pt-3 border-t border-slate-200">
                <span>{t('chk_total')}</span>
                <span className="text-emerald-800 text-lg">{formatCurrency(grandTotal)}</span>
              </div>
            </div>

            {/* Security Badge */}
            <div className="p-3.5 bg-slate-50 border border-slate-200 rounded-2xl flex items-center gap-2.5 text-xs text-slate-600">
              <ShieldCheck size={20} className="text-emerald-700 flex-shrink-0" />
              <span>{t('chk_sec_guarantee')}</span>
            </div>

            {/* Checkout Submit Button */}
            <button
              type="submit"
              disabled={isProcessing}
              className="w-full py-4 px-4 bg-slate-900 hover:bg-slate-800 disabled:bg-slate-400 text-white font-extrabold text-sm rounded-2xl flex items-center justify-center gap-2 shadow-xl transition-all active:scale-95"
            >
              <Lock size={16} />
              <span>{isProcessing ? 'Processing...' : `${t('chk_pay_btn')} (${formatCurrency(grandTotal)})`}</span>
            </button>
          </div>
        </div>
      </form>
    </div>
  );
};

export default CheckoutPage;
