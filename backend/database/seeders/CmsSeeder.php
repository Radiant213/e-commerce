<?php

namespace Database\Seeders;

use App\Models\Cms\Banner;
use App\Models\Cms\CmsSetting;
use App\Models\Cms\EmailTemplate;
use App\Models\Cms\HomepageSection;
use App\Models\Cms\Menu;
use App\Models\Cms\MenuItem;
use App\Models\Cms\Page;
use App\Models\Cms\PageBlock;
use App\Models\Cms\Popup;
use App\Models\User;
use App\Support\CmsCache;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create or migrate Content Editor test account
        // Migrate legacy editor email if present
        $legacyEditor = User::where('email', 'editor@radiantstudio.com')->first();
        if ($legacyEditor) {
            $legacyEditor->update([
                'email' => 'cms@radiantcode.web.id',
                'name' => 'Content Editor',
                'password' => Hash::make('password'),
                'role' => 'content_editor',
                'email_verified_at' => now(),
            ]);
        } else {
            User::firstOrCreate(
                ['email' => 'cms@radiantcode.web.id'],
                [
                    'name' => 'Content Editor',
                    'password' => Hash::make('password'),
                    'role' => 'content_editor',
                    'email_verified_at' => now(),
                ]
            );
        }

        // 2. Default CMS Settings (non-destructive: firstOrCreate preserves existing user edits)
        $settings = [
            // General
            ['key' => 'site_name', 'label' => 'Nama Toko', 'value' => 'Radiant Studio', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_tagline', 'label' => 'Tagline', 'value' => 'Premium E-Commerce Experience', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_email', 'label' => 'Email Toko', 'value' => 'hello@radiantcode.web.id', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_phone', 'label' => 'No. Telepon', 'value' => '+62 878-7844-4402', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_whatsapp', 'label' => 'WhatsApp CS', 'value' => '+6287878444402', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_address', 'label' => 'Alamat Toko', 'value' => 'Jl. Boulevard Raya Blok M No. 21, Jakarta Selatan, Indonesia', 'type' => 'textarea', 'group' => 'general'],
            ['key' => 'site_currency', 'label' => 'Mata Uang', 'value' => 'IDR', 'type' => 'text', 'group' => 'general'],
            ['key' => 'copyright_text', 'label' => 'Copyright', 'value' => '© {year} Radiant Studio. Hak cipta dilindungi undang-undang.', 'type' => 'text', 'group' => 'general'],
            ['key' => 'powered_by_text', 'label' => 'Powered By', 'value' => 'Powered by Radiant Studio Commerce Engine', 'type' => 'text', 'group' => 'general'],
            ['key' => 'maintenance_mode', 'label' => 'Maintenance Mode', 'value' => '0', 'type' => 'boolean', 'group' => 'general'],

            // Branding
            ['key' => 'primary_color', 'label' => 'Warna Primer', 'value' => '#0f172a', 'type' => 'color', 'group' => 'branding'],
            ['key' => 'accent_color', 'label' => 'Warna Aksen', 'value' => '#059669', 'type' => 'color', 'group' => 'branding'],
            ['key' => 'font_family', 'label' => 'Font Family', 'value' => 'Plus Jakarta Sans', 'type' => 'select', 'group' => 'branding'],

            // Announcement Bar
            ['key' => 'announcement_active', 'label' => 'Announcement Aktif', 'value' => '1', 'type' => 'boolean', 'group' => 'announcement'],
            ['key' => 'announcement_text', 'label' => 'Teks Announcement (ID)', 'value' => '🚚 Bebas Ongkir ke Seluruh Indonesia untuk Pesanan di Atas Rp 100.000!', 'type' => 'text', 'group' => 'announcement'],
            ['key' => 'announcement_text_en', 'label' => 'Teks Announcement (EN)', 'value' => '🚚 Free Shipping Across Indonesia for Orders Over Rp 100,000!', 'type' => 'text', 'group' => 'announcement'],
            ['key' => 'announcement_link', 'label' => 'Link Announcement', 'value' => '/products', 'type' => 'text', 'group' => 'announcement'],
            ['key' => 'announcement_bg_color', 'label' => 'BG Announcement', 'value' => '#0f172a', 'type' => 'color', 'group' => 'announcement'],
            ['key' => 'announcement_text_color', 'label' => 'Teks BG Announcement', 'value' => '#ffffff', 'type' => 'color', 'group' => 'announcement'],

            // Social Media
            ['key' => 'social_whatsapp', 'label' => 'WhatsApp Link', 'value' => 'https://wa.me/6287878444402', 'type' => 'text', 'group' => 'social'],
            ['key' => 'social_instagram', 'label' => 'Instagram', 'value' => 'https://instagram.com/radofdiant', 'type' => 'text', 'group' => 'social'],
            ['key' => 'social_twitter', 'label' => 'Twitter / X', 'value' => 'https://twitter.com/radiant213_', 'type' => 'text', 'group' => 'social'],
            ['key' => 'social_github', 'label' => 'GitHub', 'value' => 'https://github.com/Radiant213', 'type' => 'text', 'group' => 'social'],
            ['key' => 'social_tiktok', 'label' => 'TikTok', 'value' => '', 'type' => 'text', 'group' => 'social'],
            ['key' => 'social_youtube', 'label' => 'YouTube', 'value' => '', 'type' => 'text', 'group' => 'social'],

            // Footer
            ['key' => 'footer_about_desc', 'label' => 'Deskripsi Footer (ID)', 'value' => 'Platform e-commerce premium terpercaya dengan kurasi produk berkualitas, jaminan produk 100% original, dan pengalaman belanja modern.', 'type' => 'textarea', 'group' => 'footer'],
            ['key' => 'footer_about_desc_en', 'label' => 'Deskripsi Footer (EN)', 'value' => 'Trusted premium e-commerce platform featuring curated quality products, 100% authentic guarantee, and a seamless shopping experience.', 'type' => 'textarea', 'group' => 'footer'],
            ['key' => 'footer_verified_payment', 'label' => 'Label Pembayaran', 'value' => 'Metode Pembayaran Resmi', 'type' => 'text', 'group' => 'footer'],
            ['key' => 'footer_payment_methods', 'label' => 'Metode Pembayaran', 'value' => 'MIDTRANS,QRIS,BCA,MANDIRI,BNI,BRI,GOPAY,OVO,SHOPEEPAY', 'type' => 'text', 'group' => 'footer'],
            [
                'key' => 'footer_trust_pillars',
                'label' => 'Trust Pillars',
                'value' => json_encode([
                    ['icon' => 'Truck', 'title' => 'Gratis Ongkir', 'title_en' => 'Free Shipping', 'subtitle' => 'Seluruh Indonesia', 'subtitle_en' => 'Across Indonesia'],
                    ['icon' => 'ShieldCheck', 'title' => '100% Original', 'title_en' => '100% Authentic', 'subtitle' => 'Produk Terverifikasi', 'subtitle_en' => 'Verified Products'],
                    ['icon' => 'RotateCcw', 'title' => 'Garansi 30 Hari', 'title_en' => '30-Day Guarantee', 'subtitle' => 'Uang Kembali', 'subtitle_en' => 'Money Back'],
                    ['icon' => 'Headphones', 'title' => 'Layanan 24/7', 'title_en' => '24/7 Support', 'subtitle' => 'Siap Membantu', 'subtitle_en' => 'Ready to Help'],
                ]),
                'type' => 'json',
                'group' => 'footer',
            ],

            // SEO
            ['key' => 'seo_title_template', 'label' => 'SEO Title Template', 'value' => '{page} | Radiant Studio', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'seo_default_description', 'label' => 'SEO Default Description (ID)', 'value' => 'Temukan koleksi produk gaya hidup premium, elektronik, dan fashion terkini di Radiant Studio dengan promo eksklusif dan jaminan original.', 'type' => 'textarea', 'group' => 'seo'],
            ['key' => 'seo_default_description_en', 'label' => 'SEO Default Description (EN)', 'value' => 'Discover the latest premium lifestyle, electronics, and fashion collections at Radiant Studio with exclusive deals and authentic guarantee.', 'type' => 'textarea', 'group' => 'seo'],
        ];

        foreach ($settings as $setting) {
            CmsSetting::firstOrCreate(['key' => $setting['key']], $setting);
        }

        // 3. Navigation Menus
        $headerMenu = Menu::firstOrCreate(
            ['location' => 'header'],
            ['name' => 'Navigasi Utama (Header)', 'is_active' => true]
        );

        $headerItems = [
            ['label' => 'Home', 'label_translations' => ['id' => 'Beranda', 'en' => 'Home'], 'url' => '/', 'sort_order' => 1],
            ['label' => 'Produk', 'label_translations' => ['id' => 'Koleksi Produk', 'en' => 'All Products'], 'url' => '/products', 'sort_order' => 2],
            ['label' => 'Tentang Kami', 'label_translations' => ['id' => 'Tentang Kami', 'en' => 'About Us'], 'url' => '/pages/about', 'sort_order' => 3],
            ['label' => 'Bantuan & FAQ', 'label_translations' => ['id' => 'Bantuan & FAQ', 'en' => 'Help & FAQ'], 'url' => '/pages/faq', 'sort_order' => 4],
            ['label' => 'Kontak', 'label_translations' => ['id' => 'Hubungi Kami', 'en' => 'Contact Us'], 'url' => '/pages/contact', 'sort_order' => 5],
        ];

        foreach ($headerItems as $item) {
            MenuItem::firstOrCreate(
                ['menu_id' => $headerMenu->id, 'url' => $item['url']],
                array_merge($item, ['menu_id' => $headerMenu->id, 'type' => 'custom', 'is_visible' => true])
            );
        }

        // Footer Column 1: Produk & Belanja
        $footerCol1 = Menu::firstOrCreate(
            ['location' => 'footer_col_1'],
            ['name' => 'Footer Kolom 1 (Belanja)', 'is_active' => true]
        );
        $col1Items = [
            ['label' => 'Semua Produk', 'label_translations' => ['id' => 'Semua Produk', 'en' => 'All Products'], 'url' => '/products', 'sort_order' => 1],
            ['label' => 'Produk Terbaru', 'label_translations' => ['id' => 'Produk Terbaru', 'en' => 'New Arrivals'], 'url' => '/products?sort=latest', 'sort_order' => 2],
            ['label' => 'Paling Populer', 'label_translations' => ['id' => 'Paling Populer', 'en' => 'Best Sellers'], 'url' => '/products?sort=popular', 'sort_order' => 3],
        ];
        foreach ($col1Items as $item) {
            MenuItem::firstOrCreate(
                ['menu_id' => $footerCol1->id, 'url' => $item['url']],
                array_merge($item, ['menu_id' => $footerCol1->id, 'type' => 'custom', 'is_visible' => true])
            );
        }

        // Footer Column 2: Layanan & Informasi
        $footerCol2 = Menu::firstOrCreate(
            ['location' => 'footer_col_2'],
            ['name' => 'Footer Kolom 2 (Bantuan)', 'is_active' => true]
        );
        $col2Items = [
            ['label' => 'Bantuan & FAQ', 'label_translations' => ['id' => 'Bantuan & FAQ', 'en' => 'Help & FAQ'], 'url' => '/pages/faq', 'sort_order' => 1],
            ['label' => 'Informasi Pengiriman', 'label_translations' => ['id' => 'Informasi Pengiriman', 'en' => 'Shipping Info'], 'url' => '/pages/shipping', 'sort_order' => 2],
            ['label' => 'Kebijakan Pengembalian', 'label_translations' => ['id' => 'Kebijakan Pengembalian', 'en' => 'Return Policy'], 'url' => '/pages/returns', 'sort_order' => 3],
            ['label' => 'Lacak Pesanan', 'label_translations' => ['id' => 'Lacak Pesanan', 'en' => 'Track Order'], 'url' => '/orders', 'sort_order' => 4],
        ];
        foreach ($col2Items as $item) {
            MenuItem::firstOrCreate(
                ['menu_id' => $footerCol2->id, 'url' => $item['url']],
                array_merge($item, ['menu_id' => $footerCol2->id, 'type' => 'custom', 'is_visible' => true])
            );
        }

        // Footer Column 3: Perusahaan & Legal
        $footerCol3 = Menu::firstOrCreate(
            ['location' => 'footer_col_3'],
            ['name' => 'Footer Kolom 3 (Perusahaan)', 'is_active' => true]
        );
        $col3Items = [
            ['label' => 'Tentang Radiant Studio', 'label_translations' => ['id' => 'Tentang Radiant Studio', 'en' => 'About Radiant Studio'], 'url' => '/pages/about', 'sort_order' => 1],
            ['label' => 'Hubungi Kami', 'label_translations' => ['id' => 'Hubungi Kami', 'en' => 'Contact Us'], 'url' => '/pages/contact', 'sort_order' => 2],
            ['label' => 'Syarat & Ketentuan', 'label_translations' => ['id' => 'Syarat & Ketentuan', 'en' => 'Terms & Conditions'], 'url' => '/pages/terms', 'sort_order' => 3],
            ['label' => 'Kebijakan Privasi', 'label_translations' => ['id' => 'Kebijakan Privasi', 'en' => 'Privacy Policy'], 'url' => '/pages/privacy', 'sort_order' => 4],
        ];
        foreach ($col3Items as $item) {
            MenuItem::firstOrCreate(
                ['menu_id' => $footerCol3->id, 'url' => $item['url']],
                array_merge($item, ['menu_id' => $footerCol3->id, 'type' => 'custom', 'is_visible' => true])
            );
        }

        // 4. Static Pages with Rich Content Blocks
        $pages = [
            [
                'title' => 'Tentang Kami',
                'title_translations' => ['id' => 'Tentang Kami', 'en' => 'About Us'],
                'slug' => 'about',
                'template' => 'default',
                'status' => 'published',
                'show_in_nav' => true,
                'show_in_footer' => true,
                'blocks' => [
                    [
                        'type' => 'heading',
                        'content' => ['level' => 1, 'text' => 'Kisah Radiant Studio: Dedikasi untuk Kualitas'],
                        'content_translations' => ['en' => ['text' => 'The Story of Radiant Studio: Dedicated to Quality']],
                    ],
                    [
                        'type' => 'text',
                        'content' => ['body' => 'Radiant Studio didirikan dengan satu visi sederhana: menghadirkan barang-barang berkualitas premium terbaik kepada pelanggan tanpa kompromi. Kami percaya bahwa setiap produk memiliki cerita dan kepuasan Anda adalah prioritas utama kami.'],
                        'content_translations' => ['en' => ['body' => 'Radiant Studio was founded with a simple vision: delivering the finest premium quality goods to customers without compromise. We believe every product tells a story and your satisfaction is our utmost priority.']],
                    ],
                    [
                        'type' => 'accordion',
                        'content' => [
                            'items' => [
                                ['title' => 'Visi Kami', 'body' => 'Menjadi platform e-commerce pilihan pertama bagi masyarakat modern yang menghargai kualitas, orisinalitas, dan kenyamanan bertransaksi.'],
                                ['title' => 'Misi Kami', 'body' => '1. Mengurasi hanya produk terbaik dari manufaktur dan brand terpercaya.\n2. Memberikan layanan pelanggan responsif 24/7.\n3. Menjamin transparansi harga dan proses pengiriman yang cepat.'],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Bantuan & FAQ',
                'title_translations' => ['id' => 'Bantuan & FAQ', 'en' => 'Help & FAQ'],
                'slug' => 'faq',
                'template' => 'default',
                'status' => 'published',
                'show_in_nav' => true,
                'show_in_footer' => true,
                'blocks' => [
                    [
                        'type' => 'heading',
                        'content' => ['level' => 1, 'text' => 'Pertanyaan yang Sering Diajukan (FAQ)'],
                        'content_translations' => ['en' => ['text' => 'Frequently Asked Questions (FAQ)']],
                    ],
                    [
                        'type' => 'accordion',
                        'content' => [
                            'items' => [
                                ['title' => 'Bagaimana cara melakukan pemesanan?', 'body' => 'Pilih produk yang Anda inginkan, klik "Tambah ke Keranjang", lalu ikuti proses Checkout dengan melengkapi alamat pengiriman dan memilih metode pembayaran yang tersedia.'],
                                ['title' => 'Metode pembayaran apa saja yang didukung?', 'body' => 'Kami mendukung berbagai saluran pembayaran melalui Midtrans: Virtual Account (BCA, Mandiri, BNI, BRI), QRIS, GoPay, OVO, ShopeePay, serta Transfer Bank Manual.'],
                                ['title' => 'Berapa lama proses pengiriman barang?', 'body' => 'Pesanan diproses dalam 1x24 jam kerja. Estimasi pengiriman reguler berkisar 1-3 hari kerja untuk Jabodetabek dan 2-5 hari kerja untuk luar pulau Jawa.'],
                                ['title' => 'Bagaimana cara melacak pesanan saya?', 'body' => 'Anda dapat membuka menu "Pesanan Saya" di halaman akun Anda, atau menggunakan nomor resi pengiriman yang tertera pada detail order.'],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Syarat & Ketentuan',
                'title_translations' => ['id' => 'Syarat & Ketentuan', 'en' => 'Terms & Conditions'],
                'slug' => 'terms',
                'template' => 'default',
                'status' => 'published',
                'show_in_nav' => false,
                'show_in_footer' => true,
                'blocks' => [
                    [
                        'type' => 'heading',
                        'content' => ['level' => 1, 'text' => 'Syarat dan Ketentuan Layanan'],
                        'content_translations' => ['en' => ['text' => 'Terms and Conditions of Service']],
                    ],
                    [
                        'type' => 'text',
                        'content' => ['body' => 'Dengan mengakses dan menggunakan platform Radiant Studio, Anda menyetujui untuk terikat oleh syarat dan ketentuan berikut. Harap membaca seluruh ketentuan ini dengan saksama sebelum melakukan transaksi.'],
                    ],
                ],
            ],
            [
                'title' => 'Kebijakan Privasi',
                'title_translations' => ['id' => 'Kebijakan Privasi', 'en' => 'Privacy Policy'],
                'slug' => 'privacy',
                'template' => 'default',
                'status' => 'published',
                'show_in_nav' => false,
                'show_in_footer' => true,
                'blocks' => [
                    [
                        'type' => 'heading',
                        'content' => ['level' => 1, 'text' => 'Kebijakan Privasi Data'],
                        'content_translations' => ['en' => ['text' => 'Data Privacy Policy']],
                    ],
                    [
                        'type' => 'text',
                        'content' => ['body' => 'Radiant Studio sangat menghargai privasi data Anda. Kami tidak akan pernah menjual atau membagikan informasi pribadi Anda kepada pihak ketiga untuk kepentingan komersial tanpa izin tegas dari Anda.'],
                    ],
                ],
            ],
            [
                'title' => 'Informasi Pengiriman',
                'title_translations' => ['id' => 'Informasi Pengiriman', 'en' => 'Shipping Information'],
                'slug' => 'shipping',
                'template' => 'default',
                'status' => 'published',
                'show_in_nav' => false,
                'show_in_footer' => true,
                'blocks' => [
                    [
                        'type' => 'heading',
                        'content' => ['level' => 1, 'text' => 'Pengiriman & Ekspedisi'],
                        'content_translations' => ['en' => ['text' => 'Shipping & Delivery']],
                    ],
                    [
                        'type' => 'text',
                        'content' => ['body' => 'Kami bermitra dengan jasa ekspedisi terpercaya (JNE, SiCepat, J&T, AnterAja) untuk memastikan paket Anda tiba dalam kondisi sempurna dan tepat waktu.'],
                    ],
                ],
            ],
            [
                'title' => 'Kebijakan Pengembalian',
                'title_translations' => ['id' => 'Kebijakan Pengembalian', 'en' => 'Return Policy'],
                'slug' => 'returns',
                'template' => 'default',
                'status' => 'published',
                'show_in_nav' => false,
                'show_in_footer' => true,
                'blocks' => [
                    [
                        'type' => 'heading',
                        'content' => ['level' => 1, 'text' => 'Kebijakan Pengembalian & Garansi'],
                        'content_translations' => ['en' => ['text' => 'Return Policy & Warranty']],
                    ],
                    [
                        'type' => 'text',
                        'content' => ['body' => 'Setiap produk yang rusak atau tidak sesuai dengan pesanan dapat ditukar atau dikembalikan dalam jangka waktu 30 hari sejak barang diterima. Hubungi CS kami dengan menyertakan bukti unboxing.'],
                    ],
                ],
            ],
            [
                'title' => 'Hubungi Kami',
                'title_translations' => ['id' => 'Hubungi Kami', 'en' => 'Contact Us'],
                'slug' => 'contact',
                'template' => 'default',
                'status' => 'published',
                'show_in_nav' => true,
                'show_in_footer' => true,
                'blocks' => [
                    [
                        'type' => 'heading',
                        'content' => ['level' => 1, 'text' => 'Hubungi Tim Radiant Studio'],
                        'content_translations' => ['en' => ['text' => 'Contact the Radiant Studio Team']],
                    ],
                    [
                        'type' => 'text',
                        'content' => ['body' => 'Ada pertanyaan, saran, atau kendala dalam berbelanja? Tim kami siap melayani Anda melalui WhatsApp di +62 878-7844-4402 atau email ke hello@radiantcode.web.id.'],
                    ],
                ],
            ],
        ];

        foreach ($pages as $p) {
            $blocks = $p['blocks'];
            unset($p['blocks']);

            $page = Page::firstOrCreate(
                ['slug' => $p['slug']],
                array_merge($p, ['published_at' => now()])
            );

            // Create blocks only if page is new or has no blocks
            if ($page->blocks()->count() === 0) {
                foreach ($blocks as $idx => $block) {
                    PageBlock::create([
                        'page_id' => $page->id,
                        'type' => $block['type'],
                        'content' => $block['content'],
                        'content_translations' => $block['content_translations'] ?? null,
                        'sort_order' => $idx + 1,
                        'is_visible' => true,
                    ]);
                }
            }
        }

        // 5. Homepage Sections (Hero, Categories, Showcase, Promo Strip, Brand Story, Newsletter)
        $sections = [
            [
                'type' => 'hero',
                'title' => 'Hero Banner',
                'sort_order' => 1,
                'is_active' => true,
                'config' => [
                    'badge' => '✨ New Season Collection 2026',
                    'title' => 'Koleksi Eksklusif, Kualitas Terbaik',
                    'subtitle' => 'Temukan produk premium terkurasi dengan penawaran istimewa dan jaminan keaslian 100%.',
                    'cta_primary_text' => 'Jelajahi Sekarang',
                    'cta_primary_link' => '/products',
                    'cta_secondary_text' => 'Lihat Promo',
                    'cta_secondary_link' => '/products?promo=1',
                    'stats' => [
                        ['value' => '500+', 'label' => 'Produk Terkurasi'],
                        ['value' => '50K+', 'label' => 'Pelanggan Puas'],
                        ['value' => '99.8%', 'label' => 'Rating Positif'],
                    ],
                ],
                'config_translations' => [
                    'en' => [
                        'badge' => '✨ New Season Collection 2026',
                        'title' => 'Exclusive Collection, Superior Quality',
                        'subtitle' => 'Discover curated premium goods with exclusive deals and 100% authentic guarantee.',
                        'cta_primary_text' => 'Explore Now',
                        'cta_secondary_text' => 'View Deals',
                    ],
                ],
            ],
            [
                'type' => 'categories',
                'title' => 'Kategori Pilihan',
                'sort_order' => 2,
                'is_active' => true,
                'config' => [
                    'badge' => 'Koleksi Unggulan',
                    'title' => 'Jelajahi Berdasarkan Kategori',
                    'subtitle' => 'Pilih kategori favorit Anda dan temukan produk terbaik dengan mudah.',
                ],
            ],
            [
                'type' => 'product_showcase',
                'title' => 'Showcase Produk',
                'sort_order' => 3,
                'is_active' => true,
                'config' => [
                    'badge' => 'Trending Sekarang',
                    'title' => 'Produk Pilihan Minggu Ini',
                    'limit' => 8,
                ],
            ],
            [
                'type' => 'promo_strip',
                'title' => 'Banner Promo Beranda',
                'sort_order' => 4,
                'is_active' => true,
                'config' => [
                    'badge' => 'PENAWARAN SPESIAL',
                    'title' => 'Flash Sale & Promo Terbatas',
                    'subtitle' => 'Dapatkan diskon eksklusif untuk berbagai produk pilihan minggu ini.',
                ],
            ],
            [
                'type' => 'brand_story',
                'title' => 'Kisah Brand & Keunggulan',
                'sort_order' => 5,
                'is_active' => true,
                'config' => [
                    'badge' => 'Kenapa Radiant Studio?',
                    'title' => 'Belanja Lebih Nyaman, Aman, dan Menguntungkan',
                    'subtitle' => 'Kami berkomitmen memberikan standar pelayanan bintang lima bagi seluruh pelanggan kami di seluruh Indonesia.',
                    'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=1000&q=80',
                    'points' => [
                        ['title' => '100% Original Terjamin', 'description' => 'Seluruh produk lolos kurasi ketat dan bergaransi resmi distributor.'],
                        ['title' => 'Pengiriman Cepat & Aman', 'description' => 'Didukung ekspedisi nasional terpercaya dengan asuransi gratis.'],
                        ['title' => 'Layanan Pelanggan 24/7', 'description' => 'Tim support kami siap membantu kebutuhan belanja Anda kapan saja.'],
                    ],
                    'cta_text' => 'Tentang Kami',
                    'cta_link' => '/pages/about',
                ],
            ],
            [
                'type' => 'newsletter',
                'title' => 'Langganan Newsletter',
                'sort_order' => 6,
                'is_active' => true,
                'config' => [
                    'title' => 'Dapatkan Diskon 15% untuk Pesanan Pertama Anda',
                    'subtitle' => 'Bergabunglah dengan buletin kami untuk info peluncuran produk baru dan promo eksklusif mingguan.',
                    'button_text' => 'Daftar Sekarang',
                ],
            ],
        ];

        foreach ($sections as $section) {
            $existing = HomepageSection::where('type', $section['type'])->first();
            if (!$existing) {
                HomepageSection::create($section);
            } else {
                // If brand_story exists but has no points array, enrich it safely
                if ($section['type'] === 'brand_story' && empty($existing->config['points'])) {
                    $mergedConfig = array_merge($existing->config ?? [], [
                        'points' => $section['config']['points'],
                        'image' => $existing->config['image'] ?? $section['config']['image'],
                    ]);
                    $existing->update(['config' => $mergedConfig]);
                }
            }
        }

        // 6. Promotional Banners (Hero Slider + Promo Strip)
        // Hero Slider Banner 1: Sony Headphone
        Banner::firstOrCreate(
            ['title' => 'Sony WH-1000XM5 Special Edition'],
            [
                'title_translations' => ['en' => 'Sony WH-1000XM5 Special Edition'],
                'subtitle' => 'Wireless Noise Canceling • Baterai Hingga 30 Jam • Audio Resolusi Tinggi',
                'subtitle_translations' => ['en' => 'Wireless Noise Canceling • Up to 30 Hours Battery • High-Res Audio'],
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=1200&q=80',
                'link' => '/products',
                'placement' => Banner::PLACEMENT_HERO_SLIDER,
                'cta_text' => 'Beli Sekarang',
                'cta_text_translations' => ['en' => 'Shop Now'],
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        // Hero Slider Banner 2: Smartwatch
        Banner::firstOrCreate(
            ['title' => 'Smartwatch Ultra Series'],
            [
                'title_translations' => ['en' => 'Smartwatch Ultra Series'],
                'subtitle' => 'Layar AMOLED Retina • Tahan Air 50M • Sensor Kesehatan Akurat',
                'subtitle_translations' => ['en' => 'Retina AMOLED Display • 50M Water Resistant • Precision Health Sensors'],
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=1200&q=80',
                'link' => '/products',
                'placement' => Banner::PLACEMENT_HERO_SLIDER,
                'cta_text' => 'Lihat Koleksi',
                'cta_text_translations' => ['en' => 'Explore Collection'],
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        // Promo Strip Banner: Mega Mid-Year Sale
        Banner::firstOrCreate(
            ['title' => 'Mega Mid-Year Sale 2026'],
            [
                'title_translations' => ['en' => 'Mega Mid-Year Sale 2026'],
                'subtitle' => 'Diskon hingga 50% untuk kategori pilihan. Terbatas minggu ini!',
                'subtitle_translations' => ['en' => 'Up to 50% off selected categories. Limited time offer!'],
                'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=1600&q=80',
                'link' => '/products',
                'placement' => Banner::PLACEMENT_PROMO_STRIP,
                'cta_text' => 'Beli Sekarang',
                'cta_text_translations' => ['en' => 'Shop Now'],
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        // 7. Popups
        Popup::firstOrCreate(
            ['name' => 'welcome_modal'],
            [
                'title' => 'Selamat Datang di Radiant Studio! 🎉',
                'title_translations' => ['en' => 'Welcome to Radiant Studio! 🎉'],
                'description' => 'Gunakan kode voucher RADIANT10 saat checkout untuk mendapatkan potongan harga Rp 25.000 pada pembelian pertama Anda.',
                'type' => 'welcome',
                'show_on' => 'home',
                'cta_text' => 'Gunakan Voucher',
                'cta_link' => '/products',
                'delay_seconds' => 5,
                'show_once_per_session' => true,
                'is_active' => false, // Default inactive so it can be turned on whenever needed
            ]
        );

        // 8. Email Templates
        EmailTemplate::firstOrCreate(
            ['slug' => 'order_confirmation'],
            [
                'name' => 'Konfirmasi Pesanan',
                'subject' => 'Pesanan #{order_number} Telah Diterima — Radiant Studio',
                'subject_translations' => ['en' => 'Order #{order_number} Received — Radiant Studio'],
                'header_text' => 'Terima kasih atas pesanan Anda!',
                'body_content' => 'Halo {customer_name}, pesanan Anda #{order_number} telah berhasil kami terima dan sedang diproses.',
                'footer_text' => 'Butuh bantuan? Balas email ini atau hubungi WhatsApp CS kami.',
                'is_active' => true,
            ]
        );

        // 9. Invalidate CMS cache
        CmsCache::bump();
    }
}
