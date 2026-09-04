import React, { useEffect, useState, useRef } from 'react';
import { useNavigate, useSearchParams } from 'react-router-dom';
import { useAuth } from '@shared/context/AuthContext';
import { useLanguage } from '@shared/context/LanguageContext';
import authApi from '@shared/api/auth';
import { Loader2 } from 'lucide-react';

const GOOGLE_CLIENT_ID = '477329625428-mrlq6nal54u6a8rj8018cjj1ele70t1e.apps.googleusercontent.com';

const GoogleLoginButton = ({ onError, onSuccess }) => {
  const { t } = useLanguage();
  const { loginWithGoogle } = useAuth();
  const navigate = useNavigate();
  const [searchParams] = useSearchParams();
  const redirect = searchParams.get('redirect') || '/';

  const [isLoading, setIsLoading] = useState(false);
  const tokenClientRef = useRef(null);
  const popupTimerRef = useRef(null);

  useEffect(() => {
    // Listen for window focus to detect when popup is closed/cancelled by user
    const handleWindowFocus = () => {
      // If user came back to main window, clear any stuck loading state after 1.5s
      if (popupTimerRef.current) clearTimeout(popupTimerRef.current);
      popupTimerRef.current = setTimeout(() => {
        setIsLoading(false);
      }, 1200);
    };

    window.addEventListener('focus', handleWindowFocus);

    // Dynamically load Google Identity Services client script
    const loadGIS = () => {
      if (window.google?.accounts?.oauth2) {
        initTokenClient();
        return;
      }

      const existingScript = document.getElementById('google-gis-script');
      if (existingScript) {
        existingScript.onload = () => initTokenClient();
        return;
      }

      const script = document.createElement('script');
      script.id = 'google-gis-script';
      script.src = 'https://accounts.google.com/gsi/client';
      script.async = true;
      script.defer = true;
      script.onload = () => initTokenClient();
      script.onerror = () => {
        console.warn('Failed to load Google GIS script.');
      };
      document.body.appendChild(script);
    };

    loadGIS();

    return () => {
      window.removeEventListener('focus', handleWindowFocus);
      if (popupTimerRef.current) clearTimeout(popupTimerRef.current);
    };
  }, []);

  const initTokenClient = () => {
    if (!window.google?.accounts?.oauth2) return;

    try {
      tokenClientRef.current = window.google.accounts.oauth2.initTokenClient({
        client_id: GOOGLE_CLIENT_ID,
        scope: 'email profile openid',
        callback: async (tokenResponse) => {
          setIsLoading(false);
          if (popupTimerRef.current) clearTimeout(popupTimerRef.current);

          if (tokenResponse?.error) {
            console.warn('Google OAuth response error:', tokenResponse.error);
            if (tokenResponse.error !== 'popup_closed_by_user' && tokenResponse.error !== 'access_denied') {
              if (onError) onError('Login dibatalkan atau terjadi kesalahan otorisasi.');
            }
            return;
          }

          if (tokenResponse?.access_token) {
            await handleAccessToken(tokenResponse.access_token);
          }
        },
        error_callback: (error) => {
          setIsLoading(false);
          if (popupTimerRef.current) clearTimeout(popupTimerRef.current);
          console.warn('Google OAuth error_callback:', error);
          if (error?.type === 'popup_closed') {
            // User closed the popup, silently reset loading
            return;
          }
          if (onError) {
            onError(error?.message || 'Login Google dibatalkan.');
          }
        },
      });
    } catch (err) {
      console.error('Failed initializing Google Token Client:', err);
    }
  };

  const handleAccessToken = async (accessToken) => {
    setIsLoading(true);
    try {
      const res = await loginWithGoogle({ access_token: accessToken });

      if (onSuccess) onSuccess(res);

      // If user is admin, redirect to Filament admin panel
      if (res?.user?.role === 'admin' || res?.redirect_url) {
        const apiUrl = import.meta.env.VITE_API_URL || 'https://api-ecommerce.radiantcode.web.id/api';
        const adminUrl = res?.redirect_url || apiUrl.replace(/\/api\/?$/, '/admin');
        window.location.href = adminUrl;
        return;
      }

      navigate(redirect);
    } catch (err) {
      console.error('Google token verification error:', err);
      const msg = err.response?.data?.message || err.message || 'Verifikasi login Google gagal.';
      if (onError) onError(msg);
    } finally {
      setIsLoading(false);
    }
  };

  const handleButtonClick = async () => {
    if (isLoading) return;

    // 1. Try Google Token Client Popup Flow (Opens the standard Google Popup)
    if (window.google?.accounts?.oauth2) {
      if (!tokenClientRef.current) {
        initTokenClient();
      }

      if (tokenClientRef.current) {
        setIsLoading(true);
        try {
          // Request access token - triggers the Google account chooser popup!
          tokenClientRef.current.requestAccessToken({ prompt: 'select_account' });
        } catch (err) {
          console.error('Token client request error:', err);
          setIsLoading(false);
          fallbackToRedirectFlow();
        }
        return;
      }
    }

    // 2. Fallback to server-side Google OAuth Redirect Flow
    fallbackToRedirectFlow();
  };

  const fallbackToRedirectFlow = async () => {
    setIsLoading(true);
    try {
      const authUrl = await authApi.getGoogleRedirectUrl();
      if (authUrl) {
        window.location.href = authUrl;
      } else {
        throw new Error('URL Google OAuth tidak ditemukan.');
      }
    } catch (err) {
      console.error('Fallback redirect error:', err);
      setIsLoading(false);
      if (onError) onError(err.response?.data?.message || err.message || 'Gagal membuka halaman login Google.');
    }
  };

  return (
    <button
      type="button"
      onClick={handleButtonClick}
      disabled={isLoading}
      className="w-full py-3 px-4 bg-white hover:bg-slate-50 active:bg-slate-100 disabled:opacity-60 border border-slate-300 hover:border-slate-400 text-slate-700 font-bold text-xs rounded-xl shadow-xs hover:shadow-md transition-all flex items-center justify-center gap-3 group active:scale-[0.99] cursor-pointer"
    >
      {isLoading ? (
        <Loader2 size={16} className="animate-spin text-slate-500" />
      ) : (
        <svg className="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24">
          <path
            fill="#4285F4"
            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
          />
          <path
            fill="#34A853"
            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
          />
          <path
            fill="#FBBC05"
            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"
          />
          <path
            fill="#EA4335"
            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"
          />
        </svg>
      )}
      <span>{isLoading ? t('auth_google_loading') : t('auth_google_btn')}</span>
    </button>
  );
};

export default GoogleLoginButton;
