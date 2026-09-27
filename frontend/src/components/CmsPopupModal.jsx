import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { useCms } from '@shared/context/CmsContext';
import { X, Sparkles, ArrowRight } from 'lucide-react';

export default function CmsPopupModal() {
  const { popups } = useCms();
  const [activePopup, setActivePopup] = useState(null);
  const [isOpen, setIsOpen] = useState(false);

  useEffect(() => {
    if (!popups || !Array.isArray(popups) || popups.length === 0) return;

    try {
      // Pick first active popup that hasn't been closed in this session
      const popup = popups.find((p) => {
        if (!p || typeof p !== 'object' || !p.id) return false;
        try {
          const alreadyShown = sessionStorage.getItem(`cms_popup_dismissed_${p.id}`);
          return !alreadyShown;
        } catch {
          return false;
        }
      });

      if (!popup) return;

      setActivePopup(popup);

      const delayMs = Math.max(1, parseInt(popup.delay_seconds, 10) || 2) * 1000;
      const timer = setTimeout(() => {
        setIsOpen(true);
      }, delayMs);

      return () => clearTimeout(timer);
    } catch (err) {
      console.warn('Error evaluating CMS popup:', err);
    }
  }, [popups]);

  const handleClose = () => {
    if (activePopup?.id) {
      try {
        sessionStorage.setItem(`cms_popup_dismissed_${activePopup.id}`, 'true');
      } catch {
        // Ignore storage errors
      }
    }
    setIsOpen(false);
  };

  if (!isOpen || !activePopup) return null;

  const isExternalCta = activePopup.cta_link && (
    activePopup.cta_link.startsWith('http://') || 
    activePopup.cta_link.startsWith('https://')
  );

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm animate-fade-in">
      <div 
        className="relative w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl border border-slate-100 animate-scale-up"
        onClick={(e) => e.stopPropagation()}
      >
        {/* Close Button */}
        <button
          type="button"
          onClick={handleClose}
          className="absolute top-4 right-4 z-10 w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition-colors"
          aria-label="Tutup modal"
        >
          <X className="w-5 h-5" />
        </button>

        {/* Optional Image */}
        {activePopup.image && (
          <div className="relative h-44 w-full bg-slate-100 overflow-hidden">
            <img 
              src={activePopup.image} 
              alt={activePopup.title || 'Promo'} 
              className="w-full h-full object-cover" 
            />
            <div className="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent" />
          </div>
        )}

        {/* Content Body */}
        <div className="p-6 sm:p-8 text-center">
          <div className="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4 border border-emerald-100">
            <Sparkles className="w-6 h-6" />
          </div>

          <h3 className="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mb-2">
            {activePopup.title}
          </h3>

          <p className="text-slate-600 text-sm leading-relaxed mb-6">
            {activePopup.description}
          </p>

          <div className="flex flex-col gap-2.5">
            {activePopup.cta_link && (
              isExternalCta ? (
                <a
                  href={activePopup.cta_link}
                  target="_blank"
                  rel="noopener noreferrer"
                  onClick={handleClose}
                  className="w-full py-3.5 px-6 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm flex items-center justify-center gap-2 transition-all shadow-md shadow-slate-900/10"
                >
                  <span>{activePopup.cta_text || 'Lihat Promo'}</span>
                  <ArrowRight className="w-4 h-4" />
                </a>
              ) : (
                <Link
                  to={activePopup.cta_link}
                  onClick={handleClose}
                  className="w-full py-3.5 px-6 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm flex items-center justify-center gap-2 transition-all shadow-md shadow-slate-900/10"
                >
                  <span>{activePopup.cta_text || 'Lihat Promo'}</span>
                  <ArrowRight className="w-4 h-4" />
                </Link>
              )
            )}

            <button
              type="button"
              onClick={handleClose}
              className="w-full py-2.5 text-xs text-slate-400 hover:text-slate-600 font-medium transition-colors"
            >
              Nanti Saja
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
