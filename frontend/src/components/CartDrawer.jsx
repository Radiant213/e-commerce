import React, { useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { X, Trash2, Plus, Minus, ShoppingCart, ArrowRight, Sparkles } from 'lucide-react';
import { useCart } from '@shared/context/CartContext';
import { useLanguage } from '@shared/context/LanguageContext';
import { formatCurrency } from '@shared/utils/formatCurrency';

const CartDrawer = () => {
  const navigate = useNavigate();
  const {
    items,
    total,
    totalItems,
    isLoading,
    isDrawerOpen,
    setIsDrawerOpen,
    updateQuantity,
    removeItem,
  } = useCart();
  const { t } = useLanguage();

  // Close drawer on ESC key
  useEffect(() => {
    const handleKeyDown = (e) => {
      if (e.key === 'Escape' && isDrawerOpen) {
        setIsDrawerOpen(false);
      }
    };
    window.addEventListener('keydown', handleKeyDown);
    return () => window.removeEventListener('keydown', handleKeyDown);
  }, [isDrawerOpen, setIsDrawerOpen]);

  if (!isDrawerOpen) return null;

  const FREE_SHIPPING_THRESHOLD = 500000;
  const remainingForFreeShipping = Math.max(0, FREE_SHIPPING_THRESHOLD - total);
  const progressPercent = Math.min(100, Math.round((total / FREE_SHIPPING_THRESHOLD) * 100));

  const handleCheckoutClick = () => {
    setIsDrawerOpen(false);
    navigate('/checkout');
  };

  return (
    <div className="fixed inset-0 z-50 overflow-hidden">
      {/* Animated Dark Backdrop */}
      <div
        onClick={() => setIsDrawerOpen(false)}
        className="absolute inset-0 bg-slate-950/60 backdrop-blur-xs transition-opacity duration-300 animate-fade-in"
      />

      <div className="fixed inset-y-0 right-0 max-w-full flex pl-10">
        <div className="w-screen max-w-md bg-white shadow-2xl flex flex-col animate-slide-in-right border-l border-slate-200">
          {/* Header */}
          <div className="px-6 py-5 border-b border-slate-200 flex items-center justify-between bg-white">
            <div className="flex items-center gap-2.5">
              <div className="w-9 h-9 rounded-xl bg-slate-900 text-white flex items-center justify-center shadow-xs">
                <ShoppingCart size={18} />
              </div>
              <div className="text-left">
                <h2 className="text-sm font-extrabold text-slate-900 leading-tight">
                  {t('cart_title')}
                </h2>
                <p className="text-[11px] text-slate-400">{totalItems} {t('cart_ready_checkout')}</p>
              </div>
            </div>
            <button
              onClick={() => setIsDrawerOpen(false)}
              className="p-2 text-slate-400 hover:text-slate-900 rounded-xl hover:bg-slate-100 transition-colors"
            >
              <X size={20} />
            </button>
          </div>

          {/* Free Shipping Progress Indicator */}
          <div className="bg-slate-50 px-6 py-3.5 border-b border-slate-200/80">
            <div className="flex items-center justify-between text-xs text-slate-700 mb-1.5 font-bold">
              {remainingForFreeShipping > 0 ? (
                <span>
                  {t('cart_free_shipping_add')} <strong className="text-emerald-800">{formatCurrency(remainingForFreeShipping)}</strong> {t('cart_free_shipping_more')} <strong>{t('cart_free_shipping_bold')}</strong>!
                </span>
              ) : (
                <span className="text-emerald-700 font-extrabold flex items-center gap-1">
                  <Sparkles size={14} className="text-amber-500" />
                  {t('cart_free_shipping_success')}
                </span>
              )}
              <span className="text-slate-400 font-mono text-[11px]">{progressPercent}%</span>
            </div>
            <div className="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
              <div
                className="h-full bg-emerald-700 transition-all duration-500 ease-out rounded-full"
                style={{ width: `${progressPercent}%` }}
              />
            </div>
          </div>

          {/* Cart Items List */}
          <div className="flex-1 overflow-y-auto p-6 divide-y divide-slate-100 space-y-1">
            {items.length === 0 ? (
              <div className="h-full flex flex-col items-center justify-center text-center py-12">
                <div className="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 mb-4 shadow-inner">
                  <ShoppingCart size={28} />
                </div>
                <h3 className="text-base font-bold text-slate-900 mb-1">
                  {t('cart_empty_title')}
                </h3>
                <p className="text-xs text-slate-500 max-w-xs mb-6">
                  {t('cart_empty_desc')}
                </p>
                <button
                  onClick={() => {
                    setIsDrawerOpen(false);
                    navigate('/products');
                  }}
                  className="px-6 py-3 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-all shadow-md active:scale-95"
                >
                  {t('cart_start_shopping')}
                </button>
              </div>
            ) : (
              items.map((item) => {
                const prod = item.product;
                const img = item.variant?.image_url || prod?.primary_image?.image_path || 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=300&q=80';
                const unitPrice = item.variant?.effective_price ?? prod?.effective_price ?? prod?.sale_price ?? prod?.price;

                return (
                  <div key={item.id} className="py-4 first:pt-0 last:pb-0 flex gap-4 text-left group">
                    {/* Item Image */}
                    <img
                      src={img}
                      alt={prod?.name}
                      className="w-18 h-18 rounded-2xl object-cover bg-slate-50 border border-slate-200/80 flex-shrink-0 shadow-xs"
                    />

                    {/* Details */}
                    <div className="flex-1 flex flex-col justify-between">
                      <div>
                        <div className="flex justify-between items-start gap-2">
                          <div>
                            <h4 className="text-xs font-bold text-slate-900 line-clamp-2 leading-snug">
                              {prod?.name}
                            </h4>
                            {item.variant && (
                              <span className="inline-block mt-1 text-[10px] font-semibold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">
                                {item.variant.name}
                              </span>
                            )}
                          </div>
                          <button
                            onClick={() => removeItem(item.id)}
                            className="text-slate-400 hover:text-red-600 p-1 rounded-lg hover:bg-red-50 transition-colors"
                            title="Hapus Barang"
                          >
                            <Trash2 size={15} />
                          </button>
                        </div>
                        <p className="text-xs font-extrabold text-slate-900 mt-1">
                          {formatCurrency(unitPrice)}
                        </p>
                      </div>

                      {/* Quantity Selector */}
                      <div className="flex items-center justify-between mt-3">
                        <div className="flex items-center border border-slate-200 rounded-xl bg-slate-50">
                          <button
                            disabled={isLoading || item.quantity <= 1}
                            onClick={() => updateQuantity(item.id, item.quantity - 1)}
                            className="p-1.5 text-slate-600 hover:text-slate-900 disabled:opacity-30 active:scale-90 transition-transform"
                          >
                            <Minus size={13} />
                          </button>
                          <span className="px-3 text-xs font-extrabold text-slate-900">
                            {item.quantity}
                          </span>
                          <button
                            disabled={isLoading}
                            onClick={() => updateQuantity(item.id, item.quantity + 1)}
                            className="p-1.5 text-slate-600 hover:text-slate-900 disabled:opacity-30 active:scale-90 transition-transform"
                          >
                            <Plus size={13} />
                          </button>
                        </div>
                        <span className="text-[11px] font-bold text-slate-900">
                          {formatCurrency((prod?.effective_price || prod?.price) * item.quantity)}
                        </span>
                      </div>
                    </div>
                  </div>
                );
              })
            )}
          </div>

          {/* Footer Summary & Checkout Button */}
          {items.length > 0 && (
            <div className="border-t border-slate-200 p-6 bg-slate-50/70 space-y-4 text-left">
              <div className="flex items-center justify-between text-sm">
                <span className="text-slate-500 font-medium">{t('cart_subtotal')}</span>
                <span className="text-xl font-extrabold text-slate-900">
                  {formatCurrency(total)}
                </span>
              </div>
              <p className="text-[11px] text-slate-400 text-center">
                {t('cart_tax_note')}
              </p>
              <button
                onClick={handleCheckoutClick}
                className="w-full py-4 px-4 bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-sm rounded-2xl flex items-center justify-center gap-2 shadow-xl hover-pop-lift cursor-pointer group"
              >
                <span>{t('cart_checkout_btn')}</span>
                <ArrowRight size={16} className="group-hover:translate-x-1.5 transition-transform duration-200" />
              </button>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default CartDrawer;
