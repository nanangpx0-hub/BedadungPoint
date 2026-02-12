<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/database.php';

function ok(string $message): void
{
    echo '[OK] ' . $message . PHP_EOL;
}

function fail_and_exit(string $message, int $code = 1): void
{
    fwrite(STDERR, '[FAIL] ' . $message . PHP_EOL);
    exit($code);
}

echo 'BedadungPoint Functional DB Test' . PHP_EOL;
echo 'Tanggal: ' . date('Y-m-d H:i:s') . PHP_EOL;
echo str_repeat('-', 45) . PHP_EOL;

try {
    $pdo = get_pdo();
    ok('Koneksi database berhasil.');
} catch (Throwable $exception) {
    fail_and_exit('Koneksi database gagal: ' . $exception->getMessage());
}

try {
    $pdo->query('SELECT 1 FROM points LIMIT 1');
    ok('Tabel points tersedia.');
} catch (Throwable $exception) {
    fail_and_exit('Tabel points belum ada. Jalankan points.sql terlebih dahulu.');
}

$testName = 'AUTO_TEST_' . date('Ymd_His');
$testLat = -8.1704;
$testLng = 113.7022;

try {
    $pdo->beginTransaction();

    $insert = $pdo->prepare('INSERT INTO points (nama, lat, lng) VALUES (:nama, :lat, :lng)');
    $insert->execute(
        array(
            ':nama' => $testName,
            ':lat' => $testLat,
            ':lng' => $testLng,
        )
    );

    $insertedId = (int) $pdo->lastInsertId();
    if ($insertedId <= 0) {
        fail_and_exit('Insert gagal: ID baru tidak valid.');
    }
    ok('Insert data berhasil (ID: ' . $insertedId . ').');

    $read = $pdo->prepare('SELECT nama, lat, lng FROM points WHERE id = :id');
    $read->execute(array(':id' => $insertedId));
    $row = $read->fetch();

    if (!$row) {
        fail_and_exit('Data tidak ditemukan setelah insert.');
    }

    if ((string) $row['nama'] !== $testName) {
        fail_and_exit('Nama data tidak sesuai. Dapat: ' . (string) $row['nama']);
    }
    ok('Read data berhasil dan nilai sesuai.');

    $delete = $pdo->prepare('DELETE FROM points WHERE id = :id');
    $delete->execute(array(':id' => $insertedId));
    if ($delete->rowCount() !== 1) {
        fail_and_exit('Delete gagal: jumlah row terhapus tidak sama dengan 1.');
    }

    $check = $pdo->prepare('SELECT COUNT(*) FROM points WHERE id = :id');
    $check->execute(array(':id' => $insertedId));
    $count = (int) $check->fetchColumn();

    if ($count !== 0) {
        fail_and_exit('Verifikasi delete gagal: data masih ada.');
    }
    ok('Delete data berhasil.');

    $pdo->rollBack();
    ok('Transaksi test di-roll back (database tetap bersih).');
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    fail_and_exit('Pengujian gagal: ' . $exception->getMessage());
}

echo str_repeat('-', 45) . PHP_EOL;
echo 'Semua pengujian database lulus.' . PHP_EOL;
exit(0);
