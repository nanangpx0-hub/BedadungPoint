# BedadungPoint

Aplikasi web untuk konversi koordinat dua arah antara format Decimal dan DMS (Derajat-Menit-Detik), dilengkapi informasi alamat otomatis dari hasil koordinat.

Tagline: **"BedadungPoint - Presisi di Bumi Pandalungan"**

## Fitur Utama
- Peta interaktif di bagian atas konverter (Google Maps).
- Klik pada peta untuk memilih titik koordinat secara visual.
- Marker draggable (drag-and-drop) untuk mengubah koordinat.
- Zoom control, pilihan mode peta (`Roadmap`, `Satellite`, `Hybrid`, `Terrain`), dan Street View.
- Legenda koordinat real-time (lat, lng, zoom, status) saat marker dipindah/klik peta.
- Konversi `Decimal -> DMS` dengan penunjuk arah `N/S/E/W`.
- Konversi `DMS -> Decimal`.
- Validasi format input dan rentang koordinat (lat/lng).
- Hasil konversi ditampilkan jelas dalam blok teks.
- Informasi alamat otomatis di bawah hasil konversi (reverse geocoding Google Maps).
- Tombol `Copy Hasil` ke clipboard.

## Struktur File
- `index.php`: UI utama konverter.
- `script.js`: logika konversi, validasi, copy, dan pencarian alamat.
- `database.php`: konfigurasi environment + `MAPS_API_KEY`.
- `tests/manual_test_checklist.md`: checklist uji manual terbaru.

Catatan:
- File lama `simpan.php`, `hapus.php`, `points.sql`, dan `tests/functional_db_test.php` masih tersedia sebagai modul legacy database.

## Prasyarat
- PHP 7.4+ (disarankan PHP 8.x).
- Web server (Apache/Nginx/Laragon/XAMPP).
- Google Maps JavaScript API key aktif (untuk fitur alamat).

## Setup Lokal (Localhost)
1. Pastikan project berada di `C:\laragon\www\bedadung`.
2. Set API key:
   - via environment variable:
     ```powershell
     setx MAPS_API_KEY "API_KEY_ANDA"
     ```
   - atau isi fallback di `database.php`:
     - `MAPS_API_KEY_LOCAL`
3. Restart Apache/Laragon.
4. Akses:
   - `http://localhost/bedadung/index.php`

## Format Input

### Decimal
- Format: `lat, lng`
- Contoh: `-8.1050786039, 113.7262102605`
- Wajib desimal bertitik (`.`), bukan koma desimal.

### DMS
- Format: `DD°MM'SS.S"[N|S] DDD°MM'SS.S"[E|W]`
- Contoh: `8°06'18.3"S 113°43'34.4"E`

## Contoh Hasil
```text
Mode: Decimal → DMS
Input Decimal: -8.1050786039, 113.7262102605
Hasil DMS: 8°06'18.3"S 113°43'34.4"E
Alamat: VPVG+R85 Arjasa, Kabupaten Jember, Jawa Timur
```

## Troubleshooting
- Peta tidak muncul:
  - cek `MAPS_API_KEY` valid
  - pastikan `Maps JavaScript API` aktif
  - pastikan billing Google Cloud aktif
  - cek pembatasan referrer mengizinkan `localhost`
- Alamat tidak muncul:
  - cek `MAPS_API_KEY` valid
  - pastikan `Maps JavaScript API` aktif
  - pastikan billing Google Cloud aktif
  - cek pembatasan referrer mengizinkan `localhost`
- Jika hanya konversi yang muncul tanpa alamat:
  - artinya konversi berjalan, tetapi reverse geocoding belum aktif.
