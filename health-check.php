<?php

class HealthChecker
{
    public function run()
    {
        $checks = [
            'PHP Version' => $this->checkPHPVersion(),
            'Extensions' => $this->checkExtensions(),
            'Database' => $this->checkDatabase(),
            'File Permissions' => $this->checkPermissions(),
            'Environment' => $this->checkEnvironment(),
            'Dependencies' => $this->checkDependencies()
        ];
        
        $this->displayResults($checks);
    }
    
    private function checkPHPVersion()
    {
        return [
            'status' => version_compare(PHP_VERSION, '8.0.0', '>='),
            'message' => PHP_VERSION
        ];
    }
    
    private function checkExtensions()
    {
        $extensions = ['pdo', 'pdo_mysql', 'json', 'mbstring', 'gd', 'zip', 'openssl'];
        $missing = [];
        
        foreach ($extensions as $ext) {
            if (!extension_loaded($ext)) {
                $missing[] = $ext;
            }
        }
        
        return [
            'status' => empty($missing),
            'message' => empty($missing) ? 'Alle Extensions verfügbar' : 'Fehlend: ' . implode(', ', $missing)
        ];
    }
    
    private function checkDatabase()
    {
        try {
            $config = $this->parseEnvConfig();
            $dsn = "mysql:host={$config['DB_HOST']};dbname={$config['DB_DATABASE']};charset=utf8mb4";
            $pdo = new PDO($dsn, $config['DB_USERNAME'], $config['DB_PASSWORD']);
            $pdo->query("SELECT 1");
            
            return [
                'status' => true,
                'message' => 'Datenbankverbindung OK'
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    private function checkPermissions()
    {
        $paths = ['storage', 'storage/logs', 'storage/uploads', 'bootstrap/cache'];
        $issues = [];
        
        foreach ($paths as $path) {
            if (!is_writable($path)) {
                $issues[] = $path;
            }
        }
        
        return [
            'status' => empty($issues),
            'message' => empty($issues) ? 'Alle Berechtigungen OK' : 'Nicht beschreibbar: ' . implode(', ', $issues)
        ];
    }
    
    private function checkEnvironment()
    {
        return [
            'status' => file_exists('.env'),
            'message' => file_exists('.env') ? '.env Datei vorhanden' : '.env Datei fehlt'
        ];
    }
    
    private function checkDependencies()
    {
        $composerExists = file_exists('vendor/autoload.php');
        $npmExists = file_exists('node_modules');
        
        return [
            'status' => $composerExists && $npmExists,
            'message' => $composerExists && $npmExists ? 'Alle Dependencies installiert' : 'Dependencies fehlen'
        ];
    }
    
    private function parseEnvConfig()
    {
        if (!file_exists('.env')) {
            return [];
        }
        
        $lines = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $config = [];
        
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $config[$key] = trim($value);
            }
        }
        
        return $config;
    }
    
    private function displayResults($checks)
    {
        echo "<!DOCTYPE html>
        <html><head><title>System Health Check</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 40px; }
            .check { margin: 10px 0; padding: 10px; border-radius: 5px; }
            .success { background: #d4edda; color: #155724; }
            .error { background: #f8d7da; color: #721c24; }
            .warning { background: #fff3cd; color: #856404; }
            .overall { margin: 20px 0; padding: 20px; border-radius: 10px; font-size: 18px; }
        </style></head><body>";
        
        echo "<h1>System Health Check</h1>";
        
        $overallStatus = true;
        foreach ($checks as $name => $check) {
            $class = $check['status'] ? 'success' : 'error';
            $icon = $check['status'] ? '✓' : '✗';
            echo "<div class='check $class'>$icon <strong>$name:</strong> {$check['message']}</div>";
            
            if (!$check['status']) {
                $overallStatus = false;
            }
        }
        
        $overallClass = $overallStatus ? 'success' : 'error';
        echo "<div class='overall $overallClass'>";
        echo $overallStatus ? "System ist bereit zur Verwendung!" : "Bitte beheben Sie die markierten Probleme.";
        echo "</div>";
        
        echo "</body></html>";
    }
}

// Run health check
$checker = new HealthChecker();
$checker->run();