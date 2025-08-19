<?php

namespace App\Helpers;

class DatabaseDebugger
{
    public static function log($message, $data = [])
    {
        $logFile = 'storage/logs/database.log';
        $timestamp = date('Y-m-d H:i:s');
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        
        $logEntry = sprintf(
            "[%s] %s\nFile: %s:%d\nData: %s\n\n",
            $timestamp,
            $message,
            $backtrace[0]['file'] ?? 'unknown',
            $backtrace[0]['line'] ?? 0,
            print_r($data, true)
        );
        
        file_put_contents($logFile, $logEntry, FILE_APPEND);
        
        // Console output for web
        if (php_sapi_name() === 'cli') {
            echo $logEntry;
        }
    }
    
    public static function dump($data, $label = 'Debug')
    {
        if (php_sapi_name() === 'cli') {
            echo "\n=== $label ===\n";
            var_dump($data);
            echo "\n";
        } else {
            echo "<script>console.log('$label:', " . json_encode($data) . ");</script>";
        }
    }
}