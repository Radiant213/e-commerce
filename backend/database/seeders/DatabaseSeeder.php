<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Review;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin & Customer Users
        $admin = User::create([
            'name' => 'Admin Radiant',
            'email' => 'admin@radiantcode.web.id',
            'password' => Hash::make('password123'),
            'phone' => '081234567890',
            'address' => 'Jl. Sudirman No. 45, Jakarta Pusat',
            'role' => 'admin',
            'avatar' => 'https://api.dicebear.com/7.x/adventurer/svg?seed=RadiantAdmin&backgroundColor=b6e3f4',
        ]);

        $customer1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password123'),
            'phone' => '082198765432',
            'address' => 'Jl. Melati Blok C2 No. 12, Bandung',
            'role' => 'customer',
            'avatar' => 'https://api.dicebear.com/7.x/adventurer/svg?seed=BudiSantoso&backgroundColor=ffdfbf',
        ]);

        $customer2 = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@example.com',
            'password' => Hash::make('password123'),
            'phone' => '085712345678',
            'address' => 'Jl. Kebon Jeruk No. 88, Surabaya',
            'role' => 'customer',
            'avatar' => 'https://api.dicebear.com/7.x/lorelei/svg?seed=SitiNurhaliza&backgroundColor=ffd5dc',
        ]);

        $customers = [
            $customer1,
            $customer2,
            User::create([
                'name' => 'Reza Pratama',
                'email' => 'reza@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081399887766',
                'address' => 'Jl. Diponegoro No. 10, Yogyakarta',
                'role' => 'customer',
                'avatar' => 'https://api.dicebear.com/7.x/personas/svg?seed=RezaPratama&backgroundColor=c0aede',
            ]),
            User::create([
                'name' => 'Dewi Lestari',
                'email' => 'dewi@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081233445566',
                'address' => 'Jl. Gajah Mada No. 23, Semarang',
                'role' => 'customer',
                'avatar' => 'https://api.dicebear.com/7.x/adventurer/svg?seed=DewiLestari&backgroundColor=d1d4f9',
            ]),
            User::create([
                'name' => 'Dimas Anggara',
                'email' => 'dimas@example.com',
                'password' => Hash::make('password123'),
                'phone' => '087811223344',
                'address' => 'Jl. Thamrin No. 99, Medan',
                'role' => 'customer',
                'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=DimasAnggara&backgroundColor=b6e3f4',
            ]),
        ];

        // 2. Categories
        $categoriesData = [
            [
                'name' => 'Elektronik & Gadget',
                'slug' => 'elektronik-gadget',
                'description' => 'Smartphone, laptop, audio, dan aksesoris teknologi terkini.',
                'image' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=600&q=80',
                'icon' => 'Smartphone',
                'sort_order' => 1,
            ],
            [
                'name' => 'Fashion Pria',
                'slug' => 'fashion-pria',
                'description' => 'Koleksi pakaian, sepatu, dan aksesoris pria modern.',
                'image' => 'https://images.unsplash.com/photo-1490578474895-699cd4e2cf59?auto=format&fit=crop&w=600&q=80',
                'icon' => 'Shirt',
                'sort_order' => 2,
            ],
            [
                'name' => 'Fashion Wanita',
                'slug' => 'fashion-wanita',
                'description' => 'Tren busana wanita elegan, kasual, hingga formal.',
                'image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=600&q=80',
                'icon' => 'Sparkles',
                'sort_order' => 3,
            ],
            [
                'name' => 'Peralatan Rumah Tangga',
                'slug' => 'peralatan-rumah-tangga',
                'description' => 'Perlengkapan dapur, dekorasi ruangan, dan furnitur minimalis.',
                'image' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=600&q=80',
                'icon' => 'Home',
                'sort_order' => 4,
            ],
            [
                'name' => 'Kesehatan & Kecantikan',
                'slug' => 'kesehatan-kecantikan',
                'description' => 'Skincare, suplemen, kosmetik, dan perawatan tubuh.',
                'image' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80',
                'icon' => 'HeartPulse',
                'sort_order' => 5,
            ],
            [
                'name' => 'Olahraga & Outdoor',
                'slug' => 'olahraga-outdoor',
                'description' => 'Peralatan fitness, running, camping, dan pakaian olahraga.',
                'image' => 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?auto=format&fit=crop&w=600&q=80',
                'icon' => 'Trophy',
                'sort_order' => 6,
            ],
            [
                'name' => 'Makanan & Minuman',
                'slug' => 'makanan-minuman',
                'description' => 'Kopi premium, camilan nusantara, dan makanan organik.',
                'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=600&q=80',
                'icon' => 'Coffee',
                'sort_order' => 7,
            ],
            [
                'name' => 'Buku & Alat Tulis',
                'slug' => 'buku-alat-tulis',
                'description' => 'Buku pengembangan diri, novel best-seller, dan stationary kantor.',
                'image' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=600&q=80',
                'icon' => 'BookOpen',
                'sort_order' => 8,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $catData) {
            $categories[$catData['slug']] = Category::create($catData);
        }

        // 3. Products
        $productsData = [
            // ELEKTRONIK (10 items)
            [
                'category' => 'elektronik-gadget',
                'name' => 'Sony WH-1000XM5 Wireless Noise Cancelling Headphones',
                'price' => 5499000,
                'sale_price' => 4899000,
                'stock' => 25,
                'weight' => 250,
                'is_featured' => true,
                'short' => 'Headphone premium dengan noise cancelling terdepan di industri.',
                'desc' => 'Ditenagai oleh prosesor V1 dan QN1 yang terintegrasi, Sony WH-1000XM5 menghadirkan peredam kebisingan kelas dunia dan kualitas suara resolusi tinggi nirkabel dengan daya tahan baterai hingga 30 jam.',
                'images' => [
                    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1484704849700-f032a568e944?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category' => 'elektronik-gadget',
                'name' => 'Keychron K2 Pro QMK/VIA Wireless Custom Mechanical Keyboard',
                'price' => 1899000,
                'sale_price' => 1649000,
                'stock' => 40,
                'weight' => 800,
                'is_featured' => true,
                'short' => 'Keyboard mekanikal nirkabel 75% dengan hot-swappable switch.',
                'desc' => 'Keyboard mekanik kompak dengan konektivitas Bluetooth 5.1 & Type-C, kompatibel penuh dengan Mac dan Windows. Dilengkapi keycaps PBT double-shot dan switch Gateron Pro.',
                'images' => [
                    'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category' => 'elektronik-gadget',
                'name' => 'Logitech MX Master 3S Wireless Performance Mouse',
                'price' => 1799000,
                'sale_price' => null,
                'stock' => 50,
                'weight' => 141,
                'is_featured' => true,
                'short' => 'Mouse produktivitas ergonomis dengan scroll wheel elektromagnetik MagSpeed.',
                'desc' => 'Sensor optik 8K DPI yang dapat melacak pada permukaan apa pun bahkan kaca. Dilengkapi klik hening (Quiet Clicks) dan koneksi multi-perangkat Flow.',
                'images' => [
                    'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category' => 'elektronik-gadget',
                'name' => 'Anker 737 Power Bank (PowerCore 24K) 140W',
                'price' => 1999000,
                'sale_price' => 1750000,
                'stock' => 30,
                'weight' => 630,
                'is_featured' => false,
                'short' => 'Power bank ultra-cepat 140W dengan layar digital pintar.',
                'desc' => 'Kapasitas 24.000mAh dengan teknologi Power Delivery 3.1 dua arah hingga 140W, cukup untuk mengisi daya laptop MacBook Pro 16" hingga 50% hanya dalam 40 menit.',
                'images' => [
                    'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category' => 'elektronik-gadget',
                'name' => 'Apple Watch Series 9 GPS 45mm Midnight',
                'price' => 7499000,
                'sale_price' => 6999000,
                'stock' => 15,
                'weight' => 390,
                'is_featured' => true,
                'short' => 'Smartwatch paling canggih dengan gestur Double Tap dan chip S9 SiP.',
                'desc' => 'Layar Always-On Retina hingga 2000 nits, pemantauan kesehatan komprehensif (EKG, Oksigen Darah, Siklus Suhu), tahan air hingga 50 meter.',
                'images' => [
                    'https://images.unsplash.com/photo-1546868871-7041f2a55e12?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category' => 'elektronik-gadget',
                'name' => 'Sony Alpha 7 IV Full-frame Mirrorless Camera',
                'price' => 34999000,
                'sale_price' => 32499000,
                'stock' => 8,
                'weight' => 658,
                'is_featured' => false,
                'short' => 'Kamera hybrid profesional dengan sensor Exmor R 33MP dan video 4K 60p.',
                'desc' => 'Autofokus Real-time Eye AF canggih untuk manusia, hewan, dan burung, dilengkapi sistem stabilisasi gambar 5-axis in-body image stabilization (IBIS).',
                'images' => [
                    'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=800&q=80',
                ],
            ],

            // FASHION PRIA (6 items)
            [
                'category' => 'fashion-pria',
                'name' => 'Jaket Bomber Harrington Casual Navy',
                'price' => 450000,
                'sale_price' => 389000,
                'stock' => 60,
                'weight' => 500,
                'is_featured' => true,
                'short' => 'Jaket pria semi-formal bahan katun twill premium bernapas.',
                'desc' => 'Desain klasik timeless dengan lapisan dalam motif tartan (plaid), resleting YKK anti macet, dan saku fungsional.',
                'images' => [
                    'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category' => 'fashion-pria',
                'name' => 'Kemeja Linen Lengan Panjang Putih Slim Fit',
                'price' => 349000,
                'sale_price' => 299000,
                'stock' => 85,
                'weight' => 300,
                'is_featured' => true,
                'short' => 'Kemeja pria 100% serat linen murni bertekstur adem dan elegan.',
                'desc' => 'Sangat cocok untuk cuaca tropis, memberikan tampilan santai namun tetap rapi untuk acara casual maupun smart-casual.',
                'images' => [
                    'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category' => 'fashion-pria',
                'name' => 'Sepatu Sneakers Minimalist Leather All White',
                'price' => 799000,
                'sale_price' => 699000,
                'stock' => 35,
                'weight' => 900,
                'is_featured' => true,
                'short' => 'Sneakers kulit asli sapi premium dengan sol karet vulkanisir anti slip.',
                'desc' => 'Didesain untuk kenyamanan harian dengan insole busa memori yang empuk dan siluet bersih yang cocok dipadukan dengan celana apapun.',
                'images' => [
                    'https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category' => 'fashion-pria',
                'name' => 'Dompet Kulit Bifold Slim Horween Brown',
                'price' => 320000,
                'sale_price' => null,
                'stock' => 45,
                'weight' => 120,
                'is_featured' => false,
                'short' => 'Dompet kulit nabati asli dengan 8 slot kartu dan proteksi RFID.',
                'desc' => 'Jahitan tangan kuat dengan benang lilin, akan mengalami proses patina alami seiring bertambahnya usia pemakaian.',
                'images' => [
                    'https://images.unsplash.com/photo-1627123424574-724758594e93?auto=format&fit=crop&w=800&q=80',
                ],
            ],

            // FASHION WANITA (6 items)
            [
                'category' => 'fashion-wanita',
                'name' => 'Tas Kulit Selempang Minimalist Tote Bag Caramel',
                'price' => 680000,
                'sale_price' => 599000,
                'stock' => 30,
                'weight' => 700,
                'is_featured' => true,
                'short' => 'Tas bahu wanita kulit vegan berkualitas dengan kompartemen luas.',
                'desc' => 'Muat untuk laptop hingga 13 inci, dompet, makeup, dan tumbler. Tali panjang bisa diatur atau dilepas sesuai kebutuhan gaya.',
                'images' => [
                    'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category' => 'fashion-wanita',
                'name' => 'Floral Midi Dress Vintage French Style',
                'price' => 425000,
                'sale_price' => 359000,
                'stock' => 50,
                'weight' => 350,
                'is_featured' => true,
                'short' => 'Gaun midi wanita motif bunga anggun dengan potongan pinggang elastis.',
                'desc' => 'Bahan sifon premium berfuring katun lembut yang tidak menerawang. Memberikan siluet ramping dan flowy yang mempesona.',
                'images' => [
                    'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category' => 'fashion-wanita',
                'name' => 'Kacamata Hitam Cat-Eye Acetate Tortoise',
                'price' => 280000,
                'sale_price' => null,
                'stock' => 70,
                'weight' => 90,
                'is_featured' => false,
                'short' => 'Kacamata gaya wanita dengan lensa UV400 Polarized pelindung mata.',
                'desc' => 'Frame asetat kokoh motif tempurung kura-kura klasik yang tahan banting dan cocok untuk berbagai bentuk wajah.',
                'images' => [
                    'https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=800&q=80',
                ],
            ],

            // PERALATAN RUMAH TANGGA (6 items)
            [
                'category' => 'peralatan-rumah-tangga',
                'name' => 'Fellow Stagg EKG Electric Pour-Over Kettle Matte Black',
                'price' => 2850000,
                'sale_price' => 2599000,
                'stock' => 20,
                'weight' => 1200,
                'is_featured' => true,
                'short' => 'Ketel listrik leher angsa dengan pengatur suhu presisi hingga 1 derajat.',
                'desc' => 'Favorit para barista dunia dengan corong presisi untuk tuangan konsisten, layar LCD tersembunyi, dan mode tahan suhu 60 menit.',
                'images' => [
                    'https://images.unsplash.com/photo-1517668808822-9ebb02f2a0e6?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category' => 'peralatan-rumah-tangga',
                'name' => 'Lampu Meja Nordik Minimalis Dimmable Touch Wood',
                'price' => 389000,
                'sale_price' => 319000,
                'stock' => 40,
                'weight' => 850,
                'is_featured' => false,
                'short' => 'Lampu tidur meja dengan alas kayu solid dan kap kain linen hangat.',
                'desc' => 'Fitur sensor sentuh 3 tingkat kecerahan, sudah termasuk lampu LED warm white 3000K yang hemat energi dan ramah mata.',
                'images' => [
                    'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category' => 'peralatan-rumah-tangga',
                'name' => 'Ceramic Aroma Diffuser Ultrasonic 300ml',
                'price' => 450000,
                'sale_price' => 379000,
                'stock' => 35,
                'weight' => 600,
                'is_featured' => true,
                'short' => 'Diffuser aromaterapi bodi keramik handmade dengan lampu ambient lembut.',
                'desc' => 'Menghasilkan kabut halus tanpa panas berkat getaran ultrasonik 2.4MHz, otomatis mati ketika air habis.',
                'images' => [
                    'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?auto=format&fit=crop&w=800&q=80',
                ],
            ],

            // KESEHATAN & KECANTIKAN (6 items)
            [
                'category' => 'kesehatan-kecantikan',
                'name' => 'Hydrating Hyaluronic Acid Serum 50ml',
                'price' => 199000,
                'sale_price' => 159000,
                'stock' => 120,
                'weight' => 150,
                'is_featured' => true,
                'short' => 'Serum pelembap intensif dengan 5 jenis molekul Hyaluronic Acid.',
                'desc' => 'Menghidrasi kulit hingga lapisan terdalam, memperbaiki skin barrier, tanpa rasa lengket dan bebas pewangi buatan.',
                'images' => [
                    'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category' => 'kesehatan-kecantikan',
                'name' => 'Natural Scented Soy Wax Candle - Lavender & Bergamot',
                'price' => 165000,
                'sale_price' => 135000,
                'stock' => 80,
                'weight' => 300,
                'is_featured' => false,
                'short' => 'Lilin aromaterapi dari 100% lilin kedelai alami dengan sumbu kayu.',
                'desc' => 'Membakar bersih tanpa jelaga hitam hingga 45 jam, memberikan aroma menenangkan untuk relaksasi dan tidur nyenyak.',
                'images' => [
                    'https://images.unsplash.com/photo-1603006905003-be475563bc59?auto=format&fit=crop&w=800&q=80',
                ],
            ],

            // OLAHRAGA (5 items)
            [
                'category' => 'olahraga-outdoor',
                'name' => 'Matras Yoga Anti-Slip Eco TPE 6mm',
                'price' => 275000,
                'sale_price' => 220000,
                'stock' => 60,
                'weight' => 950,
                'is_featured' => true,
                'short' => 'Matras olahraga ramah lingkungan dengan garis alignment pemandu pose.',
                'desc' => 'Daya cengkeram optimal ganda di kedua sisi, ketebalan 6mm melindungi sendi lutut dan siku saat latihan yoga maupun pilates.',
                'images' => [
                    'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category' => 'olahraga-outdoor',
                'name' => 'Botol Minum Stainless Steel Vacuum Insulated 750ml',
                'price' => 219000,
                'sale_price' => 179000,
                'stock' => 90,
                'weight' => 450,
                'is_featured' => true,
                'short' => 'Tumbler termos tahan dingin 24 jam dan tahan panas 12 jam.',
                'desc' => 'Bahan stainless steel 304 food-grade bebas BPA, anti bocor dengan tutup botol berbantalan silikon rapat.',
                'images' => [
                    'https://images.unsplash.com/photo-1602143407151-7111542de6e8?auto=format&fit=crop&w=800&q=80',
                ],
            ],

            // MAKANAN & MINUMAN (5 items)
            [
                'category' => 'makanan-minuman',
                'name' => 'Specialty Coffee Beans Arabica Gayo Single Origin 250g',
                'price' => 110000,
                'sale_price' => 95000,
                'stock' => 100,
                'weight' => 260,
                'is_featured' => true,
                'short' => 'Biji kopi sangrai fresh medium roast dengan notes cokelat dan rempah.',
                'desc' => 'Dipetik dari ketinggian 1.500 mdpl di dataran tinggi Aceh Gayo, diproses secara semi-washed untuk body yang tebal dan keasaman seimbang.',
                'images' => [
                    'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category' => 'makanan-minuman',
                'name' => 'Artisan Dark Chocolate 70% Bali Single Origin 80g',
                'price' => 65000,
                'sale_price' => null,
                'stock' => 150,
                'weight' => 90,
                'is_featured' => false,
                'short' => 'Cokelat batang murni tanpa bahan pengawet dengan kakao organik Tabanan.',
                'desc' => 'Kombinasi rasa buah tropis alami kakao dengan tekstur meleleh lembut di lidah.',
                'images' => [
                    'https://images.unsplash.com/photo-1548907040-4baa42d10919?auto=format&fit=crop&w=800&q=80',
                ],
            ],

            // BUKU (4 items)
            [
                'category' => 'buku-alat-tulis',
                'name' => 'Atomic Habits - James Clear (Edisi Bahasa Indonesia)',
                'price' => 108000,
                'sale_price' => 92000,
                'stock' => 75,
                'weight' => 400,
                'is_featured' => true,
                'short' => 'Buku terlaris tentang cara mudah & terbukti membentuk kebiasaan baik.',
                'desc' => 'Panduan praktis langkah demi langkah mengubah rutinitas kecil setiap hari menjadi hasil transformatif luar biasa.',
                'images' => [
                    'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'category' => 'buku-alat-tulis',
                'name' => 'Leather Bound Dotted Journal A5 160 GSM',
                'price' => 175000,
                'sale_price' => 145000,
                'stock' => 85,
                'weight' => 450,
                'is_featured' => false,
                'short' => 'Buku catatan bersampul kulit dengan kertas tebal anti tembus tinta fountain pen.',
                'desc' => 'Dilengkapi pita pembatas buku ganda, kantong belakang dokumen, dan karet penutup elastis.',
                'images' => [
                    'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&w=800&q=80',
                ],
            ],
        ];

        $createdProducts = [];
        foreach ($productsData as $prod) {
            $category = $categories[$prod['category']];
            $product = Product::create([
                'category_id' => $category->id,
                'name' => $prod['name'],
                'slug' => Str::slug($prod['name']),
                'short_description' => $prod['short'],
                'description' => $prod['desc'],
                'price' => $prod['price'],
                'sale_price' => $prod['sale_price'],
                'stock' => $prod['stock'],
                'sku' => 'SKU-' . strtoupper(Str::random(6)),
                'weight' => $prod['weight'],
                'is_active' => true,
                'is_featured' => $prod['is_featured'],
                'total_sold' => rand(15, 120),
            ]);

            // Add images
            foreach ($prod['images'] as $idx => $imgUrl) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imgUrl,
                    'is_primary' => $idx === 0,
                    'sort_order' => $idx + 1,
                ]);
            }

            $createdProducts[] = $product;
        }

        // 4. Seed Reviews for products
        $reviewComments = [
            5 => ['Barang sangat bagus, original dan pengiriman super cepat! Mantap pokoknya.', 'Kualitas juara! Melebihi ekspektasi, packing juga aman banget pakai bubble wrap tebal.', 'Sangat memuaskan! Produk persis seperti di foto. Bakal langganan di sini.'],
            4 => ['Produk bagus dan berfungsi normal. Cuma pengiriman kurir agak lama sedikit, tapi over all good.', 'Kualitas bahan bagus, harga ramah di kantong. Recommended seller!'],
            3 => ['Cukup bagus, sesuai harga. Semoga awet ya.'],
        ];

        foreach ($createdProducts as $product) {
            // Add 2-4 reviews per product from distinct customers
            $numReviews = rand(2, 4);

            for ($i = 0; $i < $numReviews; $i++) {
                $rating = rand(4, 5);
                $comments = $reviewComments[$rating];
                $comment = $comments[array_rand($comments)];
                $reviewer = $customers[$i];

                Review::create([
                    'user_id' => $reviewer->id,
                    'product_id' => $product->id,
                    'rating' => $rating,
                    'comment' => $comment,
                ]);
            }
            $product->updateRatingStats();
        }

        // 5. Wishlists for customer 1
        for ($i = 0; $i < 3; $i++) {
            Wishlist::firstOrCreate([
                'user_id' => $customer1->id,
                'product_id' => $createdProducts[$i]->id,
            ]);
        }

        // 6. Dummy Order for testing
        $sampleOrder = Order::create([
            'user_id' => $customer1->id,
            'order_number' => 'ORD-DEMO-001',
            'subtotal' => $createdProducts[0]->effective_price,
            'shipping_cost' => 20000,
            'total' => $createdProducts[0]->effective_price + 20000,
            'status' => 'paid',
            'shipping_name' => 'Budi Santoso',
            'shipping_phone' => '082198765432',
            'shipping_address' => 'Jl. Melati Blok C2 No. 12',
            'shipping_city' => 'Bandung',
            'shipping_postal_code' => '40115',
            'notes' => 'Tolong packing kayu jika memungkinkan.',
        ]);

        OrderItem::create([
            'order_id' => $sampleOrder->id,
            'product_id' => $createdProducts[0]->id,
            'product_name' => $createdProducts[0]->name,
            'product_price' => $createdProducts[0]->effective_price,
            'quantity' => 1,
            'subtotal' => $createdProducts[0]->effective_price,
        ]);

        Payment::create([
            'order_id' => $sampleOrder->id,
            'midtrans_transaction_id' => 'TRX-' . Str::random(10),
            'snap_token' => 'dummy-snap-token-demo',
            'payment_type' => 'qris',
            'status' => 'settlement',
            'gross_amount' => $sampleOrder->total,
        ]);
    }
}
