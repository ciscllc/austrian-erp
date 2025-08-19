<?php

namespace App\Controllers;

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
        
        // Extract variables for view
        extract($data);
        
        // Include view content
        $viewPath = __DIR__ . '/../Views/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View not found: $viewPath");
        }
        
        // Include the view (no layout() method needed)
        ob_start();
        include $viewPath;
        $content = ob_get_clean();
        
        // Always include layout
        $layoutPath = __DIR__ . '/../Views/layouts/app.php';
        if (file_exists($layoutPath)) {
            include $layoutPath;
        } else {
            echo $content;
        }
    }
    
    protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }
}