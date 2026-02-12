<p align="center">
  <a href="https://github.com/aryadians/inventaris-bmn">
    <img src="public/images/logo.png" alt="Logo" width="120" height="120">
  </a>

  <h1 align="center">SIMA: Sistem Informasi Manajemen Aset BMN</h1>

  <p align="center">
    <strong>Enterprise-Grade Asset Management Solution</strong><br />
    Platform digital terintegrasi untuk pengelolaan, pemantauan, dan akuntansi Barang Milik Negara (BMN) di instansi pemerintah.
    <br />
    <br />
    <a href="https://github.com/aryadians/inventaris-bmn/issues">Laporkan Bug</a>
    ·
    <a href="https://github.com/aryadians/inventaris-bmn/issues">Ajukan Fitur</a>
    ·
    <a href="mailto:aryadian003@gmail.com">Kontak Pengembang</a>
  </p>
</p>

<div align="center">

![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Laravel Version](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Filament Version](https://img.shields.io/badge/Filament-v3-FAA04B?style=for-the-badge&logo=filament&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-005C84?style=for-the-badge&logo=mysql&logoColor=white)

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=flat-square)](https://opensource.org/licenses/MIT)
[![Stars](https://img.shields.io/github/stars/aryadians/inventaris-bmn?style=flat-square)](https://github.com/aryadians/inventaris-bmn/stargazers)
![PWA Ready](https://img.shields.io/badge/PWA-Ready-orange?style=flat-square&logo=pwa)
![Optimization](https://img.shields.io/badge/Performance-High-brightgreen?style=flat-square)

</div>

---

## 📖 Deskripsi Proyek

**SIMA (Sistem Informasi Manajemen Aset)** adalah solusi transformasi digital yang dirancang untuk menggantikan pencatatan inventaris manual menjadi sistem otomatis yang akurat. Dikembangkan dengan teknologi terbaru, SIMA menangani seluruh siklus hidup aset BMN—mulai dari perencanaan pengadaan, penggunaan sehari-hari, pemeliharaan rutin, hingga proses penghapusan aset dari daftar negara.

Aplikasi ini dioptimalkan untuk performa tinggi (SPA-style) dan mendukung penggunaan di perangkat mobile secara native (PWA).

---

## ✨ Fitur Lengkap (End-to-End)

Berikut adalah daftar lengkap fitur yang tersedia di dalam sistem SIMA:

### 📦 1. Manajemen Aset & Basis Data
| Fitur | Deskripsi |
| :--- | :--- |
| **Katalog Aset Utama** | Manajemen data barang lengkap (Merk, Tipe, No. Seri, NUP, Tahun Perolehan). |
| **Visual Evidence** | Upload foto fisik barang untuk verifikasi kondisi secara visual. |
| **QR Code System** | Generasi QR Code otomatis per item untuk identifikasi cepat di lapangan. |
| **E-Labeling** | Cetak label aset (thermal/grid) langsung dari sistem untuk ditempel ke fisik barang. |
| **Kategorisasi BMN** | Pengelompokan barang sesuai kode akun BMN standar pemerintah. |

### 📈 2. Akuntansi & Nilai Aset
| Fitur | Deskripsi |
| :--- | :--- |
| **Penyusutan Otomatis** | Kalkulasi depresiasi metode *Straight Line* (Garis Lurus) secara real-time. |
| **Masa Manfaat** | Pengaturan umur ekonomis per kategori untuk akurasi nilai buku. |
| **Financial Tracking** | Monitoring harga perolehan, akumulasi penyusutan, dan nilai residu aset. |
| **Laporan Penyusutan** | Cetak laporan rekapitulasi nilai aset tahunan ke format PDF. |

### 🏢 3. Inventarisasi & Lokasi
| Fitur | Deskripsi |
| :--- | :--- |
| **Manajemen Ruangan** | Pengelolaan data lokasi/ruangan beserta penanggung jawab (PIC). |
| **Cetak KIB (DBR)** | **(New)** Cetak Kartu Inventaris Barang / Daftar Barang Ruangan untuk ditempel di pintu. |
| **Tracking Eksternal** | Fitur khusus untuk aset yang dipinjam pakai/dibawa pulang (dilengkapi SPTJM). |
| **Mutasi Aset** | Pencatatan otomatis setiap kali barang berpindah antar ruangan (Audit Trail). |

### 🔄 4. Alur Kerja Operasional
| Fitur | Deskripsi |
| :--- | :--- |
| **Peminjaman Aset** | Workflow peminjaman digital dengan status (Dipinjam/Kembali). |
| **Quick Return** | Tombol satu-klik untuk konfirmasi pengembalian barang. |
| **Pemeliharaan (Servis)** | Sistem ticketing kerusakan barang, tracking vendor, dan pencatatan biaya perbaikan. |
| **Audit Stock Opname** | Pemeriksaan berkala menggunakan scanner untuk mencocokkan fisik vs sistem. |
| **Pengadaan (Procurement)** | Modul pengajuan barang baru dengan sistem persetujuan (Approval) bertingkat. |
| **Auto-Asset Creation** | Barang yang diterima dari pengadaan otomatis menjadi data aset baru. |

### 🗑️ 5. Administrasi & Penghapusan (New)
| Fitur | Deskripsi |
| :--- | :--- |
| **Modul Penghapusan** | Proses penghapusan aset dari sistem (Pemusnahan, Lelang, Hibah). |
| **Manajemen SK** | Upload Surat Keputusan (SK) dan Berita Acara (BA) penghapusan aset. |
| **Update Status Final** | Aset yang telah dihapus otomatis masuk ke arsip dan tidak muncul di daftar aktif. |

### 📱 6. Teknologi Mobile & Notifikasi
| Fitur | Deskripsi |
| :--- | :--- |
| **PWA (Installable)** | Dapat diinstal di Android/iOS dan berjalan seperti aplikasi mobile. |
| **High-Speed Scanner** | Scanner QR kamera HP dengan UI profesional, laser animation, dan beep feedback. |
| **WA Reminder** | **(New)** Kirim pengingat jatuh tempo peminjaman otomatis via WhatsApp. |
| **Email Integration** | Notifikasi persetujuan pengadaan dikirim langsung ke email user. |

### 🔐 7. Keamanan & Performa
| Fitur | Deskripsi |
| :--- | :--- |
| **RBAC (Shield)** | Hak akses granular (Super Admin, Admin, Staff, Peminjam). |
| **Audit Logs** | Mencatat siapa, kapan, dan apa yang diubah pada setiap data (Audit Compliance). |
| **SPA Navigation** | Perpindahan halaman super cepat tanpa reload browser. |
| **Deferred Loading** | Tabel memuat data di background untuk menghindari "hang" pada data besar. |

---

## 📊 Executive Dashboard

SIMA menyediakan visualisasi data real-time untuk pengambil keputusan:
*   **Stats Widget:** Total Unit, Total Nilai Aset, Barang Rusak, Nilai Buku.
*   **Kondisi Chart:** Donut chart perbandingan kondisi Baik/Rusak.
*   **Value Chart:** Bar chart nilai aset per kategori barang.
*   **Maintenance Trend:** **(New)** Line chart biaya perbaikan bulanan.
*   **Recent Activity:** Daftar mutasi dan peminjaman terbaru.

---

## 🚀 Panduan Instalasi (Development)

### 1. Kloning & Dependensi
```bash
git clone https://github.com/aryadians/inventaris-bmn.git
cd inventaris-bmn
composer install
npm install
```

### 2. Konfigurasi Sistem
```bash
cp .env.example .env
php artisan key:generate
```
*Atur koneksi DB di `.env` (DB_DATABASE, DB_USERNAME, DB_PASSWORD).*

### 3. Migrasi & Seed
```bash
php artisan migrate --seed
# Untuk data demo:
php artisan db:seed --class=ComprehensiveDataSeeder
```

### 4. Build & Run
```bash
php artisan storage:link
npm run dev
php artisan serve
```

---

## 🌐 Panduan Deployment (Production)

Untuk performa "Turbo" di server produksi:

1.  **Optimasi Aset & Cache:**
    ```bash
    npm run build
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan filament:optimize
    ```
2.  **WhatsApp Integration:**
    Masukkan API Key Fonnte Anda ke `.env`: `WA_API_KEY=xxx`
3.  **Cron Job (Scheduler):**
    Tambahkan ini ke crontab server Anda:
    `* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1`

---

## 📂 Struktur Direktori Utama

*   `app/Filament/Resources`: Logika utama Admin Panel (CRUD, Table, Form).
*   `app/Models`: Definisi skema database dan relasi antar aset.
*   `app/Services`: Integrasi pihak ketiga (WhatsApp API).
*   `database/migrations`: Histori struktur database.
*   `resources/views/pdf`: Template laporan (KIB, Bukti Pinjam, Penyusutan).

---

## ✉️ Kontak & Kontribusi

Kami sangat terbuka untuk masukan dan kontribusi pengembang lain.

**Arya Dian**
*   **Instagram:** [@aransptr_](https://instagram.com/aransptr_)
*   **GitHub:** [aryadians](https://github.com/aryadians)
*   **Email:** [aryadian003@gmail.com](mailto:aryadian003@gmail.com)

---
<p align="center">
  Dibuat dengan ❤️ untuk Lapas Kelas IIB Jombang dan Indonesia.
</p>
