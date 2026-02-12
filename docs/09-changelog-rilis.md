# 9. Changelog dan Versi Rilis

Dokumen ini mengikuti gaya Keep a Changelog dan Semantic Versioning.

## [1.1.1] - 2026-02-12
### Changed
- Pembaruan dokumentasi teknis menyeluruh pada `README.md` dan `docs/01-14`.
- Penyelarasan dokumentasi endpoint dengan implementasi terbaru.
- Penambahan panduan deployment mode localhost/staging/production.

### Security
- Menghapus kredensial hardcoded pada `database.php`.
- Menambah `.env.example` sebagai template konfigurasi aman.
- Memperkuat `.gitignore` untuk mencegah file secret ikut ter-push.
- Sanitasi `db_bedadung.sql` agar hanya berisi schema (tanpa data record).

### Docs
- Menambah detail arsitektur, flowchart, ERD, API, troubleshooting, dan dependensi.

## [1.1.0] - 2026-02-12
### Added
- Paket dokumentasi teknis lengkap (`README.md` + `docs/`).
- Panduan deployment, struktur database, dan daftar dependensi.

## [1.0.0] - 2026-02-12
### Added
- Konversi koordinat Decimal <-> DMS.
- Integrasi peta interaktif.
- Reverse geocoding alamat.
- URL rewrite tanpa `index.php`.
