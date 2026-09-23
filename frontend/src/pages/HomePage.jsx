import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { ArrowRight, Sparkles, CheckCircle2, Mail, Send } from 'lucide-react';
import productsApi from '@shared/api/products';
import categoriesApi from '@shared/api/categories';
import { useLanguage } from '@shared/context/LanguageContext';
import ProductCard from '../components/ProductCard';
import { useToast } from '../components/Toast';

const HomePage = () => {
  const { addToast } = useToast();
  const { t } = useLanguage();
  const [featuredProducts, setFeaturedProducts] = useState([]);
  const [bestSellers, setBestSellers] = useState([]);
  const [newArrivals, setNewArrivals] = useState([]);
  const [categories, setCategories] = useState([]);
  const [activeTab, setActiveTab] = useState('featured');
  const [isLoading, setIsLoading] = useState(true);

  // Newsletter state
  const [newsletterEmail, setNewsletterEmail] = useState('');
  const [isSubscribed, setIsSubscribed] = useState(false);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const [featured, sellers, arrivals, cats] = await Promise.all([
          productsApi.getFeaturedProducts(),
          productsApi.getBestSellers(),
          productsApi.getNewArrivals(),
          categoriesApi.getCategories(),
        ]);
        setFeaturedProducts(featured || []);
        setBestSellers(sellers || []);
        setNewArrivals(arrivals || []);
        setCategories(cats || []);
      } catch (err) {
        console.error('Error loading homepage data:', err);
      } finally {
        setIsLoading(false);
      }
    };

    fetchData();
  }, []);

  const handleNewsletterSubmit = (e) => {
    e.preventDefault();
    if (newsletterEmail) {
      setIsSubscribed(true);
      addToast({
        title: 'Berhasil Berlangganan!',
        message: `Terima kasih! Kode voucher diskon 10% telah dikirim ke ${newsletterEmail}`,
        type: 'success',
      });
      setNewsletterEmail('');
    }
  };

  const getDisplayedProducts = () => {
    switch (activeTab) {
      case 'bestsellers': return bestSellers;
      case 'new': return newArrivals;
      default: return featuredProducts;
    }
  };

  return (
    <div className="space-y-16 lg:space-y-24 pb-20 animate-fade-in">
      {/* 1. Hero Section (Ultra-clean Studio Split) */}
      <section className="relative bg-white border-b border-slate-200/80 overflow-hidden">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-24">
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            {/* Left Content */}
            <div className="lg:col-span-7 space-y-6 text-left">
              <div className="inline-flex items-center gap-2 px-3.5 py-1.5 bg-slate-100/90 text-slate-800 text-xs font-bold rounded-full tracking-wide border border-slate-200/60 shadow-xs">
                <Sparkles size={13} className="text-emerald-700" />
                <span>{t('hero_badge')}</span>
              </div>
              <h1 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 leading-[1.1]">
                {t('hero_title')}
              </h1>
              <p className="text-base sm:text-lg text-slate-600 max-w-xl leading-relaxed">
                {t('hero_desc')}
              </p>
              <div className="flex flex-wrap items-center gap-4 pt-2">
                <Link
                  to="/products"
                  className="px-7 py-3.5 bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-bold rounded-xl shadow-md flex items-center gap-2 transition-all hover:gap-3 active:scale-95"
                >
                  <span>{t('hero_explore')}</span>
                  <ArrowRight size={16} />
                </Link>
                <Link
                  to="/products?is_featured=1"
                  className="px-7 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs sm:text-sm font-bold rounded-xl transition-all active:scale-95"
                >
                  {t('hero_featured')}
                </Link>
              </div>

              {/* Quick stats tags */}
              <div className="pt-8 border-t border-slate-100 grid grid-cols-3 gap-3 sm:gap-6 text-slate-700">
                <div>
                  <p className="text-2xl sm:text-3xl font-extrabold text-slate-900">500+</p>
                  <p className="text-xs text-slate-500 mt-0.5 font-medium">{t('hero_stat_curated')}</p>
                </div>
                <div>
                  <p className="text-2xl sm:text-3xl font-extrabold text-slate-900">99.4%</p>
                  <p className="text-xs text-slate-500 mt-0.5 font-medium">{t('hero_stat_satisfaction')}</p>
                </div>
                <div>
                  <p className="text-2xl sm:text-3xl font-extrabold text-slate-900">100%</p>
                  <p className="text-xs text-slate-500 mt-0.5 font-medium">{t('hero_stat_guarantee')}</p>
                </div>
              </div>
            </div>

            {/* Right Hero Image Card (Whole card clickable) */}
            <div className="lg:col-span-5 relative">
              <Link
                to="/products/sony-wh-1000xm5-wireless-noise-cancelling-headphones"
                className="product-card relative aspect-[4/5] rounded-3xl overflow-hidden shadow-2xl bg-slate-100 border border-slate-200 group block cursor-pointer"
              >
                <img
                  src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=1000&q=80"
                  alt="Sony WH-1000XM5 Studio Shot"
                  className="product-img w-full h-full object-cover object-center"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/25 to-transparent transition-opacity group-hover:opacity-90" />
                <div className="absolute bottom-8 left-8 right-8 text-white text-left space-y-2">
                  <span className="text-[10px] uppercase tracking-widest text-emerald-300 font-bold bg-emerald-950/80 backdrop-blur-xs px-3 py-1 rounded-lg inline-block border border-emerald-500/30 shadow-xs">
                    {t('hero_spotlight')}
                  </span>
                  <h3 className="text-xl sm:text-2xl font-extrabold tracking-tight group-hover:text-emerald-300 transition-colors">
                    {t('hero_spotlight_title')}
                  </h3>
                  <p className="text-xs text-slate-300">{t('hero_spotlight_desc')}</p>
                  <div className="inline-flex items-center gap-1.5 text-xs font-extrabold text-emerald-300 group-hover:text-emerald-200 group-hover:translate-x-1 transition-all pt-1">
                    <span>{t('hero_buy_now')}</span>
                    <ArrowRight size={14} />
                  </div>
                </div>
              </Link>
            </div>
          </div>
        </div>
      </section>

      {/* 2. Featured Categories Grid */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex flex-col sm:flex-row sm:items-end justify-between mb-8 text-left">
          <div>
            <span className="text-[11px] uppercase tracking-widest text-slate-400 font-bold">
              {t('cat_curated')}
            </span>
            <h2 className="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">
              {t('cat_title')}
            </h2>
          </div>
          <Link
            to="/products"
            className="text-xs font-bold text-slate-900 hover:text-emerald-800 inline-flex items-center gap-1.5 mt-2 sm:mt-0"
          >
            {t('cat_view_all')} <ArrowRight size={14} />
          </Link>
        </div>

        <div className="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
          {categories.slice(0, 4).map((cat) => (
            <Link
              key={cat.id}
              to={`/products?category_id=${cat.id}`}
              className="product-card group relative rounded-2xl overflow-hidden aspect-[4/5] bg-slate-100 border border-slate-200/90 block text-left shadow-xs hover:shadow-md transition-all cursor-pointer"
            >
              <img
                src={cat.image}
                alt={cat.name}
                className="product-img w-full h-full object-cover"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent" />
              <div className="absolute bottom-5 left-5 right-5 text-white">
                <h3 className="text-base sm:text-lg font-bold leading-tight group-hover:text-emerald-300 transition-colors">
                  {cat.name}
                </h3>
                <p className="text-[11px] text-slate-300 mt-0.5">
                  {cat.active_products_count || 0} {t('cat_items_count')}
                </p>
              </div>
            </Link>
          ))}
        </div>
      </section>

      {/* 3. Interactive Product Showcase (Tabs: Featured / Best Sellers / New Arrivals) */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex flex-col sm:flex-row sm:items-end justify-between mb-8 text-left border-b border-slate-200 pb-4 gap-4">
          <div>
            <span className="text-[11px] uppercase tracking-widest text-slate-400 font-bold">
              {t('showcase_badge')}
            </span>
            <h2 className="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">
              {t('showcase_title')}
            </h2>
          </div>

          {/* Interactive Filter Pills */}
          <div className="flex items-center gap-1.5 bg-slate-100 p-1 rounded-xl">
            <button
              onClick={() => setActiveTab('featured')}
              className={`px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all ${
                activeTab === 'featured' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'
              }`}
            >
              {t('tab_featured')}
            </button>
            <button
              onClick={() => setActiveTab('bestsellers')}
              className={`px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all ${
                activeTab === 'bestsellers' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'
              }`}
            >
              {t('tab_bestsellers')}
            </button>
            <button
              onClick={() => setActiveTab('new')}
              className={`px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all ${
                activeTab === 'new' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'
              }`}
            >
              {t('tab_new')}
            </button>
          </div>
        </div>

        {isLoading ? (
          <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
            {[1, 2, 3, 4, 5, 6, 7, 8].map((n) => (
              <div key={n} className="animate-shimmer rounded-2xl p-4 h-80" />
            ))}
          </div>
        ) : (
          <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            {getDisplayedProducts().slice(0, 8).map((product) => (
              <ProductCard key={product.id} product={product} />
            ))}
          </div>
        )}
      </section>

      {/* 4. Editorial Brand Story Banner */}
      <section className="bg-slate-950 text-white rounded-3xl mx-4 sm:mx-6 lg:px-8 overflow-hidden shadow-2xl">
        <div className="max-w-7xl mx-auto px-6 sm:px-10 lg:px-12 py-16 lg:py-20">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div className="space-y-6 text-left">
              <span className="text-xs font-bold uppercase tracking-widest text-emerald-400 bg-emerald-950/80 px-3 py-1 rounded-md inline-block">
                {t('story_badge')}
              </span>
              <h2 className="text-3xl sm:text-4xl font-extrabold tracking-tight text-white leading-tight">
                {t('story_title')}
              </h2>
              <p className="text-sm sm:text-base text-slate-300 leading-relaxed">
                {t('story_desc')}
              </p>
              <div className="space-y-3 pt-2">
                <div className="flex items-center gap-3 text-xs sm:text-sm text-slate-200">
                  <CheckCircle2 size={18} className="text-emerald-400 flex-shrink-0" />
                  <span>{t('story_point_1')}</span>
                </div>
                <div className="flex items-center gap-3 text-xs sm:text-sm text-slate-200">
                  <CheckCircle2 size={18} className="text-emerald-400 flex-shrink-0" />
                  <span>{t('story_point_2')}</span>
                </div>
                <div className="flex items-center gap-3 text-xs sm:text-sm text-slate-200">
                  <CheckCircle2 size={18} className="text-emerald-400 flex-shrink-0" />
                  <span>{t('story_point_3')}</span>
                </div>
              </div>
            </div>

            <div className="relative rounded-2xl overflow-hidden aspect-[16/10] bg-slate-900 border border-slate-800 shadow-xl">
              <img
                src="https://images.unsplash.com/photo-1517668808822-9ebb02f2a0e6?auto=format&fit=crop&w=1000&q=80"
                alt="Artisan Craftsmanship"
                className="w-full h-full object-cover"
              />
            </div>
          </div>
        </div>
      </section>

      {/* 5. Newsletter Subscription Section */}
      <section className="max-w-4xl mx-auto px-4 text-center">
        <div className="bg-gradient-to-b from-slate-50 to-white border border-slate-200/90 rounded-3xl p-8 sm:p-12 shadow-sm space-y-4">
          <div className="w-12 h-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center mx-auto shadow-md">
            <Mail size={22} />
          </div>
          <h2 className="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
            {t('news_title')}
          </h2>
          <p className="text-xs sm:text-sm text-slate-600 max-w-md mx-auto">
            {t('news_desc')}
          </p>

          <form onSubmit={handleNewsletterSubmit} className="flex flex-col sm:flex-row gap-2.5 max-w-md mx-auto pt-2">
            <input
              type="email"
              required
              value={newsletterEmail}
              onChange={(e) => setNewsletterEmail(e.target.value)}
              placeholder={t('news_placeholder')}
              className="flex-1 px-4 py-3 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-slate-900 transition-colors shadow-xs"
            />
            <button
              type="submit"
              className="px-6 py-3 bg-slate-900 hover:bg-slate-800 active:scale-95 text-white font-bold text-xs sm:text-sm rounded-xl transition-all shadow-md flex items-center justify-center gap-2"
            >
              <span>{t('news_btn')}</span>
              <Send size={14} />
            </button>
          </form>
        </div>
      </section>
    </div>
  );
};

export default HomePage;
