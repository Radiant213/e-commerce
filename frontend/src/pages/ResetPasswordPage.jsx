import React, { useState, useEffect } from 'react';
import { Link, useNavigate, useSearchParams } from 'react-router-dom';
import { Lock, Eye, EyeOff } from 'lucide-react';
import authApi from '@shared/api/auth';
import { useToast } from '../components/Toast';

const ResetPasswordPage = () => {
  const [searchParams] = useSearchParams();
  const navigate = useNavigate();
  const { showToast } = useToast();
  
  const token = searchParams.get('token');
  const email = searchParams.get('email');

  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');
  const [showPassword, setShowPassword] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [error, setError] = useState(null);

  useEffect(() => {
    if (!token || !email) {
      setError('Tautan reset kata sandi tidak valid. Silakan minta tautan baru.');
    }
  }, [token, email]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    if (password !== passwordConfirmation) {
      setError('Konfirmasi kata sandi tidak cocok.');
      return;
    }

    if (password.length < 8) {
      setError('Kata sandi minimal 8 karakter.');
      return;
    }

    setIsSubmitting(true);
    setError(null);

    try {
      const response = await authApi.resetPassword({
        token,
        email,
        password,
        password_confirmation: passwordConfirmation
      });
      
      showToast(response.message, 'success');
      navigate('/login');
    } catch (err) {
      setError(err.message || 'Terjadi kesalahan saat mengatur ulang kata sandi.');
    } finally {
      setIsSubmitting(false);
    }
  };

  if (!token || !email) {
    return (
      <div className="min-h-[80vh] flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div className="max-w-md w-full space-y-8 bg-white p-8 rounded-3xl shadow-xs border border-slate-100 text-center">
          <div className="text-red-500 font-bold mb-4">Tautan Tidak Valid</div>
          <p className="text-sm text-slate-600 mb-6">Tautan reset kata sandi tidak valid atau tidak lengkap. Silakan minta tautan baru.</p>
          <Link to="/forgot-password" className="inline-block py-2.5 px-6 bg-slate-900 text-white font-bold rounded-xl text-sm">
            Minta Tautan Baru
          </Link>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
      <div className="max-w-md w-full space-y-8 bg-white p-8 rounded-3xl shadow-xs border border-slate-100">
        <div>
          <h2 className="mt-2 text-center text-3xl font-extrabold text-slate-900">
            Buat Kata Sandi Baru
          </h2>
          <p className="mt-4 text-center text-sm text-slate-500">
            Silakan masukkan kata sandi baru untuk akun <strong>{email}</strong>
          </p>
        </div>

        {error && (
          <div className="p-4 bg-red-50 text-red-700 text-sm rounded-2xl border border-red-100">
            {error}
          </div>
        )}

        <form className="mt-8 space-y-6" onSubmit={handleSubmit}>
          <div className="space-y-4">
            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">
                Kata Sandi Baru
              </label>
              <div className="relative">
                <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                  <Lock size={18} />
                </div>
                <input
                  type={showPassword ? "text" : "password"}
                  required
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  className="w-full pl-11 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:bg-white focus:outline-none focus:border-slate-900 transition-colors"
                  placeholder="Minimal 8 karakter"
                />
                <button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  className="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600"
                >
                  {showPassword ? <EyeOff size={18} /> : <Eye size={18} />}
                </button>
              </div>
            </div>

            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2">
                Konfirmasi Kata Sandi Baru
              </label>
              <div className="relative">
                <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                  <Lock size={18} />
                </div>
                <input
                  type={showPassword ? "text" : "password"}
                  required
                  value={passwordConfirmation}
                  onChange={(e) => setPasswordConfirmation(e.target.value)}
                  className="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:bg-white focus:outline-none focus:border-slate-900 transition-colors"
                  placeholder="Ketik ulang kata sandi baru"
                />
              </div>
            </div>
          </div>

          <div>
            <button
              type="submit"
              disabled={isSubmitting || !password || !passwordConfirmation}
              className="w-full flex justify-center py-3 px-4 border border-transparent rounded-2xl shadow-sm text-sm font-extrabold text-white bg-slate-900 hover:bg-slate-800 disabled:bg-slate-400 transition-colors active:scale-[0.98]"
            >
              {isSubmitting ? 'Menyimpan...' : 'Simpan Kata Sandi'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default ResetPasswordPage;
