import React, { createContext, useContext, useState, useCallback } from 'react';
import { CheckCircle2, AlertCircle, Info, X, ShoppingBag } from 'lucide-react';

const ToastContext = createContext(null);

export const ToastProvider = ({ children }) => {
  const [toasts, setToasts] = useState([]);

  const addToast = useCallback(({ title, message, type = 'success', action, duration = 4000 }) => {
    const id = Date.now() + Math.random();
    setToasts((prev) => [...prev, { id, title, message, type, action }]);

    if (duration > 0) {
      setTimeout(() => {
        setToasts((prev) => prev.filter((t) => t.id !== id));
      }, duration);
    }
  }, []);

  const removeToast = useCallback((id) => {
    setToasts((prev) => prev.filter((t) => t.id !== id));
  }, []);

  return (
    <ToastContext.Provider value={{ addToast, removeToast }}>
      {children}
      {/* Toast Container */}
      <div className="fixed bottom-5 right-5 z-50 flex flex-col gap-2.5 max-w-sm w-full pointer-events-none px-4 sm:px-0">
        {toasts.map((toast) => (
          <div
            key={toast.id}
            className="pointer-events-auto bg-slate-900 text-white rounded-xl p-4 shadow-2xl border border-slate-700/80 flex items-start gap-3 animate-slide-in-right transition-all"
          >
            {toast.type === 'success' && <CheckCircle2 size={20} className="text-emerald-400 flex-shrink-0 mt-0.5" />}
            {toast.type === 'error' && <AlertCircle size={20} className="text-red-400 flex-shrink-0 mt-0.5" />}
            {toast.type === 'info' && <Info size={20} className="text-sky-400 flex-shrink-0 mt-0.5" />}

            <div className="flex-1 text-left">
              {toast.title && <h4 className="text-xs font-bold text-white leading-tight">{toast.title}</h4>}
              {toast.message && <p className="text-xs text-slate-300 mt-0.5 leading-snug">{toast.message}</p>}
              {toast.action && (
                <button
                  onClick={() => {
                    toast.action.onClick();
                    removeToast(toast.id);
                  }}
                  className="mt-2 text-xs font-bold text-emerald-400 hover:text-emerald-300 underline inline-flex items-center gap-1"
                >
                  {toast.action.label}
                </button>
              )}
            </div>

            <button
              onClick={() => removeToast(toast.id)}
              className="text-slate-400 hover:text-white p-1 rounded-md transition-colors"
            >
              <X size={14} />
            </button>
          </div>
        ))}
      </div>
    </ToastContext.Provider>
  );
};

export const useToast = () => {
  const context = useContext(ToastContext);
  if (!context) {
    throw new Error('useToast must be used within a ToastProvider');
  }
  return context;
};

export default ToastContext;
