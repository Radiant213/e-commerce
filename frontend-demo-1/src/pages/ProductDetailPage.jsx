import React, { useState, useEffect } from 'react';
import { useParams, Link, useNavigate } from 'react-router-dom';
import {
  Star,
  Heart,
  ShoppingCart,
  Truck,
  ShieldCheck,
  RotateCcw,
  CheckCircle2,
  Plus,
  Minus,
  MessageSquarePlus,
  Sparkles,
} from 'lucide-react';
import productsApi from '@shared/api/products';
import reviewsApi from '@shared/api/reviews';
import { formatCurrency } from '@shared/utils/formatCurrency';
import { formatDate } from '@shared/utils/formatDate';
import { useCart } from '@shared/context/CartContext';
import { useWishlist } from '@shared/context/WishlistContext';
import { useAuth } from '@shared/context/AuthContext';
import { useLanguage } from '@shared/context/LanguageContext';
import { useToast } from '../components/Toast';
import ProductCard from '../components/ProductCard';

const ProductDetailPage = () => {
  const { t } = useLanguage();
  const { slug } = useParams();
  const navigate = useNavigate();
  const { addToCart, setIsDrawerOpen } = useCart();
  const { isWishlisted, toggleWishlist } = useWishlist();
  const { isAuthenticated } = useAuth();
  const { addToast } = useToast();

  const [product, setProduct] = useState(null);
  const [relatedProducts, setRelatedProducts] = useState([]);
  const [selectedImage, setSelectedImage] = useState('');
  const [quantity, setQuantity] = useState(1);
  const [activeTab, setActiveTab] = useState('description');
  const [isLoading, setIsLoading] = useState(true);

  // Review submission state
  const [ratingInput, setRatingInput] = useState(5);
  const [hoverRating, setHoverRating] = useState(0);
  const [commentInput, setCommentInput] = useState('');
  const [isSubmittingReview, setIsSubmittingReview] = useState(false);

  useEffect(() => {
    const fetchDetail = async () => {
      setIsLoading(true);
      try {
        const data = await productsApi.getProductBySlug(slug);
        setProduct(data.product);
        setRelatedProducts(data.related_products || []);
        const firstImg = data.product?.primary_image?.image_path || data.product?.images?.[0]?.image_path;
        setSelectedImage(firstImg || 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=80');
      } catch (err) {
        console.error('Error fetching product detail:', err);
      } finally {
        setIsLoading(false);
      }
    };

    fetchDetail();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }, [slug]);

  if (isLoading) {
    return (
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-left">
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-12">
          <div className="lg:col-span-7 aspect-square rounded-3xl animate-shimmer" />
          <div className="lg:col-span-5 space-y-4">
            <div className="h-6 rounded-lg w-1/4 animate-shimmer" />
            <div className="h-10 rounded-lg w-3/4 animate-shimmer" />
            <div className="h-8 rounded-lg w-1/3 animate-shimmer" />
            <div className="h-32 rounded-2xl w-full animate-shimmer" />
          </div>
        </div>
      </div>
    );
  }

  if (!product) {
    return (
      <div className="max-w-7xl mx-auto px-4 py-20 text-center">
        <h2 className="text-xl font-bold text-slate-900">Produk Tidak Ditemukan</h2>
        <Link to="/products" className="text-xs font-semibold text-emerald-800 underline mt-2 inline-block">
          Kembali ke Katalog
        </Link>
      </div>
    );
  }

  const wishlisted = isWishlisted(product.id);
  const discountPercent = product.discount_percent || (product.sale_price ? Math.round(((product.price - product.sale_price) / product.price) * 100) : null);

  const handleAddToCart = async () => {
    try {
      await addToCart(product.id, quantity);
      addToast({
        title: 'Berhasil Masuk Keranjang!',
        message: `${quantity}x ${product.name} telah ditambahkan ke keranjang.`,
        type: 'success',
        action: {
          label: 'Buka Keranjang &rarr;',
          onClick: () => setIsDrawerOpen(true),
        },
      });
    } catch (err) {
      addToast({
        title: 'Gagal Menambahkan',
        message: err.message || 'Terjadi kesalahan.',
        type: 'error',
      });
    }
  };

  const handleBuyNow = async () => {
    try {
      await addToCart(product.id, quantity);
      navigate('/checkout');
    } catch (err) {
      alert(err.message || 'Gagal memproses pesanan.');
    }
  };

  const handleWishlistClick = () => {
    toggleWishlist(product.id);
    addToast({
      title: wishlisted ? 'Dihapus dari Wishlist' : 'Disimpan ke Wishlist',
      message: `${product.name} ${wishlisted ? 'dihapus dari' : 'ditambahkan ke'} wishlist Anda.`,
      type: 'info',
    });
  };

  const handleReviewSubmit = async (e) => {
    e.preventDefault();
    if (!isAuthenticated) {
      addToast({
        title: 'Silakan Masuk Terlebih Dahulu',
        message: 'Anda harus login untuk dapat memberikan ulasan produk.',
        type: 'info',
      });
      navigate('/login');
      return;
    }

    setIsSubmittingReview(true);
    try {
      await reviewsApi.submitReview(product.id, {
        rating: ratingInput,
        comment: commentInput,
      });
      addToast({
        title: 'Ulasan Berhasil Dikirim!',
        message: 'Terima kasih telah berbagi pengalaman Anda.',
        type: 'success',
      });
      setCommentInput('');
      // Refresh reviews list
      const refreshed = await productsApi.getProductBySlug(slug);
      setProduct(refreshed.product);
    } catch (err) {
      addToast({
        title: 'Gagal Mengirim Ulasan',
        message: err.message || 'Terjadi kesalahan.',
        type: 'error',
      });
    } finally {
      setIsSubmittingReview(false);
    }
  };

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 text-left animate-fade-in">
      {/* Breadcrumb */}
      <nav className="text-xs text-slate-500 mb-8 flex items-center gap-2">
        <Link to="/" className="hover:text-slate-900 transition-colors">{t('nav_home')}</Link>
        <span>/</span>
        <Link to="/products" className="hover:text-slate-900 transition-colors">{t('nav_all_products')}</Link>
        <span>/</span>
        <Link to={`/products?category_id=${product.category_id}`} className="hover:text-slate-900 transition-colors">
          {product.category?.name}
        </Link>
        <span>/</span>
        <span className="text-slate-900 font-bold truncate max-w-xs">{product.name}</span>
      </nav>

      {/* Main PDP Grid */}
      <div className="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16">
        {/* Left Column: Gallery */}
        <div className="lg:col-span-7 flex flex-col-reverse sm:flex-row gap-4">
          {/* Thumbnails */}
          {product.images && product.images.length > 1 && (
            <div className="flex sm:flex-col gap-3 overflow-x-auto sm:overflow-visible">
              {product.images.map((img) => (
                <button
                  key={img.id}
                  onClick={() => setSelectedImage(img.image_path)}
                  className={`w-16 h-16 sm:w-20 sm:h-20 rounded-2xl overflow-hidden border-2 bg-slate-50 flex-shrink-0 transition-all ${
                    selectedImage === img.image_path ? 'border-slate-900 ring-2 ring-slate-900/20' : 'border-slate-200 hover:border-slate-400'
                  }`}
                >
                  <img src={img.image_path} alt={product.name} className="w-full h-full object-cover" />
                </button>
              ))}
            </div>
          )}

          {/* Main Large Image */}
          <div className="flex-1 aspect-square rounded-3xl overflow-hidden bg-slate-50 border border-slate-200/90 relative group shadow-sm">
            <img
              src={selectedImage}
              alt={product.name}
              className="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 ease-out"
            />
            {discountPercent && (
              <span className="absolute top-5 left-5 px-3 py-1.5 bg-emerald-800 text-white text-xs font-bold rounded-lg tracking-wider shadow-md">
                {t('card_save')} {discountPercent}%
              </span>
            )}
          </div>
        </div>

        {/* Right Column: Product Info & Actions */}
        <div className="lg:col-span-5 space-y-6">
          {/* Category & Status */}
          <div className="flex items-center justify-between">
            <span className="text-xs font-bold uppercase tracking-widest text-slate-700 bg-slate-100 px-3 py-1 rounded-lg">
              {product.category?.name}
            </span>
            <span className={`text-xs font-bold flex items-center gap-1.5 ${product.stock > 0 ? 'text-emerald-700' : 'text-red-600'}`}>
              <CheckCircle2 size={14} />
              {product.stock > 0 ? `${t('detail_in_stock')} (${product.stock})` : t('detail_out_of_stock')}
            </span>
          </div>

          {/* Product Title */}
          <h1 className="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-snug">
            {product.name}
          </h1>

          {/* Rating Summary */}
          <div className="flex items-center gap-3 text-xs text-slate-600 pb-4 border-b border-slate-100 font-medium">
            <div className="flex items-center gap-1.5 text-amber-500 font-bold bg-amber-50 px-2 py-0.5 rounded-md border border-amber-100">
              <Star size={14} className="fill-amber-400 text-amber-400" />
              <span>{Number(product.avg_rating || 5.0).toFixed(1)}</span>
            </div>
            <span>&bull;</span>
            <span>{product.total_reviews || product.reviews?.length || 0} {t('detail_reviews_count')}</span>
            <span>&bull;</span>
            <span>{product.total_sold || 0} {t('prod_of')} sold</span>
          </div>

          {/* Price */}
          <div className="flex items-baseline gap-3">
            <span className="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
              {formatCurrency(product.effective_price || product.sale_price || product.price)}
            </span>
            {(product.sale_price || discountPercent) && (
              <span className="text-sm sm:text-base text-slate-400 line-through">
                {formatCurrency(product.price)}
              </span>
            )}
          </div>

          {/* Short Description */}
          {product.short_description && (
            <p className="text-xs sm:text-sm text-slate-600 leading-relaxed">
              {product.short_description}
            </p>
          )}

          {/* Quantity & CTA Actions */}
          <div className="space-y-4 pt-4 border-t border-slate-100">
            <div className="flex items-center gap-4">
              <span className="text-xs font-bold text-slate-700">{t('detail_quantity')}:</span>
              <div className="flex items-center border border-slate-300 rounded-xl bg-slate-50/50">
                <button
                  disabled={quantity <= 1}
                  onClick={() => setQuantity(Math.max(1, quantity - 1))}
                  className="p-2.5 text-slate-600 hover:text-slate-900 disabled:opacity-30 active:scale-95"
                >
                  <Minus size={14} />
                </button>
                <span className="px-4 text-xs font-bold text-slate-900">{quantity}</span>
                <button
                  disabled={quantity >= product.stock}
                  onClick={() => setQuantity(Math.min(product.stock, quantity + 1))}
                  className="p-2.5 text-slate-600 hover:text-slate-900 disabled:opacity-30 active:scale-95"
                >
                  <Plus size={14} />
                </button>
              </div>
            </div>

            <div className="flex items-center gap-3 pt-2">
              <button
                onClick={handleAddToCart}
                disabled={product.stock <= 0}
                className="flex-1 py-3.5 px-4 bg-slate-900 hover:bg-slate-800 disabled:bg-slate-300 text-white font-bold text-xs rounded-xl flex items-center justify-center gap-2 shadow-md transition-all active:scale-95"
              >
                <ShoppingCart size={16} />
                <span>{t('card_add_cart')}</span>
              </button>

              <button
                onClick={handleBuyNow}
                disabled={product.stock <= 0}
                className="flex-1 py-3.5 px-4 bg-emerald-800 hover:bg-emerald-700 disabled:bg-slate-300 text-white font-bold text-xs rounded-xl text-center shadow-md transition-all active:scale-95"
              >
                {t('hero_buy_now')}
              </button>

              <button
                onClick={handleWishlistClick}
                className={`p-3.5 border rounded-xl transition-all active:scale-90 ${
                  wishlisted ? 'border-red-300 bg-red-50 text-red-500' : 'border-slate-200 hover:bg-slate-50 text-slate-700'
                }`}
                title="Wishlist"
              >
                <Heart size={18} className={wishlisted ? 'fill-red-500 text-red-500 animate-pop' : ''} />
              </button>
            </div>
          </div>

          {/* Value props micro-bar */}
          <div className="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 space-y-2.5 text-xs text-slate-600">
            <div className="flex items-center gap-2.5">
              <Truck size={16} className="text-slate-900 flex-shrink-0" />
              <span>{t('nav_announcement')}</span>
            </div>
            <div className="flex items-center gap-2.5">
              <ShieldCheck size={16} className="text-slate-900 flex-shrink-0" />
              <span>{t('detail_guarantee_badge')}</span>
            </div>
            <div className="flex items-center gap-2.5">
              <RotateCcw size={16} className="text-slate-900 flex-shrink-0" />
              <span>{t('detail_free_returns')}</span>
            </div>
          </div>
        </div>
      </div>

      {/* Tabs: Description, Shipping, Reviews */}
      <div className="mt-16 lg:mt-24 border-t border-slate-200 pt-8">
        <div className="flex border-b border-slate-200 gap-8">
          <button
            onClick={() => setActiveTab('description')}
            className={`pb-3.5 text-xs uppercase font-bold tracking-wider transition-colors relative ${
              activeTab === 'description' ? 'text-slate-900 border-b-2 border-slate-900' : 'text-slate-400 hover:text-slate-700'
            }`}
          >
            {t('detail_tab_spec')}
          </button>
          <button
            onClick={() => setActiveTab('reviews')}
            className={`pb-3.5 text-xs uppercase font-bold tracking-wider transition-colors relative ${
              activeTab === 'reviews' ? 'text-slate-900 border-b-2 border-slate-900' : 'text-slate-400 hover:text-slate-700'
            }`}
          >
            {t('detail_tab_reviews')} ({product.reviews?.length || 0})
          </button>
          <button
            onClick={() => setActiveTab('shipping')}
            className={`pb-3.5 text-xs uppercase font-bold tracking-wider transition-colors relative ${
              activeTab === 'shipping' ? 'text-slate-900 border-b-2 border-slate-900' : 'text-slate-400 hover:text-slate-700'
            }`}
          >
            {t('footer_shipping_guide')}
          </button>
        </div>

        <div className="py-6">
          {activeTab === 'description' && (
            <div className="text-slate-700 text-sm leading-relaxed space-y-6 max-w-4xl">
              <p>{product.description}</p>
              <div className="grid grid-cols-2 sm:grid-cols-3 gap-4 bg-slate-50 p-5 rounded-2xl border border-slate-200/60 text-xs">
                <div>
                  <span className="text-slate-400 block font-medium">SKU Produk</span>
                  <span className="font-bold text-slate-900 font-mono mt-0.5 block">{product.sku || '-'}</span>
                </div>
                <div>
                  <span className="text-slate-400 block font-medium">Berat Barang</span>
                  <span className="font-bold text-slate-900 mt-0.5 block">{product.weight ? `${product.weight} gram` : '-'}</span>
                </div>
                <div>
                  <span className="text-slate-400 block font-medium">Kondisi</span>
                  <span className="font-bold text-slate-900 mt-0.5 block">Baru & 100% Original</span>
                </div>
              </div>
            </div>
          )}

          {activeTab === 'reviews' && (
            <div className="space-y-8 max-w-4xl">
              {/* Reviews List */}
              <div className="space-y-4">
                {product.reviews && product.reviews.length > 0 ? (
                  product.reviews.map((rev) => (
                    <div key={rev.id} className="bg-white border border-slate-200/90 rounded-2xl p-5 space-y-2.5 shadow-xs">
                      <div className="flex items-center justify-between">
                        <div className="flex items-center gap-2.5">
                          <div className="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                            {rev.user?.name?.charAt(0) || 'U'}
                          </div>
                          <div>
                            <span className="font-bold text-xs text-slate-900">{rev.user?.name}</span>
                            <span className="ml-2 px-2 py-0.5 bg-emerald-50 text-emerald-800 text-[10px] font-bold rounded-full border border-emerald-200">
                              Pembeli Terverifikasi
                            </span>
                          </div>
                        </div>
                        <span className="text-[11px] text-slate-400">{formatDate(rev.created_at)}</span>
                      </div>

                      <div className="flex text-amber-400">
                        {[...Array(5)].map((_, i) => (
                          <Star key={i} size={13} className={i < rev.rating ? 'fill-amber-400' : 'text-slate-200'} />
                        ))}
                      </div>

                      <p className="text-xs sm:text-sm text-slate-700 leading-relaxed">{rev.comment}</p>
                    </div>
                  ))
                ) : (
                  <p className="text-xs text-slate-500 py-4">Belum ada ulasan untuk produk ini. Jadilah yang pertama memberikan review!</p>
                )}
              </div>

              {/* Submit Review Form */}
              <div className="bg-slate-50 border border-slate-200/90 rounded-2xl p-6 space-y-4">
                <h4 className="text-sm font-bold text-slate-900 flex items-center gap-2">
                  <MessageSquarePlus size={16} />
                  Tulis Ulasan Anda
                </h4>

                <form onSubmit={handleReviewSubmit} className="space-y-4">
                  <div>
                    <label className="block text-xs font-bold text-slate-700 mb-1.5">Rating Bintang</label>
                    <div className="flex gap-1.5">
                      {[1, 2, 3, 4, 5].map((star) => (
                        <button
                          type="button"
                          key={star}
                          onMouseEnter={() => setHoverRating(star)}
                          onMouseLeave={() => setHoverRating(0)}
                          onClick={() => setRatingInput(star)}
                          className="p-1 focus:outline-none transition-transform hover:scale-125"
                        >
                          <Star
                            size={22}
                            className={`${
                              (hoverRating || ratingInput) >= star
                                ? 'fill-amber-400 text-amber-400'
                                : 'text-slate-300'
                            }`}
                          />
                        </button>
                      ))}
                    </div>
                  </div>

                  <div>
                    <label className="block text-xs font-bold text-slate-700 mb-1">Komentar & Pengalaman</label>
                    <textarea
                      rows={3}
                      value={commentInput}
                      onChange={(e) => setCommentInput(e.target.value)}
                      required
                      placeholder="Bagikan ulasan jujur Anda mengenai kualitas bahan, kesesuaian deskripsi, dan kepuasan pemakaian..."
                      className="w-full p-3 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-slate-900 transition-colors shadow-xs"
                    />
                  </div>

                  <button
                    type="submit"
                    disabled={isSubmittingReview}
                    className="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 disabled:opacity-50 text-white rounded-xl text-xs font-bold transition-all shadow-sm active:scale-95"
                  >
                    {isSubmittingReview ? 'Mengirim Ulasan...' : 'Kirim Ulasan'}
                  </button>
                </form>
              </div>
            </div>
          )}

          {activeTab === 'shipping' && (
            <div className="text-xs sm:text-sm text-slate-600 space-y-3 leading-relaxed max-w-3xl">
              <p>
                <strong>Proses Pengiriman:</strong> Semua pesanan yang dikonfirmasi sebelum pukul 15:00 WIB akan diproses dan diserahkan ke kurir pada hari yang sama.
              </p>
              <p>
                <strong>Opsi Kurir:</strong> JNE, J&T Express, SiCepat, dan Anteraja dengan resi otomatis terupdate di dashboard.
              </p>
              <p>
                <strong>Kebijakan Garansi:</strong> Kami memberikan jaminan pengembalian dana 100% atau penggantian unit baru apabila terjadi cacat produksi dalam 30 hari pertama.
              </p>
            </div>
          )}
        </div>
      </div>

      {/* Related Products */}
      {relatedProducts.length > 0 && (
        <div className="mt-16 lg:mt-24 border-t border-slate-200 pt-12">
          <h3 className="text-xl sm:text-2xl font-bold text-slate-900 mb-6">Produk Terkait Lainnya</h3>
          <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            {relatedProducts.slice(0, 4).map((p) => (
              <ProductCard key={p.id} product={p} />
            ))}
          </div>
        </div>
      )}
    </div>
  );
};

export default ProductDetailPage;
