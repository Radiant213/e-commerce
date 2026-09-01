import React, { createContext, useContext, useState, useEffect } from 'react';

const LanguageContext = createContext();

export const translations = {
  id: {
    // Navbar
    nav_announcement: 'Pengiriman Gratis ke Seluruh Indonesia untuk Pesanan di Atas Rp 500.000',
    nav_home: 'Beranda',
    nav_all_products: 'Semua Produk',
    nav_gadget: 'Gadget',
    nav_men: 'Pria',
    nav_women: 'Wanita',
    nav_home_living: 'Rumah Tangga',
    nav_search_placeholder: 'Cari headphone, jaket, smartwatch...',
    nav_search_btn: 'Cari',
    nav_login: 'Masuk',
    nav_dashboard: 'Dashboard Akun',
    nav_my_orders: 'Pesanan Saya',
    nav_wishlist: 'Wishlist',
    nav_logout: 'Keluar Akun',
    nav_member_badge: 'MEMBER AKTIF',
    nav_search_results: 'Hasil Pencarian Cepat',
    nav_view_all_results: 'Lihat Semua Hasil',

    // Hero
    hero_badge: 'Koleksi Edisi Terbaru 2026 • Radiant Studio',
    hero_title: 'Desain Esensial untuk Keseharian Lebih Bermakna.',
    hero_desc: 'Temukan perpaduan sempurna antara fungsi esensial, estetika minimalis, dan material berkualitas tinggi tanpa kompromi.',
    hero_explore: 'Jelajahi Katalog',
    hero_featured: 'Produk Pilihan',
    hero_stat_curated: 'Produk Kurasi',
    hero_stat_satisfaction: 'Kepuasan Pembeli',
    hero_stat_guarantee: 'Original Bergaransi',
    hero_spotlight: 'SOROTAN UTAMA',
    hero_spotlight_title: 'Audio Akustik Presisi Tinggi',
    hero_spotlight_desc: 'Ditenagai noise cancellation ganda otomatis',
    hero_buy_now: 'Beli Sekarang',

    // Categories
    cat_curated: 'Kategori Kurasi',
    cat_title: 'Temukan Berdasarkan Kebutuhan',
    cat_view_all: 'Lihat Semua Kategori',
    cat_items_count: 'Koleksi Pilihan',

    // Product Showcase
    showcase_badge: 'Koleksi Unggulan',
    showcase_title: 'Produk Pilihan Spesial',
    tab_featured: 'Pilihan Editor',
    tab_bestsellers: 'Paling Laris',
    tab_new: 'Terbaru',

    // Product Card
    card_save: 'HEMAT',
    card_featured: 'PILIHAN',
    card_add_cart: '+ Masuk Keranjang',
    card_quick_add: '+ Keranjang',
    card_rating: 'Rating',

    // Products Catalog Page
    prod_catalog_title: 'Katalog Produk',
    prod_catalog_subtitle: 'Temukan produk kebutuhan gaya hidup modern dengan kurasi kualitas terbaik.',
    prod_search_for: 'Hasil pencarian untuk:',
    prod_all_categories: 'Semua Kategori',
    prod_sort_by: 'Urutkan:',
    prod_sort_featured: 'Produk Pilihan',
    prod_sort_price_low: 'Harga: Rendah ke Tinggi',
    prod_sort_price_high: 'Harga: Tinggi ke Rendah',
    prod_sort_rating: 'Rating Tertinggi',
    prod_sort_latest: 'Terbaru',
    prod_filter_btn: 'Filter Produk',
    prod_filter_price: 'Rentang Harga',
    prod_filter_reset: 'Reset Filter',
    prod_showing: 'Menampilkan',
    prod_of: 'dari',
    prod_empty_title: 'Tidak Ada Produk Ditemukan',
    prod_empty_desc: 'Coba ubah kata kunci pencarian atau sesuaikan filter Anda.',
    prod_prev: 'Sebelumnya',
    prod_next: 'Selanjutnya',

    // Product Detail Page
    detail_back: 'Kembali ke Katalog',
    detail_in_stock: 'Stok Tersedia',
    detail_out_of_stock: 'Stok Habis',
    detail_sku: 'SKU',
    detail_category: 'Kategori',
    detail_weight: 'Berat',
    detail_quantity: 'Jumlah',
    detail_add_cart: 'Tambah ke Keranjang',
    detail_buy_now: 'Beli Sekarang (Instant Checkout)',
    detail_tab_desc: 'Deskripsi Lengkap',
    detail_tab_spec: 'Spesifikasi & Detail',
    detail_tab_reviews: 'Ulasan Pembeli',
    detail_reviews_count: 'Ulasan Terverifikasi',
    detail_write_review: 'Tulis Ulasan Produk',
    detail_rating_label: 'Beri Rating Bintang',
    detail_comment_label: 'Ulasan / Komentar Anda',
    detail_comment_placeholder: 'Ceritakan pengalaman Anda menggunakan produk ini...',
    detail_submit_review: 'Kirim Ulasan',
    detail_login_to_review: 'Silakan masuk untuk memberikan ulasan produk ini.',
    detail_guarantee_badge: '100% Original & Garansi Resmi',
    detail_free_returns: 'Pengembalian Mudah 30 Hari',

    // Wishlist Page
    wish_title: 'Wishlist Saya',
    wish_subtitle: 'Daftar produk favorit yang Anda simpan untuk dibeli nanti.',
    wish_empty_title: 'Wishlist Anda Masih Kosong',
    wish_empty_desc: 'Simpan produk-produk impian Anda dengan menekan ikon hati di kartu produk.',
    wish_explore_btn: 'Mulai Eksplorasi Produk',
    wish_items_saved: 'produk tersimpan',

    // Checkout Page
    chk_title: 'Checkout & Pembayaran',
    chk_subtitle: 'Pilih alamat pengiriman dan selesaikan transaksi secara aman via MidTrans.',
    chk_step_address: '1. Alamat Pengiriman',
    chk_manage_addresses: 'Kelola Buku Alamat',
    chk_use_manual: '+ Gunakan Alamat Berbeda',
    chk_recipient_name: 'Nama Lengkap Penerima',
    chk_phone: 'Nomor WhatsApp / HP',
    chk_address_line: 'Alamat Lengkap (Jalan, RT/RW, No. Rumah)',
    chk_city: 'Kota / Kabupaten',
    chk_postal: 'Kode Pos',
    chk_notes: 'Catatan Tambahan untuk Kurir (Opsional)',
    chk_step_courier: '2. Pilihan Layanan Kurir',
    chk_courier_reg: 'Reguler Standard (2-3 Hari Kerja)',
    chk_courier_exp: 'Express Kilat (1-2 Hari Kerja)',
    chk_courier_free_badge: 'GRATIS ONGKIR',
    chk_step_payment: '3. Metode Pembayaran',
    chk_pay_midtrans_desc: 'Dukungan QRIS, GoPay, OVO, ShopeePay, Virtual Account BCA, Mandiri, BNI, BRI, & Kartu Kredit.',
    chk_order_summary: 'Ringkasan Pesanan',
    chk_subtotal: 'Subtotal Produk',
    chk_shipping_fee: 'Biaya Pengiriman',
    chk_total: 'Total Pembayaran',
    chk_pay_btn: 'Bayar Sekarang (MidTrans)',
    chk_sec_guarantee: 'Enkripsi SSL 256-bit & Terverifikasi Bank Indonesia',

    // Dashboard Page
    dash_title: 'Dashboard Pengguna',
    dash_tab_overview: 'Ringkasan',
    dash_tab_orders: 'Pesanan Saya',
    dash_tab_wishlist: 'Wishlist',
    dash_tab_addresses: 'Buku Alamat',
    dash_tab_profile: 'Profil Saya',
    dash_tab_password: 'Keamanan & Sandi',
    dash_stat_spent: 'Total Belanja Selesai',
    dash_stat_active: 'Pesanan Aktif',
    dash_stat_completed: 'Pesanan Selesai',
    dash_order_number: 'No. Pesanan',
    dash_order_date: 'Tanggal',
    dash_order_items: 'Barang',
    dash_order_total: 'Total Tagihan',
    dash_order_status: 'Status Pesanan',
    dash_status_paid: 'Lunas (Dibayar)',
    dash_status_pending: 'Menunggu Pembayaran',
    dash_status_processing: 'Sedang Diproses',
    dash_status_shipped: 'Dalam Pengiriman',
    dash_status_delivered: 'Pesanan Terkirim',
    dash_status_cancelled: 'Dibatalkan',
    dash_btn_pay: 'Bayar Sekarang',
    dash_btn_cancel: 'Batalkan Pesanan',
    dash_addr_add: '+ Tambah Alamat Baru',
    dash_addr_primary: 'Alamat Utama',
    dash_addr_set_primary: 'Jadikan Alamat Utama',
    dash_addr_edit: 'Edit',
    dash_addr_delete: 'Hapus',
    dash_save_changes: 'Simpan Perubahan',

    // Auth (Login / Register)
    auth_login_title: 'Selamat Datang Kembali',
    auth_login_desc: 'Masuk ke akun Radiant Studio Anda untuk melanjutkan transaksi.',
    auth_demo_title: '1 Click Akun Demo:',
    auth_email: 'Alamat Email',
    auth_password: 'Kata Sandi',
    auth_login_btn: 'Masuk Sekarang',
    auth_no_account: 'Belum punya akun?',
    auth_register_link: 'Daftar Akun Baru',
    auth_reg_title: 'Daftar Akun Baru',
    auth_reg_desc: 'Daftarkan diri Anda untuk menikmati kemudahan berbelanja dan promo eksklusif.',
    auth_name: 'Nama Lengkap',
    auth_phone: 'Nomor Telepon',
    auth_password_confirm: 'Konfirmasi Kata Sandi',
    auth_reg_btn: 'Daftar Sekarang',
    auth_has_account: 'Sudah punya akun?',

    // Brand Story
    story_badge: 'STANDAR RADIANT',
    story_title: 'Kesederhanaan adalah Bentuk Tertinggi dari Kecanggihan.',
    story_desc: 'Setiap barang yang kami kurasi telah melewati uji fungsi menyeluruh, ketahanan material kelas atas, dan standar estetika modern yang tidak lekang oleh waktu.',
    story_point_1: 'Material premium teruji ketahanan jangka panjang',
    story_point_2: 'Jaminan garansi resmi dan kemudahan retur 30 hari',
    story_point_3: 'Pembayaran aman dengan proteksi enkripsi MidTrans',

    // Newsletter
    news_title: 'Dapatkan Voucher Diskon 10%',
    news_desc: 'Daftarkan email Anda untuk menerima informasi peluncuran produk edisi terbatas dan diskon khusus anggota.',
    news_placeholder: 'Masukkan alamat email Anda...',
    news_btn: 'Langganan',

    // Cart Drawer
    cart_title: 'Keranjang Belanja',
    cart_ready_checkout: 'barang siap di-checkout',
    cart_free_shipping_add: 'Tambah',
    cart_free_shipping_more: 'lagi untuk',
    cart_free_shipping_bold: 'Gratis Ongkir',
    cart_free_shipping_success: 'Selamat! Anda berhak Gratis Ongkir!',
    cart_empty_title: 'Keranjang Anda Kosong',
    cart_empty_desc: 'Jelajahi koleksi kami dan temukan berbagai produk pilihan berkualitas.',
    cart_start_shopping: 'Mulai Belanja Sekarang',
    cart_subtotal: 'Subtotal Belanja:',
    cart_tax_note: 'Pajak dan ongkos kirim dihitung otomatis saat checkout.',
    cart_checkout_btn: 'Lanjut ke Checkout',

    // Footer
    footer_pillar_shipping: 'Bebas Biaya Kirim',
    footer_pillar_shipping_sub: 'Untuk pesanan di atas Rp 500rb',
    footer_pillar_orig: '100% Produk Asli',
    footer_pillar_orig_sub: 'Garansi uang kembali penuh',
    footer_pillar_guar: 'Garansi 30 Hari',
    footer_pillar_guar_sub: 'Pengembalian mudah & cepat',
    footer_pillar_cs: 'Layanan Pelanggan',
    footer_pillar_cs_sub: 'Siap melayani setiap hari 24/7',
    footer_about_desc: 'Platform e-commerce dengan kurasi produk berkualitas tinggi untuk memenuhi gaya hidup modern yang esensial dan fungsional.',
    footer_verified_pay: 'Pembayaran Resmi Terverifikasi:',
    footer_cat_title: 'Kategori',
    footer_service_title: 'Layanan',
    footer_about_title: 'Tentang',
    footer_track_order: 'Lacak Pesanan',
    footer_my_wishlist: 'Wishlist Saya',
    footer_warranty: 'Kebijakan Garansi',
    footer_shipping_guide: 'Panduan Pengiriman',
    footer_about_us: 'Tentang Radiant Studio',
    footer_terms: 'Syarat & Ketentuan',
    footer_privacy: 'Kebijakan Privasi',
    footer_contact: 'Hubungi Kami',
    footer_rights: 'All rights reserved.',
    footer_lang_title: 'Pilih Bahasa',
  },
  en: {
    // Navbar
    nav_announcement: 'Free Shipping Nationwide across Indonesia for Orders Over Rp 500,000',
    nav_home: 'Home',
    nav_all_products: 'All Products',
    nav_gadget: 'Gadgets',
    nav_men: 'Men',
    nav_women: 'Women',
    nav_home_living: 'Home & Living',
    nav_search_placeholder: 'Search headphones, jackets, smartwatches...',
    nav_search_btn: 'Search',
    nav_login: 'Sign In',
    nav_dashboard: 'Account Dashboard',
    nav_my_orders: 'My Orders',
    nav_wishlist: 'Wishlist',
    nav_logout: 'Sign Out',
    nav_member_badge: 'ACTIVE MEMBER',
    nav_search_results: 'Quick Search Results',
    nav_view_all_results: 'View All Results',

    // Hero
    hero_badge: 'Latest 2026 Collection • Radiant Studio',
    hero_title: 'Essential Design for More Meaningful Everyday Living.',
    hero_desc: 'Discover the perfect harmony of essential functionality, minimalist aesthetics, and uncompromising premium craftsmanship.',
    hero_explore: 'Explore Catalog',
    hero_featured: 'Featured Picks',
    hero_stat_curated: 'Curated Items',
    hero_stat_satisfaction: 'Customer Rating',
    hero_stat_guarantee: '100% Guaranteed',
    hero_spotlight: 'SPOTLIGHT PICK',
    hero_spotlight_title: 'High-Precision Acoustic Audio',
    hero_spotlight_desc: 'Powered by dual automatic active noise cancellation',
    hero_buy_now: 'Shop Now',

    // Categories
    cat_curated: 'Curated Categories',
    cat_title: 'Explore By Lifestyle Needs',
    cat_view_all: 'View All Categories',
    cat_items_count: 'Curated Items',

    // Product Showcase
    showcase_badge: 'Featured Showcase',
    showcase_title: 'Specially Handpicked Products',
    tab_featured: "Editor's Choice",
    tab_bestsellers: 'Best Sellers',
    tab_new: 'New Arrivals',

    // Product Card
    card_save: 'SAVE',
    card_featured: 'FEATURED',
    card_add_cart: '+ Add to Cart',
    card_quick_add: '+ Cart',
    card_rating: 'Rating',

    // Products Catalog Page
    prod_catalog_title: 'Product Catalog',
    prod_catalog_subtitle: 'Discover curated modern lifestyle essentials crafted with uncompromising quality.',
    prod_search_for: 'Search results for:',
    prod_all_categories: 'All Categories',
    prod_sort_by: 'Sort by:',
    prod_sort_featured: 'Featured',
    prod_sort_price_low: 'Price: Low to High',
    prod_sort_price_high: 'Price: High to Low',
    prod_sort_rating: 'Highest Rated',
    prod_sort_latest: 'Newest Arrivals',
    prod_filter_btn: 'Filter Products',
    prod_filter_price: 'Price Range',
    prod_filter_reset: 'Reset Filters',
    prod_showing: 'Showing',
    prod_of: 'of',
    prod_empty_title: 'No Products Found',
    prod_empty_desc: 'Try adjusting your search query or reset your active filters.',
    prod_prev: 'Previous',
    prod_next: 'Next',

    // Product Detail Page
    detail_back: 'Back to Catalog',
    detail_in_stock: 'In Stock',
    detail_out_of_stock: 'Out of Stock',
    detail_sku: 'SKU',
    detail_category: 'Category',
    detail_weight: 'Weight',
    detail_quantity: 'Quantity',
    detail_add_cart: 'Add to Shopping Cart',
    detail_buy_now: 'Buy Now (Instant Checkout)',
    detail_tab_desc: 'Product Overview',
    detail_tab_spec: 'Specifications',
    detail_tab_reviews: 'Customer Reviews',
    detail_reviews_count: 'Verified Customer Reviews',
    detail_write_review: 'Write a Review',
    detail_rating_label: 'Star Rating',
    detail_comment_label: 'Your Review / Feedback',
    detail_comment_placeholder: 'Share your genuine experience with this product...',
    detail_submit_review: 'Post Review',
    detail_login_to_review: 'Please sign in to share a review for this product.',
    detail_guarantee_badge: '100% Authentic with Official Warranty',
    detail_free_returns: 'Hassle-Free 30-Day Returns',

    // Wishlist Page
    wish_title: 'My Wishlist',
    wish_subtitle: 'Your curated personal wishlist saved for future purchases.',
    wish_empty_title: 'Your Wishlist is Empty',
    wish_empty_desc: 'Tap the heart icon on any product card to save your favorite items here.',
    wish_explore_btn: 'Explore Products',
    wish_items_saved: 'items saved',

    // Checkout Page
    chk_title: 'Checkout & Payment',
    chk_subtitle: 'Select your shipping destination and complete payment safely via MidTrans.',
    chk_step_address: '1. Shipping Address',
    chk_manage_addresses: 'Manage Address Book',
    chk_use_manual: '+ Use a Different Address',
    chk_recipient_name: 'Full Recipient Name',
    chk_phone: 'Phone / WhatsApp Number',
    chk_address_line: 'Full Street Address (Street name, unit, house no.)',
    chk_city: 'City / Region',
    chk_postal: 'Postal Code',
    chk_notes: 'Delivery Instructions for Courier (Optional)',
    chk_step_courier: '2. Courier Shipping Service',
    chk_courier_reg: 'Standard Delivery (2-3 Business Days)',
    chk_courier_exp: 'Express Priority (1-2 Business Days)',
    chk_courier_free_badge: 'FREE SHIPPING',
    chk_step_payment: '3. Payment Method',
    chk_pay_midtrans_desc: 'Seamless payments via QRIS, GoPay, OVO, ShopeePay, BCA, Mandiri, BNI, BRI, & Credit Cards.',
    chk_order_summary: 'Order Summary',
    chk_subtotal: 'Items Subtotal',
    chk_shipping_fee: 'Shipping Fee',
    chk_total: 'Total Payment',
    chk_pay_btn: 'Pay Now with MidTrans',
    chk_sec_guarantee: '256-bit SSL Encrypted & Bank Indonesia Certified',

    // Dashboard Page
    dash_title: 'Account Dashboard',
    dash_tab_overview: 'Overview',
    dash_tab_orders: 'My Orders',
    dash_tab_wishlist: 'Wishlist',
    dash_tab_addresses: 'Address Book',
    dash_tab_profile: 'My Profile',
    dash_tab_password: 'Security & Password',
    dash_stat_spent: 'Total Completed Spent',
    dash_stat_active: 'Active Orders',
    dash_stat_completed: 'Completed Orders',
    dash_order_number: 'Order Number',
    dash_order_date: 'Date',
    dash_order_items: 'Items',
    dash_order_total: 'Total Amount',
    dash_order_status: 'Order Status',
    dash_status_paid: 'Paid (Settled)',
    dash_status_pending: 'Pending Payment',
    dash_status_processing: 'Processing',
    dash_status_shipped: 'Shipped',
    dash_status_delivered: 'Delivered',
    dash_status_cancelled: 'Cancelled',
    dash_btn_pay: 'Pay Now',
    dash_btn_cancel: 'Cancel Order',
    dash_addr_add: '+ Add New Address',
    dash_addr_primary: 'Primary Address',
    dash_addr_set_primary: 'Set as Primary',
    dash_addr_edit: 'Edit',
    dash_addr_delete: 'Delete',
    dash_save_changes: 'Save Changes',

    // Auth (Login / Register)
    auth_login_title: 'Welcome Back',
    auth_login_desc: 'Sign in to your Radiant Studio account to manage your orders.',
    auth_demo_title: '1-Click Demo Accounts:',
    auth_email: 'Email Address',
    auth_password: 'Password',
    auth_login_btn: 'Sign In Now',
    auth_no_account: "Don't have an account?",
    auth_register_link: 'Create an Account',
    auth_reg_title: 'Create an Account',
    auth_reg_desc: 'Register now to enjoy effortless shopping and exclusive member-only promotions.',
    auth_name: 'Full Name',
    auth_phone: 'Phone Number',
    auth_password_confirm: 'Confirm Password',
    auth_reg_btn: 'Register Now',
    auth_has_account: 'Already have an account?',

    // Brand Story
    story_badge: 'RADIANT STANDARD',
    story_title: 'Simplicity is the Ultimate Form of Sophistication.',
    story_desc: 'Every item in our collection is rigorously evaluated for enduring durability, tactile elegance, and timeless modern aesthetics.',
    story_point_1: 'Premium materials rigorously tested for longevity',
    story_point_2: 'Official warranty with seamless 30-day returns',
    story_point_3: 'Secured payments protected by MidTrans 256-bit encryption',

    // Newsletter
    news_title: 'Get 10% Off Your First Order',
    news_desc: 'Subscribe to our newsletter for exclusive access to limited-edition drops and member-only promotions.',
    news_placeholder: 'Enter your email address...',
    news_btn: 'Subscribe',

    // Cart Drawer
    cart_title: 'Shopping Cart',
    cart_ready_checkout: 'items ready for checkout',
    cart_free_shipping_add: 'Add',
    cart_free_shipping_more: 'more to unlock',
    cart_free_shipping_bold: 'Free Shipping',
    cart_free_shipping_success: 'Congratulations! You unlocked Free Shipping!',
    cart_empty_title: 'Your Cart is Empty',
    cart_empty_desc: 'Explore our catalog and find premium curated goods for your lifestyle.',
    cart_start_shopping: 'Start Shopping Now',
    cart_subtotal: 'Cart Subtotal:',
    cart_tax_note: 'Taxes and shipping fees are calculated automatically at checkout.',
    cart_checkout_btn: 'Proceed to Checkout',

    // Footer
    footer_pillar_shipping: 'Free Nationwide Shipping',
    footer_pillar_shipping_sub: 'For all orders over Rp 500k',
    footer_pillar_orig: '100% Authentic Products',
    footer_pillar_orig_sub: 'Full money-back guarantee',
    footer_pillar_guar: '30-Day Warranty',
    footer_pillar_guar_sub: 'Easy & fast hassle-free returns',
    footer_pillar_cs: 'Dedicated Support',
    footer_pillar_cs_sub: '24/7 round-the-clock service',
    footer_about_desc: 'Curated e-commerce platform offering premium everyday essentials designed for modern, functional lifestyles.',
    footer_verified_pay: 'Verified Payment Gateways:',
    footer_cat_title: 'Categories',
    footer_service_title: 'Services',
    footer_about_title: 'About Us',
    footer_track_order: 'Track My Order',
    footer_my_wishlist: 'My Wishlist',
    footer_warranty: 'Warranty Policy',
    footer_shipping_guide: 'Shipping Guide',
    footer_about_us: 'About Radiant Studio',
    footer_terms: 'Terms & Conditions',
    footer_privacy: 'Privacy Policy',
    footer_contact: 'Contact Us',
    footer_rights: 'All rights reserved.',
    footer_lang_title: 'Language',
  },
};

export const LanguageProvider = ({ children }) => {
  const [language, setLanguage] = useState(() => {
    return localStorage.getItem('app_language') || 'id';
  });

  useEffect(() => {
    localStorage.setItem('app_language', language);
    document.documentElement.lang = language;
  }, [language]);

  const t = (key) => {
    return translations[language]?.[key] || translations['id']?.[key] || key;
  };

  const changeLanguage = (lang) => {
    if (lang === 'id' || lang === 'en') {
      setLanguage(lang);
    }
  };

  return (
    <LanguageContext.Provider value={{ language, setLanguage: changeLanguage, t }}>
      {children}
    </LanguageContext.Provider>
  );
};

export const useLanguage = () => {
  const context = useContext(LanguageContext);
  if (!context) {
    throw new Error('useLanguage must be used within a LanguageProvider');
  }
  return context;
};
