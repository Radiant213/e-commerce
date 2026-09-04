import React, { useState, useRef, useEffect } from 'react';
import { Link, useNavigate, useLocation } from 'react-router-dom';
import { ShoppingCart, Heart, Search, User as UserIcon, LogOut, Menu, X, Package, LayoutDashboard, ArrowRight } from 'lucide-react';
import { useAuth } from '@shared/context/AuthContext';
import { useCart } from '@shared/context/CartContext';
import { useWishlist } from '@shared/context/WishlistContext';
import { useLanguage } from '@shared/context/LanguageContext';
import productsApi from '@shared/api/products';
import { formatCurrency } from '@shared/utils/formatCurrency';

const Navbar = () => {
  const navigate = useNavigate();
  const location = useLocation();
  const { user, isAuthenticated, logout } = useAuth();
  const { totalItems, setIsDrawerOpen } = useCart();
  const { totalWishlist } = useWishlist();
  const { t } = useLanguage();

  const [isSearchOpen, setIsSearchOpen] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const [searchResults, setSearchResults] = useState([]);
  const [isSearching, setIsSearching] = useState(false);
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [isProfileOpen, setIsProfileOpen] = useState(false);

  const profileRef = useRef(null);
  const searchContainerRef = useRef(null);
  const searchInputRef = useRef(null);

  // Outside click listener for Profile & Search
  useEffect(() => {
    const handleClickOutside = (event) => {
      if (profileRef.current && !profileRef.current.contains(event.target)) {
        setIsProfileOpen(false);
      }
      if (searchContainerRef.current && !searchContainerRef.current.contains(event.target)) {
        setIsSearchOpen(false);
        setSearchResults([]);
      }
    };

    const handleKeyDown = (e) => {
      if (e.key === 'Escape') {
        setIsSearchOpen(false);
        setIsProfileOpen(false);
        setSearchResults([]);
      }
    };

    document.addEventListener('mousedown', handleClickOutside);
    window.addEventListener('keydown', handleKeyDown);
    return () => {
      document.removeEventListener('mousedown', handleClickOutside);
      window.removeEventListener('keydown', handleKeyDown);
    };
  }, []);

  // Auto-focus input when opened
  useEffect(() => {
    if (isSearchOpen && searchInputRef.current) {
      searchInputRef.current.focus();
    }
  }, [isSearchOpen]);

  // Live search debounced suggestions
  useEffect(() => {
    if (!searchQuery.trim() || searchQuery.length < 2) {
      setSearchResults([]);
      return;
    }

    const timer = setTimeout(async () => {
      setIsSearching(true);
      try {
        const res = await productsApi.getProducts({ search: searchQuery, per_page: 4 });
        setSearchResults(res.data || []);
      } catch (err) {
        console.error('Quick search error:', err);
      } finally {
        setIsSearching(false);
      }
    }, 250);

    return () => clearTimeout(timer);
  }, [searchQuery]);

  const handleSearchSubmit = (e) => {
    e.preventDefault();
    if (searchQuery.trim()) {
      navigate(`/products?search=${encodeURIComponent(searchQuery.trim())}`);
      setIsSearchOpen(false);
      setSearchResults([]);
    }
  };

  const handleSelectProductSuggestion = (slug) => {
    navigate(`/products/${slug}`);
    setIsSearchOpen(false);
    setSearchQuery('');
    setSearchResults([]);
  };

  const navLinks = [
    { name: t('nav_home'), path: '/' },
    { name: t('nav_all_products'), path: '/products' },
    { name: t('nav_gadget'), path: '/products?category_id=1' },
    { name: t('nav_men'), path: '/products?category_id=2' },
    { name: t('nav_women'), path: '/products?category_id=3' },
    { name: t('nav_home_living'), path: '/products?category_id=4' },
  ];

  return (
    <header className="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/90 transition-all">
      {/* Top Announcement Bar */}
      <div className="bg-slate-950 text-slate-200 text-xs py-2 px-4 text-center tracking-wide font-medium flex items-center justify-center gap-2">
        <span className="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-ping" />
        <span>{t('nav_announcement')}</span>
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16 sm:h-20 gap-4">
          {/* Mobile menu button */}
          <div className="flex items-center lg:hidden">
            <button
              onClick={() => setIsMenuOpen(!isMenuOpen)}
              className="p-2 text-slate-700 hover:text-slate-900 rounded-xl hover:bg-slate-100 transition-colors"
            >
              {isMenuOpen ? <X size={22} /> : <Menu size={22} />}
            </button>
          </div>

          {/* Brand Logo */}
          <div className="flex-shrink-0 flex items-center">
            <Link to="/" className="flex items-center gap-2.5 group">
              <div className="w-9 h-9 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-lg tracking-tighter group-hover:scale-105 group-hover:bg-emerald-900 transition-all shadow-xs">
                R
              </div>
              <div className="flex flex-col text-left">
                <span className="font-extrabold text-lg tracking-tight text-slate-900 leading-none group-hover:text-emerald-950 transition-colors">
                  RADIANT
                </span>
                <span className="text-[10px] tracking-[0.25em] text-slate-400 uppercase font-semibold mt-0.5">
                  STUDIO
                </span>
              </div>
            </Link>
          </div>

          {/* Desktop Navigation Links */}
          <nav className={`hidden lg:flex items-center space-x-1 transition-opacity duration-300 ${isSearchOpen ? 'opacity-0 pointer-events-none lg:hidden xl:flex' : 'opacity-100'}`}>
            {navLinks.map((link) => {
              const isActive = location.pathname + location.search === link.path;
              return (
                <Link
                  key={link.path}
                  to={link.path}
                  className={`px-3.5 py-2 rounded-xl text-xs font-bold tracking-wide transition-all ${
                    isActive
                      ? 'text-slate-900 bg-slate-100/90 font-extrabold shadow-2xs'
                      : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'
                  }`}
                >
                  {link.name}
                </Link>
              );
            })}
          </nav>

          {/* Action Icons & Morphing Search Bar */}
          <div className="flex items-center space-x-2 sm:space-x-3 flex-shrink-0">
            {/* Inline Morphing Expanding Search Bar */}
            <div className="relative flex-shrink-0" ref={searchContainerRef}>
              <div className="flex items-center">
                {/* Search Expansion Input Container */}
                <form
                  onSubmit={handleSearchSubmit}
                  className={`flex items-center bg-slate-100/90 rounded-2xl border border-slate-200 overflow-hidden transition-all duration-300 ease-out shadow-xs ${
                    isSearchOpen
                      ? 'w-48 sm:w-64 md:w-72 lg:w-84 px-3 py-1.5 opacity-100 border-slate-900 bg-white ring-2 ring-slate-900/10'
                      : 'w-0 opacity-0 px-0 py-0 border-transparent pointer-events-none'
                  }`}
                >
                  <Search size={16} className="text-slate-400 flex-shrink-0 mr-2" />
                  <input
                    ref={searchInputRef}
                    type="text"
                    value={searchQuery}
                    onChange={(e) => setSearchQuery(e.target.value)}
                    placeholder={t('nav_search_placeholder')}
                    className="w-full bg-transparent text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none"
                  />
                  {searchQuery && (
                    <button
                      type="button"
                      onClick={() => {
                        setSearchQuery('');
                        setSearchResults([]);
                        searchInputRef.current?.focus();
                      }}
                      className="p-1 text-slate-400 hover:text-slate-700 flex-shrink-0"
                    >
                      <X size={13} />
                    </button>
                  )}
                  <button
                    type="submit"
                    className="ml-1.5 px-2.5 py-1 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-[11px] font-bold transition-colors flex-shrink-0"
                  >
                    {t('nav_search_btn')}
                  </button>
                </form>

                {/* Compact Trigger Button (When closed) */}
                {!isSearchOpen && (
                  <button
                    onClick={() => setIsSearchOpen(true)}
                    className="p-2.5 text-slate-700 hover:text-slate-900 rounded-full hover:bg-slate-100 transition-all hover:scale-105 active:scale-95 flex-shrink-0"
                    title="Buka Pencarian"
                  >
                    <Search size={19} strokeWidth={2} />
                  </button>
                )}

                {/* Close Search Button (When open) */}
                {isSearchOpen && (
                  <button
                    onClick={() => {
                      setIsSearchOpen(false);
                      setSearchResults([]);
                    }}
                    className="p-2 text-slate-400 hover:text-slate-700 rounded-full hover:bg-slate-100 transition-colors ml-1 flex-shrink-0"
                    title="Tutup Pencarian"
                  >
                    <X size={18} />
                  </button>
                )}
              </div>

              {/* Live Search Autocomplete Suggestions Dropdown */}
              {isSearchOpen && searchResults.length > 0 && (
                <div className="absolute right-0 mt-2 w-72 sm:w-88 bg-white rounded-2xl shadow-2xl border border-slate-200/90 py-2.5 z-50 animate-fade-in divide-y divide-slate-100 text-left">
                  <div className="px-3.5 py-1 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 flex items-center justify-between">
                    <span>{t('nav_search_results')}</span>
                    <span>{searchResults.length} Produk</span>
                  </div>

                  <div className="py-1">
                    {searchResults.map((prod) => (
                      <div
                        key={prod.id}
                        onClick={() => handleSelectProductSuggestion(prod.slug)}
                        className="px-3.5 py-2.5 hover:bg-slate-50 flex items-center gap-3 cursor-pointer transition-colors group"
                      >
                        <img
                          src={prod.primary_image?.image_path || 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=100&q=80'}
                          alt={prod.name}
                          className="w-10 h-10 rounded-lg object-cover bg-slate-50 border border-slate-100 flex-shrink-0"
                        />
                        <div className="flex-1 min-w-0">
                          <p className="text-xs font-bold text-slate-900 truncate group-hover:text-emerald-800 transition-colors">
                            {prod.name}
                          </p>
                          <p className="text-[11px] font-bold text-slate-700">
                            {formatCurrency(prod.effective_price || prod.price)}
                          </p>
                        </div>
                      </div>
                    ))}
                  </div>

                  <div className="pt-1.5 px-3">
                    <button
                      onClick={handleSearchSubmit}
                      className="w-full py-2 bg-slate-50 hover:bg-slate-100 text-slate-800 text-xs font-bold rounded-xl flex items-center justify-center gap-1.5 transition-colors"
                    >
                      <span>{t('nav_view_all_results')} "{searchQuery}"</span>
                      <ArrowRight size={13} />
                    </button>
                  </div>
                </div>
              )}
            </div>

            {/* Wishlist Link */}
            <Link
              to="/wishlist"
              className="p-2.5 text-slate-700 hover:text-slate-900 rounded-full hover:bg-slate-100 transition-all hover:scale-105 active:scale-95 relative flex-shrink-0"
              title={t('nav_wishlist')}
            >
              <Heart size={19} strokeWidth={2} />
              {totalWishlist > 0 && (
                <span className="absolute top-1 right-1 w-4 h-4 bg-slate-900 text-white text-[10px] font-bold rounded-full flex items-center justify-center animate-pop">
                  {totalWishlist}
                </span>
              )}
            </Link>

            {/* Cart Trigger Button */}
            <button
              onClick={() => setIsDrawerOpen(true)}
              className="p-2.5 text-slate-700 hover:text-slate-900 rounded-full hover:bg-slate-100 transition-all hover:scale-105 active:scale-95 relative group flex-shrink-0"
              title="Keranjang Belanja"
            >
              <ShoppingCart size={20} strokeWidth={1.8} className="group-hover:scale-110 transition-transform" />
              {totalItems > 0 && (
                <span className="absolute top-0.5 right-0.5 w-4 h-4 bg-emerald-700 text-white text-[10px] font-bold rounded-full flex items-center justify-center animate-pop">
                  {totalItems}
                </span>
              )}
            </button>

            {/* User Profile / Auth */}
            <div className="relative flex-shrink-0" ref={profileRef}>
              {isAuthenticated ? (
                <div className="relative flex-shrink-0">
                  <button
                    onClick={() => setIsProfileOpen(!isProfileOpen)}
                    className="flex items-center gap-2 p-1 rounded-full hover:ring-2 hover:ring-slate-300 transition-all active:scale-95 flex-shrink-0"
                  >
                    {user?.avatar ? (
                      <img
                        src={user.avatar}
                        alt={user.name}
                        className="w-8 h-8 min-w-[32px] min-h-[32px] aspect-square rounded-full object-cover border border-slate-200 shadow-xs flex-shrink-0"
                      />
                    ) : (
                      <div className="w-8 h-8 min-w-[32px] min-h-[32px] aspect-square rounded-full bg-slate-900 text-white flex items-center justify-center text-xs font-bold shadow-xs flex-shrink-0">
                        {user?.name?.charAt(0) || 'U'}
                      </div>
                    )}
                  </button>

                  {/* Dropdown Menu */}
                  {isProfileOpen && (
                    <div className="absolute right-0 mt-2 w-60 bg-white rounded-2xl shadow-2xl border border-slate-200/90 py-2 z-50 animate-fade-in divide-y divide-slate-100">
                      <div className="px-4 py-3 text-left">
                        <p className="text-xs font-bold text-slate-900 truncate">{user?.name}</p>
                        <p className="text-[11px] text-slate-400 truncate">{user?.email}</p>
                        <span className="inline-block mt-1 px-2 py-0.5 bg-emerald-50 text-emerald-800 text-[9px] font-bold rounded-full border border-emerald-200">
                          {t('nav_member_badge')}
                        </span>
                      </div>

                      <div className="py-1">
                        <Link
                          to="/dashboard"
                          onClick={() => setIsProfileOpen(false)}
                          className="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors"
                        >
                          <LayoutDashboard size={15} />
                          {t('nav_dashboard')}
                        </Link>
                        {user?.role === 'admin' && (
                          <a
                            href={import.meta.env.VITE_API_URL?.replace(/\/api\/?$/, '/admin') || 'https://api-ecommerce.radiantcode.web.id/admin'}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="flex items-center justify-between px-4 py-2.5 text-xs font-bold text-indigo-700 bg-indigo-50/70 hover:bg-indigo-100/80 transition-colors"
                          >
                            <span className="flex items-center gap-2">
                              <span>👑</span>
                              <span>Panel Admin Filament</span>
                            </span>
                            <ArrowRight size={13} />
                          </a>
                        )}
                        <Link
                          to="/dashboard?tab=orders"
                          onClick={() => setIsProfileOpen(false)}
                          className="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors"
                        >
                          <Package size={15} />
                          {t('nav_my_orders')}
                        </Link>
                        <Link
                          to="/dashboard?tab=wishlist"
                          onClick={() => setIsProfileOpen(false)}
                          className="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors"
                        >
                          <Heart size={15} />
                          {t('nav_wishlist')}
                        </Link>
                      </div>

                      <div className="pt-1">
                        <button
                          onClick={() => {
                            setIsProfileOpen(false);
                            logout();
                          }}
                          className="w-full flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-red-600 hover:bg-red-50 transition-colors text-left"
                        >
                          <LogOut size={15} />
                          {t('nav_logout')}
                        </button>
                      </div>
                    </div>
                  )}
                </div>
              ) : (
                <Link
                  to="/login"
                  className="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl transition-all shadow-xs hover:shadow-sm active:scale-95"
                >
                  <UserIcon size={14} />
                  <span>{t('nav_login')}</span>
                </Link>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* Mobile Drawer Menu */}
      {isMenuOpen && (
        <div className="lg:hidden border-t border-slate-200 bg-white px-4 pt-3 pb-6 space-y-2 animate-fade-in text-left">
          {navLinks.map((link) => (
            <Link
              key={link.path}
              to={link.path}
              onClick={() => setIsMenuOpen(false)}
              className="block text-sm font-semibold text-slate-800 py-2 px-3 rounded-xl hover:bg-slate-50"
            >
              {link.name}
            </Link>
          ))}
        </div>
      )}
    </header>
  );
};

export default Navbar;
