# Checklist Uji Manual BedadungPoint (Converter Only)

## 1) Uji Fungsional Inti
- Buka `index.php`, pastikan halaman tampil tanpa error.
- Pastikan panel **Input Koordinat** sudah tidak ada.
- Pastikan peta interaktif tampil di atas form konversi.
- Klik peta, pastikan marker pindah ke titik klik dan nilai decimal ikut terupdate.
- Drag marker, pastikan legenda koordinat (lat/lng) berubah secara real-time.
- Ubah zoom peta, pastikan nilai zoom di legenda ikut berubah.
- Ubah mode peta ke `Satellite` lalu kembali ke `Roadmap`, pastikan tampil normal.
- Coba Street View dari kontrol peta, pastikan dapat dibuka.
- Uji konversi Decimal ke DMS:
  - Input `-8.1050786039, 113.7262102605`
  - Klik `Decimal → DMS`
  - Pastikan hasil memuat:
    - `Mode: Decimal → DMS`
    - `Input Decimal: ...`
    - `Hasil DMS: ...`
    - `Alamat: ...`
- Uji konversi DMS ke Decimal:
  - Input `8°06'18.3"S 113°43'34.4"E`
  - Klik `DMS → Decimal`
  - Pastikan hasil memuat:
    - `Mode: DMS → Decimal`
    - `Hasil Decimal: ...`
    - `Alamat: ...`
- Klik tombol `Copy Hasil`, lalu paste ke editor teks untuk memastikan hasil tersalin.
- Scroll ke footer, klik **Kirim Feedback**:
  - Pastikan email client default terbuka.
  - Pastikan tujuan email: `nanang.pamungkas@bps.go.id`.
  - Pastikan subject otomatis: `Feedback Pengembangan Aplikasi`.
- Uji fallback: jika mailto gagal, pastikan email fallback tetap terlihat jelas dan dapat diklik.
- Uji aksesibilitas keyboard:
  - Navigasi dengan `Tab` sampai tombol **Kirim Feedback**.
  - Pastikan fokus terlihat.
  - Tekan `Enter`, pastikan mailto aktif.

## 2) Uji Validasi Error
- Coba input decimal salah format (mis. `abc, 113`), pastikan muncul pesan error.
- Coba input DMS salah format (mis. `8°70'00"S 113°41'40.5"E`), pastikan muncul pesan error.
- Coba input latitude di luar rentang (mis. `-120, 113.7`), pastikan ditolak.

## 3) Uji Alamat
- Saat konversi berhasil, pastikan baris `Alamat:` muncul di bawah hasil.
- Uji koordinat padat bangunan:
  - Input `-8.17374903394212, 113.6894298841495`
  - Klik `Decimal → DMS`
  - Pastikan `Alamat:` tetap terisi (melalui geocoder Google atau fallback endpoint), bukan `Alamat tidak ditemukan ...`.
- Jika API key Google bermasalah, pastikan fallback server tetap mencoba mengambil alamat dari layanan cadangan.

## 4) Uji Responsif dan Kompatibilitas
- Desktop (>= 1280px): peta + dua kolom input konversi tampil rapi.
- Tablet (~768px): layout menyesuaikan dan tetap terbaca.
- Mobile (~360px): peta, input, tombol, dan hasil tetap nyaman dipakai.
- Browser yang diuji:
  - Chrome terbaru
  - Firefox terbaru
  - Edge terbaru
  - Safari (jika tersedia)

## 5) Kriteria Lulus
- Konversi dua arah menghasilkan nilai yang benar dan mudah dibaca.
- Baris `Alamat:` muncul otomatis setelah konversi sukses.
- Tidak ada error JS/PHP di console/log saat skenario utama dijalankan.
