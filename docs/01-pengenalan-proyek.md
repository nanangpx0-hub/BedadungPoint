# 1. Pengenalan Proyek

## 1.1 Latar Belakang
Kebutuhan pengolahan koordinat di lingkungan kerja statistik dan pemetaan wilayah sering melibatkan lebih dari satu format data spasial. Tim lapangan, operator GIS, dan pengguna dashboard dapat menggunakan format:
- Decimal (contoh: `-8.1050786039, 113.7262102605`)
- DMS (contoh: `8°06'18.3"S 113°43'34.4"E`)

Perbedaan format tersebut berpotensi menimbulkan kesalahan input, inkonsistensi data, dan keterlambatan validasi lokasi. BedadungPoint dibuat untuk menyederhanakan proses konversi sekaligus memverifikasi titik secara visual pada peta.

## 1.2 Tujuan
Tujuan utama BedadungPoint:
1. Menyediakan konversi koordinat dua arah yang akurat antara Decimal dan DMS.
2. Menyediakan peta interaktif untuk verifikasi visual titik koordinat.
3. Menyediakan hasil alamat otomatis (reverse geocoding) untuk mempercepat validasi lapangan.
4. Menyediakan endpoint backend untuk penyimpanan/hapus data titik (modul legacy yang masih dapat dipakai).

## 1.3 Ruang Lingkup
### In Scope
- Frontend interaktif berbasis HTML/CSS/JavaScript.
- Backend PHP untuk routing, keamanan dasar, dan endpoint data titik.
- Integrasi Google Maps JavaScript API + Geocoder API.
- Dokumentasi teknis, instalasi, troubleshooting, dan standar kontribusi.

### Out of Scope
- Manajemen user account/role berbasis login.
- Integrasi SSO.
- Pipeline CI/CD otomatis penuh.
- Audit keamanan formal pihak ketiga.

## 1.4 Profil Pengguna
- Operator data spasial.
- Tim statistik wilayah.
- Pengembang internal yang memelihara aplikasi.
- Admin infrastruktur (deployment lokal/hosting).

## 1.5 Nilai Tambah
- Mengurangi error konversi manual.
- Mempercepat validasi lokasi berbasis peta.
- Menyatukan proses konversi, visualisasi, dan verifikasi alamat dalam satu antarmuka.
