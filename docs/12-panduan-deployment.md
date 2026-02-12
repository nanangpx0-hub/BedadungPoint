# 12. Panduan Deployment

## 12.1 Strategi Deployment
Dokumen ini mencakup deployment dua mode:
- Mode localhost (development).
- Mode hosting online (staging/production).

## 12.2 Checklist Pra-Deploy
- Branch source stabil.
- Tidak ada secret dalam source code.
- `.env` tidak ikut ter-push.
- `MAPS_API_KEY` dan `BEDADUNG_DB_*` siap pada environment target.

## 12.3 Deployment ke Hosting (cPanel/Shared Hosting)
1. Upload source code ke folder target (`public_html` atau subfolder app).
2. Pastikan `.htaccess` ikut ter-upload.
3. Set environment variable lewat panel hosting atau `SetEnv`.
4. Buat database dan import `points.sql` (jika endpoint legacy dipakai).
5. Uji endpoint penting dan halaman utama.

Contoh `SetEnv` di Apache:
```apache
SetEnv MAPS_API_KEY "YOUR_GOOGLE_MAPS_KEY"
SetEnv BEDADUNG_DB_HOST "localhost"
SetEnv BEDADUNG_DB_PORT "3306"
SetEnv BEDADUNG_DB_NAME "your_db_name"
SetEnv BEDADUNG_DB_USER "your_db_user"
SetEnv BEDADUNG_DB_PASS "your_db_password"
SetEnv BEDADUNG_DEBUG_ENV "false"
```

## 12.4 Deployment ke VPS (Apache)
1. Install stack web (`apache2`, `php`, `php-mysql`).
2. Clone repository ke document root.
3. Set permission owner ke user web server.
4. Aktifkan `mod_rewrite`.
5. Konfigurasi VirtualHost dengan `AllowOverride All`.

Contoh VirtualHost:
```apache
<VirtualHost *:80>
    ServerName your-domain.example
    DocumentRoot /var/www/bedadung

    <Directory /var/www/bedadung>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

## 12.5 Smoke Test Pasca Deploy
- `GET /` menampilkan aplikasi.
- URL tidak menampilkan `index.php`.
- Konversi Decimal dan DMS berjalan.
- Marker peta bisa digeser.
- Endpoint fallback `reverse_geocode.php` merespons JSON.

## 12.6 Rollback Plan
- Simpan backup source dan DB sebelum release.
- Jika release gagal, rollback ke tag/commit stabil sebelumnya.
- Verifikasi ulang endpoint utama setelah rollback.
