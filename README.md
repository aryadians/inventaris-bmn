
<p align="center">
  <a href="https://github.com/aryadians/inventaris-bmn">
    <img src="public/images/logo.png" alt="Logo" width="80" height="80">
  </a>

  <h3 align="center">Sistem Inventaris BMN</h3>

  <p align="center">
    Aplikasi manajemen inventaris Barang Milik Negara (BMN) berbasis web yang dibangun menggunakan Laravel dan Filament.
    <br />
    <a href="https://github.com/aryadians/inventaris-bmn"><strong>Explore the docs »</strong></a>
    <br />
    <br />
    <a href="https://github.com/aryadians/inventaris-bmn/issues">Report Bug</a>
    ·
    <a href="https://github.com/aryadians/inventaris-bmn/issues">Request Feature</a>
  </p>
</p>

<div align="center">

[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-FAA04B?style=for-the-badge&logo=filament&logoColor=white)](https://filamentphp.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)
[![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)

</div>

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

Proyek ini dilisensikan di bawah Lisensi MIT.

```
MIT License

Copyright (c) 2026 Arya Dian

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

## Kontak

Arya Dian - [@aransptr_](https://instagram.com/aransptr_) - aryadian003@gmail.com

Project Link: [https://github.com/aryadians/inventaris-bmn](https://github.com/aryadians/inventaris-bmn)

[php-shield]: https://img.shields.io/badge/PHP-8.2%2B-blue?style=for-the-badge
[php-url]: https://www.php.net/
[laravel-shield]: https://img.shields.io/badge/Laravel-12.x-orange?style=for-the-badge
[laravel-url]: https://laravel.com/
[filament-shield]: https://img.shields.io/badge/Filament-3.2-orange?style=for-the-badge
[filament-url]: https://filamentphp.com/
