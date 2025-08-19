<?php

namespace App\Controllers;

use App\Config\Database;

class BaseController
{
    protected function requireAuth()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
    }
    
    protected function render(string $view, array $data = [])
    {
        $this->requireAuth();
        
        extract($data);
        include __DIR__ . '/../Views/layouts/app.php';
    }
    
    protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }
    
    protected function json(array $data, int $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}