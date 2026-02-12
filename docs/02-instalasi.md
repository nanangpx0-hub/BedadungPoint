# 2. Panduan Instalasi

## 2.1 Persyaratan Sistem
### Minimum
- OS: Windows 10+/Linux/macOS
- Web server: Apache 2.4+
- PHP: 7.4+ (disarankan 8.2+)
- Database: MySQL 5.7+/MariaDB 10+
- Browser modern: Chrome, Firefox, Edge, Safari

### Dependensi Eksternal
- Google Maps JavaScript API
- Google Geocoding service (via Maps API pada frontend)

## 2.2 Struktur Berkas Penting
- `index.php` (UI utama)
- `script.js` (konversi, map interaction, reverse geocode)
- `database.php` (koneksi DB, helper keamanan, konstanta API key)
- `simpan.php`, `hapus.php` (endpoint legacy data titik)
- `points.sql` (skema tabel legacy)
- `.htaccess` (clean URL tanpa `index.php`)

## 2.3 Setup Development (Localhost)
Contoh untuk Laragon (Windows):

1. Clone/salin project ke:
   - `C:\laragon\www\bedadung`
2. Pastikan Apache & MySQL berjalan.
3. Buat database legacy (opsional, jika fitur simpan/hapus dipakai):
   ```powershell
   mysql -u root -e "CREATE DATABASE IF NOT EXISTS db_bedadung CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   Get-Content points.sql | mysql -u root db_bedadung
   ```
4. Set API key:
   - Opsi A (direkomendasikan): env var
     ```powershell
     setx MAPS_API_KEY "API_KEY_ANDA"
     ```
   - Opsi B: fallback lokal di `database.php` (`MAPS_API_KEY_LOCAL`)
5. Restart Apache/Laragon.
6. Akses aplikasi:
   - `http://localhost/bedadung/`

## 2.4 Setup Staging
Karakteristik staging:
- Konfigurasi mendekati production.
- Database terpisah dari production.
- API key Google dibatasi untuk domain staging.

Langkah:
1. Deploy source code ke server staging.
2. Set environment variable:
   - `BEDADUNG_DB_HOST`
   - `BEDADUNG_DB_PORT`
   - `BEDADUNG_DB_PASS`
   - `MAPS_API_KEY`
3. Aktifkan rewrite (`AllowOverride All`, `mod_rewrite`).
4. Verifikasi:
   - URL tanpa `index.php`
   - Peta tampil
   - Reverse geocoding berjalan

## 2.5 Setup Production
1. Upload source ke web root/subfolder production.
2. Konfigurasi environment variable production.
3. Pastikan HTTPS aktif.
4. Restrict `MAPS_API_KEY` berdasarkan domain production.
5. Pastikan `display_errors=Off` dan logging aktif.
6. Jalankan smoke test:
   - Halaman utama (200)
   - URL canonical tanpa `index.php`
   - Konversi Decimal -> DMS
   - Konversi DMS -> Decimal
   - Alamat otomatis muncul

## 2.6 Verifikasi Instalasi
Checklist cepat:
- `index.php` bisa diakses.
- Peta interaktif muncul.
- Marker bisa digeser dan koordinat berubah real-time.
- Konversi dua arah berfungsi.
- Hasil menampilkan `Alamat: ...`.
- Tidak ada 404/redirect loop untuk URL bersih.
