import React from 'react';
import { Link } from 'react-router-dom';
import { ShieldCheck, Truck, RotateCcw, Headphones, Globe, Check, MessageCircle } from 'lucide-react';
import { useLanguage } from '@shared/context/LanguageContext';

const Footer = () => {
  const { language, setLanguage, t } = useLanguage();

  return (
    <footer className="bg-slate-950 text-slate-400 text-sm border-t border-slate-800">
      {/* 4 Pillars Trust Bar */}
      <div className="border-b border-slate-800/80 py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
          <div className="group flex items-center gap-4 p-4 rounded-2xl bg-slate-900/40 hover:bg-slate-900/90 border border-slate-800/60 hover:border-slate-700/80 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-950/20 transition-all duration-300 cursor-pointer">
            <div className="w-12 h-12 rounded-xl bg-slate-900 group-hover:bg-emerald-950/70 border border-slate-800 group-hover:border-emerald-500/40 flex items-center justify-center text-slate-200 group-hover:text-emerald-400 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-sm flex-shrink-0">
              <Truck size={22} strokeWidth={1.5} className="group-hover:scale-110 transition-transform duration-300" />
            </div>
            <div className="text-left">
              <h4 className="text-white font-bold text-sm group-hover:text-emerald-300 transition-colors">{t('footer_pillar_shipping')}</h4>
              <p className="text-xs text-slate-500 mt-0.5 group-hover:text-slate-400 transition-colors">{t('footer_pillar_shipping_sub')}</p>
            </div>
          </div>

          <div className="group flex items-center gap-4 p-4 rounded-2xl bg-slate-900/40 hover:bg-slate-900/90 border border-slate-800/60 hover:border-slate-700/80 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-950/20 transition-all duration-300 cursor-pointer">
            <div className="w-12 h-12 rounded-xl bg-slate-900 group-hover:bg-emerald-950/70 border border-slate-800 group-hover:border-emerald-500/40 flex items-center justify-center text-slate-200 group-hover:text-emerald-400 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-sm flex-shrink-0">
              <ShieldCheck size={22} strokeWidth={1.5} className="group-hover:scale-110 transition-transform duration-300" />
            </div>
            <div className="text-left">
              <h4 className="text-white font-bold text-sm group-hover:text-emerald-300 transition-colors">{t('footer_pillar_orig')}</h4>
              <p className="text-xs text-slate-500 mt-0.5 group-hover:text-slate-400 transition-colors">{t('footer_pillar_orig_sub')}</p>
            </div>
          </div>

          <div className="group flex items-center gap-4 p-4 rounded-2xl bg-slate-900/40 hover:bg-slate-900/90 border border-slate-800/60 hover:border-slate-700/80 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-950/20 transition-all duration-300 cursor-pointer">
            <div className="w-12 h-12 rounded-xl bg-slate-900 group-hover:bg-emerald-950/70 border border-slate-800 group-hover:border-emerald-500/40 flex items-center justify-center text-slate-200 group-hover:text-emerald-400 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-sm flex-shrink-0">
              <RotateCcw size={22} strokeWidth={1.5} className="group-hover:scale-110 transition-transform duration-300" />
            </div>
            <div className="text-left">
              <h4 className="text-white font-bold text-sm group-hover:text-emerald-300 transition-colors">{t('footer_pillar_guar')}</h4>
              <p className="text-xs text-slate-500 mt-0.5 group-hover:text-slate-400 transition-colors">{t('footer_pillar_guar_sub')}</p>
            </div>
          </div>

          <div className="group flex items-center gap-4 p-4 rounded-2xl bg-slate-900/40 hover:bg-slate-900/90 border border-slate-800/60 hover:border-slate-700/80 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-950/20 transition-all duration-300 cursor-pointer">
            <div className="w-12 h-12 rounded-xl bg-slate-900 group-hover:bg-emerald-950/70 border border-slate-800 group-hover:border-emerald-500/40 flex items-center justify-center text-slate-200 group-hover:text-emerald-400 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-sm flex-shrink-0">
              <Headphones size={22} strokeWidth={1.5} className="group-hover:scale-110 transition-transform duration-300" />
            </div>
            <div className="text-left">
              <h4 className="text-white font-bold text-sm group-hover:text-emerald-300 transition-colors">{t('footer_pillar_cs')}</h4>
              <p className="text-xs text-slate-500 mt-0.5 group-hover:text-slate-400 transition-colors">{t('footer_pillar_cs_sub')}</p>
            </div>
          </div>
        </div>
      </div>

      {/* Main Footer Links */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
          {/* Brand Col */}
          <div className="lg:col-span-2 space-y-4 text-left">
            <Link to="/" className="inline-flex items-center gap-2.5 group cursor-pointer hover-pop-lift select-none">
              <span className="w-9 h-9 rounded-xl bg-gradient-to-br from-white to-slate-200 text-slate-950 flex items-center justify-center font-extrabold text-base shadow-sm group-hover:scale-115 group-hover:-rotate-8 group-hover:bg-emerald-400 group-hover:text-slate-950 transition-all duration-300">
                R
              </span>
              <span className="font-extrabold text-white tracking-wider text-base group-hover:text-emerald-300 transition-colors">RADIANT STUDIO</span>
            </Link>
            <p className="text-xs leading-relaxed max-w-sm text-slate-400">
              {t('footer_about_desc')}
            </p>

            {/* Social Icons Bar */}
            <div className="pt-2">
              <p className="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2.5">Ikuti Kami</p>
              <div className="flex items-center gap-2">
                {/* WhatsApp */}
                <a
                  href="https://wa.me/+6287878444402"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="w-9 h-9 rounded-xl bg-slate-900 border border-slate-800 hover:border-emerald-500/50 hover:bg-emerald-600 hover:text-white text-slate-400 flex items-center justify-center hover-pop-bounce cursor-pointer group"
                  title="WhatsApp"
                >
                  <MessageCircle size={16} className="group-hover:scale-115 transition-transform" />
                </a>

                {/* Instagram */}
                <a
                  href="https://instagram.com/radofdiant"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="w-9 h-9 rounded-xl bg-slate-900 border border-slate-800 hover:border-pink-500/50 hover:bg-gradient-to-tr hover:from-amber-500 hover:via-pink-500 hover:to-purple-600 hover:text-white text-slate-400 flex items-center justify-center hover-pop-bounce cursor-pointer group"
                  title="Instagram"
                >
                  <svg className="w-4 h-4 fill-current group-hover:scale-115 transition-transform" viewBox="0 0 24 24">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                  </svg>
                </a>

                {/* X / Twitter */}
                <a
                  href="https://twitter.com/radiant213_"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="w-9 h-9 rounded-xl bg-slate-900 border border-slate-800 hover:border-slate-600 hover:bg-white hover:text-slate-950 text-slate-400 flex items-center justify-center hover-pop-bounce cursor-pointer group"
                  title="X (Twitter)"
                >
                  <svg className="w-3.5 h-3.5 fill-current group-hover:scale-115 transition-transform" viewBox="0 0 24 24">
                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                  </svg>
                </a>

                {/* GitHub */}
                <a
                  href="https://github.com/Radiant213"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="w-9 h-9 rounded-xl bg-slate-900 border border-slate-800 hover:border-slate-600 hover:bg-slate-800 hover:text-white text-slate-400 flex items-center justify-center hover-pop-bounce cursor-pointer group"
                  title="GitHub"
                >
                  <svg className="w-4 h-4 fill-current group-hover:scale-115 transition-transform" viewBox="0 0 24 24">
                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                  </svg>
                </a>
              </div>
            </div>

            <div className="pt-2 text-xs text-slate-500">
              <p className="font-medium text-slate-400">{t('footer_verified_pay')}</p>
              <div className="flex flex-wrap items-center gap-2 mt-2">
                {['MIDTRANS', 'QRIS', 'BCA / MANDIRI', 'GOPAY / OVO'].map((item) => (
                  <span
                    key={item}
                    className="px-2.5 py-1 bg-slate-900 border border-slate-800 hover:border-slate-700 hover:bg-slate-800 hover:text-slate-200 hover:scale-105 transition-all duration-200 rounded-lg text-[10px] text-slate-300 font-mono font-bold cursor-default shadow-2xs"
                  >
                    {item}
                  </span>
                ))}
              </div>
            </div>
          </div>

          {/* Col 1 */}
          <div className="text-left">
            <h4 className="text-white font-bold text-xs tracking-wider uppercase mb-4">{t('footer_cat_title')}</h4>
            <ul className="space-y-1.5 text-xs">
              <li>
                <Link to="/products?category_id=1" className="inline-block text-slate-400 hover:text-white hover-pop-bounce hover:translate-x-1.5 cursor-pointer origin-left py-0.5">
                  Elektronik & Gadget
                </Link>
              </li>
              <li>
                <Link to="/products?category_id=2" className="inline-block text-slate-400 hover:text-white hover-pop-bounce hover:translate-x-1.5 cursor-pointer origin-left py-0.5">
                  Fashion Pria
                </Link>
              </li>
              <li>
                <Link to="/products?category_id=3" className="inline-block text-slate-400 hover:text-white hover-pop-bounce hover:translate-x-1.5 cursor-pointer origin-left py-0.5">
                  Fashion Wanita
                </Link>
              </li>
              <li>
                <Link to="/products?category_id=4" className="inline-block text-slate-400 hover:text-white hover-pop-bounce hover:translate-x-1.5 cursor-pointer origin-left py-0.5">
                  Peralatan Rumah Tangga
                </Link>
              </li>
              <li>
                <Link to="/products?category_id=5" className="inline-block text-slate-400 hover:text-white hover-pop-bounce hover:translate-x-1.5 cursor-pointer origin-left py-0.5">
                  Kesehatan & Kecantikan
                </Link>
              </li>
            </ul>
          </div>

          {/* Col 2 */}
          <div className="text-left">
            <h4 className="text-white font-bold text-xs tracking-wider uppercase mb-4">{t('footer_service_title')}</h4>
            <ul className="space-y-1.5 text-xs">
              <li>
                <Link to="/dashboard?tab=orders" className="inline-block text-slate-400 hover:text-white hover-pop-bounce hover:translate-x-1.5 cursor-pointer origin-left py-0.5">
                  {t('footer_track_order')}
                </Link>
              </li>
              <li>
                <Link to="/wishlist" className="inline-block text-slate-400 hover:text-white hover-pop-bounce hover:translate-x-1.5 cursor-pointer origin-left py-0.5">
                  {t('footer_my_wishlist')}
                </Link>
              </li>
              <li>
                <a href="#faq" className="inline-block text-slate-400 hover:text-white hover-pop-bounce hover:translate-x-1.5 cursor-pointer origin-left py-0.5">
                  {t('footer_warranty')}
                </a>
              </li>
              <li>
                <a href="#shipping" className="inline-block text-slate-400 hover:text-white hover-pop-bounce hover:translate-x-1.5 cursor-pointer origin-left py-0.5">
                  {t('footer_shipping_guide')}
                </a>
              </li>
            </ul>
          </div>

          {/* Col 3: About & Language Switcher */}
          <div className="text-left space-y-6">
            <div>
              <h4 className="text-white font-bold text-xs tracking-wider uppercase mb-4">{t('footer_about_title')}</h4>
              <ul className="space-y-1.5 text-xs">
                <li>
                  <a href="#about" className="inline-block text-slate-400 hover:text-white hover-pop-bounce hover:translate-x-1.5 cursor-pointer origin-left py-0.5">
                    {t('footer_about_us')}
                  </a>
                </li>
                <li>
                  <a href="#terms" className="inline-block text-slate-400 hover:text-white hover-pop-bounce hover:translate-x-1.5 cursor-pointer origin-left py-0.5">
                    {t('footer_terms')}
                  </a>
                </li>
                <li>
                  <a href="#privacy" className="inline-block text-slate-400 hover:text-white hover-pop-bounce hover:translate-x-1.5 cursor-pointer origin-left py-0.5">
                    {t('footer_privacy')}
                  </a>
                </li>
                <li>
                  <a href="#contact" className="inline-block text-slate-400 hover:text-white hover-pop-bounce hover:translate-x-1.5 cursor-pointer origin-left py-0.5">
                    {t('footer_contact')}
                  </a>
                </li>
              </ul>
            </div>

            {/* Language Switcher Section */}
            <div className="pt-2 border-t border-slate-900">
              <div className="flex items-center gap-1.5 text-xs font-bold text-slate-300 mb-2.5">
                <Globe size={14} className="text-emerald-400" />
                <span>{t('footer_lang_title')} / Language</span>
              </div>
              <div className="inline-flex p-1 bg-slate-900 border border-slate-800 rounded-xl shadow-inner">
                <button
                  type="button"
                  onClick={() => setLanguage('id')}
                  className={`flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition-all duration-200 hover:scale-105 active:scale-95 cursor-pointer ${language === 'id'
                    ? 'bg-slate-800 text-white shadow-xs border border-slate-700/60 ring-1 ring-emerald-500/30'
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
                  className={`flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition-all duration-200 hover:scale-105 active:scale-95 cursor-pointer ${language === 'en'
                    ? 'bg-slate-800 text-white shadow-xs border border-slate-700/60 ring-1 ring-emerald-500/30'
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
