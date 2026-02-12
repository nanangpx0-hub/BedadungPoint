# 13. Struktur Database

## 13.1 Ringkasan
Database digunakan oleh endpoint legacy untuk menyimpan daftar titik koordinat.

## 13.2 Nama Database
- Lokal (default): `db_bedadung`.
- Hosting (default aman): `bedadung`.
- Direkomendasikan: selalu set melalui `BEDADUNG_DB_NAME`.

## 13.3 Tabel `points`
| Kolom | Tipe | Null | Default | Keterangan |
|---|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | No | Auto Increment | Primary key |
| `nama` | `VARCHAR(120)` | No | - | Nama/label titik |
| `lat` | `DECIMAL(10,7)` | No | - | Latitude |
| `lng` | `DECIMAL(10,7)` | No | - | Longitude |
| `waktu` | `TIMESTAMP` | No | `CURRENT_TIMESTAMP` | Waktu simpan |

Index:
- `PRIMARY KEY (id)`
- `KEY idx_points_waktu (waktu)`
- `KEY idx_points_nama (nama)`

## 13.4 DDL Referensi
```sql
CREATE TABLE IF NOT EXISTS points (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nama VARCHAR(120) NOT NULL,
    lat DECIMAL(10,7) NOT NULL,
    lng DECIMAL(10,7) NOT NULL,
    waktu TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_points_waktu (waktu),
    KEY idx_points_nama (nama)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;
```

## 13.5 Operasi Data
- Insert: `POST /simpan.php`.
- Delete: `POST /hapus.php`.
- Read: saat render daftar titik (jika modul legacy diaktifkan UI).

## 13.6 Catatan Keamanan Data
- Tidak menyimpan data autentikasi pengguna.
- Hindari menyimpan data sensitif non-esensial.
- Gunakan backup rutin pada production.
