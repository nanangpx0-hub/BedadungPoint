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

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
if ($id === false || $id === null) {
    set_flash('error', 'ID data tidak valid.');
    redirect('index.php');
}

try {
    $statement = get_pdo()->prepare('DELETE FROM points WHERE id = :id');
    $statement->bindValue(':id', (int) $id, PDO::PARAM_INT);
    $statement->execute();

    if ($statement->rowCount() > 0) {
        set_flash('success', 'Data titik berhasil dihapus.');
    } else {
        set_flash('warning', 'Data tidak ditemukan atau sudah dihapus sebelumnya.');
    }
} catch (Throwable $exception) {
    set_flash('error', 'Gagal menghapus data. Periksa koneksi database Anda.');
}

redirect('index.php');
