<?php

namespace App\Helpers;

use App\Config\Database;
use Google\Client as GoogleClient;
use Google\Service\Drive;

class BackupHelper
{
    public static function create(bool $full = true): array
    {
        $timestamp = date('Y-m-d_H-i-s');
        $filename = ($full ? 'full' : 'db') . "_backup_$timestamp.sql.gz";
        
        $backupPath = storage_path("backups/$filename");
        
        if (!is_dir(dirname($backupPath))) {
            mkdir(dirname($backupPath), 0755, true);
        }
        
        // Create database dump
        $dbConfig = [
            'host' => $_ENV['DB_HOST'],
            'database' => $_ENV['DB_DATABASE'],
            'username' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD']
        ];
        
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s | gzip > %s',
            escapeshellarg($dbConfig['username']),
            escapeshellarg($dbConfig['password']),
            escapeshellarg($dbConfig['host']),
            escapeshellarg($dbConfig['database']),
            escapeshellarg($backupPath)
        );
        
        exec($command, $output, $returnCode);
        
        if ($returnCode !== 0) {
            throw new \Exception('Database backup failed');
        }
        
        // Upload to Google Drive if configured
        if (Settings::get('google_drive_enabled')) {
            self::uploadToGoogleDrive($backupPath, $filename);
        }
        
        // Upload via FTP if configured
        if (Settings::get('ftp_enabled')) {
            self::uploadViaFTP($backupPath, $filename);
        }
        
        return [
            'filename' => $filename,
            'path' => $backupPath,
            'size' => filesize($backupPath),
            'created_at' => date('Y-m-d H:i:s')
        ];
    }
    
    private static function uploadToGoogleDrive(string $filePath, string $filename)
    {
        $client = new GoogleClient();
        $client->setClientId(Settings::get('google_client_id'));
        $client->setClientSecret(Settings::get('google_client_secret'));
        $client->refreshToken(Settings::get('google_refresh_token'));
        
        $service = new Drive($client);
        
        $file = new Drive\DriveFile([
            'name' => $filename,
            'parents' => [Settings::get('google_drive_folder_id')]
        ]);
        
        $content = file_get_contents($filePath);
        $service->files->create($file, [
            'data' => $content,
            'mimeType' => 'application/gzip',
            'uploadType' => 'multipart'
        ]);
    }
    
    public static function getAll(): array
    {
        $backupDir = storage_path('backups');
        
        if (!is_dir($backupDir)) {
            return [];
        }
        
        $backups = [];
        $files = scandir($backupDir, SCANDIR_SORT_DESCENDING);
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            $path = "$backupDir/$file";
            $backups[] = [
                'filename' => $file,
                'size' => filesize($path),
                'created_at' => date('Y-m-d H:i:s', filemtime($path))
            ];
        }
        
        return $backups;
    }
}