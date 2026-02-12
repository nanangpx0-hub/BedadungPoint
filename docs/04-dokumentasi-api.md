# 4. Dokumentasi API

## 4.1 Ringkasan
Aplikasi ini berbasis server-rendered PHP dengan endpoint:
- `GET /` -> halaman utama (`index.php`)
- `POST /simpan.php` -> simpan titik (legacy)
- `POST /hapus.php` -> hapus titik (legacy)

## 4.2 Authentication & Security
Tidak ada login token/JWT. Mekanisme keamanan:
- **CSRF token** wajib untuk endpoint POST mutasi data.
- Validasi server-side untuk semua input.
- Prepared statements PDO.
- Security headers pada response.

## 4.3 Endpoint Detail

### 4.3.1 `GET /` (atau `/index.php`)
Menampilkan halaman aplikasi konversi.

#### Request
- Method: `GET`
- Content-Type: `text/html`

#### Response
- `200 OK` halaman HTML.
- Redirect canonical dari `/index.php` ke `/` jika `.htaccess` aktif.

#### Contoh
```bash
curl -I http://localhost/bedadung/
```

---

### 4.3.2 `POST /simpan.php` (Legacy)
Menyimpan data titik ke tabel `points`.

#### Request Body (form-urlencoded)
- `csrf_token` (string, wajib)
- `nama` (string, wajib, max 120)
- `lat` (float, wajib, -90..90)
- `lng` (float, wajib, -180..180)

#### Response
- Redirect ke `/` dengan flash message:
  - sukses simpan
  - error validasi
  - error database

#### Error Handling
- `405 Method Not Allowed` jika bukan POST.
- Redirect dengan pesan error jika CSRF invalid.

#### Contoh
```bash
curl -X POST http://localhost/bedadung/simpan.php \
  -d "csrf_token=TOKEN" \
  -d "nama=Contoh Titik" \
  -d "lat=-8.1050786" \
  -d "lng=113.7262103"
```

---

### 4.3.3 `POST /hapus.php` (Legacy)
Menghapus data titik berdasarkan ID.

#### Request Body (form-urlencoded)
- `csrf_token` (string, wajib)
- `id` (integer positif, wajib)

#### Response
- Redirect ke `/` dengan flash status.

#### Error Handling
- `405 Method Not Allowed` jika bukan POST.
- Redirect error jika CSRF invalid atau ID tidak valid.

#### Contoh
```bash
curl -X POST http://localhost/bedadung/hapus.php \
  -d "csrf_token=TOKEN" \
  -d "id=1"
```

## 4.4 Kode Error yang Relevan
- `200 OK`: request berhasil.
- `301 Moved Permanently`: canonical redirect (hapus `index.php`).
- `405 Method Not Allowed`: method tidak sesuai endpoint.
- `500 Internal Server Error`: error runtime server tak tertangani.

## 4.5 Integrasi API Eksternal
### Google Maps JavaScript API
Digunakan untuk:
- render peta
- marker interaktif
- street view control
- geocoder (alamat otomatis)

Kebutuhan:
- `MAPS_API_KEY` valid.
- API aktif di Google Cloud.
- billing aktif.
- referrer restriction sesuai domain.
