import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { useLanguage } from '@shared/context/LanguageContext';
import { useCms } from '@shared/context/CmsContext';
import cmsApi from '@shared/api/cms';
import { 
  ChevronRight, 
  ChevronDown, 
  HelpCircle, 
  FileText, 
  ExternalLink, 
  ArrowLeft,
  ShieldCheck,
  Sparkles,
  Info
} from 'lucide-react';

export default function StaticPage() {
  const { slug } = useParams();
  const { language, t } = useLanguage();
  const { pagesNav } = useCms();
  const [page, setPage] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [openAccordions, setOpenAccordions] = useState({});

  useEffect(() => {
    let isMounted = true;
    setLoading(true);
    setError(null);

    cmsApi.getPage(slug, language || 'id')
      .then((res) => {
        if (isMounted) {
          if (res.data && res.data.success) {
            setPage(res.data.data);
            document.title = `${res.data.data.title} | Radiant Studio`;
          } else {
            setError('Halaman tidak ditemukan');
          }
        }
      })
      .catch((err) => {
        if (isMounted) {
          setError(err.response?.status === 404 ? 'Halaman tidak ditemukan' : 'Gagal memuat konten halaman');
        }
      })
      .finally(() => {
        if (isMounted) setLoading(false);
      });

    return () => {
      isMounted = false;
    };
  }, [slug, language]);

  const toggleAccordion = (blockId, itemIdx) => {
    const key = `${blockId}-${itemIdx}`;
    setOpenAccordions((prev) => ({
      ...prev,
      [key]: !prev[key],
    }));
  };

  if (loading) {
    return (
      <div className="min-h-[60vh] flex flex-col items-center justify-center py-20">
        <div className="w-10 h-10 border-4 border-slate-200 border-t-emerald-600 rounded-full animate-spin mb-4" />
        <p className="text-slate-500 text-sm font-medium animate-pulse">Memuat konten halaman...</p>
      </div>
    );
  }

  if (error || !page) {
    return (
      <div className="max-w-3xl mx-auto px-4 py-24 text-center">
        <div className="w-16 h-16 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-sm">
          <Info className="w-8 h-8" />
        </div>
        <h1 className="text-2xl font-bold text-slate-900 mb-2">Halaman Tidak Ditemukan</h1>
        <p className="text-slate-500 mb-8 max-w-md mx-auto text-sm leading-relaxed">
          Maaf, halaman yang Anda cari mungkin telah dipindahkan, dinonaktifkan, atau alamat URL yang dimasukkan kurang tepat.
        </p>
        <Link
          to="/"
          className="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 text-white rounded-xl font-medium text-sm hover:bg-slate-800 transition-all shadow-sm shadow-slate-900/10"
        >
          <ArrowLeft className="w-4 h-4" /> Kembali ke Beranda
        </Link>
      </div>
    );
  }

  const isSidebar = page.template === 'with-sidebar';

  return (
    <div className="min-h-screen py-10 bg-[#FAFAFA]">
      <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Breadcrumb */}
        <nav className="flex items-center gap-2 text-xs text-slate-400 mb-8">
          <Link to="/" className="hover:text-slate-700 transition-colors">Beranda</Link>
          <ChevronRight className="w-3.5 h-3.5" />
          <span className="text-slate-400">Halaman</span>
          <ChevronRight className="w-3.5 h-3.5" />
          <span className="text-slate-800 font-semibold">{page.title}</span>
        </nav>

        {/* Content Layout */}
        <div className={`grid grid-cols-1 ${isSidebar ? 'lg:grid-cols-12 gap-10' : 'max-w-4xl mx-auto'}`}>
          {/* Main Article */}
          <article className={`${isSidebar ? 'lg:col-span-8' : ''} bg-white rounded-3xl p-6 sm:p-10 lg:p-12 shadow-sm border border-slate-100`}>
            {/* Header Badge & Title */}
            <div className="mb-10 pb-8 border-b border-slate-100">
              <span className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 mb-4 border border-emerald-100/60">
                <Sparkles className="w-3.5 h-3.5" /> Informasi Resmi
              </span>
              <h1 className="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                {page.title}
              </h1>
            </div>

            {/* Modular Content Blocks */}
            <div className="space-y-8 text-slate-600 leading-relaxed">
              {page.blocks && page.blocks.length > 0 ? (
                page.blocks.map((block) => {
                  const content = block.content || {};

                  // 1. Heading Block
                  if (block.type === 'heading') {
                    const text = content.text || '';
                    if (content.level === 1) {
                      return <h2 key={block.id} className="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight pt-4">{text}</h2>;
                    }
                    if (content.level === 3) {
                      return <h4 key={block.id} className="text-lg font-bold text-slate-900 pt-2">{text}</h4>;
                    }
                    return <h3 key={block.id} className="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight pt-3">{text}</h3>;
                  }

                  // 2. Text Block
                  if (block.type === 'text') {
                    return (
                      <div key={block.id} className="prose prose-slate max-w-none text-[15px] sm:text-base leading-relaxed text-slate-600 space-y-4">
                        {(content.body || '').split('\n\n').map((paragraph, pIdx) => (
                          <p key={pIdx} className="leading-relaxed whitespace-pre-line">{paragraph}</p>
                        ))}
                      </div>
                    );
                  }

                  // 3. Accordion / FAQ Block
                  if (block.type === 'accordion') {
                    const items = content.items || [];
                    return (
                      <div key={block.id} className="space-y-3 pt-2">
                        {items.map((item, idx) => {
                          const isOpen = openAccordions[`${block.id}-${idx}`] ?? (idx === 0);
                          return (
                            <div 
                              key={idx} 
                              className={`rounded-2xl border transition-all duration-200 overflow-hidden ${
                                isOpen 
                                  ? 'border-emerald-200 bg-emerald-50/20 shadow-sm' 
                                  : 'border-slate-200/80 bg-white hover:border-slate-300'
                              }`}
                            >
                              <button
                                type="button"
                                onClick={() => toggleAccordion(block.id, idx)}
                                className="w-full flex items-center justify-between p-5 text-left font-semibold text-slate-900 gap-4"
                              >
                                <span className="flex items-center gap-3 text-[15px]">
                                  <span className="w-7 h-7 rounded-lg bg-emerald-100/70 text-emerald-800 flex items-center justify-center text-xs font-bold shrink-0">
                                    Q{idx + 1}
                                  </span>
                                  {item.title}
                                </span>
                                <ChevronDown className={`w-5 h-5 text-slate-400 shrink-0 transition-transform duration-200 ${isOpen ? 'rotate-180 text-emerald-600' : ''}`} />
                              </button>
                              {isOpen && (
                                <div className="px-5 pb-5 pt-1 text-sm text-slate-600 leading-relaxed border-t border-emerald-100/40">
                                  <p className="whitespace-pre-line pl-10">{item.body}</p>
                                </div>
                              )}
                            </div>
                          );
                        })}
                      </div>
                    );
                  }

                  // 4. Image Block
                  if (block.type === 'image') {
                    return (
                      <div key={block.id} className="my-6">
                        <img 
                          src={content.src} 
                          alt={content.alt || page.title}
                          className="w-full rounded-2xl object-cover max-h-[450px] shadow-sm border border-slate-100" 
                        />
                        {content.caption && (
                          <p className="text-xs text-center text-slate-400 mt-2 italic">{content.caption}</p>
                        )}
                      </div>
                    );
                  }

                  // 5. CTA Block
                  if (block.type === 'cta') {
                    return (
                      <div key={block.id} className="my-8 p-8 rounded-3xl bg-gradient-to-br from-slate-900 to-slate-800 text-white shadow-xl relative overflow-hidden">
                        <div className="absolute top-0 right-0 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none" />
                        <div className="relative z-10 max-w-xl">
                          <h4 className="text-xl sm:text-2xl font-bold tracking-tight mb-2 text-white">
                            {content.title || 'Mulai Belanja Hari Ini'}
                          </h4>
                          <p className="text-slate-300 text-sm mb-6 leading-relaxed">
                            {content.description || 'Dapatkan produk pilihan terbaik dengan jaminan original dan promo bebas ongkir.'}
                          </p>
                          {content.button_link && (
                            <Link
                              to={content.button_link}
                              className="inline-flex items-center gap-2 px-6 py-3 bg-emerald-500 text-white rounded-xl font-semibold text-sm hover:bg-emerald-400 transition-all shadow-md shadow-emerald-500/20"
                            >
                              {content.button_text || 'Jelajahi Katalog'}
                              <ExternalLink className="w-4 h-4" />
                            </Link>
                          )}
                        </div>
                      </div>
                    );
                  }

                  return null;
                })
              ) : (
                <p className="text-slate-400 italic">Konten halaman sedang disiapkan oleh tim kami.</p>
              )}
            </div>
          </article>

          {/* Optional Sidebar */}
          {isSidebar && (
            <aside className="lg:col-span-4 space-y-6">
              <div className="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                <h3 className="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                  <FileText className="w-4 h-4 text-emerald-600" /> Halaman Lainnya
                </h3>
                <div className="space-y-1">
                  {pagesNav.map((p) => {
                    const isActive = p.slug === slug;
                    return (
                      <Link
                        key={p.slug}
                        to={`/pages/${p.slug}`}
                        className={`flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors ${
                          isActive 
                            ? 'bg-emerald-50 text-emerald-700 font-semibold' 
                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'
                        }`}
                      >
                        <span>{p.title}</span>
                        <ChevronRight className={`w-4 h-4 ${isActive ? 'text-emerald-600' : 'text-slate-300'}`} />
                      </Link>
                    );
                  })}
                </div>
              </div>

              {/* Help Box */}
              <div className="bg-emerald-50/70 border border-emerald-100 rounded-3xl p-6">
                <div className="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center mb-4 shadow-sm">
                  <HelpCircle className="w-5 h-5" />
                </div>
                <h4 className="font-bold text-slate-900 text-sm mb-1">Butuh Bantuan Langsung?</h4>
                <p className="text-xs text-slate-600 mb-4 leading-relaxed">
                  Tim Customer Service kami siap membantu Anda menjawab kendala pemesanan 24/7.
                </p>
                <Link
                  to="/pages/contact"
                  className="inline-block w-full py-2.5 bg-emerald-600 text-white rounded-xl text-center text-xs font-semibold hover:bg-emerald-700 transition-colors shadow-sm"
                >
                  Hubungi Dukungan CS
                </Link>
              </div>
            </aside>
          )}
        </div>
      </div>
    </div>
  );
}
