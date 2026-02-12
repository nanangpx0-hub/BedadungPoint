# 6. Konfigurasi dan Environment Variables

## 6.1 Prinsip Umum
Semua konfigurasi sensitif harus dimuat dari environment variable. Jangan simpan secret di source code.

## 6.2 Daftar Variabel Environment
| Variabel | Wajib | Default | Keterangan |
|---|---|---|---|
| `MAPS_API_KEY` | Ya (fitur peta) | kosong | API key Google Maps JS. |
| `MAPS_API_KEY_LOCAL` | Opsional | kosong | Fallback key khusus mode lokal. |
| `BEDADUNG_DB_HOST` | Ya (jika endpoint DB dipakai) | `localhost` | Host database. |
| `BEDADUNG_DB_PORT` | Opsional | `3306` | Port database. |
| `BEDADUNG_DB_NAME` | Ya (jika endpoint DB dipakai) | `db_bedadung` lokal / `bedadung` hosting | Nama database. |
| `BEDADUNG_DB_USER` | Ya (jika endpoint DB dipakai) | `root` lokal / `bedadung_user` hosting | User database. |
| `BEDADUNG_DB_PASS` | Ya (hosting) | kosong | Password database. |
| `BEDADUNG_DEBUG_ENV` | Opsional | `false` | Menampilkan header debug environment. |

## 6.3 Template Konfigurasi
Gunakan `.env.example` sebagai referensi nilai awal.

Contoh:
```ini
MAPS_API_KEY=
MAPS_API_KEY_LOCAL=
BEDADUNG_DB_HOST=localhost
BEDADUNG_DB_PORT=3306
BEDADUNG_DB_NAME=db_bedadung
BEDADUNG_DB_USER=root
BEDADUNG_DB_PASS=
BEDADUNG_DEBUG_ENV=false
```

## 6.4 Deteksi Mode Environment
`database.php` mendeteksi mode runtime berdasarkan host request:
- `LOCALHOST`: `localhost`, `127.0.0.1`, `::1`, domain `.test`/`.local`.
- `HOSTING`: selain nilai di atas.

## 6.5 Pengaturan di Windows (PowerShell)
```powershell
setx MAPS_API_KEY "YOUR_GOOGLE_MAPS_KEY"
setx BEDADUNG_DB_HOST "localhost"
setx BEDADUNG_DB_PORT "3306"
setx BEDADUNG_DB_NAME "db_bedadung"
setx BEDADUNG_DB_USER "root"
setx BEDADUNG_DB_PASS ""
setx BEDADUNG_DEBUG_ENV "false"
```

## 6.6 Pengaturan di Apache VirtualHost
```apache
SetEnv MAPS_API_KEY "YOUR_GOOGLE_MAPS_KEY"
SetEnv BEDADUNG_DB_HOST "localhost"
SetEnv BEDADUNG_DB_PORT "3306"
SetEnv BEDADUNG_DB_NAME "your_db_name"
SetEnv BEDADUNG_DB_USER "your_db_user"
SetEnv BEDADUNG_DB_PASS "your_db_password"
SetEnv BEDADUNG_DEBUG_ENV "false"
```

## 6.7 Praktik Keamanan
- Gunakan API key terpisah untuk dev/staging/prod.
- Aktifkan restriction API key berdasarkan referrer domain.
- Rotasi key dan password secara berkala.
- Jangan commit `.env`, `config.json`, atau file berisi secret.
