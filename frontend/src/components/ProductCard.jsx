import React from 'react';
import { Link } from 'react-router-dom';
import { Heart, Star, ShoppingCart } from 'lucide-react';
import { formatCurrency } from '@shared/utils/formatCurrency';
import { useCart } from '@shared/context/CartContext';
import { useWishlist } from '@shared/context/WishlistContext';
import { useLanguage } from '@shared/context/LanguageContext';
import { useToast } from './Toast';

const ProductCard = ({ product }) => {
  const { addToCart, setIsDrawerOpen } = useCart();
  const { isWishlisted, toggleWishlist } = useWishlist();
  const { t } = useLanguage();
  const { addToast } = useToast();

  const wishlisted = isWishlisted(product.id);
  const primaryImg = product.primary_image?.image_path || product.images?.[0]?.image_path || 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=80';

  const handleQuickAdd = async (e) => {
    e.preventDefault();
    e.stopPropagation();
    try {
      await addToCart(product.id, 1);
      addToast({
        title: t('card_add_cart'),
        message: `${product.name} telah ditambahkan ke keranjang belanja.`,
        type: 'success',
        action: {
          label: 'Buka Keranjang &rarr;',
          onClick: () => setIsDrawerOpen(true),
        },
      });
    } catch (err) {
      addToast({
        title: 'Gagal Menambahkan',
        message: err.message || 'Terjadi kesalahan.',
        type: 'error',
      });
    }
  };

  const handleWishlistClick = (e) => {
    e.preventDefault();
    e.stopPropagation();
    toggleWishlist(product.id);
    addToast({
      title: wishlisted ? 'Dihapus dari Wishlist' : 'Disimpan ke Wishlist',
      message: `${product.name} ${wishlisted ? 'dihapus dari' : 'ditambahkan ke'} koleksi wishlist Anda.`,
      type: 'info',
    });
  };

  const discountPercent = product.discount_percent || (product.sale_price ? Math.round(((product.price - product.sale_price) / product.price) * 100) : null);

  return (
    <div className="product-card group relative bg-white border border-slate-200/90 rounded-2xl overflow-hidden flex flex-col cursor-pointer">
      {/* Image Container */}
      <Link to={`/products/${product.slug}`} className="relative block aspect-square bg-slate-50 overflow-hidden">
        <img
          src={primaryImg}
          alt={product.name}
          referrerPolicy="no-referrer"
          onError={(e) => {
            if (e.currentTarget.src !== 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=80') {
              e.currentTarget.src = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=80';
            }
          }}
          className="product-img w-full h-full object-cover object-center"
          loading="lazy"
        />

        {/* Hover Subtle Dark Overlay for Contrast */}
        <div className="product-overlay-glow absolute inset-0 bg-slate-950/15 opacity-0 transition-opacity duration-300 pointer-events-none" />

        {/* Badges */}
        <div className="absolute top-3 left-3 flex flex-col gap-1.5 z-10">
          {discountPercent && (
            <span className="px-2.5 py-1 bg-emerald-800 text-white text-[10px] font-bold rounded-lg tracking-wider shadow-sm">
              {t('card_save')} {discountPercent}%
            </span>
          )}
          {product.is_featured && !discountPercent && (
            <span className="px-2.5 py-1 bg-slate-900 text-white text-[10px] font-bold rounded-lg tracking-wider shadow-sm">
              {t('card_featured')}
            </span>
          )}
        </div>

        {/* Wishlist Button with rich micro-animation */}
        <button
          onClick={handleWishlistClick}
          className="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/95 backdrop-blur-md flex items-center justify-center text-slate-700 hover:text-red-500 shadow-md transition-all hover:scale-115 active:scale-90 z-10 hover:bg-white"
          title={wishlisted ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist'}
        >
          <Heart
            size={15}
            className={wishlisted ? 'fill-red-500 text-red-500 animate-pop' : 'text-slate-600'}
            strokeWidth={2.2}
          />
        </button>

        {/* Quick Add Overlay on desktop hover */}
        <div className="product-quick-add absolute inset-x-3 bottom-3 hidden sm:block z-10">
          <button
            onClick={handleQuickAdd}
            className="w-full py-2.5 px-3 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl shadow-xl flex items-center justify-center gap-2 backdrop-blur-sm transition-all active:scale-95 hover:bg-emerald-900"
          >
            <ShoppingCart size={14} />
            <span>{t('card_add_cart')}</span>
          </button>
        </div>
      </Link>

      {/* Content Container */}
      <div className="p-4 sm:p-5 flex flex-col flex-1 text-left">
        {/* Category & Rating */}
        <div className="flex items-center justify-between text-xs text-slate-500 mb-2">
          <span className="truncate uppercase tracking-wider text-[10px] font-bold text-slate-400">
            {product.category?.name || 'Umum'}
          </span>
          <div className="flex items-center gap-1 text-slate-700 bg-slate-50 px-2 py-0.5 rounded-lg border border-slate-100 shadow-2xs">
            <Star size={11} className="fill-amber-400 text-amber-400" />
            <span className="font-bold text-[11px]">
              {Number(product.avg_rating || 5.0).toFixed(1)}
            </span>
          </div>
        </div>

        {/* Product Title */}
        <h3 className="text-xs sm:text-sm font-bold text-slate-900 line-clamp-2 leading-snug mb-3 group-hover:text-emerald-800 transition-colors">
          <Link to={`/products/${product.slug}`}>
            {product.name}
          </Link>
        </h3>

        {/* Price Row */}
        <div className="mt-auto pt-2 flex items-baseline gap-2">
          <span className="text-sm sm:text-base font-extrabold text-slate-900 tracking-tight">
            {formatCurrency(product.effective_price || product.sale_price || product.price)}
          </span>
          {(product.sale_price || product.discount_percent) && (
            <span className="text-[11px] text-slate-400 line-through font-medium">
              {formatCurrency(product.price)}
            </span>
          )}
        </div>

        {/* Mobile Quick Add Button */}
        <button
          onClick={handleQuickAdd}
          className="mt-3 sm:hidden w-full py-2.5 px-3 bg-slate-900 active:bg-slate-800 text-white text-xs font-bold rounded-xl flex items-center justify-center gap-1.5 shadow-xs"
        >
          <ShoppingCart size={13} />
          <span>{t('card_quick_add')}</span>
        </button>
      </div>
    </div>
  );
};

export default ProductCard;
