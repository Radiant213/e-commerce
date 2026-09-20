import React, { useState, useEffect } from 'react';
import { Link, useNavigate, useSearchParams } from 'react-router-dom';
import { Lock, Mail, UserCheck, ArrowRight } from 'lucide-react';
import { useAuth } from '@shared/context/AuthContext';
import { useLanguage } from '@shared/context/LanguageContext';
import GoogleLoginButton from '../components/GoogleLoginButton';

const LoginPage = () => {
  const { t } = useLanguage();
  const navigate = useNavigate();
  const [searchParams] = useSearchParams();
  const { login } = useAuth();

  const redirect = searchParams.get('redirect') || '/';

  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const [errorMessage, setErrorMessage] = useState('');
  const [successMessage, setSuccessMessage] = useState('');

  useEffect(() => {
    const token = searchParams.get('token');
    const error = searchParams.get('error');
    const verified = searchParams.get('verified');

    if (verified) {
      setSuccessMessage('Email Anda telah berhasil diverifikasi! Silakan login.');
    }

    if (error) {
      setErrorMessage(decodeURIComponent(error));
    }

    if (token) {
      localStorage.setItem('auth_token', token);
      window.location.href = redirect;
    }
  }, [searchParams]);

  const handleLoginSubmit = async (e) => {
    e.preventDefault();
    setIsLoading(true);
    setErrorMessage('');

    try {
      const res = await login({ email, password });
      
      // If admin, redirect directly to Filament Admin Panel
      if (res?.user?.role === 'admin') {
        const apiUrl = import.meta.env.VITE_API_URL || 'https://api-ecommerce.radiantcode.web.id/api';
        const adminUrl = apiUrl.replace(/\/api\/?$/, '/admin');
        window.location.href = adminUrl;
        return;
      }

      navigate(redirect);
    } catch (err) {
      setErrorMessage(err.message || 'Email atau password salah.');
    } finally {
      setIsLoading(false);
    }
  };

  const fillDemoAccount = (demoEmail, demoPass) => {
    setEmail(demoEmail);
    setPassword(demoPass);
  };

  return (
    <div className="min-h-[75vh] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
      <div className="max-w-md w-full space-y-6 bg-white border border-slate-200 rounded-3xl p-8 shadow-xs text-left">
        {/* Header */}
        <div className="text-center">
          <div className="w-12 h-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-extrabold text-xl mx-auto mb-3 shadow-md">
            R
          </div>
          <h2 className="text-2xl font-extrabold text-slate-900 tracking-tight">{t('auth_login_title')}</h2>
          <p className="text-xs text-slate-500 mt-1">
            {t('auth_login_desc')}
          </p>
        </div>

        {/* Google OAuth Login Button */}
        <div className="space-y-4 pt-2">
          <GoogleLoginButton onError={(msg) => setErrorMessage(msg)} />

          <div className="relative flex items-center justify-center">
            <div className="border-t border-slate-200 w-full" />
            <span className="bg-white px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider relative z-10 whitespace-nowrap">
              {t('auth_or_divider')}
            </span>
          </div>
        </div>

        {/* Demo Fast Login Buttons */}
        <div className="bg-slate-50 border border-slate-200/80 rounded-2xl p-3.5 space-y-2">
          <span className="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">
            {t('auth_demo_title')}
          </span>
          <div className="grid grid-cols-2 gap-2">
            <button
              type="button"
              onClick={() => fillDemoAccount('budi@example.com', 'password123')}
              className="px-2.5 py-1.5 bg-white border border-slate-200 hover:border-slate-400 rounded-xl text-xs font-bold text-slate-800 text-left transition-colors flex items-center gap-1.5 shadow-2xs"
            >
              <UserCheck size={14} className="text-emerald-700" />
              <span>Customer</span>
            </button>
            <button
              type="button"
              onClick={() => fillDemoAccount('admin@radiantcode.web.id', 'password123')}
              className="px-2.5 py-1.5 bg-white border border-slate-200 hover:border-slate-400 rounded-xl text-xs font-bold text-slate-800 text-left transition-colors flex items-center gap-1.5 shadow-2xs"
            >
              <UserCheck size={14} className="text-indigo-700" />
              <span>Admin Studio</span>
            </button>
          </div>
        </div>

        {successMessage && (
          <div className="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-xl font-medium">
            {successMessage}
          </div>
        )}

        {errorMessage && (
          <div className="p-3 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl">
            {errorMessage}
          </div>
        )}

        {/* Form */}
        <form onSubmit={handleLoginSubmit} className="space-y-4">

          <div>
            <label className="block text-xs font-semibold text-slate-700 mb-1">{t('auth_email')}</label>
            <div className="relative">
              <Mail className="absolute left-3 top-2.5 text-slate-400" size={16} />
              <input
                type="email"
                required
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                placeholder="nama@email.com"
                className="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:bg-white focus:outline-none focus:border-slate-900"
              />
            </div>
          </div>

          <div>
            <label className="block text-xs font-semibold text-slate-700 mb-1">{t('auth_password')}</label>
            <div className="relative">
              <Lock className="absolute left-3 top-2.5 text-slate-400" size={16} />
              <input
                type="password"
                required
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                placeholder="••••••••"
                className="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:bg-white focus:outline-none focus:border-slate-900"
              />
            </div>
            <div className="flex justify-end mt-1">
              <Link to="/forgot-password" className="text-[11px] font-semibold text-slate-500 hover:text-slate-900 transition-colors">
                Lupa Kata Sandi?
              </Link>
            </div>
          </div>

          <button
            type="submit"
            disabled={isLoading}
            className="w-full py-3 px-4 bg-slate-900 hover:bg-slate-800 disabled:bg-slate-400 text-white font-semibold text-xs rounded-lg flex items-center justify-center gap-2 shadow-sm transition-colors mt-2"
          >
            <span>{isLoading ? 'Processing...' : t('auth_login_btn')}</span>
            <ArrowRight size={14} />
          </button>
        </form>

        <div className="text-center text-xs text-slate-500 pt-2">
          {t('auth_no_account')}{' '}
          <Link to="/register" className="font-semibold text-slate-900 underline hover:text-emerald-800">
            {t('auth_register_link')}
          </Link>
        </div>
      </div>
    </div>
  );
};

export default LoginPage;
