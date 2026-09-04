import React from 'react';
import { Link } from 'react-router-dom';
import { Heart, ShoppingCart, ArrowRight } from 'lucide-react';
import { useWishlist } from '@shared/context/WishlistContext';
import { useLanguage } from '@shared/context/LanguageContext';
import ProductCard from '../components/ProductCard';

const WishlistPage = () => {
  const { t } = useLanguage();
  const { wishlistItems, isLoading } = useWishlist();

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 text-left animate-fade-in">
      <div className="border-b border-slate-200 pb-6 mb-8">
        <h1 className="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{t('wish_title')}</h1>
        <p className="text-xs sm:text-sm text-slate-500 mt-1">
          {t('wish_subtitle')}
        </p>
      </div>

      {isLoading ? (
        <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
          {[1, 2, 3, 4].map((n) => (
            <div key={n} className="animate-shimmer rounded-3xl p-4 h-80" />
          ))}
        </div>
      ) : wishlistItems.length === 0 ? (
        <div className="bg-white border border-slate-200 rounded-3xl p-12 text-center max-w-md mx-auto shadow-xs">
          <div className="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 mx-auto mb-3 shadow-inner">
            <Heart size={26} />
          </div>
          <h3 className="text-base font-bold text-slate-900">{t('wish_empty_title')}</h3>
          <p className="text-xs text-slate-500 mt-1 mb-6">
            {t('wish_empty_desc')}
          </p>
          <Link
            to="/products"
            className="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-all shadow-md active:scale-95"
          >
            <span>{t('wish_explore_btn')}</span>
            <ArrowRight size={14} />
          </Link>
        </div>
      ) : (
        <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
          {wishlistItems.map((item) => (
            <ProductCard key={item.id} product={item.product} />
          ))}
        </div>
      )}
    </div>
  );
};

export default WishlistPage;
