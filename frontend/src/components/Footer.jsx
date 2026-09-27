import React from 'react';
import { Link } from 'react-router-dom';
import { ShieldCheck, Truck, RotateCcw, Headphones, Globe, Check, MessageCircle } from 'lucide-react';
import { useLanguage } from '@shared/context/LanguageContext';
import { useCms } from '@shared/context/CmsContext';

const ICON_MAP = {
  Truck: Truck,
  ShieldCheck: ShieldCheck,
  RotateCcw: RotateCcw,
  Headphones: Headphones,
};

const Footer = () => {
  const { language, setLanguage, t } = useLanguage();
  const { settings, menus } = useCms();

  // Parse Trust Pillars from CMS
  let trustPillars = [];
  try {
    if (typeof settings?.footer_trust_pillars === 'string') {
      trustPillars = JSON.parse(settings.footer_trust_pillars);
    } else if (Array.isArray(settings?.footer_trust_pillars)) {
      trustPillars = settings.footer_trust_pillars;
    }
  } catch {
    trustPillars = [];
  }

  // Fallback pillars if not configured in CMS
  if (!trustPillars || trustPillars.length === 0) {
    trustPillars = [
      { icon: 'Truck', title: t('footer_pillar_shipping'), subtitle: t('footer_pillar_shipping_sub') },
      { icon: 'ShieldCheck', title: t('footer_pillar_orig'), subtitle: t('footer_pillar_orig_sub') },
      { icon: 'RotateCcw', title: t('footer_pillar_guar'), subtitle: t('footer_pillar_guar_sub') },
      { icon: 'Headphones', title: t('footer_pillar_cs'), subtitle: t('footer_pillar_cs_sub') },
    ];
  }

  // Footer navigation columns from CMS
  const col1Items = menus?.footer_col_1?.items || [
    { label: 'Elektronik & Gadget', url: '/products?category_id=1' },
    { label: 'Fashion Pria', url: '/products?category_id=2' },
    { label: 'Fashion Wanita', url: '/products?category_id=3' },
    { label: 'Peralatan Rumah Tangga', url: '/products?category_id=4' },
    { label: 'Kesehatan & Kecantikan', url: '/products?category_id=5' },
  ];

  const col2Items = menus?.footer_col_2?.items || [
    { label: 'Bantuan & FAQ', url: '/pages/faq' },
    { label: 'Informasi Pengiriman', url: '/pages/shipping' },
    { label: 'Kebijakan Pengembalian', url: '/pages/returns' },
    { label: 'Lacak Pesanan', url: '/dashboard?tab=orders' },
    { label: 'Wishlist Saya', url: '/wishlist' },
  ];

  const col3Items = menus?.footer_col_3?.items || [
    { label: 'Tentang Radiant Studio', url: '/pages/about' },
    { label: 'Hubungi Kami', url: '/pages/contact' },
    { label: 'Syarat & Ketentuan', url: '/pages/terms' },
    { label: 'Kebijakan Privasi', url: '/pages/privacy' },
  ];

  const copyrightText = (settings?.copyright_text || '© {year} Radiant Studio. Hak cipta dilindungi undang-undang.')
    .replace('{year}', new Date().getFullYear());

  return (
    <footer className="bg-slate-950 text-slate-400 text-sm border-t border-slate-800">
      {/* Trust Pillars Bar */}
      <div className="border-b border-slate-800/80 py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
          {trustPillars.map((pillar, idx) => {
            const IconComponent = ICON_MAP[pillar.icon] || ShieldCheck;
            const title = (language === 'en' && pillar.title_en) ? pillar.title_en : pillar.title;
            const subtitle = (language === 'en' && pillar.subtitle_en) ? pillar.subtitle_en : pillar.subtitle;

            return (
              <div 
                key={idx} 
                className="group flex items-center gap-4 p-4 rounded-2xl bg-slate-900/40 hover:bg-slate-900/90 border border-slate-800/60 hover:border-slate-700/80 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-950/20 transition-all duration-300"
              >
                <div className="w-12 h-12 rounded-xl bg-slate-900 group-hover:bg-emerald-950/70 border border-slate-800 group-hover:border-emerald-500/40 flex items-center justify-center text-slate-200 group-hover:text-emerald-400 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-sm flex-shrink-0">
                  <IconComponent size={22} strokeWidth={1.5} className="group-hover:scale-110 transition-transform duration-300" />
                </div>
                <div className="text-left">
                  <h4 className="text-white font-bold text-sm group-hover:text-emerald-300 transition-colors">{title}</h4>
                  <p className="text-xs text-slate-500 mt-0.5 group-hover:text-slate-400 transition-colors">{subtitle}</p>
                </div>
              </div>
            );
          })}
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
              <span className="font-extrabold text-white tracking-wider text-base group-hover:text-emerald-300 transition-colors">
                {settings?.site_name?.toUpperCase() || 'RADIANT STUDIO'}
              </span>
            </Link>
            <p className="text-xs leading-relaxed max-w-sm text-slate-400">
              {settings?.footer_about_desc || t('footer_about_desc')}
            </p>

            {/* Social Icons Bar */}
            <div className="pt-2">
              <p className="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2.5">Ikuti Kami</p>
              <div className="flex items-center gap-2">
                {/* WhatsApp */}
                {settings?.social_whatsapp && (
                  <a
                    href={settings.social_whatsapp}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="w-9 h-9 rounded-xl bg-slate-900 border border-slate-800 hover:border-emerald-500/50 hover:bg-emerald-600 hover:text-white text-slate-400 flex items-center justify-center hover-pop-bounce cursor-pointer group"
                    title="WhatsApp"
                  >
                    <MessageCircle size={16} className="group-hover:scale-115 transition-transform" />
                  </a>
                )}

                {/* Instagram */}
                {settings?.social_instagram && (
                  <a
                    href={settings.social_instagram}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="w-9 h-9 rounded-xl bg-slate-900 border border-slate-800 hover:border-pink-500/50 hover:bg-gradient-to-tr hover:from-amber-500 hover:via-pink-500 hover:to-purple-600 hover:text-white text-slate-400 flex items-center justify-center hover-pop-bounce cursor-pointer group"
                    title="Instagram"
                  >
                    <svg className="w-4 h-4 fill-current group-hover:scale-115 transition-transform" viewBox="0 0 24 24">
                      <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                    </svg>
                  </a>
                )}

                {/* X / Twitter */}
                {settings?.social_twitter && (
                  <a
                    href={settings.social_twitter}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="w-9 h-9 rounded-xl bg-slate-900 border border-slate-800 hover:border-sky-500/50 hover:bg-slate-800 hover:text-white text-slate-400 flex items-center justify-center hover-pop-bounce cursor-pointer group"
                    title="X / Twitter"
                  >
                    <svg className="w-3.5 h-3.5 fill-current group-hover:scale-115 transition-transform" viewBox="0 0 24 24">
                      <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                    </svg>
                  </a>
                )}

                {/* GitHub */}
                {settings?.social_github && (
                  <a
                    href={settings.social_github}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="w-9 h-9 rounded-xl bg-slate-900 border border-slate-800 hover:border-purple-500/50 hover:bg-purple-900/60 hover:text-white text-slate-400 flex items-center justify-center hover-pop-bounce cursor-pointer group"
                    title="GitHub"
                  >
                    <svg className="w-4 h-4 fill-current group-hover:scale-115 transition-transform" viewBox="0 0 24 24">
                      <path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" />
                    </svg>
                  </a>
                )}
              </div>
            </div>
          </div>

          {/* Col 1 */}
          <div className="text-left">
            <h4 className="text-white font-bold text-xs tracking-wider uppercase mb-4">
              {menus?.footer_col_1?.name || t('footer_cat_title')}
            </h4>
            <ul className="space-y-1.5 text-xs">
              {col1Items.map((item, idx) => (
                <li key={idx}>
                  <Link 
                    to={item.url} 
                    target={item.target || '_self'}
                    className="inline-block text-slate-400 hover:text-white hover-pop-bounce hover:translate-x-1.5 cursor-pointer origin-left py-0.5"
                  >
                    {item.label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Col 2 */}
          <div className="text-left">
            <h4 className="text-white font-bold text-xs tracking-wider uppercase mb-4">
              {menus?.footer_col_2?.name || t('footer_service_title')}
            </h4>
            <ul className="space-y-1.5 text-xs">
              {col2Items.map((item, idx) => (
                <li key={idx}>
                  <Link 
                    to={item.url} 
                    target={item.target || '_self'}
                    className="inline-block text-slate-400 hover:text-white hover-pop-bounce hover:translate-x-1.5 cursor-pointer origin-left py-0.5"
                  >
                    {item.label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Col 3: About & Language Switcher */}
          <div className="text-left space-y-6">
            <div>
              <h4 className="text-white font-bold text-xs tracking-wider uppercase mb-4">
                {menus?.footer_col_3?.name || t('footer_about_title')}
              </h4>
              <ul className="space-y-1.5 text-xs">
                {col3Items.map((item, idx) => (
                  <li key={idx}>
                    <Link 
                      to={item.url} 
                      target={item.target || '_self'}
                      className="inline-block text-slate-400 hover:text-white hover-pop-bounce hover:translate-x-1.5 cursor-pointer origin-left py-0.5"
                    >
                      {item.label}
                    </Link>
                  </li>
                ))}
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

        {/* Payment Methods & Copyright */}
        <div className="border-t border-slate-900 mt-12 pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
          <p>{copyrightText}</p>
          <div className="flex items-center gap-3">
            <span className="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse" />
            <p>{settings?.powered_by_text || 'Powered by Laravel 11 API & React'}</p>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
