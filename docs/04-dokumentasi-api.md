# 4. Dokumentasi API

## 4.1 Ringkasan Endpoint
- `GET /` atau `GET /index.php`: halaman utama aplikasi.
- `POST /simpan.php`: simpan titik koordinat (legacy).
- `POST /hapus.php`: hapus titik koordinat (legacy).
- `GET /reverse_geocode.php`: fallback reverse geocoding.

## 4.2 Mekanisme Keamanan
- Tidak menggunakan token auth/JWT.
- Endpoint POST memakai CSRF (`csrf_token`).
- Semua input diverifikasi server-side.

## 4.3 Detail Endpoint

### 4.3.1 `GET /`
Deskripsi: menampilkan antarmuka aplikasi.

Request:
- Method: `GET`
- Query: tidak ada.

Response:
- `200 OK` dengan HTML.
- `301` dari `/index.php` ke `/` jika rewrite aktif.

Contoh:
```bash
curl -I http://localhost/bedadung/
```

### 4.3.2 `POST /simpan.php` (Legacy)
Deskripsi: menyimpan titik ke tabel `points`.

Request body (form-urlencoded):
- `csrf_token`: string wajib.
- `nama`: string wajib, max 120.
- `lat`: float wajib, rentang `-90..90`.
- `lng`: float wajib, rentang `-180..180`.

Response:
- Redirect `302` ke `index.php` dengan flash message session.
- `405` jika bukan method POST.

Contoh:
```bash
curl -X POST http://localhost/bedadung/simpan.php \
  -d "csrf_token=TOKEN" \
  -d "nama=Titik Uji" \
  -d "lat=-8.1704" \
  -d "lng=113.7022"
```

### 4.3.3 `POST /hapus.php` (Legacy)
Deskripsi: menghapus titik berdasarkan `id`.

Request body (form-urlencoded):
- `csrf_token`: string wajib.
- `id`: integer positif wajib.

Response:
- Redirect `302` ke `index.php` dengan flash message.
- `405` jika bukan method POST.

Contoh:
```bash
curl -X POST http://localhost/bedadung/hapus.php \
  -d "csrf_token=TOKEN" \
  -d "id=1"
```

### 4.3.4 `GET /reverse_geocode.php`
Deskripsi: endpoint fallback alamat berbasis Nominatim (server-side).

Request query:
- `lat`: float wajib.
- `lng`: float wajib.

Response sukses (`200`):
```json
{
  "ok": true,
  "address": "Kimia Farma, Jalan Gajah Mada, Gebang, Jember, Jawa Timur, 68131, Indonesia",
  "source": "nominatim"
}
```

Response error validasi (`400`):
```json
{
  "ok": false,
  "message": "Parameter lat/lng tidak valid. Gunakan format desimal bertitik."
}
```

Response method salah (`405`):
```json
{
  "ok": false,
  "message": "Method tidak diizinkan. Gunakan GET."
}
```

Response upstream gagal (`502`):
```json
{
  "ok": false,
  "message": "Layanan reverse geocoding fallback sedang tidak tersedia.",
  "status": 429
}
```

Contoh:
```bash
curl "http://localhost/bedadung/reverse_geocode.php?lat=-8.17374903394212&lng=113.6894298841495"
```

## 4.4 Kode Status Umum
- `200`: sukses.
- `301`: redirect canonical URL.
- `302`: redirect setelah endpoint POST legacy.
- `400`: input tidak valid.
- `405`: method tidak sesuai.
- `502`: layanan pihak ketiga fallback tidak tersedia.
