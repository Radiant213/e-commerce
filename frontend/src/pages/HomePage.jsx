import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { ArrowRight, Sparkles, CheckCircle2, Mail, Send } from 'lucide-react';
import productsApi from '@shared/api/products';
import categoriesApi from '@shared/api/categories';
import { useLanguage } from '@shared/context/LanguageContext';
import { useCms } from '@shared/context/CmsContext';
import ProductCard from '../components/ProductCard';
import { useToast } from '../components/Toast';

const HeroSlider = ({ heroBanners, t }) => {
  const [currentIndex, setCurrentIndex] = useState(0);

  useEffect(() => {
    if (!heroBanners || heroBanners.length <= 1) return;
    const interval = setInterval(() => {
      setCurrentIndex((prev) => (prev + 1) % heroBanners.length);
    }, 5000);
    return () => clearInterval(interval);
  }, [heroBanners]);

  if (!heroBanners || heroBanners.length === 0) {
    // Fallback to Sony Spotlight card
    return (
      <Link
        to="/products/sony-wh-1000xm5-wireless-noise-cancelling-headphones"
        className="product-card relative aspect-[4/5] rounded-3xl overflow-hidden shadow-2xl bg-slate-100 border border-slate-200 group block cursor-pointer"
      >
        <img
          src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=1000&q=80"
          alt="Product Spotlight"
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
    );
  }

  const current = heroBanners[currentIndex] || heroBanners[0];

  return (
    <div className="relative aspect-[4/5] rounded-3xl overflow-hidden shadow-2xl bg-slate-900 border border-slate-200 group">
      <Link to={current.link || '/products'} className="block w-full h-full">
        <img
          src={current.image}
          alt={current.alt_text || current.title || 'Hero Banner'}
          className="w-full h-full object-cover object-center transition-all duration-700"
        />
        <div className="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/30 to-transparent" />
        <div className="absolute bottom-8 left-8 right-8 text-white text-left space-y-2">
          {current.title && (
            <h3 className="text-xl sm:text-2xl font-extrabold tracking-tight text-white group-hover:text-emerald-300 transition-colors">
              {current.title}
            </h3>
          )}
          {current.subtitle && (
            <p className="text-xs text-slate-300 line-clamp-2">{current.subtitle}</p>
          )}
          {current.cta_text && (
            <div className="inline-flex items-center gap-1.5 text-xs font-extrabold text-emerald-300 group-hover:text-emerald-200 group-hover:translate-x-1 transition-all pt-1">
              <span>{current.cta_text}</span>
              <ArrowRight size={14} />
            </div>
          )}
        </div>
      </Link>

      {/* Slider dots indicator */}
      {heroBanners.length > 1 && (
        <div className="absolute top-4 right-4 flex items-center gap-1.5 bg-black/40 backdrop-blur-md px-2.5 py-1.5 rounded-full z-10">
          {heroBanners.map((_, dotIdx) => (
            <button
              key={dotIdx}
              type="button"
              onClick={(e) => {
                e.preventDefault();
                setCurrentIndex(dotIdx);
              }}
              className={`h-1.5 rounded-full transition-all cursor-pointer ${
                dotIdx === currentIndex ? 'w-5 bg-emerald-400' : 'w-1.5 bg-white/50 hover:bg-white'
              }`}
              aria-label={`Slide ${dotIdx + 1}`}
            />
          ))}
        </div>
      )}
    </div>
  );
};

const HomePage = () => {
  const { addToast } = useToast();
  const { t } = useLanguage();
  const { homepageSections, banners, heroBanners, promoBanners } = useCms();

  const [featuredProducts, setFeaturedProducts] = useState([]);
  const [bestSellers, setBestSellers] = useState([]);
  const [newArrivals, setNewArrivals] = useState([]);
  const [categories, setCategories] = useState([]);
  const [activeTab, setActiveTab] = useState('featured');
  const [isLoading, setIsLoading] = useState(true);

  // Newsletter state
  const [newsletterEmail, setNewsletterEmail] = useState('');

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
      addToast({
        title: 'Berhasil Berlangganan!',
        message: `Terima kasih! Kode voucher diskon 10% telah dikirim ke ${newsletterEmail}`,
        type: 'success',
      });
      setNewsletterEmail('');
    }
  };

  const getDisplayedProducts = (limit = 8) => {
    let list = featuredProducts;
    if (activeTab === 'bestsellers') list = bestSellers;
    if (activeTab === 'new') list = newArrivals;
    return list.slice(0, limit);
  };

  // Default section ordering if CMS hasn't loaded yet
  const activeSections = (homepageSections && homepageSections.length > 0)
    ? homepageSections.filter((s) => s.is_active)
    : [
        { type: 'hero', config: {} },
        { type: 'categories', config: {} },
        { type: 'product_showcase', config: {} },
        { type: 'promo_strip', config: {} },
        { type: 'brand_story', config: {} },
        { type: 'newsletter', config: {} },
      ];

  // Helper to render section by type
  const renderSection = (section, idx) => {
    const config = section.config || {};

    switch (section.type) {
      // 1. Hero Section
      case 'hero': {
        const stats = config.stats && config.stats.length > 0 ? config.stats : [
          { value: '500+', label: t('hero_stat_curated') },
          { value: '99.4%', label: t('hero_stat_satisfaction') },
          { value: '100%', label: t('hero_stat_guarantee') },
        ];

        return (
          <section key={section.id || idx} className="relative bg-white border-b border-slate-200/80 overflow-hidden">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-24">
              <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                {/* Left Content */}
                <div className="lg:col-span-7 space-y-6 text-left">
                  <div className="inline-flex items-center gap-2 px-3.5 py-1.5 bg-slate-100/90 text-slate-800 text-xs font-bold rounded-full tracking-wide border border-slate-200/60 shadow-xs">
                    <Sparkles size={13} className="text-emerald-700" />
                    <span>{config.badge || t('hero_badge')}</span>
                  </div>
                  <h1 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 leading-[1.1]">
                    {config.title || t('hero_title')}
                  </h1>
                  <p className="text-base sm:text-lg text-slate-600 max-w-xl leading-relaxed">
                    {config.subtitle || t('hero_desc')}
                  </p>
                  <div className="flex flex-wrap items-center gap-4 pt-2">
                    <Link
                      to={config.cta_primary_link || '/products'}
                      className="px-7 py-3.5 bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-bold rounded-xl shadow-md flex items-center gap-2 transition-all hover:gap-3 active:scale-95"
                    >
                      <span>{config.cta_primary_text || t('hero_explore')}</span>
                      <ArrowRight size={16} />
                    </Link>
                    <Link
                      to={config.cta_secondary_link || '/products?is_featured=1'}
                      className="px-7 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs sm:text-sm font-bold rounded-xl transition-all active:scale-95"
                    >
                      {config.cta_secondary_text || t('hero_featured')}
                    </Link>
                  </div>

                  {/* Quick stats tags */}
                  <div className="pt-8 border-t border-slate-100 grid grid-cols-3 gap-3 sm:gap-6 text-slate-700">
                    {stats.map((stat, sIdx) => (
                      <div key={sIdx}>
                        <p className="text-2xl sm:text-3xl font-extrabold text-slate-900">{stat.value}</p>
                        <p className="text-xs text-slate-500 mt-0.5 font-medium">{stat.label}</p>
                      </div>
                    ))}
                  </div>
                </div>

                {/* Right Hero Image Card / Banner Slider */}
                <div className="lg:col-span-5 relative">
                  <HeroSlider heroBanners={heroBanners} t={t} />
                </div>
              </div>
            </div>
          </section>
        );
      }

      // 2. Categories Section
      case 'categories': {
        return (
          <section key={section.id || idx} className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="flex flex-col sm:flex-row sm:items-end justify-between mb-8 text-left">
              <div>
                <span className="text-[11px] uppercase tracking-widest text-slate-400 font-bold">
                  {config.badge || t('cat_curated')}
                </span>
                <h2 className="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">
                  {config.title || t('cat_title')}
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
              {categories.slice(0, config.limit || 4).map((cat) => (
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
        );
      }

      // 3. Product Showcase Section
      case 'product_showcase': {
        const limit = config.limit ? Number(config.limit) : 8;
        const showTabs = config.show_tabs !== false;
        const isManual = config.selection_mode === 'manual' && Array.isArray(config.pinned_product_ids) && config.pinned_product_ids.length > 0;
        const manualTabLabel = config.manual_tab_label || 'Pilihan Kami';

        return (
          <section key={section.id || idx} className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="flex flex-col sm:flex-row sm:items-end justify-between mb-8 text-left border-b border-slate-200 pb-4 gap-4">
              <div>
                <span className="text-[11px] uppercase tracking-widest text-slate-400 font-bold">
                  {config.badge || t('showcase_badge')}
                </span>
                <h2 className="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">
                  {config.title || t('showcase_title')}
                </h2>
                {config.subtitle && (
                  <p className="text-xs text-slate-500 mt-1 max-w-2xl">{config.subtitle}</p>
                )}
              </div>

              {/* Interactive Filter Pills */}
              {showTabs && (
                <div className="flex items-center gap-1.5 bg-slate-100 p-1 rounded-xl">
                  <button
                    type="button"
                    onClick={() => setActiveTab('featured')}
                    className={`px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer ${
                      activeTab === 'featured' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'
                    }`}
                  >
                    {isManual ? manualTabLabel : t('tab_featured')}
                  </button>
                  <button
                    type="button"
                    onClick={() => setActiveTab('bestsellers')}
                    className={`px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer ${
                      activeTab === 'bestsellers' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'
                    }`}
                  >
                    {t('tab_bestsellers')}
                  </button>
                  <button
                    type="button"
                    onClick={() => setActiveTab('new')}
                    className={`px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer ${
                      activeTab === 'new' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'
                    }`}
                  >
                    {t('tab_new')}
                  </button>
                </div>
              )}
            </div>

            {isLoading ? (
              <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                {[1, 2, 3, 4, 5, 6, 7, 8].map((n) => (
                  <div key={n} className="animate-shimmer rounded-2xl p-4 h-80" />
                ))}
              </div>
            ) : (
              <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                {getDisplayedProducts(limit).map((product) => (
                  <ProductCard key={product.id} product={product} />
                ))}
              </div>
            )}
          </section>
        );
      }

      // 4. Promo Strip Banners Section
      case 'promo_strip': {
        const activeBanners = (promoBanners && promoBanners.length > 0) ? promoBanners : banners;
        if (!activeBanners || activeBanners.length === 0) return null;

        return (
          <section key={section.id || idx} className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              {activeBanners.slice(0, 2).map((banner) => (
                <Link
                  key={banner.id}
                  to={banner.link || '/products'}
                  className="group relative rounded-3xl overflow-hidden h-64 sm:h-72 border border-slate-200 shadow-sm block text-left"
                >
                  <img
                    src={banner.image}
                    alt={banner.alt_text || banner.title || 'Promo'}
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-black/85 via-black/40 to-transparent" />
                  <div className="absolute bottom-6 left-6 right-6 text-white space-y-2">
                    <h3 className="text-xl sm:text-2xl font-extrabold tracking-tight group-hover:text-emerald-300 transition-colors">
                      {banner.title}
                    </h3>
                    {banner.subtitle && (
                      <p className="text-xs sm:text-sm text-slate-300 max-w-sm line-clamp-2">
                        {banner.subtitle}
                      </p>
                    )}
                    {banner.cta_text && (
                      <span className="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-400 group-hover:text-emerald-300 group-hover:translate-x-1 transition-all pt-1">
                        <span>{banner.cta_text}</span>
                        <ArrowRight size={14} />
                      </span>
                    )}
                  </div>
                </Link>
              ))}
            </div>
          </section>
        );
      }

      // 5. Brand Story Section
      case 'brand_story': {
        const points = (config.points && config.points.length > 0)
          ? config.points.map((p) => (typeof p === 'string' ? p : p.text))
          : [t('story_point_1'), t('story_point_2'), t('story_point_3')];

        const brandImage = config.image || 'https://images.unsplash.com/photo-1517668808822-9ebb02f2a0e6?auto=format&fit=crop&w=1000&q=80';

        return (
          <section key={section.id || idx} className="bg-slate-950 text-white rounded-3xl mx-4 sm:mx-6 lg:mx-8 overflow-hidden shadow-2xl">
            <div className="max-w-7xl mx-auto px-6 sm:px-10 lg:px-12 py-16 lg:py-20">
              <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div className="space-y-6 text-left">
                  <span className="text-xs font-bold uppercase tracking-widest text-emerald-400 bg-emerald-950/80 px-3 py-1 rounded-md inline-block">
                    {config.badge || t('story_badge')}
                  </span>
                  <h2 className="text-3xl sm:text-4xl font-extrabold tracking-tight text-white leading-tight">
                    {config.title || t('story_title')}
                  </h2>
                  <p className="text-sm sm:text-base text-slate-300 leading-relaxed">
                    {config.subtitle || t('story_desc')}
                  </p>
                  <div className="space-y-3 pt-2">
                    {points.map((pointText, pIdx) => (
                      <div key={pIdx} className="flex items-center gap-3 text-xs sm:text-sm text-slate-200">
                        <CheckCircle2 size={18} className="text-emerald-400 flex-shrink-0" />
                        <span>{pointText}</span>
                      </div>
                    ))}
                  </div>

                  {config.cta_link && (
                    <div className="pt-2">
                      <Link
                        to={config.cta_link}
                        className="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs sm:text-sm font-semibold transition-all shadow-md shadow-emerald-900/30"
                      >
                        <span>{config.cta_text || 'Pelajari Lebih Lanjut'}</span>
                        <ArrowRight size={14} />
                      </Link>
                    </div>
                  )}
                </div>

                <div className="relative rounded-2xl overflow-hidden aspect-[16/10] bg-slate-900 border border-slate-800 shadow-xl">
                  <img
                    src={brandImage}
                    alt="Artisan Craftsmanship"
                    className="w-full h-full object-cover"
                  />
                </div>
              </div>
            </div>
          </section>
        );
      }

      // 6. Newsletter Section
      case 'newsletter': {
        return (
          <section key={section.id || idx} className="max-w-4xl mx-auto px-4 text-center">
            <div className="bg-gradient-to-b from-slate-50 to-white border border-slate-200/90 rounded-3xl p-8 sm:p-12 shadow-sm space-y-4">
              <div className="w-12 h-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center mx-auto shadow-md">
                <Mail size={22} />
              </div>
              <h2 className="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                {config.title || t('news_title')}
              </h2>
              <p className="text-xs sm:text-sm text-slate-600 max-w-md mx-auto">
                {config.subtitle || t('news_desc')}
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
                  className="px-6 py-3 bg-slate-900 hover:bg-slate-800 active:scale-95 text-white font-bold text-xs sm:text-sm rounded-xl transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer"
                >
                  <span>{config.button_text || t('news_btn')}</span>
                  <Send size={14} />
                </button>
              </form>
            </div>
          </section>
        );
      }

      default:
        return null;
    }
  };

  return (
    <div className="space-y-16 lg:space-y-24 pb-20 animate-fade-in">
      {/* Dynamic Sections from CMS */}
      {activeSections.map((section, idx) => renderSection(section, idx))}
    </div>
  );
};

export default HomePage;
