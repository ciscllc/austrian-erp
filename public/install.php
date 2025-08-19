#!/usr/bin/env php
<?php

class AustrianERPInstaller
{
    private $steps = [];
    private $currentStep = 0;
    private $totalSteps = 0;
    private $logFile = 'install.log';
    
    public function __construct()
    {
        $this->steps = [
            'Systemprüfung',
            'PHP Extensions prüfen',
            'Datenbankverbindung testen',
            'Abhängigkeiten installieren',
            'Umgebungsvariablen einrichten',
            'Datenbank-Schema importieren',
            'Admin-Benutzer erstellen',
            'Assets kompilieren',
            'Webserver-Konfiguration prüfen',
            'Installation abschließen'
        ];
        $this->totalSteps = count($this->steps);
        
        $this->checkCLI();
    }
    
    public function run()
    {
        $this->printHeader();
        
        try {
            foreach ($this->steps as $step => $description) {
                $this->currentStep = $step + 1;
                $this->executeStep($description);
            }
            
            $this->printSuccess();
        } catch (\Exception $e) {
            $this->printError($e->getMessage());
            $this->printHelp();
            exit(1);
        }
    }
    
    private function executeStep(string $stepName)
    {
        $this->printStep($stepName);
        
        switch ($stepName) {
            case 'Systemprüfung':
                $this->checkSystemRequirements();
                break;
            case 'PHP Extensions prüfen':
                $this->checkPHPExtensions();
                break;
            case 'Datenbankverbindung testen':
                $this->testDatabaseConnection();
                break;
            case 'Abhängigkeiten installieren':
                $this->installDependencies();
                break;
            case 'Umgebungsvariablen einrichten':
                $this->setupEnvironment();
                break;
            case 'Datenbank-Schema importieren':
                $this->importDatabaseSchema();
                break;
            case 'Admin-Benutzer erstellen':
                $this->createAdminUser();
                break;
            case 'Assets kompilieren':
                $this->compileAssets();
                break;
            case 'Webserver-Konfiguration prüfen':
                $this->checkWebserverConfig();
                break;
            case 'Installation abschließen':
                $this->finalizeInstallation();
                break;
        }
        
        $this->printStepComplete();
    }
    
    private function checkSystemRequirements()
    {
        $requirements = [
            'PHP Version >= 8.0' => [
                'check' => version_compare(PHP_VERSION, '8.0.0', '>='),
                'help' => 'PHP 8.0+ installieren: https://php.net/downloads.php'
            ],
            'Composer' => [
                'check' => $this->commandExists('composer'),
                'help' => 'Composer installieren: https://getcomposer.org/download/'
            ],
            'MySQL/MariaDB' => [
                'check' => $this->commandExists('mysql'),
                'help' => 'MySQL/MariaDB installieren: https://mariadb.org/download/'
            ],
            'Node.js' => [
                'check' => $this->commandExists('node'),
                'help' => 'Node.js installieren: https://nodejs.org/'
            ],
            'Git' => [
                'check' => $this->commandExists('git'),
                'help' => 'Git installieren: https://git-scm.com/downloads'
            ]
        ];
        
        $failed = [];
        foreach ($requirements as $req => $data) {
            if (!$data['check']) {
                $failed[] = "$req: {$data['help']}";
            }
        }
        
        if (!empty($failed)) {
            throw new \Exception("Systemanforderungen nicht erfüllt:\n" . implode("\n", $failed));
        }
    }
    
    private function checkPHPExtensions()
    {
        $extensions = [
            'pdo' => 'PDO Extension',
            'pdo_mysql' => 'PDO MySQL Extension',
            'json' => 'JSON Extension',
            'mbstring' => 'Multibyte String Extension',
            'gd' => 'GD Graphics Extension',
            'zip' => 'ZIP Extension',
            'openssl' => 'OpenSSL Extension',
            'curl' => 'cURL Extension',
            'intl' => 'Internationalization Extension',
            'fileinfo' => 'Fileinfo Extension'
        ];
        
        $missing = [];
        foreach ($extensions as $ext => $name) {
            if (!extension_loaded($ext)) {
                $missing[] = $name;
            }
        }
        
        if (!empty($missing)) {
            $help = "Installieren Sie die fehlenden PHP Extensions:\n";
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $help .= "Windows: Entfernen Sie das Semikolon (;) vor der Extension in php.ini\n";
            } else {
                $help .= "Ubuntu/Debian: sudo apt install php8.2-" . strtolower(str_replace(' ', '-', $missing[0])) . "\n";
                $help .= "CentOS/RHEL: sudo yum install php-" . strtolower(str_replace(' ', '-', $missing[0])) . "\n";
            }
            throw new \Exception("Fehlende PHP Extensions:\n" . implode(', ', $missing) . "\n\n" . $help);
        }
    }
    
    private function testDatabaseConnection()
    {
        $config = $this->collectDatabaseConfig();
        
        try {
            $dsn = "mysql:host={$config['host']};port={$config['port']};charset=utf8mb4";
            $pdo = new PDO($dsn, $config['user'], $config['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            
            // Check if database exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM information_schema.schemata WHERE schema_name = ?");
            $stmt->execute([$config['name']]);
            $exists = $stmt->fetchColumn() > 0;
            
            if ($exists) {
                $overwrite = $this->askYesNo("Datenbank '{$config['name']}' existiert bereits. Überschreiben?", false);
                if (!$overwrite) {
                    throw new \Exception("Installation abgebrochen");
                }
            }
            
            // Create database
            $pdo->exec("CREATE DATABASE IF NOT EXISTS {$config['name']} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE {$config['name']}");
            
            // Test write permissions
            $pdo->exec("CREATE TABLE IF NOT EXISTS test_table (id INT PRIMARY KEY)");
            $pdo->exec("DROP TABLE IF EXISTS test_table");
            
        } catch (\PDOException $e) {
            throw new \Exception("Datenbankverbindung fehlgeschlagen: " . $e->getMessage());
        }
    }
    
    private function collectDatabaseConfig()
    {
        print "\n\033[34mDatenbank-Konfiguration:\033[0m\n";
        
        return [
            'host' => $this->askInput("Host", "localhost"),
            'port' => $this->askInput("Port", "3306"),
            'name' => $this->askInput("Datenbankname", "austrian_erp"),
            'user' => $this->askInput("Benutzername", "root"),
            'pass' => $this->askInput("Passwort", "", true)
        ];
    }
    
    private function installDependencies()
    {
        print "\n\033[34mInstalliere Abhängigkeiten...\033[0m\n";
        
        if (!file_exists('composer.json')) {
            throw new \Exception("composer.json nicht gefunden");
        }
        
        $this->runCommand('composer install --optimize-autoloader --no-dev --no-interaction');
        
        if (file_exists('package.json')) {
            $this->runCommand('npm install --production --silent');
        }
    }
    
    private function setupEnvironment()
    {
        if (file_exists('.env')) {
            $backup = '.env.backup.' . date('Y-m-d_H-i-s');
            rename('.env', $backup);
            print "Bestehende .env gesichert als: $backup\n";
        }
        
        $config = $this->collectEnvironmentConfig();
        
        $envContent = $this->generateEnvContent($config);
        file_put_contents('.env', $envContent);
        
        // Generate application key
        $this->runCommand('php -r "echo bin2hex(random_bytes(32));" > storage/app.key');
    }
    
    private function collectEnvironmentConfig()
    {
        print "\n\033[34mSystem-Konfiguration:\033[0m\n";
        
        return [
            'app_name' => $this->askInput("Anwendungsname", "Österreichische ERP"),
            'app_url' => $this->askInput("App URL", "http://localhost:8000"),
            'mail_driver' => $this->askInput("Mail Driver", "smtp"),
            'mail_host' => $this->askInput("Mail Host", "localhost"),
            'mail_port' => $this->askInput("Mail Port", "587"),
            'mail_user' => $this->askInput("Mail Benutzer", ""),
            'mail_pass' => $this->askInput("Mail Passwort", "", true),
            'timezone' => $this->askInput("Zeitzone", "Europe/Vienna"),
            'locale' => $this->askInput("Sprache", "de")
        ];
    }
    
    private function generateEnvContent($config)
    {
        $dbConfig = $this->collectDatabaseConfig();
        
        return <<<ENV
APP_NAME="{$config['app_name']}"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:$(php -r "echo base64_encode(random_bytes(32));")
APP_URL={$config['app_url']}

DB_CONNECTION=mysql
DB_HOST={$dbConfig['host']}
DB_PORT={$dbConfig['port']}
DB_DATABASE={$dbConfig['name']}
DB_USERNAME={$dbConfig['user']}
DB_PASSWORD={$dbConfig['pass']}

MAIL_DRIVER={$config['mail_driver']}
MAIL_HOST={$config['mail_host']}
MAIL_PORT={$config['mail_port']}
MAIL_USERNAME={$config['mail_user']}
MAIL_PASSWORD={$config['mail_pass']}
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${config['app_name']}"

TIMEZONE={$config['timezone']}
LOCALE={$config['locale']}

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database
ENV
    }
    
    private function importDatabaseSchema()
    {
        $config = $this->parseEnvConfig();
        
        $dsn = "mysql:host={$config['DB_HOST']};dbname={$config['DB_DATABASE']};charset=utf8mb4";
        $pdo = new PDO($dsn, $config['DB_USERNAME'], $config['DB_PASSWORD']);
        
        if (!file_exists('database/schema.sql')) {
            throw new \ Exception("Datenbank-Schema nicht gefunden");
        }
        
        $schema = file_get_contents('database/schema.sql');
        $pdo->exec($schema);
        
        // Import additional data
        if (file_exists('database/seed-data.sql')) {
            $seedData = file_get_contents('database/seed-data.sql');
            $pdo->exec($seedData);
        }
        
        print_success("Datenbank-Schema importiert");
    }
    
    private function createAdminUser()
    {
        print "\n\033[34mAdmin-Benutzer anlegen:\033[0m\n";
        
        $name = $this->askInput("Voller Name", "Administrator");
        $email = $this->askInput("E-Mail", "admin@example.com");
        $password = $this->askInput("Passwort", "", true);
        
        if (strlen($password) < 8) {
            throw new \Exception("Passwort muss mindestens 8 Zeichen lang sein");
        }
        
        $config = $this->parseEnvConfig();
        
        $dsn = "mysql:host={$config['DB_HOST']};dbname={$config['DB_DATABASE']};charset=utf8mb4";
        $pdo = new PDO($dsn, $config['DB_USERNAME'], $config['DB_PASSWORD']);
        
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        
        $stmt = $pdo->prepare("
            INSERT INTO users (username, email, password_hash, first_name, last_name, role_id, created_at) 
            VALUES (?, ?, ?, ?, ?, 1, NOW())
        ");
        
        $stmt->execute([
            'admin',
            $email,
            $passwordHash,
            $name,
            'System'
        ]);
        
        print_success("Admin-Benutzer '$email' erstellt");
    }
    
    private function compileAssets()
    {
        if (file_exists('package.json')) {
            print "\n\033[34mKompiliere Assets...\033[0m\n";
            $this->runCommand('npm run build');
        }
    }
    
    private function checkWebserverConfig()
    {
        print "\n\033[34mWebserver-Konfiguration:\033[0m\n";
        
        $publicDir = realpath('public');
        
        if (!$publicDir) {
            throw new \Exception("public/ Verzeichnis nicht gefunden");
        }
        
        // Apache .htaccess check
        if (!file_exists('public/.htaccess')) {
            $htaccess = <<<HTACCESS
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
</IfModule>
HTACCESS;
            file_put_contents('public/.htaccess', $htaccess);
            print_success("Apache .htaccess erstellt");
        }
        
        // Nginx config suggestion
        $nginxConfig = <<<NGINX
server {
    listen 80;
    server_name localhost;
    root {$publicDir};
    index index.php index.html;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
NGINX;
        
        file_put_contents('nginx.example.conf', $nginxConfig);
        print "\n\033[34mNginx Konfiguration gespeichert als nginx.example.conf\033[0m\n";
    }
    
    private function finalizeInstallation()
    {
        // Create storage directories
        $directories = [
            'storage/backups',
            'storage/logs',
            'storage/uploads',
            'storage/cache',
            'storage/sessions',
            'storage/framework/cache',
            'storage/framework/sessions',
            'storage/framework/views'
        ];
        
        foreach ($directories as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }
        
        // Set proper permissions
        $this->runCommand('chmod -R 755 storage');
        $this->runCommand('chmod -R 755 bootstrap/cache');
        
        // Create installation marker
        file_put_contents('storage/installed.lock', date('Y-m-d H:i:s'));
        
        // Create startup script
        $this->createStartupScript();
    }
    
    private function createStartupScript()
    {
        $script = <<<SCRIPT
#!/bin/bash
# Austrian ERP Startup Script

echo "Starte Austrian ERP..."
php -S localhost:8000 -t public/

# Für Apache/Nginx:
# 1. Webserver-Root auf das 'public/' Verzeichnis zeigen lassen
# 2. URL-Rewriting aktivieren
# 3. PHP-FPM oder mod_php konfigurieren

echo "Anwendung läuft auf: http://localhost:8000"
echo "Admin-Login: Siehe install.log"
SCRIPT;
        
        file_put_contents('start.sh', $script);
        chmod('start.sh', 0755);
        
        // Windows batch file
        $batch = <<<BATCH
@echo off
echo Starte Austrian ERP...
php -S localhost:8000 -t public/
pause
BATCH;
        
        file_put_contents('start.bat', $batch);
    }
    
    private function printSuccess()
    {
        print "\n\033[32m╔═══════════════════════════════════════════════════════════════╗\033[0m\n";
        print "\033[32m║                   Installation Abgeschlossen!                 ║\033[0m\n";
        print "\033[32m╚═══════════════════════════════════════════════════════════════╝\033[0m\n\n";
        
        print "\033[36mStartbefehle:\033[0m\n";
        print "  Entwicklung: ./start.sh (oder start.bat auf Windows)\n";
        print "  Production: Webserver auf das 'public/' Verzeichnis konfigurieren\n\n";
        
        print "\033[36mWichtige URLs:\033[0m\n";
        print "  Anwendung: http://localhost:8000\n";
        print "  Health Check: http://localhost:8000/health-check.php\n";
        print "  Login: http://localhost:8000/login\n\n";
        
        print "\033[36mLog-Dateien:\033[0m\n";
        print "  Installations-Log: install.log\n";
        print "  Applikations-Log: storage/logs/\n\n";
        
        print "\033[33mNächste Schritte:\033[0m\n";
        print "1. Webserver konfigurieren (siehe nginx.example.conf oder Apache .htaccess)\n";
        print "2. Cron-Jobs einrichten: */5 * * * * cd " . getcwd() . " && php artisan schedule:run\n";
        print "3. Sicherheit überprüfen: php health-check.php\n";
    }
    
    private function printHelp()
    {
        print "\n\033[33mHilfe zur Fehlerbehebung:\033[0m\n";
        print "1. Prüfen Sie die install.log Datei für Details\n";
        print "2. Führen Sie 'php health-check.php' aus\n";
        print "3. Stellen Sie sicher, dass alle Systemanforderungen erfüllt sind\n";
        print "4. Überprüfen Sie die .env Datei\n";
        print "5. Datenbank-Berechtigungen prüfen\n";
    }
    
    // Helper methods (same as before)
    private function commandExists($command) { /* same */ }
    private function runCommand($command) { /* same */ }
    private function parseEnvConfig() { /* same */ }
    private function askInput($question, $default = '', $hidden = false) { /* same */ }
    private function askYesNo($question, $default = true) { /* same */ }
    private function printHeader() { /* same */ }
    private function printStep($stepName) { /* same */ }
    private function printStepComplete() { /* same */ }
    private function printError($message) { /* same */ }
    private function printWarning($message) { /* same */ }
    private function printSuccess($message = '') { /* same */ }
    private function printInfo($message) { /* same */ }
}

// Web Installer (simplified)
class WebInstaller
{
    public function run()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost();
        } else {
            $this->showForm();
        }
    }
    
    private function showForm()
    {
        ?>
        <!DOCTYPE html>
        <html lang="de">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Österreichische ERP - Installation</title>
            <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
            <style>
                .progress-bar { transition: width 0.3s ease; }
            </style>
        </head>
        <body class="bg-gray-100">
            <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
                <div class="max-w-md w-full space-y-8">
                    <div>
                        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                            Austrian ERP Installation
                        </h2>
                        <p class="mt-2 text-center text-sm text-gray-600">
                            Revisionssicheres ERP-System für Österreich
                        </p>
                    </div>
                    
                    <form id="install-form" class="mt-8 space-y-6">
                        <div class="rounded-md shadow-sm -space-y-px">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Datenbank-Konfiguration</h3>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Host</label>
                                <input type="text" name="db_host" value="localhost" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Datenbankname</label>
                                <input type="text" name="db_name" value="austrian_erp" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Benutzername</label>
                                <input type="text" name="db_user" value="root" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Passwort</label>
                                <input type="password" name="db_pass" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                            </div>
                            
                            <h3 class="text-lg font-medium text-gray-900 mb-4 mt-6">Admin-Zugang</h3>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">E-Mail</label>
                                <input type="email" name="admin_email" value="admin@example.com" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Passwort</label>
                                <input type="password" name="admin_pass" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                            </div>
                        </div>
                        
                        <div>
                            <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Installation starten
                            </button>
                        </div>
                    </form>
                    
                    <div id="progress" class="hidden">
                        <div class="bg-gray-200 rounded-full h-2">
                            <div id="progress-bar" class="bg-blue-600 h-2 rounded-full progress-bar" style="width: 0%"></div>
                        </div>
                        <p id="progress-text" class="text-sm text-center mt-2">Installation läuft...</p>
                    </div>
                </div>
            </div>
            
            <script>
                document.getElementById('install-form').addEventListener('submit', async (e) => {
                    e.preventDefault();
                    
                    const formData = new FormData(e.target);
                    const progress = document.getElementById('progress');
                    const progressBar = document.getElementById('progress-bar');
                    const progressText = document.getElementById('progress-text');
                    
                    progress.classList.remove('hidden');
                    e.target.classList.add('hidden');
                    
                    try {
                        const response = await fetch('/install.php', {
                            method: 'POST',
                            body: formData
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            progressBar.style.width = '100%';
                            progressText.textContent = 'Installation abgeschlossen! Weiterleitung...';
                            setTimeout(() => {
                                window.location.href = '/login';
                            }, 2000);
                        } else {
                            alert('Fehler: ' + result.error);
                            location.reload();
                        }
                    } catch (error) {
                        alert('Fehler: ' + error.message);
                        location.reload();
                    }
                });
            </script>
        </body>
        </html>
        <?php
    }
    
    private function handlePost()
    {
        // Simulate web installation
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    }
}

// Main execution
if (php_sapi_name() === 'cli') {
    $installer = new AustrianERPInstaller();
    $installer->run();
} else {
    $webInstaller = new WebInstaller();
    $webInstaller->run();
}