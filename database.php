<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params(
        array(
            'lifetime' => 0,
            'path' => '/',
            'secure' => $isHttps,
            'httponly' => true,
            'samesite' => 'Lax',
        )
    );
    session_start();
}

function is_local_environment(): bool
{
    $host = 'localhost';

    if (!empty($_SERVER['HTTP_HOST'])) {
        $host = $_SERVER['HTTP_HOST'];
    } elseif (!empty($_SERVER['SERVER_NAME'])) {
        $host = $_SERVER['SERVER_NAME'];
    }

    $host = strtolower((string) preg_replace('/:\d+$/', '', $host));
    $localHosts = array('localhost', '127.0.0.1', '::1');

    if (in_array($host, $localHosts, true)) {
        return true;
    }

    return (bool) preg_match('/(\.test|\.local)$/', $host);
}

function get_env_value(string $key): string
{
    $value = getenv($key);
    if ($value !== false && trim((string) $value) !== '') {
        return trim((string) $value);
    }

    if (isset($_ENV[$key]) && trim((string) $_ENV[$key]) !== '') {
        return trim((string) $_ENV[$key]);
    }

    if (isset($_SERVER[$key]) && trim((string) $_SERVER[$key]) !== '') {
        return trim((string) $_SERVER[$key]);
    }

    return '';
}

if (!defined('MAPS_API_KEY_LOCAL')) {
    // Fallback manual untuk localhost jika env MAPS_API_KEY tidak terbaca oleh web server.
    define('MAPS_API_KEY_LOCAL', 'AIzaSyA9hl838wg2X7AjUuZEgpY5Wd8Xlb3uyFk');
}

if (!defined('DB_HOST')) {
    $dbHost = get_env_value('BEDADUNG_DB_HOST');
    define('DB_HOST', $dbHost !== '' ? $dbHost : '127.0.0.1');
}

if (!defined('DB_PORT')) {
    $dbPort = get_env_value('BEDADUNG_DB_PORT');
    define('DB_PORT', $dbPort !== '' ? $dbPort : '3306');
}

if (is_local_environment()) {
    if (!defined('DB_NAME')) {
        define('DB_NAME', 'db_bedadung');
    }
    if (!defined('DB_USER')) {
        define('DB_USER', 'root');
    }
    if (!defined('DB_PASS')) {
        define('DB_PASS', '');
    }
} else {
    if (!defined('DB_NAME')) {
        define('DB_NAME', 'bpsjembe_bedadung');
    }
    if (!defined('DB_USER')) {
        define('DB_USER', 'bpsjembe_admin');
    }
    if (!defined('DB_PASS')) {
        $prodPassword = get_env_value('BEDADUNG_DB_PASS');
        define('DB_PASS', $prodPassword !== '' ? $prodPassword : 'ganti_dengan_password_hosting');
    }
}

if (!defined('MAPS_API_KEY')) {
    $mapsApiKey = get_env_value('MAPS_API_KEY');
    if ($mapsApiKey === '') {
        $mapsApiKey = MAPS_API_KEY_LOCAL;
    }
    define('MAPS_API_KEY', trim((string) $mapsApiKey));
}

function get_pdo(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        DB_HOST,
        DB_PORT,
        DB_NAME
    );

    $pdo = new PDO(
        $dsn,
        DB_USER,
        DB_PASS,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        )
    );

    return $pdo;
}

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return (string) $_SESSION['csrf_token'];
}

function is_valid_csrf(?string $token): bool
{
    if (!is_string($token) || $token === '') {
        return false;
    }

    if (empty($_SESSION['csrf_token'])) {
        return false;
    }

    return hash_equals((string) $_SESSION['csrf_token'], $token);
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = array(
        'type' => $type,
        'message' => $message,
    );
}

function get_flash(): ?array
{
    if (empty($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function set_old_input(array $data): void
{
    $_SESSION['old_input'] = $data;
}

function pull_old_input(): array
{
    if (empty($_SESSION['old_input']) || !is_array($_SESSION['old_input'])) {
        return array();
    }

    $oldInput = $_SESSION['old_input'];
    unset($_SESSION['old_input']);

    return $oldInput;
}

function redirect(string $location): void
{
    header('Location: ' . $location);
    exit;
}

function send_security_headers(): void
{
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), camera=(), microphone=()');
}
