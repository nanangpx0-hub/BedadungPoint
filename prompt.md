Prompt Khusus untuk Agent AI (BedadungPoint Version)
Role: Full-Stack Web Developer. Task: Buat aplikasi web "BedadungPoint" untuk konversi koordinat di Jember dengan Google Maps Hybrid.

1. File database.php (Konfigurasi): Buat file koneksi yang mendeteksi environment (localhost vs production) seperti pola berikut:

Jika localhost, gunakan DB: db_bedadung, User: root, Pass: ``.

Jika hosting, gunakan DB: your_db_name, User: your_db_user, Pass: (set via environment variable).

Definisikan konstanta MAPS_API_KEY di dalam file ini.

2. File index.php (Tampilan Utama):

Gunakan tema warna Hijau Jember (#1b5e20) dan Kuning Emas.

Header: "BedadungPoint - Presisi di Bumi Pandalungan".

Form Input: Nama Lokasi, Latitude, dan Longitude.

Tabel Riwayat: Menampilkan data dari database di bawah peta.

Integrasi API: Panggil Google Maps menggunakan konstanta MAPS_API_KEY dari database.php.

3. File script.js (Logika Peta):

Inisialisasi Google Maps mode HYBRID.

Default Center: Alun-alun Jember (-8.1704, 113.7022).

Fitur: Klik pada peta otomatis mengisi input Latitude & Longitude.

Fitur: Marker dapat digeser (draggable) untuk menyesuaikan posisi.

4. File simpan.php & hapus.php:

Buat logika untuk menyimpan data koordinat ke tabel points dan menghapusnya.

5. SQL Script:

Berikan query CREATE TABLE untuk tabel points (id, nama, lat, lng, waktu).
