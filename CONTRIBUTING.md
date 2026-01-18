# Panduan Kontribusi (Contributing Guide)

Terima kasih telah tertarik untuk berkontribusi pada **SIMA Lapas Jombang**! Kami sangat terbuka terhadap bantuan dalam bentuk perbaikan bug, penambahan fitur, maupun penyempurnaan dokumentasi.

## Cara Berkontribusi

### 1. Melaporkan Bug atau Saran Fitur
Jika Anda menemukan masalah atau memiliki ide fitur baru, silakan buka [New Issue](https://github.com/aryadians/inventaris-bmn/issues). Pastikan untuk memberikan deskripsi yang jelas dan langkah-langkah untuk mereproduksi bug tersebut.

### 2. Melakukan Perubahan Kode (Pull Request)
Jika Anda ingin memperbaiki kode secara langsung, ikuti langkah-langkah berikut:

1. **Fork** repositori ini ke akun GitHub Anda.
2. Buat **branch** baru untuk fitur atau perbaikan Anda (contoh: `git checkout -b fitur-baru-keren`).
3. Lakukan perubahan kode dan pastikan mengikuti standar coding Laravel.
4. **Commit** perubahan Anda dengan pesan yang deskriptif (`git commit -m 'Menambah fitur filter aset'`).
5. **Push** ke branch Anda (`git push origin fitur-baru-keren`).
6. Buka **Pull Request** di repositori utama ini.

## Standar Coding

Untuk menjaga kualitas kode, harap perhatikan hal berikut:
- Ikuti standar **PSR-12** untuk penulisan PHP.
- Gunakan fitur **FilamentPHP v3** dengan benar (Resource, Relation Manager, Widgets).
- Pastikan setiap fitur baru tidak merusak sistem grid dashboard yang sudah ada (6-column layout).
- Selalu jalankan `php artisan filament:optimize` setelah melakukan perubahan pada widget.

## Lisensi
Dengan berkontribusi pada proyek ini, Anda setuju bahwa kontribusi Anda akan dilisensikan di bawah **Lisensi MIT**.
