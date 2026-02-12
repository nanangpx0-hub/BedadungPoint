# 11. Implementasi Fitur Feedback

## 11.1 Tujuan
Menyediakan saluran masukan pengguna langsung dari antarmuka aplikasi.

## 11.2 Spesifikasi Fungsional
- Tautan feedback menggunakan skema `mailto:`.
- Alamat tujuan: `nanang.pamungkas@bps.go.id`.
- Subject otomatis: `Feedback Pengembangan Aplikasi`.
- Memiliki `aria-label` untuk aksesibilitas.

## 11.3 Implementasi Dasar (Contoh)
```html
<a href="mailto:nanang.pamungkas@bps.go.id?subject=Feedback%20Pengembangan%20Aplikasi"
   aria-label="Kirim feedback ke nanang.pamungkas@bps.go.id">
  Kirim Feedback
</a>
```

## 11.4 Fallback
Jika email client tidak terbuka otomatis:
- Tampilkan alamat email secara jelas di UI.
- Pengguna dapat copy alamat lalu mengirim manual.

## 11.5 Uji Fungsional
- Desktop: klik link membuka email client default.
- Mobile: klik link membuka aplikasi mail yang terpasang.
- Keyboard: fokus dengan `Tab`, aktivasi dengan `Enter`.

## 11.6 Aset Uji
- `docs/assets/screenshots/feedback-desktop.png`
- `docs/assets/screenshots/feedback-mobile.png`
