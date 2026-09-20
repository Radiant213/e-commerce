import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { ArrowLeft, Mail } from 'lucide-react';
import authApi from '@shared/api/auth';

const ForgotPasswordPage = () => {
  const [email, setEmail] = useState('');
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [message, setMessage] = useState(null);
  const [error, setError] = useState(null);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setIsSubmitting(true);
    setError(null);
    setMessage(null);

    try {
      const response = await authApi.forgotPassword(email);
      setMessage(response.message);
    } catch (err) {
      setError(err.message || 'Terjadi kesalahan saat mengirim link reset.');
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div className="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
      <div className="max-w-md w-full space-y-8 bg-white p-8 rounded-3xl shadow-xs border border-slate-100">
        <div>
          <h2 className="mt-2 text-center text-3xl font-extrabold text-slate-900">
            Lupa Kata Sandi
          </h2>
          <p className="mt-4 text-center text-sm text-slate-500">
            Masukkan email yang terdaftar untuk menerima tautan reset kata sandi.
          </p>
        </div>

        {error && (
          <div className="p-4 bg-red-50 text-red-700 text-sm rounded-2xl border border-red-100">
            {error}
          </div>
        )}

        {message && (
          <div className="p-4 bg-emerald-50 text-emerald-700 text-sm rounded-2xl border border-emerald-100">
            {message}
          </div>
        )}

        <form className="mt-8 space-y-6" onSubmit={handleSubmit}>
          <div>
            <label htmlFor="email" className="block text-sm font-bold text-slate-700 mb-2">
              Email Address
            </label>
            <div className="relative">
              <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                <Mail size={18} />
              </div>
              <input
                id="email"
                name="email"
                type="email"
                autoComplete="email"
                required
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                className="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:bg-white focus:outline-none focus:border-slate-900 transition-colors"
                placeholder="nama@email.com"
              />
            </div>
          </div>

          <div>
            <button
              type="submit"
              disabled={isSubmitting || !email}
              className="w-full flex justify-center py-3 px-4 border border-transparent rounded-2xl shadow-sm text-sm font-extrabold text-white bg-slate-900 hover:bg-slate-800 disabled:bg-slate-400 transition-colors active:scale-[0.98]"
            >
              {isSubmitting ? 'Mengirim...' : 'Kirim Tautan Reset'}
            </button>
          </div>
        </form>

        <div className="text-center mt-6">
          <Link to="/login" className="inline-flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-slate-900 transition-colors">
            <ArrowLeft size={16} />
            Kembali ke Halaman Login
          </Link>
        </div>
      </div>
    </div>
  );
};

export default ForgotPasswordPage;
