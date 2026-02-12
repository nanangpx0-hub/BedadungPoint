# 8. Kontribusi dan Standar Pengembangan

## 8.1 Prinsip Kontribusi
- Perubahan harus dapat direproduksi.
- Wajib ada validasi teknis minimal sebelum merge.
- Perubahan perilaku aplikasi wajib diikuti update dokumentasi.

## 8.2 Workflow Git
1. Buat branch dari `main`:
   - `feature/...`, `fix/...`, atau `docs/...`.
2. Lakukan perubahan terfokus.
3. Jalankan verifikasi lokal.
4. Buat commit dengan pesan jelas.
5. Buat Pull Request berisi ringkasan, dampak, dan hasil uji.

## 8.3 Konvensi Commit
Contoh:
- `docs: refresh deployment and API documentation`
- `fix: improve reverse geocode fallback handling`
- `chore: harden gitignore for secret files`

## 8.4 Standar Kode
PHP:
- `declare(strict_types=1);`
- Validasi input server-side.
- Prepared statements untuk query DB.

JavaScript:
- Gunakan mode strict.
- Pisahkan logic parsing, transformasi, dan UI event.
- Hindari hardcoded secret.

HTML/CSS:
- Responsif untuk desktop dan mobile.
- Gunakan atribut aksesibilitas dasar (`aria-label`, fokus keyboard).

## 8.5 Standar Keamanan
- Dilarang commit kredensial database/API key/password.
- Gunakan `.env`/environment variable.
- Lindungi endpoint POST dengan CSRF.
- Review ulang perubahan konfigurasi sebelum merge.

## 8.6 Checklist Sebelum Merge
- [ ] Lint/syntax check lulus.
- [ ] Fitur utama tidak regress.
- [ ] Dokumentasi terkait diperbarui.
- [ ] Tidak ada secret di diff.
- [ ] PR description lengkap.
