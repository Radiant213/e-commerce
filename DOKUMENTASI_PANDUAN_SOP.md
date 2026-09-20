# 📘 BUKU PANDUAN OPERASIONAL & SOP SISTEM E-COMMERCE
**Platform E-Commerce Modern Full-Stack (Laravel 11 + Filament CMS + React Vite + Midtrans Gateway)**

---

## 📑 DAFTAR ISI
1. [Ringkasan Arsitektur & Peran Pengguna](#1-ringkasan-arsitektur--peran-pengguna)
2. [Panduan Rekaman Video Tutorial (Skrip & Alur)](#2-panduan-rekaman-video-tutorial-skrip--alur)
   - [Video 1: Pengalaman Berbelanja Pembeli (Frontend)](#video-1-pengalaman-berbelanja-pembeli-frontend-durasi-3-5-menit)
   - [Video 2: CMS Admin Part 1 - Katalog & Master Data](#video-2-cms-admin-part-1---manajemen-katalog--produk-durasi-5-7-menit)
   - [Video 3: CMS Admin Part 2 - Pesanan, Resi, & Laporan](#video-3-cms-admin-part-2---operasional-transaksi-resi--laporan-durasi-5-7-menit)
3. [SOP Standar Operasional Prosedur Admin](#3-sop-standar-operasional-prosedur-admin)
   - [SOP-01: Penambahan & Pembaruan Produk Baru](#sop-01-penambahan--pembaruan-produk-baru)
   - [SOP-02: Import Massal Produk via Excel / CSV](#sop-02-import-massal-produk-via-excel--csv)
   - [SOP-03: Pemrosesan Pesanan Baru & Verifikasi Midtrans](#sop-03-pemrosesan-pesanan-baru--verifikasi-midtrans)
   - [SOP-04: Pengiriman Barang & Upload Bukti Foto Resi Fisik](#sop-04-pengiriman-barang--upload-bukti-foto-resi-fisik)
   - [SOP-05: Penutupan Pembukuan & Cetak Laporan Keuangan](#sop-05-penutupan-pembukuan--cetak-laporan-keuangan)
4. [FAQ & Troubleshooting Kendala Umum](#4-faq--troubleshooting-kendala-umum)

---

## 1. Ringkasan Arsitektur & Peran Pengguna

| Komponen | Teknologi | Akses URL | Kredensial Demo |
| :--- | :--- | :--- | :--- |
| **Frontend Toko** | React 19 + Tailwind CSS + Lucide | `https://demo1-ecommerce.radiantcode.web.id` | Pembeli Umum / Akun Customer |
| **Admin Panel (CMS)** | Laravel 11 + Filament v4 | `https://api-ecommerce.radiantcode.web.id/admin` | `admin@radiantcode.web.id` / `password123` |
| **Payment Gateway** | Midtrans Snap (Sandbox & Production) | Otomatis terintegrasi via Webhook | QRIS, BCA/BRI/Mandiri VA, GoPay |

---

## 2. Panduan Rekaman Video Tutorial (Skrip & Alur)

### Video 1: Pengalaman Berbelanja Pembeli (Frontend)
> **Target Durasi:** 3 – 5 Menit  
> **Tujuan:** Menunjukkan kemudahan dan kenyamanan pelanggan dalam mencari barang, memilih varian, membayar via Midtrans, hingga menyelesaikan pesanan.

#### 🎬 Urutan Adegan (Scene by Scene):
1. **Scene 1: Registrasi & Login Cepat (00:00 - 00:45)**
   - Buka halaman utama website.
   - Tunjukkan tombol **Login** di pojok kanan atas.
   - Sorot opsi login fleksibel:
     - Login biasa (Email & Kata Sandi).
     - **Login 1-Klik Akun Google**.
     - **Fitur Fast Demo Button** (klik tombol *Customer* untuk login instan tanpa ketik).
2. **Scene 2: Eksplorasi Etalase & Smart Search (00:45 - 01:30)**
   - Coba fitur **Smart Search** di kolom pencarian:
     - Ketik kata kunci bebas (misal: `"Sony"`, `"Headphone Hitam"`, atau kode SKU `"PRD-"`). Tunjukkan bagaimana sistem mencari kecocokan multi-kata secara cerdas.
   - Filter berdasarkan kategori (Elektronik, Fashion, dsb.) dan urutkan harga/terlaris.
3. **Scene 3: Halaman Detail Produk & Varian (01:30 - 02:30)**
   - Buka produk unggulan (misal: *Sony WH-1000XM5*).
   - Tunjukkan:
     - **Video Showcase Produk** (bisa diputar langsung).
     - **Galeri Foto Interaktif**.
     - **Pilihan Varian** (pilih warna/tipe, perhatikan harga dan stok menyesuaikan).
     - **Tabel Spesifikasi Teknis**.
     - **Ulasan Pembeli** dengan badge *Verified Purchase*.
   - Klik tombol **+ Keranjang** atau **Beli Sekarang**.
4. **Scene 4: Checkout & Pembayaran Midtrans (02:30 - 03:45)**
   - Masuk ke halaman Checkout.
   - Pilih alamat pengiriman dari **Buku Alamat Tersimpan** (atau input alamat baru).
   - Klik tombol **Bayar Sekarang**.
   - Pop-up **Midtrans Snap** muncul di layar:
     - Pilih metode bayar (misal QRIS Simulator atau Virtual Account).
     - Lakukan pembayaran sampai status berhasil (*Payment Success*).
5. **Scene 5: Dashboard Pelanggan & Konfirmasi Pesanan Selesai (03:45 - 05:00)**
   - Masuk ke menu **Dashboard Akun ➔ Riwayat Pesanan**.
   - Tunjukkan:
     - Status berubah menjadi **Pembayaran Berhasil / Diproses**.
     - Stepper 4 tahap visual (Dipesan ➔ Dibayar ➔ Dikirim ➔ Diterima).
     - Ketika pesanan sudah dikirim oleh penjual: nomor resi muncul dan klik tombol **"Lihat Foto Resi"** untuk melihat bukti fisik resi paket.
     - Klik tombol hijau **"Pesanan Sudah Sampai / Selesai"**.
     - Tunjukkan **Pop-up Modal Estetik Baru** yang muncul untuk konfirmasi. Klik *"Ya, Sudah Diterima"*, dan tunjukkan status pesanan berubah hijau penuh menjadi *Selesai*.

---

### Video 2: CMS Admin Part 1 - Manajemen Katalog & Produk
> **Target Durasi:** 5 – 7 Menit  
> **Tujuan:** Menjelaskan cara admin mengatur kategori, memasukkan produk baru lengkap dengan varian dan media showcase, serta fitur import massal Excel.

#### 🎬 Urutan Adegan (Scene by Scene):
1. **Scene 1: Pengenalan Filament Admin Panel (00:00 - 01:00)**
   - Login ke `/admin` menggunakan akun Administrator.
   - Jelaskan halaman Dashboard:
     - Widget ringkasan total omset, jumlah pesanan, total produk, dan customer.
     - Grafik penjualan mingguan dan tabel pesanan terkini.
2. **Scene 2: Mengelola Kategori Produk (01:00 - 02:00)**
   - Buka menu **Katalog Produk ➔ Kategori Produk**.
   - Klik **Tambah Kategori**:
     - Masukkan nama kategori (misal: *Gadget & Aksesoris*).
     - Pilih icon Lucide (misal: `Smartphone` atau `Headphones`).
     - Tunjukkan fleksibilitas banner: bisa upload file gambar atau masukkan URL gambar langsung (Unsplash/CDN).
3. **Scene 3: Input Produk Baru Lengkap (02:00 - 04:30)**
   - Buka menu **Katalog Produk ➔ Produk ➔ Buat Produk**.
   - **Bagian 1: Info Utama & Harga:**
     - Nama produk, kategori, harga normal, harga promo coret, dan stok dasar.
   - **Bagian 2: Deskripsi & Spesifikasi:**
     - Tulis deskripsi dengan Rich Editor (bisa sisip foto).
     - Tambahkan atribut di **Tabel Spesifikasi Teknis** (misal: *Bahan: Aluminium*, *Koneksi: Bluetooth 5.2*, *Garansi: 1 Tahun*).
   - **Bagian 3: Video Showcase Utama:**
     - Tunjukkan opsi upload video langsung (file MP4 s/d 100MB) atau tempelkan link YouTube.
   - **Bagian 4: Galeri Media Tambahan:**
     - Tambahkan beberapa foto produk tambahan.
   - **Bagian 5: Variasi Produk (Warna / Ukuran):**
     - Tambahkan varian (misal: *Hitam*, *Putih*, *Midnight Blue*).
     - Tentukan stok per variasi dan upload foto thumbnail mini varian.
   - Klik **Simpan** dan buka halaman web frontend untuk melihat produk langsung tayang rapi.
4. **Scene 4: Fitur Import & Export Excel / CSV (04:30 - 06:30)**
   - Di daftar tabel produk, klik tombol **"Format Excel (.xlsx)"** untuk mengunduh template resmi.
   - Buka file Excel, tunjukkan format kolom yang disediakan.
   - Klik tombol **"Import Excel / CSV"**, pilih file, dan jalankan proses import.
   - Tunjukkan notifikasi sukses dan produk bertambah/ter-update otomatis (Upsert).

---

### Video 3: CMS Admin Part 2 - Operasional Transaksi, Resi, & Laporan
> **Target Durasi:** 5 – 7 Menit  
> **Tujuan:** Panduan harian admin operasional untuk memproses pesanan masuk, menginput resi kurir beserta foto bukti resi, mencetak invoice/surat jalan, dan menutup laporan pembukuan.

#### 🎬 Urutan Adegan (Scene by Scene):
1. **Scene 1: Memantau Pesanan Masuk (00:00 - 01:30)**
   - Buka menu **Penjualan & Transaksi ➔ Pesanan Masuk**.
   - Jelaskan badge counter notifikasi pending di sidebar.
   - Buka detail salah satu pesanan:
     - Tunjukkan tabel produk belanjaan pelanggan.
     - Tunjukkan **Info Gateway Midtrans** (ID Transaksi Midtrans, tipe pembayaran QRIS/VA, status settlement lunas).
2. **Scene 2: Pengiriman Pesanan & Upload Foto Resi (01:30 - 03:30)**
   - Di tabel pesanan (atau halaman detail), klik aksi **"Kirim Pesanan"**:
     - Masukkan nama kurir (misal: *JNE Reguler*).
     - Masukkan nomor resi (misal: *JNE12873812*).
     - **Upload Foto Bukti Resi Fisik Pengiriman** (foto struk/label paket dari kurir, maks 10 MB).
   - Klik **Kirim Pesanan**.
   - Jelaskan bahwa:
     - Sistem otomatis mengubah status menjadi **Shipped**.
     - **Email notifikasi pengiriman otomatis terkirim** ke inbox email customer, lengkap dengan nomor resi dan link foto resi.
3. **Scene 3: Cetak Invoice & Label Pengiriman (03:30 - 04:30)**
   - Klik menu aksi titik tiga pada pesanan ➔ **Cetak Invoice**.
   - Tunjukkan halaman invoice cetak resmi yang siap di-print atau diunduh sebagai PDF, lengkap dengan rincian barang dan alamat pembeli untuk ditempel di paket.
4. **Scene 4: Pusat Laporan & Pembukuan Keuangan (04:30 - 06:30)**
   - Buka menu **Laporan & Analisis ➔ Pusat Laporan**.
   - Tunjukkan 4 tab laporan:
     - **Laporan Penjualan:** filter rentang tanggal, lihat total omset, jumlah barang terjual, dan klik **"Cetak Laporan Penjualan"** / **"Ekspor Excel"**.
     - **Laporan Stok & Inventaris:** pantau produk habis (*out of stock*) atau stok menipis (*low stock*), dan klik **"Cetak Laporan Stok"**.
     - **Produk Terlaris:** ranking 10 produk paling laku 30 hari terakhir.
     - **Top Pelanggan:** pelanggan dengan total transaksi terbanyak.

---

## 3. SOP Standar Operasional Prosedur Admin

### SOP-01: Penambahan & Pembaruan Produk Baru
1. **Tujuan:** Memastikan setiap produk yang dipajang memiliki data akurat, gambar jernih, dan informasi varian yang jelas bagi calon pembeli.
2. **Langkah Kerja:**
   - Masuk ke Admin Panel ➔ **Produk** ➔ **Buat Produk**.
   - Input **Nama Produk** secara deskriptif (misal: `Brand - Model - Seri`).
   - Tentukan **Harga Normal** dan opsional **Harga Coret / Promo**.
   - Isi **Stok** dan **Berat Produk** (dalam gram) untuk akurasi pengiriman.
   - Wajib melampirkan minimal **1 Foto Utama (Cover)** dengan rasio 1:1 resolusi minimal 800x800px.
   - Jika produk memiliki pilihan (warna/ukuran), tambahkan pada bagian **Variasi Produk** dan isi stok masing-masing.
   - Pastikan toggle **Aktifkan di Toko** bernilai hijau (aktif).

---

### SOP-02: Import Massal Produk via Excel / CSV
1. **Tujuan:** Mempercepat penambahan katalog puluhan hingga ratusan produk sekaligus.
2. **Langkah Kerja:**
   - Klik tombol **"Format Excel (.xlsx)"** di atas tabel produk.
   - Buka file template menggunakan Microsoft Excel atau Google Sheets.
   - Jangan mengubah nama kolom pada baris pertama header.
   - Isi kolom wajib: `name`, `price`, `stock`, `category_name`.
   - Gunakan kode **SKU** yang konsisten. Jika produk dengan SKU tersebut sudah ada di database, sistem akan otomatis memperbarui harga dan stoknya (*Upsert*).
   - Simpan file dalam format `.xlsx` atau `.csv`.
   - Klik tombol **"Import Excel / CSV"**, pilih file, dan tunggu notifikasi konfirmasi selesai.

---

### SOP-03: Pemrosesan Pesanan Baru & Verifikasi Midtrans
1. **Tujuan:** Memastikan pesanan yang diproses hanya pesanan yang pembayarannya sah dan sudah lunas.
2. **Langkah Kerja:**
   - Buka menu **Pesanan Masuk**.
   - Cek kolom status:
     - Status **Pending**: Pembeli belum menyelesaikan pembayaran di Midtrans. Jangan kirim barang.
     - Status **Paid**: Pembayaran sudah diverifikasi Midtrans secara otomatis (*Settlement*).
   - Periksa rincian barang dan alamat penerima.
   - Segera kemas barang pesanan sesuai standar keamanan packaging toko.

---

### SOP-04: Pengiriman Barang & Upload Bukti Foto Resi Fisik
1. **Tujuan:** Memberikan transparansi nomor resi dan bukti fisik kepada pembeli untuk mencegah komplain.
2. **Langkah Kerja:**
   - Setelah paket diserahkan ke kurir (JNE/J&T/Sicepat/GoSend), ambil foto struk resi fisik pengiriman menggunakan kamera HP.
   - Di daftar pesanan admin, klik tombol **"Kirim Pesanan"** (ikon truk).
   - Masukkan:
     - **Nama Kurir** (misal: `J&T Express`)
     - **Nomor Resi** (misal: `JT9283748291`)
     - **Upload Foto Bukti Resi** (pilih file foto struk, maks 10 MB).
   - Klik **Kirim Pesanan**.
   - Sistem akan otomatis mengirimkan email konfirmasi pengiriman ke pembeli bersangkutan.

---

### SOP-05: Penutupan Pembukuan & Cetak Laporan Keuangan
1. **Tujuan:** Merekap data omset penjualan dan memantau ketersediaan barang di gudang.
2. **Langkah Kerja:**
   - Buka menu **Pusat Laporan**.
   - Pilih tab **Laporan Penjualan**, atur rentang tanggal awal dan akhir (misal: 1 bulan berjalan).
   - Klik tombol **"Ekspor Excel (.xlsx)"** untuk arsip digital tim akuntansi/keuangan.
   - Klik tombol **"Cetak Laporan"** untuk mencetak rekapitulasi fisik atau simpan ke PDF.
   - Periksa tab **Laporan Stok & Inventaris** untuk mendata produk yang mendekati habis (*Low Stock*) agar segera dilakukan *restock* ke supplier.

---

## 4. FAQ & Troubleshooting Kendala Umum

**Q: Bagaimana jika pembeli komplain sudah bayar tapi status di web masih pending?**  
*A: Masuk ke halaman detail pesanan di CMS Admin atau refresh daftar pesanan. Sistem memiliki fitur Auto-Sync realtime yang akan langsung mengecek status transaksi ke server Midtrans dan mengubahnya ke Paid jika uang sudah masuk.*

**Q: Apakah pembeli bisa membatalkan pesanan?**  
*A: Pembeli hanya dapat membatalkan pesanan jika statusnya masih **Pending** (belum dibayar). Jika pesanan dibatalkan, stok produk dan stok varian otomatis dikembalikan ke etalase toko.*

**Q: Kapan status pesanan berubah menjadi Selesai (Delivered)?**  
*A: Ketika pesanan sudah sampai di tangan pembeli, pembeli cukup menekan tombol **"Pesanan Sudah Sampai / Selesai"** di dashboard akunnya. Konfirmasi ini akan langsung mengubah status pesanan menjadi Selesai.*

**Q: Apakah admin bisa mencetak invoice pesanan kapan saja?**  
*A: Bisa. Klik tombol menu aksi titik tiga pada pesanan manapun, lalu pilih **Cetak Invoice**. Invoice sudah diformat rapi standar A4 siap cetak.*

---
*Dokumentasi ini disiapkan untuk Operasional Toko E-Commerce Radiant Code. Seluruh sistem telah melalui pengujian menyeluruh dan siap digunakan secara penuh.*
