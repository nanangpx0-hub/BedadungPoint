# 2. Panduan Instalasi

## 2.1 Persyaratan Sistem
- OS: Windows, Linux, atau macOS.
- Apache: 2.4+ (`mod_rewrite` aktif).
- PHP: 8.1+ (minimum 7.4).
- Ekstensi PHP: `pdo_mysql`, `curl` (opsional tapi direkomendasikan).
- MySQL/MariaDB: MySQL 5.7+ atau MariaDB 10+.
- Browser: Chrome, Firefox, Edge, Safari versi modern.

## 2.2 Berkas Penting
- `index.php`: halaman utama.
- `script.js`: logika konversi dan interaksi peta.
- `database.php`: konfigurasi env, koneksi PDO, helper keamanan.
- `reverse_geocode.php`: fallback reverse geocoding server-side.
- `simpan.php`, `hapus.php`: endpoint legacy mutasi data titik.
- `.htaccess`: URL rewrite tanpa `index.php`.
- `.env.example`: template variabel environment.

## 2.3 Setup Development (Localhost)
1. Clone/salin source code ke web root.
2. Jalankan Apache dan MySQL.
3. Buat database lokal (opsional untuk endpoint legacy):
   ```bash
   mysql -u root -e "CREATE DATABASE IF NOT EXISTS db_bedadung CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   mysql -u root db_bedadung < points.sql
   ```
4. Set environment variable (Windows PowerShell contoh):
   ```powershell
   setx MAPS_API_KEY "YOUR_GOOGLE_MAPS_KEY"
   setx BEDADUNG_DB_HOST "localhost"
   setx BEDADUNG_DB_PORT "3306"
   setx BEDADUNG_DB_NAME "db_bedadung"
   setx BEDADUNG_DB_USER "root"
   setx BEDADUNG_DB_PASS ""
   ```
5. Restart Apache/Laragon.
6. Akses `http://localhost/bedadung/`.

## 2.4 Setup Staging
1. Deploy source code ke server staging.
2. Set environment variable staging (`MAPS_API_KEY`, `BEDADUNG_DB_*`).
3. Aktifkan `AllowOverride All` dan `mod_rewrite`.
4. Verifikasi fitur utama:
- Halaman terbuka tanpa error.
- URL bersih tanpa `index.php`.
- Konversi koordinat berjalan.
- Reverse geocoding menghasilkan alamat.

## 2.5 Setup Production
1. Deploy source code ke hosting production.
2. Konfigurasi environment variable production melalui panel hosting/VHost.
3. Pastikan HTTPS aktif.
4. Batasi API key dengan HTTP referrer domain production.
5. Nonaktifkan error display (`display_errors=Off`) dan aktifkan logging server.

## 2.6 Verifikasi Instalasi
- `index.php` dapat diakses dari URL utama.
- Peta muncul dan marker bisa dipindahkan.
- Konversi Decimal <-> DMS berhasil.
- Baris `Alamat:` muncul setelah konversi.
- Tidak ada redirect loop/404 pada URL bersih.
