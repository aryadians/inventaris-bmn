<p align="center">
  <a href="https://github.com/aryadians/inventaris-bmn">
    <img src="public/images/logo.png" alt="Logo" width="80" height="80">
  </a>

  <h3 align="center">SIMA: Sistem Inventaris BMN</h3>

  <p align="center">
    Solusi manajemen inventaris Barang Milik Negara (BMN) berbasis web yang modern, cepat, dan akuntabel.
    <br />
    <a href="https://github.com/aryadians/inventaris-bmn"><strong>Jelajahi Dokumentasi »</strong></a>
    <br />
    <br />
    <a href="https://github.com/aryadians/inventaris-bmn/issues">Laporkan Bug</a>
    ·
    <a href="https://github.com/aryadians/inventaris-bmn/issues">Ajukan Fitur</a>
  </p>
</p>

<div align="center">

[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-v3-FAA04B?style=for-the-badge&logo=filament&logoColor=white)](https://filamentphp.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)
[![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)

<a href="https://github.com/aryadians/inventaris-bmn/fork"><img src="https://img.shields.io/github/forks/aryadians/inventaris-bmn?style=social" alt="Forks"></a>
<a href="https://github.com/aryadians/inventaris-bmn/stargazers"><img src="https://img.shields.io/github/stars/aryadians/inventaris-bmn?style=social" alt="Stars"></a>
<a href="https://github.com/aryadians/inventaris-bmn/blob/main/LICENSE"><img src="https://img.shields.io/github/license/aryadians/inventaris-bmn?style=flat-square" alt="License"></a>
<img src="https://img.shields.io/github/last-commit/aryadians/inventaris-bmn" alt="Last Commit">

</div>

---

## 📖 Tentang Aplikasi

**SIMA (Sistem Informasi Manajemen Aset)** dirancang untuk mendigitalisasi pengelolaan aset negara di lingkungan Lapas Kelas IIB Jombang. Aplikasi ini berfokus pada kemudahan pemantauan riwayat barang, otomatisasi notifikasi, dan pelaporan keuangan aset yang akurat.

### ✨ Fitur Utama (Terbaru)

* 🔔 **Notifikasi Otomatis:**
    * Pengingat otomatis via Dashboard/Email kepada peminjam sebelum tanggal kembali tiba.
    * Alert instan bagi Admin untuk permintaan peminjaman baru dan jadwal pemeliharaan rutin.
* 📉 **Laporan Penyusutan Aset:** Perhitungan nilai buku secara otomatis menggunakan metode Garis Lurus (Straight Line) sesuai standar akuntansi BMN.
* 🔍 **Advanced Filtering:** Filter aset dinamis berdasarkan kategori, ruangan, kondisi fisik, dan rentang waktu perolehan.
* 📋 **Manajemen Siklus Aset:** Meliputi manajemen Ruangan, Kategori, Mutasi (perpindahan), dan Stock Opname berkala.
* 🤝 **Peminjaman & Pemeliharaan:** Pelacakan status peminjaman aktif dan pencatatan riwayat servis vendor.
* 📠 **Labeling & QR:** Generasi label QR unik dan integrasi pemindaian QR untuk identifikasi cepat.
* 📥 **Data Portability:** Impor dan Ekspor data aset secara massal melalui file Excel.

---

## 🛠️ Stack Teknologi

* **Framework:** [Laravel 11](https://laravel.com/)
* **Admin Panel:** [Filament v3](https://filamentphp.com/)
* **UI/Styling:** [Tailwind CSS](https://tailwindcss.com/)
* **Database:** [MySQL 8.0](https://www.mysql.com/)

---

## 🚀 Memulai

### Prasyarat
* PHP >= 8.2
* Composer
* Node.js & NPM
* Database MySQL

### Instalasi Langkah-demi-Langkah

1.  **Clone Repositori**
    ```sh
    git clone [https://github.com/aryadians/inventaris-bmn.git](https://github.com/aryadians/inventaris-bmn.git)
    cd inventaris-bmn
    ```

2.  **Instalasi Dependensi**
    ```sh
    composer install
    npm install && npm run build
    ```

3.  **Konfigurasi Environment**
    ```sh
    cp .env.example .env
    php artisan key:generate
    ```
    *Sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di file `.env`.*

4.  **Migrasi & Seed Database**
    ```sh
    php artisan migrate --seed
    ```

5.  **Optimasi & Tautan Storage**
    ```sh
    php artisan storage:link
    php artisan filament:optimize
    ```

6.  **Jalankan Aplikasi**
    ```sh
    php artisan serve
    ```

---

## 📉 Metodologi Penyusutan
Aplikasi ini menggunakan perhitungan penyusutan otomatis berdasarkan Masa Manfaat yang ditentukan pada setiap kategori barang:

$$Penyusutan\ Per\ Tahun = \frac{Harga\ Perolehan}{Masa\ Manfaat}$$

---

## 📑 Sitasi (Citation)

Jika Anda menggunakan proyek ini untuk keperluan akademik, silakan kutip sebagai berikut:

**Format APA:**
> Dian, A. (2026). *SIMA: Sistem Informasi Manajemen Aset BMN berbasis Laravel & Filament* (Versi 1.1.0) [Computer software]. https://github.com/aryadians/inventaris-bmn

---

## ✉️ Kontak

**Arya Dian**
* Instagram: [@aransptr_](https://instagram.com/aransptr_)
* Email: [aryadian003@gmail.com](mailto:aryadian003@gmail.com)
* GitHub: [aryadians](https://github.com/aryadians)

---
<p align="center"> Dilisensikan di bawah <strong>Lisensi MIT</strong> </p>
