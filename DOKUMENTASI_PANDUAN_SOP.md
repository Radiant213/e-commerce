# 📘 BUKU PANDUAN OPERASIONAL & SOP SISTEM E-COMMERCE
**Platform E-Commerce Modern Full-Stack (Laravel 11 + Filament CMS + React Vite + Midtrans Gateway)**

---

## 📑 DAFTAR ISI
1. [Ringkasan Arsitektur & Peran Pengguna](#1-ringkasan-arsitektur--peran-pengguna)
2. [Panduan Rekaman Video Tutorial (1 Video All-in-One: 3 Section & Timestamps YouTube)](#2-panduan-rekaman-video-tutorial-1-video-all-in-one-3-section--timestamps-youtube)
   - [Aturan Wajib YouTube Video Chapters](#aturan-wajib-fitur-youtube-chapters-track-play)
   - [Template Deskripsi YouTube Siap Pakai (Timestamps & Links)](#template-deskripsi-youtube-siap-pakai)
   - [Section 1: Pengalaman Berbelanja Pembeli (Frontend Toko)](#section-1-pengalaman-berbelanja-pembeli-frontend-toko)
   - [Section 2: CMS Admin Part 1 - Manajemen Katalog & Master Produk](#section-2-cms-admin-part-1---manajemen-katalog--master-produk)
   - [Section 3: CMS Admin Part 2 - Operasional Transaksi, Resi, & Laporan](#section-3-cms-admin-part-2---operasional-transaksi-resi--laporan)
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

## 2. Panduan Rekaman Video Tutorial (1 Video All-in-One: 3 Section & Timestamps YouTube)

> **Konsep:** 1 Video Komprehensif (Durasi ± 15 - 18 Menit) yang terbagi menjadi **3 Bagian Besar (Section)** menggunakan fitur **YouTube Video Chapters**.  
> Di video player YouTube, bar pemutaran (*track play / scrubber*) akan otomatis terpecah menjadi babak-babak interaktif yang memudahkan penonton meloncat ke topik yang mereka butuhkan.

### Aturan Wajib Fitur YouTube Chapters (Track Play)
Agar timeline YouTube otomatis terbagi menjadi segmen-segmen interaktif, format berikut **WAJIB** dipenuhi:
1. Timestamp pertama **HARUS dimulai dari `00:00`** (Jika dimulai dari 00:05 atau detik lain, fitur chapter otomatis gagal aktif).
2. Minimal memiliki **3 timestamp** secara kronologis (waktu naik).
3. Setiap babak/chapter minimal berdurasi **10 detik**.
4. Akun YouTube Anda tidak memiliki teguran pedoman komunitas aktif (fitur chapter aktif default untuk semua video standar).

---

### Template Deskripsi YouTube Siap Pakai

Salin teks berikut ke kolom **Deskripsi Video YouTube** Anda (sesuaikan menit dan detik setelah rekaman selesai):

```text
🚀 Demo & Tutorial Lengkap Sistem E-Commerce Full-Stack Modern (Laravel 11 + Filament CMS + React 19 + Midtrans Gateway).
Video ini mencakup panduan lengkap dari sudut pandang Pembeli (Frontend Storefront) hingga pengelolaan operasional toko online oleh Administrator (CMS Backend).

Gunakan Chapter / Timestamp di bawah untuk langsung menuju bagian yang Anda inginkan:

⏱️ DAFTAR ISI VIDEO (CHAPTERS):
00:00 - Pendahuluan & Overview Sistem E-Commerce
00:30 - [SECTION 1] Pengalaman Berbelanja Pembeli (Frontend)
01:00 - Registrasi Akun, Login Google & Fitur Fast Demo
02:15 - Eksplorasi Etalase & Pencarian Cerdas (Smart Search)
03:45 - Halaman Detail Produk, Video Showcase & Pilihan Varian
05:10 - Keranjang Belanja & Checkout Terintegrasi Buku Alamat
06:30 - Pembayaran Otomatis Midtrans Snap (QRIS & Virtual Account)
07:50 - Dashboard Pelanggan, Lacak Resi & Konfirmasi Pesanan Selesai

09:15 - [SECTION 2] CMS Admin: Manajemen Katalog & Produk
09:45 - Login Filament Admin Panel & Statistik Dashboard
10:45 - Kelola Kategori Produk & Icon Lucide
12:00 - Input Produk Lengkap: Deskripsi, Spesifikasi & Video Showcase
14:10 - Konfigurasi Varian Produk (Warna/Ukuran) & Galeri Media
15:30 - Fitur Import & Export Massal Produk via Excel / CSV

17:00 - [SECTION 3] CMS Admin: Operasional Pesanan, Resi & Laporan
17:30 - Monitoring Pesanan Masuk & Verifikasi Pembayaran Midtrans
18:45 - Proses Pengiriman, Input Resi & Upload Bukti Foto Resi Fisik
20:00 - Cetak Invoice & Label Pengiriman Paket
21:15 - Pusat Laporan Penjualan, Laporan Stok & Ekspor Excel
22:30 - Pengaturan Toko, Banner Slider & Penutup

🔗 TAUTAN AKSES APLIKASI:
• Website Toko: https://demo1-ecommerce.radiantcode.web.id
• Admin Panel: https://api-ecommerce.radiantcode.web.id/admin (Akun: admin@radiantcode.web.id / password123)
• Source Code GitHub: https://github.com/Radiant213/e-commerce

🛠️ TEKNOLOGI YANG DIGUNAKAN:
- Backend: Laravel 11, PHP 8.2+, MySQL
- Admin CMS: Filament PHP v4
- Frontend: React 19, Vite, Tailwind CSS, Lucide Icons
- Payment Gateway: Midtrans Snap API (Sandbox/Production)
- Deployment: Docker Nginx + VPS Ubuntu 24.04 LTS

Jangan lupa Like, Share, dan Subscribe jika video ini bermanfaat!
```

---

### Section 1: Pengalaman Berbelanja Pembeli (Frontend Toko)
> **Estimasi Durasi:** ± 5 – 7 Menit  
> **Tujuan:** Menunjukkan pengalaman antarmuka yang modern, cepat, responsif, dan alur belanja tanpa kendala.

#### 🎬 Urutan Adegan Rekaman:
1. **Scene 1.1: Registrasi & Pilihan Login Cepat**
   - Buka halaman utama website (`https://demo1-ecommerce.radiantcode.web.id`).
   - Tunjukkan tombol **Masuk / Daftar** di navbar.
   - Sorot opsi login fleksibel:
     - Login reguler (Email & Sandi).
     - **Login 1-Klik Akun Google**.
     - **Tombol Fast Demo**: klik badge *Customer* untuk langsung masuk tanpa perlu mengetik kredensial.
2. **Scene 1.2: Eksplorasi Etalase & Smart Search**
   - Gunakan search bar di bagian atas.
   - Ketik kata kunci parsial (misal: `"sony"`, `"headphone hitam"`, atau SKU `"PRD-"`).
   - Tunjukkan pencarian cerdas yang memecah kata kunci secara akurat.
   - Filter produk berdasarkan kategori dan urutkan harga terendah/terlaris.
3. **Scene 1.3: Halaman Detail Produk & Variasi**
   - Buka salah satu produk unggulan (misal: *Sony WH-1000XM5*).
   - Tunjukkan:
     - **Video Showcase Produk** (bisa langsung diputar lancar di halaman produk).
     - **Galeri Foto Interaktif**.
     - **Pemilihan Varian** (pilih warna/tipe, tunjukkan harga dan stok otomatis berganti sesuai varian).
     - **Tabel Spesifikasi Teknis**.
     - **Ulasan Pembeli** dengan lencana *Verified Purchase*.
   - Klik **Beli Sekarang** atau **+ Keranjang**.
4. **Scene 1.4: Checkout & Pembayaran Midtrans Snap**
   - Masuk ke keranjang dan lanjut ke Checkout.
   - Pilih alamat dari **Buku Alamat Tersimpan** (atau isi alamat penerima baru).
   - Klik tombol **Bayar Sekarang**.
   - Pop-up modal **Midtrans Snap** terbuka:
     - Pilih metode pembayaran (misal: QRIS Simulator atau Virtual Account).
     - Selesaikan pembayaran simulasi hingga status berhasil (*Payment Success*).
5. **Scene 1.5: Dashboard Pelanggan & Tombol "Pesanan Selesai"**
   - Masuk ke menu **Akun Saya ➔ Riwayat Pesanan**.
   - Perhatikan stepper 4 status visual:
     1. Menunggu Pembayaran ➔ 2. Diproses ➔ 3. Dikirim ➔ 4. Diterima.
   - Tunjukkan nomor resi dan tombol **"Lihat Foto Resi"** untuk memeriksa bukti foto paket fisik yang dikirimkan penjual.
   - Klik tombol hijau **"Pesanan Sudah Sampai / Selesai"**.
   - Tunjukkan **Modal Dialog Konfirmasi Estetik Baru** yang muncul dengan backdrop blur. Klik *"Ya, Sudah Diterima"*, pesanan langsung berstatus *Delivered (Selesai)*.

---

### Section 2: CMS Admin Part 1 - Manajemen Katalog & Master Produk
> **Estimasi Durasi:** ± 5 – 6 Menit  
> **Tujuan:** Menunjukkan betapa cepat dan rapinya tim admin dalam mengelola etalase, kategori, stok, media showcase, dan import massal.

#### 🎬 Urutan Adegan Rekaman:
1. **Scene 2.1: Overview Filament Admin Panel**
   - Buka `/admin` dan login sebagai Administrator.
   - Jelaskan widget Dashboard: Total Pendapatan, Pesanan Baru, Grafik Penjualan Mingguan, dan status stok.
2. **Scene 2.2: Mengelola Kategori Produk**
   - Buka menu **Katalog Produk ➔ Kategori Produk**.
   - Tambah kategori baru:
     - Masukkan nama kategori.
     - Pilih ikon Lucide modern (misal: `Laptop`, `Smartphone`).
     - Tunjukkan fleksibilitas banner: bisa upload file lokal atau input tautan gambar langsung (URL).
3. **Scene 2.3: Input Produk Baru Lengkap**
   - Masuk ke **Katalog Produk ➔ Produk ➔ Buat Produk**.
   - Isi form bertahap:
     - **Info Utama:** Nama produk, kategori, harga dasar, harga diskon coret, stok awal.
     - **Deskripsi & Spesifikasi:** Tulis deskripsi rich-text & tambahkan baris spesifikasi teknis dinamis.
     - **Video Showcase:** Tunjukkan upload video lokal (MP4 hingga 100MB) atau paste link YouTube produk.
     - **Variasi Produk:** Tambahkan varian (misal warna Hitam, Perak) lengkap dengan stok dan thumbnail varian.
   - Klik **Simpan** dan buka tab frontend untuk membuktikan produk langsung muncul rapi di etalase pembeli.
4. **Scene 2.4: Fitur Import & Export Excel / CSV**
   - Di tabel produk, klik tombol **"Format Excel (.xlsx)"** untuk mengunduh template resmi.
   - Tunjukkan kolom-kolom Excel yang mudah dipahami.
   - Klik tombol **"Import Excel / CSV"**, pilih file, dan jalankan proses import.
   - Tunjukkan notifikasi keberhasilan dan pembaruan data produk secara otomatis (Upsert).

---

### Section 3: CMS Admin Part 2 - Operasional Transaksi, Resi, & Laporan
> **Estimasi Durasi:** ± 5 – 6 Menit  
> **Tujuan:** Panduan langkah demi langkah menangani pesanan pembeli, integrasi resi kurir, pencetakan dokumen toko, hingga pembukuan keuangan.

#### 🎬 Urutan Adegan Rekaman:
1. **Scene 3.1: Memantau Pesanan Masuk & Verifikasi Midtrans**
   - Buka menu **Penjualan & Transaksi ➔ Pesanan Masuk**.
   - Sorot badge notifikasi pesanan pending di bilah navigasi kiri.
   - Buka detail pesanan yang baru saja dibayar di Section 1:
     - Tunjukkan rincian item belanja.
     - Tunjukkan bagian **Detail Transaksi Midtrans**: Settlement lunas, ID Transaksi Midtrans, dan channel pembayaran.
2. **Scene 3.2: Pengiriman Barang & Upload Foto Resi Fisik**
   - Di daftar pesanan (atau halaman detail), klik aksi **"Kirim Pesanan"**:
     - Masukkan nama kurir (misal: *JNE YES* / *SiCepat*).
     - Masukkan nomor resi pengiriman.
     - **Upload Foto Bukti Resi Fisik Pengiriman** (foto kertas struk dari agen ekspedisi).
   - Klik **Kirim Pesanan**.
   - Jelaskan bahwa sistem otomatis mengirim email notifikasi pengiriman ke pembeli beserta tautan resi dan lampiran foto fisik paket.
3. **Scene 3.3: Cetak Invoice & Label Alamat Paket**
   - Klik aksi **"Cetak Invoice"** pada pesanan.
   - Tunjukkan tampilan dokumen cetak invoice resmi siap print (PDF) yang berisi rincian barang dan alamat penerima untuk ditempel langsung di kardus paket.
4. **Scene 3.4: Pusat Laporan & Pembukuan Keuangan**
   - Masuk ke menu **Laporan & Analisis ➔ Pusat Laporan**.
   - Tunjukkan 4 tab laporan lengkap:
     - **Laporan Penjualan:** filter periode tanggal, rekap omset, tombol *Cetak Laporan Penjualan* & *Ekspor Excel*.
     - **Laporan Stok & Inventaris:** status *Out of Stock* dan *Low Stock Alert*, tombol *Cetak Laporan Stok*.
     - **Produk Terlaris:** top ranking barang paling laku.
     - **Pelanggan Terbaik:** pembeli dengan loyalitas transaksi tertinggi.
5. **Scene 3.5: Pengaturan Sistem Toko & Penutup**
   - Buka menu **Pengaturan Sistem**: manajemen profil toko, konfigurasi slider banner promo halaman depan.
   - Penutup video: ringkasan integrasi keseluruhan dan ajakan untuk mencoba demo publik.

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
