import React from 'react';
import { Link } from 'react-router-dom';
import { ShieldCheck, Truck, RotateCcw, Headphones, Globe, Check } from 'lucide-react';
import { useLanguage } from '@shared/context/LanguageContext';

const Footer = () => {
  const { language, setLanguage, t } = useLanguage();

  return (
    <footer className="bg-slate-950 text-slate-400 text-sm border-t border-slate-800">
      {/* 4 Pillars Trust Bar */}
      <div className="border-b border-slate-800/80 py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
          <div className="flex items-center gap-4">
            <div className="w-12 h-12 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-200 shadow-sm">
              <Truck size={22} strokeWidth={1.5} className="text-emerald-400" />
            </div>
            <div className="text-left">
              <h4 className="text-white font-bold text-sm">{t('footer_pillar_shipping')}</h4>
              <p className="text-xs text-slate-500 mt-0.5">{t('footer_pillar_shipping_sub')}</p>
            </div>
          </div>

          <div className="flex items-center gap-4">
            <div className="w-12 h-12 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-200 shadow-sm">
              <ShieldCheck size={22} strokeWidth={1.5} className="text-emerald-400" />
            </div>
            <div className="text-left">
              <h4 className="text-white font-bold text-sm">{t('footer_pillar_orig')}</h4>
              <p className="text-xs text-slate-500 mt-0.5">{t('footer_pillar_orig_sub')}</p>
            </div>
          </div>

          <div className="flex items-center gap-4">
            <div className="w-12 h-12 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-200 shadow-sm">
              <RotateCcw size={22} strokeWidth={1.5} className="text-emerald-400" />
            </div>
            <div className="text-left">
              <h4 className="text-white font-bold text-sm">{t('footer_pillar_guar')}</h4>
              <p className="text-xs text-slate-500 mt-0.5">{t('footer_pillar_guar_sub')}</p>
            </div>
          </div>

          <div className="flex items-center gap-4">
            <div className="w-12 h-12 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-200 shadow-sm">
              <Headphones size={22} strokeWidth={1.5} className="text-emerald-400" />
            </div>
            <div className="text-left">
              <h4 className="text-white font-bold text-sm">{t('footer_pillar_cs')}</h4>
              <p className="text-xs text-slate-500 mt-0.5">{t('footer_pillar_cs_sub')}</p>
            </div>
          </div>
        </div>
      </div>

      {/* Main Footer Links */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
          {/* Brand Col */}
          <div className="lg:col-span-2 space-y-4 text-left">
            <div className="flex items-center gap-2.5">
              <span className="w-8 h-8 rounded-xl bg-white text-slate-950 flex items-center justify-center font-extrabold text-base shadow-sm">
                R
              </span>
              <span className="font-extrabold text-white tracking-wider text-base">RADIANT STUDIO</span>
            </div>
            <p className="text-xs leading-relaxed max-w-sm text-slate-400">
              {t('footer_about_desc')}
            </p>
            <div className="pt-2 text-xs text-slate-500">
              <p className="font-medium text-slate-400">{t('footer_verified_pay')}</p>
              <div className="flex flex-wrap items-center gap-2 mt-2">
                <span className="px-2.5 py-1 bg-slate-900 border border-slate-800 rounded-lg text-[10px] text-slate-300 font-mono font-bold">
                  MIDTRANS
                </span>
                <span className="px-2.5 py-1 bg-slate-900 border border-slate-800 rounded-lg text-[10px] text-slate-300 font-mono font-bold">
                  QRIS
                </span>
                <span className="px-2.5 py-1 bg-slate-900 border border-slate-800 rounded-lg text-[10px] text-slate-300 font-mono font-bold">
                  BCA / MANDIRI
                </span>
                <span className="px-2.5 py-1 bg-slate-900 border border-slate-800 rounded-lg text-[10px] text-slate-300 font-mono font-bold">
                  GOPAY / OVO
                </span>
              </div>
            </div>
          </div>

          {/* Col 1 */}
          <div className="text-left">
            <h4 className="text-white font-bold text-xs tracking-wider uppercase mb-4">{t('footer_cat_title')}</h4>
            <ul className="space-y-2.5 text-xs">
              <li><Link to="/products?category_id=1" className="hover:text-white transition-colors">Elektronik & Gadget</Link></li>
              <li><Link to="/products?category_id=2" className="hover:text-white transition-colors">Fashion Pria</Link></li>
              <li><Link to="/products?category_id=3" className="hover:text-white transition-colors">Fashion Wanita</Link></li>
              <li><Link to="/products?category_id=4" className="hover:text-white transition-colors">Peralatan Rumah Tangga</Link></li>
              <li><Link to="/products?category_id=5" className="hover:text-white transition-colors">Kesehatan & Kecantikan</Link></li>
            </ul>
          </div>

          {/* Col 2 */}
          <div className="text-left">
            <h4 className="text-white font-bold text-xs tracking-wider uppercase mb-4">{t('footer_service_title')}</h4>
            <ul className="space-y-2.5 text-xs">
              <li><Link to="/orders" className="hover:text-white transition-colors">{t('footer_track_order')}</Link></li>
              <li><Link to="/wishlist" className="hover:text-white transition-colors">{t('footer_my_wishlist')}</Link></li>
              <li><a href="#faq" className="hover:text-white transition-colors">{t('footer_warranty')}</a></li>
              <li><a href="#shipping" className="hover:text-white transition-colors">{t('footer_shipping_guide')}</a></li>
            </ul>
          </div>

          {/* Col 3: About & Language Switcher */}
          <div className="text-left space-y-6">
            <div>
              <h4 className="text-white font-bold text-xs tracking-wider uppercase mb-4">{t('footer_about_title')}</h4>
              <ul className="space-y-2.5 text-xs">
                <li><a href="#about" className="hover:text-white transition-colors">{t('footer_about_us')}</a></li>
                <li><a href="#terms" className="hover:text-white transition-colors">{t('footer_terms')}</a></li>
                <li><a href="#privacy" className="hover:text-white transition-colors">{t('footer_privacy')}</a></li>
                <li><a href="#contact" className="hover:text-white transition-colors">{t('footer_contact')}</a></li>
              </ul>
            </div>

            {/* Language Switcher Section */}
            <div className="pt-2 border-t border-slate-900">
              <div className="flex items-center gap-1.5 text-xs font-bold text-slate-300 mb-2.5">
                <Globe size={14} className="text-emerald-400" />
                <span>{t('footer_lang_title')} / Language</span>
              </div>
              <div className="inline-flex p-1 bg-slate-900 border border-slate-800 rounded-xl">
                <button
                  type="button"
                  onClick={() => setLanguage('id')}
                  className={`flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition-all ${
                    language === 'id'
                      ? 'bg-slate-800 text-white shadow-xs border border-slate-700/60'
                      : 'text-slate-400 hover:text-white'
                  }`}
                >
                  <span>🇮🇩</span>
                  <span>Indonesia</span>
                  {language === 'id' && <Check size={12} className="text-emerald-400" />}
                </button>
                <button
                  type="button"
                  onClick={() => setLanguage('en')}
                  className={`flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition-all ${
                    language === 'en'
                      ? 'bg-slate-800 text-white shadow-xs border border-slate-700/60'
                      : 'text-slate-400 hover:text-white'
                  }`}
                >
                  <span>🇬🇧</span>
                  <span>English</span>
                  {language === 'en' && <Check size={12} className="text-emerald-400" />}
                </button>
              </div>
            </div>
          </div>
        </div>

        {/* Copyright */}
        <div className="border-t border-slate-900 mt-12 pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
          <p>&copy; {new Date().getFullYear()} Radiant Studio E-Commerce. {t('footer_rights')}</p>
          <div className="flex items-center gap-3">
            <span className="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse" />
            <p>Demo Mode &bull; Powered by Laravel 11 API & React</p>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
