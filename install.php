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
    }
    
    public function run()
    {
        $this->printHeader();
        
        try {
            foreach ($this->steps as $step => $description) {
                $this->currentStep = $step + 1;
                $this->executeStep($description);
            }
            
            $this->printFinalSuccess();
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
            'curl' => 'cURL Extension'
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
            
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM information_schema.schemata WHERE schema_name = ?");
            $stmt->execute([$config['name']]);
            $exists = $stmt->fetchColumn() > 0;
            
            if ($exists) {
                $overwrite = $this->askYesNo("Datenbank '{$config['name']}' existiert bereits. Überschreiben?", false);
                if (!$overwrite) {
                    throw new \Exception("Installation abgebrochen");
                }
            }
            
            $pdo->exec("CREATE DATABASE IF NOT EXISTS {$config['name']} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
        } catch (\PDOException $e) {
            throw new \Exception("Datenbankverbindung fehlgeschlagen: " . $e->getMessage());
        }
        
        return $config;
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
        $dbConfig = $this->testDatabaseConnection(); // Get DB config
        
        $envContent = $this->buildEnvContent($config, $dbConfig);
        file_put_contents('.env', $envContent);
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
    
    private function buildEnvContent($config, $dbConfig)
    {
        $key = bin2hex(random_bytes(32));
        
        return "APP_NAME=\"{$config['app_name']}\"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:" . base64_encode($key) . "
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
MAIL_FROM_NAME=\"{$config['app_name']}\"

TIMEZONE={$config['timezone']}
LOCALE={$config['locale']}

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database";
    }
    
    private function importDatabaseSchema()
    {
        $config = $this->parseEnvConfig();
        
        $dsn = "mysql:host={$config['DB_HOST']};dbname={$config['DB_DATABASE']};charset=utf8mb4";
        $pdo = new PDO($dsn, $config['DB_USERNAME'], $config['DB_PASSWORD']);
        
        if (!file_exists('database/schema.sql')) {
            throw new Exception("Datenbank-Schema nicht gefunden");
        }
        
        $schema = file_get_contents('database/schema.sql');
        $pdo->exec($schema);
        
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
            throw new Exception("Passwort muss mindestens 8 Zeichen lang sein");
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
        if (!file_exists('public/.htaccess')) {
            $htaccess = '<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [QSA,L]
</IfModule>';
            file_put_contents('public/.htaccess', $htaccess);
            print_success("Apache .htaccess erstellt");
        }
    }
    
    private function finalizeInstallation()
    {
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
        
        $this->runCommand('chmod -R 755 storage');
        $this->runCommand('chmod -R 755 bootstrap/cache');
        
        file_put_contents('storage/installed.lock', date('Y-m-d H:i:s'));
        
        // Create startup scripts
        $script = '#!/bin/bash
echo "Starte Austrian ERP..."
php -S localhost:8000 -t public/
echo "Anwendung läuft auf: http://localhost:8000"';
        file_put_contents('start.sh', $script);
        chmod('start.sh', 0755);
        
        $batch = '@echo off
echo Starte Austrian ERP...
php -S localhost:8000 -t public/
pause';
        file_put_contents('start.bat', $batch);
    }
    
    // Helper methods
    private function commandExists($command)
    {
        $whereIs = shell_exec("which $command 2>/dev/null");
        return !empty($whereIs);
    }
    
    private function runCommand($command)
    {
        $this->log("Executing: $command");
        $output = shell_exec($command . ' 2>&1');
        $this->log("Output: $output");
        
        if ($output === null && !empty(shell_exec('echo $? 2>&1'))) {
            throw new Exception("Befehl fehlgeschlagen: $command");
        }
    }
    
    private function parseEnvConfig()
    {
        if (!file_exists('.env')) {
            throw new Exception('.env Datei nicht gefunden');
        }
        
        $lines = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $config = [];
        
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false && !str_starts_with($line, '#')) {
                list($key, $value) = explode('=', $line, 2);
                $config[$key] = trim($value, '"');
            }
        }
        
        return $config;
    }
    
    private function askInput($question, $default = '', $hidden = false)
    {
        echo "\033[33m$question" . ($default ? " [$default]" : "") . ": \033[0m";
        
        if ($hidden) {
            system('stty -echo');
            $input = trim(fgets(STDIN));
            system('stty echo');
            echo "\n";
        } else {
            $input = trim(fgets(STDIN));
        }
        
        return $input ?: $default;
    }
    
    private function askYesNo($question, $default = true)
    {
        $defaultText = $default ? 'Y/n' : 'y/N';
        $response = strtolower($this->askInput("$question [$defaultText]"));
        
        if (empty($response)) {
            return $default;
        }
        
        return $response === 'y' || $response === 'yes';
    }
    
    private function log($message)
    {
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($this->logFile, "[$timestamp] $message\n", FILE_APPEND);
    }
    
    private function printHeader()
    {
        echo "\n\033[34m╔═══════════════════════════════════════════════════════════════╗\n";
        echo "║                   Austrian ERP Installer                      ║\n";
        echo "║                    Version 1.0.0 - Revisionssicher          ║\n";
        echo "╚═══════════════════════════════════════════════════════════════╝\033[0m\n\n";
    }
    
    private function printStep($stepName)
    {
        echo "\033[36m[{$this->currentStep}/{$this->totalSteps}] $stepName...\033[0m ";
    }
    
    private function printStepComplete()
    {
        echo "\033[32m✓\033[0m\n";
    }
    
    private function printFinalSuccess()
    {
        echo "\n\033[32m╔═══════════════════════════════════════════════════════════════╗\033[0m\n";
        echo "\033[32m║                   Installation Abgeschlossen!                 ║\033[0m\n";
        echo "\033[32m╚═══════════════════════════════════════════════════════════════╝\033[0m\n\n";
        echo "\033[36mStartbefehle:\033[0m\n";
        echo "  Entwicklung: ./start.sh (oder start.bat auf Windows)\n";
        echo "  Production: Webserver auf das 'public/' Verzeichnis konfigurieren\n\n";
        echo "\033[36mWichtige URLs:\033[0m\n";
        echo "  Anwendung: http://localhost:8000\n";
        echo "  Health Check: http://localhost:8000/health-check.php\n";
        echo "  Login: http://localhost:8000/login\n\n";
    }
    
    private function printError($message)
    {
        echo "\n\033[31m✗ Fehler: $message\033[0m\n";
    }
    
    private function printHelp()
    {
        echo "\n\033[33mHilfe zur Fehlerbehebung:\033[0m\n";
        echo "1. Prüfen Sie die install.log Datei für Details\n";
        echo "2. Führen Sie 'php health-check.php' aus\n";
        echo "3. Stellen Sie sicher, dass alle Systemanforderungen erfüllt sind\n";
        echo "4. Überprüfen Sie die .env Datei\n";
        echo "5. Datenbank-Berechtigungen prüfen\n";
    }
}

// Run installer
if (php_sapi_name() === 'cli') {
    $installer = new AustrianERPInstaller();
    $installer->run();
}