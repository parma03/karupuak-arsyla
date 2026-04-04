<?php
// config/app.php

define('APP_NAME',    'Karupuak Jangek Arsyla');
define('APP_URL',     'http://localhost/kaknani/karupuak-arsyla');
define('APP_VERSION', '1.0.0');
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

// RajaOngkir
define('RAJAONGKIR_API_KEY', 'ISI_API_KEY_RAJAONGKIR_ANDA');
define('RAJAONGKIR_BASE_URL', 'https://api.rajaongkir.com/starter');
define('STORE_CITY_ID',      '432'); // ID kota toko (sesuaikan)

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
