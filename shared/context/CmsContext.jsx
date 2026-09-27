import React, { createContext, useContext, useState, useEffect, useCallback } from 'react';
import { useLanguage } from './LanguageContext';
import cmsApi from '../api/cms';

const CmsContext = createContext();

const DEFAULT_SETTINGS = {
  site_name: 'Radiant Studio',
  site_tagline: 'Premium E-Commerce Experience',
  site_email: 'hello@radiantstudio.com',
  site_phone: '+6287878444402',
  site_whatsapp: '+6287878444402',
  site_currency: 'IDR',
  copyright_text: '© 2026 Radiant Studio. Hak cipta dilindungi undang-undang.',
  powered_by_text: 'Powered by Radiant Studio Commerce Engine',
  maintenance_mode: '0',
  primary_color: '#0f172a',
  accent_color: '#059669',
  font_family: 'Plus Jakarta Sans',
  announcement_active: '1',
  announcement_text: '🚚 Bebas Ongkir ke Seluruh Indonesia untuk Pesanan di Atas Rp 100.000!',
  announcement_link: '/products',
  announcement_bg_color: '#0f172a',
  announcement_text_color: '#ffffff',
  social_whatsapp: 'https://wa.me/6287878444402',
  social_instagram: 'https://instagram.com/radofdiant',
  social_twitter: 'https://twitter.com/radiant213_',
  social_github: 'https://github.com/Radiant213',
  footer_about_desc: 'Platform e-commerce premium terpercaya dengan kurasi produk berkualitas, jaminan produk 100% original, dan pengalaman belanja modern.',
  footer_verified_payment: 'Metode Pembayaran Resmi',
  footer_payment_methods: 'MIDTRANS,QRIS,BCA,MANDIRI,BNI,BRI,GOPAY,OVO,SHOPEEPAY',
};

export function CmsProvider({ children }) {
  const { language } = useLanguage();
  const [cmsData, setCmsData] = useState({
    settings: DEFAULT_SETTINGS,
    menus: {},
    homepageSections: [],
    banners: [],
    popups: [],
    footer: null,
    pagesNav: [],
  });
  const [loading, setLoading] = useState(true);

  const fetchCmsData = useCallback(async () => {
    try {
      const response = await cmsApi.getBootstrap(language || 'id');
      if (response.data && response.data.success) {
        const d = response.data.data;
        setCmsData({
          settings: { ...DEFAULT_SETTINGS, ...(d.settings || {}) },
          menus: d.menus || {},
          homepageSections: Array.isArray(d.homepage_sections) ? d.homepage_sections : [],
          banners: Array.isArray(d.banners) ? d.banners : [],
          popups: Array.isArray(d.popups) ? d.popups : [],
          footer: d.footer || null,
          pagesNav: Array.isArray(d.pages_nav) ? d.pages_nav : [],
        });
      }
    } catch (error) {
      console.warn('CMS Bootstrap failed to load, falling back to default theme settings:', error.message);
    } finally {
      setLoading(false);
    }
  }, [language]);

  useEffect(() => {
    fetchCmsData();
  }, [fetchCmsData]);

  const getSetting = (key, fallback = '') => {
    return cmsData.settings[key] ?? fallback;
  };

  return (
    <CmsContext.Provider
      value={{
        ...cmsData,
        loading,
        getSetting,
        refreshCms: fetchCmsData,
      }}
    >
      {children}
    </CmsContext.Provider>
  );
}

export function useCms() {
  const context = useContext(CmsContext);
  if (!context) {
    throw new Error('useCms must be used within a CmsProvider');
  }
  return context;
}

export default CmsContext;
