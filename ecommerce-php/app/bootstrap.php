<?php

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = __DIR__ . '/' . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

require_once __DIR__ . '/Support/helpers.php';

use App\Core\Database;
use App\Core\Env;
use App\Core\ErrorHandler;
use App\Core\Router;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$rootPath = dirname(__DIR__);
Env::load($rootPath . '/.env');

$appDebug = filter_var(env('APP_DEBUG', 'false'), FILTER_VALIDATE_BOOL);
ErrorHandler::register($appDebug);

$config = [
    'host' => env('DB_HOST', 'localhost'),
    'db' => env('DB_NAME', 'ecommerce'),
    'user' => env('DB_USER', 'root'),
    'pass' => env('DB_PASS', ''),
    'charset' => env('DB_CHARSET', 'utf8mb4'),
];

$pdo = Database::connect($config);

$router = new Router();

require __DIR__ . '/routes.php';

return [
    'pdo' => $pdo,
    'router' => $router,
];
