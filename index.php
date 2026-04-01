<?php

/**
 * KARUPUAK JANGEK ARSYLA — E-Commerce Platform
 * Entry Point (Front Controller)
 *
 * URL: http://localhost/karupuak-arsyla/
 */

define('ROOT_PATH', __DIR__);
define('APP_PATH',  ROOT_PATH . '/app');
define('SRC_PATH',  ROOT_PATH . '/src');
define('CORE_PATH', ROOT_PATH . '/core');

// Autoloader sederhana (PSR-4 style)
spl_autoload_register(function (string $class): void {
    $map = [
        'Core\\'           => CORE_PATH . '/',
        'App\\'            => APP_PATH . '/',
        'Domain\\'         => SRC_PATH . '/Domain/',
        'Application\\'    => SRC_PATH . '/Application/',
        'Infrastructure\\' => SRC_PATH . '/Infrastructure/',
    ];

    foreach ($map as $prefix => $base) {
        if (str_starts_with($class, $prefix)) {
            $relative = substr($class, strlen($prefix));
            $file = $base . str_replace('\\', '/', $relative) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});

// Load konfigurasi
require_once ROOT_PATH . '/config/app.php';
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/config/midtrans.php';

// Bootstrap & jalankan aplikasi
use Core\Application;

$app = new Application();
$app->run();
