@echo off
setlocal enabledelayedexpansion

color 0B
title Austrian ERP Installer

echo.
echo ╔═══════════════════════════════════════════════════════════════╗
echo ║                   Austrian ERP Installer                      ║
echo ║                    Version 1.0.0 - Windows                    ║
echo ╚═══════════════════════════════════════════════════════════════╝
echo.

:: Check if running as administrator
net session >nul 2>&1
if %errorLevel% == 0 (
    echo [WARNING] Bitte nicht als Administrator ausführen!
    pause
    exit /b 1
)

:: Check prerequisites
echo [INFO] Prüfe Voraussetzungen...

where php >nul 2>&1
if errorlevel 1 (
    echo [ERROR] PHP ist nicht installiert oder nicht im PATH
    echo [INFO] Bitte installieren Sie PHP von https://windows.php.net/download/
    pause
    exit /b 1
)

where composer >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Composer ist nicht installiert oder nicht im PATH
    echo [INFO] Bitte installieren Sie Composer von https://getcomposer.org/
    pause
    exit /b 1
)

where mysql >nul 2>&1
if errorlevel 1 (
    echo [ERROR] MySQL ist nicht installiert oder nicht im PATH
    echo [INFO] Bitte installieren Sie MySQL oder XAMPP
    pause
    exit /b 1
)

where npm >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Node.js/npm ist nicht installiert oder nicht im PATH
    echo [INFO] Bitte installieren Sie Node.js von https://nodejs.org/
    pause
    exit /b 1
)

:: Check PHP version
for /f "tokens=*" %%i in ('php -r "echo PHP_VERSION_ID;"') do set php_version=%%i
if %php_version% LSS 80000 (
    echo [ERROR] PHP 8.0 oder höher wird benötigt
    pause
    exit /b 1
)

echo [SUCCESS] Alle Voraussetzungen erfüllt

:: Download and extract
echo [INFO] Lade Anwendung herunter...
if not exist "austrian-erp" (
    git clone https://github.com/yourusername/austrian-erp.git austrian-erp
)

cd austrian-erp

:: Run installer
echo [INFO] Starte Installation...
php install.php

if %errorlevel% neq 0 (
    echo [ERROR] Installation fehlgeschlagen
    pause
    exit /b 1
)

echo [SUCCESS] Installation abgeschlossen!
echo [INFO] Starte Anwendung...
start http://localhost:8000
php -S localhost:8000 -t public/

pause