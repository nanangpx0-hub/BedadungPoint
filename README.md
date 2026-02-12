# BedadungPoint

Dokumentasi teknis resmi untuk aplikasi **BedadungPoint**.  
Identitas institusi: **BPS Kabupaten Jember**.

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

## Ringkasan Proyek
BedadungPoint adalah aplikasi web berbasis PHP untuk:
- Konversi koordinat dua arah: **Decimal <-> DMS**
- Visualisasi titik pada **Google Maps interaktif**
- Reverse geocoding (alamat otomatis dari koordinat)
- Pengelolaan data titik koordinat melalui endpoint backend (`simpan.php`, `hapus.php`)

## Struktur Dokumentasi
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
  assets/
    screenshots/
      map-overview.svg
      convert-decimal-dms.svg
      convert-dms-decimal.svg
      result-with-address.svg
```

## Quick Start
1. Ikuti langkah setup di [Panduan Instalasi](docs/02-instalasi.md).
2. Set `MAPS_API_KEY` (atau `MAPS_API_KEY_LOCAL`) sesuai [Konfigurasi ENV](docs/06-konfigurasi-env.md).
3. Buka `http://localhost/bedadung/`.

## Catatan
- Dokumen ini ditulis dalam Bahasa Indonesia dan dipecah menjadi modul agar mudah dipelihara.
- Diagram menggunakan format **Mermaid** (didukung oleh GitHub dan banyak viewer Markdown modern).
