<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Router;
use App\Config\Routes;

// Load environment variables
if (file_exists(dirname(__DIR__) . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
}

// Error reporting
if ($_ENV['APP_DEBUG'] ?? false) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Start session
session_start();

// Set timezone
date_default_timezone_set($_ENV['TIMEZONE'] ?? 'Europe/Vienna');

// Initialize router
$router = new Router();
$router->registerRoutes(Routes::getRoutes());

// Get request method and URI
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

try {
    $router->dispatch($requestMethod, $requestUri);
} catch (\Exception $e) {
    http_response_code(500);
    if ($_ENV['APP_DEBUG'] ?? false) {
        echo "Error: " . $e->getMessage();
    } else {
        echo "Internal Server Error";
    }
}