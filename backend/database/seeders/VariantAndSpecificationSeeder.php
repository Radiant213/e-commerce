<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class VariantAndSpecificationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Sony Headphones
        $sony = Product::where('name', 'like', '%Sony WH-1000XM5%')->first();
        if ($sony) {
            $sony->update([
                'specifications' => [
                    'Merek' => 'Sony Official Store',
                    'Masa Garansi' => '12 Bulan Resmi PT Sony Indonesia',
                    'Konektivitas' => 'Bluetooth 5.2 / Kabel Jack 3.5mm',
                    'Daya Tahan Baterai' => '30 Jam (ANC On) / 40 Jam (ANC Off)',
                    'Fitur Khusus' => 'Active Noise Cancelling (ANC), LDAC, Hi-Res Audio, Speak-to-Chat',
                    'Port Pengisian' => 'USB Type-C (Fast Charging)',
                    'Bobot Produk' => '250 gram',
                ],
                'description' => '<p><strong>Sony WH-1000XM5</strong> mendefinisikan ulang standar peredam bising nirkabel dengan prosesor terintegrasi V1 dan prosesor noise cancelling HD QN1 yang dirancang khusus.</p>' .
                    '<p><img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=1200&q=80" alt="Sony WH-1000XM5 Visual" /></p>' .
                    '<h3>Keunggulan Utama</h3>' .
                    '<ul>' .
                    '<li><strong>Noise Cancelling Unggulan Industri:</strong> Dilengkapi 8 mikrofon dan Auto NC Optimizer untuk otomatis menyesuaikan dengan lingkungan sekitar Anda.</li>' .
                    '<li><strong>Kualitas Suara Kelas Atas:</strong> Driver 30mm presisi tinggi dengan diafragma serat karbon ringan memberikan audio jernih dan bass mendalam.</li>' .
                    '<li><strong>Panggilan Super Jernih:</strong> Teknologi Voice Pickup yang menggunakan 4 mikrofon beamforming dan AI pengurangan bising angin.</li>' .
                    '</ul>' .
                    '<p><img src="https://images.unsplash.com/photo-1484704849700-f032a568e944?auto=format&fit=crop&w=1200&q=80" alt="Ergonomic Comfort" /></p>' .
                    '<h3>Kenyamanan Sepanjang Hari</h3>' .
                    '<p>Desain baru yang ramping dengan kulit berbusa lembut memberikan tekanan minimal pada kepala, memungkinkan penggunaan berjam-jam tanpa rasa pegal.</p>',
            ]);

            // Clear old variants
            $sony->variants()->delete();

            ProductVariant::create([
                'product_id' => $sony->id,
                'name' => 'Silver Platinum',
                'sku' => 'SNY-XM5-SLV',
                'price' => 5499000,
                'sale_price' => 4899000,
                'stock' => 15,
                'image_path' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=400&q=80',
                'is_active' => true,
                'sort_order' => 1,
            ]);

            ProductVariant::create([
                'product_id' => $sony->id,
                'name' => 'Midnight Black',
                'sku' => 'SNY-XM5-BLK',
                'price' => 5499000,
                'sale_price' => 4899000,
                'stock' => 20,
                'image_path' => 'https://images.unsplash.com/photo-1484704849700-f032a568e944?auto=format&fit=crop&w=400&q=80',
                'is_active' => true,
                'sort_order' => 2,
            ]);

            ProductVariant::create([
                'product_id' => $sony->id,
                'name' => 'Smoky Pink (Special Edition)',
                'sku' => 'SNY-XM5-PNK',
                'price' => 5799000,
                'sale_price' => 5199000,
                'stock' => 8,
                'image_path' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&w=400&q=80',
                'is_active' => true,
                'sort_order' => 3,
            ]);
        }

        // 2. Keychron Keyboard
        $keychron = Product::where('name', 'like', '%Keychron K2%')->first();
        if ($keychron) {
            $keychron->update([
                'specifications' => [
                    'Merek' => 'Keychron',
                    'Layout' => '75% (84 Keys)',
                    'Tipe Switch' => 'Hot-swappable Gateron G Pro Mechanical',
                    'Konektivitas' => 'Bluetooth 5.1 & Kabel USB Type-C',
                    'Kompatibilitas' => 'macOS, Windows, iOS, Android',
                    'Kapasitas Baterai' => '4000 mAh (Hingga 240 jam)',
                    'Material' => 'Aluminium Frame & PBT Double-shot Keycaps',
                ],
            ]);

            $keychron->variants()->delete();

            ProductVariant::create([
                'product_id' => $keychron->id,
                'name' => 'Red Switch (Linear)',
                'sku' => 'KCH-K2P-RED',
                'price' => 1899000,
                'sale_price' => 1649000,
                'stock' => 20,
                'image_path' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=400&q=80',
                'is_active' => true,
                'sort_order' => 1,
            ]);

            ProductVariant::create([
                'product_id' => $keychron->id,
                'name' => 'Brown Switch (Tactile)',
                'sku' => 'KCH-K2P-BRN',
                'price' => 1899000,
                'sale_price' => 1649000,
                'stock' => 15,
                'image_path' => 'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?auto=format&fit=crop&w=400&q=80',
                'is_active' => true,
                'sort_order' => 2,
            ]);

            ProductVariant::create([
                'product_id' => $keychron->id,
                'name' => 'Blue Switch (Clicky)',
                'sku' => 'KCH-K2P-BLU',
                'price' => 1899000,
                'sale_price' => 1649000,
                'stock' => 10,
                'image_path' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=400&q=80',
                'is_active' => true,
                'sort_order' => 3,
            ]);
        }
    }
}

