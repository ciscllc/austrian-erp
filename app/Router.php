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
        $uri = $this->normalizeUri($uri);
        $method = strtoupper($method);
        
        foreach ($this->routes as $routePattern => $handler) {
            if ($this->matchRoute($method, $uri, $routePattern)) {
                return $this->executeHandler($handler);
            }
        }
        
        $this->send404();
    }
    
    private function normalizeUri(string $uri): string
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/');
        return $uri === '' ? '/' : $uri;
    }
    
    private function matchRoute(string $method, string $uri, string $routePattern): bool
    {
        $parts = explode(' ', $routePattern, 2);
        $routeMethod = $parts[0];
        $routePath = $parts[1] ?? '';
        
        if ($routeMethod !== $method) {
            return false;
        }
        
        // Convert route pattern to regex
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $routePath);
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = '/^' . $pattern . '$/';
        
        return (bool) preg_match($pattern, $uri);
    }
    
    private function executeHandler($handler)
    {
        if (is_array($handler) && count($handler) === 2) {
            [$controllerClass, $method] = $handler;
            
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                
                if (method_exists($controller, $method)) {
                    $params = $this->extractParameters();
                    return call_user_func_array([$controller, $method], $params);
                }
            }
        }
        
        throw new \Exception("Handler not found");
    }
    
    private function extractParameters(): array
    {
        $params = [];
        
        // URL parameters
        $uri = $this->normalizeUri($_SERVER['REQUEST_URI']);
        $uriParts = explode('/', trim($uri, '/'));
        
        foreach ($uriParts as $part) {
            if (is_numeric($part)) {
                $params[] = (int)$part;
            }
        }
        
        // POST parameters
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $params[] = $_POST;
        }
        
        return $params;
    }
    
    private function send404()
    {
        http_response_code(404);
        include __DIR__ . '/../views/errors/404.php';
        exit;
    }
}