<?php

namespace App\Config;

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\CustomerController;
use App\Controllers\ProductController;
use App\Controllers\OrderController;
use App\Controllers\InvoiceController;
use App\Controllers\DeliveryController;
use App\Controllers\SettingsController;
use App\Controllers\ApiController;
use App\Controllers\BackupController;

class Routes
{
    public static function getRoutes(): array
    {
        return [
            // Auth
            'GET /login' => [AuthController::class, 'showLogin'],
            'POST /login' => [AuthController::class, 'login'],
            'POST /logout' => [AuthController::class, 'logout'],

            // Dashboard
            'GET /' => [DashboardController::class, 'index'],
            'GET /dashboard' => [DashboardController::class, 'index'],

            // Customers
            'GET /customers' => [CustomerController::class, 'index'],
            'GET /customers/create' => [CustomerController::class, 'create'],
            'POST /customers' => [CustomerController::class, 'store'],
            'GET /customers/{id}' => [CustomerController::class, 'show'],
            'GET /customers/{id}/edit' => [CustomerController::class, 'edit'],
            'POST /customers/{id}/update' => [CustomerController::class, 'update'],
            'POST /customers/{id}/delete' => [CustomerController::class, 'destroy'],

            // Products
            'GET /products' => [ProductController::class, 'index'],
            'GET /products/create' => [ProductController::class, 'create'],
            'POST /products' => [ProductController::class, 'store'],
            'GET /products/{id}' => [ProductController::class, 'show'],
            'GET /products/{id}/edit' => [ProductController::class, 'edit'],
            'POST /products/{id}/update' => [ProductController::class, 'update'],
            'POST /products/{id}/delete' => [ProductController::class, 'destroy'],

            // Orders
            'GET /orders' => [OrderController::class, 'index'],
            'GET /orders/create' => [OrderController::class, 'create'],
            'POST /orders' => [OrderController::class, 'store'],
            'GET /orders/{id}' => [OrderController::class, 'show'],
            'GET /orders/{id}/edit' => [OrderController::class, 'edit'],
            'POST /orders/{id}/update' => [OrderController::class, 'update'],
            'POST /orders/{id}/delete' => [OrderController::class, 'destroy'],

            // Documents
            'GET /orders/{id}/invoice' => [InvoiceController::class, 'generate'],
            'GET /orders/{id}/delivery' => [DeliveryController::class, 'generate'],
            'POST /orders/{id}/send-email' => [OrderController::class, 'sendEmail'],
            'POST /orders/{id}/signature' => [OrderController::class, 'saveSignature'],

            // Settings
            'GET /settings' => [SettingsController::class, 'index'],
            'POST /settings' => [SettingsController::class, 'update'],

            // API
            'GET /api/products' => [ApiController::class, 'getProducts'],
            'POST /api/products/{id}/sub-products' => [ApiController::class, 'addSubProduct'],
            'GET /api/orders/{id}/signature' => [ApiController::class, 'getSignature'],
            'POST /api/orders/{id}/signature' => [ApiController::class, 'saveSignature'],
            'GET /api/pdf/{orderId}/{type}' => [ApiController::class, 'generatePDF'],
            'POST /api/email/{orderId}' => [ApiController::class, 'sendEmail'],

            // Backups
            'GET /admin/backups' => [BackupController::class, 'index'],
            'POST /admin/backups/create' => [BackupController::class, 'create'],
            'POST /admin/backups/restore/{id}' => [BackupController::class, 'restore'],
            'POST /admin/settings/backup' => [SettingsController::class, 'updateBackupSettings'],
        ];
    }
}