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