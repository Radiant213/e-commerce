import React, { createContext, useContext, useState, useCallback, useEffect } from 'react';
import { createPortal } from 'react-dom';
import { CheckCircle2, AlertTriangle, Trash2, Info, X, HelpCircle } from 'lucide-react';

const ConfirmContext = createContext(null);

export const ConfirmProvider = ({ children }) => {
  const [modalState, setModalState] = useState(null);

  /**
   * Triggers a confirmation dialog. Returns a Promise resolving to boolean.
   *
   * @param {Object} options
   * @param {string} options.title
   * @param {string|React.ReactNode} options.message
   * @param {string} [options.confirmText='Lanjutkan']
   * @param {string} [options.cancelText='Batal']
   * @param {'success'|'warning'|'danger'|'info'} [options.type='warning']
   * @param {boolean} [options.isAlertOnly=false]
   * @returns {Promise<boolean>}
   */
  const confirm = useCallback((options) => {
    return new Promise((resolve) => {
      setModalState({
        ...options,
        resolve,
      });
    });
  }, []);

  /**
   * Triggers an aesthetic alert modal with a single "Tutup" / "Mengerti" button.
   */
  const alertModal = useCallback((message, title = 'Pemberitahuan', type = 'info') => {
    return new Promise((resolve) => {
      setModalState({
        title,
        message,
        confirmText: 'Mengerti',
        isAlertOnly: true,
        type,
        resolve: () => resolve(true),
      });
    });
  }, []);

  const handleClose = (result) => {
    if (modalState?.resolve) {
      modalState.resolve(result);
    }
    setModalState(null);
  };

  // Close on Escape key
  useEffect(() => {
    const handleKeyDown = (e) => {
      if (e.key === 'Escape' && modalState) {
        handleClose(false);
      }
    };
    window.addEventListener('keydown', handleKeyDown);
    return () => window.removeEventListener('keydown', handleKeyDown);
  }, [modalState]);

  // Expose global window.__confirm and window.__alert for universal fallback if needed
  useEffect(() => {
    window.__customConfirm = (msg, title = 'Konfirmasi') =>
      confirm({ title, message: msg, type: 'warning' });
    window.__customAlert = (msg, title = 'Pemberitahuan') =>
      alertModal(msg, title, 'info');
  }, [confirm, alertModal]);

  const getIconAndColors = (type = 'warning') => {
    switch (type) {
      case 'success':
        return {
          icon: <CheckCircle2 size={28} className="text-emerald-500" />,
          bg: 'bg-emerald-50 border-emerald-200 text-emerald-700',
          ring: 'ring-emerald-500/20',
          btnConfirm: 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-emerald-600/30',
        };
      case 'danger':
        return {
          icon: <Trash2 size={28} className="text-red-500" />,
          bg: 'bg-red-50 border-red-200 text-red-700',
          ring: 'ring-red-500/20',
          btnConfirm: 'bg-red-600 hover:bg-red-700 text-white shadow-red-600/30',
        };
      case 'info':
        return {
          icon: <Info size={28} className="text-sky-500" />,
          bg: 'bg-sky-50 border-sky-200 text-sky-700',
          ring: 'ring-sky-500/20',
          btnConfirm: 'bg-slate-900 hover:bg-slate-800 text-white shadow-slate-900/30',
        };
      case 'warning':
      default:
        return {
          icon: <AlertTriangle size={28} className="text-amber-500" />,
          bg: 'bg-amber-50 border-amber-200 text-amber-700',
          ring: 'ring-amber-500/20',
          btnConfirm: 'bg-amber-600 hover:bg-amber-700 text-white shadow-amber-600/30',
        };
    }
  };

  const styleConfig = modalState ? getIconAndColors(modalState.type) : null;

  return (
    <ConfirmContext.Provider value={{ confirm, alertModal }}>
      {children}

      {modalState && typeof document !== 'undefined' && createPortal(
        <div
          className="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6 bg-slate-950/70 backdrop-blur-md animate-fade-in transition-all"
          onClick={() => !modalState.isAlertOnly && handleClose(false)}
        >
          <div
            className="relative w-full max-w-md bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-7 shadow-2xl overflow-hidden text-left animate-scale-up"
            onClick={(e) => e.stopPropagation()}
          >
            {/* Soft decorative background glow */}
            <div className="absolute -right-12 -top-12 w-40 h-40 bg-slate-100 rounded-full blur-2xl pointer-events-none" />

            {/* Close Button */}
            <button
              type="button"
              onClick={() => handleClose(false)}
              className="absolute right-5 top-5 p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-full transition-colors cursor-pointer"
            >
              <X size={18} />
            </button>

            {/* Icon Header */}
            <div className="flex items-start gap-4 mb-4">
              <div
                className={`w-13 h-13 rounded-2xl flex items-center justify-center shrink-0 border ring-4 ${styleConfig.bg} ${styleConfig.ring} shadow-xs`}
              >
                {styleConfig.icon}
              </div>

              <div className="pt-1 pr-6">
                <h3 className="text-lg font-extrabold text-slate-900 leading-snug">
                  {modalState.title || 'Konfirmasi Tindakan'}
                </h3>
                {modalState.badge && (
                  <span className="inline-block mt-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                    {modalState.badge}
                  </span>
                )}
              </div>
            </div>

            {/* Message Body */}
            <div className="text-slate-600 text-xs sm:text-sm leading-relaxed mb-6 pl-0.5">
              {modalState.message}
            </div>

            {/* Action Buttons */}
            <div className="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
              {!modalState.isAlertOnly && (
                <button
                  type="button"
                  onClick={() => handleClose(false)}
                  className="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all active:scale-95 cursor-pointer"
                >
                  {modalState.cancelText || 'Batal'}
                </button>
              )}

              <button
                type="button"
                autoFocus
                onClick={() => handleClose(true)}
                className={`px-5 py-2.5 rounded-xl text-xs font-bold transition-all shadow-md active:scale-95 cursor-pointer flex items-center gap-1.5 ${styleConfig.btnConfirm}`}
              >
                {modalState.type === 'success' && <CheckCircle2 size={15} />}
                <span>{modalState.confirmText || (modalState.isAlertOnly ? 'Tutup' : 'Ya, Lanjutkan')}</span>
              </button>
            </div>
          </div>
        </div>,
        document.body
      )}
    </ConfirmContext.Provider>
  );
};

export const useConfirm = () => {
  const context = useContext(ConfirmContext);
  if (!context) {
    throw new Error('useConfirm must be used within a ConfirmProvider');
  }
  return context;
};

export default ConfirmContext;
