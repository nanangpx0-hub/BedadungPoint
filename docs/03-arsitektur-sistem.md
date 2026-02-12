# 3. Arsitektur Sistem

## 3.1 Arsitektur Tingkat Tinggi
Aplikasi menggunakan pola server-rendered PHP dengan JavaScript client-side.

```mermaid
flowchart LR
    U[User Browser] --> W[Apache + PHP]
    W --> V[index.php]
    V --> J[script.js]
    J --> G[Google Maps JS API]
    J --> R[reverse_geocode.php]
    R --> N[Nominatim API]
    W --> D[(MySQL/MariaDB)]
    D <-->|legacy endpoint| S[simpan.php / hapus.php]
```

## 3.2 Alur Konversi dan Alamat
```mermaid
flowchart TD
    A[Input Decimal atau DMS] --> B[Validasi Format]
    B -->|valid| C[Konversi Koordinat]
    C --> D[Perbarui Marker dan Legenda Peta]
    D --> E[Ambil Alamat]
    E --> E1[Google Geocoder / Places]
    E --> E2[Fallback reverse_geocode.php]
    E2 --> E3[Nominatim]
    E1 --> F[Tampilkan Hasil]
    E3 --> F[Tampilkan Hasil]
    B -->|tidak valid| X[Tampilkan Pesan Error]
```

## 3.3 Komponen Utama
- `index.php`
  - Render UI dan injeksi konfigurasi JavaScript.
  - Menampilkan area peta, input konversi, dan hasil.
- `script.js`
  - Parser/validator input.
  - Konversi Decimal <-> DMS.
  - Inisialisasi peta, marker drag-and-drop, klik peta.
  - Integrasi reverse geocoding.
- `database.php`
  - Deteksi mode lingkungan (`LOCALHOST`/`HOSTING`).
  - Konfigurasi DB/API dari environment variable.
  - Helper CSRF dan security headers.
- `reverse_geocode.php`
  - Endpoint fallback alamat server-side.
  - Validasi input `lat/lng` dan response JSON.
- `simpan.php`, `hapus.php` (legacy)
  - Endpoint mutasi data titik yang dilindungi CSRF.

## 3.4 ER Diagram
```mermaid
erDiagram
    POINTS {
        BIGINT_UNSIGNED id PK
        VARCHAR nama
        DECIMAL lat
        DECIMAL lng
        TIMESTAMP waktu
    }
```

## 3.5 Keamanan Arsitektur
- CSRF token untuk endpoint POST.
- Validasi input di frontend dan backend.
- Prepared statement PDO (anti SQL injection).
- Security headers (`X-Frame-Options`, `X-Content-Type-Options`, dll).
