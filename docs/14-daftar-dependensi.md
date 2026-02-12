# 14. Daftar Dependensi

## 14.1 Dependensi Runtime
| Komponen | Versi Referensi | Keterangan |
|---|---|---|
| PHP | 8.1+ (minimum 7.4) | Runtime backend.
| Apache | 2.4+ | Web server dan URL rewrite.
| MySQL/MariaDB | MySQL 5.7+ / MariaDB 10+ | Penyimpanan data legacy.
| Google Maps JavaScript API | v3 (latest channel) | Peta interaktif, marker, Street View.
| Nominatim OpenStreetMap | Public API | Fallback reverse geocoding.

## 14.2 Ekstensi PHP
| Ekstensi | Status | Fungsi |
|---|---|---|
| `pdo_mysql` | Wajib (jika endpoint DB dipakai) | Koneksi database aman.
| `curl` | Direkomendasikan | HTTP request fallback geocoding.

## 14.3 Dependensi Frontend
- Vanilla JavaScript (tanpa framework eksternal).
- HTML/CSS native.

## 14.4 Dependensi Dokumentasi
- Markdown.
- Mermaid (diagram flow dan ERD pada viewer yang mendukung).

## 14.5 Cara Verifikasi Versi
```bash
php -v
apache2 -v
mysql --version
```

## 14.6 Catatan Lisensi Dependensi
- Google Maps Platform mengikuti Terms of Service Google.
- Data OpenStreetMap/Nominatim tunduk pada lisensi ODbL dan kebijakan penggunaan masing-masing endpoint.
