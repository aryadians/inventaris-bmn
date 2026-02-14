# 🏛️ SIMA: Sistem Informasi Manajemen Aset BMN

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-3.x-FFA500?style=for-the-badge&logo=laravel)](https://filamentphp.com)
[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-4.x-38B2AC?style=for-the-badge&logo=tailwind-css)](https://tailwindcss.com)
[![PWA](https://img.shields.io/badge/PWA-Ready-06b6d4?style=for-the-badge&logo=pwa)](https://web.dev/progressive-web-apps/)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](https://opensource.org/licenses/MIT)

**SIMA (Sistem Informasi Manajemen Aset)** adalah solusi *Enterprise Asset Management* (EAM) yang dirancang khusus untuk mendigitalisasi siklus hidup Barang Milik Negara (BMN). Aplikasi ini membantu **Lapas Kelas IIB Jombang** dalam mengelola inventaris secara akuntabel, mulai dari pengadaan, pemeliharaan, hingga penghapusan aset.

---

## 🌟 Fitur Utama (Project Roadmap)

| Modul | Fitur & Keunggulan | Ikon |
| :--- | :--- | :---: |
| **Dashboard** | Analitik visual kondisi aset (Pie Chart) & tren pengadaan biaya tahunan (Line Chart). | 📊 |
| **Self-Service** | Portal publik tanpa login bagi pegawai untuk lapor kerusakan & ajukan pinjam via QR Code. | 📱 |
| **Finansial** | Kalkulasi penyusutan otomatis (*Straight Line Method*) & laporan nilai buku real-time. | 💰 |
| **Otomatisasi** | Integrasi WhatsApp Gateway untuk notifikasi jatuh tempo & pengingat servis rutin. | 💬 |
| **Pelaporan** | Generator PDF resmi standar Kemenkumham (DBR, SPTJM, KIB, Berita Acara). | 📋 |
| **Inventaris** | Manajemen Ruangan, Kategori, Mutasi Aset, dan Stock Opname digital. | 🛠 |
| **Keamanan** | Audit Trail (Log Aktivitas) & Role-Based Access Control (RBAC) via Filament Shield. | 🛡️ |

---

## 🏗️ Arsitektur & Teknologi

Sistem ini dibangun dengan arsitektur **Monolith Modern** menggunakan **TALL Stack**:

*   **Backend:** PHP 8.2+ dengan Framework **Laravel 12**.
*   **Admin Panel:** **Filament PHP v3** (menggunakan Livewire untuk reaktivitas tinggi).
*   **Frontend:** **Tailwind CSS 4.x** dengan tema kustom *Glassmorphism* & *Dark Mode Premium*.
*   **Database:** **MySQL 8.0** dengan optimasi indeks untuk ribuan data aset.
*   **Mobile Access:** **Progressive Web App (PWA)** sehingga sistem bisa diinstal di Android/iOS.
*   **Integrasi Pihak Ketiga:** 
    *   **Fonnte API:** Untuk pengiriman pesan WhatsApp otomatis.
    *   **Simple QR Code:** Untuk generasi label QR dinamis.

---

## ⚙️ Cara Kerja Sistem (Workflows)

### 1. Siklus Hidup Aset
Admin menginput aset baru -> Sistem men-generate **QR Code** -> Label dicetak & ditempel di barang -> Sistem menghitung **Penyusutan** setiap bulan secara otomatis.

### 2. Alur Peminjaman & Mutasi
Pegawai scan QR -> Ajukan Pinjam -> Admin menyetujui -> Sistem mengirim **WhatsApp** pengingat saat mendekati tanggal kembali -> Admin mencetak **Berita Acara**.

### 3. Pemeliharaan Preventif
Setiap kategori aset memiliki **Frekuensi Servis** (misal: 3 bulan) -> Setiap hari sistem mengecek aset yang mendekati jadwal servis -> Mengirim **Notifikasi WhatsApp** ke Bagian Umum.

### 4. Audit Stock Opname
Petugas membuat sesi audit per ruangan -> Melakukan scan aset satu per satu di ruangan tersebut -> Sistem mencocokkan fisik dengan database -> Menghasilkan laporan **Aset Ditemukan / Hilang**.

---

## 🚀 Instalasi (Development)

### 1. Clone Repositori
```bash
git clone https://github.com/aryadians/inventaris-bmn.git
cd inventaris-bmn
```

### 2. Install Dependensi
```bash
composer install
npm install
```

### 3. Konfigurasi Environment
Salin file `.env.example` menjadi `.env` dan atur koneksi database Anda:
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database & Permissions
Jalankan migrasi, seeder, dan generate kebijakan keamanan:
```bash
php artisan migrate --seed
php artisan storage:link
php artisan shield:generate --all
```

### 5. Build & Run
```bash
npm run dev
# Di terminal lain
php artisan serve
```

---

## 🌐 Panduan Deployment (Production)

Untuk memindahkan sistem ke server VPS/Hosting:

1.  **Server Requirements:** PHP 8.2+, MySQL 8.0, Nginx/Apache.
2.  **Optimize Production:**
    ```bash
    composer install --optimize-autoloader --no-dev
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    npm run build
    ```
3.  **Setup Cron Job (Wajib):**
    Agar notifikasi WhatsApp otomatis berjalan, tambahkan ini ke crontab server (`crontab -e`):
    ```bash
    * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
    ```
4.  **Queue Worker (Opsional):**
    Jika menggunakan pengiriman email/WA dalam jumlah massal:
    ```bash
    php artisan queue:work --tries=3
    ```

---

## 📄 Lisensi & Kontribusi

Proyek ini bersifat Open Source di bawah lisensi **[MIT](LICENSE)**. Kontribusi sangat diharapkan untuk pengembangan fitur-fitur baru.

**Pengembang:** [Arya Dian](https://github.com/aryadians)  
**Instansi:** Lapas Kelas IIB Jombang  
**Tahun:** 2024 - 2026
