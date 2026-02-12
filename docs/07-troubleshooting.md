# 7. Panduan Troubleshooting

## 7.1 Peta Tidak Muncul
Gejala:
- Area peta kosong atau muncul peringatan API key.

Penyebab umum:
- `MAPS_API_KEY` belum terpasang.
- Maps JavaScript API belum aktif.
- Referrer restriction key tidak mengizinkan domain.

Solusi:
1. Verifikasi `MAPS_API_KEY` pada environment server.
2. Aktifkan Maps JavaScript API di Google Cloud.
3. Tambahkan referrer `http://localhost/*` dan domain hosting.
4. Lakukan hard refresh (`Ctrl+F5`).

## 7.2 Pesan "Geocoding ditolak"
Gejala:
- Hasil alamat menampilkan pesan geocoding ditolak.

Penyebab umum:
- Geocoding API belum aktif.
- API restriction key terlalu ketat.

Solusi:
1. Aktifkan Geocoding API di project yang sama.
2. Pastikan key diizinkan untuk layanan geocoding.
3. Verifikasi fallback endpoint `reverse_geocode.php` dapat diakses.

## 7.3 Alamat Tetap Tidak Muncul
Gejala:
- Baris `Alamat:` muncul tapi berisi not found/error.

Penyebab umum:
- Lokasi titik tidak memiliki data geocoding.
- Kuota/limit provider geocoding tercapai.
- Outbound request server ke Nominatim diblokir hosting.

Solusi:
1. Geser marker beberapa meter ke titik jalan terdekat.
2. Uji endpoint fallback langsung:
   - `/reverse_geocode.php?lat=...&lng=...`
3. Periksa firewall hosting untuk outbound HTTPS.

## 7.4 URL Masih Menampilkan `index.php`
Penyebab:
- `.htaccess` tidak diproses oleh Apache.

Solusi:
1. Aktifkan `mod_rewrite`.
2. Set `AllowOverride All` di VirtualHost.
3. Restart Apache.

## 7.5 Redirect Loop
Solusi:
1. Pastikan hanya satu aturan canonical URL di `.htaccess`.
2. Cek rule rewrite tambahan pada level hosting panel.
3. Uji dengan `curl -I` untuk melihat urutan redirect.

## 7.6 Error 405 pada Endpoint
Gejala:
- Endpoint mengembalikan `Method Not Allowed`.

Solusi:
- Gunakan method sesuai endpoint:
  - `GET` untuk `reverse_geocode.php`.
  - `POST` untuk `simpan.php` dan `hapus.php`.

## 7.7 CSRF Token Tidak Valid
Solusi:
1. Refresh halaman, kirim ulang form.
2. Pastikan cookie session tidak diblokir browser.
3. Pastikan form membawa nilai `csrf_token` terbaru.

## 7.8 Koneksi Database Gagal
Gejala:
- Pesan gagal simpan/hapus data atau error PDO.

Solusi:
1. Verifikasi `BEDADUNG_DB_*` pada environment.
2. Pastikan database dan tabel `points` sudah dibuat.
3. Pastikan ekstensi `pdo_mysql` aktif.

## 7.9 Tombol Feedback Tidak Membuka Email Client
Penyebab:
- Default email client belum diset.

Solusi:
1. Set default email client di OS.
2. Uji di browser lain.
3. Gunakan alamat fallback email yang ditampilkan UI.
