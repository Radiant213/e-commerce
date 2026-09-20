import React, { useState, useEffect } from 'react';
import { createPortal } from 'react-dom';
import { Link, useNavigate, useSearchParams } from 'react-router-dom';
import {
  Package,
  Heart,
  ShoppingCart,
  User,
  Settings,
  CreditCard,
  CheckCircle2,
  Clock,
  Truck,
  ExternalLink,
  ShieldCheck,
  MapPin,
  LogOut,
  ChevronRight,
  Sparkles,
  Plus,
  Edit3,
  Trash2,
  Check,
  Lock,
  X,
  Phone,
  Home,
  Building2,
  MapPinned,
  Camera,
  UploadCloud,
  Image as ImageIcon,
  RefreshCw,
  Eye,
} from 'lucide-react';
import { useAuth } from '@shared/context/AuthContext';
import { useCart } from '@shared/context/CartContext';
import { useWishlist } from '@shared/context/WishlistContext';
import { useLanguage } from '@shared/context/LanguageContext';
import { useToast } from '../components/Toast';
import ordersApi from '@shared/api/orders';
import paymentsApi from '@shared/api/payments';
import addressesApi from '@shared/api/addresses';
import authApi from '@shared/api/auth';
import { formatCurrency } from '@shared/utils/formatCurrency';
import { formatDate } from '@shared/utils/formatDate';
import { payWithSnap } from '@shared/utils/midtransSnap';
import ProductCard from '../components/ProductCard';

const PRESET_AVATARS = [
  'https://api.dicebear.com/7.x/adventurer/svg?seed=RadiantHero&backgroundColor=b6e3f4',
  'https://api.dicebear.com/7.x/adventurer/svg?seed=RadiantQueen&backgroundColor=ffd5dc',
  'https://api.dicebear.com/7.x/lorelei/svg?seed=RadiantStar&backgroundColor=c0aede',
  'https://api.dicebear.com/7.x/lorelei/svg?seed=RadiantCool&backgroundColor=d1d4f9',
  'https://api.dicebear.com/7.x/bottts/svg?seed=RadiantHero&backgroundColor=ffdfbf',
  'https://api.dicebear.com/7.x/bottts/svg?seed=RadiantCyber&backgroundColor=b6e3f4',
  'https://api.dicebear.com/7.x/personas/svg?seed=RadiantPro&backgroundColor=ffd5dc',
  'https://api.dicebear.com/7.x/adventurer/svg?seed=RadiantGamer&backgroundColor=c0aede',
];

const DashboardPage = () => {
  const { t } = useLanguage();
  const navigate = useNavigate();
  const [searchParams, setSearchParams] = useSearchParams();
  const { user, isAuthenticated, logout, updateProfile, updateAvatar } = useAuth();
  const { totalItems, setIsDrawerOpen } = useCart();
  const { wishlistItems } = useWishlist();
  const { addToast } = useToast();

  const fileInputRef = React.useRef(null);
  const [isUploadingAvatar, setIsUploadingAvatar] = useState(false);

  const activeTab = searchParams.get('tab') || 'orders';
  const setActiveTab = (tab) => {
    setSearchParams({ tab });
  };

  // Orders State
  const [orders, setOrders] = useState([]);
  const [isLoadingOrders, setIsLoadingOrders] = useState(true);
  const [selectedReceipt, setSelectedReceipt] = useState(null);

  // Addresses State
  const [addresses, setAddresses] = useState([]);
  const [isLoadingAddresses, setIsLoadingAddresses] = useState(true);
  const [isAddressModalOpen, setIsAddressModalOpen] = useState(false);
  const [editingAddress, setEditingAddress] = useState(null);
  const [addressForm, setAddressForm] = useState({
    label: 'Rumah',
    recipient_name: '',
    phone: '',
    address_line: '',
    city: 'Jakarta Selatan',
    postal_code: '12190',
    is_primary: false,
    notes: '',
  });
  const [isSubmittingAddress, setIsSubmittingAddress] = useState(false);

  // Profile Form State
  const [profileForm, setProfileForm] = useState({
    name: user?.name || '',
    phone: user?.phone || '',
  });
  const [isUpdatingProfile, setIsUpdatingProfile] = useState(false);

  // Password Form State
  const [passwordForm, setPasswordForm] = useState({
    current_password: '',
    password: '',
    password_confirmation: '',
  });
  const [isUpdatingPassword, setIsUpdatingPassword] = useState(false);

  // Email Verification State
  const [isResendingEmail, setIsResendingEmail] = useState(false);

  useEffect(() => {
    if (user) {
      setProfileForm({
        name: user.name || '',
        phone: user.phone || '',
      });
    }
  }, [user]);

  const fetchOrders = async () => {
    setIsLoadingOrders(true);
    try {
      const res = await ordersApi.getOrders(1);
      setOrders(res.data || []);
    } catch (err) {
      console.error('Error loading dashboard orders:', err);
    } finally {
      setIsLoadingOrders(false);
    }
  };

  const fetchAddresses = async () => {
    setIsLoadingAddresses(true);
    try {
      const data = await addressesApi.getAddresses();
      setAddresses(data || []);
    } catch (err) {
      console.error('Error loading addresses:', err);
    } finally {
      setIsLoadingAddresses(false);
    }
  };

  useEffect(() => {
    if (isAuthenticated) {
      fetchOrders();
      fetchAddresses();
    }
  }, [isAuthenticated]);

  if (!isAuthenticated) {
    return (
      <div className="min-h-[70vh] flex items-center justify-center px-4">
        <div className="max-w-md w-full bg-white border border-slate-200 rounded-3xl p-8 text-center space-y-4 shadow-xl animate-fade-in">
          <div className="w-14 h-14 rounded-2xl bg-slate-900 text-white flex items-center justify-center mx-auto shadow-md">
            <User size={26} />
          </div>
          <h2 className="text-xl font-bold text-slate-900">Akses Dashboard Akun</h2>
          <p className="text-xs text-slate-500">
            Silakan masuk ke akun Anda untuk melihat ringkasan aktivitas, pesanan, alamat, dan wishlist Anda.
          </p>
          <Link
            to="/login?redirect=/dashboard"
            className="inline-block w-full py-3.5 px-4 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all shadow-md active:scale-95"
          >
            Masuk ke Akun Sekarang
          </Link>
        </div>
      </div>
    );
  }

  // Calculate metrics
  const totalSpent = orders
    .filter((o) => ['paid', 'processing', 'shipped', 'delivered'].includes(o.status))
    .reduce((sum, o) => sum + Number(o.total || 0), 0);

  const activeOrdersCount = orders.filter((o) => ['pending', 'processing', 'shipped'].includes(o.status)).length;
  const completedOrdersCount = orders.filter((o) => ['paid', 'delivered'].includes(o.status)).length;

  const handlePayOrder = async (orderId) => {
    try {
      const res = await paymentsApi.getSnapToken(orderId);
      await payWithSnap(res.snap_token, {
        onSuccess: () => {
          addToast({
            title: 'Pembayaran Berhasil!',
            message: 'Pesanan Anda telah berhasil dibayar dan akan segera diproses.',
            type: 'success',
          });
          fetchOrders();
        },
        onPending: () => fetchOrders(),
        onError: () => {
          addToast({
            title: 'Pembayaran Dibatalkan',
            message: 'Transaksi belum selesai.',
            type: 'error',
          });
          fetchOrders();
        },
        onClose: () => fetchOrders(),
      });
    } catch (err) {
      alert(err.message || 'Gagal memproses pembayaran.');
    }
  };

  const handleProfileSubmit = async (e) => {
    e.preventDefault();
    setIsUpdatingProfile(true);
    try {
      await updateProfile(profileForm);
      addToast({
        title: 'Profil Berhasil Diperbarui',
        message: 'Informasi akun Anda telah tersimpan dengan aman.',
        type: 'success',
      });
    } catch (err) {
      addToast({
        title: 'Gagal Memperbarui Profil',
        message: err.message || 'Terjadi kesalahan.',
        type: 'error',
      });
    } finally {
      setIsUpdatingProfile(false);
    }
  };

  const handleResendEmail = async () => {
    setIsResendingEmail(true);
    try {
      const response = await authApi.resendVerification();
      addToast({
        title: 'Email Terkirim',
        message: response.message,
        type: 'success',
      });
    } catch (err) {
      addToast({
        title: 'Gagal Mengirim Email',
        message: err.message || 'Terjadi kesalahan saat mengirim ulang email verifikasi.',
        type: 'error',
      });
    } finally {
      setIsResendingEmail(false);
    }
  };

  const handleAvatarFileUpload = async (e) => {
    const file = e.target.files?.[0];
    if (!file) return;

    if (!file.type.startsWith('image/')) {
      addToast({
        title: 'Format File Tidak Sesuai',
        message: 'Harap pilih file gambar (JPG, PNG, WEBP, GIF).',
        type: 'error',
      });
      return;
    }

    if (file.size > 5 * 1024 * 1024) {
      addToast({
        title: 'Ukuran Terlalu Besar',
        message: 'Maksimal ukuran foto adalah 5MB.',
        type: 'error',
      });
      return;
    }

    setIsUploadingAvatar(true);
    try {
      await updateAvatar(file);
      addToast({
        title: 'Foto Profil Diperbarui',
        message: 'Foto profil akun Anda berhasil diunggah.',
        type: 'success',
      });
    } catch (err) {
      addToast({
        title: 'Gagal Mengunggah Foto',
        message: err.response?.data?.message || err.message || 'Terjadi kesalahan saat unggah.',
        type: 'error',
      });
    } finally {
      setIsUploadingAvatar(false);
      if (fileInputRef.current) fileInputRef.current.value = '';
    }
  };

  const handleSelectPresetAvatar = async (url) => {
    setIsUploadingAvatar(true);
    try {
      await updateAvatar(url);
      addToast({
        title: 'Avatar Diperbarui',
        message: 'Avatar baru Anda telah aktif.',
        type: 'success',
      });
    } catch (err) {
      addToast({
        title: 'Gagal Memperbarui Avatar',
        message: err.message || 'Terjadi kesalahan.',
        type: 'error',
      });
    } finally {
      setIsUploadingAvatar(false);
    }
  };

  const handlePasswordSubmit = async (e) => {
    e.preventDefault();
    if (passwordForm.password !== passwordForm.password_confirmation) {
      addToast({
        title: 'Konfirmasi Sandi Gagal',
        message: 'Konfirmasi kata sandi baru tidak cocok.',
        type: 'error',
      });
      return;
    }

    setIsUpdatingPassword(true);
    try {
      await authApi.updatePassword(passwordForm);
      addToast({
        title: 'Kata Sandi Diperbarui',
        message: 'Kata sandi akun Anda berhasil diganti.',
        type: 'success',
      });
      setPasswordForm({ current_password: '', password: '', password_confirmation: '' });
    } catch (err) {
      addToast({
        title: 'Gagal Mengubah Sandi',
        message: err.response?.data?.message || err.message || 'Terjadi kesalahan.',
        type: 'error',
      });
    } finally {
      setIsUpdatingPassword(false);
    }
  };

  // Lock background scroll when address modal is open
  useEffect(() => {
    if (isAddressModalOpen) {
      document.body.style.overflow = 'hidden';
    } else {
      document.body.style.overflow = '';
    }
    return () => {
      document.body.style.overflow = '';
    };
  }, [isAddressModalOpen]);

  // Address Handlers
  const openNewAddressModal = () => {
    setEditingAddress(null);
    setAddressForm({
      label: 'Rumah',
      recipient_name: user?.name || '',
      phone: user?.phone || '',
      address_line: '',
      city: '',
      postal_code: '',
      is_primary: addresses.length === 0,
      notes: '',
    });
    setIsAddressModalOpen(true);
  };

  const openEditAddressModal = (addr) => {
    setEditingAddress(addr);
    setAddressForm({
      label: addr.label,
      recipient_name: addr.recipient_name,
      phone: addr.phone,
      address_line: addr.address_line,
      city: addr.city,
      postal_code: addr.postal_code,
      is_primary: addr.is_primary,
      notes: addr.notes || '',
    });
    setIsAddressModalOpen(true);
  };

  const handleAddressSubmit = async (e) => {
    e.preventDefault();
    setIsSubmittingAddress(true);
    try {
      if (editingAddress) {
        await addressesApi.updateAddress(editingAddress.id, addressForm);
        addToast({
          title: 'Alamat Berhasil Diperbarui',
          message: `Alamat "${addressForm.label}" berhasil disimpan.`,
          type: 'success',
        });
      } else {
        await addressesApi.createAddress(addressForm);
        addToast({
          title: 'Alamat Baru Ditambahkan',
          message: `Alamat "${addressForm.label}" berhasil dibuat.`,
          type: 'success',
        });
      }
      setIsAddressModalOpen(false);
      fetchAddresses();
    } catch (err) {
      addToast({
        title: 'Gagal Menyimpan Alamat',
        message: err.message || 'Terjadi kesalahan.',
        type: 'error',
      });
    } finally {
      setIsSubmittingAddress(false);
    }
  };

  const handleDeleteAddress = async (id, label) => {
    if (!window.confirm(`Apakah Anda yakin ingin menghapus alamat "${label}"?`)) return;
    try {
      await addressesApi.deleteAddress(id);
      addToast({
        title: 'Alamat Dihapus',
        message: `Alamat "${label}" telah dihapus.`,
        type: 'info',
      });
      fetchAddresses();
    } catch (err) {
      addToast({
        title: 'Gagal Menghapus Alamat',
        message: err.message || 'Terjadi kesalahan.',
        type: 'error',
      });
    }
  };

  const handleSetPrimaryAddress = async (id, label) => {
    try {
      await addressesApi.setPrimaryAddress(id);
      addToast({
        title: 'Alamat Utama Diubah',
        message: `Alamat "${label}" sekarang menjadi alamat pengiriman utama Anda.`,
        type: 'success',
      });
      fetchAddresses();
    } catch (err) {
      addToast({
        title: 'Gagal Mengatur Alamat Utama',
        message: err.message || 'Terjadi kesalahan.',
        type: 'error',
      });
    }
  };

  const getOrderStatusStep = (status) => {
    switch (status) {
      case 'pending': return 1;
      case 'paid':
      case 'processing': return 2;
      case 'shipped': return 3;
      case 'delivered': return 4;
      default: return 0;
    }
  };

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 text-left space-y-8 animate-fade-in">
      {!user?.email_verified_at && (
        <div className="bg-amber-50 border border-amber-200 rounded-3xl p-5 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
          <div className="flex items-center gap-3 text-amber-800">
            <ShieldCheck size={24} className="shrink-0" />
            <div>
              <p className="text-sm font-bold">Verifikasi Email Anda</p>
              <p className="text-xs">Silakan cek kotak masuk email Anda untuk memverifikasi akun agar dapat melakukan checkout.</p>
            </div>
          </div>
          <button
            onClick={handleResendEmail}
            disabled={isResendingEmail}
            className="shrink-0 px-4 py-2 bg-amber-600 hover:bg-amber-700 disabled:bg-amber-400 text-white rounded-xl text-xs font-bold transition-colors"
          >
            {isResendingEmail ? 'Mengirim...' : 'Kirim Ulang Email'}
          </button>
        </div>
      )}

      {/* 1. Header Profile Banner Card with Rich Micro-Animations */}
      <div className="relative bg-gradient-to-r from-slate-950 via-slate-900 to-slate-950 text-white rounded-3xl p-6 sm:p-8 shadow-2xl overflow-hidden border border-slate-800">
        <div className="absolute right-0 top-0 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none" />
        <div className="absolute -left-12 -bottom-12 w-64 h-64 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none" />

        <div className="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
          <div className="flex items-center gap-4 sm:gap-6">
            <div
              className="relative group cursor-pointer"
              onClick={() => {
                setActiveTab('profile');
                if (fileInputRef.current) fileInputRef.current.click();
              }}
              title="Klik untuk ubah foto profil"
            >
              {user?.avatar_url || user?.avatar ? (
                <img
                  src={user.avatar_url || user.avatar}
                  alt={user.name}
                  className="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover border-2 border-emerald-500/60 shadow-lg group-hover:scale-105 transition-transform"
                />
              ) : (
                <div className="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-slate-800 border-2 border-emerald-500/40 flex items-center justify-center text-2xl sm:text-3xl font-extrabold text-emerald-400 shadow-lg group-hover:scale-105 transition-transform">
                  {user?.name?.charAt(0) || 'U'}
                </div>
              )}
              <div className="absolute inset-0 bg-black/50 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white text-[10px] font-bold">
                <Camera size={18} className="mb-0.5" />
                <span>Ubah</span>
              </div>
              <span className="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-400 border-2 border-slate-950 rounded-full animate-pulse" title="Akun Aktif" />
            </div>

            <div className="space-y-1.5">
              <div className="flex flex-wrap items-center gap-2.5">
                <h1 className="text-xl sm:text-2xl font-extrabold text-white tracking-tight">{user?.name}</h1>
                <span className="px-2.5 py-0.5 bg-emerald-500/20 text-emerald-300 text-[10px] font-bold rounded-full border border-emerald-500/40 flex items-center gap-1">
                  <ShieldCheck size={11} />
                  MEMBER TERVERIFIKASI
                </span>
              </div>
              <p className="text-xs text-slate-300">{user?.email}</p>
              <p className="text-[11px] text-slate-400 flex items-center gap-1.5 pt-0.5">
                <Phone size={12} className="text-emerald-400" />
                <span>{user?.phone || 'Nomor WhatsApp belum diatur'}</span>
              </p>
            </div>
          </div>

          <div className="flex items-center gap-2.5">
            <button
              onClick={() => setActiveTab('profile')}
              className="px-4 py-2.5 bg-slate-800/90 hover:bg-slate-800 text-slate-200 rounded-xl text-xs font-bold transition-all border border-slate-700 hover:border-slate-500 flex items-center gap-1.5 shadow-xs active:scale-95"
            >
              <Settings size={14} />
              <span>{t('dash_tab_profile')}</span>
            </button>
            <button
              onClick={() => logout()}
              className="px-4 py-2.5 bg-red-500/20 hover:bg-red-500/30 text-red-300 rounded-xl text-xs font-bold transition-all border border-red-500/30 flex items-center gap-1.5 active:scale-95"
            >
              <LogOut size={14} />
              <span>{t('nav_logout')}</span>
            </button>
          </div>
        </div>
      </div>

      {/* 2. Stat Metric Cards with Hover Elevation */}
      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div className="group bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:shadow-xl hover:border-emerald-500/40 hover:-translate-y-1.5 transition-all duration-300">
          <div className="flex items-center justify-between text-slate-500 text-xs mb-3">
            <span className="font-bold uppercase tracking-wider text-[10px] text-slate-400">{t('dash_stat_spent')}</span>
            <div className="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center group-hover:scale-110 transition-transform">
              <CreditCard size={18} />
            </div>
          </div>
          <p className="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
            {formatCurrency(totalSpent)}
          </p>
          <p className="text-[11px] text-slate-400 mt-1">{completedOrdersCount} {t('dash_stat_completed')}</p>
        </div>

        <div className="group bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:shadow-xl hover:border-sky-500/40 hover:-translate-y-1.5 transition-all duration-300">
          <div className="flex items-center justify-between text-slate-500 text-xs mb-3">
            <span className="font-bold uppercase tracking-wider text-[10px] text-slate-400">{t('dash_stat_active')}</span>
            <div className="w-9 h-9 rounded-xl bg-sky-50 text-sky-700 flex items-center justify-center group-hover:scale-110 transition-transform">
              <Clock size={18} />
            </div>
          </div>
          <p className="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
            {activeOrdersCount}
          </p>
          <p className="text-[11px] text-slate-400 mt-1">{t('dash_status_processing')}</p>
        </div>

        <div className="group bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:shadow-xl hover:border-rose-500/40 hover:-translate-y-1.5 transition-all duration-300">
          <div className="flex items-center justify-between text-slate-500 text-xs mb-3">
            <span className="font-bold uppercase tracking-wider text-[10px] text-slate-400">{t('dash_tab_addresses')}</span>
            <div className="w-9 h-9 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center group-hover:scale-110 transition-transform">
              <MapPinned size={18} />
            </div>
          </div>
          <p className="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
            {addresses.length}
          </p>
          <button
            onClick={() => setActiveTab('addresses')}
            className="text-[11px] text-amber-700 hover:text-amber-900 font-bold mt-1 inline-flex items-center gap-1"
          >
            {t('chk_manage_addresses')} &rarr;
          </button>
        </div>

        <div className="group bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:shadow-xl hover:border-indigo-500/40 hover:-translate-y-1.5 transition-all duration-300">
          <div className="flex items-center justify-between text-slate-500 text-xs mb-3">
            <span className="font-bold uppercase tracking-wider text-[10px] text-slate-400">{t('cart_title')}</span>
            <div className="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center group-hover:scale-110 transition-transform">
              <ShoppingCart size={18} />
            </div>
          </div>
          <p className="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
            {totalItems} Item
          </p>
          <button
            onClick={() => setIsDrawerOpen(true)}
            className="text-[11px] text-indigo-600 hover:text-indigo-800 font-bold mt-1 inline-flex items-center gap-1"
          >
            {t('cart_title')} &rarr;
          </button>
        </div>
      </div>

      {/* 3. Interactive Modern Tab Navigator */}
      <div className="bg-white border border-slate-200/90 rounded-2xl p-1.5 shadow-xs flex items-center gap-1.5 overflow-x-auto">
        <button
          onClick={() => setActiveTab('orders')}
          className={`flex-1 min-w-[130px] py-2.5 px-4 rounded-xl text-xs font-bold tracking-wide transition-all flex items-center justify-center gap-2 ${activeTab === 'orders'
              ? 'bg-slate-900 text-white shadow-md'
              : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70'
            }`}
        >
          <Package size={15} />
          <span>{t('dash_tab_orders')} ({orders.length})</span>
        </button>

        <button
          onClick={() => setActiveTab('addresses')}
          className={`flex-1 min-w-[140px] py-2.5 px-4 rounded-xl text-xs font-bold tracking-wide transition-all flex items-center justify-center gap-2 ${activeTab === 'addresses'
              ? 'bg-slate-900 text-white shadow-md'
              : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70'
            }`}
        >
          <MapPin size={15} />
          <span>{t('dash_tab_addresses')} ({addresses.length})</span>
        </button>

        <button
          onClick={() => setActiveTab('wishlist')}
          className={`flex-1 min-w-[130px] py-2.5 px-4 rounded-xl text-xs font-bold tracking-wide transition-all flex items-center justify-center gap-2 ${activeTab === 'wishlist'
              ? 'bg-slate-900 text-white shadow-md'
              : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70'
            }`}
        >
          <Heart size={15} />
          <span>{t('dash_tab_wishlist')} ({wishlistItems.length})</span>
        </button>

        <button
          onClick={() => setActiveTab('profile')}
          className={`flex-1 min-w-[140px] py-2.5 px-4 rounded-xl text-xs font-bold tracking-wide transition-all flex items-center justify-center gap-2 ${activeTab === 'profile'
              ? 'bg-slate-900 text-white shadow-md'
              : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70'
            }`}
        >
          <User size={15} />
          <span>{t('dash_tab_profile')}</span>
        </button>
      </div>

      {/* 4. Tab Content */}
      <div className="pt-2">
        {/* ========================================================================= */}
        {/* TAB 1: ORDERS */}
        {/* ========================================================================= */}
        {activeTab === 'orders' && (
          <div className="space-y-6">
            {isLoadingOrders ? (
              <div className="space-y-4">
                {[1, 2].map((n) => (
                  <div key={n} className="animate-shimmer rounded-3xl p-6 h-56" />
                ))}
              </div>
            ) : orders.length === 0 ? (
              <div className="bg-white border border-slate-200 rounded-3xl p-12 text-center max-w-md mx-auto shadow-xs">
                <Package size={32} className="text-slate-400 mx-auto mb-3" />
                <h3 className="text-base font-bold text-slate-900">Belum Ada Riwayat Pesanan</h3>
                <p className="text-xs text-slate-500 mt-1 mb-6">Mulai belanja produk kebutuhan Anda hari ini.</p>
                <Link
                  to="/products"
                  className="px-6 py-3 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-all shadow-md active:scale-95 inline-block"
                >
                  Jelajahi Produk
                </Link>
              </div>
            ) : (
              orders.map((order) => {
                const currentStep = getOrderStatusStep(order.status);

                return (
                  <div
                    key={order.id}
                    className="bg-white border border-slate-200/90 rounded-3xl overflow-hidden shadow-xs hover:shadow-lg hover:border-slate-300 transition-all duration-300 space-y-4"
                  >
                    {/* Order Header */}
                    <div className="bg-slate-50/90 px-6 py-4 border-b border-slate-200/70 flex flex-wrap items-center justify-between gap-4 text-xs">
                      <div className="flex flex-wrap items-center gap-6">
                        <div>
                          <span className="text-slate-400 block text-[10px] uppercase font-bold">No. Pesanan</span>
                          <span className="font-mono font-extrabold text-slate-900">{order.order_number}</span>
                        </div>
                        <div>
                          <span className="text-slate-400 block text-[10px] uppercase font-bold">Tanggal</span>
                          <span className="text-slate-700 font-medium">{formatDate(order.created_at, true)}</span>
                        </div>
                        <div>
                          <span className="text-slate-400 block text-[10px] uppercase font-bold">Total Pembayaran</span>
                          <span className="font-extrabold text-slate-900">{formatCurrency(order.total)}</span>
                        </div>
                      </div>

                      <div>
                        {order.status === 'paid' && (
                          <span className="px-3.5 py-1 bg-emerald-50 text-emerald-800 text-xs font-bold rounded-full border border-emerald-200 inline-flex items-center gap-1.5">
                            <CheckCircle2 size={13} /> Lunas / Selesai
                          </span>
                        )}
                        {order.status === 'pending' && (
                          <span className="px-3.5 py-1 bg-amber-50 text-amber-800 text-xs font-bold rounded-full border border-amber-200 inline-flex items-center gap-1.5 animate-pulse">
                            <Clock size={13} /> Menunggu Pembayaran
                          </span>
                        )}
                        {order.status === 'processing' && (
                          <span className="px-3.5 py-1 bg-sky-50 text-sky-800 text-xs font-bold rounded-full border border-sky-200 inline-flex items-center gap-1.5">
                            <Package size={13} /> Diproses Penjual
                          </span>
                        )}
                        {order.status === 'shipped' && (
                          <span className="px-3.5 py-1 bg-indigo-50 text-indigo-800 text-xs font-bold rounded-full border border-indigo-200 inline-flex items-center gap-1.5">
                            <Truck size={13} /> Sedang Dikirim Kurir
                          </span>
                        )}
                        {order.status === 'cancelled' && (
                          <span className="px-3.5 py-1 bg-red-50 text-red-800 text-xs font-bold rounded-full border border-red-200">
                            Dibatalkan
                          </span>
                        )}
                      </div>
                    </div>

                    {/* Visual Progress Steps Tracker */}
                    {order.status !== 'cancelled' && (
                      <div className="px-6 pt-2 pb-4 border-b border-slate-100">
                        <div className="grid grid-cols-4 gap-3 text-center text-xs">
                          <div className="space-y-1.5">
                            <div className={`h-2 rounded-full transition-all ${currentStep >= 1 ? 'bg-emerald-600' : 'bg-slate-200'}`} />
                            <span className={`text-[11px] font-bold block ${currentStep >= 1 ? 'text-slate-900' : 'text-slate-400'}`}>
                              1. Dipesan
                            </span>
                          </div>
                          <div className="space-y-1.5">
                            <div className={`h-2 rounded-full transition-all ${currentStep >= 2 ? 'bg-emerald-600' : 'bg-slate-200'}`} />
                            <span className={`text-[11px] font-bold block ${currentStep >= 2 ? 'text-slate-900' : 'text-slate-400'}`}>
                              2. Dibayar
                            </span>
                          </div>
                          <div className="space-y-1.5">
                            <div className={`h-2 rounded-full transition-all ${currentStep >= 3 ? 'bg-emerald-600' : 'bg-slate-200'}`} />
                            <span className={`text-[11px] font-bold block ${currentStep >= 3 ? 'text-slate-900' : 'text-slate-400'}`}>
                              3. Dikirim
                            </span>
                          </div>
                          <div className="space-y-1.5">
                            <div className={`h-2 rounded-full transition-all ${currentStep >= 4 ? 'bg-emerald-600' : 'bg-slate-200'}`} />
                            <span className={`text-[11px] font-bold block ${currentStep >= 4 ? 'text-slate-900' : 'text-slate-400'}`}>
                              4. Diterima
                            </span>
                          </div>
                        </div>
                      </div>
                    )}

                    {/* Order Items */}
                    <div className="px-6 py-2 divide-y divide-slate-100">
                      {order.items?.map((item) => (
                        <div key={item.id} className="py-3 first:pt-0 last:pb-0 flex items-center justify-between text-xs">
                          <div className="flex items-center gap-3.5">
                            <img
                              src={item.product?.primary_image?.image_path || 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=100&q=80'}
                              alt={item.product_name}
                              className="w-12 h-12 rounded-xl object-cover border border-slate-200/60 bg-slate-50"
                            />
                            <div>
                              <h4 className="font-bold text-slate-900 line-clamp-1">{item.product_name}</h4>
                              <p className="text-slate-400 text-[11px]">
                                {item.quantity} x {formatCurrency(item.product_price)}
                              </p>
                            </div>
                          </div>
                          <span className="font-bold text-slate-900">
                            {formatCurrency(item.subtotal)}
                          </span>
                        </div>
                      ))}
                    </div>

                    {/* Footer Actions */}
                    <div className="bg-slate-50/60 px-6 py-3.5 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                      <div className="text-slate-500 text-[11px] space-y-1.5">
                        <div>
                          <span>Tujuan: <strong>{order.shipping_name}</strong> ({order.shipping_phone}) - {order.shipping_address}, {order.shipping_city}</span>
                        </div>

                        {/* Kurir, Resi & Bukti Foto Resi */}
                        {(order.courier_name || order.tracking_number || order.receipt_image_url || order.status === 'shipped' || order.status === 'delivered') && (
                          <div className="flex flex-wrap items-center gap-2 pt-1">
                            <span className="text-slate-800 font-medium inline-flex items-center gap-1.5 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                              <Truck size={13} className="text-slate-500" />
                              <span>Kurir: <strong>{order.courier_name || 'Menunggu input kurir'}</strong></span>
                            </span>

                            {order.tracking_number && (
                              <span className="text-slate-800 font-medium inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-800 px-2.5 py-1 rounded-lg border border-emerald-200">
                                <span>Resi:</span>
                                <strong className="font-mono font-bold tracking-wide">{order.tracking_number}</strong>
                              </span>
                            )}

                            {order.receipt_image_url && (
                              <button
                                type="button"
                                onClick={() => setSelectedReceipt({
                                  url: order.receipt_image_url,
                                  orderNumber: order.order_number,
                                  courier: order.courier_name,
                                  tracking: order.tracking_number,
                                })}
                                className="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all shadow-xs cursor-pointer active:scale-95"
                              >
                                <Eye size={13} />
                                <span>Lihat Foto Resi</span>
                              </button>
                            )}
                          </div>
                        )}
                      </div>

                      {order.status === 'pending' && (
                        <button
                          onClick={() => handlePayOrder(order.id)}
                          className="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all shadow-md flex items-center gap-2 active:scale-95 whitespace-nowrap self-start sm:self-auto"
                        >
                          <CreditCard size={14} />
                          <span>Bayar Sekarang (MidTrans)</span>
                        </button>
                      )}
                    </div>
                  </div>
                );
              })
            )}
          </div>
        )}

        {/* ========================================================================= */}
        {/* TAB 2: MULTI-ADDRESS BOOK (BUKU ALAMAT PENGIRIMAN) */}
        {/* ========================================================================= */}
        {activeTab === 'addresses' && (
          <div className="space-y-6">
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs">
              <div>
                <h3 className="text-base font-bold text-slate-900">Daftar Alamat Pengiriman</h3>
                <p className="text-xs text-slate-500 mt-0.5">
                  Kelola alamat rumah, kantor, atau tujuan lainnya untuk mempercepat proses checkout.
                </p>
              </div>
              <button
                onClick={openNewAddressModal}
                className="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all shadow-md flex items-center justify-center gap-2 active:scale-95"
              >
                <Plus size={15} />
                <span>Tambah Alamat Baru</span>
              </button>
            </div>

            {isLoadingAddresses ? (
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                {[1, 2].map((n) => (
                  <div key={n} className="animate-shimmer rounded-2xl p-6 h-48" />
                ))}
              </div>
            ) : addresses.length === 0 ? (
              <div className="bg-white border border-slate-200 rounded-3xl p-12 text-center max-w-md mx-auto shadow-xs">
                <MapPin size={32} className="text-slate-400 mx-auto mb-3" />
                <h3 className="text-base font-bold text-slate-900">Belum Ada Alamat Tersimpan</h3>
                <p className="text-xs text-slate-500 mt-1 mb-6">Tambahkan alamat pertama Anda untuk mempermudah pengiriman barang.</p>
                <button
                  onClick={openNewAddressModal}
                  className="px-6 py-3 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-all shadow-md active:scale-95"
                >
                  + Tambah Alamat Sekarang
                </button>
              </div>
            ) : (
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                {addresses.map((addr) => (
                  <div
                    key={addr.id}
                    className={`relative bg-white rounded-3xl p-6 border-2 transition-all duration-300 flex flex-col justify-between shadow-xs hover:shadow-lg ${addr.is_primary ? 'border-emerald-700 bg-emerald-50/10' : 'border-slate-200 hover:border-slate-400'
                      }`}
                  >
                    <div>
                      {/* Address Header */}
                      <div className="flex items-center justify-between gap-2 mb-3">
                        <div className="flex items-center gap-2">
                          <span className="px-2.5 py-1 bg-slate-100 text-slate-800 text-xs font-bold rounded-lg flex items-center gap-1.5">
                            {addr.label.toLowerCase().includes('kantor') ? <Building2 size={13} /> : <Home size={13} />}
                            {addr.label}
                          </span>
                          {addr.is_primary && (
                            <span className="px-2.5 py-1 bg-emerald-700 text-white text-[10px] font-bold rounded-lg tracking-wide shadow-xs flex items-center gap-1">
                              <Check size={11} /> ALAMAT UTAMA
                            </span>
                          )}
                        </div>

                        {/* Action buttons (Edit & Delete) */}
                        <div className="flex items-center gap-1">
                          <button
                            onClick={() => openEditAddressModal(addr)}
                            className="p-1.5 text-slate-400 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors"
                            title="Edit Alamat"
                          >
                            <Edit3 size={15} />
                          </button>
                          <button
                            onClick={() => handleDeleteAddress(addr.id, addr.label)}
                            className="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                            title="Hapus Alamat"
                          >
                            <Trash2 size={15} />
                          </button>
                        </div>
                      </div>

                      {/* Details */}
                      <div className="space-y-1 text-xs text-slate-700">
                        <p className="font-extrabold text-sm text-slate-900">{addr.recipient_name}</p>
                        <p className="text-slate-500 font-medium">{addr.phone}</p>
                        <p className="text-slate-800 leading-relaxed pt-1">{addr.address_line}</p>
                        <p className="text-slate-600 font-medium">{addr.city}, Kode Pos {addr.postal_code}</p>
                        {addr.notes && (
                          <p className="text-[11px] text-slate-400 italic pt-1">Catatan: "{addr.notes}"</p>
                        )}
                      </div>
                    </div>

                    {/* Bottom Primary Toggle */}
                    <div className="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                      {!addr.is_primary ? (
                        <button
                          onClick={() => handleSetPrimaryAddress(addr.id, addr.label)}
                          className="px-3.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 rounded-xl text-xs font-bold transition-all active:scale-95 flex items-center gap-1.5 shadow-xs"
                        >
                          <Check size={13} strokeWidth={2.5} />
                          <span>Jadikan Alamat Utama</span>
                        </button>
                      ) : (
                        <span className="text-[11px] font-bold text-emerald-700 flex items-center gap-1.5 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200">
                          <CheckCircle2 size={14} /> Digunakan saat checkout otomatis
                        </span>
                      )}
                    </div>
                  </div>
                ))}
              </div>
            )}
          </div>
        )}

        {/* ========================================================================= */}
        {/* TAB 3: WISHLIST */}
        {/* ========================================================================= */}
        {activeTab === 'wishlist' && (
          <div>
            {wishlistItems.length === 0 ? (
              <div className="bg-white border border-slate-200 rounded-3xl p-12 text-center max-w-md mx-auto shadow-xs">
                <Heart size={32} className="text-slate-400 mx-auto mb-3" />
                <h3 className="text-base font-bold text-slate-900">Wishlist Masih Kosong</h3>
                <p className="text-xs text-slate-500 mt-1 mb-6">Simpan barang favorit Anda untuk dibeli nanti.</p>
                <Link
                  to="/products"
                  className="px-6 py-3 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-all shadow-md active:scale-95 inline-block"
                >
                  Mulai Belanja
                </Link>
              </div>
            ) : (
              <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                {wishlistItems.map((item) => (
                  <ProductCard key={item.id} product={item.product} />
                ))}
              </div>
            )}
          </div>
        )}

        {/* ========================================================================= */}
        {/* TAB 4: PROFILE & SECURITY SETTINGS */}
        {/* ========================================================================= */}
        {activeTab === 'profile' && (
          <div className="space-y-8">
            {/* Card 0: Avatar Customizer Card */}
            <div className="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
              <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                  <h3 className="text-base font-extrabold text-slate-900 flex items-center gap-2">
                    <Camera size={18} className="text-emerald-700" />
                    <span>{t('dash_avatar_title')}</span>
                  </h3>
                  <p className="text-xs text-slate-500 mt-0.5">
                    Unggah foto dari perangkat atau pilih avatar karakter sesuai gaya Anda.
                  </p>
                </div>
                {isUploadingAvatar && (
                  <div className="flex items-center gap-2 text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200 animate-pulse">
                    <RefreshCw size={14} className="animate-spin" />
                    <span>Memperbarui Avatar...</span>
                  </div>
                )}
              </div>

              <div className="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                {/* Current Avatar Large Preview & Upload Trigger */}
                <div className="md:col-span-4 flex flex-col items-center text-center p-5 bg-slate-50 border border-slate-200/80 rounded-2xl space-y-3">
                  <div className="relative group">
                    {user?.avatar_url || user?.avatar ? (
                      <img
                        src={user.avatar_url || user.avatar}
                        alt={user.name}
                        className="w-24 h-24 rounded-2xl object-cover border-4 border-white shadow-md group-hover:scale-105 transition-transform"
                      />
                    ) : (
                      <div className="w-24 h-24 rounded-2xl bg-slate-900 text-white flex items-center justify-center text-3xl font-extrabold shadow-md">
                        {user?.name?.charAt(0) || 'U'}
                      </div>
                    )}
                  </div>

                  <input
                    type="file"
                    ref={fileInputRef}
                    onChange={handleAvatarFileUpload}
                    accept="image/jpeg,image/png,image/webp,image/gif"
                    className="hidden"
                  />

                  <button
                    type="button"
                    onClick={() => fileInputRef.current?.click()}
                    disabled={isUploadingAvatar}
                    className="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-800 active:scale-95 text-white font-bold text-xs rounded-xl shadow-xs transition-all flex items-center justify-center gap-2"
                  >
                    <UploadCloud size={15} />
                    <span>{t('dash_avatar_upload_btn')}</span>
                  </button>
                  <span className="text-[10px] text-slate-400">JPG, PNG, WEBP maks 5MB</span>
                </div>

                {/* Preset Avatars Grid */}
                <div className="md:col-span-8 space-y-3">
                  <span className="text-xs font-bold text-slate-700 block">
                    {t('dash_avatar_preset_title')}
                  </span>
                  <div className="grid grid-cols-4 sm:grid-cols-4 gap-3">
                    {PRESET_AVATARS.map((avatarUrl, idx) => {
                      const isSelected = (user?.avatar_url === avatarUrl) || (user?.avatar === avatarUrl);
                      return (
                        <button
                          key={idx}
                          type="button"
                          onClick={() => handleSelectPresetAvatar(avatarUrl)}
                          disabled={isUploadingAvatar}
                          className={`relative aspect-square rounded-2xl p-1 border-2 transition-all hover:scale-105 active:scale-95 group overflow-hidden bg-slate-50 ${isSelected
                              ? 'border-emerald-700 ring-2 ring-emerald-700/30 bg-emerald-50/30 shadow-md'
                              : 'border-slate-200 hover:border-slate-400'
                            }`}
                        >
                          <img
                            src={avatarUrl}
                            alt={`Preset ${idx + 1}`}
                            className="w-full h-full object-cover rounded-xl"
                          />
                          {isSelected && (
                            <div className="absolute top-1 right-1 w-4 h-4 bg-emerald-700 text-white rounded-full flex items-center justify-center shadow-xs">
                              <Check size={10} strokeWidth={3} />
                            </div>
                          )}
                        </button>
                      );
                    })}
                  </div>
                </div>
              </div>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
              {/* Card 1: Biodata Akun */}
              <div className="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
                <div>
                  <h3 className="text-base font-extrabold text-slate-900">Biodata & Kontak Diri</h3>
                  <p className="text-xs text-slate-500 mt-0.5">
                    Informasi dasar akun pengguna Radiant Studio Anda.
                  </p>
                </div>

                <form onSubmit={handleProfileSubmit} className="space-y-4">
                  <div>
                    <label className="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
                    <input
                      type="text"
                      required
                      value={profileForm.name}
                      onChange={(e) => setProfileForm({ ...profileForm, name: e.target.value })}
                      className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:border-slate-900 transition-colors"
                    />
                  </div>

                  <div>
                    <label className="block text-xs font-bold text-slate-700 mb-1.5">Email Akun (Permanen)</label>
                    <input
                      type="email"
                      disabled
                      value={user?.email || ''}
                      className="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-500 cursor-not-allowed font-mono"
                    />
                  </div>

                  <div>
                    <label className="block text-xs font-bold text-slate-700 mb-1.5">Nomor WhatsApp / HP</label>
                    <input
                      type="tel"
                      value={profileForm.phone}
                      onChange={(e) => setProfileForm({ ...profileForm, phone: e.target.value })}
                      placeholder="081234567890"
                      className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:border-slate-900 transition-colors"
                    />
                  </div>

                  <div className="pt-2">
                    <button
                      type="submit"
                      disabled={isUpdatingProfile}
                      className="w-full py-3.5 bg-slate-900 hover:bg-slate-800 disabled:bg-slate-400 text-white rounded-xl text-xs font-bold transition-all shadow-md active:scale-95"
                    >
                      {isUpdatingProfile ? 'Menyimpan...' : 'Simpan Data Diri'}
                    </button>
                  </div>
                </form>
              </div>

              {/* Card 2: Ubah Kata Sandi */}
              <div className="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
                <div>
                  <h3 className="text-base font-extrabold text-slate-900">Keamanan & Sandi</h3>
                  <p className="text-xs text-slate-500 mt-0.5">
                    Perbarui kata sandi Anda secara berkala demi keamanan akun.
                  </p>
                </div>

                <form onSubmit={handlePasswordSubmit} className="space-y-4">
                  <div>
                    <label className="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi Saat Ini</label>
                    <input
                      type="password"
                      required
                      value={passwordForm.current_password}
                      onChange={(e) => setPasswordForm({ ...passwordForm, current_password: e.target.value })}
                      placeholder="••••••••"
                      className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:border-slate-900 transition-colors"
                    />
                  </div>

                  <div>
                    <label className="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi Baru (Min. 8 Karakter)</label>
                    <input
                      type="password"
                      required
                      minLength={8}
                      value={passwordForm.password}
                      onChange={(e) => setPasswordForm({ ...passwordForm, password: e.target.value })}
                      placeholder="••••••••"
                      className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:border-slate-900 transition-colors"
                    />
                  </div>

                  <div>
                    <label className="block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Kata Sandi Baru</label>
                    <input
                      type="password"
                      required
                      minLength={8}
                      value={passwordForm.password_confirmation}
                      onChange={(e) => setPasswordForm({ ...passwordForm, password_confirmation: e.target.value })}
                      placeholder="••••••••"
                      className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:bg-white focus:outline-none focus:border-slate-900 transition-colors"
                    />
                  </div>

                  <div className="pt-2">
                    <button
                      type="submit"
                      disabled={isUpdatingPassword}
                      className="w-full py-3.5 bg-slate-900 hover:bg-slate-800 disabled:bg-slate-400 text-white rounded-xl text-xs font-bold transition-all shadow-md active:scale-95"
                    >
                      {isUpdatingPassword ? 'Memperbarui Sandi...' : 'Perbarui Kata Sandi'}
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        )}
      </div>

      {/* ========================================================================= */}
      {/* MODAL DIALOG: TAMBAH / EDIT ALAMAT (MOUNTED TO DOCUMENT.BODY VIA PORTAL) */}
      {/* ========================================================================= */}
      {isAddressModalOpen && createPortal(
        <div className="fixed inset-0 z-[99999] overflow-y-auto flex items-center justify-center p-4 sm:p-6">
          <div
            onClick={() => setIsAddressModalOpen(false)}
            className="fixed inset-0 bg-slate-950/70 backdrop-blur-md transition-opacity animate-fade-in"
          />

          <div className="relative bg-white border border-slate-200/90 rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl z-10 animate-fade-in text-left">
            <div className="flex items-center justify-between pb-4 border-b border-slate-100">
              <h3 className="text-base font-extrabold text-slate-900">
                {editingAddress ? 'Edit Alamat' : 'Tambah Alamat Baru'}
              </h3>
              <button
                type="button"
                onClick={() => setIsAddressModalOpen(false)}
                className="p-1.5 text-slate-400 hover:text-slate-700 rounded-full hover:bg-slate-100 transition-colors"
              >
                <X size={18} />
              </button>
            </div>

            <form onSubmit={handleAddressSubmit} className="space-y-4 pt-4">
              <div>
                <label className="block text-xs font-bold text-slate-700 mb-2">Label Alamat</label>
                <div className="grid grid-cols-5 gap-1.5 sm:gap-2">
                  {['Rumah', 'Kantor', 'Apartemen', 'Kost', 'Lainnya'].map((lbl) => {
                    const isSelected = addressForm.label === lbl;
                    return (
                      <button
                        type="button"
                        key={lbl}
                        onClick={() => setAddressForm({ ...addressForm, label: lbl })}
                        className={`py-2 px-1 rounded-xl text-[11px] sm:text-xs font-bold transition-all active:scale-95 flex flex-col sm:flex-row items-center justify-center gap-1 ${isSelected
                            ? 'bg-slate-900 text-white shadow-xs'
                            : 'bg-slate-100 hover:bg-slate-200 text-slate-700'
                          }`}
                      >
                        {lbl === 'Kantor' ? <Building2 size={12} /> : <Home size={12} />}
                        <span>{lbl}</span>
                      </button>
                    );
                  })}
                </div>
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-700 mb-1">Nomor Telepon / WhatsApp</label>
                <input
                  type="tel"
                  required
                  value={addressForm.phone}
                  onChange={(e) => setAddressForm({ ...addressForm, phone: e.target.value })}
                  placeholder="Contoh: 081234567890"
                  className="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:border-slate-900 transition-colors"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-700 mb-1">Nama Penerima</label>
                <input
                  type="text"
                  required
                  value={addressForm.recipient_name}
                  onChange={(e) => setAddressForm({ ...addressForm, recipient_name: e.target.value })}
                  placeholder="Nama lengkap penerima paket"
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:border-slate-900"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-700 mb-1">Alamat Lengkap</label>
                <textarea
                  rows={2}
                  required
                  value={addressForm.address_line}
                  onChange={(e) => setAddressForm({ ...addressForm, address_line: e.target.value })}
                  placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan..."
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:border-slate-900"
                />
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="block text-xs font-bold text-slate-700 mb-1">Kota / Kabupaten</label>
                  <input
                    type="text"
                    required
                    value={addressForm.city}
                    onChange={(e) => setAddressForm({ ...addressForm, city: e.target.value })}
                    placeholder="Contoh: Jakarta Selatan"
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:border-slate-900"
                  />
                </div>

                <div>
                  <label className="block text-xs font-bold text-slate-700 mb-1">Kode Pos</label>
                  <input
                    type="text"
                    required
                    value={addressForm.postal_code}
                    onChange={(e) => setAddressForm({ ...addressForm, postal_code: e.target.value })}
                    placeholder="Contoh: 12190"
                    className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:border-slate-900"
                  />
                </div>
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-700 mb-1">Catatan Kurir (Opsional)</label>
                <input
                  type="text"
                  value={addressForm.notes}
                  onChange={(e) => setAddressForm({ ...addressForm, notes: e.target.value })}
                  placeholder="Contoh: Pagar hitam, samping pos satpam..."
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:border-slate-900"
                />
              </div>

              <div>
                <label className="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-800">
                  <input
                    type="checkbox"
                    checked={addressForm.is_primary}
                    onChange={(e) => setAddressForm({ ...addressForm, is_primary: e.target.checked })}
                    className="rounded border-slate-300 text-slate-900 focus:ring-slate-900"
                  />
                  <span>Jadikan Alamat Utama</span>
                </label>
              </div>

              <div className="pt-2 flex items-center justify-end gap-2">
                <button
                  type="button"
                  onClick={() => setIsAddressModalOpen(false)}
                  className="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-colors"
                >
                  Batal
                </button>
                <button
                  type="submit"
                  disabled={isSubmittingAddress}
                  className="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 disabled:bg-slate-400 text-white rounded-xl text-xs font-bold transition-all shadow-md active:scale-95"
                >
                  {isSubmittingAddress ? 'Menyimpan...' : 'Simpan Alamat'}
                </button>
              </div>
            </form>
          </div>
        </div>,
        document.body
      )}

      {/* Receipt Photo Lightbox Modal */}
      {selectedReceipt && createPortal(
        <div
          className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm animate-fade-in"
          onClick={() => setSelectedReceipt(null)}
        >
          <div
            className="bg-white rounded-2xl max-w-lg w-full overflow-hidden shadow-2xl border border-slate-200 animate-scale-up"
            onClick={(e) => e.stopPropagation()}
          >
            {/* Modal Header */}
            <div className="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
              <div className="flex items-center gap-2.5">
                <div className="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center">
                  <ImageIcon size={18} />
                </div>
                <div>
                  <h3 className="text-sm font-bold text-slate-900">Foto Bukti Resi Pengiriman</h3>
                  <p className="text-[11px] text-slate-500 font-mono">
                    {selectedReceipt.orderNumber}
                    {selectedReceipt.courier && ` • ${selectedReceipt.courier}`}
                    {selectedReceipt.tracking && ` (${selectedReceipt.tracking})`}
                  </p>
                </div>
              </div>
              <button
                type="button"
                onClick={() => setSelectedReceipt(null)}
                className="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors cursor-pointer"
              >
                <X size={16} />
              </button>
            </div>

            {/* Modal Image Body */}
            <div className="p-4 bg-slate-900/5 max-h-[70vh] overflow-auto flex items-center justify-center">
              <img
                src={selectedReceipt.url}
                alt="Foto Resi Pengiriman"
                className="max-h-[60vh] w-auto max-w-full rounded-xl object-contain shadow-sm border border-slate-200 bg-white"
              />
            </div>

            {/* Modal Footer */}
            <div className="px-5 py-3 border-t border-slate-100 flex items-center justify-between bg-slate-50 text-xs">
              <span className="text-slate-500 text-[11px]">
                Diunggah oleh penjual / kurir
              </span>
              <div className="flex items-center gap-2">
                <a
                  href={selectedReceipt.url}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-lg font-semibold transition-all shadow-xs"
                >
                  <ExternalLink size={13} />
                  <span>Buka Gambar Asli</span>
                </a>
                <button
                  type="button"
                  onClick={() => setSelectedReceipt(null)}
                  className="px-3.5 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg font-semibold transition-colors cursor-pointer"
                >
                  Tutup
                </button>
              </div>
            </div>
          </div>
        </div>,
        document.body
      )}
    </div>
  );
};

export default DashboardPage;
