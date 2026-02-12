# BedadungPoint

Dokumentasi teknis resmi aplikasi BedadungPoint (BPS Kabupaten Jember).

## Ringkasan
BedadungPoint adalah aplikasi web berbasis PHP untuk:
- Konversi koordinat Decimal ke DMS dan DMS ke Decimal.
- Visualisasi titik pada peta interaktif.
- Reverse geocoding alamat dengan fallback berlapis.
- Pengelolaan data titik (legacy endpoint `simpan.php` dan `hapus.php`).
- Kanal masukan pengguna melalui email feedback.

## Daftar Isi
1. [Pengenalan Proyek](docs/01-pengenalan-proyek.md)
2. [Panduan Instalasi](docs/02-instalasi.md)
3. [Arsitektur Sistem](docs/03-arsitektur-sistem.md)
4. [Dokumentasi API](docs/04-dokumentasi-api.md)
5. [Panduan Penggunaan Fitur](docs/05-panduan-penggunaan.md)
6. [Konfigurasi dan Environment Variables](docs/06-konfigurasi-env.md)
7. [Troubleshooting](docs/07-troubleshooting.md)
8. [Kontribusi dan Standar Pengembangan](docs/08-kontribusi-standar.md)
9. [Changelog dan Rilis](docs/09-changelog-rilis.md)
10. [Lisensi dan Informasi Legal](docs/10-lisensi-legal.md)
11. [Implementasi Fitur Feedback](docs/11-implementasi-feedback.md)
12. [Panduan Deployment](docs/12-panduan-deployment.md)
13. [Struktur Database](docs/13-struktur-database.md)
14. [Daftar Dependensi](docs/14-daftar-dependensi.md)

## Struktur Proyek
```text
docs/
  01-pengenalan-proyek.md
  02-instalasi.md
  03-arsitektur-sistem.md
  04-dokumentasi-api.md
  05-panduan-penggunaan.md
  06-konfigurasi-env.md
  07-troubleshooting.md
  08-kontribusi-standar.md
  09-changelog-rilis.md
  10-lisensi-legal.md
  11-implementasi-feedback.md
  12-panduan-deployment.md
  13-struktur-database.md
  14-daftar-dependensi.md
  assets/
    screenshots/
```

## Quick Start
1. Siapkan Apache, PHP, dan MySQL/MariaDB sesuai [Panduan Instalasi](docs/02-instalasi.md).
2. Salin nilai contoh dari `.env.example` ke environment server.
3. Set minimal `MAPS_API_KEY` dan variabel database.
4. Import schema `points.sql` (jika fitur legacy simpan/hapus digunakan).
5. Buka aplikasi di `http://localhost/bedadung/`.

## Keamanan Konfigurasi
- Jangan commit API key, password, atau kredensial database.
- Gunakan environment variable (`MAPS_API_KEY`, `BEDADUNG_DB_*`).
- Gunakan `.gitignore` untuk mencegah `.env` dan file sensitif lain ikut ter-push.

## Dokumen Tambahan Root
- `CHANGELOG.md` mengarah ke changelog utama di `docs/09-changelog-rilis.md`.
- `CONTRIBUTING.md` mengarah ke pedoman kontribusi di `docs/08-kontribusi-standar.md`.
- `LICENSE.md` mengarah ke ketentuan legal di `docs/10-lisensi-legal.md`.
