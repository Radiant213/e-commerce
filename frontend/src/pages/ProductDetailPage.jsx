import React, { useState, useEffect, useMemo, useRef } from 'react';
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
  Copy,
  Check,
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

const getYouTubeId = (url) => {
  if (!url || typeof url !== 'string') return null;
  const match = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i);
  return match ? match[1] : null;
};

const FALLBACK_PRODUCT_IMAGE = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=80';

const handleImgError = (e) => {
  if (e.currentTarget.src !== FALLBACK_PRODUCT_IMAGE) {
    e.currentTarget.src = FALLBACK_PRODUCT_IMAGE;
  }
};

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
  const [selectedMedia, setSelectedMedia] = useState(null);
  const [selectedVariant, setSelectedVariant] = useState(null);
  const [isCopied, setIsCopied] = useState(false);
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
        setRelatedProducts(data.related_products || data.related || []);

        if (data.product?.variants && data.product.variants.length > 0) {
          setSelectedVariant(data.product.variants[0]);
        }

        const p = data.product;
        const videoUrl = p?.video_url || p?.video_path;
        if (videoUrl) {
          setSelectedMedia({
            id: 'showcase-video',
            url: videoUrl,
            type: 'video',
            is_showcase: true,
            youtubeId: getYouTubeId(videoUrl),
          });
        } else {
          const primary = p?.primary_image || p?.images?.find((i) => i.is_primary) || p?.images?.[0];
          if (primary) {
            const ytId = getYouTubeId(primary.image_path);
            const isVid = primary.is_video || primary.media_type === 'video' || /\.(mp4|mov|webm|ogg)($|\?)/i.test(primary.image_path || '') || !!ytId;
            setSelectedMedia({
              id: primary.id,
              url: primary.image_path,
              type: isVid ? 'video' : 'image',
              youtubeId: ytId,
            });
          } else {
            setSelectedMedia({
              id: 'default-fallback',
              url: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=80',
              type: 'image',
              youtubeId: null,
            });
          }
        }
      } catch (err) {
        console.error('Error fetching product detail:', err);
      } finally {
        setIsLoading(false);
      }
    };

    fetchDetail();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }, [slug]);

  const mediaList = useMemo(() => {
    if (!product) return [];
    const list = [];

    // 1. Showcase Video (Slot 1 Highlight if present)
    const showcaseVideo = product.video_url || product.video_path;
    if (showcaseVideo) {
      list.push({
        id: 'showcase-video',
        url: showcaseVideo,
        type: 'video',
        is_showcase: true,
        youtubeId: getYouTubeId(showcaseVideo),
      });
    }

    // 2. Product Images & Additional Videos (from repeater)
    if (product.images && product.images.length > 0) {
      product.images.forEach((item) => {
        const ytId = getYouTubeId(item.image_path);
        const isVideo = item.is_video || item.media_type === 'video' || /\.(mp4|mov|webm|ogg)($|\?)/i.test(item.image_path || '') || !!ytId;
        list.push({
          id: item.id,
          url: item.image_path,
          type: isVideo ? 'video' : 'image',
          is_primary: item.is_primary,
          is_showcase: false,
          sort_order: item.sort_order ?? 1,
          youtubeId: ytId,
        });
      });
    }

    // Fallback if no images or video
    if (list.length === 0) {
      list.push({
        id: 'default-fallback',
        url: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=80',
        type: 'image',
        is_showcase: false,
      });
    }

    return list;
  }, [product]);

  const currentMedia = selectedMedia || mediaList[0];
  const videoRef = useRef(null);

  const activeVideoUrl = useMemo(() => {
    if (currentMedia?.type === 'video') return currentMedia.url;
    const v = mediaList.find((m) => m.type === 'video');
    return v ? v.url : null;
  }, [currentMedia, mediaList]);

  // Pause / Play video based on whether video media is currently active, preserving timestamp
  useEffect(() => {
    if (videoRef.current) {
      if (currentMedia?.type === 'video') {
        videoRef.current.play().catch(() => {});
      } else {
        videoRef.current.pause();
      }
    }
  }, [currentMedia]);

  const wishlisted = product ? isWishlisted(product.id) : false;
  const discountPercent = product
    ? (product.discount_percent || (product.sale_price ? Math.round(((product.price - product.sale_price) / product.price) * 100) : null))
    : null;

  const handleCopyLink = () => {
    navigator.clipboard.writeText(window.location.href);
    setIsCopied(true);
    addToast({
      title: t('detail_share_copied') || 'Link Disalin!',
      message: 'Tautan produk berhasil disalin ke papan klip.',
      type: 'success',
    });
    setTimeout(() => setIsCopied(false), 2000);
  };

  const handleSelectVariant = (variant) => {
    setSelectedVariant(variant);
    if (variant.image_url) {
      setSelectedMedia({
        id: `variant-${variant.id}`,
        url: variant.image_url,
        type: 'image',
      });
    }
  };

  const currentPrice = selectedVariant
    ? (selectedVariant.effective_price ?? (selectedVariant.sale_price ? Number(selectedVariant.sale_price) : (selectedVariant.price ? Number(selectedVariant.price) : null))) ?? (product?.effective_price || product?.sale_price || product?.price || 0)
    : (product?.effective_price || product?.sale_price || product?.price || 0);

  const originalPrice = selectedVariant?.price
    ? Number(selectedVariant.price)
    : (product?.price || 0);

  const hasDiscount = (selectedVariant?.sale_price && Number(selectedVariant.sale_price) < Number(selectedVariant.price)) ||
    (!selectedVariant && (product?.sale_price || discountPercent));

  const currentStock = selectedVariant ? selectedVariant.stock : (product?.stock ?? 0);
  const isInStock = currentStock > 0;

  useEffect(() => {
    if (selectedVariant && selectedVariant.stock > 0) {
      setQuantity((prev) => (prev > selectedVariant.stock ? selectedVariant.stock : prev));
    }
  }, [selectedVariant]);

  const specsList = useMemo(() => {
    if (!product) return [];
    const list = [];

    if (product.specifications && typeof product.specifications === 'object') {
      if (Array.isArray(product.specifications)) {
        product.specifications.forEach((item) => {
          const key = item?.key || item?.name || item?.label;
          const val = item?.value || item?.val;
          if (key && String(key).trim() !== '' && val && String(val).trim() !== '') {
            list.push({ label: String(key).trim(), value: String(val).trim() });
          }
        });
      } else {
        Object.entries(product.specifications).forEach(([k, v]) => {
          if (k && String(k).trim() !== '' && v !== null && v !== undefined && String(v).trim() !== '') {
            list.push({ label: String(k).trim(), value: String(v).trim() });
          }
        });
      }
    }

    return list;
  }, [product]);

  const handleAddToCart = async () => {
    if (!product) return;
    try {
      await addToCart(product.id, quantity, selectedVariant?.id);
      addToast({
        title: 'Berhasil Masuk Keranjang!',
        message: `${quantity}x ${product.name} ${selectedVariant ? `(${selectedVariant.name})` : ''} telah ditambahkan ke keranjang.`,
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
    if (!product) return;
    try {
      await addToCart(product.id, quantity, selectedVariant?.id);
      navigate('/checkout');
    } catch (err) {
      addToast({
        title: 'Gagal Memproses Pesanan',
        message: err.message || 'Terjadi kesalahan saat memproses pesanan.',
        type: 'error',
      });
    }
  };

  const handleWishlistClick = () => {
    if (!product) return;
    toggleWishlist(product.id);
    addToast({
      title: wishlisted ? 'Dihapus dari Wishlist' : 'Disimpan ke Wishlist',
      message: `${product.name} ${wishlisted ? 'dihapus dari' : 'ditambahkan ke'} wishlist Anda.`,
      type: 'info',
    });
  };

  const handleReviewSubmit = async (e) => {
    e.preventDefault();
    if (!product) return;
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

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 pb-28 sm:pb-8 lg:pb-12 text-left animate-fade-in">
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
        {/* Left Column: Gallery & Share */}
        <div className="lg:col-span-7 space-y-4">
          <div className="flex flex-col-reverse sm:flex-row gap-4">
            {/* Thumbnails */}
            {mediaList.length > 1 && (
              <div className="flex sm:flex-col gap-3 overflow-x-auto sm:overflow-y-auto sm:max-h-[580px] pb-2 sm:pb-0">
                {mediaList.map((item) => {
                  const isSelected = currentMedia?.url === item.url;
                  return (
                    <button
                      key={item.id}
                      type="button"
                      onMouseEnter={() => setSelectedMedia(item)}
                      onClick={() => setSelectedMedia(item)}
                      className={`relative w-16 h-16 sm:w-20 sm:h-20 rounded-2xl overflow-hidden border-2 bg-slate-900/90 flex-shrink-0 transition-all cursor-pointer ${
                        isSelected
                          ? 'border-emerald-500 ring-2 ring-emerald-500/30 shadow-md scale-105'
                          : 'border-slate-200 hover:border-emerald-400'
                      }`}
                    >
                      {item.type === 'video' ? (
                        <div className="w-full h-full relative bg-slate-950 flex items-center justify-center">
                          {item.youtubeId ? (
                            <img
                              src={`https://img.youtube.com/vi/${item.youtubeId}/hqdefault.jpg`}
                              alt={product.name}
                              referrerPolicy="no-referrer"
                              onError={handleImgError}
                              className="w-full h-full object-cover opacity-70 pointer-events-none"
                            />
                          ) : (
                            <video
                              src={item.url}
                              className="w-full h-full object-cover opacity-60 pointer-events-none"
                              muted
                              preload="metadata"
                            />
                          )}
                          <div className="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <div className="w-7 h-7 rounded-full bg-emerald-500 text-slate-950 flex items-center justify-center shadow-lg transition-transform group-hover:scale-110">
                              <svg
                                className="w-3.5 h-3.5 fill-slate-950 text-slate-950"
                                viewBox="0 0 16 16"
                              >
                                <path d="M5 3.5v9l7.5-4.5-7.5-4.5z" />
                              </svg>
                            </div>
                          </div>
                        </div>
                      ) : (
                        <img
                          src={item.url}
                          alt={product.name}
                          referrerPolicy="no-referrer"
                          onError={handleImgError}
                          className="w-full h-full object-cover"
                        />
                      )}
                    </button>
                  );
                })}
              </div>
            )}

            {/* Main Large Media */}
            <div className="flex-1 aspect-square rounded-3xl overflow-hidden bg-slate-950 border border-slate-200/90 relative group shadow-sm flex items-center justify-center">
              {currentMedia?.type === 'video' ? (
                (currentMedia.youtubeId || getYouTubeId(currentMedia.url)) ? (
                  <iframe
                    src={`https://www.youtube-nocookie.com/embed/${currentMedia.youtubeId || getYouTubeId(currentMedia.url)}?autoplay=0&rel=0`}
                    title={product.name}
                    className="w-full h-full rounded-3xl"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowFullScreen
                  />
                ) : (
                  <video
                    ref={videoRef}
                    src={activeVideoUrl}
                    controls
                    muted
                    playsInline
                    loop
                    className="w-full h-full object-contain bg-black rounded-3xl block"
                  >
                    Browser Anda tidak mendukung tag video.
                  </video>
                )
              ) : (
                <img
                  src={currentMedia?.url || FALLBACK_PRODUCT_IMAGE}
                  alt={product.name}
                  referrerPolicy="no-referrer"
                  onError={handleImgError}
                  className="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 ease-out bg-slate-50 block"
                />
              )}

              {/* Discount Badge Overlay (on images) */}
              {discountPercent && currentMedia?.type !== 'video' && (
                <span className="absolute top-5 left-5 px-3 py-1.5 bg-emerald-800 text-white text-xs font-bold rounded-lg tracking-wider shadow-md">
                  {t('card_save')} {discountPercent}%
                </span>
              )}
            </div>
          </div>

          {/* Share & Favorite Bar (Shopee PDP Style) */}
          <div className="flex items-center justify-between pt-3 text-xs text-slate-500 border-t border-slate-100">
            <div className="flex items-center gap-2">
              <span className="font-semibold text-slate-700">{t('detail_share_label') || 'Bagikan'}:</span>
              <button
                type="button"
                onClick={handleCopyLink}
                className="p-1.5 rounded-lg border border-slate-200 hover:bg-slate-100 text-slate-700 flex items-center gap-1 transition-colors cursor-pointer"
                title="Salin Tautan"
              >
                {isCopied ? <Check size={14} className="text-emerald-600" /> : <Copy size={14} />}
                <span className="text-[11px]">{isCopied ? (t('detail_share_copied') || 'Tersalin') : 'Salin'}</span>
              </button>
              <a
                href={`https://api.whatsapp.com/send?text=${encodeURIComponent(product.name + ' - ' + window.location.href)}`}
                target="_blank"
                rel="noopener noreferrer"
                className="p-1.5 rounded-lg border border-slate-200 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 text-slate-700 transition-colors"
                title="Share via WhatsApp"
              >
                <svg className="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                  <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                </svg>
              </a>
              <a
                href={`https://twitter.com/intent/tweet?text=${encodeURIComponent(product.name)}&url=${encodeURIComponent(window.location.href)}`}
                target="_blank"
                rel="noopener noreferrer"
                className="p-1.5 rounded-lg border border-slate-200 hover:bg-sky-50 hover:text-sky-600 hover:border-sky-200 text-slate-700 transition-colors"
                title="Share via X / Twitter"
              >
                <svg className="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                  <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                </svg>
              </a>
            </div>

            <button
              type="button"
              onClick={handleWishlistClick}
              className="flex items-center gap-1.5 text-slate-600 hover:text-red-500 transition-colors cursor-pointer"
            >
              <Heart size={15} className={wishlisted ? 'fill-red-500 text-red-500' : ''} />
              <span>{t('detail_favorite_label') || 'Favorit'}</span>
              <span className="font-semibold text-slate-900">({(product.wishlist_count ?? 0) + (wishlisted ? 1 : 0)})</span>
            </button>
          </div>
        </div>

        {/* Right Column: Product Info & Actions */}
        <div className="lg:col-span-5 space-y-6">
          {/* Category & Status */}
          <div className="flex items-center justify-between">
            <span className="text-xs font-bold uppercase tracking-widest text-slate-700 bg-slate-100 px-3 py-1 rounded-lg">
              {product.category?.name}
            </span>
            <span className={`text-xs font-bold flex items-center gap-1.5 ${isInStock ? 'text-emerald-700' : 'text-red-600'}`}>
              <CheckCircle2 size={14} />
              {isInStock ? `${t('detail_in_stock')} (${currentStock})` : t('detail_out_of_stock')}
            </span>
          </div>

          {/* Product Title */}
          <h1 className="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-snug">
            {product.name}
          </h1>

          {/* Rating Summary */}
          <div className="flex items-center gap-3 text-xs text-slate-600 pb-4 border-b border-slate-100 font-medium">
            <button
              type="button"
              onClick={() => {
                setActiveTab('reviews');
                document.getElementById('product-tabs-section')?.scrollIntoView({ behavior: 'smooth' });
              }}
              className="flex items-center gap-1.5 text-amber-600 font-bold bg-amber-50 hover:bg-amber-100 px-2 py-0.5 rounded-md border border-amber-200 transition-colors cursor-pointer"
            >
              <Star size={14} className="fill-amber-400 text-amber-400" />
              <span>{Number(product.avg_rating || 5.0).toFixed(1)}</span>
            </button>
            <span>&bull;</span>
            <button
              type="button"
              onClick={() => {
                setActiveTab('reviews');
                document.getElementById('product-tabs-section')?.scrollIntoView({ behavior: 'smooth' });
              }}
              className="hover:text-slate-900 underline transition-colors cursor-pointer"
            >
              {product.total_reviews || product.reviews?.length || 0} {t('detail_ratings_label') || 'Penilaian'}
            </button>
            <span>&bull;</span>
            <span>{product.total_sold || 0} {t('detail_sold_label') || 'Terjual'}</span>
          </div>

          {/* Price */}
          <div className="flex items-baseline gap-3">
            <span className="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
              {formatCurrency(currentPrice)}
            </span>
            {hasDiscount && (
              <span className="text-sm sm:text-base text-slate-400 line-through">
                {formatCurrency(originalPrice)}
              </span>
            )}
            {selectedVariant && (
              <span className="text-xs px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 font-semibold border border-slate-200">
                {selectedVariant.name}
              </span>
            )}
          </div>

          {/* Short Description */}
          {product.short_description && (
            <p className="text-xs sm:text-sm text-slate-600 leading-relaxed">
              {product.short_description}
            </p>
          )}

          {/* Variant Selector (Shopee Style) */}
          {product.variants && product.variants.length > 0 && (
            <div className="space-y-3 pt-2">
              <div className="flex items-center justify-between text-xs">
                <span className="font-bold text-slate-700">
                  {t('detail_variants_label') || 'Pilihan Variasi'}:
                </span>
                {selectedVariant && (
                  <span className="text-slate-500 font-medium">
                    {selectedVariant.name}
                  </span>
                )}
              </div>
              <div className="flex flex-wrap gap-2.5">
                {product.variants.map((v) => {
                  const isSelected = selectedVariant?.id === v.id;
                  const isOutOfStock = v.stock <= 0;
                  return (
                    <button
                      key={v.id}
                      type="button"
                      disabled={isOutOfStock}
                      onClick={() => handleSelectVariant(v)}
                      className={`group relative flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold border transition-all cursor-pointer ${
                        isSelected
                          ? 'border-emerald-600 bg-emerald-50/50 text-emerald-900 ring-2 ring-emerald-500/20 shadow-xs'
                          : isOutOfStock
                          ? 'border-dashed border-slate-200 bg-slate-50 text-slate-400 cursor-not-allowed opacity-60'
                          : 'border-slate-200 bg-white text-slate-700 hover:border-slate-400 hover:bg-slate-50'
                      }`}
                    >
                      {v.image_url && (
                        <img
                          src={v.image_url}
                          alt={v.name}
                          className="w-6 h-6 rounded-md object-cover border border-slate-200 flex-shrink-0"
                        />
                      )}
                      <span>{v.name}</span>
                      {v.stock > 0 && v.stock <= 5 && (
                        <span className="text-[10px] text-amber-600 font-normal">
                          (Sisa {v.stock})
                        </span>
                      )}
                      {isSelected && (
                        <span className="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px] shadow-xs">
                          ✓
                        </span>
                      )}
                    </button>
                  );
                })}
              </div>
            </div>
          )}

          {/* Quantity & CTA Actions */}
          <div className="space-y-4 pt-4 border-t border-slate-100">
            <div className="flex items-center gap-4">
              <span className="text-xs font-bold text-slate-700">{t('detail_quantity')}:</span>
              <div className="flex items-center border border-slate-300 rounded-xl bg-slate-50/50">
                <button
                  disabled={quantity <= 1 || !isInStock}
                  onClick={() => setQuantity(Math.max(1, quantity - 1))}
                  className="p-2.5 text-slate-600 hover:text-slate-900 disabled:opacity-30 active:scale-95"
                >
                  <Minus size={14} />
                </button>
                <span className="px-4 text-xs font-bold text-slate-900">{isInStock ? quantity : 0}</span>
                <button
                  disabled={quantity >= currentStock || !isInStock}
                  onClick={() => setQuantity(Math.min(currentStock, quantity + 1))}
                  className="p-2.5 text-slate-600 hover:text-slate-900 disabled:opacity-30 active:scale-95"
                >
                  <Plus size={14} />
                </button>
              </div>
              <span className="text-xs text-slate-500">
                {t('detail_in_stock')}: {currentStock}
              </span>
            </div>

            <div className="fixed sm:static bottom-0 left-0 right-0 p-4 sm:p-0 bg-white sm:bg-transparent border-t border-slate-200 sm:border-0 z-50 sm:z-auto shadow-[0_-10px_30px_-15px_rgba(0,0,0,0.2)] sm:shadow-none flex items-center gap-2 sm:gap-3 pt-3 sm:pt-2">
              <button
                onClick={handleAddToCart}
                disabled={!isInStock}
                className="flex-1 py-3.5 px-4 bg-slate-900 hover:bg-slate-800 disabled:bg-slate-300 text-white font-bold text-xs rounded-xl flex items-center justify-center gap-2 shadow-md transition-all active:scale-95 cursor-pointer disabled:cursor-not-allowed"
              >
                <ShoppingCart size={16} />
                <span>{t('card_add_cart')}</span>
              </button>

              <button
                onClick={handleBuyNow}
                disabled={!isInStock}
                className="flex-1 py-3.5 px-4 bg-emerald-800 hover:bg-emerald-700 disabled:bg-slate-300 text-white font-bold text-xs rounded-xl text-center shadow-md transition-all active:scale-95 cursor-pointer disabled:cursor-not-allowed"
              >
                {t('hero_buy_now')}
              </button>

              <button
                onClick={handleWishlistClick}
                className={`p-3.5 border rounded-xl transition-all active:scale-90 cursor-pointer ${
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

      {/* Tabs: Description & Specifications, Shipping, Reviews */}
      <div id="product-tabs-section" className="mt-16 lg:mt-24 border-t border-slate-200 pt-8 scroll-mt-24">
        <div className="flex border-b border-slate-200 gap-8">
          <button
            onClick={() => setActiveTab('description')}
            className={`pb-3.5 text-xs uppercase font-bold tracking-wider transition-colors relative cursor-pointer ${
              activeTab === 'description' ? 'text-slate-900 border-b-2 border-slate-900' : 'text-slate-400 hover:text-slate-700'
            }`}
          >
            {t('detail_tab_spec')}
          </button>
          <button
            onClick={() => setActiveTab('reviews')}
            className={`pb-3.5 text-xs uppercase font-bold tracking-wider transition-colors relative cursor-pointer ${
              activeTab === 'reviews' ? 'text-slate-900 border-b-2 border-slate-900' : 'text-slate-400 hover:text-slate-700'
            }`}
          >
            {t('detail_tab_reviews')} ({product.reviews?.length || 0})
          </button>
          <button
            onClick={() => setActiveTab('shipping')}
            className={`pb-3.5 text-xs uppercase font-bold tracking-wider transition-colors relative cursor-pointer ${
              activeTab === 'shipping' ? 'text-slate-900 border-b-2 border-slate-900' : 'text-slate-400 hover:text-slate-700'
            }`}
          >
            {t('footer_shipping_guide')}
          </button>
        </div>

        <div className="py-8">
          {activeTab === 'description' && (
            <div className="space-y-10 max-w-4xl text-slate-700">
              {/* SPESIFIKASI PRODUK SECTION (Tampil murni jika diisi oleh admin) */}
              {specsList.length > 0 && (
                <div className="space-y-4">
                  <h3 className="text-xs uppercase font-extrabold tracking-wider text-slate-400 bg-slate-100/80 px-4 py-2 rounded-lg inline-block">
                    {t('detail_specifications_title') || 'SPESIFIKASI PRODUK'}
                  </h3>
                  <div className="border border-slate-200/80 rounded-2xl overflow-hidden bg-white shadow-xs">
                    <table className="w-full text-xs sm:text-sm text-left border-collapse">
                      <tbody>
                        {specsList.map((spec, idx) => (
                          <tr
                            key={idx}
                            className={`border-b border-slate-100 last:border-0 ${
                              idx % 2 === 0 ? 'bg-slate-50/40' : 'bg-white'
                            }`}
                          >
                            <td className="py-3 px-4 sm:px-6 w-1/3 sm:w-1/4 text-slate-500 font-medium">
                              {spec.label}
                            </td>
                            <td className="py-3 px-4 sm:px-6 text-slate-900 font-semibold">
                              {spec.value}
                            </td>
                          </tr>
                        ))}
                      </tbody>
                    </table>
                  </div>
                </div>
              )}

              {/* DESKRIPSI PRODUK SECTION (Rich HTML + Images) */}
              <div className={`space-y-4 ${specsList.length > 0 ? 'pt-4 border-t border-slate-200/80' : ''}`}>
                <h3 className="text-xs uppercase font-extrabold tracking-wider text-slate-400 bg-slate-100/80 px-4 py-2 rounded-lg inline-block">
                  {t('detail_description_title') || 'DESKRIPSI PRODUK'}
                </h3>
                {product.description && /<[a-z][\s\S]*>/i.test(product.description) ? (
                  <div
                    className="rich-description"
                    dangerouslySetInnerHTML={{ __html: product.description }}
                  />
                ) : (
                  <div className="whitespace-pre-line text-xs sm:text-sm text-slate-700 leading-relaxed space-y-4">
                    {product.description}
                  </div>
                )}
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
