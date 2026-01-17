<p align="center">
  <a href="https://github.com/aryadians/inventaris-bmn">
    <img src="public/images/logo.png" alt="Logo" width="80" height="80">
  </a>

  <h3 align="center">Sistem Inventaris BMN</h3>

  <p align="center">
    Sistem Inventaris BMN (Barang Milik Negara) adalah sebuah aplikasi berbasis web yang dirancang untuk memudahkan proses pengelolaan dan pemantauan aset negara di lingkungan Politeknik Negeri Indramayu.
    <br />
    <a href="https://github.com/aryadians/inventaris-bmn"><strong>Explore the docs »</strong></a>
    <br />
    <br />
    <a href="https://github.com/aryadians/inventaris-bmn/issues">Report Bug</a>
    ·
    <a href="https://github.com/aryadians/inventaris-bmn/issues">Request Feature</a>
  </p>
</p>

[![PHP Version][php-shield]][php-url]
[![Laravel Version][laravel-shield]][laravel-url]
[![Filament Version][filament-shield]][filament-url]
[![MIT License][license-shield]][license-url]

## Tentang Aplikasi

Sistem Inventaris BMN adalah aplikasi yang dirancang untuk memudahkan pengelolaan aset dan barang milik negara. Aplikasi ini mencakup fitur-fitur seperti:

*   **Manajemen Aset:** Menambah, mengubah, dan menghapus data aset.
*   **Manajemen Ruangan:** Mengelola daftar ruangan tempat aset disimpan.
*   **Peminjaman Aset:** Melacak peminjaman dan pengembalian aset.
*   **Pemeliharaan Aset:** Mencatat riwayat pemeliharaan aset.
*   **Laporan:** Menghasilkan laporan aset, peminjaman, dan usulan penghapusan.

## Dibangun Dengan

Aplikasi ini dibangun dengan menggunakan teknologi-teknologi berikut:

*   [Laravel](https://laravel.com/)
*   [Filament](https://filamentphp.com/)
*   [PHP](https://www.php.net/)
*   [MySQL](https://www.mysql.com/)

## Memulai

Untuk menjalankan aplikasi ini secara lokal, ikuti langkah-langkah berikut.

### Prasyarat

Pastikan Anda telah menginstal perangkat lunak berikut:

*   PHP >= 8.2
*   Composer
*   Node.js
*   NPM

### Instalasi

1.  Clone repositori
    ```sh
    git clone https://github.com/aryadians/inventaris-bmn.git
    ```
2.  Masuk ke direktori proyek
    ```sh
    cd inventaris-bmn
    ```
3.  Instal dependensi PHP
    ```sh
    composer install
    ```
4.  Instal dependensi JavaScript
    ```sh
    npm install
    ```
5.  Salin file `.env.example` menjadi `.env`
    ```sh
    cp .env.example .env
    ```
6.  Buat kunci aplikasi
    ```sh
    php artisan key:generate
    ```
7.  Jalankan migrasi database
    ```sh
    php artisan migrate
    ```
8.  Jalankan server pengembangan
    ```sh
    php artisan serve
    ```

## Lisensi

Didistribusikan di bawah Lisensi MIT. Lihat `LICENSE` untuk informasi lebih lanjut.

## Kontak

Arya Diansyah - [@aryadiansyah_](https://twitter.com/aryadiansyah_) - aryadiansyah86@gmail.com

Project Link: [https://github.com/aryadians/inventaris-bmn](https://github.com/aryadians/inventaris-bmn)

[php-shield]: https://img.shields.io/badge/PHP-8.2%2B-blue?style=for-the-badge
[php-url]: https://www.php.net/
[laravel-shield]: https://img.shields.io/badge/Laravel-12.x-orange?style=for-the-badge
[laravel-url]: https://laravel.com/
[filament-shield]: https://img.shields.io/badge/Filament-3.2-orange?style=for-the-badge
[filament-url]: https://filamentphp.com/
[license-shield]: https://img.shields.io/github/license/aryadians/inventaris-bmn?style=for-the-badge
[license-url]: https://github.com/aryadians/inventaris-bmn/blob/master/LICENSE