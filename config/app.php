<?php
// config/app.php

define('APP_NAME',    'Karupuak Jangek Arsyla');
define('APP_VERSION', '1.0.0');

// ── Auto-detect base path & URL ────────────────────────────────────────────
// Bekerja di path manapun: localhost/, localhost/karupuak-arsyla/,
// localhost/kaknani/karupuak-arsyla/, dsb. tanpa perlu diubah manual.
(function () {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        ? 'https' : 'http';
    $host     = $_SERVER['HTTP_HOST'] ?? 'localhost';

    // SCRIPT_NAME = /kaknani/karupuak-arsyla/index.php  →  dirname = /kaknani/karupuak-arsyla
    $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '/\\');
    // Normalisasi: bisa jadi "" (root) atau "/subfolder"
    if ($basePath === '\\' || $basePath === '.') {
        $basePath = '';
    }

    define('APP_BASE_PATH', $basePath);          // e.g. "" | "/karupuak-arsyla" | "/kaknani/karupuak-arsyla"
    define('APP_URL',       $protocol . '://' . $host . $basePath);
})();
define('APP_DEBUG',   true);   // Set false di production

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Session
define('SESSION_NAME',     'karupuak_session');
define('SESSION_LIFETIME', 7200); // 2 jam

// Upload
define('UPLOAD_PATH',     ROOT_PATH . '/public/uploads/products/');
define('UPLOAD_URL',      APP_URL . '/public/uploads/products/');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5 MB
define('ALLOWED_TYPES',   ['image/jpeg', 'image/png', 'image/webp']);

// Pagination
define('ITEMS_PER_PAGE', 12);

// Error handling
if (APP_DEBUG) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}
