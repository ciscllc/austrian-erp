#!/bin/bash

echo "Repariere Installation..."

# Create missing directories
mkdir -p app/Controllers
mkdir -p app/Models
mkdir -p app/Helpers
mkdir -p app/Views/layouts
mkdir -p app/Views/partials
mkdir -p bootstrap/cache
mkdir -p storage/logs
mkdir -p storage/cache
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views

# Set permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Create Router
cat > app/Router.php << 'EOF'
<?php

namespace App;

class Router
{
    private $routes = [];
    
    public function registerRoutes(array $routes)
    {
        $this->routes = $routes;
    }
    
    public function dispatch(string $method, string $uri)
    {
        $uri = rtrim($uri, '/') ?: '/';
        $route = strtoupper($method) . ' ' . $uri;
        
        foreach ($this->routes as $pattern => $handler) {
            if ($pattern === $route) {
                return $this->executeHandler($handler);
            }
            
            // Pattern matching for variables
            $pattern = str_replace('{id}', '(\d+)', $pattern);
            $pattern = str_replace('/', '\/', $pattern);
            
            if (preg_match('/^' . $pattern . '$/', $route, $matches)) {
                array_shift($matches);
                return $this->executeHandler($handler, $matches);
            }
        }
        
        http_response_code(404);
        echo "404 - Page Not Found";
    }
    
    private function executeHandler($handler, array $params = [])
    {
        if (is_array($handler) && count($handler) === 2) {
            [$controllerClass, $method] = $handler;
            
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                
                if (method_exists($controller, $method)) {
                    return call_user_func_array([$controller, $method], $params);
                }
            }
        }
        
        throw new \Exception("Handler not found");
    }
}
EOF

# Create BaseController
cat > app/Controllers/BaseController.php << 'EOF'
<?php

namespace App\Controllers;

class BaseController
{
    protected function render(string $view, array $data = [])
    {
        $viewPath = __DIR__ . '/../Views/' . str_replace('.', '/', $view) . '.php';
        
        if (file_exists($viewPath)) {
            extract($data);
            include $viewPath;
        } else {
            echo "View not found: $viewPath";
        }
    }
    
    protected function json(array $data, int $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    
    protected function redirect(string $url, int $status = 302)
    {
        http_response_code($status);
        header("Location: $url");
        exit;
    }
}
EOF

echo "Installation repariert!"