import React, { useState, useEffect, useRef } from 'react';
import { useSearchParams } from 'react-router-dom';
import { SlidersHorizontal, X, Star, ChevronDown, Check, ArrowUpDown, Sparkles } from 'lucide-react';
import productsApi from '@shared/api/products';
import categoriesApi from '@shared/api/categories';
import { useLanguage } from '@shared/context/LanguageContext';
import ProductCard from '../components/ProductCard';

const ProductsPage = () => {
  const { t } = useLanguage();
  const [searchParams, setSearchParams] = useSearchParams();

  const SORT_OPTIONS = [
    { label: t('prod_sort_latest'), value: 'created_at_desc', sortBy: 'created_at', sortDir: 'desc' },
    { label: t('prod_sort_price_low'), value: 'price_asc', sortBy: 'price', sortDir: 'asc' },
    { label: t('prod_sort_price_high'), value: 'price_desc', sortBy: 'price', sortDir: 'desc' },
    { label: t('prod_sort_rating'), value: 'avg_rating_desc', sortBy: 'avg_rating', sortDir: 'desc' },
  ];

  const [products, setProducts] = useState([]);
  const [categories, setCategories] = useState([]);
  const [pagination, setPagination] = useState({ current_page: 1, last_page: 1, total: 0 });
  const [isLoading, setIsLoading] = useState(true);
  const [isFilterDrawerOpen, setIsFilterDrawerOpen] = useState(false);

  // Custom Dropdown State
  const [isSortDropdownOpen, setIsSortDropdownOpen] = useState(false);
  const sortDropdownRef = useRef(null);

  // Filter states initialized from URL params
  const categoryId = searchParams.get('category_id') || '';
  const search = searchParams.get('search') || '';
  const minPrice = searchParams.get('min_price') || '';
  const maxPrice = searchParams.get('max_price') || '';
  const minRating = searchParams.get('min_rating') || '';
  const isFeatured = searchParams.get('is_featured') || '';
  const sortBy = searchParams.get('sort_by') || 'created_at';
  const sortDir = searchParams.get('sort_dir') || 'desc';
  const page = searchParams.get('page') || 1;

  // Local filter form state
  const [localMinPrice, setLocalMinPrice] = useState(minPrice);
  const [localMaxPrice, setLocalMaxPrice] = useState(maxPrice);

  // Outside click listener for sort dropdown
  useEffect(() => {
    const handleClickOutside = (e) => {
      if (sortDropdownRef.current && !sortDropdownRef.current.contains(e.target)) {
        setIsSortDropdownOpen(false);
      }
    };
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  useEffect(() => {
    categoriesApi.getCategories().then((cats) => setCategories(cats || []));
  }, []);

  useEffect(() => {
    const fetchProducts = async () => {
      setIsLoading(true);
      try {
        const params = {
          category_id: categoryId || undefined,
          search: search || undefined,
          min_price: minPrice || undefined,
          max_price: maxPrice || undefined,
          min_rating: minRating || undefined,
          is_featured: isFeatured ? 1 : undefined,
          sort_by: sortBy,
          sort_dir: sortDir,
          page,
          per_page: 12,
        };

        const res = await productsApi.getProducts(params);
        setProducts(res.data || []);
        setPagination({
          current_page: res.current_page || 1,
          last_page: res.last_page || 1,
          total: res.total || 0,
        });
      } catch (err) {
        console.error('Error fetching products:', err);
      } finally {
        setIsLoading(false);
      }
    };

    fetchProducts();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }, [searchParams]);

  const updateFilters = (updates) => {
    const nextParams = new URLSearchParams(searchParams);
    Object.entries(updates).forEach(([key, val]) => {
      if (val === null || val === undefined || val === '') {
        nextParams.delete(key);
      } else {
        nextParams.set(key, val);
      }
    });
    nextParams.delete('page');
    setSearchParams(nextParams);
  };

  const handlePriceApply = (e) => {
    e.preventDefault();
    updateFilters({ min_price: localMinPrice, max_price: localMaxPrice });
  };

  const clearAllFilters = () => {
    setLocalMinPrice('');
    setLocalMaxPrice('');
    setSearchParams(new URLSearchParams());
  };

  const currentSortOption =
    SORT_OPTIONS.find((opt) => opt.sortBy === sortBy && opt.sortDir === sortDir) || SORT_OPTIONS[0];

  const hasActiveFilters = categoryId || search || minPrice || maxPrice || minRating || isFeatured;

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 animate-fade-in">
      {/* Page Header */}
      <div className="border-b border-slate-200 pb-6 mb-8 text-left">
        <h1 className="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
          {search ? `${t('prod_search_for')} "${search}"` : t('prod_catalog_title')}
        </h1>
        <p className="text-xs sm:text-sm text-slate-500 mt-1">
          {t('prod_showing')} {pagination.total} {t('prod_catalog_subtitle')}
        </p>

        {/* Active Filter Chips */}
        {hasActiveFilters && (
          <div className="flex flex-wrap items-center gap-2 mt-4 pt-3.5 border-t border-slate-100">
            <span className="text-xs text-slate-400 font-bold">Filter:</span>
            {search && (
              <span className="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 text-white rounded-xl text-xs font-bold shadow-xs">
                "{search}"
                <button onClick={() => updateFilters({ search: '' })} className="hover:text-red-300">
                  <X size={13} />
                </button>
              </span>
            )}
            {categoryId && (
              <span className="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-800 text-white rounded-xl text-xs font-bold shadow-xs">
                {categories.find((c) => String(c.id) === String(categoryId))?.name}
                <button onClick={() => updateFilters({ category_id: '' })} className="hover:text-emerald-200">
                  <X size={13} />
                </button>
              </span>
            )}
            {(minPrice || maxPrice) && (
              <span className="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-800 text-white rounded-xl text-xs font-bold shadow-xs">
                Rp {minPrice || 0} - Rp {maxPrice || '∞'}
                <button onClick={() => updateFilters({ min_price: '', max_price: '' })} className="hover:text-red-300">
                  <X size={13} />
                </button>
              </span>
            )}
            {minRating && (
              <span className="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-500 text-slate-950 rounded-xl text-xs font-bold shadow-xs">
                {minRating}+ Stars
                <button onClick={() => updateFilters({ min_rating: '' })} className="hover:text-slate-900">
                  <X size={13} />
                </button>
              </span>
            )}
            {isFeatured === '1' && (
              <span className="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-900 text-white rounded-xl text-xs font-bold shadow-xs">
                {t('prod_sort_featured')}
                <button onClick={() => updateFilters({ is_featured: '' })} className="hover:text-indigo-200">
                  <X size={13} />
                </button>
              </span>
            )}
            <button
              onClick={clearAllFilters}
              className="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-xl text-xs font-bold transition-all active:scale-95 shadow-xs flex items-center gap-1"
            >
              <X size={13} />
              <span>{t('prod_filter_reset')}</span>
            </button>
          </div>
        )}
      </div>

      {/* Main Grid: Sidebar Filters (Desktop) + Products Area */}
      <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">
        {/* Desktop Sidebar Filters */}
        <aside className="hidden lg:block space-y-6 text-left">
          {/* Category Filter */}
          <div className="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs space-y-3">
            <h3 className="text-xs uppercase font-extrabold tracking-wider text-slate-900">
              {t('prod_all_categories')}
            </h3>
            <ul className="space-y-1 text-xs font-medium">
              <li>
                <button
                  onClick={() => updateFilters({ category_id: '' })}
                  className={`w-full text-left py-2 px-3 rounded-xl transition-all ${
                    !categoryId
                      ? 'font-extrabold text-white bg-slate-900 shadow-xs'
                      : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'
                  }`}
                >
                  {t('prod_all_categories')}
                </button>
              </li>
              {categories.map((cat) => {
                const isSelected = String(categoryId) === String(cat.id);
                return (
                  <li key={cat.id}>
                    <button
                      onClick={() => updateFilters({ category_id: cat.id })}
                      className={`w-full text-left py-2 px-3 rounded-xl flex items-center justify-between transition-all ${
                        isSelected
                          ? 'font-extrabold text-white bg-slate-900 shadow-xs'
                          : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'
                      }`}
                    >
                      <span>{cat.name}</span>
                      <span
                        className={`text-[10px] px-1.5 py-0.5 rounded-md ${
                          isSelected ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-500'
                        }`}
                      >
                        {cat.active_products_count}
                      </span>
                    </button>
                  </li>
                );
              })}
            </ul>
          </div>

          {/* Price Range Filter */}
          <div className="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs space-y-3">
            <h3 className="text-xs uppercase font-extrabold tracking-wider text-slate-900">
              {t('prod_filter_price')}
            </h3>
            <form onSubmit={handlePriceApply} className="space-y-2.5">
              <div className="grid grid-cols-2 gap-2">
                <input
                  type="number"
                  placeholder="Min"
                  value={localMinPrice}
                  onChange={(e) => setLocalMinPrice(e.target.value)}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:bg-white focus:border-slate-900 transition-colors"
                />
                <input
                  type="number"
                  placeholder="Max"
                  value={localMaxPrice}
                  onChange={(e) => setLocalMaxPrice(e.target.value)}
                  className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:bg-white focus:border-slate-900 transition-colors"
                />
              </div>
              <button
                type="submit"
                className="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all shadow-xs active:scale-95"
              >
                {t('prod_filter_btn')}
              </button>
            </form>
          </div>

          {/* Rating Filter */}
          <div className="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs space-y-3">
            <h3 className="text-xs uppercase font-extrabold tracking-wider text-slate-900">
              {t('card_rating')}
            </h3>
            <div className="space-y-1.5">
              {[4, 3, 2].map((r) => {
                const isSelected = String(minRating) === String(r);
                return (
                  <button
                    key={r}
                    onClick={() => updateFilters({ min_rating: isSelected ? '' : r })}
                    className={`w-full text-left py-2 px-3 rounded-xl flex items-center justify-between text-xs transition-all ${
                      isSelected
                        ? 'font-extrabold bg-slate-900 text-white shadow-xs'
                        : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'
                    }`}
                  >
                    <div className="flex items-center gap-1.5">
                      <div className="flex text-amber-400">
                        {[...Array(5)].map((_, i) => (
                          <Star
                            key={i}
                            size={12}
                            className={i < r ? 'fill-amber-400' : isSelected ? 'text-slate-700' : 'text-slate-200'}
                          />
                        ))}
                      </div>
                      <span>{r}+ Stars</span>
                    </div>
                    {isSelected && <Check size={14} className="text-emerald-400" />}
                  </button>
                );
              })}
            </div>
          </div>

          {/* Featured Only */}
          <div className="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-xs">
            <label className="flex items-center gap-2.5 cursor-pointer text-xs font-bold text-slate-800">
              <input
                type="checkbox"
                checked={isFeatured === '1'}
                onChange={(e) => updateFilters({ is_featured: e.target.checked ? '1' : '' })}
                className="w-4 h-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900"
              />
              <span>{t('tab_featured')}</span>
            </label>
          </div>
        </aside>

        {/* Product Catalog Area */}
        <div className="lg:col-span-3">
          {/* Top Bar with Custom Dropdown */}
          <div className="flex items-center justify-between bg-white border border-slate-200/90 rounded-2xl p-3.5 mb-6 shadow-xs">
            {/* Mobile filter toggle */}
            <button
              onClick={() => setIsFilterDrawerOpen(!isFilterDrawerOpen)}
              className="lg:hidden inline-flex items-center gap-2 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-xl transition-all active:scale-95"
            >
              <SlidersHorizontal size={14} />
              <span>{t('prod_filter_btn')}</span>
            </button>

            <span className="hidden sm:inline text-xs text-slate-500 font-medium">
              {t('prod_showing')} <strong className="text-slate-900">{pagination.total}</strong>
            </span>

            {/* Custom Modern Dropdown Component */}
            <div className="relative ml-auto" ref={sortDropdownRef}>
              <div className="flex items-center gap-2">
                <span className="text-xs text-slate-400 font-bold hidden sm:inline">{t('prod_sort_by')}</span>
                <button
                  type="button"
                  onClick={() => setIsSortDropdownOpen(!isSortDropdownOpen)}
                  className="px-4 py-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl text-xs font-bold text-slate-900 inline-flex items-center gap-2 transition-all active:scale-95 shadow-xs"
                >
                  <ArrowUpDown size={13} className="text-slate-500" />
                  <span>{currentSortOption.label}</span>
                  <ChevronDown
                    size={14}
                    className={`text-slate-400 transition-transform duration-200 ${
                      isSortDropdownOpen ? 'rotate-180' : ''
                    }`}
                  />
                </button>
              </div>

              {/* Animated Custom Dropdown Menu */}
              {isSortDropdownOpen && (
                <div className="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-slate-200/90 py-2 z-30 animate-fade-in divide-y divide-slate-100 text-left">
                  <div className="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    {t('prod_sort_by')}
                  </div>
                  <div className="py-1">
                    {SORT_OPTIONS.map((option) => {
                      const isSelected = option.sortBy === sortBy && option.sortDir === sortDir;
                      return (
                        <button
                          key={option.value}
                          onClick={() => {
                            updateFilters({ sort_by: option.sortBy, sort_dir: option.sortDir });
                            setIsSortDropdownOpen(false);
                          }}
                          className={`w-full px-3.5 py-2.5 text-xs font-bold text-left flex items-center justify-between transition-colors ${
                            isSelected
                              ? 'text-emerald-800 bg-emerald-50/60 font-extrabold'
                              : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900'
                          }`}
                        >
                          <span>{option.label}</span>
                          {isSelected && <Check size={14} className="text-emerald-700" />}
                        </button>
                      );
                    })}
                  </div>
                </div>
              )}
            </div>
          </div>

          {/* Product Grid */}
          {isLoading ? (
            <div className="grid grid-cols-2 sm:grid-cols-3 gap-4 sm:gap-6">
              {[...Array(6)].map((_, i) => (
                <div key={i} className="animate-shimmer rounded-2xl p-4 h-80" />
              ))}
            </div>
          ) : products.length === 0 ? (
            <div className="bg-white border border-slate-200 rounded-3xl p-12 text-center shadow-xs">
              <div className="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 mx-auto mb-3 shadow-inner">
                <SlidersHorizontal size={24} />
              </div>
              <h3 className="text-base font-bold text-slate-900">
                {t('prod_empty_title')}
              </h3>
              <p className="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                {t('prod_empty_desc')}
              </p>
              <button
                onClick={clearAllFilters}
                className="mt-5 px-6 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-all shadow-md active:scale-95"
              >
                {t('prod_filter_reset')}
              </button>
            </div>
          ) : (
            <div className="grid grid-cols-2 sm:grid-cols-3 gap-4 sm:gap-6">
              {products.map((product) => (
                <ProductCard key={product.id} product={product} />
              ))}
            </div>
          )}

          {/* Pagination */}
          {pagination.last_page > 1 && (
            <div className="mt-12 flex items-center justify-center gap-2">
              {[...Array(pagination.last_page)].map((_, idx) => {
                const pageNum = idx + 1;
                const isActive = Number(page) === pageNum;
                return (
                  <button
                    key={pageNum}
                    onClick={() => {
                      const nextParams = new URLSearchParams(searchParams);
                      nextParams.set('page', pageNum);
                      setSearchParams(nextParams);
                    }}
                    className={`w-10 h-10 rounded-xl text-xs font-extrabold transition-all active:scale-95 ${
                      isActive
                        ? 'bg-slate-900 text-white shadow-md'
                        : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-400 shadow-xs'
                    }`}
                  >
                    {pageNum}
                  </button>
                );
              })}
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default ProductsPage;
