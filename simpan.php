<?php
declare(strict_types=1);

require_once __DIR__ . '/database.php';
send_security_headers();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
}

$csrfToken = isset($_POST['csrf_token']) ? (string) $_POST['csrf_token'] : '';
if (!is_valid_csrf($csrfToken)) {
    set_flash('error', 'Token keamanan tidak valid. Muat ulang halaman dan coba lagi.');
    redirect('index.php');
}

$nama = trim((string) ($_POST['nama'] ?? ''));
$latRaw = str_replace(',', '.', trim((string) ($_POST['lat'] ?? '')));
$lngRaw = str_replace(',', '.', trim((string) ($_POST['lng'] ?? '')));

set_old_input(
    array(
        'nama' => $nama,
        'lat' => $latRaw,
        'lng' => $lngRaw,
    )
);

$errors = array();
$nameLength = function_exists('mb_strlen') ? mb_strlen($nama) : strlen($nama);

if ($nama === '') {
    $errors[] = 'Nama lokasi wajib diisi.';
}

if ($nameLength > 120) {
    $errors[] = 'Nama lokasi maksimal 120 karakter.';
}

if (!is_numeric($latRaw)) {
    $errors[] = 'Latitude harus berupa angka.';
}

if (!is_numeric($lngRaw)) {
    $errors[] = 'Longitude harus berupa angka.';
}

$lat = (float) $latRaw;
$lng = (float) $lngRaw;

if ($lat < -90 || $lat > 90) {
    $errors[] = 'Latitude harus berada di rentang -90 sampai 90.';
}

if ($lng < -180 || $lng > 180) {
    $errors[] = 'Longitude harus berada di rentang -180 sampai 180.';
}

if (!empty($errors)) {
    set_flash('error', implode(' ', $errors));
    redirect('index.php');
}

try {
    $statement = get_pdo()->prepare('INSERT INTO points (nama, lat, lng) VALUES (:nama, :lat, :lng)');
    $statement->bindValue(':nama', $nama, PDO::PARAM_STR);
    $statement->bindValue(':lat', $lat);
    $statement->bindValue(':lng', $lng);
    $statement->execute();

    set_old_input(array());
    set_flash('success', 'Titik koordinat berhasil disimpan.');
} catch (Throwable $exception) {
    set_flash('error', 'Gagal menyimpan data. Pastikan tabel points sudah dibuat dan koneksi database benar.');
}

redirect('index.php');
