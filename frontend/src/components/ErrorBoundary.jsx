import React from 'react';

export default class ErrorBoundary extends React.Component {
  constructor(props) {
    super(props);
    this.state = { hasError: false, error: null };
  }

  static getDerivedStateFromError(error) {
    return { hasError: true, error };
  }

  componentDidCatch(error, errorInfo) {
    console.error('Unhandled UI Error caught by boundary:', error, errorInfo);
  }

  render() {
    if (this.state.hasError) {
      return this.props.fallback || (
        <div className="min-h-[40vh] flex flex-col items-center justify-center p-8 text-center">
          <div className="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-3">
            ⚠️
          </div>
          <h2 className="text-lg font-bold text-slate-900 mb-1">Terjadi Kendala Tampilan</h2>
          <p className="text-sm text-slate-500 mb-4 max-w-md">
            Komponen ini sedang mengalami kendala teknis sementara. Bagian lain dari toko tetap dapat digunakan normal.
          </p>
          <button
            onClick={() => this.setState({ hasError: false })}
            className="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-semibold hover:bg-slate-800 transition"
          >
            Muat Ulang Komponen
          </button>
        </div>
      );
    }

    return this.props.children;
  }
}
