# 1. Pengenalan Proyek

## 1.1 Latar Belakang
Tim operasional lapangan dan pengolahan data spasial sering menggunakan format koordinat yang berbeda. Perbedaan format ini meningkatkan risiko salah input, data tidak konsisten, dan proses validasi titik menjadi lebih lambat.

BedadungPoint dibuat untuk menyatukan proses:
- Konversi koordinat dua arah.
- Validasi visual di peta.
- Pembacaan alamat otomatis dari titik koordinat.

## 1.2 Tujuan
1. Menyediakan konversi koordinat Decimal <-> DMS yang akurat.
2. Menyediakan peta interaktif untuk validasi titik.
3. Menyediakan alamat lokasi melalui reverse geocoding.
4. Menjaga aplikasi ringan, mudah dirawat, dan aman untuk deployment internal/hosting.

## 1.3 Ruang Lingkup
### In Scope
- Halaman konversi koordinat berbasis web.
- Integrasi peta interaktif (Google Maps JS API).
- Reverse geocoding dengan fallback endpoint server.
- Endpoint backend legacy untuk simpan/hapus titik.
- Dokumentasi teknis lengkap.

### Out of Scope
- Autentikasi multi-user dan role permission.
- Integrasi SSO.
- Workflow approval data titik.
- CI/CD penuh berbasis pipeline terotomasi.

## 1.4 Pemangku Kepentingan
- Operator pemetaan dan statistik wilayah.
- Admin aplikasi/internal developer.
- Tim infrastruktur hosting.

## 1.5 Nilai Bisnis
- Mengurangi kesalahan konversi manual.
- Mempercepat validasi koordinat di lapangan.
- Menyediakan proses kerja yang lebih seragam antar perangkat.
