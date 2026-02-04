<p align="center">
  <a href="https://github.com/aryadians/inventaris-bmn">
    <img src="public/images/logo.png" alt="Logo" width="80" height="80">
  </a>

  <h3 align="center">SIMA: Sistem Inventaris BMN</h3>

  <p align="center">
    Solusi enterprise manajemen inventaris Barang Milik Negara (BMN) berbasis web yang modern, cepat, dan akuntabel.
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
[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-v3-FAA04B?style=for-the-badge&logo=filament&logoColor=white)](https://filamentphp.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-005C84?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)

<a href="https://github.com/aryadians/inventaris-bmn/fork"><img src="https://img.shields.io/github/forks/aryadians/inventaris-bmn?style=social" alt="Forks"></a>
<a href="https://github.com/aryadians/inventaris-bmn/stargazers"><img src="https://img.shields.io/github/stars/aryadians/inventaris-bmn?style=social" alt="Stars"></a>
<a href="https://github.com/aryadians/inventaris-bmn/blob/main/LICENSE"><img src="https://img.shields.io/github/license/aryadians/inventaris-bmn?style=flat-square" alt="License"></a>
<img src="https://img.shields.io/github/last-commit/aryadians/inventaris-bmn" alt="Last Commit">

</div>

---

## 📖 Tentang Aplikasi

**SIMA (Sistem Informasi Manajemen Aset)** adalah solusi enterprise-grade untuk mendigitalisasi pengelolaan aset negara di lingkungan Lapas Kelas IIB Jombang. Aplikasi ini dilengkapi dengan fitur-fitur advanced seperti workflow automation, role-based access control, REST API, dan real-time analytics dashboard.

### ✨ Fitur Utama

#### 🎯 **Core Features**
* **Manajemen Aset Lengkap:** CRUD aset dengan tracking history lengkap, foto, dan QR code unik
* **Digital Stock Opname:** Sistem stock opname digital dengan barcode scanner dan laporan discrepancy
* **Peminjaman Aset:** Workflow peminjaman dengan approval, tracking, dan reminder otomatis
* **Pemeliharaan & Servis:** Ticketing system untuk maintenance dengan vendor tracking dan biaya
* **Mutasi Aset:** Transfer aset antar ruangan dengan audit trail lengkap
* **Penyusutan Otomatis:** Perhitungan depresiasi menggunakan metode Straight Line sesuai standar BMN

#### 🔐 **Security & Access Control**
* **Role-Based Access Control (RBAC):** 3 roles (Manager, Staff, Teknisi) dengan permissions granular
* **Shield Integration:** Filament Shield untuk policy management
* **Audit Log:** Complete audit trail untuk semua perubahan data kritis

#### 🛒 **Procurement Workflow**
* **Pengadaan Digital:** Sistem pengajuan procurement dengan multi-approval
* **Status Tracking:** Real-time tracking dari draft hingga received
* **Auto Asset Creation:** Automatic conversion procurement item to assets
* **Email Notifications:** Notifikasi otomatis approval/rejection ke pengaju

#### 📊 **Analytics & Reporting**
* **Executive Dashboard:** 
  - Total Asset Value Widget (4 stats cards)
  - Procurement Trend Chart (6-month historical data)
  - Maintenance Cost Analysis (monthly comparison)
* **Advanced Filters:** Filter dinamis berdasarkan kategori, ruangan, kondisi, tanggal
* **Excel Export:** Bulk export data dengan custom columns
* **PDF Reports:** Generate laporan aset dengan QR code labels

#### 🌐 **Integration & API**
* **RESTful API:** 3 public endpoints (list assets, detail, search by QR)
* **Laravel Sanctum:** Token-based authentication untuk API
* **Email Notifications:** Professional email templates untuk procurement workflow
* **Webhook Ready:** Architecture siap untuk webhook integration

#### 📱 **Mobile & PWA**
* **Progressive Web App:** Install ke home screen tanpa app store
* **QR Scanner:** Built-in camera scanner untuk asset identification
* **Offline Support:** Basic offline capability dengan service worker
* **Responsive Design:** Mobile-first UI/UX design

#### ⚡ **Performance**
* **Database Indexing:** 18 optimized indexes untuk query speed
* **Widget Caching:** 5-minute cache untuk dashboard analytics
* **Lazy Loading:** Progressive widget loading untuk fast page render
* **Query Optimization:** Eager loading untuk eliminate N+1 queries

#### 🎨 **UI/UX Excellence**
* **Professional Blue Theme:** Modern corporate color palette
* **Custom Branding:** Logo, favicon, dan brand name customizable
* **Dark Mode:** Full dark mode support
* **Poppins Font:** Premium typography untuk better readability
* **Collapsible Sidebar:** Desktop optimization dengan auto-collapse

---

## 🛠️ Stack Teknologi

* **Framework:** [Laravel 12.x](https://laravel.com/)
* **Admin Panel:** [Filament v3](https://filamentphp.com/)
* **UI/Styling:** [Tailwind CSS](https://tailwindcss.com/)
* **Database:** [MySQL 8.0](https://www.mysql.com/)
* **Authentication:** [Laravel Sanctum](https://laravel.com/docs/sanctum)
* **Permissions:** [Filament Shield](https://github.com/bezhanSalleh/filament-shield)
* **Excel:** [Laravel Excel](https://laravel-excel.com/)
* **PDF:** [Laravel DomPDF](https://github.com/barryvdh/laravel-dompdf)
* **QR Code:** [Simple QrCode](https://www.simplesoftware.io/docs/simple-qrcode)

---

## 🚀 Memulai

### Prasyarat
* PHP >= 8.2
* Composer
* Node.js & NPM
* MySQL 8.0+
* Extension PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, GD

### Instalasi Langkah-demi-Langkah

1.  **Clone Repositori**
    ```sh
    git clone https://github.com/aryadians/inventaris-bmn.git
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
    *Sesuaikan variabel di file `.env`:*
    ```env
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_user
    DB_PASSWORD=your_database_password
    
    # Email Configuration (optional)
    MAIL_MAILER=smtp
    MAIL_HOST=smtp.gmail.com
    MAIL_PORT=587
    MAIL_USERNAME=your-email@gmail.com
    MAIL_PASSWORD=your-app-password
    ```

4.  **Migrasi & Seed Database**
    ```sh
    php artisan migrate --seed
    php artisan shield:generate --all
    ```

5.  **Seed Test Data (Optional)**
    ```sh
    php artisan db:seed --class=ComprehensiveDataSeeder
    ```
    *Ini akan membuat 60+ sample assets, 3 users dengan roles, dan test data.*

6.  **Optimasi & Tautan Storage**
    ```sh
    php artisan storage:link
    php artisan filament:optimize
    php artisan route:cache
    php artisan config:cache
    ```

7.  **Jalankan Aplikasi**
    ```sh
    # Development
    npm run dev
    php artisan serve
    
    # Production
    npm run build
    ```

8.  **Akses Aplikasi**
    - URL: `http://localhost:8000/admin`
    - Email: `admin@admin.com` (default)
    - Password: `password`

---

## 📚 Dokumentasi Lengkap

### Default Users (after seeding)
| Email | Role | Password |
|-------|------|----------|
| `manager@lapas.go.id` | Manager | `password` |
| `staff@lapas.go.id` | Staff | `password` |
| `teknisi@lapas.go.id` | Teknisi | `password` |

### API Endpoints
```bash
# List all assets (with filters)
GET /api/assets?status=AKTIF&search=laptop

# Get asset details
GET /api/assets/{id}

# Search by QR code
POST /api/assets/qr
Body: { "qr_code": "ASSET-001" }
```

### Key Artisan Commands
```bash
# Clear all caches
php artisan cache:clear
php artisan filament:optimize-clear

# Generate policies for new resources
php artisan shield:generate --all

# Create test data
php artisan db:seed --class=ComprehensiveDataSeeder
```

---

## 📉 Metodologi Penyusutan
Aplikasi ini menggunakan perhitungan penyusutan otomatis berdasarkan Masa Manfaat yang ditentukan pada setiap kategori barang:

$$Penyusutan\ Per\ Tahun = \frac{Harga\ Perolehan}{Masa\ Manfaat}$$

Nilai Buku dihitung secara real-time setiap kali data diakses.

---

## 🎯 Roadmap & Future Features

- [ ] Multi-tenancy (SaaS mode)
- [ ] Advanced workflow (multi-level approval)
- [ ] AI-powered depreciation prediction
- [ ] Automated procurement suggestions
- [ ] Mobile apps (React Native)
- [ ] Blockchain integration untuk audit trail
- [ ] IoT integration untuk asset tracking

---

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan:
1. Fork repository
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buka Pull Request

---

## 📑 Sitasi (Citation)

Jika Anda menggunakan proyek ini untuk keperluan akademik, silakan kutip sebagai berikut:

**Format APA:**
> Dian, A. (2026). *SIMA: Sistem Informasi Manajemen Aset BMN Enterprise* (Versi 2.0.0) [Computer software]. https://github.com/aryadians/inventaris-bmn

---

## 📄 Lisensi

Dilisensikan di bawah **Lisensi MIT**. Lihat file `LICENSE` untuk detail lengkap.

---

## ✉️ Kontak

**Arya Dian**
* Instagram: [@aransptr_](https://instagram.com/aransptr_)
* Email: [aryadian003@gmail.com](mailto:aryadian003@gmail.com)
* GitHub: [aryadians](https://github.com/aryadians)

---

## 🙏 Acknowledgments

* Terima kasih kepada tim Lapas Kelas IIB Jombang
* Filament Team untuk amazing admin panel framework
* Laravel Community untuk ecosystem yang luar biasa
* Contributors & testers yang telah membantu

---

<p align="center">
  Made with ❤️ in Indonesia
  <br>
  <strong>SIMA - Digitalisasi BMN untuk Indonesia Lebih Baik</strong>
</p>
