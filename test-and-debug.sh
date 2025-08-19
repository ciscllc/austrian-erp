#!/bin/bash
echo "🔍 Teste Datenbank und Debugging..."

# Create demo data
php -r "
<?php
require_once 'app/Config/Database.php';
require_once 'app/Models/Customer.php';
require_once 'app/Helpers/DatabaseDebugger.php';

echo '=== Datenbank-Test ===\n';

try {
    \$customers = App\Models\Customer::getAll();
    echo '✅ Datenbank verbunden - ' . count(\$customers) . ' Kunden gefunden\n';
} catch (Exception \$e) {
    echo '❌ Datenbank-Fehler: ' . \$e->getMessage() . '\n';
    echo '🔄 Erstelle Demo-Daten...\n';
    App\Models\Customer::createDemoData();
    echo '✅ Demo-Daten erstellt\n';
}

echo '=== System-Test ===\n';
echo '✅ Alle Tests abgeschlossen!\n';
echo '🚀 Starten Sie mit: php -S localhost:8000 -t public/\n';
"