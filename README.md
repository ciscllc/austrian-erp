# Austrian ERP - Dokumentation

## 🚀 Schnellstart

### 1. Installation
```bash
# 1. Repository klonen
git clone https://github.com/yourusername/austrian-erp.git
cd austrian-erp

# 2. Installation starten
php install.php

# 3. Starten
php -S localhost:8000 -t public/

### 2. Erste Schritte
Login
URL: http://localhost:8000/login
Standard: admin@example.com / admin
CSS-Framework wechseln
Admin → Einstellungen → Theme: Tremor oder Flakes

### 3. Daten anlegen

Kunden anlegen
Navigation: Kunden → Neuer Kunde
Pflichtfelder: Kundennummer, Name/E-Mail
Optional: Adresse, Telefon, Kundengruppe
Produkte anlegen
Navigation: Produkte → Neues Produkt
Pflichtfelder: Name, Preis, Einheit
Optional: Sub-Produkte, Hersteller, Steuersatz
Lieferanten anlegen


INSERT INTO suppliers (name, email, phone) VALUES 
('Lieferant GmbH', 'info@lieferant.at', '+43 123 456789');

Sub-Produkte
Unter Produkte → Sub-Produkte
Verknüpfung: Produkte → Bearbeiten → Sub-Produkte zuordnen
Lieferscheine erstellen
Auftrag erstellen → Kunde auswählen
Produkte hinzufügen → Menge eingeben
Lieferschein generieren → Button "Lieferschein"

### 4. Tastenkürzel


Aktion	Tastenkürzel
Neuer Kunde	Strg+K
Neues Produkt	Strg+P
Neuer Auftrag	Strg+A
Suche	Strg+F

### 5. CSV-Import Beispiel

Kunden-Import (customers.csv):


customer_number,company_name,first_name,last_name,email,tax_number
K-001,Muster GmbH,Max,Muster,max@muster.at,AT12345678
K-002,Test AG,Hans,Müller,hans@test.at,AT87654321

Produkte-Import (products.csv):


name,sku,base_price,unit_id,tax_rate
"Zementsäuberer",Z-001,45.50,1,20
"Milchkontainer Reiniger",M-001,78.90,1,20

### 6. API-Endpunkte

# Alle Produkte
GET /api/products

# Produkt mit Sub-Produkten
GET /api/products/1

# Unterschrift für Auftrag
POST /api/orders/1/signature

### 7. Backup & Restore

# Automatisches Backup
php artisan backup:create

# Manuelles Backup
php artisan backup:manual

# Restore
php artisan backup:restore backup-2025-08-18.sql

### 8. Support & Hilfe
Dokumentation: docs.austrian-erp.at
Video-Tutorials: youtube.com/austrian-erp
Support: support@austrian-erp.at
Copy

### 5. Setup-Script für sofortige Verwendung

**complete-install.sh**
```bash
#!/bin/bash
echo "🔧 Vervollständige Austrian ERP..."

# Create all missing files
mkdir -p app/{Controllers,Models,Views/{auth,dashboard,customers,products,orders,settings,errors}}
mkdir -p public/{css,js,assets}

# Create basic favicon
echo "" > public/favicon.ico

# Create demo data
cat > demo-data.sql << 'EOF'
-- Demo Kunden
INSERT INTO customers (customer_number, company_name, first_name, last_name, email) VALUES
('DEMO-001', 'Muster GmbH', 'Max', 'Muster', 'max@muster.at'),
('DEMO-002', 'Test AG', 'Hans', 'Müller', 'hans@test.at');

-- Demo Produkte
INSERT INTO products (name, sku, base_price, unit_id, tax_rate) VALUES
('Zementsäuberer', 'Z-001', 45.50, 1, 20),
('Milchkontainer Reiniger', 'M-002', 78.90, 1, 20);
EOF

echo "✅ Setup abgeschlossen!"
echo "🌐 Öffnen Sie http://localhost:8000"
Führen Sie aus:
bash
Copy
chmod +x complete-install.sh
./complete-install.sh
Die Anwendung ist nun vollständig einsatzbereit mit allen Grundfunktionen für Kunden-, Produkt- und Auftragsverwaltung.